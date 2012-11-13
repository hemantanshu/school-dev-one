<?php
require_once 'config.php';
require_once BASE_PATH . 'include/exam/class.examination.php';
require_once BASE_PATH . 'include/exam/class.markHandling.php';
require_once BASE_PATH . 'include/exam/class.grading.php';

$examination = new Examination ();
$markHandling = new MarkHandling();
$grading = new Grading();

$examination->isRequestAuthorised4Form ( 'LMENUL79' );

if (isset ( $_POST ['task'] )) {
	if ($_POST ['task'] == 'verify') {
		$score = $_POST['mark'];
		$examinationId = $_POST['examinationId'];
		$candidateId = $_POST['candidateId'];		
		$scoreId = $markHandling->setGradeRecord4Candidate($examinationId, $candidateId, $score, $grading->getGradingOptionWeight($score));		
		if($scoreId){
			$markHandling->confirmMarkVerification($scoreId);
			$outputArray[0] = $grading->getGradingOptionName($score);
		}else{
			$outputArray[0] = 2;
		}
		echo json_encode($outputArray);	
	} else {
		$outputArray [0] = 0;
		echo json_encode ( $outputArray );
	}
}
?> 