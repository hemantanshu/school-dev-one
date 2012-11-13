<?php
require_once 'config.php';
require_once BASE_PATH . 'include/exam/class.result.php';
require_once BASE_PATH . 'include/global/class.session.php';
require_once BASE_PATH . 'include/exam/class.grading.php';
require_once BASE_PATH . 'include/global/class.notification.php';
require_once BASE_PATH . 'include/global/class.menuTask.php';
require_once BASE_PATH . 'include/exam/class.resultType.php';


$result = new Result();
$session = new Session();
$grading = new Grading();
$menuTask = new MenuTask();
$notification = new Notification();
$resultType = new ResultType();

$result->isRequestAuthorised4Form ( 'LMENUL81' );

if (isset ( $_POST ['task'] )) {
	if ($_POST ['task'] == 'insertRecord') {
		$assessmentId = $result->setResultAssessment($_POST['sessionId'], $_POST['resultId'], $_POST['classId'], $_POST['sectionId'], $_POST['assessmentName'], $_POST['order'], $_POST['markingType']);
		if($assessmentId){
			$outputArray[0] = $assessmentId;
			$outputArray[] = $_POST['assessmentName'];
			$outputArray[] = $_POST['order'];				
		}else
			$outputArray[0] = 0;
		echo json_encode($outputArray);
		
	} else if ($_POST ['task'] == 'search') {
		$search_type = $_POST['search_type'];
		$resultId = $_POST['resultId'];
		$sectionId = $_POST['sectionId'];
		$sessionId = $_POST['sessionId'];
		$editable = 1;
		if(!$session->isSessionEditable($sessionId))
			$editable = 0;
		$resultIds = $result->getResultAssessment($resultId, $sectionId, $search_type);
		$outputArray[0][0] = 1;
		$i = 0;
		foreach ($resultIds as $resultId){
			$details = $result->getTableIdDetails($resultId);
			$outputArray[$i][0] = $resultId;
			$outputArray[$i][] = $details['assessment_name'];
			$outputArray[$i][] = $details['assessment_order'];
			$outputArray[$i][] = $editable;
			++$i;
		}
		echo json_encode ( $outputArray );
	} else if ($_POST ['task'] == 'getRecordIdDetails') {
		$id = $_POST ['id'];				
		$details = $result->getTableIdDetails ( $id );
		$editable = 1;
		if(!$session->isSessionEditable($details['session_id']))
			$editable = 0;		
			
		$outputArray [0] = $details ['id'];
		$outputArray [] = $details['assessment_name'];
		$outputArray [] = $details['assessment_order'];
		$outputArray [] = $result->getDisplayDate($details['start_date']);
		$outputArray [] = $result->getDisplayDate($details['end_date']);
		$outputArray [] = $details ['last_update_date'];
		$outputArray [] = $result->getOfficerName ( $details ['last_updated_by'] );
		$outputArray [] = $details ['creation_date'];
		$outputArray [] = $result->getOfficerName ( $details ['created_by'] ); //10
		$outputArray [] = $details ['active'];
		$outputArray [] = $editable;
		
		$outputArray [] = $details['marking_scheme'] == "" ? "Absolute Marking" : $grading->getGradingName($details['marking_scheme']);		
		$outputArray [] = $details['marking_scheme'];
			
		echo json_encode ( $outputArray );
	} elseif ($_POST ['task'] == 'updateRecord') {		
		$id = $_POST['valueId_u'];
		$sessionId = $_POST['sessionId'];		
		if($session->isSessionEditable($sessionId)){
			$details = $result->getTableIdDetails($id);
			
			if($details['assessment_name'] != $_POST['assessmentName_u']){
				$result->setUpdateLog('Name from '.$details['assessment_name'].' to '.$_POST['assessmentName_u']);
				$result->updateTableParameter('assessment_name', $_POST['assessmentName_u']);
			}
			if($details['assessment_order'] != $_POST['order_u']){
				$result->setUpdateLog('Order from '.$details['assessment_order'].' to '.$_POST['order_u']);
				$result->updateTableParameter('assessment_order', $_POST['order_u']);
			}
			if($details['marking_scheme'] != $_POST['markingType_u']){
				$result->setUpdateLog('Marking Scheme from '.$details['marking_scheme'].' to '.$_POST['markingType_u']);
				$result->updateTableParameter('marking_scheme', $_POST['markingType_u']);
			}			
			if($result->commitResultAssessmentUpdate($id)){
				$outputArray[0] = $id;
				$outputArray[] = $_POST['assessmentName_u'];
				$outputArray[] = $_POST['order_u'];				
			}	
		}
		else{
			$outputArray[] = 0;
		}		
		echo json_encode ( $outputArray );
	
	} elseif($_POST['task'] == "getResultSessionDetails"){
		$sessionId = $_POST['sessionId'];
		$resultId = $_POST['resultId'];
		$sectionId = $_POST['sectionId'];	
		
		$resultDetails = $grading->getTableIdDetails($resultId);
		$sessionDetails = $grading->getTableIdDetails($resultDetails['session_id']);
		$sectionDetails = $grading->getTableIdDetails($sectionId);
		$classDetails = $grading->getTableIdDetails($sectionDetails['class_id']);
		$classDetails = $grading->getTableIdDetails($classDetails['class_id']);
		
		$outputArray[0] = $sessionDetails['session_name'];
		$outputArray[] = $resultDetails['result_name'];
		$outputArray[] = $classDetails['class_name'];
		$outputArray[] = $sectionDetails['section_name'];
		$outputArray[] = $sectionDetails['class_id'];
		$outputArray[] = $resultDetails['result_type'];
		
		echo json_encode($outputArray);
	}elseif ($_POST ['task'] == 'dropRecord') {
		$id = htmlentities ( trim ( $_POST ['id'] ) );
		$activityIds = $result->getActivityIds($id, 1);
		foreach($activityIds as $activityId){
			$notificationId = $notification->getNotificationId4Source($activityId."-S");
			$notification->dropNotification($notificationId);
			$menuTaskId = $menuTask->getMenuTaskId4SourceId($activityId."-S");
			$menuTask->dropMenuTaskAssignment($menuTaskId);
			
			$notificationId = $notification->getNotificationId4Source($activityId."-V");
			$notification->dropNotification($notificationId);
			$menuTaskId = $menuTask->getMenuTaskId4SourceId($activityId."-V");
			$menuTask->dropMenuTaskAssignment($menuTaskId);
		}
		$result->dropResultAssessment($id);
		$outputArray [0] = $id;
		echo json_encode ( $outputArray );
	} elseif ($_POST ['task'] == 'activateRecord') {
		$id = htmlentities ( trim ( $_POST ['id'] ) );
		$activityIds = $result->getActivityIds($id, 0);
		foreach($activityIds as $activityId){
			$notificationId = $notification->getNotificationId4Source($activityId."-S");
			$notification->activateNotification($notificationId);
			$menuTaskId = $menuTask->getMenuTaskId4SourceId($activityId."-S");
			$menuTask->activateMenuTaskAssignment($menuTaskId);
				
			$notificationId = $notification->getNotificationId4Source($activityId."-V");
			$notification->activateNotification($notificationId);
			$menuTaskId = $menuTask->getMenuTaskId4SourceId($activityId."-V");
			$menuTask->activateMenuTaskAssignment($menuTaskId);
		}
		
		$result->activateResultAssessment($id);
		$outputArray [0] = $id;
		echo json_encode ( $outputArray );
	}else {
		$outputArray [0] = 0;
		echo json_encode ( $outputArray );
	}
}
?>