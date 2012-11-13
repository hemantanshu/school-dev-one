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
require_once BASE_PATH . 'include/global/class.options.php';
require_once BASE_PATH . 'include/utility/class.registration.php';

$body = new body ();
$marks = new MarkHandling();
$menuTask = new MenuTask();
$personalInfo = new personalInfo();
$registration = new registrationInfo();

$body->startBody ( 'exam', 'LMENUL75', 'Exam Mark Submission' );
$referenceId = $_GET['referenceId'];
$message = $marks->checkValidityOfMarkSubmission($referenceId);
echo $message;
if($message)
	$body->palert($message, "./");

$details = $menuTask->getMenuTaskAttributes($referenceId);
$candidateIds = $marks->getCandidate4MarkSubmission($details['attribute1']);
$markSubmitted = $marks->getMarkSubmittedCandidateIds($details['attribute1'], false);
$markCofirmed = $marks->getMarkSubmittedCandidateIds($details['attribute1'], true);
?>

<div id="content_header">
    <div id="pageButton" class="buttons">
    	<button type="button" id="finalConfirmationButton" class="negative activate" onclick="checkFinalConfirmation('<?php echo $referenceId; ?>')">Final Confirmation</button>
        <button type="button" class="regular toggle" onclick="showHideEntryForm()">Toggle Entry Form</button>
        <button type="button" class="regular toggle" onclick="showHideConfirmForm()">Toggle Confirm Form</button>
        <button type="button" class="regular toggle" onclick="showHideFinalForm()">Toggle Confirm Form</button>        
    </div>
    <div id="contentHeader">Examination Mark Submission Form </div>
</div>
<input type="hidden" name="examinationId" id="examinationId" value="<?php echo $details['attribute1']; ?>" />
<div class="clear"></div>
<div class="display">
    <div id="displaySubjectRecord">
        <fieldset class="displayElements">
            <dl>
                <dt style="width: 15%;">
                    <label for="examinationName">Examination Name :</label>
                </dt>
                <dd style="width: 30%">
                    <span id="examinationName"></span>
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
                <dt style="width: 15%">
                    <label for="subjectCombination">SubType Name :</label>
                </dt>
                <dd style="width: 30%">
                    <span id="subjectCombination"></span>
                </dd>
            </dl>  
            <dl>
                <dt style="width: 15%;">
                    <label for="startDate">Start Date :</label>
                </dt>
                <dd style="width: 30%">
                    <span id="startDate"></span>
                </dd>
                <dt style="width: 15%">
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
			                    <th>".$personalInfo->getUserName()."</th>			                    
			                    <th>".$gender."</th>
			                    <th><input type=\"text\" name=\"$markId\" id=\"$markId\" size=\"15\" class=\"numeric required\" onchange=\"javascript: valid.validateInput(this);\" /></th>
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
                		$score = $marks->getMark4Candidate($details['attribute1'], $candidateId);
                		
                		echo "
                			<tr class=\"odd\" id=\"$tableId\">
			                    <th>".$registration->getCandidateRegistrationNumber($candidateId)."</th>
			                    <th>".$personalInfo->getUserName()."</th>			                    
			                    <th>".$gender."</th>
			                    <th><input type=\"text\" name=\"$markId\" id=\"$markId\" size=\"15\" value=\"$score[0]\" class=\"numeric required\" onchange=\"javascript: valid.validateInput(this);\" /></th>
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
                	foreach ($markCofirmed as $candidateId){
                		$candidateDetails = $personalInfo->getUserIdDetails($candidateId);
                		$tableId = "table".$candidateId;
                		$markId = "mark".$candidateId;
                		$gender = $candidateDetails['gender'] == 'M' ? 'Male' : 'Female';
                		$score = $marks->getMark4Candidate($details['attribute1'], $candidateId);
                		
                		echo "
                			<tr class=\"odd\" id=\"$tableId\">
			                    <th>".$registration->getCandidateRegistrationNumber($candidateId)."</th>
			                    <th>".$personalInfo->getUserName()."</th>			                    
			                    <th>".$gender."</th>
			                    <th>".$score[0]."</th>
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


<?php 
	$body->endMainBody();
?>
