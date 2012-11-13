<?php
require_once 'config.php';
require_once BASE_PATH . 'include/exam/class.examination.php';
require_once BASE_PATH . 'include/global/class.session.php';

$examination = new Examination();
$session = new Session();
$examination->isRequestAuthorised4Form ( 'LMENUL68' );

if (isset ( $_POST ['task'] )) {
	if ($_POST ['task'] == 'insertRecord') {
		if($session->isSessionEditable($_POST['sessionId'])){
			$examinationId = $examination->setExaminationType($_POST['examName'], $_POST['sessionId'], $_POST['examDescription'], $_POST['startDate'], $_POST['endDate']);
			if($examinationId){
				$outputArray[0] = $examinationId;
				$outputArray[] = $_POST['examName'];
				$outputArray[] = $_POST['examDescription'];
			}	
		}
		else
			$outputArray[0] = 0;		 
		echo json_encode ( $outputArray );
	} else if ($_POST ['task'] == 'search') {
		$search_type = $_POST['search_type'];
		$sessionId = $_POST['sessionId'];
		$editable = 1;
		if(!$session->isSessionEditable($sessionId))
			$editable = 0;
		//$examinationIds = $examination->getExaminationType($sessionId, $search_type);
		$examinationIds = $examination->getExaminationRecords($sessionId, $search_type);
		$outputArray[0][0] = 1;
		$i = 0;
		foreach ($examinationIds as $examinationId){
			$details = $examination->getTableIdDetails($examinationId);
			$outputArray[$i][0] = $examinationId;
			$outputArray[$i][] = $details['examination_name'];
			$outputArray[$i][] = $details['examination_description'];
			$outputArray[$i][] = $editable;
			++$i;
		}
		echo json_encode ( $outputArray );
	} else if ($_POST ['task'] == 'getRecordIdDetails') {
		$id = $_POST ['id'];				
		$details = $examination->getTableIdDetails ( $id );
		$editable = 1;
		if(!$session->isSessionEditable($details['session_id']))
			$editable = 0;		
			
		$outputArray [0] = $details ['id'];
		$outputArray [] = $details['examination_name'];
		$outputArray [] = $details['examination_description'];
		$outputArray [] = $examination->getDisplayDate($details['start_date']);
		$outputArray [] = $examination->getDisplayDate($details['end_date']);
		$outputArray [] = $details ['last_update_date'];
		$outputArray [] = $examination->getOfficerName ( $details ['last_updated_by'] );
		$outputArray [] = $details ['creation_date'];
		$outputArray [] = $examination->getOfficerName ( $details ['created_by'] ); //10
		$outputArray [] = $details ['active'];
		$outputArray [] = $editable;
		
		$outputArray [] = $details['start_date'];
		$outputArray [] = $details['end_date'];
		
		
		echo json_encode ( $outputArray );
	} elseif ($_POST ['task'] == 'updateRecord') {		
		$id = $_POST['valueId_u'];
		$sessionId = $_POST['sessionId'];		
		if($session->isSessionEditable($sessionId)){
			$details = $examination->getTableIdDetails($id);
			
			if($details['examination_name'] != $_POST['examName_u']){
				$examination->setUpdateLog('Name from '.$details['examination_name'].' to '.$_POST['examName_u']);
				$examination->updateTableParameter('examination_name', $_POST['examName_u']);
			}
			if($details['examination_description'] != $_POST['examDescription_u']){
				$examination->setUpdateLog('Description from '.$details['examination_description'].' to '.$_POST['examDescription_u']);
				$examination->updateTableParameter('examination_description', $_POST['examDescription_u']);
			}
			if($details['start_date'] != $_POST['startDate_u']){
				$examination->setUpdateLog('Start Date from '.$details['start_date'].' to '.$_POST['startDate_u']);
				$examination->updateTableParameter('start_date', $_POST['startDate_u']);
			}
			if($details['end_date'] != $_POST['endDate_u']){
				$examination->setUpdateLog('End Date From '.$details['end_date'].' to '.$_POST['endDate_u']);
				$examination->updateTableParameter('end_date', $_POST['endDate_u']);
			}							
			if($examination->commitExaminationTypeUpdate($id)){
				$outputArray[0] = $id;
				$outputArray[] = $_POST['examName_u'];
				$outputArray[] = $_POST['examDescription_u'];				
			}	
		}
		else{
			$outputArray[] = 0;
		}		
		echo json_encode ( $outputArray );
	
	} elseif ($_POST ['task'] == 'dropRecord') {
		$id = htmlentities ( trim ( $_POST ['id'] ) );
		$examination->dropExaminationType($id);
		$outputArray [0] = $id;
		echo json_encode ( $outputArray );
	} elseif ($_POST ['task'] == 'activateRecord') {
		$id = htmlentities ( trim ( $_POST ['id'] ) );
		$examination->activateExaminationType($id);
		$outputArray [0] = $id;
		echo json_encode ( $outputArray );
	} else {
		$outputArray [0] = 0;
		echo json_encode ( $outputArray );
	}
}
?>