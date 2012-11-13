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

$examination->isRequestAuthorised4Form ( 'LMENUL75' );

if (isset ( $_POST ['task'] )) {
	if ($_POST ['task'] == 'submitMark') {
		$score = $_POST['mark'];
		$examinationId = $_POST['examinationId'];
		$candidateId = $_POST['candidateId'];
		
		$scoreId = $markHandling->setMarkRecord4Candidate($examinationId, $candidateId, $score);
		if($scoreId)
			$outputArray[0] = 1;
		else
			$outputArray[0] = 2;
		
		echo json_encode($outputArray);			
	}elseif ($_POST ['task'] == 'getExaminationDetails') {
		$examinationId = $_POST['examinationId'];
		$outputArray[0] = 1;
		$details = $markHandling->getExaminationDisplayDetails($examinationId);
		if($details[0] != ''){
			$componentDetails = $examination->getTableIdDetails($details['subject_component_id']);
			$outputArray[0] = $details['examination_name'];
			$outputArray[] = $details['session_name'];
			$outputArray[] = $details['class_name'];
			$outputArray[] = $details['subject_code'];
			$outputArray[] = $details['subject_name'];
			$outputArray[] = $componentDetails['subject_component_name'];
			$outputArray[] = $details['max_mark'];
			$outputArray[] = $details['pass_mark'];
			$outputArray[] = $details['section_name'];
			$outputArray[] = $markHandling->getDisplayDate($details['start_date']);			
			$outputArray[] = $markHandling->getDisplayDate($details['submission_date']);
			$outputArray[] = $markHandling->getDisplayDate($details['verification_date']);
				
		}
        echo json_encode($outputArray);
	} elseif ($_POST ['task'] == 'getCandidateDetails') {
		$candidateId = $_POST['candidateId'];
		$details = $personalInfo->getUserIdDetails($candidateId);
		
		$outputArray[0] = $candidateId;
		$outputArray[] = $registration->getCandidateRegistrationNumber($candidateId);
		$outputArray[] = $personalInfo->getUserName();
		$outputArray[] = $details['gender'] == 'M' ? 'Male' : 'Female';
		
		echo json_encode($outputArray);
	} elseif ($_POST ['task'] == 'confirmMark') {
		$score = $_POST['mark'];
		$examinationId = $_POST['examinationId'];
		$candidateId = $_POST['candidateId'];
		
		$scoreId = $markHandling->setMarkRecord4Candidate($examinationId, $candidateId, $score);
		if($scoreId){
			$markHandling->confirmMarkSubmission($scoreId);
			$outputArray[0] = 1;
		}else{
			$outputArray[0] = 2;
		}
		echo json_encode($outputArray);	
	} elseif($_POST['task'] == "finalConfirmation"){
		$examinationId = $_POST['examinationId'];
		$status = $markHandling->checkMarkFillingCompletionStatus($examinationId, false);
		if($status){
			//closing the exam submission flag
			$details = $examination->getTableIdDetails($examinationId);
			
			$examination->updateTableParameter('actual_mark_submission_date', $examination->getCurrentDate());
			$examination->commitExaminationSubjectDateUpdate($examinationId);			
			//closing the task assignment 
			$menuTaskId = $menuTask->getMenuTaskId4SourceId($examinationId.'-S');
			$menuTask->setUpdateLog('Complete Flag To Y');
			$menuTask->updateTableParameter('complete_flag', 'y');
			$menuTask->updateTableParameter('end_date', $examination->getCurrentDate());
			$menuTask->commitMenuTaskAssignmentUpdate($menuTaskId);
			
			$notificationId = $notification->getNotificationId4Source($examinationId."-S");
			$notification->setUpdateLog('Hide Flag To Y');
			$notification->updateTableParameter('hide_flag', 'y');
			$notification->updateTableParameter('end_date', $examination->getCurrentDate());
			$notification->commitNotificationUpdate($notificationId);
			
			if($details['mark_submission_date'] != $examination->getCurrentDate()){
				$menuTaskId = $menuTask->getMenuTaskId4SourceId($examinationId.'-V');
				$menuTask->setUpdateLog('Start Date to '.$examination->getCurrentDate());
				$menuTask->updateTableParameter('start_date', $examination->getCurrentDate());
				$menuTask->commitMenuTaskAssignmentUpdate($menuTaskId);
				
				$notificationId = $notification->getNotificationId4Source($examinationId."-V");		
				$notification->setUpdateLog('Start Date to '.$examination->getCurrentDate());
				$notification->updateTableParameter('start_date', $examination->getCurrentDate());
				$notification->commitNotificationUpdate($notificationId);
			}		
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