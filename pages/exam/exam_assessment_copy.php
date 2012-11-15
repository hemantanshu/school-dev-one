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
require_once BASE_PATH . 'include/exam/class.result.php';

$body = new body ();
$result = new Result();
$body->startBody ( 'exam', 'LMENUL107', 'Result Section Assessment Setup' );

$resultId = $_GET['resultId'];
$sessionId = $_GET['sessionId'];
$sectionId = $_GET['sectionId'];

$resultDetails = $body->getTableIdDetails($resultId);
$resultIds = $result->getResultDefinitions($sessionId, 1);

?>
<input type="hidden" name="resultId" id="resultId" value="<?php echo $resultId;  ?>" />
<input type="hidden" name="sessionId" id="sessionId" value="<?php echo $sessionId;  ?>" />
<input type="hidden" name="sectionId" id="sectionId" value="<?php echo $sectionId;  ?>" />


<div id="content_header">
    
    <div id="contentHeader">Copy Assessment Record From Another Result Setup </div>
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

<div class="inputs">
    <form id="insertForm1" class="insertForm1" onsubmit="return valid.validateForm(this) ? processCopyForm() : false;">
        <fieldset class="formelements">
            <div class="legend">
                <span>Select Result To Copy Assessment Details</span>
            </div>            
            <dl class="element">
                <dt style="width: 15%">
                    <label for="resultNameId">Result Name :</label>
                </dt>
                <dd style="width: 30%">
                    <select name="resultNameId" id="resultNameId" class="required"  title="Select The Result Name"  tabindex="1" onchange="javascript: valid.validateInput(this);">
                    <?php 
                    	foreach ($resultIds as $resultIdValue){
                    		if($resultIdValue == $resultId)
                    			continue;
                    		$details = $result->getTableIdDetails($resultIdValue);
                    		echo "<option value=\"$resultIdValue\">".$details['result_name']."</option>";
                    	}
                    ?>
                    </select>
                    <div id="resultNameIdError" class="validationError"	style="display: none"></div>
                </dd>
            </dl>            
        </fieldset>
        <fieldset class="action buttons">
            <button type="reset" name="insertReset" id="insertReset" class="negative reset">Reset Form</button>
            <button type="submit" name="submit" id="submit" class="positive insert" >Copy Result Assessment</button>
        </fieldset>
    </form>
</div>
<div class="clear"></div>
<div class="inputs">
    <form id="insertForm" class="insertForm" onsubmit="return valid.validateForm(this) ? processInsertForm() : false;" style="display: none">
        <fieldset class="formelements">
            <div class="legend">
                <span>Additional Details To Copy Assessment Records</span>
            </div>
            <dl class="element">
                <dt style="width: 15%">
                    <label for="assessmentNames">Assessment Names :</label>
                </dt>
                <dd style="width: 80%"><span id="assessmentNames"></span></dd>                
            </dl>     
            <dl class="element">
                <dt style="width: 15%">
                    <label for="startDate">Start Date :</label>
                </dt>
                <dd style="width: 30%">
                    <input type="text" name="startDate" id="startDate" class="required date"  title="Start Date Of Submission" tabindex="8" value="" size="20" onchange="javascript: valid.validateInput(this);" />
                    <div id="startDateError" class="validationError"	style="display: none"></div>
                </dd>                
            </dl>       
            <dl class="element">
                <dt style="width: 15%">
                    <label for="markSubmissionDate_i">Grade Submission Date :</label>
                </dt>
                <dd style="width: 30%">
                    <input type="text" name="markSubmissionDate_i" id="markSubmissionDate_i" class="required date"  title="Last Date Of Mark Submission" tabindex="8" value="" size="20" onchange="javascript: valid.validateInput(this);" />
                    <div id="markSubmissionDate_iError" class="validationError"	style="display: none"></div>
                </dd>
                <dt style="width: 15%">
                    <label for="markSubmissionOfficer_i">Class Teacher :</label>
                </dt>
                <dd style="width: 30%">
                    <input type="hidden" name="markSubmissionOfficer_ival" id="markSubmissionOfficer_ival" />
                    <input type="text" name="markSubmissionOfficer_i" id="markSubmissionOfficer_i" class="required autocomplete"  title="Assign Mark Submission Task To Some Officer" tabindex="9" value="" size="40" onchange="javascript: valid.validateInput(this);" />
                    <div id="markSubmissionOfficer_iError" class="validationError"	style="display: none"></div>
                </dd>
            </dl>
            <dl class="element">
                <dt style="width: 15%">
                    <label for="markVerificationDate_i">Grade Verification Date :</label>
                </dt>
                <dd style="width: 30%">
                    <input type="text" name="markVerificationDate_i" id="markVerificationDate_i" class="required date"  title="Last Date Of Mark Verification" tabindex="10" value="" size="20" onchange="javascript: valid.validateInput(this);" />
                    <div id="markVerificationDate_iError" class="validationError"	style="display: none"></div>
                </dd>
                <dt style="width: 15%">
                    <label for="markVerificationOfficer_i">Class Coordinator :</label>
                </dt>
                <dd style="width: 30%">
                    <input type="hidden" name="markVerificationOfficer_ival" id="markVerificationOfficer_ival" />
                    <input type="text" name="markVerificationOfficer_i" id="markVerificationOfficer_i" class="required autocomplete"  title="Assign Mark Verification Task To Some Officer" tabindex="11" value="" size="40" onchange="javascript: valid.validateInput(this);" />
                    <div id="markVerificationOfficer_iError" class="validationError"	style="display: none"></div>
                </dd>
            </dl>
        </fieldset>
        <fieldset class="action buttons">
        	<input type="hidden" name="toCopyResultId" id="toCopyResultId" value="" />
            <button type="reset" name="insertReset" id="insertReset" class="negative reset">Reset Form</button>
            <button type="button" name="submit" class="regular hide" onclick="hideInsertForm()">Hide
                Insert Form</button>
            <button type="submit" name="submit" id="submit" class="positive insert" accesskey="I">Insert New Record</button>
        </fieldset>
    </form>
</div>


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
	<li><a href="#" class="bookmarkedMenuListing" onclick="loadPageIntoDisplay('<?php echo $baseServer."pages/exam/exam_result_section.php?sessionId=".$sessionId."&resultId=".$resultId."&sectionId=".$sectionId; ?>')"><img src="<?php echo $baseServer; ?>images/global/b_usredit.png" alt="" />Set Section Details</a></li>
	<li><a href="#" class="bookmarkedMenuListing" onclick="loadPageIntoDisplay('<?php echo $baseServer."pages/exam/exam_attendance_view.php?sessionId=".$sessionId."&resultId=".$resultId."&sectionId=".$sectionId; ?>')"><img src="<?php echo $baseServer; ?>images/global/b_usredit.png" alt="" />View Attendance Records</a></li>
	<li><a href="#" class="bookmarkedMenuListing" onclick="loadPageIntoDisplay('<?php echo $baseServer."pages/exam/exam_remarks_view.php?sessionId=".$sessionId."&resultId=".$resultId."&sectionId=".$sectionId; ?>')"><img src="<?php echo $baseServer; ?>images/global/b_usredit.png" alt="" />View Remarks Records</a></li>
	<li><a href="#" class="bookmarkedMenuListing" onclick="loadPageIntoDisplay('<?php echo $baseServer."pages/exam/exam_result_process.php?sessionId=".$sessionId."&resultId=".$resultId."&sectionId=".$sectionId; ?>')"><img src="<?php echo $baseServer; ?>images/global/b_usredit.png" alt="" />Process Result Now</a></li>
	<li><a href="#" class="bookmarkedMenuListing" onclick="loadPageIntoDisplay('<?php echo  $datasheetUrl; ?>')"><img src="<?php echo $baseServer; ?>images/global/b_usredit.png" alt="" />Print Datasheet</a></li>
	<li><a href="#" class="bookmarkedMenuListing" onclick="loadPageIntoDisplay('<?php echo  $resultUrl; ?>')"><img src="<?php echo $baseServer; ?>images/global/b_usredit.png" alt="" />Print Candidate Result</a></li>
</div>