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
$body->startBody ( 'accounts', 'LMENUL111', 'Accounts Formulae Computation' );

$allowanceId = $_GET['allowanceId'];
?>
<input type="hidden" name="allowanceId" id="allowanceId" value="<?php echo $allowanceId; ?>" />
<div id="content_header">
    <div id="pageButton" class="buttons">
        <button type="button" class="regular toggle" onclick="showHideSearchForm()">Toggle Search Form</button>
        <button type="button" class="regular toggle" onclick="showHideDatatable()">Toggle Tabulated Data</button>
    </div>
    <div id="contentHeader">Allowance Formulae Computation</div>
</div>
<div class="clear"></div>
<div class="display">
    <div id="displaySubjectRecord">
        <fieldset class="displayElements">
			<dl>
                <dt style="width: 15%;">
                    <label for="allowanceName">Allowance Name :</label>
                </dt>
                <dd style="width: 30%">
                    <span id="allowanceName"></span>
                </dd>
                <dt style="width: 15%">
                    <label for="accountHead">Account Head :</label>
                </dt>
                <dd style="width: 30%">
                    <span id="accountHead"></span>
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
                <span>Allowance Combination Formulae Insert Form</span>
            </div>
            <dl class="element">
                <dt style="width: 15%">
                    <label for="value_i">Magnitude :</label>
                </dt>
                <dd style="width: 30%">
                    <input type="text" name="value_i" id="value_i" class="required numeric"  title="Numeric magnitude of the allowance"  tabindex="1" size="40" onchange="javascript: valid.validateInput(this);" />
                    <div id="value_iError" class="validationError"	style="display: none"></div>
                </dd>
                <dt style="width: 15%">
                    <label for="dependent_i">Dependent :</label>
                </dt>
                <dd style="width: 30%">
                    <select name="dependent_i" id="dependent_i" class="required"  title="Select The Dependent Value, Leave if absolute amount"  tabindex="2" onchange="javascript: valid.validateInput(this);">
                    </select>
                    <div id="dependent_iError" class="validationError"	style="display: none"></div>
                </dd>
            </dl>
            <dl class="element">
                <dt style="width: 15%">
                    <label for="type">Type :</label>
                </dt>
                <dd style="width: 30%">
                    <select name="type_i" id="type_i" class="required"  title="The Type of amount"  tabindex="3" onchange="javascript: valid.validateInput(this);" style="width: 80px">
                    	<option value="c">Credit</option>
                    	<option value="d">Debit</option>
                    </select>
                    <div id="type_iError" class="validationError"	style="display: none"></div>
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
                <span id="legend_editForm">Allowance Combination Record Update Form</span>
            </div>
            <dl class="element">
                <dt style="width: 15%">
                    <label for="value_u">Magnitude :</label>
                </dt>
                <dd style="width: 30%">
                    <input type="text" name="value_u" id="value_u" class="required numeric"  title="Numeric magnitude of the allowance"  tabindex="1" size="40" onchange="javascript: valid.validateInput(this);" />
                    <div id="value_uError" class="validationError"	style="display: none"></div>
                </dd>
                <dt style="width: 15%">
                    <label for="dependent_u">Dependent :</label>
                </dt>
                <dd style="width: 30%">
                    <select name="dependent_u" id="dependent_u" class="required"  title="Select The Dependent Value, Leave if absolute amount"  tabindex="2" onchange="javascript: valid.validateInput(this);">                    	
                    </select>
                    <div id="dependent_uError" class="validationError"	style="display: none"></div>
                </dd>
            </dl>
            <dl class="element">
                <dt style="width: 15%">
                    <label for="type">Type :</label>
                </dt>
                <dd style="width: 30%">
                    <select name="type_u" id="type_u" class="required"  title="The Type of amount"  tabindex="3" onchange="javascript: valid.validateInput(this);" style="width: 80px">
                    	<option value="c">Credit</option>
                    	<option value="d">Debit</option>
                    </select>
                    <div id="type_uError" class="validationError"	style="display: none"></div>
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
                    <label for="value_d">Magnitude :</label>
                </dt>
                <dd style="width: 30%">
                    <span id="value_d"></span>
                </dd>
                <dt style="width: 15%;">
                    <label for="dependent_d">Dependent :</label>
                </dt>
                <dd style="width: 30%">
                    <span id="dependent_d"></span>
                </dd>
            </dl>
            <dl>
                <dt style="width: 15%;">
                    <label for="type_d">Type :</label>
                </dt>
                <dd style="width: 30%">
                    <span id="type_d"></span>
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
            <span>Tabulated Listing Of All Formulae</span>
        </div>
        <table  class="display"
               id="groupRecords">
            <thead>
            <tr>
            	<th>Magnitude</th>
            	<th>Dependent</th>
            	<th>Type</th>
                <th style="width: 160px">View Details</th>
                <th style="width: 150px">Edit Details</th>
            </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </fieldset>
</div>