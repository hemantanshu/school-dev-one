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
$body->startBody ( 'exam', 'LMENUL81', 'Result Section Assessment Setup' );

$resultId = $_GET['resultId'];
$sessionId = $_GET['sessionId'];
$sectionId = $_GET['sectionId'];

$resultDetails = $body->getTableIdDetails($resultId);
?>
<input type="hidden" name="resultId" id="resultId" value="<?php echo $resultId;  ?>" />
<input type="hidden" name="sessionId" id="sessionId" value="<?php echo $sessionId;  ?>" />
<input type="hidden" name="sectionId" id="sectionId" value="<?php echo $sectionId;  ?>" />

<div id="content_header">
    <div id="pageButton" class="buttons">
        <button type="button" class="negative toggle" onclick="copyAssessmentRecord()" id="copyAssessment" style="display: none">Copy Assessment Record</button>
        <button type="button" class="regular toggle" onclick="showHideSearchForm()">Toggle Search Form</button>
        <button type="button" class="regular toggle" onclick="showHideDatatable()">Toggle Tabulated Data</button>
    </div>
    <div id="contentHeader">Result Class Record Entry Form </div>
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
    <form id="insertForm" class="insertForm" onsubmit="return valid.validateForm(this) ? processInsertForm() : false;">
        <fieldset class="formelements">
            <div class="legend">
                <span>New Assessment Entry Form</span>
            </div>
            <dl class="element">
                <dt style="width: 15%">
                    <label for="assessmentName">Accessment Name :</label>
                </dt>
                <dd style="width: 30%">
                    <input type="text" name="assessmentName" id="assessmentName" class="required"  title="Put The Accessment Name"  tabindex="1" value="" size="40" onchange="javascript: valid.validateInput(this);" />
                    <div id="assessmentNameError" class="validationError"	style="display: none"></div>
                </dd>
                <dt style="width: 15%">
                    <label for="order">Display Order :</label>
                </dt>
                <dd style="width: 30%">
                    <input type="text" name="order" id="order" class="required numeric"  title="Display Order Of The Accessment" tabindex="2" value="" size="20" onchange="javascript: valid.validateInput(this);" />
                    <div id="orderError" class="validationError"	style="display: none"></div>
                </dd>
            </dl>
            <dl class="element">
                <dt style="width: 15%">
                    <label for="markingType">Scoring Type :</label>
                </dt>
                <dd style="width: 30%">
                    <select name="markingType" id="markingType" class="required"  title="Put The Accessment Name"  tabindex="3" onchange="javascript: valid.validateInput(this);">
                    </select>
                    <div id="markingTypeError" class="validationError"	style="display: none"></div>
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
                <span id="legend_editForm">Result Assessment Update Form</span>
            </div>
            <dl class="element">
                <dt style="width: 15%">
                    <label for="assessmentName_u">Accessment Name :</label>
                </dt>
                <dd style="width: 30%">
                    <input type="text" name="assessmentName_u" id="assessmentName_u" class="required"  title="Put The Accessment Name"  tabindex="11" value="" size="40" onchange="javascript: valid.validateInput(this);" />
                    <div id="accessment_uNameError" class="validationError"	style="display: none"></div>
                </dd>
                <dt style="width: 15%">
                    <label for="order_u">Display Order :</label>
                </dt>
                <dd style="width: 30%">
                    <input type="text" name="order_u" id="order_u" class="required numeric"  title="Display Order Of The Accessment" tabindex="12" value="" size="20" onchange="javascript: valid.validateInput(this);" />
                    <div id="order_uError" class="validationError"	style="display: none"></div>
                </dd>
            </dl>
            <dl class="element">
                <dt style="width: 15%">
                    <label for="markingType_u">Scoring Type :</label>
                </dt>
                <dd style="width: 30%">
                    <select name="markingType_u" id="markingType_u" class="required"  title="Put The Accessment Name"  tabindex="13" onchange="javascript: valid.validateInput(this);">
                    </select>
                    <div id="markingType_uError" class="validationError"	style="display: none"></div>
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
                    <label for="assessmentName_d">Assessment Name : </label>
                </dt>
                <dd style="width: 30%">
                    <span id="assessmentName_d"></span>
                </dd>
                <dt style="width: 15%">
                    <label for="order_d">Display Order :</label>
                </dt>
                <dd style="width: 30%">
                    <span id="order_d"></span>
                </dd>
            </dl>
            <dl>
                <dt style="width: 15%;">
                    <label for="markingType_d">Marking Type : </label>
                </dt>
                <dd style="width: 30%">
                    <span id="markingType_d"></span>
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
            <span>Tabulated Listing Of Assessment</span>
        </div>
        <table  class="display"
               id="groupRecords">
            <thead>
            <tr>
                <th>Assessment Name</th>
                <th>Order</th>
                <th style="width: 190px">Set Activities</th>
                <th style="width: 160px">View Details</th>
                <th style="width: 150px">Edit Details</th>
            </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </fieldset>
</div>

<div class="clear"></div>
