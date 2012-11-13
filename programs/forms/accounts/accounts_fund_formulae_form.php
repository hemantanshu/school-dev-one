<?php
require_once 'config.php';

require_once BASE_PATH . 'include/accounts/class.fund.php';
require_once BASE_PATH . 'include/accounts/class.allowance.php';

$allowance = new Allowance();
$fund = new Fund();

$fund->isRequestAuthorised4Form ( 'LMENUL131' );

if (isset ( $_POST ['task'] )) {
	if ($_POST ['task'] == 'insertRecord') {
		$allowanceCombinationId = $fund->setFundComputationalFormula ( $_POST ['allowanceId'], $_POST ['dependent_i'], $_POST ['value_i'], $_POST ['type_i'] );
		
		$outputArray = array ();
		$outputArray [0] = 0;
		if ($allowanceCombinationId) {
			$outputArray [0] = $allowanceCombinationId;
			$outputArray [] = $_POST ['value_i'];
			$outputArray [] = $_POST ['dependent_i'] == '' ? 'Absolute Sum' : $allowance->getAllowanceName ( $_POST ['dependent_i'] );
			$outputArray [] = $_POST ['type_i'] == 'c' ? 'Credit' : 'Debit';
		}
		echo json_encode ( $outputArray );
	} else if ($_POST ['task'] == 'search') {
		$hint = htmlentities ( trim ( $_POST ['hint'] ) );
		$search_type = htmlentities ( trim ( $_POST ['search_type'] ) );
		$allowanceId = $_POST ['allowanceId'];
		$data = $fund->getFundComputationalIds ( $allowanceId, $search_type );
		$i = 0;
		$outputArray [0] [0] = 1;
		foreach ( $data as $id ) {
			$details = $fund->getTableIdDetails ( $id );
			$outputArray [$i] [0] = $id;
			$outputArray [$i] [] = $details ['magnitude'];
			$outputArray [$i] [] = $details ['dependent_id'] == '' ? 'Absolute Sum' : $allowance->getAllowanceName ( $details ['dependent_id'] );
			$outputArray [$i] [] = $details ['type'] == 'c' ? 'Credit' : 'Debit';
			++ $i;
		}
		echo json_encode ( $outputArray );
	} else if ($_POST ['task'] == 'getRecordIdDetails') {
		$id = htmlentities ( trim ( $_POST ['id'] ) );
		$details = $fund->getTableIdDetails ( $id );
		
		$outputArray [0] = $details ['id'];
		$outputArray [] = $details ['magnitude'];
		$outputArray [] = $details ['dependent_id'] == '' ? 'Absolute Sum' : $allowance->getAllowanceName ( $details ['dependent_id'] );
		$outputArray [] = $details ['type'] == 'c' ? 'Credit' : 'Debit';
		
		$outputArray [] = $details ['last_update_date'];
		$outputArray [] = $fund->getOfficerName ( $details ['last_updated_by'] );
		$outputArray [] = $details ['creation_date'];
		$outputArray [] = $fund->getOfficerName ( $details ['created_by'] );
		$outputArray [] = $details ['active'];
		
		$outputArray [] = $details ['dependent_id'];
		$outputArray [] = $details ['type'];
		
		echo json_encode ( $outputArray );
	} elseif ($_POST ['task'] == 'updateRecord') {
		$allowanceCombinationId = $_POST ['valueId_u'];
		$details = $fund->getTableIdDetails ( $allowanceCombinationId );
		
		if($details['magnitude'] != $_POST['value_u']){
			$fund->setUpdateLog('Magnitude from '.$details['magnitude'].' to '.$_POST['value_u']);
			$fund->updateTableParameter ( 'magnitude', $_POST ['value_u'] );
		}
		if($details['dependent_id'] != $_POST['dependent_u']){
			$fund->setUpdateLog('Dependent from '.$details['dependent_id'].' to '.$_POST['dependent_u']);
			$fund->updateTableParameter ( 'dependent_id', $_POST ['dependent_u'] );
		}
		if($details['type'] != $_POST['type_u']){
			$fund->setUpdateLog('Type from '.$details['type'].' to '.$_POST['type_u']);
			$fund->updateTableParameter ( 'type', $_POST ['type_u'] );
		}	
		$formulaeId = $fund->commitFundCombinationDetailsUpdate ( $allowanceCombinationId );
				
		$outputArray [0] = $allowanceCombinationId;
		$outputArray [] = $_POST ['value_u'];
		$outputArray [] = $_POST ['dependent_u'] == '' ? 'Absolute Sum' : $allowance->getAllowanceName ( $_POST ['dependent_u'] );
		$outputArray [] = $_POST ['type_u'] == 'c' ? 'Credit' : 'Debit';
		
		
		echo json_encode ( $outputArray );
	} elseif($_POST['task'] == 'fetchFundIds'){
		$outputArray = $fund->getFundAllowanceIds();
		echo json_encode($outputArray);
	}elseif ($_POST ['task'] == 'dropRecord') {
		$id = htmlentities ( trim ( $_POST ['id'] ) );
		$fund->dropFundCombinationDetails($id);
		$outputArray [0] = $id;
		echo json_encode ( $outputArray );
	} elseif ($_POST ['task'] == 'activateRecord') {
		$id = htmlentities ( trim ( $_POST ['id'] ) );
		$fund->activateFundCombinationDetails($id);
		$outputArray [0] = $id;
		echo json_encode ( $outputArray );
	} else {
		$outputArray [0] = 0;
		echo json_encode ( $outputArray );
	}
}
?>