<?php
require_once 'config.php';
require_once BASE_PATH . 'include/exam/class.examination.php';
require_once BASE_PATH . 'include/exam/class.markHandling.php';
require_once BASE_PATH . 'include/global/class.menuTask.php';
require_once BASE_PATH . 'include/global/class.notification.php';
require_once BASE_PATH . 'include/exam/class.grading.php';

$examination = new Examination ();
$markHandling = new MarkHandling();
$menuTask = new MenuTask();
$notification = new Notification();
$grading = new grading();

$examination->isRequestAuthorised4Form ( 'LMENUL78' );

if (isset ( $_POST ['task'] )) {
	if ($_POST ['task'] == 'submitMark') {
		$score = $_POST['mark'];
		$examinationId = $_POST['examinationId'];
		$candidateId = $_POST['candidateId'];		
		$scoreId = $markHandling->setGradeRecord4Candidate($examinationId, $candidateId, $score, $grading->getGradingOptionWeight($score));
		if($scoreId)
			$outputArray[0] = 1;
		else
			$outputArray[0] = 2;
		
		echo json_encode($outputArray);			
	}elseif($_POST['task'] == "getGradeOptions"){
		$examinationId = $_POST['examinationId'];
		$details = $examination->getTableIdDetails($examinationId);
		$outputArray = $grading->getGradingOptions($details['marking_type']);
		echo json_encode($outputArray);
	}elseif ($_POST ['task'] == 'confirmMark') {
		$score = $_POST['mark'];
		$examinationId = $_POST['examinationId'];
		$candidateId = $_POST['candidateId'];
		
		$scoreId = $markHandling->setGradeRecord4Candidate($examinationId, $candidateId, $score, $grading->getGradingOptionWeight($score));
		if($scoreId){
			$markHandling->confirmMarkSubmission($scoreId);
			$outputArray[0] = $grading->getGradingOptionName($score);
		}else{
			$outputArray[0] = 2;
		}
		echo json_encode($outputArray);	
	} elseif($_POST['task'] == "getGradeName"){
		$optionId = $_POST['optionId'];
		$outputArray[0] = $grading->getGradingOptionName($optionId);
		echo json_encode($outputArray);
	}else {
		$outputArray [0] = 0;
		echo json_encode ( $outputArray );
	}
}
?>