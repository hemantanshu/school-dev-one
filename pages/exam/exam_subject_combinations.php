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
$body->startBody ( 'exam', 'LMENUL69', 'Subject Component Entry Form' );
?>
<div id="content_header">
    <div id="pageButton" class="buttons">
        <button type="button" class="negative toggle" onclick="changeSubjectName()">Change Subject Name </button>
        <button type="button" class="regular toggle" onclick="showHideSearchForm()">Toggle Search Form</button>
        <button type="button" class="regular toggle" onclick="showHideDatatable()">Toggle Tabulated Data</button>
    </div>
    <div id="contentHeader">Subject Combination Entry Form </div>
</div>
<div class="clear"></div>
<div id="choiceListing">
    <div class="inputs">
        <fieldset class="formelements">
            <div class="legend">
                <span>Change The Subject Of The Form</span>
            </div>
            <dl class="element">
                <dt style="width: 15%"><label for="subject">Subject Name :</label>	</dt>
                <dd style="width: 80%">
                    <input type="hidden" name="subject_val" id="subject_val" />
                    <input type="text" class="required autocomplete" name="subject" size="30" id="subject" onblur="checkSubjectNameChange()" />
            </dl>
        </fieldset>
    </div>
</div>
<div class="clear"></div>
<div id="completePageDisplay" style="display: none">
	<div class="display">
    <div id="displaySubjectRecord">
        <fieldset class="displayElements">
            <dl>
                <dt style="width: 15%;">
                    <label for="subjectCode">Subject Code :</label>
                </dt>
                <dd style="width: 30%">
                    <span id="subjectCode"></span>
                </dd>
                <dt style="width: 15%">
                    <label for="subjectName">Subject Name :</label>
                </dt>
                <dd style="width: 30%">
                    <span id="subjectName"></span>
                </dd>
            </dl>
        </fieldset>
    </div>
</div>

<div class="inputs">
    <form id="insertForm" class="insertForm" onsubmit="return valid.validateForm(this) ? processInsertForm() : false;">
        <fieldset class="formelements">
            <div class="legend">
                <span>New Subject Component Entry</span>
            </div>
            <dl class="element">
                <dt style="width: 15%"><label for="subjectComponentName">Component Name :</label>	</dt>
                <dd style="width: 30%">
                    <input type="text" size="25" name="subjectComponentName" id="subjectComponentName" class="required" tabindex="1" onchange="javascript: valid.validateInput(this);" title="Enter The Subject Component" />
                    <div id="subjectComponentNameError" class="validationError" style="display: none"></div></dd>
                <dt style="width: 15%"><label for="subjectComponentOrder">Order :</label>	</dt>
                <dd style="width: 30%">
                    <input type="text" size="25" name="subjectComponentOrder" id="subjectComponentOrder" class="required" tabindex="2" onchange="javascript: valid.validateInput(this);" title="Enter The Subject Component order" />
                    <div id="subjectComponentOrderError" class="validationError" style="display: none"></div></dd>    
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
    <form id="updateForm" class="updateForm" onsubmit="return valid.validateForm(this) ? processUpdateForm() : false;" style="display: none">
        <fieldset class="formelements">
            <div class="legend">
                <span id="legend_editForm">Update Subject Component Record</span>
            </div>
            <dl class="element">
                <dt style="width: 15%"><label for="subjectComponentName">Component Name :</label>	</dt>
                <dd style="width: 30%">
                    <input type="text" size="25" name="subjectComponentName_u" id="subjectComponentName_u" class="required" tabindex="11" onchange="javascript: valid.validateInput(this);" title="Enter The Subject Component" />
                    <div id="subjectComponentName_uError" class="validationError" style="display: none"></div></dd>
                <dt style="width: 15%"><label for="subjectComponentOrder_u">Order :</label>	</dt>
                <dd style="width: 30%">
                    <input type="text" size="25" name="subjectComponentOrder_u" id="subjectComponentOrder_u" class="required" tabindex="12" onchange="javascript: valid.validateInput(this);" title="Enter The Subject Component order" />
                    <div id="subjectComponentOrder_uError" class="validationError" style="display: none"></div></dd>    
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
            <button type="submit" class="positive update" accesskey="U">Update Record</button>
        </fieldset>
    </form>
</div>

<div class="clear"></div>
<div class="display">
    <div id="displayRecord" style="display: none">
        <fieldset class="displayElements">
            <div class="legend">
                <span>Showing Examination Record Details </span>
            </div>
            <dl>
                <dt style="width: 15%;">
                    <label for="subjectComponentId_d">Component Id :</label>
                </dt>
                <dd style="width: 30%">
                    <span id="subjectComponentId_d"></span>
                </dd>
                <dt style="width: 15%">
                    <label for="subjectComponentName_d">Component Name :</label>
                </dt>
                <dd style="width: 30%">
                    <span id="subjectComponentName_d"></span>
                </dd>
            </dl>
            <dl>
                <dt style="width: 15%;">
                    <label for="subjectComponentOrder_d">Order :</label>
                </dt>
                <dd style="width: 30%">
                    <span id="subjectComponentOrder_d"></span>
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


<div class="datatable buttons" id="displayDatatable" style="display: none">
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
                <th>Order</th>
                <th style="width: 160px">Set Date</th>
                <th style="width: 160px">View Details</th>
                <th style="width: 150px">Edit Details</th>
            </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </fieldset>
</div>
</div>