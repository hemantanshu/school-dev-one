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
require_once BASE_PATH . 'include/utility/class.personalInfo.php';
require_once BASE_PATH . 'include/exam/class.grading.php';
require_once BASE_PATH . 'include/utility/class.registration.php';


$body = new body ();
$marks = new ResultMarking();
$markHandling = new MarkHandling();
$menuTask = new MenuTask();
$personalInfo = new personalInfo();
$registration = new registrationInfo();
$grading = new Grading();

$body->startBody ( 'exam', 'LMENUL83', 'Exam Grade Submission' );
$referenceId = $_GET['referenceId'];
$message = $markHandling->checkValidityOfMarkSubmission($referenceId);

if($message)
	$body->palert($message, "./");

$details = $menuTask->getMenuTaskAttributes($referenceId);
$resultId = $details['attribute1'];

$details = $menuTask->getTableIdDetails($resultId);
$gradingOptions = $grading->getGradingOptions($details['marking_type']);

if(count($gradingOptions) == 0)
	exit(0);


$candidateIds = $marks->getCandidate4GradeSubmission($resultId);
$markSubmitted = $marks->getGradeSubmittedCandidateIds($resultId, false);
$markConfirmed = $marks->getGradeSubmittedCandidateIds($resultId, true);
?>

<div id="content_header">
    <div id="pageButton" class="buttons">
    	<button type="button" id="finalConfirmationButton" class="negative activate" onclick="checkFinalConfirmation('<?php echo $referenceId; ?>')">Final Confirmation</button>
        <button type="button" class="regular toggle" onclick="showHideEntryForm()">Toggle Entry Form</button>
        <button type="button" class="regular toggle" onclick="showHideConfirmForm()">Toggle Confirm Form</button>
        <button type="button" class="regular toggle" onclick="showHideFinalForm()">Toggle Confirm Form</button>        
    </div>
    <div id="contentHeader">Examination Grade Submission Form </div>
</div>
<input type="hidden" name="activityId" id="activityId" value="<?php echo $resultId; ?>" />
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
                    <th style="width: 150px">Submit</th>
                    <th style="width: 190px">Submit & Confirm</th>
                </tr>
                <tr>
                	<td colspan="7"><hr /></td>
                </tr>
                <?php 
                	$i = 1;
                	foreach ($candidateIds as $candidateId){
                		$candidateDetails = $personalInfo->getUserIdDetails($candidateId);
                		$tableId = "table".$candidateId;
                		$markId = "mark".$candidateId;
                		$nextFocusElement = "mark".$candidateIds[$i];
                		$gender = $candidateDetails['gender'] == 'M' ? 'Male' : 'Female';
                		
                		echo "
                			<tr class=\"odd\" id=\"$tableId\">
                				<th>$i</th>
			                    <th>".$registration->getCandidateRegistrationNumber($candidateId)."</th>
			                    <td>".$personalInfo->getCandidateName($candidateId)."</td>			                    
			                    <th>".$gender."</th>
			                    <th>
			                    	<select name=\"$markId\" id=\"$markId\" class=\"required\" style=\"width: 100px\">";
                		foreach ($gradingOptions as $options){
                			echo "<option value=\"".$options[0]."\">".$options[1]."</option>";
                		}
			                    	
                		echo "
                					</select></th>
			                    <th align=\"center\"><button type=\"button\" class=\"positive browse\" onclick=\"processSubmitAction('".$candidateId."', '".$nextFocusElement."')\">Submit Mark</th>
			                    <th><button type=\"button\" class=\"negative insert\" onclick=\"processConfirmAction('".$candidateId."', '".$nextFocusElement."')\">Submit & Confirm</th>
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
<div id="displayTable" class="display">
    <form id="confirmForm" class="confirmForm">
        <fieldset>
        	<div class="legend">
            <span id="legendDisplayDetail">Listing Of All Mark Submitted Candidates</span>
        </div>
        <dl>
        	
        	<table width="100%" align="left" border = "0" class="buttons" id="confirmTable">
                <tr class="even">
                    <th>Reg No</th>
                    <th>Candidate Name</th>
                    <th>Gender</th>
                    <th>Mark</th>
                    <th style="width: 190px">Submit & Confirm</th>
                </tr>
                <tr>
                	<td colspan="5"><hr /></td>
                </tr>
                <?php
                	$i = 1;
                	foreach ($markSubmitted as $candidateId){
                		$candidateDetails = $personalInfo->getUserIdDetails($candidateId);
                		$tableId = "table".$candidateId;
                		$markId = "mark".$candidateId;
                		$nextFocusElement = "mark".$markSubmitted[$i];
                		$gender = $candidateDetails['gender'] == 'M' ? 'Male' : 'Female';
                		$score = $marks->getMark4Candidate($resultId, $candidateId);
                		
                		echo "
                			<tr class=\"odd\" id=\"$tableId\">
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
			                    <th><button type=\"button\" class=\"negative\" onclick=\"processConfirmAction('".$candidateId."', '".$nextFocusElement."')\">Submit & Confirm</th>
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
<div id="displayTable" class="display">
    <form id="finalForm" class="finalForm">
        <fieldset>
        	<div class="legend">
            <span id="legendDisplayDetail">Listing Of All Mark Confirmed Candidates</span>
        </div>
        <dl>
        	
        	<table width="100%" align="left" border = "0" class="buttons" id="finalTable">
                <tr class="even">
                    <th>Reg No</th>
                    <th>Candidate Name</th>
                    <th>Gender</th>
                    <th>Mark</th>
                </tr>
                <tr>
                	<td colspan="4"><hr /></td>
                </tr>
                <?php
                	foreach ($markConfirmed as $candidateId){
                		$candidateDetails = $personalInfo->getUserIdDetails($candidateId);
                		$tableId = "table".$candidateId;
                		$markId = "mark".$candidateId;
                		$gender = $candidateDetails['gender'] == 'M' ? 'Male' : 'Female';
                		$score = $marks->getMark4Candidate($resultId, $candidateId);
                		
                		echo "
                			<tr class=\"odd\" id=\"$tableId\">
			                    <th>".$registration->getCandidateRegistrationNumber($candidateId)."</th>
			                    <th>".$personalInfo->getUserName()."</th>			                    
			                    <th>".$gender."</th>
			                    <th align=\"left\">".$grading->getGradingOptionName($score[0])."</th>
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
