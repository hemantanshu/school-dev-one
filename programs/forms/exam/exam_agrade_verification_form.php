<?php
require_once 'config.php';
require_once BASE_PATH . 'include/exam/class.result.php';
require_once BASE_PATH . 'include/exam/class.resultMarking.php';
require_once BASE_PATH . 'include/exam/class.grading.php';
require_once BASE_PATH . 'include/global/class.notification.php';
require_once BASE_PATH . 'include/global/class.menuTask.php';

$result = new Result();
$markHandling = new ResultMarking();
$grading = new Grading();
$notification = new Notification();
$menuTask = new MenuTask();

$result->isRequestAuthorised4Form ( 'LMENUL84' );

if (isset ( $_POST ['task'] )) {
	if ($_POST ['task'] == 'verify') {
		$score = $_POST['mark'];
		$activityId = $_POST['activityId'];
		$candidateId = $_POST['candidateId'];				
		$scoreId = $markHandling->setGradeRecord4Candidate($activityId, $candidateId, $score, $grading->getGradingOptionWeight($score));		
		if($scoreId){
			$markHandling->confirmMarkVerification($scoreId);
			$outputArray[0] = $grading->getGradingOptionName($score);
		}else{
			$outputArray[0] = 2;
		}
		echo json_encode($outputArray);	
	} elseif($_POST['task'] == "finalConfirmation"){
		$activityId = $_POST['activityId'];
		$status = $markHandling->checkMarkFillingCompletionStatus($activityId, true);
		if($status){
			//closing the exam submission flag
			$details = $result->getTableIdDetails($activityId);

			$result->setUpdateLog('Actual Mark Verification Date '.$result->getCurrentDateTime());
			$result->updateTableParameter('actual_mark_verification_date', $result->getCurrentDate());
			$result->commitAssessmentSubjectUpdate($activityId);
			//closing the task assignment
			$menuTaskId = $menuTask->getMenuTaskId4SourceId($activityId.'-V');
			$menuTask->setUpdateLog('Complete Flag to Y');
			$menuTask->updateTableParameter('complete_flag', 'y');
			$menuTask->updateTableParameter('end_date', $result->getCurrentDate());
			$menuTask->commitMenuTaskAssignmentUpdate($menuTaskId);
				
			$notificationId = $notification->getNotificationId4Source($activityId."-V");
			$notification->setUpdateLog('Hide Flag to y');
			$notification->updateTableParameter('hide_flag', 'y');
			$notification->updateTableParameter('end_date', $result->getCurrentDate());
			$notification->commitNotificationUpdate($notificationId);
		
			$outputArray[0] = 1;
		}
		else
			$outputArray[1] = 2;
		echo json_encode($outputArray);
	}else {
		$outputArray [0] = 0;
		echo json_encode ( $outputArray );
	}
}
?> 