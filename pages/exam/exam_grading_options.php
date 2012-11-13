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
$body->startBody ( 'exam', 'LMENUL67', 'Grading Type Options Entry Form' );

$gradingType = $_GET['gradeId'];

$details = $body->getTableIdDetails($gradingType);
if($details['grading_name'] == ''){
    exit(0);
}

?>
<input type="hidden" id="gradeGlobal" name="gradeGlobal" value="<?php echo $gradingType; ?>" />
<input type="hidden" id="gradeNameGlobal" name="gradeNameGlobal" value="<?php echo $details['grading_name']; ?>" />
<div id="content_header">
    <div id="pageButton" class="buttons">
        <button type="button" class="regular toggle" onclick="showHideSearchForm()"><span class="underline">T</span>oggle Search Form</button>
        <button type="button" class="regular toggle" onclick="showHideDatatable()"><span class="underline">T</span>oggle Tabulated Data</button>
    </div>
    <div id="contentHeader">Grading Type Options Entry Form </div>
</div>
<div class="clear"></div>
<div class="display">
    <div id="gradeIdDetails">
        <fieldset class="displayElements">
            <dl>
                <dt style="width: 15%;">
                    <label for="gradeId_o">Grade Type :</label>
                </dt>
                <dd style="width: 30%">
                    <span id="gradeId_o"><?php echo $details['grading_name']; ?></span>
                </dd>
            </dl>
        </fieldset>
    </div>
</div>
<div class="clear"></div>
<div class="inputs">
    <form id="insertForm" class="insertForm" onsubmit="return valid.validateForm(this) ? processInsertForm() : false;">
        <fieldset class="formelements">
            <div class="legend">
                <span>New Grade Option Record Entry Form</span>
            </div>
            <dl class="element">
                <dt style="width: 15%"><label for="gradeOption">Grade Name :</label>	</dt>
                <dd style="width: 30%">
                    <input type="text" name="gradeOption" id="gradeOption" class="required" tabindex="1" size="30" onchange="javascript: valid.validateInput(this);" title="Enter The Grade Option Name" />
                    <div id="gradeOptionError" class="validationError" style="display: none"></div></dd>
                <dt style="width: 15%"><label for="gradeWeight">Weight :</label>	</dt>
                <dd style="width: 30%">
                    <input type="text" name="gradeWeight" id="gradeWeight" class="required numeric" tabindex="2" size="15" onchange="javascript: valid.validateInput(this);" title="Value of the grade option" />
                    <div id="gradeWeightError" class="validationError" style="display: none"></div></dd>
            </dl>

        </fieldset>
        <fieldset class="action buttons">
            <button type="reset" name="insertReset" id="insertReset" class="negative reset">Reset Form</button>
            <button type="button" name="submit" class="regular hide" onclick="hideInsertForm()">Hide
                Insert Form</button>
            <button type="submit" name="submit" id="submit" class="positive insert">Insert New Record</button>
        </fieldset>
    </form>
</div>


<div class="clear"></div>
<div class="inputs">
    <form id="updateForm" class="updateForm" onsubmit="return valid.validateForm(this) ? processUpdateForm() : false;" style="display: none">
        <fieldset class="formelements">
            <div class="legend">
                <span id="legend_editForm">Update Grade Option Record Details</span>
            </div>
            <dl class="element">
                <dt style="width: 15%"><label for="gradeOption_u">Grade Name :</label>	</dt>
                <dd style="width: 30%">
                    <input type="text" name="gradeOption_u" id="gradeOption_u" class="required" tabindex="11" size="30" onchange="javascript: valid.validateInput(this);" title="Enter The Grade Option Name" />
                    <div id="gradeOption_uError" class="validationError" style="display: none"></div></dd>
                <dt style="width: 15%"><label for="gradeWeight_u">Weight :</label>	</dt>
                <dd style="width: 30%">
                    <input type="text" name="gradeWeight_u" id="gradeWeight_u" class="required numeric" tabindex="2" size="15" onchange="javascript: valid.validateInput(this);" title="Value of the grade option" />
                    <div id="gradeWeight_uError" class="validationError" style="display: none"></div></dd>
            </dl>
        </fieldset>
        <fieldset class="action buttons">
            <input type="hidden" name="valueId_u" id="valueId_u" value="" /> <input
            type="hidden" name="rowPosition_u" id="rowPosition_u" value="" />
            <button type="button" class="positive activate" name="activateRecord_u"
                    id="activateRecord_u">Activate Record</button>
            <button type="button" class="negative drop" name="dropRecord_u"
                    id="dropRecord_u">Drop Record</button>
            <button type="button" name="submit" id="submit" class="regular hide"
                    onclick="hideUpdateForm()">Hide Update Portion</button>
            <button type="submit" class="positive update">Update Record</button>
        </fieldset>
    </form>
</div>

<div class="clear"></div>
<div class="display">
    <div id="displayRecord" style="display: none">
        <fieldset class="displayElements">
            <div class="legend">
                <span id="legendDisplayDetail">Showing Details Of The Grade Option </span>
            </div>
            <dl>
                <dt style="width: 15%;">
                    <label for="gradeOption_d">Grade Name :</label>
                </dt>
                <dd style="width: 30%">
                    <span id="gradeOption_d"></span>
                </dd>
                <dt style="width: 15%">
                    <label for="gradeWeight_d">Weight :</label>
                </dt>
                <dd style="width: 30%">
                    <span id="gradeWeight_d"></span>
                </dd>
            </dl>
            <dl>
                <dt style="width: 15%;">
                    <label for="updateDate">Last Update Date : </label>
                </dt>
                <dd style="width: 30%">
                    <span id="lastUpdateDateDisplay"></span>
                </dd>
                <dt style="width: 15%">
                    <label for="updatedBy">Updated By :</label>
                </dt>
                <dd style="width: 30%">
                    <span id="lastUpdatedByDisplay"></span>
                </dd>

            </dl>
            <dl>
                <dt style="width: 15%;">
                    <label for="creationDate">Creation Date : </label>
                </dt>
                <dd style="width: 30%">
                    <span id="creationDateDisplay"></span>
                </dd>
                <dt style="width: 15%">
                    <label for="createdBy">Created By :</label>
                </dt>
                <dd style="width: 30%">
                    <span id="createdByDisplay"></span>
                </dd>

            </dl>
            <dl>
                <dt style="width: 15%;">
                    <label for="active">Active/Inactive : </label>
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
            <button type="button" name="submit" class="regular hide" id="submit"
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
                    <label for="hint">Grade Name :</label>
                </dt>
                <dd style="width: 30%">
                    <input type="text" name="hint" id="hint" class="required"
                           style="width: 200px" title="Enter the grading name" />
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
                    onclick="toggleInsertForm()">Toggle Insert Form</button>
            <button type="submit" name="searchData" id="searchData" class="positive search">Get Search
                Results</button>
        </fieldset>
    </form>
</div>

<div class="clear"></div>


<div class="datatable buttons" id="displayDatatable" style="display: none">
    <fieldset class="formelements">
        <div class="legend">
            <span>Tabulated Listing Of Grading Options</span>
        </div>
        <table  class="display"
               id="groupRecords">
            <thead>
            <tr>
                <th>Grading Type</th>
                <th>Grade Name</th>
                <th style="width: 150px">Grade Weight</th>
                <th style="width: 160px">View Details</th>
                <th style="width: 150px">Edit Details</th>
            </tr>
            </thead>
            <tbody>

            </tbody>
        </table>
    </fieldset>
</div>
