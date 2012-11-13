<?php
require_once 'config.php';

require_once BASE_PATH . 'include/exam/class.resultMarking.php';
require_once BASE_PATH . 'include/exam/class.result.php';
require_once BASE_PATH . 'include/utility/class.candidate.php';

$result = new Result();
$candidate = new Candidate();
$marking = new ResultMarking();

$resultId = $_GET['resultId'];
$sectionId = $_GET['sectionId'];

$resultSetupIds = $result->getResultSetupIds($resultId);
$i = 0;

foreach($resultSetupIds as $resultSetupId){
	$details = $result->getTableIdDetails($resultSetupId);
	$examinationArray[$i][0] = $details['examination_id'];
	$examinationArray[$i][] = $details['weightage'];
	++$i;
}
$candidateIds  = $candidate->getCandidate4Section($sectionId, 1);
$subjectIds = $result->getResultSubjectIds($resultId, $sectionId);
foreach($candidateIds as $candidateId){
	foreach($subjectIds as $subjectId){
		$i = 0;		
		foreach ($resultSetupIds as $resultSetupId){
			$examinationId = $examinationArray[$i][0];
			$weightage = $examinationArray[$i][1];			
			$score = $result->getCandidateSubjectMark($candidateId, $examinationId, $subjectId);
			if($score){
				$score = $score*$weightage/100;
				echo $marking->setMarkRecords($resultSetupId, $sectionId, $subjectId, $candidateId, $score, $score)."<br />";				
			}
			++$i;
		}			
	}
}
?>