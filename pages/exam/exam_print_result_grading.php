<?php
require_once 'config.php';

require_once BASE_PATH . 'include/exam/class.resultMarking.php';
require_once BASE_PATH . 'include/exam/class.result.php';
require_once BASE_PATH . 'include/exam/class.resultSections.php';
require_once BASE_PATH . 'include/utility/class.candidate.php';
require_once BASE_PATH . 'include/utility/class.registration.php';
require_once BASE_PATH . 'include/utility/class.personalInfo.php';
require_once BASE_PATH . 'include/exam/class.grading.php';
require_once BASE_PATH . 'include/global/class.body.php';

$result = new Result ();
$candidate = new Candidate ();
$marking = new ResultMarking ();
$registration = new registrationInfo ();
$grading = new Grading ();
$options = new options ();
$resultSections = new ResultSections ();
$personalInfo = new personalInfo ();
$body = new body();

$resultId = $_GET ['resultId'];
$sectionId = $_GET ['sectionId'];
$sessionId = $_GET['sessionId'];

$body->startBody("exam", "LMENUL97", "Exam Result Printing");

$resultDetails = $result->getTableIdDetails ( $resultId );
$sectionDetails = $result->getTableIdDetails ( $sectionId );
$classDetails = $result->getTableIdDetails ( $sectionDetails ['class_id'] );
$classDetails = $result->getTableIdDetails ( $classDetails ['class_id'] );

$resultSetupIds = $result->getResultSetupIds ( $resultId , 1);
$subjectIds = $result->getResultSubjectIds ( $resultId, $sectionId );
$resultSectionId = $resultSections->getResultSectionId ( $resultId, $sectionId );
$resultSectionDetails = $result->getTableIdDetails ( $resultSectionId );

$totalAttendance = $resultSectionDetails ['total_attendance'];
$sessionDetails = $result->getTableIdDetails ( $resultDetails ['session_id'] );

$candidateIds = $candidate->getCandidate4Section ( $sectionId, 1 );
$assessmentIds = $result->getResultAssessment ( $resultId, $sectionId, 1 );


foreach ($subjectIds as $subjectId){
	foreach ($resultSetupIds as $resultSetupId){
		$resultSetupDetails = $result->getTableIdDetails($resultSetupId);
		$maxScore = $resultSetupDetails['weightage']*.01*$result->getResultExaminationSubjectTotal($resultSetupDetails['examination_id'], $sectionId, $subjectId);
		$subjectTotal[$subjectId] += $maxScore;
		$subjectResultTotal[$subjectId][$resultSetupId] = $maxScore;
	}
}

?>

<?php
foreach ( $candidateIds as $candidateId ) {
	$details = $personalInfo->getUserIdDetails($candidateId);	
	$registrationDetails = $registration->getTableIdDetails($registration->getCandidateRegistrationId($candidateId));
	echo "<div style=\"padding-top: 150px;\">
            
    <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\">
        <tr>
                    <td width=\"100%\" align=\"center\" colspan=\"6\"><font class=\"printTopHeaderNames\">".$resultDetails ['display_name']."</font><br /></td>
                </tr>
                <tr>
                	<td height=\"10px\"></td>
                </tr>
                
        <tr>
            <td width=\"15%\" align=\"right\"><font class=\"printTableRow\">Name</font></td>
            <th width=\"3%\">:</th>
            <td width=\"30%\" align=\"left\"><font class=\"printMark\">" . $candidate->getCandidateName($candidateId) . "</font></td>
            <td width=\"15%\" align=\"right\"><font class=\"printTableRow\">Session</font></td>
            <th width=\"3%\">:</th>
            <td width=\"30%\" align=\"left\"><font class=\"printMark\">" . $sessionDetails ['session_name'] . "</font></td>
        </tr>
        <tr>
            <td align=\"right\"><font class=\"printTableRow\">Admission No</font></td>
            <th>:</th>
            <td align=\"left\"><font class=\"printMark\">" . $registrationDetails['registration_number'] . "</font></td>
            <td align=\"right\"><font class=\"printTableRow\">DOB</font></td>
            <th>:</th>
            <td align=\"left\"><font class=\"printMark\">" . $candidate->getDisplayDate($details['dob']) . "</font></td>
        </tr>
        <tr>
            <td align=\"right\"><font class=\"printTableRow\">Class </font></td>
            <th>:</th>
            <td align=\"left\"><font class=\"printMark\">" . $classDetails ['class_name'] . " " . $sectionDetails ['section_name'] . "</font></td>
            <td align=\"right\"><font class=\"printTableRow\">House</font></td>
            <th>:</th>
            <td align=\"left\"><font class=\"printMark\">" . $options->getOptionIdValue($registrationDetails['house_id']) . "</font></td>
        </tr>
    </table>
<font class=\"printAssessmentNames\">Part 1A : Academic Assessment</font>
<table border=\"1px\" style=\"border-style: solid\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\">
    <tr>
        <td align=\"center\"><font class=\"printSubjectHeader\">S N</td>
        <td align=\"center\"><font class=\"printSubjectHeader\">Subject</font></td>";
	
	
	foreach ( $resultSetupIds as $resultSetupId ) {
		$details = $result->getTableIdDetails ( $resultSetupId );
		echo "<td align=\"center\"><font class=\"printSubjectHeader\">" . $details ['display_name'] . "</font></td>";
		echo "<td align=\"center\"><font class=\"printSubjectHeader\">Grade</font></td>";
	}
	echo "
    </tr>";
	$i = 1;
	foreach ( $subjectIds as $subjectId ) {
		$subjectDetails = $result->getTableIdDetails ( $subjectId );
		$score = $marking->getTotalMarkResultEntry4CandidateSubject ( $resultSetupIds [0], $subjectId, $candidateId );
		if (! $score){
			if(!$result->checkCandidateSubject($candidateId, $subjectId, $sectionId))
				continue;
		}
		echo "<tr>
			        <td align=\"center\"><font class=\"printTableRow\">" . $i . "</font></td>
			        <td style=\"padding-left: 20px;\"><font class=\"printTableRow\">" . $subjectDetails ['subject_name'] . "</font></td>";		
		$candidateSubjectTotalScore = 0;
		foreach ( $resultSetupIds as $resultSetupId ) {
			$score = $marking->getTotalMarkResultEntry4CandidateSubject ( $resultSetupId, $subjectId, $candidateId );
			$score = $score  == "" ? 0 : $score;
			$candidateSubjectTotalScore += $score;
			echo "<td align=\"center\"><font class=\"printMark\">" . number_format($score, 1, '.', '') . "</font></th>";
			echo "<td align=\"center\"><font class=\"printMark\">" . $grading->getGradeForScore ( $score / $subjectResultTotal[$subjectId][$resultSetupId], $resultDetails ['grading_id'] )."</font></td>";
			
		}		
		++ $i;
		echo "			        
			    </tr>";
	}
	echo "        
</table>";
	
foreach ($assessmentIds as $assessmentId){
	$details = $result->getTableIdDetails($assessmentId);
	echo "<font class=\"printAssessmentNames\">".$details['assessment_name']."</font>";
	echo "<table border=\"1px\" cellpadding=\"0\" cellspacing=\"0\" style=\"width: 100%;\">";
	$i = 0;
	$activityIds = $result->getActivityIds($assessmentId, 1);	
	foreach ($activityIds as $activityId){
		if($i % 2 == 0)
			echo "<tr>";
		$score = $marking->getMark4Candidate($activityId, $candidateId);
	if (! $score){
			if(!$result->checkCandidateSubject($candidateId, $subjectId, $sectionId))
				continue;
		}
		$details = $result->getTableIdDetails($activityId);
		echo "<td align = \"right\" style=\"width: 30%; border-right: 0px\"><font class=\"printTableRow\">".$details['activity_name']."</font></td>";
		echo "<th style=\"width: 5%; border-right: 0px; border-left: 0px\">:</th>";		
		echo "<td style=\"width: 13%; border-left: 0px\" align=\"left\"><font class=\"printMark\">".$grading->getGradingOptionName($score[0])."</font></td>";
		
		if($i % 2 == 1)
			echo "</tr>";
		++$i;
	}
	if($i % 2 == 1)
		echo "</tr>";
	echo "</table>";
	
}	
$remarks = $resultSections->getCandidateData($resultSectionId, $candidateId, 'REMKS');
		$attendance = $resultSections->getCandidateData($resultSectionId, $candidateId, 'ATTND');

echo "
	<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" style=\"width: 100%; padding-top: 10px;\">
    <tr>
    	<td align=\"right\" style=\"width: 100px\"><font class=\"printMark\">Remarks</font></td>
    	<th style=\"width: 20px;\">:</th>
    	<td align=\"left\"><font class=\"printTableRow\">".$options->getOptionIdValue($remarks[1])."</font></td>
    </tr>
    <tr>
        <td height=\"10px\"></td>
    </tr>
    <tr>
    	<td align=\"right\"><font class=\"printMark\">Attendance</font></td>
    	<th>:</th>
    	<td align=\"left\"><font class=\"printTableRow\">".$attendance[1] ." /" . $totalAttendance."</font></td>
    </tr>
</table>";
echo "
<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" style=\"width: 100%; padding-top: 25px;\">
    <tr>
        <th>----------------------------</th>
        <th>----------------------------</th>
        <th>----------------------------</th>
    </tr>
    <tr>
        <td height=\"10px\"></td>
    </tr>
    <tr>        
        <th><font class=\"printMark\">Class Teacher</font></th>
        <th><font class=\"printMark\">Vice-Principal</font></th>
        <th><font class=\"printMark\">Principal</font></th>
    </tr>
</table></div>";

echo "<div style=\"page-break-after:always;\"></div>";
}
?>

<div class="clear"></div>
<div id="extraMenuListingPage" style="display:none">
    <?php
    $baseServer = $body->getBaseServer();
    if($resultDetails['grading_id'] != ""){
        $resultUrl = $baseServer."pages/exam/exam_print_result_grading.php?sessionId=".$sessionId."&resultId=".$resultId."&sectionId=".$sectionId;
        $datasheetUrl = $baseServer."pages/exam/exam_print_datasheet_grading.php?sessionId=".$sessionId."&resultId=".$resultId."&sectionId=".$sectionId;
    }else{
        $resultUrl = $baseServer."pages/exam/exam_print_result_absolute.php?sessionId=".$sessionId."&resultId=".$resultId."&sectionId=".$sectionId;
        $datasheetUrl = $baseServer."pages/exam/exam_print_datasheet_absolute.php?sessionId=".$sessionId."&resultId=".$resultId."&sectionId=".$sectionId;
    }
    ?>
    <li><a href="#" class="bookmarkedMenuListing" onclick="loadPageIntoDisplay('<?php echo $baseServer."pages/exam/exam_result_section.php?sessionId=".$sessionId."&resultId=".$resultId."&sectionId=".$sectionId; ?>')"><img src="<?php echo $baseServer; ?>images/global/b_usredit.png" alt="" />Set Attendance/Remarks Setup</a></li>
    <li><a href="#" class="bookmarkedMenuListing" onclick="loadPageIntoDisplay('<?php echo $baseServer."pages/exam/exam_result_assessment.php?sessionId=".$sessionId."&resultId=".$resultId."&sectionId=".$sectionId; ?>')"><img src="<?php echo $baseServer; ?>images/global/b_usredit.png" alt="" />Set Result Assessment</a></li>
    <li><a href="#" class="bookmarkedMenuListing" onclick="loadPageIntoDisplay('<?php echo $baseServer."pages/exam/exam_remarks_view.php?sessionId=".$sessionId."&resultId=".$resultId."&sectionId=".$sectionId; ?>')"><img src="<?php echo $baseServer; ?>images/global/b_usredit.png" alt="" />View Remarks Details</a></li>
    <li><a href="#" class="bookmarkedMenuListing" onclick="loadPageIntoDisplay('<?php echo  $datasheetUrl; ?>')"><img src="<?php echo $baseServer; ?>images/global/b_usredit.png" alt="" />Print Datasheet</a></li>
    <li><a href="#" class="bookmarkedMenuListing" onclick="loadPageIntoDisplay('<?php echo  $resultUrl; ?>')"><img src="<?php echo $baseServer; ?>images/global/b_usredit.png" alt="" />Print Candidate Result</a></li>
</div>