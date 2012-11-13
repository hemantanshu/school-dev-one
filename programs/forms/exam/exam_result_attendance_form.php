<?php
require_once 'config.php';
require_once BASE_PATH . 'include/exam/class.resultSections.php';
require_once BASE_PATH . 'include/global/class.notification.php';
require_once BASE_PATH . 'include/global/class.menuTask.php';

$result = new ResultSections();

$result->isRequestAuthorised4Form ( 'LMENUL87' );

if (isset ( $_POST ['task'] )) {
	if ($_POST ['task'] == 'confirmAttendance') {
		$attendance = $_POST ['attendance'];
		$resultSectionId = $_POST ['resultSectionId'];
		$candidateId = $_POST ['candidateId'];
		
		$attendanceId = $result->setResultSectionData($resultSectionId, $candidateId, $attendance, 'ATTND');		
		$outputArray[0] = $attendanceId;
		echo json_encode ( $outputArray );
	} else {
		$outputArray [0] = 0;
		echo json_encode ( $outputArray );
	}
}
?>