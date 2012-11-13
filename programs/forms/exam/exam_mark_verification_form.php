<?php
require_once 'config.php';
require_once BASE_PATH . 'include/exam/class.examination.php';
require_once BASE_PATH . 'include/exam/class.markHandling.php';
require_once BASE_PATH . 'include/utility/class.personalInfo.php';
require_once BASE_PATH . 'include/utility/class.registration.php';
require_once BASE_PATH . 'include/global/class.menuTask.php';
require_once BASE_PATH . 'include/global/class.notification.php';


$examination = new Examination ();
$markHandling = new MarkHandling();
$personalInfo = new personalInfo();
$registration = new registrationInfo();
$menuTask = new MenuTask();
$notification = new Notification();

$examination->isRequestAuthorised4Form ( 'LMENUL76' );

if (isset ( $_POST ['task'] )) {
	if ($_POST ['task'] == 'verify') {
		$score = $_POST['mark'];
		$examinationId = $_POST['examinationId'];
		$candidateId = $_POST['candidateId'];		
		$scoreId = $markHandling->setMarkRecord4Candidate($examinationId, $candidateId, $score);
		if($scoreId){
			$markHandling->confirmMarkVerification($scoreId);
			$outputArray[0] = 1;
		}else{
			$outputArray[0] = 2;
		}
		echo json_encode($outputArray);	
	} elseif($_POST['task'] == "finalConfirmation"){
		$examinationId = $_POST['examinationId'];
		$status = $markHandling->checkMarkFillingCompletionStatus($examinationId, true);
		if($status){
			//closing the exam submission flag
			$details = $examination->getTableIdDetails($examinationId);
			
			$examination->updateTableParameter('actual_mark_verification_date', $examination->getCurrentDate());
			$examination->commitExaminationSubjectDateUpdate($examinationId);			
			//closing the task assignment 
			$menuTaskId = $menuTask->getMenuTaskId4SourceId($examinationId.'-V');
			$menuTask->setUpdateLog('complete flag to y');
			$menuTask->updateTableParameter('complete_flag', 'y');
			$menuTask->updateTableParameter('end_date', $examination->getCurrentDate());
			$menuTask->commitMenuTaskAssignmentUpdate($menuTaskId);
			
			$notificationId = $notification->getNotificationId4Source($examinationId."-V");
			$menuTask->setUpdateLog('Hide flag to y');
			$notification->updateTableParameter('hide_flag', 'y');
			$notification->updateTableParameter('end_date', $examination->getCurrentDate());
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