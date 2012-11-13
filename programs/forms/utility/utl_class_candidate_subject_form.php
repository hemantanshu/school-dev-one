<?php
require_once 'config.php';

require_once BASE_PATH.'include/utility/class.candidate.php';
$candidate = new Candidate();

$candidate->isRequestAuthorised4Form('LMENUL61');
if (isset ( $_POST ['task'] )) {
	if ($_POST ['task'] == 'updateSubjectDetails') {				
		$associationId = $candidate->setCandidateSubject($_POST['candidateId'], $_POST['sessionId'], $_POST['subjectType'], $_POST['subject']);
		if($associationId)
			$outputArray[0] = 1;
		else
			$outputArray[0] = 0;
		echo json_encode($outputArray);
	} else {
		$outputArray [0] = 0;
		echo json_encode ( $outputArray );
	}
}
?>