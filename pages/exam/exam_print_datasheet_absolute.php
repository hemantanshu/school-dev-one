<?php
require_once 'config.php';

require_once BASE_PATH . 'include/exam/class.resultMarking.php';
require_once BASE_PATH . 'include/exam/class.result.php';
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
$body = new body();

$body->startBody("exam", "LMENUL96", "Exam Result Datasheet Printing");

$resultId = $_GET ['resultId'];
$sectionId = $_GET ['sectionId'];
$sessionId = $_GET['sessionId'];

$resultDetails = $result->getTableIdDetails ( $resultId);
$sectionDetails = $result->getTableIdDetails ( $sectionId );
$classDetails = $result->getTableIdDetails ( $sectionDetails ['class_id'] );
$classDetails = $result->getTableIdDetails ( $classDetails ['class_id'] );
$sessionDetails = $result->getTableIdDetails ( $resultDetails ['session_id'] );

$resultSetupIds = $result->getResultSetupIds ( $resultId , 1);
$subjectIds = $result->getResultSubjectIds ( $resultId, $sectionId );

$resultSectionId = $resultSections->getResultSectionId ( $resultId, $sectionId );
$details = $result->getTableIdDetails ( $resultSectionId );
$totalAttendance = $details ['total_attendance'];
$totalMark = $details['total_mark'];

$candidateIds = $candidate->getCandidate4Section ( $sectionId, 1 );


$assessmentIds = $result->getResultAssessment ( $resultId, $sectionId, 1 );
$i = 0;
foreach ( $assessmentIds as $assessmentId ) {
    $activityIds [$i] = $result->getActivityIds ( $assessmentId, 1 );
    ++ $i;
}

$i = 0;
foreach ($candidateIds as $candidateId){
	foreach ( $subjectIds as $subjectId ) {
		foreach ( $resultSetupIds as $resultSetupId ) {
			$score = $marking->getTotalMarkResultEntry4CandidateSubject ( $resultSetupId, $subjectId, $candidateId ,1);
			$totalScore[$i] += $score;
		}
	}
	++ $i;
}
rsort($totalScore);
?>


    <table border = "0" width="100%">
        <tr>
            <td width="15%" align="right"><font class="printTableRow">Result</font></td>
            <th width="3%">:</th>
            <td width="30%" align="left"><font class="printMark"><?php echo $resultDetails['display_name']; ?></font></td>
            <td width="15%" align="right"><font class="printTableRow">Session</font></td>
            <th width="3%">:</th>
            <td width="30%" align="left"><font class="printMark"><?php echo $sessionDetails ['session_name'];?></font></td>
        </tr>
        <tr>
            <td align="right"><font class="printTableRow">Class </font></td>
            <th>:</th>
            <td align="left"><font class="printMark"><?php echo $classDetails ['class_name'] . " " . $sectionDetails ['section_name']; ?></font></td>
        </tr>
    </table>
    <table border="1px" style="border-style: solid;" cellpadding="0" cellspacing="0">
        <tr>
            <td rowspan="2"><font class="printTableRow">S.N</font></td>
            <td rowspan="2" style="width: 70px"><font class="printTableRow">Adm. No</font></td>
            <td rowspan="2" style="width: 200px"><font class="printTableRow">Candidate Name</font></td>
            <?php
            foreach ( $subjectIds as $subjectId ) {
                $details = $result->getTableIdDetails ( $subjectId );
                echo "<th colspan=\"" . count ( $resultSetupIds ) . "\"><font class=\"printTableRow\">" . $details ['subject_code'] . "</font></th>";
            }
            ?>
            <th rowspan="2"><font class="printTableRow">Total Marks</font></th>
            <th rowspan="2"><font class="printTableRow">Percentage</font></th>
            <th rowspan="2"><font class="printTableRow">Ranks</font></th>
        </tr>
        <tr>
            <?php
            foreach ( $subjectIds as $subjectId ) {
                foreach ( $resultSetupIds as $resultSetupId ) {
                    $details = $result->getTableIdDetails ( $resultSetupId );
                    echo "<td><font class=\"printTableRow\">" . $details ['display_name'] . "</font></td>";
                }
            }


            ?>
        </tr>
        <?php
        
        $i = 1;
        foreach ( $candidateIds as $candidateId ) {
            $candidateTotalScore = 0;
            echo "<tr>
				<td><font class=\"printCandidateInfo\">$i</font></td>
				<td><font class=\"printCandidateInfo\">" . $registration->getCandidateRegistrationNumber ( $candidateId ) . "</font></td>
				<td><font class=\"printCandidateInfo\">" . $candidate->getOfficerName ( $candidateId ) . "</font></td>";

            foreach ( $subjectIds as $subjectId ) {
                foreach ( $resultSetupIds as $resultSetupId ) {
                    $score = $marking->getTotalMarkResultEntry4CandidateSubject ( $resultSetupId, $subjectId, $candidateId, 1 );
                    $candidateTotalScore += $score;
                    echo "<td align=\"center\"><font class=\"printMark\">" . $score . "</font><br /></td>";
                }
            }
            echo "<td align=\"center\"><font class=\"printMark\">" . number_format($candidateTotalScore, 2, '.', '') . "</font></td>";
            echo "<td align=\"center\"><font class=\"printMark\">" . number_format($candidateTotalScore/$totalMark*100, 2) . " %</font></td>";
            echo "<td align=\"center\"><font class=\"printMark\">" . $marking->getRankOnArray($candidateTotalScore, $totalScore) . "</font><br /></td>";
            echo "</tr>";
            ++ $i;

        }
        ?>
    </table>
    <div style="page-break-after:always;"></div>
    <table border = "0" width="100%">
        <tr>
            <td width="15%" align="right"><font class="printTableRow">Result</font></td>
            <th width="3%">:</th>
            <td width="30%" align="left"><font class="printMark"><?php echo $resultDetails['display_name']; ?></font></td>
            <td width="15%" align="right"><font class="printTableRow">Session</font></td>
            <th width="3%">:</th>
            <td width="30%" align="left"><font class="printMark"><?php echo $sessionDetails ['session_name'];?></font></td>
        </tr>
        <tr>
            <td align="right"><font class="printTableRow">Class </font></td>
            <th>:</th>
            <td align="left"><font class="printMark"><?php echo $classDetails ['class_name'] . " " . $sectionDetails ['section_name']; ?></font></td>
        </tr>
    </table>
    <table border="1px" style="border-style: solid;" cellpadding="0" cellspacing="0">
        <tr>
            <td rowspan="2"><font class="printTableRow">SN</font></td>

            <?php
            $i = 0;
            foreach ( $assessmentIds as $assessmentId ) {
                $details = $result->getTableIdDetails ( $assessmentId );
                echo "<td colspan=\"" . count ( $activityIds [$i] ) . "\"><font class=\"printTableRow\">" . $details ['assessment_name'] . "</font></td>";
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
                    echo "<td align=\"center\"><font class=\"printTableRow\">" . $details ['activity_name'] . "</font></td>";
                }
            }
            ?>
        </tr>
        <?php
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
    <div style="page-break-after:always;"></div>
    <table border = "0" width="100%">
        <tr>
            <td width="15%" align="right"><font class="printTableRow">Result</font></td>
            <th width="3%">:</th>
            <td width="30%" align="left"><font class="printMark"><?php echo $resultDetails['display_name']; ?></font></td>
            <td width="15%" align="right"><font class="printTableRow">Session</font></td>
            <th width="3%">:</th>
            <td width="30%" align="left"><font class="printMark"><?php echo $sessionDetails ['session_name'];?></font></td>
        </tr>
        <tr>
            <td align="right"><font class="printTableRow">Class </font></td>
            <th>:</th>
            <td align="left"><font class="printMark"><?php echo $classDetails ['class_name'] . " " . $sectionDetails ['section_name']; ?></font></td>
        </tr>
    </table>

    <table border="1px" style="border-style: solid;" cellpadding="0" cellspacing="0">
        <tr>
            <th style="width: 50px"></th>
            <th rowspan="2"><font class="printTableRow">Attendance.</th>
            <th rowspan="2"><font class="printTableRow">Remarks</font></th>            
        </tr>
        <tr>
            <td></td>

        </tr>
        <?php                
        $i = 1;
        foreach ( $candidateIds as $candidateId ) {
            echo "<td align=\"center\"><font class=\"printMark\">" . $i . "</font></td>";
            $remarks = $resultSections->getCandidateData($resultSectionId, $candidateId, 'REMKS');
		$attendance = $resultSections->getCandidateData($resultSectionId, $candidateId, 'ATTND');
            echo "<td align=\"center\"><font class=\"printMark\">" . $attendance [1] . "/" . $totalAttendance . "</font></td>";
            echo "<td><font class=\"printMark\">" . $options->getOptionIdValue ( $remarks [1] ) . "</font></td>";
           
            echo "</tr>";
            ++$i;

        }

        ?>
    </table>
        <br /><br />
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
    <li><a href="#" class="bookmarkedMenuListing" onclick="loadPageIntoDisplay('<?php echo  $datasheetUrl; ?>')"><img src="<?php echo $baseServer; ?>images/global/b_usredit.png" alt="" />Print Section Datasheet</a></li>
    <li><a href="#" class="bookmarkedMenuListing" onclick="loadPageIntoDisplay('<?php echo  $resultUrl; ?>')"><img src="<?php echo $baseServer; ?>images/global/b_usredit.png" alt="" />Print Candidate Result</a></li>
</div>