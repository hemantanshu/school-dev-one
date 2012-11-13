<?php
require_once 'config.php';

require_once BASE_PATH . 'include/exam/class.resultMarking.php';
require_once BASE_PATH . 'include/exam/class.result.php';
require_once BASE_PATH . 'include/exam/class.resultSections.php';
require_once BASE_PATH . 'include/utility/class.candidate.php';
require_once BASE_PATH . 'include/utility/class.registration.php';
require_once BASE_PATH . 'include/utility/class.personalInfo.php';
require_once BASE_PATH . 'include/exam/class.grading.php';
require_once BASE_PATH . 'include/global/class.options.php';

$result = new Result ();
$candidate = new Candidate ();
$marking = new ResultMarking ();
$registration = new registrationInfo ();
$grading = new Grading ();
$options = new options ();
$resultSections = new ResultSections ();
$personalInfo = new personalInfo ();

$resultId = "RESDF2";
$newSectionId = $_GET['sectionId'];

$sectionIds = $result->getSectionIds();

?>

<html>

<body>
<?php
        
foreach ($sectionIds as $sectionId){
	//if($sectionId != $newSectionId)
		//continue;
	
	$resultDetails = $result->getTableIdDetails ( $resultId );
	$sectionDetails = $result->getTableIdDetails ( $sectionId );
	$classDetails = $result->getTableIdDetails ( $sectionDetails ['class_id'] );
	$classDetails = $result->getTableIdDetails ( $classDetails ['class_id'] );
	
	$resultSetupIds = $result->getResultSetupIds ( $resultId );
	$subjectIds = $result->getResultSubjectIds ( $resultId, $sectionId );
	$resultSectionId = $resultSections->getResultSectionId ( $resultId, $sectionId );
	$resultSectionDetails = $result->getTableIdDetails ( $resultSectionId );
	
	$totalAttendance = $resultSectionDetails ['total_attendance'];
	$sessionDetails = $result->getTableIdDetails ( $resultDetails ['session_id'] );
	
	$candidateIds = $candidate->getCandidate4Section ( $sectionId, 1 );
	$subjectTotalMark = 25;
	
	$assessmentIds = $result->getResultAssessment ( $resultId, $sectionId, 1 );
	echo "<table border=\"1\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\">";
	foreach ( $candidateIds as $candidateId ) {
		$details = $personalInfo->getUserIdDetails ( $candidateId );
		$registrationDetails = $registration->getTableIdDetails ( $registration->getCandidateRegistrationId ( $candidateId ) );
	
		$i = 0;
		
		$subjectListing = "";
		foreach ( $subjectIds as $subjectId ) {
			
			$score = $marking->getTotalMarkResultEntry4CandidateSubject ( $resultSetupIds [0], $subjectId, $candidateId );
			if (! $score) {
				if (! $result->checkCandidateSubject ( $candidateId, $subjectId, $sectionId ))
					continue;
			}
			$subjectDetails = $result->getTableIdDetails($subjectId);
			foreach ( $resultSetupIds as $resultSetupId ) {
				$score = $marking->getTotalMarkResultEntry4CandidateSubject ( $resultSetupId, $subjectId, $candidateId );
				$score = $score [0] == "" ? 0 : $score[0];
				$subjectListing .= $subjectDetails['subject_name']."(".$score.")"." | "; 
			}
			++$i;
		}
		echo "
		 
		<tr>
		<td>" . $candidate->getCandidateName ( $candidateId ) . "</td>
		<td>" . $classDetails ['class_name'] . " " . $sectionDetails ['section_name'] . "</td>
		<td>".$subjectListing."</td>
		<td>" . $i . "</td>
		</tr>";
	
	}
	echo "</table><br /><hr /><br />";
	
}
?>
</body>
<html>
<body>