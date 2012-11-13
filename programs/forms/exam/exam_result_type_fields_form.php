<?php
require_once 'config.php';
require_once BASE_PATH . 'include/exam/class.resultType.php';

$resultType = new ResultType();
$resultType->isRequestAuthorised4Form ( 'LMENUL149' );

if (isset ( $_POST ['task'] )) {
	if ($_POST ['task'] == 'insertRecord') {		
		$resultTypeId = $resultType->setResultTypeFields($_POST['resultTypeId'], $_POST['displayName'], $_POST['url'], $_POST['vurl'], $_POST['internalCode']);		
		if($resultTypeId){
			$outputArray[0] = $resultTypeId;
			$outputArray[] = $_POST['displayName'];
			$outputArray[] = $_POST['internalCode'];
		}else
			$outputArray[0] = 0;		 
		echo json_encode ( $outputArray );
	} else if ($_POST ['task'] == 'search') {
		$search_type = $_POST['search_type'];
		$hint = $_POST['hint'];
		$resultTypeId = $_POST['resultTypeId'];
		$resultTypeIds = $resultType->getResultTypeFields($resultTypeId, $hint, $search_type);
		$outputArray[0][0] = 1;
		$i = 0;
		foreach ($resultTypeIds as $resultTypeId){
			$details = $resultType->getTableIdDetails($resultTypeId);
			$outputArray[$i][0] = $resultTypeId;
			$outputArray[$i][] = $details['display_name'];
			$outputArray[$i][] = $details['code'];
			++$i;
		}
		echo json_encode ( $outputArray );
	} else if ($_POST ['task'] == 'getRecordIdDetails') {
		$id = $_POST ['id'];				
		$details = $resultType->getTableIdDetails ( $id );		
			
		$outputArray [0] = $details ['id'];
		$outputArray [] = $details['display_name'];
		$outputArray [] = $details['submission_url'];
		$outputArray [] = $details['code'];
		
		$outputArray [] = $details ['last_update_date'];
		$outputArray [] = $resultType->getOfficerName ( $details ['last_updated_by'] );
		$outputArray [] = $details ['creation_date'];
		$outputArray [] = $resultType->getOfficerName ( $details ['created_by'] ); //10
		
		$outputArray [] = $details ['active'];
		
		$outputArray [] = $details['view_url'];
		
		echo json_encode ( $outputArray );
	} elseif ($_POST ['task'] == 'updateRecord') {		
		$id = $_POST['valueId_u'];
		$details = $resultType->getTableIdDetails($id);
		
		if($details['display_name'] != $_POST['displayName_u']){
			$resultType->setUpdateLog('Name from '.$details['display_name'].' to '.$_POST['displayName_u']);
			$resultType->updateTableParameter('display_name', $_POST['displayName_u']);
		}
		if($details['submission_url'] != $_POST['url_u']){
			$resultType->setUpdateLog('Submission Url from '.$details['submission_url'].' to '.$_POST['url_u']);
			$resultType->updateTableParameter('submission_url', $_POST['url_u']);
		}
		if($details['view_url'] != $_POST['vurl_u']){
			$resultType->setUpdateLog('View Url from '.$details['view_url'].' to '.$_POST['vurl_u']);
			$resultType->updateTableParameter('view_url', $_POST['vurl_u']);
		}
		if($details['code'] != $_POST['internalCode_u']){
			$resultType->setUpdateLog('Code from '.$details['code'].' to '.$_POST['internalCode_u']);
			$resultType->updateTableParameter('code', $_POST['internalCode_u']);
		}	
			
		if($resultType->commitResultTypeFieldsUpdate($id)){
			$outputArray[0] = $id;
			$outputArray[] = $_POST['displayName_u'];
			$outputArray[] = $_POST['internalCode_u'];								
		}else{
			$outputArray[] = 0;
		}		
		echo json_encode ( $outputArray );
	
	} elseif ($_POST ['task'] == 'dropRecord') {
		$id = htmlentities ( trim ( $_POST ['id'] ) );
		$resultType->dropResultTypeField($id);
		$outputArray [0] = $id;
		echo json_encode ( $outputArray );
	} elseif ($_POST ['task'] == 'activateRecord') {
		$id = htmlentities ( trim ( $_POST ['id'] ) );
		$resultType->activateResultTypeField($id);
		$outputArray [0] = $id;
		echo json_encode ( $outputArray );
	} else {
		$outputArray [0] = 0;
		echo json_encode ( $outputArray );
	}
}
?>