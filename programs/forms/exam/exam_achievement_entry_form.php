<?php
require_once 'config.php';
require_once BASE_PATH . 'include/exam/class.resultSections.php';
require_once BASE_PATH . 'include/global/class.notification.php';
require_once BASE_PATH . 'include/global/class.menuTask.php';
require_once BASE_PATH . 'include/exam/class.resultJunior.php';

$result = new ResultSections();
$menuTask = new MenuTask();
$notification = new Notification();
$junior = new ResultJunior();

$result->isRequestAuthorised4Form ( 'LMENUL119' );

if (isset ( $_POST ['task'] )) {
	if ($_POST ['task'] == 'confirmData') {
		$data = $_POST ['data'];
		$resultSectionId = $_POST ['resultSectionId'];
		$candidateId = $_POST ['candidateId'];
		
		$dataId = $result->setResultSectionData($resultSectionId, $candidateId, $data, 'ACHIV');		
		$outputArray[0] = $dataId;
		echo json_encode ( $outputArray );
	} elseif($_POST['task'] == "finalConfirmation"){
		$resultSectionId = $_POST ['resultSectionId'];
		$sectionId = $_POST['sectionId'];
		$status = $result->checkDataFillingCompletionStatus($resultSectionId, $sectionId, 'ACHIV');
		if($status){
			//closing the weight submission flag		
			$junior->updateTableParameter('achievement_actual_date', $junior->getCurrentDateTime());
			$junior->commitSectionDetailsUpdate($resultSectionId);
						
			//closing the task assignment 
			$menuTaskId = $menuTask->getMenuTaskId4SourceId($resultSectionId.'-A');
			$details = $menuTask->getTableIdDetails($menuTaskId);
			if($details['complete_flag'] != 'y'){
				$menuTask->setUpdateLog('Complete Flag from '.$details['complete_flag'].' to '.'y');
				$menuTask->updateTableParameter('complete_flag', 'y');
			}
			if($details['end_date'] != $junior->getCurrentDate()){
				$menuTask->setUpdateLog('End Date from '.$details['end_date'].' to '.$junior->getCurrentDate());
				$menuTask->updateTableParameter('end_date', $junior->getCurrentDate());
			}			
			$menuTask->commitMenuTaskAssignmentUpdate($menuTaskId);
			
			$notificationId = $notification->getNotificationId4Source($resultSectionId."-A");
			$details = $junior->getTableIdDetails($notificationId);
			if($details['hide_flag'] != 'y'){
				$notification->setUpdateLog('Hide Flag from '.$details['hide_flag'].' to '.'y');
				$notification->updateTableParameter('hide_flag', 'y');
			}
			if($details['end_date'] != $junior->getCurrentDate()){
				$notification->setUpdateLog('from '.$details['end_date'].' to '.$junior->getCurrentDate());
				$notification->updateTableParameter('end_date', $junior->getCurrentDate());
			}	
			$notification->commitNotificationUpdate($notificationId);				
			$outputArray[0] = 1;
		}			
		else
			$outputArray[0] = 2;
		echo json_encode($outputArray);
	}else {
		$outputArray [0] = 0;
		echo json_encode ( $outputArray );
	}
}
?>