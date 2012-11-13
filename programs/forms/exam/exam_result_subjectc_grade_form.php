<?php
require_once 'config.php';
require_once BASE_PATH . 'include/exam/class.grading.php';
require_once BASE_PATH . 'include/exam/class.resultGrade.php';
require_once BASE_PATH . 'include/utility/class.sections.php';

$grading = new Grading();
$section = new sections();
$resultGrade = new ResultGrade();

$grading->isRequestAuthorised4Form ( 'LMENUL151' );

if (isset ( $_POST ['task'] )) {
	if ($_POST ['task'] == 'resultDetails') {
		$resultId = $_POST['resultId'];
		$sectionId = $_POST['sectionId'];
		
		$resultDetails = $grading->getTableIdDetails($resultId);
		$resultTypeDetails = $grading->getTableIdDetails($resultDetails['result_type']);
		
		$outputArray[0] = $resultDetails['result_name'];
		$outputArray[] = $section->getClassName4Section($sectionId)." ".$section->getSectionName($sectionId);
		$outputArray[] = $resultTypeDetails['result_type'];
		$outputArray[] = $resultTypeDetails['result_description'];
		$outputArray[] = $resultTypeDetails['id'];
		
		echo json_encode ( $outputArray );
	} elseif($_POST['task'] == "submitSubjectComponentGrade"){
		$resultId = $_POST['resultId'];
		$sectionId = $_POST['sectionId'];
		$subjectId = $_POST['subjectId'];
		$componentId = $_POST['componentId'];
		$gradeId = $_POST['gradeId'];
		
		$subjectComponentGradeId = $resultGrade->setResultSubjectComponentGrade($resultId, $sectionId, $subjectId, 'LRESER25', $componentId, $gradeId);
		if($subjectComponentGradeId)
			$outputArray[0] = $subjectComponentGradeId;
		else
			$outputArray[0] = 0;
		
		echo json_encode($outputArray);
	}elseif($_POST['task'] == "getSubjectComponentDetails"){
		$subjectComponentGradeId = $_POST['subjectComponentGradeId'];
		
		$details = $grading->getTableIdDetails($subjectComponentGradeId);
		$subjectDetails = $resultGrade->getTableIdDetails($details['subject_id']);
		$componentDetails = $resultGrade->getTableIdDetails($details['subject_component_id']);
		
		$outputArray[0] = $subjectDetails['subject_code'];
		$outputArray[] = $subjectDetails['subject_name'];
		$outputArray[] = $componentDetails['subject_component_name'];
		
		$outputArray[] = $details['grade_type'] == '' ? 'Absolute Marking' : $grading->getGradingName($details['grade_type']);
		echo json_encode($outputArray);
		
	}else {
		$outputArray [0] = 0;
		echo json_encode ( $outputArray );
	}
}
?>