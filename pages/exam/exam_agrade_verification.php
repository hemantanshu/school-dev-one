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
require_once BASE_PATH . 'include/exam/class.resultMarking.php';
require_once BASE_PATH . 'include/exam/class.markHandling.php';
require_once BASE_PATH . 'include/exam/class.grading.php';
require_once BASE_PATH . 'include/utility/class.personalInfo.php';
require_once BASE_PATH . 'include/utility/class.registration.php';


$body = new body ();
$marks = new MarkHandling();
$result = new ResultMarking();

$menuTask = new MenuTask();
$personalInfo = new personalInfo();
$registration = new registrationInfo();
$grading = new grading();

$body->startBody ( 'exam', 'LMENUL84', 'Exam Grade Verification');
$referenceId = $_GET['referenceId'];
$message = $marks->checkValidityOfMarkVerification($referenceId);

if($message)
	$body->palert($message, "./");

$details = $menuTask->getMenuTaskAttributes($referenceId);
$activityId = $details['attribute1'];

$details = $menuTask->getTableIdDetails($activityId);
$gradingOptions = $grading->getGradingOptions($details['marking_type']);
if(count($gradingOptions) == 0)
	exit(0);

$candidateIds = $result->getCandidate4GradeSubmission($activityId);
$markSubmittedCandidateIds = $result->getGradeSubmittedCandidateIds($activityId, false);
$markConfirmedCandidateIds = $result->getGradeSubmittedCandidateIds($activityId, true);
$markVerified = $result->getGradeVerifiedCandidateIds($activityId);

?>

<div id="content_header">
    <div id="pageButton" class="buttons">
    	<button type="button" id="finalConfirmationButton" class="negative activate" onclick="checkFinalConfirmation('<?php echo $referenceId; ?>')">Final Confirmation</button>        
        <button type="button" class="regular toggle" onclick="showHideConfirmForm()">Toggle Confirm Form</button>
        <button type="button" class="regular toggle" onclick="showHideFinalForm()">Toggle Confirm Form</button>        
    </div>
    <div id="contentHeader">Examination Grade Verification Form </div>
</div>
<input type="hidden" name="activityId" id="activityId" value="<?php echo $activityId; ?>" />
<div class="clear"></div>
<div class="display">
    <div id="displaySubjectRecord">
        <fieldset class="displayElements">
            <dl>
                <dt style="width: 15%;">
                    <label for="resultName">Result Name :</label>
                </dt>
                <dd style="width: 30%">
                    <span id="resultName"></span>
                </dd>
                <dt style="width: 15%">
                    <label for="sessionName">Session Name :</label>
                </dt>
                <dd style="width: 30%">
                    <span id="sessionName"></span>
                </dd>
            </dl>

            <dl>
                <dt style="width: 15%;">
                    <label for="assessmentName">Assessment Name :</label>
                </dt>
                <dd style="width: 30%">
                    <span id="assessmentName"></span>
                </dd>
                <dt style="width: 15%;">
                    <label for="activityName">Activity Name :</label>
                </dt>
                <dd style="width: 30%">
                    <span id="activityName"></span>
                </dd>

            </dl>
            <dl>
                <dt style="width: 15%;">
                    <label for="className">Class Name :</label>
                </dt>
                <dd style="width: 30%">
                    <span id="className"></span>
                </dd>
                <dt style="width: 15%;">
                    <label for="sectionName">Section Name :</label>
                </dt>
                <dd style="width: 30%">
                    <span id="sectionName"></span>
                </dd>

            </dl>
            <dl>
                <dt style="width: 15%;">
                    <label for="subjectName">Subject Name :</label>
                </dt>
                <dd style="width: 30%">
                    <span id="subjectName"></span>
                </dd>
                <dt style="width: 15%;">
                    <label for="endDate">End Date :</label>
                </dt>
                <dd style="width: 30%">
                    <span id="endDate"></span>
                </dd>
            </dl>
        </fieldset>
    </div>
</div>
<div id="displayTable" class="display">
    <form id="entryForm" class="entryForm">
        <fieldset>
        	<div class="legend">
            <span id="legendDisplayDetail">Listing Of All Candidates</span>
        </div>
        <dl>
        	
        	<table width="100%" align="left" border = "0" class="buttons">
                <tr class="even">
                	<th>SN</th>
                    <th>Reg No</th>
                    <th>Candidate Name</th>
                    <th>Gender</th>
                    <th>Mark</th>
                    <th>Mark Status</th>
                    <th style="width: 190px">Submit & Confirm</th>
                </tr>
                <tr>
                	<td colspan="7"><hr /></td>
                </tr>
                <?php 
                	$i = 1;
                	$j = 1;
                	foreach ($candidateIds as $candidateId){
                		++$i;
                		if(in_array($candidateId, $markVerified))
                			continue;
                		$candidateDetails = $personalInfo->getUserIdDetails($candidateId);
                		$tableId = "table".$candidateId;
                		$markId = "mark".$candidateId;
                		$nextFocusElement = "mark".$candidateIds[$i];                		
                		$gender = $candidateDetails['gender'] == 'M' ? 'Male' : 'Female';
                		$score = $result->getMark4Candidate($activityId, $candidateId);
                		
                		echo "
                			<tr class=\"odd\" id=\"$tableId\">
                				<th>$j</th>
			                    <th>".$registration->getCandidateRegistrationNumber($candidateId)."</th>
			                    <th>".$personalInfo->getUserName()."</th>			                    
			                    <th>".$gender."</th>
			                    <th>
			                    	<select name=\"$markId\" id=\"$markId\" class=\"required\" style=\"width: 100px\">";
                		foreach ($gradingOptions as $options){
                			echo "<option value=\"".$options[0]."\">".$options[1]."</option>";
                		}
			                    	
                		echo "
                					</select></th>			                    
			                    <th class=\"red\">Mark Not Submitted</th>
			                    <th><button type=\"button\" class=\"negative insert\" onclick=\"processConfirmAction('".$candidateId."', '".$nextFocusElement."')\">Submit & Confirm</th>			                    
			                </tr>";
                		++$j;
                	}
                	$i = 1;
                	foreach ($markSubmittedCandidateIds as $candidateId){
                		++$i;
                		if(in_array($candidateId, $markVerified))
                			continue;
                		$candidateDetails = $personalInfo->getUserIdDetails($candidateId);                		
                		$tableId = "table".$candidateId;
                		$markId = "mark".$candidateId;
                		$nextFocusElement = "mark".$markSubmittedCandidateIds[$i];
                		$gender = $candidateDetails['gender'] == 'M' ? 'Male' : 'Female';
                		$score = $result->getMark4Candidate($activityId, $candidateId);
                	
                		echo "
                		<tr class=\"odd\" id=\"$tableId\">
                		<th>$j</th>
                		<th>".$registration->getCandidateRegistrationNumber($candidateId)."</th>
                		<th>".$personalInfo->getUserName()."</th>
                		<th>".$gender."</th>
                		<th>
			                    	<select name=\"$markId\" id=\"$markId\" class=\"required\" style=\"width: 100px\">";
                		foreach ($gradingOptions as $options){
                            if($options[0] == $score[0])
                			    echo "<option value=\"".$options[0]."\" selected=\"selected\">".$options[1]."</option>";
                            else
                                echo "<option value=\"".$options[0]."\">".$options[1]."</option>";
                		}
			                    	
                		echo "
                					</select></th>
                		<th class=\"yellow\">Mark Not Confirmed</th>
                		<th><button type=\"button\" class=\"negative insert\" onclick=\"processConfirmAction('".$candidateId."', '".$nextFocusElement."')\">Submit & Confirm</th>                		
                		</tr>";
                		++$j;
                	}
                	$i = 1;
                	foreach ($markConfirmedCandidateIds as $candidateId){
                		++$i;
                		if(in_array($candidateId, $markVerified))
                			continue;
                		$candidateDetails = $personalInfo->getUserIdDetails($candidateId);
                		$tableId = "table".$candidateId;
                		$markId = "mark".$candidateId;
                		$nextFocusElement = "mark".$markConfirmedCandidateIds[$i];
                		$gender = $candidateDetails['gender'] == 'M' ? 'Male' : 'Female';
                		$score = $result->getMark4Candidate($activityId, $candidateId);
                	
                		echo "
                		<tr class=\"odd\" id=\"$tableId\">
                		<th>$j</th>
                		<th>".$registration->getCandidateRegistrationNumber($candidateId)."</th>
                		<th>".$personalInfo->getUserName()."</th>
                		<th>".$gender."</th>
                		<th>
			                    	<select name=\"$markId\" id=\"$markId\" class=\"required\" style=\"width: 100px\">";
                		foreach ($gradingOptions as $options){
                			if($options[0] == $score[0])
                			    echo "<option value=\"".$options[0]."\" selected=\"selected\">".$options[1]."</option>";
                            else
                                echo "<option value=\"".$options[0]."\">".$options[1]."</option>";
                		}
			                    	
                		echo "
                					</select></th>
                		<th class=\"green\">Confirmed Marks</th>
                		<th><button type=\"button\" class=\"negative insert\" onclick=\"processConfirmAction('".$candidateId."', '".$nextFocusElement."')\">Submit & Confirm</th>                		
                		</tr>";
                		++$j;
                	}                	
                ?>
                
        	</table>
        </dl>
        </fieldset>
    </form>
</div>
<div class="clear"></div>
<br />
<div id="displayTable" class="display">
    <form id="finalForm" class="finalForm">
        <fieldset>
        	<div class="legend">
            <span id="legendDisplayDetail">Listing Of All Mark Verified Candidates</span>
        </div>
        <dl>
        	
        	<table width="100%" align="left" border = "0" class="buttons" id="finalTable">
                <tr class="even">
                    <th>Reg No</th>
                    <th>Candidate Name</th>
                    <th>Gender</th>
                    <th>Mark</th>
                    <th>Submission Date</th>
                    <th>Verification Date</th>
                </tr>
                <tr>
                	<td colspan="6"><hr /></td>
                </tr>
                <?php
                	foreach ($markVerified as $candidateId){
                		$candidateDetails = $personalInfo->getUserIdDetails($candidateId);
                		$tableId = "table".$candidateId;
                		$markId = "mark".$candidateId;
                		$gender = $candidateDetails['gender'] == 'M' ? 'Male' : 'Female';
                		$score = $result->getMark4Candidate($activityId, $candidateId);
                		$scoreDetails = $marks->getTableIdDetails($score[1]);
                		echo "
                			<tr class=\"odd\" id=\"$tableId\">
			                    <th>".$registration->getCandidateRegistrationNumber($candidateId)."</th>
			                    <th>".$personalInfo->getUserName()."</th>			                    
			                    <th>".$gender."</th>
			                    <th>".$grading->getGradingOptionName($score[0])."</th>
			                    <th>".$scoreDetails['submission_date']."</th>
			                    <th>".$scoreDetails['verification_date']."</th>
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

