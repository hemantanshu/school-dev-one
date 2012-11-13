<?php
require_once 'config.php';
require_once BASE_PATH . 'include/exam/class.resultGrade.php';

$resultGrade = new ResultGrade();

$resultGrade->isRequestAuthorised4Form ( 'LMENUL152' );

if (isset ( $_POST ['task'] )) {
	if($_POST['task'] == "submitSubjectComponentGrade"){
		$resultId = $_POST['resultId'];
		$sectionId = $_POST['sectionId'];
		$subjectId = $_POST['subjectId'];
		$componentId = $_POST['componentId'];
		$gradeId = $_POST['gradeId'];
		
		$subjectComponentGradeId = $resultGrade->setResultSubjectComponentGrade($resultId, $sectionId, $subjectId, 'LRESER26', $componentId, $gradeId);
		if($subjectComponentGradeId)
			$outputArray[0] = $subjectComponentGradeId;
		else
			$outputArray[0] = 0;
		
		echo json_encode($outputArray);
	}else {
		$outputArray [0] = 0;
		echo json_encode ( $outputArray );
	}
}
?>