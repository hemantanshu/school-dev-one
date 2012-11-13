<?php
require_once 'config.php';

require_once BASE_PATH . 'include/global/class.options.php';
require_once BASE_PATH . 'include/utility/class.address.php';
require_once BASE_PATH . 'include/accounts/class.accountHead.php';

$options = new options ();
$address = new address ();
$accountHead = new AccountHead ();

$options->isRequestAuthorised4Form ( 'LMENUL109' );

if (isset ( $_POST ['task'] )) {
	if ($_POST ['task'] == 'insertRecord') {
		$accountHeadId = $accountHead->setAccountHeadDetails ( $_POST ['accountHeadName'], $_POST ['accountType'], $_POST ['displayOrder'] );
		
		$outputArray = array ();
		$outputArray [0] = 0;
		if ($accountHeadId) {
			$outputArray [0] = $accountHeadId;
			$outputArray [] = $_POST ['accountHeadName'];
			$outputArray [] = $_POST ['accountType'] == 'c' ? 'Credit' : 'Debit';
			$outputArray [] = $_POST ['displayOrder'];
		}
		echo json_encode ( $outputArray );
	} else if ($_POST ['task'] == 'search') {
		$hint = htmlentities ( trim ( $_POST ['hint'] ) );
		$search_type = htmlentities ( trim ( $_POST ['search_type'] ) );
		$data = $accountHead->getAccountHeadNameSearchIds ( $hint, $search_type );
		$i = 0;
		$outputArray [0] [0] = 1;
		foreach ( $data as $id ) {
			$details = $accountHead->getTableIdDetails ( $id );
			$outputArray [$i] [0] = $details ['id'];
			$outputArray [$i] [] = $details ['accounthead_name'];
			$outputArray [$i] [] = $details ['account_type'] == 'c' ? 'Credit' : 'Debit';
			$outputArray [$i] [] = $details ['display_order'];
			++ $i;
		}
		echo json_encode ( $outputArray );
	} else if ($_POST ['task'] == 'getRecordIdDetails') {
		$id = htmlentities ( trim ( $_POST ['id'] ) );
		$details = $accountHead->getTableIdDetails ( $id );
		
		$outputArray [0] = $details ['id'];
		$outputArray [] = $details ['accounthead_name'];
		$outputArray [] = $details ['account_type'];
		$outputArray [] = $details ['account_type'] == 'c' ? 'Credit' : 'Debit';
		
		$outputArray [] = $details ['last_update_date'];
		$outputArray [] = $accountHead->getOfficerName ( $details ['last_updated_by'] );
		$outputArray [] = $details ['creation_date'];
		$outputArray [] = $accountHead->getOfficerName ( $details ['created_by'] );
		$outputArray [] = $details ['active'];
		
		$outputArray [] = $details ['display_order'];
		
		echo json_encode ( $outputArray );
	} elseif ($_POST ['task'] == 'updateRecord') {
		$accountHeadId = $_POST ['valueId_u'];
		$details = $accountHead->getTableIdDetails ( $accountHeadId );
		
		if($details['accounthead_name'] != $_POST['accountHeadName_u']){
			$accountHead->setUpdateLog('Name from '.$details['accounthead_name'].' to '.$_POST['accountHeadName_u']);
			$accountHead->updateTableParameter ( 'accounthead_name', $_POST ['accountHeadName_u'] );
		}
		if($details['account_type'] != $_POST['accountType_u']){
			$accountHead->setUpdateLog('Account Type from '.$details['account_type'].' to '.$_POST['accountType_u']);
			$accountHead->updateTableParameter ( 'account_type', $_POST ['accountType_u'] );
		}
		if($details['display_order'] != $_POST['displayOrder_u']){
			$accountHead->setUpdateLog('Order from '.$details['display_order'].' to '.$_POST['displayOrder_u']);
			$accountHead->updateTableParameter ( 'display_order', $_POST ['displayOrder_u'] );
		}		
		$accountHead->commitAccountHeadDetailsUpdate ( $accountHeadId );
		
		$outputArray [0] = $accountHeadId;
		$outputArray [] = $_POST ['accountHeadName_u'];
		$outputArray [] = $_POST ['accountType_u'] == 'c' ? 'Credit' : 'Debit';
		$outputArray [] = $_POST ['displayOrder_u'];
		
		echo json_encode ( $outputArray );
	} elseif ($_POST ['task'] == 'dropRecord') {
		$id = htmlentities ( trim ( $_POST ['id'] ) );
		$accountHead->dropAccountHeadDetails ( $id );
		$outputArray [0] = $id;
		echo json_encode ( $outputArray );
	} elseif ($_POST ['task'] == 'activateRecord') {
		$id = htmlentities ( trim ( $_POST ['id'] ) );
		$accountHead->activateAccountHeadDetails ( $id );
		$outputArray [0] = $id;
		echo json_encode ( $outputArray );
	} else {
		$outputArray [0] = 0;
		echo json_encode ( $outputArray );
	}
}
?>