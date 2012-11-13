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
$body->startBody ( 'exam', 'LMENUL82', 'Assessment Subject Entry' );

$assessmentId = $_GET['accessmentId'];

?>
<input type="hidden" name="assessmentId" id="assessmentId" value="<?php echo $assessmentId;  ?>" />

<div id="content_header">
    <div id="pageButton" class="buttons">
        <button type="button" class="regular toggle" onclick="showHideSearchForm()">Toggle Search Form</button>
        <button type="button" class="regular toggle" onclick="showHideDatatable()">Toggle Tabulated Data</button>
    </div>
    <div id="contentHeader">Assessment Subject Entry Form </div>
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
                    <label for="assessmentName">Assessment Name :</label>
                </dt>
                <dd style="width: 30%">
                    <span id="assessmentName"></span>
                </dd>
                <dt style="width: 15%;">
                    <label for="markingType">Marking Type :</label>
                </dt>
                <dd style="width: 30%">
                    <span id="markingType"></span>
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
                <span>New Assessment Activity Date Entry Form</span>
            </div>
            <dl class="element">
                <dt style="width: 15%">
                    <label for="activityName_i">Activity Name :</label>
                </dt>
                <dd style="width: 30%">
                    <input type="text" name="activityName_i" id="activityName_i" class="required"  title="Enter Activity Name"  tabindex="1" value="" size="30" onchange="javascript: valid.validateInput(this);" />
                    <div id="activityName_iError" class="validationError"	style="display: none"></div>
                </dd>
                <dt style="width: 15%">
                    <label for="activityOrder_i">Activity Order :</label>
                </dt>
                <dd style="width: 30%">
                    <input type="text" name="activityOrder_i" id="activityOrder_i" class="required"  title="Enter The Order Of activity" tabindex="1" value="" size="20" onchange="javascript: valid.validateInput(this);" />
                    <div id="activityOrder_iError" class="validationError"	style="display: none"></div>
                </dd>
            </dl>
            <dl class="element">
                <dt style="width: 15%">
                    <label for="subjectName_i">Subject Name :</label>
                </dt>
                <dd style="width: 30%">
                    <select name="subjectName_i" id="subjectName_i" class="required"  title="Select The Subject Component" tabindex="2" style="width: 200px;">
                    </select>
                    <div id="subjectName_iError" class="validationError"	style="display: none"></div>
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
                    <input type="text" name="markSubmissionOfficer_i" id="markSubmissionOfficer_i" class="required"  title="Assign Mark Submission Task To Some Officer" tabindex="9" value="" size="30" onchange="javascript: valid.validateInput(this);" />
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
                    <input type="text" name="markVerificationOfficer_i" id="markVerificationOfficer_i" class="required"  title="Assign Mark Verification Task To Some Officer" tabindex="11" value="" size="30" onchange="javascript: valid.validateInput(this);" />
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
                <span id="legend_editForm">Acticity Date Record Update Form</span>
            </div>
            <dl class="element">
                <dt style="width: 15%">
                    <label for="activityName_u">Activity Name :</label>
                </dt>
                <dd style="width: 30%">
                    <input type="text" name="activityName_u" id="activityName_u" class="required"  title="Enter Activity Name"  tabindex="1" value="" size="30" onchange="javascript: valid.validateInput(this);" />
                    <div id="activityName_uError" class="validationError"	style="display: none"></div>
                </dd>
                <dt style="width: 15%">
                    <label for="activityOrder_u">Activity Order :</label>
                </dt>
                <dd style="width: 30%">
                    <input type="text" name="activityOrder_u" id="activityOrder_u" class="required"  title="Enter The Order Of activity" tabindex="1" value="" size="20" onchange="javascript: valid.validateInput(this);" />
                    <div id="activityOrder_uError" class="validationError"	style="display: none"></div>
                </dd>
            </dl>
            <dl class="element">
                <dt style="width: 15%">
                    <label for="subjectName_u">Subject Name :</label>
                </dt>
                <dd style="width: 30%">
                    <input name="subjectName_u" id="subjectName_u" class="required"  title="Select The Subject Component" tabindex="2" style="width: 200px;" disabled="disabled">
                    <div id="subjectName_uError" class="validationError"	style="display: none"></div>
                </dd>
            </dl>
            <dl class="element">
                <dt style="width: 15%">
                    <label for="markSubmissionDate_u">Grade Submission Date :</label>
                </dt>
                <dd style="width: 30%">
                    <input type="text" name="markSubmissionDate_u" id="markSubmissionDate_u" class="required date"  title="Last Date Of Mark Submission" tabindex="8" value="2012-07-27" size="20" onchange="javascript: valid.validateInput(this);" />
                    <div id="markSubmissionDate_uError" class="validationError"	style="display: none"></div>
                </dd>
                <dt style="width: 15%">
                    <label for="markSubmissionOfficer_u">Class Teacher :</label>
                </dt>
                <dd style="width: 30%">
                    <input type="hidden" name="markSubmissionOfficer_uval" id="markSubmissionOfficer_uval" />
                    <input type="text" name="markSubmissionOfficer_u" id="markSubmissionOfficer_u" class="required"  title="Assign Mark Submission Task To Some Officer" tabindex="9" value="" size="30" onchange="javascript: valid.validateInput(this);" />
                    <div id="markSubmissionOfficer_uvalError" class="validationError"	style="display: none"></div>
                </dd>
            </dl>
            <dl class="element">
                <dt style="width: 15%">
                    <label for="markVerificationDate_u">Grade Verification Date :</label>
                </dt>
                <dd style="width: 30%">
                    <input type="text" name="markVerificationDate_u" id="markVerificationDate_u" class="required date"  title="Last Date Of Mark Verification" tabindex="10" value="2012-07-30" size="20" onchange="javascript: valid.validateInput(this);" />
                    <div id="markVerificationDate_uError" class="validationError"	style="display: none"></div>
                </dd>
                <dt style="width: 15%">
                    <label for="markVerificationOfficer_u">Class Coordinator :</label>
                </dt>
                <dd style="width: 30%">
                    <input type="hidden" name="markVerificationOfficer_uval" id="markVerificationOfficer_uval" />
                    <input type="text" name="markVerificationOfficer_u" id="markVerificationOfficer_u" class="required"  title="Assign Mark Verification Task To Some Officer" tabindex="11" value="" size="30" onchange="javascript: valid.validateInput(this);" />
                    <div id="markVerificationOfficer_uvalError" class="validationError"	style="display: none"></div>
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
                    <label for="activityName_d">Activity Name : </label>
                </dt>
                <dd style="width: 30%">
                    <span id="activityName_d"></span>
                </dd>
                <dt style="width: 15%">
                    <label for="activityOrder_d">Activity Order :</label>
                </dt>
                <dd style="width: 30%">
                    <span id="activityOrder_d"></span>
                </dd>
            </dl>
            <dl>

                <dt style="width: 15%">
                    <label for="subjectName_d">Subject Name :</label>
                </dt>
                <dd style="width: 30%">
                    <span id="subjectName_d"></span>
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