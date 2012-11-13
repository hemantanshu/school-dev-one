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

$resultId = $_GET ['resultId'];
$sectionId = $_GET ['sectionId'];

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

$totalMark = 125;
if($sectionDetails['class_id'] == "CLASS15")
	$totalMark = 75;

$candidateIds = $candidate->getCandidate4Section ( $sectionId, 1 );
$i = 0;
foreach ($candidateIds as $candidateId){
	foreach ( $subjectIds as $subjectId ) {		
		foreach ( $resultSetupIds as $resultSetupId ) {
			$score = $marking->getTotalMarkResultEntry4CandidateSubject ( $resultSetupId, $subjectId, $candidateId );
			$totalScore[$i] += $score[0];			
		}
	}
	++ $i;
}

rsort($totalScore);
$assessmentIds = $result->getResultAssessment ( $resultId, $sectionId, 1 );

?>

<html>
<head>
<style type="text/css">
.break {
	page-break-before: always;
}

font.printAssessmentNames {
	font-family: "Armorath Stencil Serif", Arial, "Arabic Typesetting";
	font-size: 17px;
	letter-spacing: 2px;
	word-spacing: 4px;
	line-height: 40px;
	font-weight: bolder;
}

font.printTopHeaderNames {
	font-family: "Armorath Stencil Serif", Arial, "Arabic Typesetting";
	font-size: 20px;
	letter-spacing: 3px;
	word-spacing: 4px;
	font-weight: bolder;

    text-decoration: underline;
    text-align: center;

	
}


font.printSubjectHeader{
    font-family: "Armorath Stencil Serif", Arial, "Arabic Typesetting";
    font-size: 16px;
    letter-spacing: 2px;
    word-spacing: 4px;
    line-height: 25px;
    font-weight: bolder;
}

font.printTableRow{
    font-family: "Armorath Stencil Serif", Arial, "Arabic Typesetting";
    font-size: 14px;
    letter-spacing: 1.5px;
    word-spacing: 3px;
    line-height: 20px;
    font-weight: bold;
    text-align: center;
}

font.printMark{
    font-family: "Armorath Stencil Serif", Arial, "Arabic Typesetting";
    font-size: 14px;
    letter-spacing: 2.5px;
    word-spacing: 3px;
    line-height: 20px;
    font-weight: bolder;
    text-align: center;
}


font.printSmall {
	font-family: "Armorath Stencil Serif", Arial, "Arabic Typesetting";
	font-size: 50px;
	letter-spacing: 4px;
	word-spacing: 3px;
	line-height: 30px;
	font-weight: bold;
}
</style>
<style type="text/css" media="print">
#print {
	display: none;
}

#logout {
	display: none;
}
</style>
</head>
<body onLoad="window.print()" style="margin: auto; width: 90%">
<?php

foreach ( $candidateIds as $candidateId ) {
	$totalCandidateMark = 0;
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
        <td align=\"center\"><font class=\"printSubjectHeader\">Subject</font></td>
		<td align=\"center\"><font class=\"printSubjectHeader\">M.M</font></td>";
	
	foreach ( $resultSetupIds as $resultSetupId ) {
		$details = $result->getTableIdDetails ( $resultSetupId );
		echo "<td align=\"center\"><font class=\"printSubjectHeader\">" . $details ['display_name'] . "</font></td>";
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
			        <td style=\"padding-left: 20px;\"><font class=\"printTableRow\">" . $subjectDetails ['subject_name'] . "</font></td>
					<td align=\"center\"><font class=\"printTableRow\">25</font></td>";
		foreach ( $resultSetupIds as $resultSetupId ) {
			$score = $marking->getTotalMarkResultEntry4CandidateSubject ( $resultSetupId, $subjectId, $candidateId );
			$score = $score [0] == "" ? 0 : $score[0];
			$totalCandidateMark += $score;
			echo "<td align=\"center\"><font class=\"printMark\">" . $score . "</font></th>";			
		}
		++ $i;
		echo "			        
			    </tr>";
	}
	echo "
	<tr>
		<td colspan=\"20\">
			<table border = \"0\" width=\"100%\">
				<tr>
					<td width=\"33%\" align=\"center\"><font class=\"printTableRow\">Total Score : ".$totalCandidateMark."</font></td>
					<td width=\"33%\" align=\"center\"><font class=\"printTableRow\">Percentage : ".number_format(($totalCandidateMark / $totalMark * 100), 2)." %</font></td>
					<td width=\"33%\" align=\"center\"><font class=\"printTableRow\">Rank : ".$marking->getRankOnArray($totalCandidateMark, $totalScore)."</font></td>
				</tr>
			</table>
		</td>
	</tr>        
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
		if(!$score)
			continue;
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
$remarks = $resultSections->getCandidateRemarks($resultSectionId, $candidateId);
$attendance = $resultSections->getCandidateAttendance($resultSectionId, $candidateId);

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
<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" style=\"width: 100%; padding-top: 40px;\">
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
</body>
<html>
<body>