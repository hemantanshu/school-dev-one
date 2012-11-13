<?php
require_once 'config.php';
require_once BASE_PATH . 'include/exam/class.resultType.php';

$resultType = new ResultType();
$resultType->isRequestAuthorised4Form ( 'LMENUL148' );

if (isset ( $_POST ['task'] )) {
	if ($_POST ['task'] == 'insertRecord') {
		$resultTypeId = $resultType->setResultType($_POST['resultType'], $_POST['resultOrder'], $_POST['resultDescription']);		
		if($resultTypeId){
			$outputArray[0] = $resultTypeId;
			$outputArray[] = $_POST['resultType'];
			$outputArray[] = $_POST['resultOrder'];
			$outputArray[] = $_POST['resultDescription'];
		}else
			$outputArray[0] = 0;		 
		echo json_encode ( $outputArray );
	} else if ($_POST ['task'] == 'search') {
		$search_type = $_POST['search_type'];
		$hint = $_POST['hint'];
		$resultTypeIds = $resultType->getResultType($hint, $search_type);
		$outputArray[0][0] = 1;
		$i = 0;
		foreach ($resultTypeIds as $resultTypeId){
			$details = $resultType->getTableIdDetails($resultTypeId);
			$outputArray[$i][0] = $resultTypeId;
			$outputArray[$i][] = $details['result_type'];
			$outputArray[$i][] = $details['result_order'];
			$outputArray[$i][] = $details['result_description'];
			++$i;
		}
		echo json_encode ( $outputArray );
	} else if ($_POST ['task'] == 'getRecordIdDetails') {
		$id = $_POST ['id'];				
		$details = $resultType->getTableIdDetails ( $id );		
			
		$outputArray [0] = $details ['id'];
		$outputArray [] = $details['result_type'];
		$outputArray [] = $details['result_order'];
		$outputArray [] = $details['result_description'];
		
		$outputArray [] = $details ['last_update_date'];
		$outputArray [] = $resultType->getOfficerName ( $details ['last_updated_by'] );
		$outputArray [] = $details ['creation_date'];
		$outputArray [] = $resultType->getOfficerName ( $details ['created_by'] ); //10
		
		$outputArray [] = $details ['active'];
		
		echo json_encode ( $outputArray );
	} elseif ($_POST ['task'] == 'updateRecord') {		
		$id = $_POST['valueId_u'];
		$details = $resultType->getTableIdDetails($id);
		
		if($details['result_type'] != $_POST['resultType_u']){
			$resultType->setUpdateLog('Name from '.$details['result_type'].' to '.$_POST['resultType_u']);
			$resultType->updateTableParameter('result_type', $_POST['resultType_u']);
		}
		if($details['result_order'] != $_POST['resultOrder_u']){
			$resultType->setUpdateLog('Order from '.$details['result_order'].' to '.$_POST['resultOrder_u']);
			$resultType->updateTableParameter('result_order', $_POST['resultOrder_u']);
		}
		if($details['result_description'] != $_POST['resultDescription_u']){
			$resultType->setUpdateLog('from '.$details['result_description'].' to '.$_POST['resultDescription_u']);
			$resultType->updateTableParameter('result_description', $_POST['resultDescription_u']);
		}	
			
		if($resultType->commitResultTypeUpdate($id)){
			$outputArray[0] = $id;
			$outputArray[] = $_POST['resultType_u'];
			$outputArray[] = $_POST['resultOrder_u'];
			$outputArray[] = $_POST['decription_u'];								
		}else{
			$outputArray[] = 0;
		}		
		echo json_encode ( $outputArray );
	
	} elseif ($_POST ['task'] == 'dropRecord') {
		$id = htmlentities ( trim ( $_POST ['id'] ) );
		$resultType->dropResultType($id);
		$outputArray [0] = $id;
		echo json_encode ( $outputArray );
	} elseif ($_POST ['task'] == 'activateRecord') {
		$id = htmlentities ( trim ( $_POST ['id'] ) );
		$resultType->activateResultType($id);
		$outputArray [0] = $id;
		echo json_encode ( $outputArray );
	} else {
		$outputArray [0] = 0;
		echo json_encode ( $outputArray );
	}
}
?>