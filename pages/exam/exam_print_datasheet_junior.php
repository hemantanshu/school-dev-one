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

$body->startBody ( "exam", "LMENUL121", "Section Datasheet" );

$resultId = $_GET ['resultId'];
$sectionId = $_GET ['sectionId'];
$sessionId = $_GET['sessionId'];

$resultDetails = $result->getTableIdDetails ( $resultId );
$sectionDetails = $result->getTableIdDetails ( $sectionId );
$classDetails = $result->getTableIdDetails ( $sectionDetails ['class_id'] );
$classDetails = $result->getTableIdDetails ( $classDetails ['class_id'] );
$sessionDetails = $result->getTableIdDetails ( $resultDetails ['session_id'] );

$resultSetupIds = $result->getResultSetupIds ( $resultId, 1 );
$subjectIds = $result->getResultSubjectComponentIds($resultId, $sectionId);
$resultSectionId = $resultSections->getResultSectionId ( $resultId, $sectionId );
$details = $result->getTableIdDetails ( $resultSectionId );
$totalAttendance = $details ['total_attendance'];

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
?>
<input type="hidden" name="resultId" id="resultId" value="<?php echo $resultId;  ?>" />
<input type="hidden" name="sectionId" id="sectionId" value="<?php echo $sectionId;  ?>" />
<table border="0" width="100%">
    <tr>
        <td width="15%" align="right"><font class="printMark">Result</font></td>
        <th width="3%">:</th>
        <td width="30%" align="left"><font class="printMark"><?php echo $resultDetails['display_name']; ?></font></td>
        <td width="15%" align="right"><font class="printMark">Session</font></td>
        <th width="3%">:</th>
        <td width="30%" align="left"><font class="printMark"><?php echo $sessionDetails ['session_name'];?></font></td>
    </tr>
    <tr>
        <td align="right"><font class="printMark">Class </font></td>
        <th>:</th>
        <td align="left"><font class="printMark"><?php echo $classDetails ['class_name'] . " " . $sectionDetails ['section_name']; ?></font></td>
    </tr>
</table>
<table border="1px" style="border-style: solid;" cellpadding="0"
       cellspacing="0" width="100%">
    <tr>
        <td rowspan="2"><font class="printTableRow">S.N</font></td>
        <td rowspan="2" style="width: 70px"><font class="printTableRow">Adm.
            No</font></td>
        <td rowspan="2" style="width: 200px"><font class="printTableRow">Candidate
            Name</font></td>
        <?php        
        $columnSpan = count($resultSetupIds) > 1 ? count($resultSetupIds) : 1;
        foreach ( $subjectIds as $subjectIdDetails ) {
            $subjectDetails = $result->getTableIdDetails ( $subjectIdDetails[0] );
            $subjectComponentDetails = $result->getTableIdDetails ( $subjectIdDetails[1] );
            echo "<th colspan=\"" . $columnSpan . "\"><font class=\"printTableRow\">" . $subjectDetails ['subject_name'] . "<br />".$subjectComponentDetails['subject_component_name']."</font></th>";
        }
        ?>
    </tr>
    <tr>
        <?php
        foreach ( $subjectIds as $subjectId ) {
            foreach ( $resultSetupIds as $resultSetupId ) {
                $details = $result->getTableIdDetails ( $resultSetupId );
                echo "<th><font class=\"printTableRow\">" . $details ['display_name'] . "</font></th>";
            }  
        }	

        ?>
    </tr>
    <?php
    $candidateIds = $candidate->getCandidate4Section ( $sectionId, 1 );
    $i = 1;
    foreach ( $candidateIds as $candidateId ) {
        $totalScore [$i] = 0;
        echo "<tr>
				<td><font class=\"printCandidateInfo\">$i</font></td>
				<td><font class=\"printCandidateInfo\">" . $registration->getCandidateRegistrationNumber ( $candidateId ) . "</font></td>
				<td><font class=\"printCandidateInfo\">" . $candidate->getOfficerName ( $candidateId ) . "</font></td>";

        foreach ( $subjectIds as $subjectIdDetails ) {
			$subjectId = $subjectIdDetails[0];
			$componentId = $subjectIdDetails[1];        	
            foreach ( $resultSetupIds as $resultSetupId ) {				
				
                $score = $marking->getTotalMarkResultEntry4CandidateSubjectComponent($resultSetupId, $subjectId, $componentId, $candidateId, 1);
                $gradeType = $resultGrade->getResultSubjectComponentGradeData($resultId, $sectionId, 'LRESER26', $subjectId, $componentId);
                if($gradeType)                
                	echo "<td align=\"center\"><font class=\"printMark\">" . $grading->getGradeForScore($score / $subjectResultComponentTotal[$resultSetupId][$subjectId][$componentId], $gradeType) . "</font><br /></td>";
                else
                	echo "<td align=\"center\"><font class=\"printMark\">" . number_format($score, 1, '.', '') . "</font><br /></td>";
            }
        }
        echo "</tr>";
        ++ $i;

    }
    ?>
</table>
<div style="page-break-after: always;"></div>
<table border="0" width="100%">
    <tr>
        <td width="15%" align="right"><font class="printMark">Result</font></td>
        <th width="3%">:</th>
        <td width="30%" align="left"><font class="printMark"><?php echo $resultDetails['display_name']; ?></font></td>
        <td width="15%" align="right"><font class="printMark">Session</font></td>
        <th width="3%">:</th>
        <td width="30%" align="left"><font class="printMark"><?php echo $sessionDetails ['session_name'];?></font></td>
    </tr>
    <tr>
        <td align="right"><font class="printMark">Class </font></td>
        <th>:</th>
        <td align="left"><font class="printMark"><?php echo $classDetails ['class_name'] . " " . $sectionDetails ['section_name']; ?></font></td>
    </tr>
</table>
<table border="1px" style="border-style: solid;" cellpadding="0"
       cellspacing="0" width="100%">
    <tr>
        <td rowspan="2" width="20px"><font class="printTableRow">SN</font></td>

        <?php
        $i = 0;
        foreach ( $assessmentIds as $assessmentId ) {
            $details = $result->getTableIdDetails ( $assessmentId );
            echo "<th colspan=\"" . count ( $activityIds [$i] ) . "\"><font class=\"printTableRow\">" . $details ['assessment_name'] . "</font></th>";
            ++ $i;
        }
        ?>
    </tr>
    <tr>
        <?php
        foreach ( $assessmentIds as $assessmentId ) {
            $activityIds = $result->getActivityIds ( $assessmentId, 1 );
            foreach ( $activityIds as $activityId ) {
                $details = $result->getTableIdDetails ( $activityId );
                $details = $result->getTableIdDetails ( $details['subject_id'] );
                echo "<td align=\"center\"  style=\"width: 60px\"><font class=\"printTableRow\">" . $details ['subject_code'] . "</font></td>";
            }
        }
        ?>
    </tr>
    <?php
    $candidateIds = $candidate->getCandidate4Section ( $sectionId, 1 );
    $i = 1;
    foreach ( $candidateIds as $candidateId ) {
        echo "<tr><td><font class=\"printTableRow\">$i</font></td>";
        foreach ( $assessmentIds as $assessmentId ) {
            $activityIds = $result->getActivityIds ( $assessmentId, 1 );
            foreach ( $activityIds as $activityId ) {
                $score = $marking->getMark4Candidate ( $activityId, $candidateId );
                echo "<td align=\"center\"><font class=\"printMark\">" . $grading->getGradingOptionName ( $score [0] ) . "</font><br /></td>";
            }
        }
        echo "</tr>";
        ++ $i;

    }

    ?>
</table>
<div style="page-break-after: always;"></div>
<table border="0" width="100%">
    <tr>
        <td width="15%" align="right"><font class="printMark">Result</font></td>
        <th width="3%">:</th>
        <td width="30%" align="left"><font class="printMark"><?php echo $resultDetails['display_name']; ?></font></td>
        <td width="15%" align="right"><font class="printMark">Session</font></td>
        <th width="3%">:</th>
        <td width="30%" align="left"><font class="printMark"><?php echo $sessionDetails ['session_name'];?></font></td>
    </tr>
    <tr>
        <td align="right"><font class="printMark">Class </font></td>
        <th>:</th>
        <td align="left"><font class="printMark"><?php echo $classDetails ['class_name'] . " " . $sectionDetails ['section_name']; ?></font></td>
    </tr>
</table>
<table border="1px" style="border-style: solid;" cellpadding="0"
       cellspacing="0" width="100%">
    <tr>
        <td style="width: 50px" height="15px"></td>
        <td rowspan="2"><font class="printTableRow">Attendance</font></td>
        <td rowspan="2"><font class="printTableRow">Remarks</font></td>
    </tr>
    <tr>
        <td height="15px"></td>

    </tr>
    <?php
    $candidateIds = $candidate->getCandidate4Section ( $sectionId, 1 );
    $i = 1;
    foreach ( $candidateIds as $candidateId ) {
        echo "<tr><td><font class=\"printTableRow\">$i</font></td>
		";
        $remarks = $resultSections->getCandidateData($resultSectionId, $candidateId, 'REMKS');
        $attendance = $resultSections->getCandidateData($resultSectionId, $candidateId, 'ATTND');
        echo "<td align=\"center\"><font class=\"printMark\">" . $attendance [1] . "/" . $totalAttendance . "</font></td>";
        echo "<td><font class=\"printMark\">" . $options->getOptionIdValue ( $remarks [1] ) . "</font><br /></td>";
        echo "</tr>";
        ++ $i;

    }

    ?>
</table>
<br /><br />
<div class="clear"></div>