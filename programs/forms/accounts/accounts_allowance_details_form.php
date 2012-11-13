<?php
require_once 'config.php';

require_once BASE_PATH . 'include/global/class.options.php';
require_once BASE_PATH . 'include/utility/class.address.php';
require_once BASE_PATH . 'include/accounts/class.allowance.php';
require_once BASE_PATH . 'include/accounts/class.accountHead.php';

$options = new options ();
$address = new address ();
$allowance = new Allowance ();
$accountHead = new AccountHead ();

$options->isRequestAuthorised4Form ( 'LMENUL110' );

if (isset ( $_POST ['task'] )) {
	if ($_POST ['task'] == 'insertRecord') {
		$allowanceId = $allowance->setAllowanceDetails ( $_POST ['allowanceName'], $_POST ['accountHeadName'], $_POST ['allowUpdate'], $_POST ['allowRound'], $_POST ['allowFraction'], $_POST ['contributoryFund'] );
		
		$outputArray = array ();
		$outputArray [0] = 0;
		if ($allowanceId) {
			$outputArray [0] = $allowanceId;
			$outputArray [] = $_POST ['allowanceName'];
		}
		echo json_encode ( $outputArray );
	} else if ($_POST ['task'] == 'search') {
		$hint = htmlentities ( trim ( $_POST ['hint'] ) );
		$search_type = htmlentities ( trim ( $_POST ['search_type'] ) );
		$data = $allowance->getAllowanceNameSearchIds ( $hint, $search_type );
		$i = 0;
		$outputArray [0] [0] = 1;
		foreach ( $data as $id ) {
			$details = $allowance->getTableIdDetails ( $id );
			$outputArray [$i] [0] = $details ['id'];
			$outputArray [$i] [] = $details ['allowance_name'];
			++ $i;
		}
		echo json_encode ( $outputArray );
	} else if ($_POST ['task'] == 'getRecordIdDetails') {
		$id = htmlentities ( trim ( $_POST ['id'] ) );
		$details = $allowance->getTableIdDetails ( $id );
		
		$outputArray [0] = $details ['id'];
		$outputArray [] = $details ['allowance_name'];
		$outputArray [] = $accountHead->getAccountHeadName ( $details ['accounthead_id'] );
		$outputArray [] = $details ['allow_update'] == 'y' ? '<font class="green">Enable</font>' : '<font class="red">Disable</font>';
		$outputArray [] = $details ['round_off'] == 'y' ? '<font class="green">Enable</font>' : '<font class="red">Disable</font>';
		$outputArray [] = $details ['fraction'] == 'y' ? '<font class="green">Enable</font>' : '<font class="red">Disable</font>';
		$outputArray [] = $details ['contribution'] == 'y' ? '<font class="green">Yes</font>' : '<font class="red">No</font>';
		
		$outputArray [] = '';
		
		$outputArray [] = $details ['last_update_date'];
		$outputArray [] = $allowance->getOfficerName ( $details ['last_updated_by'] );
		$outputArray [] = $details ['creation_date'];
		$outputArray [] = $allowance->getOfficerName ( $details ['created_by'] );
		$outputArray [] = $details ['active'];
		
		$outputArray [] = $details ['accounthead_id'];
		$outputArray [] = $details ['allow_update'];
		$outputArray [] = $details ['round_off'];
		$outputArray [] = $details ['fraction'];
		$outputArray [] = $details ['contribution'];
		
		echo json_encode ( $outputArray );
	} elseif ($_POST ['task'] == 'updateRecord') {
		$allowanceId = $_POST ['valueId_u'];
		$details = $allowance->getTableIdDetails ( $allowanceId );
		
		if($details['allowance_name'] != $_POST['allowanceName_u']){
			$allowance->setUpdateLog('Name from '.$details['allowance_name'].' to '.$_POST['allowanceName_u']);
			$allowance->updateTableParameter ( 'allowance_name', $_POST ['allowanceName_u'] );
		}
		if($details['accounthead_id'] != $_POST['accountHeadName_u']){
			$allowance->setUpdateLog('Accounthead from '.$details['accounthead_id'].' to '.$_POST['accountHeadName_u']);
			$allowance->updateTableParameter ( 'accounthead_id', $_POST ['accountHeadName_u'] );
		}
		if($details['allow_update'] != $_POST['allowUpdate_u']){
			$allowance->setUpdateLog('Employee Update from '.$details['allow_update'].' to '.$_POST['allowUpdate_u']);
			$allowance->updateTableParameter ( 'allow_update', $_POST ['allowUpdate_u'] );
		}
		if($details['round_off'] != $_POST['allowRound_u']){
			$allowance->setUpdateLog('Round Off from '.$details['round_off'].' to '.$_POST['allowRound_u']);
			$allowance->updateTableParameter ( 'round_off', $_POST ['allowRound_u'] );
		}
		if($details['fraction'] != $_POST['allowFraction_u']){
			$allowance->setUpdateLog('Fration from '.$details['fraction'].' to '.$_POST['allowFraction_u']);
			$allowance->updateTableParameter ( 'fraction', $_POST ['allowFraction_u'] );
		}
		if($details['contribution'] != $_POST['contributoryFund_u']){
			$allowance->setUpdateLog('Employee Fund from '.$details['contribution'].' to '.$_POST['contributoryFund_u']);
			$allowance->updateTableParameter ( 'contribution', $_POST ['contributoryFund_u'] );
		}
		$allowance->commitAllowanceDetailsUpdate($allowanceId);
		
		$outputArray [0] = $allowanceId;
		$outputArray [] = $_POST ['allowanceName_u'];
		
		echo json_encode ( $outputArray );
	} elseif($_POST['task'] == 'fetchAccountHead'){
		$accountHeadIds = $accountHead->getAccountHeadIds(1);
		$i = 0;
		$outputArray[0][0] = 1;
		foreach ($accountHeadIds as $accountHeadId){
			$outputArray[$i][0] = $accountHeadId;
			$outputArray[$i][] = $accountHead->getAccountHeadName($accountHeadId);
			++$i;	
		}
		
		echo json_encode($outputArray);		
	}elseif ($_POST ['task'] == 'dropRecord') {
		$id = htmlentities ( trim ( $_POST ['id'] ) );
		$allowance->dropAllowanceDetails ( $id );
		$outputArray [0] = $id;
		echo json_encode ( $outputArray );
	} elseif ($_POST ['task'] == 'activateRecord') {
		$id = htmlentities ( trim ( $_POST ['id'] ) );
		$allowance->activateAllowanceDetails ( $id );
		$outputArray [0] = $id;
		echo json_encode ( $outputArray );
	} else {
		$outputArray [0] = 0;
		echo json_encode ( $outputArray );
	}
}
?>