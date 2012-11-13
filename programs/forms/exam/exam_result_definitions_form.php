<?php
require_once 'config.php';
require_once BASE_PATH . 'include/exam/class.result.php';
require_once BASE_PATH . 'include/global/class.session.php';

$result = new Result ();
$session = new Session ();
$result->isRequestAuthorised4Form ( 'LMENUL80' );

if (isset ( $_POST ['task'] )) {
	if ($_POST ['task'] == 'insertRecord') {
		if ($session->isSessionEditable ( $_POST ['sessionId'] )) {
			$resultId = $result->setResultDefinition ( $_POST ['sessionId'], $_POST ['resultName'], $_POST ['displayName'], $_POST ['resultDescription'], $_POST['markingType_i'] );
			if ($resultId) {
				$outputArray [0] = $resultId;
				$outputArray [] = $_POST ['resultName'];
				$outputArray [] = $_POST ['resultDescription'];
			}
		} else
			$outputArray [0] = 0;
		echo json_encode ( $outputArray );
	} else if ($_POST ['task'] == 'search') {
		$search_type = $_POST ['search_type'];
		$sessionId = $_POST ['sessionId'];
		$editable = 1;
		if (! $session->isSessionEditable ( $sessionId ))
			$editable = 0;
		
		$resultIds = $result->getResultDefinitions ( $sessionId, $search_type );
		$outputArray [0] [0] = 1;
		$i = 0;
		foreach ( $resultIds as $resultId ) {
			$details = $result->getTableIdDetails ( $resultId );
			$outputArray [$i] [0] = $resultId;
			$outputArray [$i] [] = $details ['result_name'];
			$outputArray [$i] [] = $details ['description'];
			$outputArray [$i] [] = $editable;
			++ $i;
		}
		echo json_encode ( $outputArray );
	} else if ($_POST ['task'] == 'getRecordIdDetails') {
		$id = $_POST ['id'];
		$details = $result->getTableIdDetails ( $id );
		$editable = 1;
		if (! $session->isSessionEditable ( $details ['session_id'] ))
			$editable = 0;
		
		$outputArray [0] = $details ['id'];
		$outputArray [] = $details ['result_name'];
		$outputArray [] = $details ['description'];
		$outputArray [] = $details ['display_name'];
		$outputArray [] = $details ['active'];
		$outputArray [] = $details ['last_update_date'];
		$outputArray [] = $result->getOfficerName ( $details ['last_updated_by'] );
		$outputArray [] = $details ['creation_date'];
		$outputArray [] = $result->getOfficerName ( $details ['created_by'] ); // 10
		$outputArray [] = $editable;
		
		$outputArray[] = $details['result_type'];
		$details = $result->getTableIdDetails($details['result_type']);
		$outputArray[] = $details['result_type'];
		
		echo json_encode ( $outputArray );
	} elseif ($_POST ['task'] == 'updateRecord') {
		$id = $_POST ['valueId_u'];
		$sessionId = $_POST ['sessionId'];
		if ($session->isSessionEditable ( $sessionId )) {
			$details = $result->getTableIdDetails ( $id );
			if($details['result_name'] != $_POST['resultName_u']){
				$result->setUpdateLog('Name from '.$details['result_name'].' to '.$_POST['resultName_u']);
				$result->updateTableParameter ( 'result_name', $_POST ['resultName_u'] );
			}
			if($details['description'] != $_POST['resultDescription_u']){
				$result->setUpdateLog('Description from '.$details['description'].' to '.$_POST['resultDescription_u']);
				$result->updateTableParameter ( 'description', $_POST ['resultDescription_u'] );
			}
			if($details['display_name'] != $_POST['displayName_u']){
				$result->setUpdateLog('Display Name from '.$details['display_name'].' to '.$_POST['displayName_u']);
				$result->updateTableParameter ( 'display_name', $_POST ['displayName_u'] );
			}
			if($details['result_type'] != $_POST['markingType_u']){
				$result->setUpdateLog('Result Type from '.$details['result_type'].' to '.$_POST['markingType_u']);
				$result->updateTableParameter ( 'result_type', $_POST ['markingType_u'] );
			}
			if ($result->commitResultTypeUpdate ( $id )) {
				$outputArray [0] = $id;
				$outputArray [] = $_POST ['resultName_u'];
				$outputArray [] = $_POST ['resultDescription_u'];
			}
		} else {
			$outputArray [] = 0;
		}
		echo json_encode ( $outputArray );
	
	} elseif ($_POST ['task'] == 'dropRecord') {
		$id = htmlentities ( trim ( $_POST ['id'] ) );
		$result->dropResultType ( $id );
		$outputArray [0] = $id;
		echo json_encode ( $outputArray );
	} elseif ($_POST ['task'] == 'activateRecord') {
		$id = htmlentities ( trim ( $_POST ['id'] ) );
		$result->activateResultType ( $id );
		$outputArray [0] = $id;
		echo json_encode ( $outputArray );
	} else {
		$outputArray [0] = 0;
		echo json_encode ( $outputArray );
	}
}
?>