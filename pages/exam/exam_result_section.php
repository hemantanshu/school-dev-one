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

$body = new body ();
$body->startBody ( 'exam', 'LMENUL86', 'Result Assessment Setup' );

$resultId = $_GET['resultId'];
$sessionId = $_GET['sessionId'];
$sectionId = $_GET['sectionId'];

$resultDetails = $body->getTableIdDetails($resultId);
?>
<input type="hidden" name="resultId" id="resultId" value="<?php echo $resultId;  ?>" />
<input type="hidden" name="sessionId" id="sessionId" value="<?php echo $sessionId;  ?>" />
<input type="hidden" name="sectionId" id="sectionId" value="<?php echo $sectionId;  ?>" />

<div id="content_header">    
    <div id="contentHeader">Result Class Section Details Entry Form </div>
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
    <form id="insertForm" class="insertForm" onsubmit="return valid.validateForm(this) ? processInsertForm() : false;"  style="display: none">
        <fieldset class="formelements">
            <div class="legend">
                <span>Section Details Entry Form</span>
            </div>
            <dl class="element">
                <dt style="width: 15%">
                    <label for="totalAttendance">Total Attendance :</label>
                </dt>
                <dd style="width: 30%">
                    <input type="text" name="totalAttendance" id="totalAttendance" class="required"  title="total attendance for the result"  tabindex="1" size="15" onchange="javascript: valid.validateInput(this);" />
                    <div id="totalAttendanceError" class="validationError"	style="display: none"></div>
                </dd>
                <dt style="width: 15%">
                    <label for="totalMarks">Total Marks :</label>
                </dt>
                <dd style="width: 30%">
                    <input type="text" name="totalMarks" id="totalMarks" class="required"  title="Total Marks for the result"  tabindex="2" size="15" onchange="javascript: valid.validateInput(this);" />
                    <div id="totalMarksError" class="validationError"	style="display: none"></div>
                </dd>
            </dl>
            <dl class="element">
                <dt style="width: 15%">
                    <label for="attendanceDate">Attendance Date :</label>
                </dt>
                <dd style="width: 30%">
                    <input type="text" name="attendanceDate" id="attendanceDate" class="required"  title="last date of attendance submission"  tabindex="2" size="20" onchange="javascript: valid.validateInput(this);" />
                    <div id="attendanceDateError" class="validationError"	style="display: none"></div>
                </dd>
                <dt style="width: 15%">
                    <label for="attendanceOfficer">Attendance Officer :</label>
                </dt>
                <dd style="width: 30%">
                	<input type="hidden" name="attendanceOfficer_val" id="attendanceOfficer_val" value="" />
                    <input type="text" name="attendanceOfficer" id="attendanceOfficer" class="required autocomplete"  title="Office to be assigned"  tabindex="3" size="40" onchange="javascript: valid.validateInput(this);" />
                    <div id="attendanceOfficer_valError" class="validationError"	style="display: none"></div>
                </dd>
            </dl>
            <dl class="element">
                <dt style="width: 15%">
                    <label for="remarksDate">Remarks Date :</label>
                </dt>
                <dd style="width: 30%">
                    <input type="text" name="remarksDate" id="remarksDate" class="required"  title="remarks date "  tabindex="4" size="20" onchange="javascript: valid.validateInput(this);" />
                    <div id="remarksDateError" class="validationError"	style="display: none"></div>
                </dd>
                <dt style="width: 15%">
                    <label for="remarksOfficer">Remarks Officer :</label>
                </dt>
                <dd style="width: 30%">
                	<input type="hidden" name="remarksOfficer_val" id="remarksOfficer_val" value="" />
                    <input type="text" name="remarksOfficer" id="remarksOfficer" class="required autocomplete"  title="officer to be assigned"  tabindex="5" size="40" onchange="javascript: valid.validateInput(this);" />
                    <div id="remarksOfficer_valError" class="validationError"	style="display: none"></div>
                </dd>
            </dl>            
        </fieldset>
        <fieldset class="action buttons">
            <button type="reset" name="insertReset" id="insertReset" class="negative reset">Reset Form</button>
            <button type="submit" name="submit" id="submit" class="positive insert" accesskey="I">Insert Record</button>
        </fieldset>
    </form>
</div>
<div class="clear"></div>
<div class="display">
    <div id="displayValue">
        <fieldset class="displayElements">
            <div class="legend">
                <span id="legendDisplayAssignment">Section Setup Details : </span>
            </div>
            <dl>
                <dt style="width: 15%;">
                    <label for="totalAttendanceDisplay">Total Attendance : </label>
                </dt>
                <dd style="width: 30%">
                    <span id="totalAttendanceDisplay"></span>
                </dd>
                <dt style="width: 15%">
                    <label for="totalMarksDisplay">Total Marks :</label>
                </dt>
                <dd style="width: 30%">
                    <span id="totalMarksDisplay"></span>
                </dd>
            </dl>
            <dl>
                <dt style="width: 15%;">
                    <label for="attendanceDateDisplay">Attendance Date :</label>
                </dt>
                <dd style="width: 30%">
                    <span id="attendanceDateDisplay"></span>
                </dd>
                <dt style="width: 15%">
                    <label for="attendanceSubmissionDisplay">Class Teacher :</label>
                </dt>
                <dd style="width: 30%">
                    <span id="attendanceSubmissionDisplay"></span>
                </dd>
            </dl>
            <dl>
                <dt style="width: 15%;">
                    <label for="remarksDateDisplay">Remarks Date :</label>
                </dt>
                <dd style="width: 30%">
                    <span id="remarksDateDisplay"></span>
                </dd>
                <dt style="width: 15%">
                    <label for="remarksSubmissionDisplay">Class Teacher :</label>
                </dt>
                <dd style="width: 30%">
                    <span id="remarksSubmissionDisplay"></span>
                </dd>
            </dl>
            <dl>
                <dt style="width: 15%;">
                    <label for="lastUpdateDateDisplay">Last Update Date : </label>
                </dt>
                <dd style="width: 30%">
                    <span id="lastUpdateDateDisplay"></span>
                </dd>
                <dt style="width: 15%">
                    <label for="lastUpdatedByDisplay">Updated By :</label>
                </dt>
                <dd style="width: 30%">
                    <span id="lastUpdatedByDisplay"></span>
                </dd>
            </dl>
            <dl>
                <dt style="width: 15%;">
                    <label for="creationDateDisplay">Creation Date : </label>
                </dt>
                <dd style="width: 30%">
                    <span id="creationDateDisplay"></span>
                </dd>
                <dt style="width: 15%">
                    <label for="createdByDisplay">Created By :</label>
                </dt>
                <dd style="width: 30%">
                    <span id="createdByDisplay"></span>
                </dd>
            </dl>
        </fieldset>

        <fieldset class="action buttons">
            <button type="button" name="submit" id="submit" class="regular hide"
                    onclick="hideDisplayPortion()">Hide Display Details Portion</button>
            <button type="button" class="positive edit" id="update_value_button"
                    onclick="populateEditForm()">Edit Record</button>
        </fieldset>

    </div>
</div>
<br />
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
	<li><a href="#" class="bookmarkedMenuListing" onclick="loadPageIntoDisplay('<?php echo $baseServer."pages/exam/exam_result_section.php?sessionId=".$sessionId."&resultId=".$resultId."&sectionId=".$sectionId; ?>')"><img src="<?php echo $baseServer; ?>images/global/b_usredit.png" alt="" />Set Section Setup</a></li>
	<li><a href="#" class="bookmarkedMenuListing" onclick="loadPageIntoDisplay('<?php echo $baseServer."pages/exam/exam_result_assessment.php?sessionId=".$sessionId."&resultId=".$resultId."&sectionId=".$sectionId; ?>')"><img src="<?php echo $baseServer; ?>images/global/b_usredit.png" alt="" />Set Result Assessment</a></li>
	<li><a href="#" class="bookmarkedMenuListing" onclick="loadPageIntoDisplay('<?php echo $baseServer."pages/exam/exam_remarks_view.php?sessionId=".$sessionId."&resultId=".$resultId."&sectionId=".$sectionId; ?>')"><img src="<?php echo $baseServer; ?>images/global/b_usredit.png" alt="" />View Remarks Details</a></li>
	<li><a href="#" class="bookmarkedMenuListing" onclick="loadPageIntoDisplay('<?php echo $baseServer."pages/exam/exam_attendance_view.php?sessionId=".$sessionId."&resultId=".$resultId."&sectionId=".$sectionId; ?>')"><img src="<?php echo $baseServer; ?>images/global/b_usredit.png" alt="" />View Attendance Details</a></li>
	<li><a href="#" class="bookmarkedMenuListing" onclick="loadPageIntoDisplay('<?php echo  $datasheetUrl; ?>')"><img src="<?php echo $baseServer; ?>images/global/b_usredit.png" alt="" />Print Section Datasheet</a></li>
	<li><a href="#" class="bookmarkedMenuListing" onclick="loadPageIntoDisplay('<?php echo  $resultUrl; ?>')"><img src="<?php echo $baseServer; ?>images/global/b_usredit.png" alt="" />Print Candidate Result</a></li>
</div>
<?php 
	$body->endBody("exam", "MENUL86");
?>