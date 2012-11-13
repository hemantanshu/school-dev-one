<?php
require_once 'config.php';

require_once BASE_PATH . 'include/exam/class.resultMarking.php';
require_once BASE_PATH . 'include/exam/class.result.php';
require_once BASE_PATH . 'include/exam/class.resultGrade.php';
require_once BASE_PATH . 'include/exam/class.resultSections.php';
require_once BASE_PATH . 'include/utility/class.candidate.php';
require_once BASE_PATH . 'include/utility/class.registration.php';
require_once BASE_PATH . 'include/exam/class.grading.php';
require_once BASE_PATH . 'include/global/class.options.php';
require_once BASE_PATH . 'include/global/class.body.php';

$result = new Result ();
$candidate = new Candidate ();
$marking = new ResultMarking ();
$registration = new registrationInfo ();
$grading = new Grading ();
$options = new options ();
$resultSections = new ResultSections ();
$body = new body ();
$resultGrade = new ResultGrade();

$result->isRequestAuthorised4Form ( 'LMENUL147' );


if (isset ( $_POST ['task'] )) {
	if ($_POST ['task'] == 'getResultSheetData') {
		$resultId = $_POST ['resultId'];
		$sectionId = $_POST ['sectionId'];
		
		$resultSetupIds = $result->getResultSetupIds ( $resultId, 1 );
		$subjectIds = $result->getResultSubjectComponentIds($resultId, $sectionId);
				
		$assessmentIds = $result->getResultAssessment ( $resultId, $sectionId, 1 );
		$i = 0;
		foreach ( $assessmentIds as $assessmentId ) {
			$activityIds [$i] = $result->getActivityIds ( $assessmentId, 1 );
			++ $i;
		}
		
		foreach ($subjectIds as $subjectIdDetails){
			$subjectId = $subjectIdDetails[0];
			$componentId = $subjectIdDetails[1];
			foreach ($resultSetupIds as $resultSetupId){
				$resultDetails = $result->getTableIdDetails($resultSetupId);
				$subjectResultComponentTotal[$resultSetupId][$subjectId][$componentId] = $resultDetails['weightage']*.01*$result->getResultExaminationSubjectComponentTotal($resultDetails['examination_id'], $sectionId, $subjectId, $componentId);
			}
		}
		
		$candidateIds = $candidate->getCandidate4Section ( $sectionId, 1 );
        $i = 0;
        foreach ($candidateIds as $candidateId){
        	$outputArray[$i][0] = $registration->getCandidateRegistrationNumber($candidateId); 
            $outputArray[$i][] = $candidate->getCandidateName($candidateId);
            
            foreach ($subjectIds as $subjectIdDetails){
            	$subjectId = $subjectIdDetails[0];
            	$componentId = $subjectIdDetails[1];
            
            	foreach ($resultSetupIds as $resultSetupId){
            		$score = $marking->getTotalMarkResultEntry4CandidateSubjectComponent($resultSetupId, $subjectId, $componentId, $candidateId, 1);
            		$gradeType = $resultGrade->getResultSubjectComponentGradeData($resultId, $sectionId, 'LRESER26', $subjectId, $componentId);
            
            		if($gradeType)
            			$outputArray[$i][] = $grading->getGradeForScore($score / $subjectResultComponentTotal[$resultSetupId][$subjectId][$componentId], $gradeType) ;
            		else
            			$outputArray[$i][] = number_format($score, 1, '.', '');            
            	}           	
            }
            foreach ($assessmentIds as $assessmentId){
            	$activityIds = $result->getActivityIds ( $assessmentId, 1 );
            	foreach ($activityIds as $activityId){
            		$outputArray[$i][] = 'ss';
            	}
            }            
            ++$i;
        }
        echo json_encode($outputArray);
	} else {
		$outputArray [0] = 0;
		echo json_encode ( $outputArray );
	}
}


?>
