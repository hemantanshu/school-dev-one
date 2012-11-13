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
require_once BASE_PATH . 'include/utility/class.personalInfo.php';
require_once BASE_PATH . 'include/utility/class.candidate.php';
require_once BASE_PATH . 'include/utility/class.registration.php';
require_once BASE_PATH . 'include/exam/class.resultSections.php';

$body = new body ();
$menuTask = new MenuTask();
$personalInfo = new personalInfo();
$registration = new registrationInfo();
$candidate = new Candidate();
$result = new ResultSections();


$body->startBody ( 'exam', 'LMENUL115', 'Result Candidate Weight Submission' );

$referenceId = $_GET['referenceId'];

$details = $menuTask->getMenuTaskAttributes($referenceId);
$resultSectionId = $details['attribute1'];
$details = $menuTask->getTableIdDetails($resultSectionId);

$candidateIds = $candidate->getCandidate4Section($details['section_id'], 1);
$enteredCandidates = $result->getCandidate4Data($resultSectionId, 'WEIGH');

?>
<input type="hidden" name="resultSectionId" id="resultSectionId" value="<?php echo $details['id'];  ?>" />
<input type="hidden" name="resultId" id="resultId" value="<?php echo $details['result_id'];  ?>" />
<input type="hidden" name="sessionId" id="sessionId" value="<?php echo $details['session_id'];  ?>" />
<input type="hidden" name="sectionId" id="sectionId" value="<?php echo $details['section_id'];  ?>" />

<div id="content_header">
    <div id="pageButton" class="buttons">
    	<button type="button" id="finalConfirmationButton" class="negative activate" onclick="checkFinalConfirmation('<?php echo $referenceId; ?>')">Final Confirmation</button>
    </div>
    <div id="contentHeader">Weight Entry For The Section</div>
</div>
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
        	
        	<table width="100%" align="left" border = "0" class="buttons" id="mainTable">
                <tr class="even">
                    <th>Reg No</th>
                    <th>Candidate Name</th>
                    <th>Gender</th>
                    <th>Weight</th>
                    <th style="width: 190px">Submit</th>
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
                		$nextFocusMarkId = "mark".$candidateIds[$i];
                		$gender = $candidateDetails['gender'] == 'M' ? 'Male' : 'Female';
                		
                		$data = $result->getCandidateData($resultSectionId, $candidateId, 'WEIGH');               		
                		echo "
                			<tr class=\"odd\" id=\"$tableId\">
			                    <th>".$registration->getCandidateRegistrationNumber($candidateId)."</th>
			                    <th>".$personalInfo->getUserName()."</th>			                    
			                    <th>".$gender."</th>
			                    <th><input type=\"text\" id=\"".$markId."\" tabindex=\"$i\" name=\"".$markId."\" size=\"10\" value=\"".$data[1]."\"/></th>
			                    <th><button type=\"button\" class=\"negative insert\" onclick=\"processConfirmAction('".$candidateId."', '".$nextFocusMarkId ."')\">Submit & Confirm</th>
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