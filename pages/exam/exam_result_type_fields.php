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
$body->startBody ( 'exam', 'LMENUL149', 'Exam Result Type Fields Definition' );

$resultTypeId = $_GET['resultTypeId'];
$details = $body->getTableIdDetails($resultTypeId);
?>
<input type="hidden" name="resultTypeIdGlobal" id="resultTypeIdGlobal" value="<?php echo $resultTypeId; ?>" />
<input type="hidden" name="resultTypeNameGlobal" id="resultTypeNameGlobal" value="<?php echo $details['result_type']; ?>" />

<div id="content_header">
    <div id="pageButton" class="buttons">
        <button type="button" class="regular toggle" onclick="showHideSearchForm()">Toggle Search Form</button>
        <button type="button" class="regular toggle" onclick="showHideDatatable()">Toggle Tabulated Data</button>
    </div>
    <div id="contentHeader">Exam Result Type Field Definitions</div>
</div>
<div class="clear"></div>
<div class="display">
    <div id="displaySubjectRecord">
        <fieldset class="displayElements">
			<dl>
                <dt style="width: 15%;">
                    <label for="resultTypeName">Result Type :</label>
                </dt>
                <dd style="width: 30%">
                    <span id="resultTypeName"><?php echo $details['result_type']; ?></span>
                </dd>
                <dt style="width: 15%">
                    <label for="resultTypeDescription">Description :</label>
                </dt>
                <dd style="width: 30%">
                    <span id="resultTypeDescription"><?php echo $details['result_description']; ?></span>
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
                <span>New Field Entry Form</span>
            </div>
            <dl class="element">
                <dt style="width: 15%">
                    <label for="displayName">Display Name :</label>
                </dt>
                <dd style="width: 30%">
                    <input type="text" name="displayName" id="displayName" class="required"  title="The name to be displayed"  tabindex="1" value="" size="40" onchange="javascript: valid.validateInput(this);" />
                    <div id="displayNameError" class="validationError"	style="display: none"></div>
                </dd>
                <dt style="width: 15%">
                    <label for="internalCode">Internal Code :</label>
                </dt>
                <dd style="width: 30%">
                    <input type="text" name="internalCode" id="internalCode" class=""  title="The internal code"  tabindex="2" value="" size="40" onchange="javascript: valid.validateInput(this);" />
                    <div id="internalCodeError" class="validationError"	style="display: none"></div>
                </dd>                
            </dl>
            <dl class="element">
            	<dt style="width: 15%">
                    <label for="url">Submission URL :</label>
                </dt>
                <dd style="width: 80%">
                    <input type="text" name="url" id="url" class=""  title="The assignment url"  tabindex="3" value="" size="45" onchange="javascript: valid.validateInput(this);" />
                    <div id="urlError" class="validationError"	style="display: none"></div>
                </dd>
            </dl>
            <dl class="element">
            	<dt style="width: 15%">
                    <label for="vurl">View URL :</label>
                </dt>
                <dd style="width: 80%">
                    <input type="text" name="vurl" id="vurl" class=""  title="The View url"  tabindex="4" value="" size="45" onchange="javascript: valid.validateInput(this);" />
                    <div id="vurlError" class="validationError"	style="display: none"></div>
                </dd>
            </dl>            
        </fieldset>
        <fieldset class="action buttons" id="insertButtons">
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
                <span>Field Record Update</span>
            </div>
            <dl class="element">
                <dt style="width: 15%">
                    <label for="displayName_u">Display Name :</label>
                </dt>
                <dd style="width: 30%">
                    <input type="text" name="displayName_u" id="displayName_u" class="required"  title="The name to be displayed"  tabindex="11" value="" size="40" onchange="javascript: valid.validateInput(this);" />
                    <div id="displayName_uError" class="validationError"	style="display: none"></div>
                </dd>
                <dt style="width: 15%">
                    <label for="internalCode_u">Internal Code :</label>
                </dt>
                <dd style="width: 30%">
                    <input type="text" name="internalCode_u" id="internalCode_u" class=""  title="The internal code"  tabindex="12" value="" size="40" onchange="javascript: valid.validateInput(this);" />
                    <div id="internalCode_uError" class="validationError"	style="display: none"></div>
                </dd>                
            </dl>
            <dl class="element">
            	<dt style="width: 15%">
                    <label for="url_u">URL :</label>
                </dt>
                <dd style="width: 80%">
                    <input type="text" name="url_u" id="url_u" class=""  title="The assignment url"  tabindex="13" value="" size="45" onchange="javascript: valid.validateInput(this);" />
                    <div id="url_uError" class="validationError"	style="display: none"></div>
                </dd>
            </dl>
            <dl class="element">
            	<dt style="width: 15%">
                    <label for="vurl_u">View URL :</label>
                </dt>
                <dd style="width: 80%">
                    <input type="text" name="vurl_u" id="vurl_u" class=""  title="The View url"  tabindex="14" value="" size="45" onchange="javascript: valid.validateInput(this);" />
                    <div id="vurl_uError" class="validationError"	style="display: none"></div>
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
                <span>Result Field Display Details</span>
            </div>
            <dl>
                <dt style="width: 15%;">
                    <label for="resultType_d">Result Type :</label>
                </dt>
                <dd style="width: 30%">
                    <span id="resultType_d"></span>
                </dd>
                <dt style="width: 15%;">
                    <label for="displayName_d">Display Name :</label>
                </dt>
                <dd style="width: 30%">
                    <span id="displayName_d"></span>
                </dd>
            </dl>
            <dl>
                <dt style="width: 15%;">
                    <label for="url_d">Submission URL :</label>
                </dt>
                <dd style="width: 30%">
                    <span id="url_d"></span>
                </dd>
                <dt style="width: 15%;">
                    <label for="internalCode_d">Internal Code :</label>
                </dt>
                <dd style="width: 30%">
                    <span id="internalCode_d"></span>
                </dd>
            </dl>
            <dl>
                <dt style="width: 15%;">
                    <label for="vurl_d">View URL :</label>
                </dt>
                <dd style="width: 30%">
                    <span id="vurl_d"></span>
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
                    <label for="hint">Result Field :</label>
                </dt>
                <dd style="width: 30%">
                    <input type="text" name="hint" id="hint" class=""
                           style="width: 200px" title="Enter The Field Name" />
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
            <span>Tabulated Listing Of All</span>
        </div>
        <table  class="display"
               id="groupRecords">
            <thead>
            <tr>
            	<th>Field</th>
            	<th>URL</th>
            	<th>Code</th>
                <th style="width: 160px">View Details</th>
                <th style="width: 150px">Edit Details</th>
            </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </fieldset>
</div>