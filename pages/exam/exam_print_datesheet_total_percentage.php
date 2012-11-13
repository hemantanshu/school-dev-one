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

$body->startBody("exam", "LMENUL147", "Exam Result Datasheet Printing Percentage Rank");

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


$assessmentIds = $result->getResultAssessment ( $resultId, $sectionId, 1 );
$i = 0;
foreach ( $assessmentIds as $assessmentId ) {
    $activityIds [$i] = $result->getActivityIds ( $assessmentId, 1 );
    ++ $i;
}

$candidateIds = $candidate->getCandidate4Section ( $sectionId, 1 );
$totalMarkArray = array();
foreach($candidateIds as $candidateId){
	array_push($totalMarkArray, $marking->getCandidateTotalMark4Result($candidateId, $resultId));
}
rsort($totalMarkArray);
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
    <table border="1" style="border-style: solid;" cellpadding="0" cellspacing="0" id="totalMarkListing">
        <tr>
            <td>S.N</td>
            <td>Adm. No</font></td>
            <td>Candidate Name</font></td>            
            <?php
            foreach ( $subjectIds as $subjectId ) {
            	$subjectDetails = $result->getTableIdDetails($subjectId);
                foreach ( $resultSetupIds as $resultSetupId ) {
                    $details = $result->getTableIdDetails ( $resultSetupId );
                    echo "<td>" . $details ['display_name'] . " - ".$subjectDetails['subject_code']."</td>";
                }
            }
            foreach ( $assessmentIds as $assessmentId ) {
            	$activityIds = $result->getActivityIds ( $assessmentId, 1 );
            	foreach ( $activityIds as $activityId ) {
            		$details = $result->getTableIdDetails ( $activityId );
            		$details = $result->getTableIdDetails ( $details['subject_id'] );
                	echo "<td>" . $details ['subject_code'] . "</td>";
            	}
            }
            ?>
            <th>Attendance</th>
            <th>Remarks</th>
            <th>Total Marks</th>
            <th>Percentage</th>
            <th>Ranks</th>
        </tr>
        <?php
        $i = 1;
        foreach ( $candidateIds as $candidateId ) {
            echo "<tr>
				<td>$i</font></td>
				<td>" . $registration->getCandidateRegistrationNumber ( $candidateId ) . "</td>
				<td>" . $candidate->getOfficerName ( $candidateId ) . "</td>";
            foreach ( $subjectIds as $subjectId ) {
                foreach ( $resultSetupIds as $resultSetupId ) {
                    $score = $marking->getTotalMarkResultEntry4CandidateSubject ( $resultSetupId, $subjectId, $candidateId, 1 );
                    $totalScore += $score;
                    echo "<td>" . $score . "</td>";
                }
            }
            foreach ( $assessmentIds as $assessmentId ) {
            	$activityIds = $result->getActivityIds ( $assessmentId, 1 );
            	foreach ( $activityIds as $activityId ) {
            		$score = $marking->getMark4Candidate ( $activityId, $candidateId );            
            		echo "<td>" . $grading->getGradingOptionName ( $score [0] ) . "</td>";
            	}
            }
            
            $remarks = $resultSections->getCandidateData($resultSectionId, $candidateId, 'REMKS');
            $attendance = $resultSections->getCandidateData($resultSectionId, $candidateId, 'ATTND');
            
            echo "<td>" . $attendance [1] . "/" . $totalAttendance . "</td>";
            echo "<td>" . $options->getOptionIdValue ( $remarks [1] ) . "</td>";
            echo "<td>" . $totalScore . "</td>";
            echo "<td>" . number_format($totalScore/$totalMark*100, 2) . " %</td>";
            echo "<td>" . $marking->getRankOnArray($totalScore, $totalMarkArray) . "</td>";
            
            echo "</tr>";
            ++ $i;

        }
        ?>
    </table>
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