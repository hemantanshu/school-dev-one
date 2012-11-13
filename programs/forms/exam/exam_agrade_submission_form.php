<?php
require_once 'config.php';
require_once BASE_PATH . 'include/exam/class.resultMarking.php';
require_once BASE_PATH . 'include/exam/class.grading.php';
require_once BASE_PATH . 'include/exam/class.result.php';
require_once BASE_PATH . 'include/global/class.notification.php';
require_once BASE_PATH . 'include/global/class.menuTask.php';

$resultMarking = new ResultMarking ();
$grading = new Grading ();
$notification = new Notification ();
$menuTask = new MenuTask ();
$result = new Result ();

$resultMarking->isRequestAuthorised4Form ( 'LMENUL83' );

if (isset ( $_POST ['task'] )) {
	if ($_POST ['task'] == 'getActivityDetails') {
		$activityId = $_POST ['activityId'];
		
		$details = $resultMarking->getTableIdDetails ( $activityId );
		$resultDetails = $resultMarking->getTableIdDetails ( $details ['result_id'] );
		$sessionDetails = $resultMarking->getTableIdDetails ( $details ['session_id'] );
		$assessmentDetails = $resultMarking->getTableIdDetails ( $details ['assessment_id'] );
		$subjectDetails = $resultMarking->getTableIdDetails ( $details ['subject_id'] );
		$sectionDetails = $resultMarking->getTableIdDetails ( $details ['section_id'] );
		$classDetails = $resultMarking->getTableIdDetails ( $details ['class_id'] );
		$classNameDetails = $resultMarking->getTableIdDetails ( $classDetails ['class_id'] );
		
		$outputArray [0] = $sessionDetails ['session_name'];
		$outputArray [] = $resultDetails ['result_name'];
		$outputArray [] = $assessmentDetails ['assessment_name'];
		$outputArray [] = $details ['activity_name'];
		$outputArray [] = $classNameDetails ['class_name'];
		$outputArray [] = $sectionDetails ['section_name'];
		$outputArray [] = $subjectDetails ['subject_code'] . " " . $subjectDetails ['subject_name'];
		$outputArray [] = $resultMarking->getDisplayDate ( $details ['mark_submission_date'] );
		
		echo json_encode ( $outputArray );
	} elseif ($_POST ['task'] == 'submitMark') {
		$score = $_POST ['mark'];
		$activityId = $_POST ['activityId'];
		$candidateId = $_POST ['candidateId'];
		$scoreId = $resultMarking->setGradeRecord4Candidate ( $activityId, $candidateId, $score, $grading->getGradingOptionWeight ( $score ) );
		if ($scoreId)
			$outputArray [0] = 1;
		else
			$outputArray [0] = 2;
		
		echo json_encode ( $outputArray );
	
	} elseif ($_POST ['task'] == 'confirmMark') {
		$score = $_POST ['mark'];
		$activityId = $_POST ['activityId'];
		$candidateId = $_POST ['candidateId'];
		
		$scoreId = $resultMarking->setGradeRecord4Candidate ( $activityId, $candidateId, $score, $grading->getGradingOptionWeight ( $score ) );
		
		if ($scoreId) {
			$resultMarking->confirmMarkSubmission ( $scoreId );
			$outputArray [0] = $grading->getGradingOptionName ( $score );
		} else {
			$outputArray [0] = 2;
		}
		echo json_encode ( $outputArray );
	} elseif ($_POST ['task'] == "getGradeOptions") {
		$activityId = $_POST ['activityId'];
		$details = $resultMarking->getTableIdDetails ( $activityId );
		$outputArray = $grading->getGradingOptions ( $details ['marking_type'] );
		echo json_encode ( $outputArray );
	} elseif ($_POST ['task'] == "finalConfirmation") {
		$activityId = $_POST ['activityId'];
		$status = $resultMarking->checkMarkFillingCompletionStatus ( $activityId, false );
		if ($status) {
			// closing the exam submission flag
			$details = $resultMarking->getTableIdDetails ( $activityId );
			
			$result->setUpdateLog('Actual Mark Submision to '.$resultMarking->getCurrentDate ());
			$result->updateTableParameter ( 'actual_mark_submission_date', $resultMarking->getCurrentDate () );						
			$result->commitAssessmentSubjectUpdate ( $activityId );
			
			// closing the task assignment
			$menuTaskId = $menuTask->getMenuTaskId4SourceId ( $activityId . '-S' );
			$menuTask->setUpdateLog('Complete Flag to y');
			$menuTask->updateTableParameter ( 'complete_flag', 'y' );
			$menuTask->updateTableParameter ( 'end_date', $resultMarking->getCurrentDate () );
			$menuTask->commitMenuTaskAssignmentUpdate ( $menuTaskId );
			
			$notificationId = $notification->getNotificationId4Source ( $activityId . "-S" );
			$notification->setUpdateLog('Hide Flag to y');
			$notification->updateTableParameter ( 'hide_flag', 'y' );
			$notification->updateTableParameter ( 'end_date', $resultMarking->getCurrentDate () );
			$notification->commitNotificationUpdate ( $notificationId );
			
			$menuTaskId = $menuTask->getMenuTaskId4SourceId ( $activityId . '-V' );
			$menuTask->setUpdateLog('Start Date To '.$resultMarking->getCurrentDate ());
			$menuTask->updateTableParameter ( 'start_date', $resultMarking->getCurrentDate () );
			$menuTask->commitMenuTaskAssignmentUpdate ( $menuTaskId );
			
			$notificationId = $notification->getNotificationId4Source ( $activityId . "-V" );
			$notification->setUpdateLog('Start Date To '. $resultMarking->getCurrentDate ());
			$notification->updateTableParameter ( 'start_date', $resultMarking->getCurrentDate () );
			$notification->commitNotificationUpdate ( $notificationId );
			
			$outputArray [0] = 1;
		} else
			$outputArray [1] = 2;
		echo json_encode ( $outputArray );
	} else {
		$outputArray [0] = 0;
		echo json_encode ( $outputArray );
	}
}
?>