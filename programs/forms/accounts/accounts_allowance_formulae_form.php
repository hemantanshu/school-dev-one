<?php
require_once 'config.php';

require_once BASE_PATH . 'include/global/class.options.php';
require_once BASE_PATH . 'include/utility/class.address.php';
require_once BASE_PATH . 'include/accounts/class.accounts.php';
require_once BASE_PATH . 'include/accounts/class.accountHead.php';

$options = new options ();
$address = new address ();
$accounts = new Accounts ();
$accountHead = new AccountHead ();

$options->isRequestAuthorised4Form ( 'LMENUL111' );

if (isset ( $_POST ['task'] )) {
	if ($_POST ['task'] == 'insertRecord') {
		$allowanceCombinationId = $accounts->setAllowanceComputationalFormula ( $_POST ['allowanceId'], $_POST ['dependent_i'], $_POST ['value_i'], $_POST ['type_i'] );
		
		$outputArray = array ();
		$outputArray [0] = 0;
		if ($allowanceCombinationId) {
			$outputArray [0] = $allowanceCombinationId;
			$outputArray [] = $_POST ['value_i'];
			$outputArray [] = $_POST ['dependent_i'] == '' ? 'Absolute Sum' : $accounts->getAllowanceName ( $_POST ['dependent_i'] );
			$outputArray [] = $_POST ['type_i'] == 'c' ? 'Credit' : 'Debit';
		}
		echo json_encode ( $outputArray );
	} else if ($_POST ['task'] == 'search') {
		$hint = htmlentities ( trim ( $_POST ['hint'] ) );
		$search_type = htmlentities ( trim ( $_POST ['search_type'] ) );
		$allowanceId = $_POST ['allowanceId'];
		$data = $accounts->getAllowanceComputationalIds ( $allowanceId, $search_type );
		$i = 0;
		$outputArray [0] [0] = 1;
		foreach ( $data as $id ) {
			$details = $accounts->getTableIdDetails ( $id );
			$outputArray [$i] [0] = $id;
			$outputArray [$i] [] = $details ['magnitude'];
			$outputArray [$i] [] = $details ['dependent_id'] == '' ? 'Absolute Sum' : $accounts->getAllowanceName ( $details ['dependent_id'] );
			$outputArray [$i] [] = $details ['type'] == 'c' ? 'Credit' : 'Debit';
			++ $i;
		}
		echo json_encode ( $outputArray );
	} else if ($_POST ['task'] == 'getRecordIdDetails') {
		$id = htmlentities ( trim ( $_POST ['id'] ) );
		$details = $accounts->getTableIdDetails ( $id );
		
		$outputArray [0] = $details ['id'];
		$outputArray [] = $details ['magnitude'];
		$outputArray [] = $details ['dependent_id'] == '' ? 'Absolute Sum' : $accounts->getAllowanceName ( $details ['dependent_id'] );
		$outputArray [] = $details ['type'] == 'c' ? 'Credit' : 'Debit';
		
		$outputArray [] = $details ['last_update_date'];
		$outputArray [] = $accounts->getOfficerName ( $details ['last_updated_by'] );
		$outputArray [] = $details ['creation_date'];
		$outputArray [] = $accounts->getOfficerName ( $details ['created_by'] );
		$outputArray [] = $details ['active'];
		
		$outputArray [] = $details ['dependent_id'];
		$outputArray [] = $details ['type'];
		
		echo json_encode ( $outputArray );
	} elseif ($_POST ['task'] == 'updateRecord') {
		$allowanceCombinationId = $_POST ['valueId_u'];
		$details = $accounts->getTableIdDetails ( $allowanceCombinationId );
		
		if($details['magnitude'] != $_POST['value_u']){
			$accounts->setUpdateLog('Magnitude from '.$details['magnitude'].' to '.$_POST['value_u']);
			$accounts->updateTableParameter ( 'magnitude', $_POST ['value_u'] );
		}
		if($details['dependent_id'] != $_POST['dependent_u']){
			$accounts->setUpdateLog('Dependent from '.$details['dependent_id'].' to '.$_POST['dependent_u']);
			$accounts->updateTableParameter ( 'dependent_id', $_POST ['dependent_u'] );
		}
		if($details['type'] != $_POST['type_u']){
			$accounts->setUpdateLog('Type from '.$details['type'].' to '.$_POST['type_u']);
			$accounts->updateTableParameter ( 'type', $_POST ['type_u'] );
		}		
		$formulaeId = $accounts->commitAllowanceCombinationDetailsUpdate ( $allowanceCombinationId );
		if ($formulaeId && $details ['allowance_id'] != 'AALDT0') {
			$accounts->updateBulkNonOverRiddenEmployeeAllowanceAmount ( $details ['allowance_id'] );
		}
		
		$outputArray [0] = $allowanceCombinationId;
		$outputArray [] = $_POST ['value_u'];
		$outputArray [] = $_POST ['dependent_u'] == '' ? 'Absolute Sum' : $accounts->getAllowanceName ( $_POST ['dependent_u'] );
		$outputArray [] = $_POST ['type_u'] == 'c' ? 'Credit' : 'Debit';
		
		
		echo json_encode ( $outputArray );
	} elseif ($_POST ['task'] == 'fetchAllowanceOptions') {
		$id = $_POST ['allowanceId'];
		
		$allowanceIds = $accounts->getAllowanceIds ( 1 );
		$i = 0;
		$outputArray [0] [0] = 1;
		foreach ( $allowanceIds as $allowanceId ) {
			if ($allowanceId == $id)
				continue;
			$outputArray [$i] [0] = $allowanceId;
			$outputArray [$i] [] = $accounts->getAllowanceName ( $allowanceId );
			++ $i;
		}
		
		echo json_encode ( $outputArray );
	} elseif ($_POST ['task'] == 'fetchAllowanceDetails') {
		$id = $_POST ['allowanceId'];
		$month = $_POST['month'];
		
		$details = $accounts->getTableIdDetails ( $id );
		$accountHeadDetails = $accounts->getTableIdDetails ( $details ['accounthead_id'] );
		
		$outputArray [0] = $details ['allowance_name'];
		$outputArray [] = $accountHeadDetails ['accounthead_name'];
		$outputArray [] = $accountHeadDetails ['account_type'] == 'c' ? "<font class='green'>Credit</font>" : "<font class='red'>Debit</font>";
		$outputArray [] = $accountHeadDetails ['account_type'];
		if($month != ''){
			$monthPart = substr($month, 4, 2);
			$yearPart = substr($month, 0, 4);
			$outputArray[] = date('F', mktime(0, 0, 0, $monthPart, 15, $yearPart))." ,  ".$yearPart;
		}
		
		echo json_encode ( $outputArray );
	} elseif ($_POST ['task'] == 'dropRecord') {
		$id = htmlentities ( trim ( $_POST ['id'] ) );
		$accounts->dropAllowanceDetails ( $id );
		$outputArray [0] = $id;
		echo json_encode ( $outputArray );
	} elseif ($_POST ['task'] == 'activateRecord') {
		$id = htmlentities ( trim ( $_POST ['id'] ) );
		$accounts->activateAllowanceDetails ( $id );
		$outputArray [0] = $id;
		echo json_encode ( $outputArray );
	} else {
		$outputArray [0] = 0;
		echo json_encode ( $outputArray );
	}
}
?>