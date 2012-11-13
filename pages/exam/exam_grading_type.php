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
$body->startBody ( 'exam', 'LMENUL66', 'Grading Type Entry Form' );
?>
<div id="content_header">
    <div id="pageButton" class="buttons">
        <button type="button" class="regular toggle" onclick="showHideSearchForm()"><span class="underline">T</span>oggle Search Form</button>
        <button type="button" class="regular toggle" onclick="showHideDatatable()"><span class="underline">T</span>oggle Tabulated Data</button>
    </div>
    <div id="contentHeader">Grading Type Entry Form </div>
</div>
<div class="clear"></div>
<div class="inputs">
    <form id="insertForm" class="insertForm" onsubmit="return valid.validateForm(this) ? processInsertForm() : false;">
        <fieldset class="formelements">
            <div class="legend">
                <span>New Grade Record Entry Form</span>
            </div>
            <dl class="element">
                <dt style="width: 15%"><label for="gradingType">Grading Type :</label>	</dt>
                <dd style="width: 80%">
                    <input type="text" size="30" name="gradingType" id="gradingType" class="required" tabindex="1" onchange="javascript: valid.validateInput(this);" title="Enter The Grading Type" />
                    <div id="gradingTypeError" class="validationError" style="display: none"></div></dd>
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
                <span id="legend_editForm">Update Grading Type Record Details</span>
            </div>
            <dl class="element">
                <dt style="width: 15%"><label for="gradingType_u">Grading Type :</label>	</dt>
                <dd style="width: 80%">
                    <input type="text" size="30" name="gradingType_u" id="gradingType_u" class="required" tabindex="11" onchange="javascript: valid.validateInput(this);" title="Enter The Grading Type" />
                    <div id="gradingType_uError" class="validationError" style="display: none"></div></dd>
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
                <span id="legendDisplayDetail">Showing Details Of The Section </span>
            </div>
            <dl>
                <dt style="width: 15%;">
                    <label for="gradingType_d">Grading Type :</label>
                </dt>
                <dd style="width: 30%">
                    <span id="gradingType_d"></span>
                </dd>
                <dt style="width: 15%">
                    <label for="totalGrades_d">Total Grades :</label>
                </dt>
                <dd style="width: 30%">
                    <span id="totalGrades_d"></span>
                </dd>
            </dl>
            <dl>
                <dt style="width: 15%;">
                    <label for="updateDateDisplay">Last Update Date : </label>
                </dt>
                <dd style="width: 30%">
                    <span id="lastUpdateDateDisplay"></span>
                </dd>
                <dt style="width: 15%">
                    <label for="updatedByDisplay">Updated By :</label>
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
                    <label for="Section_hint">Grading Type :</label>
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
                        <option value="0">In-Active Recordss</option>
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
            <span>Tabulated Listing Of Grading Types</span>
        </div>
        <table  class="display"
               id="groupRecords">
            <thead>
            <tr>
                <th>Grading Type Name</th>
                <th style="width: 150px">Grades Options</th>
                <th style="width: 220px">Grade Options</th>
                <th style="width: 160px">View Details</th>
                <th style="width: 150px">Edit Details</th>
            </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </fieldset>
</div>
