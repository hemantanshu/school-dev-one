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
$body->startBody ( 'exam', 'LMENUL70', 'New Examination Date Entry Form' );

$examinationId = $_GET['examinationId'];
$sessionId = $_GET['sessionId'];
$sectionId = $_GET['sectionId'];

?>
<input type="hidden" name="examinationId" id="examinationId" value="<?php echo $examinationId;  ?>" />
<input type="hidden" name="sectionId" id="sectionId" value="<?php echo $sectionId; ?>" />
<input type="hidden" name="sessionId" id="sessionId" value="<?php echo $sessionId;  ?>" />

<div id="content_header">
    <div id="pageButton" class="buttons">
        <button type="button" class="regular toggle" onclick="showHideSearchForm()">Toggle Search Form</button>
        <button type="button" class="regular toggle" onclick="showHideDatatable()">Toggle Tabulated Data</button>
    </div>
    <div id="contentHeader">Examination Date Record Entry Form </div>
</div>
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
        </fieldset>
    </div>
</div>

<div class="inputs">
    <form id="insertForm" class="insertForm" onsubmit="return valid.validateForm(this) ? processInsertForm() : false;">
        <fieldset class="formelements">
            <div class="legend">
                <span>New Examination Date Entry Form</span>
            </div>
            <dl class="element">
                <dt style="width: 15%">
                    <label for="examinationName_i">Exam Identifier :</label>
                </dt>
                <dd style="width: 30%">
                    <input type="text" name="examinationName_i" id="examinationName_i" class="required"  title="Insert Some Exam Identifier Name"  tabindex="1" value="" size="40" onchange="javascript: valid.validateInput(this);" />
                    <div id="examinationName_iError" class="validationError"	style="display: none"></div>
                </dd>
                <dt style="width: 15%">
                    <label for="examinationDate_i">Exam Date :</label>
                </dt>
                <dd style="width: 30%">
                    <input type="text" name="examinationDate_i" id="examinationDate_i" class="required date"  title="Enter The Date Of Examination" tabindex="1" value="" size="20" onchange="javascript: valid.validateInput(this);" />
                    <div id="examinationDate_iError" class="validationError"	style="display: none"></div>
                </dd>
            </dl>
            <dl class="element">
            	<dt style="width: 15%">
                    <label for="subjectName_i">Subject Name :</label>
                </dt>
                <dd style="width: 30%">
                    <select name="subjectName_i" id="subjectName_i" class="required"  title="Select The Subject Component" tabindex="2" onchange="populateSubjectComponent(true)" style="width: 200px;">
                    </select>
                    <div id="subjectName_iError" class="validationError"	style="display: none"></div>
                </dd>
                <dt style="width: 15%">
                    <label for="subjectComponent_i">Subject Component :</label>
                </dt>
                <dd style="width: 30%">
                    <select name="subjectComponent_i" id="subjectComponent_i" class=""  title="Select The Subject Component" tabindex="2" onchange="javascript: valid.validateInput(this);">
                    </select>
                    <div id="subjectComponent_iError" class="validationError"	style="display: none"></div>
                </dd>                
            </dl>
            <dl class="element">
                <dt style="width: 15%">
                    <label for="examinationTime_i">Exam Start Time :</label>
                </dt>
                <dd style="width: 30%">
                    <input type="text" name="examinationTime_i" id="examinationTime_i" class="required"  title="Enter Examination Time in HH24:MM Format eg: 13:30" tabindex="4" value="074500" size="15" onchange="javascript: valid.validateInput(this);" />
                    <div id="examinationTime_iError" class="validationError"	style="display: none"></div>
                </dd>
                <dt style="width: 15%">
                    <label for="examinationDuration_i">Exam Duration :</label>
                </dt>
                <dd style="width: 30%">
                    <input type="text" name="examinationDuration_i" id="examinationDuration_i" class="required numeric"  title="Enter Examination Duration in mins" tabindex="5" value="60" size="15" onchange="javascript: valid.validateInput(this);" />
                    <div id="examinationDuration_iError" class="validationError"	style="display: none"></div>
                </dd>
            </dl>
            <dl class="element">
                <dt style="width: 15%">
                    <label for="markingType_i">Scoring Type :</label>
                </dt>
                <dd style="width: 30%">
                    <select name="markingType_i" id="markingType_i" class=""  title="Select The Marking Type. Leave Blank For Absolute Marking" tabindex="6" onblur="checkMarkingType()" onchange="javascript: valid.validateInput(this);" ></select>
                    <div id="markingType_iError" class="validationError"	style="display: none"></div>
                </dd>
                <dt style="width: 15%">
                    <label for="credit_i">Credits :</label>
                </dt>
                <dd style="width: 30%">
                    <input type="text" name="credit_i" id="credit_i" class="required numeric"  title="Enter The Credit Of The Exam" tabindex="7" value="1" size="15" onchange="javascript: valid.validateInput(this);" />
                    <div id="credit_iError" class="validationError"	style="display: none"></div>
                </dd>
            </dl>
            <dl class="element" id="markingScoreInsert">
                <dt style="width: 15%">
                    <label for="maxMark_i">Max Mark :</label>
                </dt>
                <dd style="width: 30%">
                    <input type="text" name="maxMark_i" id="maxMark_i" class="numeric"  title="The Max Mark For The Subject Exam" tabindex="7" value="" size="15" onchange="javascript: valid.validateInput(this);" />
                    <div id="maxMark_iError" class="validationError"	style="display: none"></div>
                </dd>
                <dt style="width: 15%">
                    <label for="passMark_i">Pass Mark :</label>
                </dt>
                <dd style="width: 30%">
                    <input type="text" name="passMark_i" id="passMark_i" class="numeric"  title="The passmark for the subject exam" tabindex="7" value="" size="15" onchange="javascript: valid.validateInput(this);" />
                    <div id="passMark_iError" class="validationError"	style="display: none"></div>
                </dd>
            </dl>
            <dl class="element">
                <dt style="width: 15%">
                    <label for="markSubmissionDate_i">Mark Submission Date :</label>
                </dt>
                <dd style="width: 30%">
                    <input type="text" name="markSubmissionDate_i" id="markSubmissionDate_i" class="required date"  title="Last Date Of Mark Submission" tabindex="8" value="" size="20" onchange="javascript: valid.validateInput(this);" />
                    <div id="markSubmissionDate_iError" class="validationError"	style="display: none"></div>
                </dd>
                <dt style="width: 15%">
                    <label for="markSubmissionOfficer_i">Subject Teacher :</label>
                </dt>
                <dd style="width: 30%">
                    <input type="hidden" name="markSubmissionOfficer_ival" id="markSubmissionOfficer_ival" />
                    <input type="text" name="markSubmissionOfficer_i" id="markSubmissionOfficer_i" class="required autocomplete"  title="Assign Mark Submission Task To Some Officer" tabindex="9" value="" size="40" onchange="javascript: valid.validateInput(this);" />
                    <div id="markSubmissionOfficer_iError" class="validationError"	style="display: none"></div>
                </dd>
            </dl>
            <dl class="element">
                <dt style="width: 15%">
                    <label for="markVerificationDate_i">Mark Verification Date :</label>
                </dt>
                <dd style="width: 30%">
                    <input type="text" name="markVerificationDate_i" id="markVerificationDate_i" class="required date"  title="Last Date Of Mark Verification" tabindex="10" value="" size="20" onchange="javascript: valid.validateInput(this);" />
                    <div id="markVerificationDate_iError" class="validationError"	style="display: none"></div>
                </dd>
                <dt style="width: 15%">
                    <label for="markVerificationOfficer_i">Class Teacher :</label>
                </dt>
                <dd style="width: 30%">
                    <input type="hidden" name="markVerificationOfficer_ival" id="markVerificationOfficer_ival" />
                    <input type="text" name="markVerificationOfficer_i" id="markVerificationOfficer_i" class="required autocomplete"  title="Assign Mark Verification Task To Some Officer" tabindex="11" value="" size="40" onchange="javascript: valid.validateInput(this);" />
                    <div id="markVerificationOfficer_iError" class="validationError"	style="display: none"></div>
                </dd>
            </dl>
        </fieldset>
        <fieldset class="action buttons">
            <button type="reset" name="insertReset" id="insertReset" class="negative reset">Reset Form</button>
            <button type="button" name="submit" class="regular hide" onclick="hideInsertForm()">Hide
                Insert Form</button>
            <button type="submit" name="submit" id="submit" class="positive insert" accesskey="I">Insert New Record</button>
        </fieldset>
    </form>
</div>


<div class="clear"></div>
<div class="inputs">
    <form id="updateForm" class="updateForm" onsubmit="return valid.validateForm(this) ? processUpdateForm() : false;" style="display:none">
        <fieldset class="formelements">
            <div class="legend">
                <span id="legend_editForm">Examination Date Record Update Form</span>
            </div>
            <dl class="element">
                <dt style="width: 15%">
                    <label for="examinationName_u">Exam Identifier :</label>
                </dt>
                <dd style="width: 30%">
                    <input type="text" name="examinationName_u" id="examinationName_u" class="required"  title="Insert Some Exam Identifier Name"  tabindex="101" value="" size="40" onchange="javascript: valid.validateInput(this);" />
                    <div id="examinationName_uError" class="validationError"	style="display: none"></div>
                </dd>
                <dt style="width: 15%">
                    <label for="examinationDate_u">Exam Date :</label>
                </dt>
                <dd style="width: 30%">
                    <input type="text" name="examinationDate_u" id="examinationDate_u" class="required date"  title="Enter The Date Of Examination" tabindex="102" value="" size="15" onchange="javascript: valid.validateInput(this);" />
                    <div id="examinationDate_uError" class="validationError"	style="display: none"></div>
                </dd>
            </dl>
            <dl class="element">
                <dt style="width: 15%">
                    <label for="subjectName_u">Subject Name :</label>
                </dt>
                <dd style="width: 30%">
                    <input type="text" name="subjectName_u" id="subjectName_u" class="required"  title="Select The Subject Component" tabindex="102" disabled="disabled" onchange="populateSubjectComponents()">
                    <div id="subjectName_uError" class="validationError"	style="display: none"></div>
                </dd>
                <dt style="width: 15%">
                    <label for="subjectComponent_u">Subject Component :</label>
                </dt>
                <dd style="width: 30%">
                    <input type="text" disabled="disabled" name="subjectComponent_u" id="subjectComponent_u" class="" value="n/a"  title="Select The Subject Component" tabindex="102" onchange="javascript: valid.validateInput(this);">
                    <div id="subjectComponent_uError" class="validationError"	style="display: none"></div>
                </dd>
            </dl>
            <dl class="element">
                <dt style="width: 15%">
                    <label for="examinationTime_u">Exam Start Time :</label>
                </dt>
                <dd style="width: 30%">
                    <input type="text" name="examinationTime_u" id="examinationTime_u" class="required"  title="Enter Examination Time in HH24:MM Format eg: 13:30" tabindex="104" value="" size="15" onchange="javascript: valid.validateInput(this);" />
                    <div id="examinationTime_uError" class="validationError"	style="display: none"></div>
                </dd>
                <dt style="width: 15%">
                    <label for="examinationDuration_u">Exam Duration :</label>
                </dt>
                <dd style="width: 30%">
                    <input type="text" name="examinationDuration_u" id="examinationDuration_u" class="required numeric"  title="Enter Examination Duration in mins" tabindex="105" value="" size="15" onchange="javascript: valid.validateInput(this);" />
                    <div id="examinationDuration_uError" class="validationError"	style="display: none"></div>
                </dd>
            </dl>
            <dl class="element">
                <dt style="width: 15%">
                    <label for="markingType_u">Scoring Type :</label>
                </dt>
                <dd style="width: 30%">
                    <select name="markingType_u" id="markingType_u" class=""  title="Select The Marking Type. Leave Blank For Absolute Marking" tabindex="106" onchange="javascript: valid.validateInput(this);" ></select>
                    <div id="markingType_uError" class="validationError"	style="display: none"></div>
                </dd>
                <dt style="width: 15%">
                    <label for="credit_u">Credits :</label>
                </dt>
                <dd style="width: 30%">
                    <input type="text" name="credit_u" id="credit_u" class="required numeric"  title="Enter The Credit Of The Exam" tabindex="107" value="" size="15" onchange="javascript: valid.validateInput(this);" />
                    <div id="credit_uError" class="validationError"	style="display: none"></div>
                </dd>
            </dl>
            <dl class="element" id="markingScoreUpdate">
                <dt style="width: 15%">
                    <label for="maxMark_u">Max Mark :</label>
                </dt>
                <dd style="width: 30%">
                    <input type="text" name="maxMark_u" id="maxMark_u" class="numeric"  title="The Max Mark For The Subject Exam" tabindex="107" value="" size="15" onchange="javascript: valid.validateInput(this);" />
                    <div id="maxMark_uError" class="validationError"	style="display: none"></div>
                </dd>
                <dt style="width: 15%">
                    <label for="passMark_u">Pass Mark :</label>
                </dt>
                <dd style="width: 30%">
                    <input type="text" name="passMark_u" id="passMark_u" class="numeric"  title="The passmark for the subject exam" tabindex="107" value="" size="15" onchange="javascript: valid.validateInput(this);" />
                    <div id="passMark_uError" class="validationError"	style="display: none"></div>
                </dd>
            </dl>
            <dl class="element">
                <dt style="width: 15%">
                    <label for="markSubmissionDate_u">Mark Submission Date :</label>
                </dt>
                <dd style="width: 30%">
                    <input type="text" name="markSubmissionDate_u" id="markSubmissionDate_u" class="required date"  title="Last Date Of Mark Submission" tabindex="108" value="" size="20" onchange="javascript: valid.validateInput(this);" />
                    <div id="markSubmissionDate_uError" class="validationError"	style="display: none"></div>
                </dd>
                <dt style="width: 15%">
                    <label for="markSubmissionOfficer_u">Subject Teacher :</label>
                </dt>
                <dd style="width: 30%">
                    <input type="hidden" name="markSubmissionOfficer_uval" id="markSubmissionOfficer_uval" />
                    <input type="text" name="markSubmissionOfficer_u" id="markSubmissionOfficer_u" class="required autocomplete"  title="Assign Mark Submission Task To Some Officer" tabindex="109" value="" size="40" onchange="javascript: valid.validateInput(this);" />
                    <div id="markSubmissionOfficer_uError" class="validationError"	style="display: none"></div>
                </dd>
            </dl>
            <dl class="element">
                <dt style="width: 15%">
                    <label for="markVerificationDate_u">Mark Verification Date :</label>
                </dt>
                <dd style="width: 30%">
                    <input type="text" name="markVerificationDate_u" id="markVerificationDate_u" class="required date"  title="Last Date Of Mark Verification" tabindex="110" value="" size="20" onchange="javascript: valid.validateInput(this);" />
                    <div id="markVerificationDate_uError" class="validationError"	style="display: none"></div>
                </dd>
                <dt style="width: 15%">
                    <label for="markVerificationOfficer_u">Class Teacher :</label>
                </dt>
                <dd style="width: 30%">
                    <input type="hidden" name="markVerificationOfficer_uval" id="markVerificationOfficer_uval" />
                    <input type="text" name="markVerificationOfficer_u" id="markVerificationOfficer_u" class="required autocomplete"  title="Assign Mark Verification Task To Some Officer" tabindex="111" value="" size="40" onchange="javascript: valid.validateInput(this);" />
                    <div id="markVerificationOfficer_uError" class="validationError"	style="display: none"></div>
                </dd>
            </dl>
        </fieldset>
        <fieldset class="action buttons">
            <input type="hidden" name="valueId_u" id="valueId_u" value="" /> <input
            type="hidden" name="rowPosition_u" id="rowPosition_u" value="" />
            <button type="button" class="positive activate" name="activateRecord_u"
                    id="activateRecord_u">Activate Record</button>
            <button type="button" class="negative drop" name="dropRecord_u"
                    id="dropRecord_u">Drop Record</button>
            <button type="button" class="regular hide"
                    onclick="hideUpdateForm()">Hide Update Portion</button>
            <button type="submit" class="positive update" accesskey="U">Update Record</button>
        </fieldset>
    </form>
</div>

<div class="clear"></div>
<div class="display">
    <div id="displayRecord" style="display:none">
        <fieldset class="displayElements">
            <div class="legend">
                <span>Examination Record Details Form </span>
            </div>
            <dl>
                <dt style="width: 15%;">
                    <label for="examinationName_d">Examination Name : </label>
                </dt>
                <dd style="width: 30%">
                    <span id="examinationName_d"></span>
                </dd>
                <dt style="width: 15%">
                    <label for="examinationDate_d">Examination Date :</label>
                </dt>
                <dd style="width: 30%">
                    <span id="examinationDate_d"></span>
                </dd>
            </dl>
            <dl>
            	
                <dt style="width: 15%">
                    <label for="subjectName_d">Subject Name :</label>
                </dt>
                <dd style="width: 30%">
                    <span id="subjectName_d"></span>
                </dd>
                <dt style="width: 15%;">
                    <label for="subjectComponent_d">Subject Component :</label>
                </dt>
                <dd style="width: 30%">
                    <span id="subjectComponent_d"></span>
                </dd>
                
            </dl>
            <dl>
                <dt style="width: 15%;">
                    <label for="examinationTime_d">Examination Time :</label>
                </dt>
                <dd style="width: 30%">
                    <span id="examinationTime_d"></span>
                </dd>
                <dt style="width: 15%">
                    <label for="examinationDuration_d">Examination Duration :</label>
                </dt>
                <dd style="width: 30%">
                    <span id="examinationDuration_d"></span>
                </dd>
            </dl>
            <dl>
                <dt style="width: 15%;">
                    <label for="markingType_d">Marking Type :</label>
                </dt>
                <dd style="width: 30%">
                    <span id="markingType_d"></span>
                </dd>
                <dt style="width: 15%">
                    <label for="credits_d">Credits :</label>
                </dt>
                <dd style="width: 30%">
                    <span id="credits_d"></span>
                </dd>
            </dl>
            <dl id="maxMarkDisplay">
                <dt style="width: 15%;">
                    <label for="maxMark_d">Max Mark :</label>
                </dt>
                <dd style="width: 30%">
                    <span id="maxMark_d"></span>
                </dd>
                <dt style="width: 15%">
                    <label for="passMark_d">Pass Mark :</label>
                </dt>
                <dd style="width: 30%">
                    <span id="passMark_d"></span>
                </dd>
            </dl>
            <dl>
                <dt style="width: 15%;">
                    <label for="markSubmissionDate_d">Submission Date :</label>
                </dt>
                <dd style="width: 30%">
                    <span id="markSubmissionDate_d"></span>
                </dd>
                <dt style="width: 15%">
                    <label for="markSubmissionOfficer_d">Submission Officer :</label>
                </dt>
                <dd style="width: 30%">
                    <span id="markSubmissionOfficer_d"></span>
                </dd>
            </dl>
            <dl>
                <dt style="width: 15%;">
                    <label for="markVerificationDate_d">Verification Date :</label>
                </dt>
                <dd style="width: 30%">
                    <span id="markVerificationDate_d"></span>
                </dd>
                <dt style="width: 15%">
                    <label for="markVerificationOfficer_d">Verification Officer :</label>
                </dt>
                <dd style="width: 30%">
                    <span id="markVerificationOfficer_d"></span>
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
            <dl>
                <dt style="width: 15%;">
                    <label for="activeDisplay">Active/Inactive : </label>
                </dt>
                <dd style="width: 30%">
                    <span id="activeDisplay"></span>
                </dd>
            </dl>
        </fieldset>
        <fieldset class="action buttons">
            <input type="hidden" name="valueId_d" id="valueId_d" value="" /> <input
            type="hidden" name="rowPosition_d" id="rowPosition_d" value="" />
            <button type="button" class="positive activate" name="activateRecord_d"
                    id="activateRecord_d">Activate Record</button>
            <button type="button" class="negative drop" name="dropRecord_d"
                    id="dropRecord_d">Drop Record</button>
            <button type="button" name="submit" class="regular hide"
                    onclick="hideDisplayPortion()">Hide Display Details Portion</button>
            <button type="button" class="negative edit" id="editRecordButton"
                    class="editRecordButton">Edit Record</button>
        </fieldset>
    </div>
</div>
<div class="clear"></div>
<div class="inputs">
    <form id="searchForm" class="searchForm" onsubmit="return getSearchResults()">
        <fieldset class="formelements">
            <div class="legend">Search Value</div>
            <dl>
                <dt style="width: 15%">
                </dt>
                <dd style="width: 30%">
                </dd>
                <dt style="width: 15%">
                    <label for="search_type">Search Type :</label>
                </dt>
                <dd>
                    <select name="search_type" id="search_type" style="width: 150px">
                        <option value="all">All Records</option>
                        <option value="1" selected="selected">Active Records</option>
                        <option value="0">In-Active Records</option>
                    </select>
                </dd>
            </dl>
        </fieldset>
        <fieldset class="action buttons">
            <button type="button" name="toggleInsert" id="toggleInsert" class="regular toggle"
                    onclick="toggleInsertForm()" accesskey="T">Toggle Insert Form</button>
            <button type="submit" name="searchData" id="searchData" class="positive search">Get Search
                Results</button>
        </fieldset>
    </form>
</div>

<div class="clear"></div>


<div class="datatable buttons" id="displayDatatable" style="display:none">
    <fieldset class="formelements">
        <div class="legend">
            <span>Tabulated Listing Of Subject Components</span>
        </div>
        <table  class="display"
               id="groupRecords">
            <thead>
            <tr>
                <th>Subject Code</th>
                <th>Subject Name</th>
                <th>Component Name</th>
                <th>Examination Date</th>
                <th style="width: 160px">Check Marks</th>
                <th style="width: 160px">View Details</th>
                <th style="width: 150px">Edit Details</th>
            </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </fieldset>
</div>
<?php
    $body->endBody('exam', 'MENUL70');
?>