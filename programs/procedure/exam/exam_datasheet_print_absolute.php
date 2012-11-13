<?php
require_once 'config.php';

require_once BASE_PATH . 'include/exam/class.resultMarking.php';
require_once BASE_PATH . 'include/exam/class.result.php';
require_once BASE_PATH . 'include/exam/class.resultSections.php';
require_once BASE_PATH . 'include/utility/class.candidate.php';
require_once BASE_PATH . 'include/utility/class.registration.php';
require_once BASE_PATH . 'include/exam/class.grading.php';
require_once BASE_PATH . 'include/global/class.options.php';

$result = new Result ();
$candidate = new Candidate ();
$marking = new ResultMarking ();
$registration = new registrationInfo ();
$grading = new Grading ();
$options = new options ();
$resultSections = new ResultSections ();

$resultId = $_GET ['resultId'];
$sectionId = $_GET ['sectionId'];

$resultDetails = $result->getTableIdDetails ( $resultId );
$sectionDetails = $result->getTableIdDetails ( $sectionId );
$classDetails = $result->getTableIdDetails ( $sectionDetails ['class_id'] );
$classDetails = $result->getTableIdDetails ( $classDetails ['class_id'] );
$sessionDetails = $result->getTableIdDetails ( $resultDetails ['session_id'] );

$resultSetupIds = $result->getResultSetupIds ( $resultId );
$subjectIds = $result->getResultSubjectIds ( $resultId, $sectionId );
$resultSectionId = $resultSections->getResultSectionId ( $resultId, $sectionId );
$details = $result->getTableIdDetails ( $resultSectionId );
$totalAttendance = $details ['total_attendance'];
$totalMark = 125;

$sectionDetails = $result->getTableIdDetails($sectionId);
if($sectionDetails['class_id'] == "CLASS15")
	$totalMark = 75;

$assessmentIds = $result->getResultAssessment ( $resultId, $sectionId, 1 );
$i = 0;
foreach ( $assessmentIds as $assessmentId ) {
	$activityIds [$i] = $result->getActivityIds ( $assessmentId, 1 );
	++ $i;
}
?>


<html>
<head>
<style type="text/css">
.break {
	page-break-before: always;
}




font.printTableRow{
    font-family: "Armorath Stencil Serif", Arial, "Arabic Typesetting";
    font-size: 10px;
    letter-spacing: 1px;
    word-spacing: 2px;
    line-height: 12px;
    font-weight: bold;
    text-align: center;
}

font.printCandidateInfo{
    font-family: "Armorath Stencil Serif", Arial, "Arabic Typesetting";
    font-size: 12px;
    letter-spacing: 1.5px;
    word-spacing: 2px;
    line-height: 12px;
    font-weight: bold;
    text-align: center;
}

font.printMark{
    font-family: "Armorath Stencil Serif", Arial, "Arabic Typesetting";
    font-size: 12px;
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

	<div style="overflow: auto">
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
		<table border="1" style="border-style: solid;" cellpadding="0" cellspacing="0">
			<tr>
				<td rowspan="2"><font class="printTableRow">S.N</font></td>
				<td rowspan="2" style="width: 70px"><font class="printTableRow">Adm. No</font></td>
				<td rowspan="2" style="width: 200px"><font class="printTableRow">Candidate Name</font></td>
		<?php
		foreach ( $subjectIds as $subjectId ) {
			$details = $result->getTableIdDetails ( $subjectId );
			echo "<td colspan=\"" . count ( $resultSetupIds ) . "\"><font class=\"printTableRow\">" . $details ['subject_name'] . "</font></td>";
		}		
		?>	
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
	$candidateIds = $candidate->getCandidate4Section ( $sectionId, 1 );
	$i = 1;
	foreach ( $candidateIds as $candidateId ) {
		$totalScore[$i] = 0;
		echo "<tr>
				<td><font class=\"printCandidateInfo\">$i</font></td>
				<td><font class=\"printCandidateInfo\">" . $registration->getCandidateRegistrationNumber ( $candidateId ) . "</font></td>
				<td><font class=\"printCandidateInfo\">" . $candidate->getOfficerName ( $candidateId ) . "</font></td>";
		
		foreach ( $subjectIds as $subjectId ) {
			foreach ( $resultSetupIds as $resultSetupId ) {
				$score = $marking->getTotalMarkResultEntry4CandidateSubject ( $resultSetupId, $subjectId, $candidateId );
				$totalScore[$i] += $score[0];
				echo "<td align=\"center\"><font class=\"printMark\">" . $score [0] . "</font></td>";
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
		<table border="1" style="border-style: solid;" cellpadding="0" cellspacing="0">
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
	$candidateIds = $candidate->getCandidate4Section ( $sectionId, 1 );
	$i = 1;
	foreach ( $candidateIds as $candidateId ) {
		echo "<tr><td><font class=\"printTableRow\">$i</font></td>";
		foreach ( $assessmentIds as $assessmentId ) {
			$activityIds = $result->getActivityIds ( $assessmentId, 1 );
			foreach ( $activityIds as $activityId ) {
				$score = $marking->getMark4Candidate ( $activityId, $candidateId );
				
				echo "<td align=\"center\"><font class=\"printMark\">" . $grading->getGradingOptionName ( $score [0] ) . "</font></td>";
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
		
		<table border="1" style="border-style: solid;">
			<tr>
				<th style="width: 50px"></th>
				<th rowspan="2"><font class="printTableRow">Attendance.</th>
				<th rowspan="2"><font class="printTableRow">Remarks</font></th>
				<th rowspan="2"><font class="printTableRow">Total Marks</font></th>
				<th rowspan="2"><font class="printTableRow">Percentage</font></th>
				<th rowspan="2"><font class="printTableRow">Ranks</font></th>
			</tr>
			<tr>
			<td></td>
		
	</tr>
	<?php
	$candidateIds = $candidate->getCandidate4Section ( $sectionId, 1 );
	$newTotalScore = $totalScore;	
	rsort($totalScore);
	$i = 1;
	foreach ( $candidateIds as $candidateId ) {
		echo "<tr><td>$i</td>
		";
		$remarks = $resultSections->getCandidateRemarks ( $resultSectionId, $candidateId );
		$attendance = $resultSections->getCandidateAttendance ( $resultSectionId, $candidateId );
		echo "<td align=\"center\"><font class=\"printMark\">" . $attendance [1] . "/" . $totalAttendance . "</font></td>";
		echo "<td><font class=\"printMark\">" . $options->getOptionIdValue ( $remarks [1] ) . "</font></td>";
		echo "<td align=\"center\"><font class=\"printMark\">" . $newTotalScore[$i] . "</font></td>";
		echo "<td align=\"center\"><font class=\"printMark\">" . number_format($newTotalScore[$i]/$totalMark*100, 2) . " %</font></td>";
		echo "<td align=\"center\"><font class=\"printMark\">" . $marking->getRankOnArray($newTotalScore[$i], $totalScore) . "</font></td>";
		echo "</tr>";
		++$i;
	
	}
	
	?>
</table>

</div>
</body>
</html>

