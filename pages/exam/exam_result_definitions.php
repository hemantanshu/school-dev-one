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
$body->startBody ( 'exam', 'LMENUL80', 'Result Type Entry Form' );
?>
<div id="content_header">
    <div id="pageButton" class="buttons">
        <button type="button" class="negative toggle" onclick="changeActiveSession()">Change Class Session </button>
        <button type="button" class="regular toggle" onclick="showHideSearchForm()">Toggle Search Form</button>
        <button type="button" class="regular toggle" onclick="showHideDatatable()">Toggle Tabulated Data</button>
    </div>
    <div id="contentHeader">Result Type Definition Entry Form </div>
</div>
<div class="clear"></div>
<div id="choiceListing" style="display: none">
    <div class="inputs">
        <fieldset class="formelements">
            <div class="legend">
                <span>Change Active Session For The Form</span>
            </div>
            <dl class="element">
                <dt style="width: 15%"><label for="session">Session Name :</label>	</dt>
                <dd style="width: 80%">
                    <input type="hidden" name="session_val" id="session_val" />
                    <input type="text" class="required" name="session" size="30" id="session" onchange="checkSessionChange()" />
            </dl>
        </fieldset>
    </div>
</div>
<div class="clear"></div>
<div class="inputs">
    <form id="insertForm" class="insertForm" onsubmit="return valid.validateForm(this) ? processInsertForm() : false;">
        <fieldset class="formelements">
            <div class="legend">
                <span>New Result Entry Setup</span>
            </div>
            <dl class="element">
                <dt style="width: 15%"><label for="resultName">Result Name :</label>	</dt>
                <dd style="width: 80%">
                    <input type="text" size="40" name="resultName" id="resultName" class="required" tabindex="1" onchange="javascript: valid.validateInput(this);" title="Entry The name of the resultination" />
                    <div id="resultNameError" class="validationError" style="display: none"></div></dd>
            </dl>
            <dl class="element">
                <dt style="width: 15%"><label for="displayName">Display Name :</label>	</dt>
                <dd style="width: 30%">
                    <input type="text" size="30" name="displayName" id="displayName" class="required" tabindex="2" onchange="javascript: valid.validateInput(this);" title="Entry The name of the resultination" />
                    <div id="displayNameError" class="validationError" style="display: none"></div></dd>
                <dt style="width: 15%">
                    <label for="markingType_i">Scoring Type :</label>
                </dt>
                <dd style="width: 30%">
                    <select name="markingType_i" id="markingType_i" class=""  title="Select The Marking Type" tabindex="3" onchange="javascript: valid.validateInput(this);" ></select>
                    <div id="markingType_iError" class="validationError"	style="display: none"></div>
                </dd>
                
            </dl>
            <dl class="element">
                <dl class="element">
                <dt style="width: 15%"><label for="resultDescription">Description :</label>	</dt>
                <dd style="width: 80%">
                    <textarea name="resultDescription" id="resultDescription" class="required" tabindex="4" cols="50" rows="3" onchange="javascript: valid.validateInput(this);" title="Description of the resultination"></textarea>
                    <div id="resultDescriptionError" class="validationError" style="display: none"></div></dd>
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
                <span id="legend_editForm">Update Result Type Record Details</span>
            </div>
            <dl class="element">
                <dt style="width: 15%"><label for="resultName_u">Result Name :</label>	</dt>
                <dd style="width: 80%">
                    <input type="text" size="40" name="resultName_u" id="resultName_u" class="required" tabindex="11" onchange="javascript: valid.validateInput(this);" title="Entry The name of the resultination" />
                    <div id="resultName_uError" class="validationError" style="display: none"></div></dd>
            </dl>
            <dl class="element">
                <dt style="width: 15%"><label for="displayName_u">Display Name :</label>	</dt>
                <dd style="width: 30%">
                    <input type="text" size="30" name="displayName_u" id="displayName_u" class="required" tabindex="12" onchange="javascript: valid.validateInput(this);" title="Entry The name of the resultination" />
                    <div id="displayName_uError" class="validationError" style="display: none"></div></dd>
                <dt style="width: 15%">
                    <label for="markingType_u">Scoring Type :</label>
                </dt>
                <dd style="width: 30%">
                    <select name="markingType_u" id="markingType_u" class="required"  title="Select The Marking Type. Leave Blank For Absolute Marking" tabindex="106" onchange="javascript: valid.validateInput(this);" ></select>
                    <div id="markingType_uError" class="validationError"	style="display: none"></div>
                </dd>
            </dl>
            <dl class="element">
                <dl class="element">
                <dt style="width: 15%"><label for="resultDescription_u">Description :</label>	</dt>
                <dd style="width: 80%">
                    <textarea name="resultDescription_u" id="resultDescription_u" class="required" tabindex="13" cols="50" rows="3" onchange="javascript: valid.validateInput(this);" title="Description of the resultination"></textarea>
                    <div id="resultDescription_uError" class="validationError" style="display: none"></div></dd>
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
                <span id="legendDisplayDetail">Showing Result Record Details </span>
            </div>
            <dl>
                <dt style="width: 15%;">
                    <label for="resultName_d">Result Name :</label>
                </dt>
                <dd style="width: 30%">
                    <span id="resultName_d"></span>
                </dd>
                <dt style="width: 15%">
                    <label for="sessionNameDisplay">Session :</label>
                </dt>
                <dd style="width: 30%">
                    <span id="sessionNameDisplay"></span>
                </dd>
            </dl>
            <dl>
                <dt style="width: 15%;">
                    <label for="displayName_d">Display Name :</label>
                </dt>
                <dd style="width: 30%">
                    <span id="displayName_d"></span>
                </dd>
                <dt style="width: 15%;">
                    <label for="scoringType_d">Scoring Type :</label>
                </dt>
                <dd style="width: 30%">
                    <span id="scoringType_d"></span>
                </dd>
                                
            </dl>            
            <dl>
                <dt style="width: 15%;">
                    <label for="resultDescription_d">Description :</label>
                </dt>
                <dd style="width: 80%">
                    <span id="resultDescription_d"></span>
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
                    <label for="Section_hint"></label>
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
            <span>Tabulated Listing Of Grading Types</span>
        </div>
        <table  class="display"
               id="groupRecords">
            <thead>
            <tr>
                <th>Result Name</th>
                <th style="width: 180px">Result Setup</th>
                <th style="width: 150px">Grades Setup</th>
                <th style="width: 150px">Class Setup</th>
                <th style="width: 160px">View Progress</th>
                <th style="width: 160px">View Details</th>
            </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </fieldset>
</div>
