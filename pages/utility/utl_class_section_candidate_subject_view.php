<?php

require_once 'config.php';
require_once BASE_PATH . 'include/global/class.body.php';
require_once BASE_PATH . 'include/utility/class.subject.php';
require_once BASE_PATH . 'include/utility/class.sections.php';
require_once BASE_PATH . 'include/utility/class.personalInfo.php';
require_once BASE_PATH . 'include/utility/class.registration.php';
require_once BASE_PATH . 'include/utility/class.candidate.php';

$body = new body ();

$body->startBody ( 'utility', 'LMENUL103', 'Candidate Optional Subject Choice View' );

$subject = new subjects ();
$section = new sections();
$personalInfo = new personalInfo ();
$registration = new registrationInfo ();
$candidate = new Candidate();

$sectionId = $_GET ['sectionId'];
$details = $body->getTableIdDetails($sectionId);
$sessionDetails = $body->getTableIdDetails($details['session_id']);


$subjectTypeIds = $subject->getClassSubjectTypeIds ( $details['class_id'], 'o', 1 );
$optionalSubjectCount = sizeof ( $subjectTypeIds );

$i = 0;
foreach ( $subjectTypeIds as $subjectTypeId ) {
	$details = $subject->getTableIdDetails ( $subjectTypeId );
	$subjectTypeDetails [$i] = $details ['subject_name'];
	$subjectOptionIds = $subject->getClassSubjectMappingIds ( $subjectTypeId, 1 );
	++ $i;
}
?>
<div id="content_header">
    <div id="contentHeader">Candidate Optional Subject Choice View</div>
</div>

<div class="clear"></div>
<div class="display">
    <div id="sessionRecord" style="display: none">
        <fieldset class="displayElements">
            <dl>
                <dt style="width: 15%">
                    <label for="session_d">Session :</label>
                </dt>
                <dd style="width: 30%">
                    <span id="session_d"><?php echo $sessionDetails['session_name']; ?></span>
                </dd>
                <dt style="width: 15%">
                    <label for="class_d">Class :</label>
                </dt>
                <dd style="width: 30%">
                    <span id="class_d"><?php echo $section->getClassName4Section($sectionId); ?></span>
                </dd>
            </dl>
            <dl>
                <dt style="width: 15%">
                    <label for="section_d">Section Name :</label>
                </dt>
                <dd style="width: 30%">
                    <span id="section_d"><?php echo $details['section_name'];?></span>
                </dd>
            </dl>
        </fieldset>
    </div>
</div>
<div class="clear"></div>
<div class=\"display\">
	<fieldset>
		<div class="legend">
			<span id="legendDisplayDetail">Displaying Candidate Optional Subject
				Choice</span>
		</div>
		<table class="buttons" cellpadding="0" cellspacing="0" border="0"
			width="100%">
			<tr class="even">
				<th>SN</th>
				<th>Registration Number</th>
				<th>Candidate Name</th>
		<?php
		foreach ( $subjectTypeDetails as $subjectTypeName ) {
			echo "<th>" . $subjectTypeName . "</th>";
		}
		?></tr>
		<?php
		
		$j = 0;
		$candidateIds = $candidate->getCandidate4Section($sectionId, 1);
		foreach ( $candidateIds as $candidateId ) {
			++ $j;
			$rowClass = $j % 2 == 0 ? "odd" : "even";
			$personalInfo->getUserIdDetails ( $candidateId );
			echo "
			<tr class=\"$rowClass\">
				<th>" . $j . "</th>
				<th>" . $registration->getCandidateRegistrationNumber ( $candidateId ) . "</th>
				<th>" . $personalInfo->getUserName () . "</th>";
			
			for($i = 0; $i < $optionalSubjectCount; ++ $i) {
				$candidateSubjectId = $candidate->getCandidateSubject ( $candidateId, $subjectTypeIds [$i] );
				$subjectDetails = $registration->getTableIdDetails ( $candidateSubjectId [1] );
				
				echo "<th>" . $subjectDetails ['subject_code'] . " " . $subjectDetails ['subject_name'] . "</th>";
			}
			echo "</tr>
				<tr>
							<td height=\"5px\"></td>
						</tr>";
		}
		echo "</table><br />";
		?>	
		
<br />
<div class="clear"></div>
<div id="extraMenuListingPage" style="display:none">
	<?php 
		$baseServer = $body->getBaseServer();				
	?>
	<li><a href="#" class="bookmarkedMenuListing" onclick="loadPageIntoDisplay('<?php echo $baseServer."pages/utility/utl_class_section_candidate_subject.php?sessionId=".$sessionDetails['id']."&sectionId=".$sectionId; ?>')"><img src="<?php echo $baseServer; ?>images/global/b_usredit.png" alt="" />Bulk Subject Assignment</a></li>
</div>
		
		
		
		