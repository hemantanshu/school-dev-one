<?php
require_once 'config.php';

require_once BASE_PATH . 'include/exam/class.resultMarking.php';
require_once BASE_PATH . 'include/exam/class.result.php';
require_once BASE_PATH . 'include/exam/class.resultGrade.php';
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
$resultSections = new ResultSections ();
$personalInfo = new personalInfo ();
$body = new body();
$resultGrade = new ResultGrade();

$body->startBody("exam", "LMENUL120", "Exam Section Result Print");

$resultId = $_GET ['resultId'];
$sectionId = $_GET ['sectionId'];
$sessionId = $_GET['sessionId'];

$resultDetails = $result->getTableIdDetails ( $resultId );
$sectionDetails = $result->getTableIdDetails ( $sectionId );
$classDetails = $result->getTableIdDetails ( $sectionDetails ['class_id'] );
$classDetails = $result->getTableIdDetails ( $classDetails ['class_id'] );

$resultSetupIds = $result->getResultSetupIds ( $resultId , 1);
$subjectComponents = $result->getResultSubjectComponentIds($resultId, $sectionId);
$subjectIds = $result->getResultSubjectIds($resultId, $sectionId);

$resultSectionId = $resultSections->getResultSectionId ( $resultId, $sectionId );
$resultSectionDetails = $result->getTableIdDetails ( $resultSectionId );

$totalAttendance = $resultSectionDetails ['total_attendance'];
$sessionDetails = $result->getTableIdDetails ( $resultDetails ['session_id'] );

$totalMark = $resultSectionDetails['total_mark'];
$flag = array();

$candidateIds = $candidate->getCandidate4Section ( $sectionId, 1 );


foreach ($subjectComponents as $subjectComponent){
	$subjectId = $subjectComponent[0];
	$componentId = $subjectComponent[1];	
	foreach ($resultSetupIds as $resultSetupId){
		$resultDetails = $result->getTableIdDetails($resultSetupId);
		$subjectResultComponentTotal[$resultSetupId][$subjectId][$componentId] = $resultDetails['weightage']*.01*$result->getResultExaminationSubjectComponentTotal($resultDetails['examination_id'], $sectionId, $subjectId, $componentId);
		$subjectTotal[$subjectId][$componentId] += $resultDetails['weightage']*.01*$result->getResultExaminationSubjectComponentTotal($resultDetails['examination_id'], $sectionId, $subjectId, $componentId);
	}
}
$assessmentIds = $result->getResultAssessment ( $resultId, $sectionId, 1 );
?>
<input type="hidden" name="resultId" id="resultId" value="<?php echo $resultId;  ?>" />
<input type="hidden" name="sectionId" id="sectionId" value="<?php echo $sectionId;  ?>" />
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
            <td align=\"left\"><font class=\"printMark\">" . $body->getOptionIdValue($registrationDetails['house_id']) . "</font></td>
        </tr>
    </table>";
    echo "
    <table border=\"1\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\">
        <tr>
            <td width=\"50%\" style=\"vertical-align: top;\" align=\"center\">
            <font class=\"printAssessmentNames\">Part 1A : Academic Assessment</font>
                <table border=\"1px\" style=\"border-style: solid\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\">
                    <tr>
                        <td align=\"center\"><font class=\"printSubjectHeader\">Subject</font></td>";

                    foreach ( $resultSetupIds as $resultSetupId ) {
                        $details = $result->getTableIdDetails ( $resultSetupId );
                        echo "<td align=\"center\"><font class=\"printSubjectHeader\">" . $details ['display_name'] . "</font></td>";
                    }
                    echo "
                    </tr>";
                    $i = $j = 1;

                    foreach ( $subjectComponents as $subjectComponent ) {
                        $subjectId = $subjectComponent[0];
                        $componentId = $subjectComponent[1];
                        $subjectDetails = $result->getTableIdDetails ( $subjectId );
                        $componentDetails = $componentId == '' ? '' : $result->getTableIdDetails($componentId);

                        if(!$result->checkCandidateSubject($candidateId, $subjectId, $sectionId))
                            continue;

                        echo "<tr>";
                        if($subjectTotal[$subjectId][''] == ''){
                            if(!$flag[$candidateId][$subjectId]){
                                echo "	<td style=\"padding-left: 20px;\"><font class=\"printTableRow\">" . $subjectDetails ['subject_name'] . "</font></td>";
                                foreach ( $resultSetupIds as $resultSetupId ) {
                                    echo "<td align=\"center\" ><font class=\"printMark\"></font></td>";

                                }
                                $flag[$candidateId][$subjectId] = true;
                                echo "</tr><tr>";
                                ++$j;
                            }
                        }
                        if($componentDetails['subject_component_name'] == ''){
                            echo "	<td style=\"padding-left: 20px;\"><font class=\"printTableRow\">" . $subjectDetails ['subject_name'] . "</font></td>";
                            ++$j;
                        }else{
                            echo "	<td style=\"padding-left: 40px;\" class=\"no-border-top\"><font class=\"printTableRow\"> - ".$componentDetails['subject_component_name']."</font></td>";
                        }


                        foreach ( $resultSetupIds as $resultSetupId ) {
                            $score = $marking->getTotalMarkResultEntry4CandidateSubjectComponent( $resultSetupId, $subjectId, $componentId, $candidateId , 1);
                            $gradeType = $resultGrade->getResultSubjectComponentGradeData($resultId, $sectionId, 'LRESER25', $subjectId, $componentId);
                            if($componentDetails['subject_component_name'] == ''){
                                if($gradeType)
                                    echo "<td align=\"center\"><font class=\"printMark\">" . $grading->getGradeForScore($score / $subjectResultComponentTotal[$resultSetupId][$subjectId][$componentId], $gradeType) . "</font><br /></td>";
                                else
                                    echo "<td align=\"center\"><font class=\"printMark\">" . number_format($score, 1, '.', '') . "</font><br /></td>";
                            }else{
                                if($gradeType)
                                    echo "<td align=\"center\" class=\"no-border-top\"><font class=\"printMark\">" . $grading->getGradeForScore($score / $subjectResultComponentTotal[$resultSetupId][$subjectId][$componentId], $gradeType) . "</font><br /></td>";
                                else
                                    echo "<td align=\"center\" class=\"no-border-top\"><font class=\"printMark\">" . number_format($score, 1, '.', '') . "</font><br /></td>";
                            }
                        }
                        ++ $i;
                        echo "
                                </tr>";
                    }
                    echo "
                </table>
            </td>
            <td width=\"50%\" style=\"vertical-align: top;\" align=\"center\">";
            foreach ($assessmentIds as $assessmentId){
                $details = $result->getTableIdDetails($assessmentId);
                echo "<font class=\"printAssessmentNames\">".$details['assessment_name']."</font>";
                echo "<table border=\"1px\" cellpadding=\"0\" cellspacing=\"0\" style=\"width: 100%;\">";
                $i = 0;
                $activityIds = $result->getActivityIds($assessmentId, 1);
                foreach ($activityIds as $activityId){
                        echo "<tr>";
                    $score = $marking->getMark4Candidate($activityId, $candidateId);
                    if(!$score)
                        continue;
                    $details = $result->getTableIdDetails($activityId);
                    echo "<td align = \"right\" style=\"width: 30%; border-right: 0px\"><font class=\"printTableRow\">".$details['activity_name']."</font></td>";
                    echo "<th style=\"width: 5%; border-right: 0px; border-left: 0px\">:</th>";
                    echo "<td style=\"width: 13%; border-left: 0px\" align=\"left\"><font class=\"printMark\">".$grading->getGradingOptionName($score[0])."</font></td>";

                        echo "</tr>";
                    ++$i;
                }
                echo "</table>";

            }
        echo "
            </td>
        </tr>
    </table>";



    $remarks = $resultSections->getCandidateData($resultSectionId, $candidateId, 'REMKS');
		$attendance = $resultSections->getCandidateData($resultSectionId, $candidateId, 'ATTND');

    echo "
	<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" style=\"width: 100%; padding-top: 10px;\">
    <tr>
    	<td align=\"right\" style=\"width: 100px\"><font class=\"printMark\">Remarks</font></td>
    	<th style=\"width: 20px;\">:</th>
    	<td align=\"left\"><font class=\"printTableRow\">".$body->getOptionIdValue($remarks[1])."</font></td>
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

<div class="clear"></div>
