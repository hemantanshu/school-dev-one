<?php
require_once 'config.php';

require_once BASE_PATH . 'include/exam/class.resultMarking.php';
require_once BASE_PATH . 'include/exam/class.result.php';
require_once BASE_PATH . 'include/utility/class.candidate.php';

$result = new Result ();
$candidate = new Candidate ();
$marking = new ResultMarking ();

$result->isRequestAuthorised4Form ( 'LMENUL93' );

if (isset ( $_POST ['task'] )) {
	if ($_POST ['task'] == 'getCandidateIds') {		
		$i = 0;
		$sectionId = $_POST ['sectionId'];
		$outputArray = $candidate->getCandidate4Section($sectionId, 1);
		$candidateNameArray = array();
		$count = count($outputArray);
		while($count > 0){
			array_push($candidateNameArray, $outputArray[$count - 1]);
			--$count;
		}
		echo json_encode ( $candidateNameArray );
	} elseif ($_POST ['task'] == 'processCandidateResult') {
		$i = 0;
		$resultId = $_POST ['resultId'];
		$sectionId = $_POST ['sectionId'];
		$candidateId = $_POST ['candidateId'];
		
		$resultSetupIds = $result->getResultSetupIds ( $resultId, true );
		foreach ( $resultSetupIds as $resultSetupId ) {
			$marking->initialiseCandidateRecord4Processing($resultId, $resultSetupId, $sectionId, $candidateId);
			$details = $result->getTableIdDetails ( $resultSetupId );
			$examinationArray [$i] [0] = $details ['examination_id'];
			$examinationArray [$i] [] = $details ['weightage'];
			++ $i;
		}
		$outputArray [0] = 1;		
		$subjectComponentArray = $result->getResultSubjectComponentIds($resultId, $sectionId );
		foreach ( $subjectComponentArray as $subjectComponent ) {
			$subjectId = $subjectComponent[0];
			$componentId = $subjectComponent[1];
			if ($result->checkCandidateSubject ( $candidateId, $subjectId, $sectionId )) {
				$i = 0;
				foreach ( $resultSetupIds as $resultSetupId ) {
					$examinationId = $examinationArray [$i] [0];
					$weightage = $examinationArray [$i] [1];
					$score = $result->getCandidateSubjectComponentMark($candidateId, $examinationId, $subjectId, $componentId);
					if ($score) {
						$score = $score * $weightage / 100;
						$marking->setMarkRecords ( $resultSetupId, $sectionId, $subjectId, $componentId, $candidateId, $score, $score );
					}
					++ $i;
				}
			}
		}
		$result->setResultProcessing ( $resultId, $sectionId, $candidateId );
		$outputArray [] = $result->getOfficerName ( $candidateId );
		echo json_encode ( $outputArray );
	} else {
		$outputArray [0] = 0;
		echo json_encode ( $outputArray );
	}
}
?>