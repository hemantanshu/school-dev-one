<?php
/**
 *
 * @author hemantanshu@supportgurukul.com(html)
 * @category Utility
 * @license Support-Gurukul
 * @version 1.0.0
 */
require_once 'config.php';
require_once BASE_PATH . 'include/global/class.body.php';
require_once BASE_PATH . 'include/global/class.menuTask.php';
require_once BASE_PATH . 'include/exam/class.markHandling.php';
require_once BASE_PATH . 'include/utility/class.personalInfo.php';
require_once BASE_PATH . 'include/utility/class.registration.php';
require_once BASE_PATH . 'include/exam/class.grading.php';


$body = new body ();
$marks = new MarkHandling();
$menuTask = new MenuTask();
$personalInfo = new personalInfo();
$registration = new registrationInfo();
$grading = new Grading();

$body->startBody ( 'exam', 'LMENUL77', 'Exam Mark Progress' );
$examinationId = $_GET['examinationId'];
$examinationDetails = $menuTask->getTableIdDetails($examinationId);
if($examinationDetails['marking_type'] == "")
	$status  = true;
else
	$status = false;

if($examinationId == "")
	exit(0);
$candidateIds = $marks->getCandidate4ExaminationDate($examinationId);


?>

<input type="hidden" name="examinationIdGlobal" id="examinationIdGlobal" value="<?php echo $examinationId; ?>" />
<div class="clear"></div>
<div class="display">
    <div id="displaySubjectRecord">
        <fieldset class="displayElements">
            <dl>
                <dt style="width: 15%;">
                    <label for="examinationNameDirect">Examination Name :</label>
                </dt>
                <dd style="width: 30%">
                    <span id="examinationNameDirect"></span>
                </dd>
                <dt style="width: 15%">
                    <label for="sessionNameDirect">Session Name :</label>
                </dt>
                <dd style="width: 30%">
                    <span id="sessionNameDirect"></span>
                </dd>
            </dl>
            <dl>
                <dt style="width: 15%;">
                    <label for="classNameDirect">Class Name :</label>
                </dt>
                <dd style="width: 30%">
                    <span id="classNameDirect"></span>
                </dd>
                <dt style="width: 15%;">
                    <label for="sectionNameDirect">Section Name :</label>
                </dt>
                <dd style="width: 30%">
                    <span id="sectionNameDirect"></span>
                </dd>
                
            </dl>
            <dl>
                <dt style="width: 15%;">
                    <label for="subjectNameDirect">Subject Name :</label>
                </dt>
                <dd style="width: 30%">
                    <span id="subjectNameDirect"></span>
                </dd>
                <dt style="width: 15%">
                    <label for="subjectCombinationDirect">SubType Name :</label>
                </dt>
                <dd style="width: 30%">
                    <span id="subjectCombinationDirect"></span>
                </dd>                
            </dl>    
            <dl>
                <dt style="width: 15%;">
                    <label for="startDateDirect">Exam Date :</label>
                </dt>
                <dd style="width: 30%">
                    <span id="startDateDirect"></span>
                </dd>
                <dt style="width: 15%">
                    <label for="endDateDirect">Verification Date :</label>
                </dt>
                <dd style="width: 30%">
                    <span id="endDateDirect"></span>
                </dd>                
            </dl>        
        </fieldset>
    </div>
</div>

<div id="displayTable" class="display">
    <form id="entryForm" class="entryForm">
        <fieldset>
        	<div class="legend">
            <span id="legendDisplayDetail">Listing Of All Candidates For This Subject</span>
        </div>
        <dl>
        	
        	<table width="100%" align="left" border = "0" class="buttons">
                <tr class="even">
                	<th>SN</th>
                    <th>Reg No</th>
                    <th>Candidate Name</th>
                    <th>Mark</th>
                    <th>Submission Date</th>
                    <th>Subm. Officer</th>
                    <th>Verif. Date</th>
                    <th>Verif. Officer</th>
                    
                </tr>
                <tr>
                	<td colspan="8"><hr /></td>
                </tr>
                <?php
                	$i = 1;
                	foreach ($candidateIds as $candidateId){
                		$candidateDetails = $personalInfo->getUserIdDetails($candidateId);
                		$score = $marks->getMark4Candidate($examinationId, $candidateId);
                		
                		$scoreDetails = $marks->getTableIdDetails($score[1]);                		
                		if($status){
                			$score = $score[0];
                		}
                		else 
                			$score = $grading->getGradingOptionName($score[0]);
                		
                		
                		echo "
                			<tr class=\"odd\">
                				<th>$i</th>
			                    <th>".$registration->getCandidateRegistrationNumber($candidateId)."</th>
			                    <th align=\"left\">".$personalInfo->getUserName()."</th>
			                    <th align=\"left\">".$score."</th>
			                    <th>".$scoreDetails['submission_date']."</th>
			                    <th>".$marks->getOfficerName($scoreDetails['submission_officer_id'])."</th>
			                    <th>".$scoreDetails['verification_date']."</th>
			                    <th>".$marks->getOfficerName($scoreDetails['verification_officer_id'])."</th>
			                </tr>";
                		++$i;
                	}
                	?>
                
        	</table>
        </dl>
        </fieldset>
    </form>
</div>
<div class="clear"></div>
<br />
<div id="extraMenuListingPage" style="display:none">	
	<?php 
		$baseServer = $registration->getBaseServer();
	?>
	<li><a href="#" class="bookmarkedMenuListing" onclick="loadPageIntoDisplay('<?php echo $baseServer."pages/exam/exam_mark_update.php?examDateId=".$examinationId; ?>')"><img src="<?php echo $baseServer; ?>images/global/b_usredit.png" alt="" />Update Candidate Marks</a></li>
</div>
