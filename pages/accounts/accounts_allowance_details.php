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
$body->startBody ( 'accounts', 'LMENUL110', 'Allowance Details Record' );
?>
<div id="content_header">
    <div id="pageButton" class="buttons">
        <button type="button" class="regular toggle" onclick="showHideSearchForm()">Toggle Search Form</button>
        <button type="button" class="regular toggle" onclick="showHideDatatable()">Toggle Tabulated Data</button>
    </div>
    <div id="contentHeader">Allowance Details Form </div>
</div>
<div class="clear"></div>
<div class="inputs">
    <form id="insertForm" class="insertForm" onsubmit="return valid.validateForm(this) ? processInsertForm() : false;">
        <fieldset class="formelements">
            <div class="legend">
                <span>Allowance Name Record Entry Form</span>
            </div>
            <dl class="element">
                <dt style="width: 15%">
                    <label for="allowanceName">Allowance Name :</label>
                </dt>
                <dd style="width: 30%">
                    <input type="text" name="allowanceName" id="allowanceName" class="required"  title="Enter The Allowance Name"  tabindex="1" value="" size="30" onchange="javascript: valid.validateInput(this);" />
                    <div id="allowanceNameError" class="validationError"	style="display: none"></div>
                </dd>
                <dt style="width: 15%">
                    <label for="accountHeadName">Account Head :</label>
                </dt>
                <dd style="width: 30%">
                    <select name="accountHeadName" id="accountHeadName" class="required"  title="select accounthead of the allowance"  tabindex="2" onchange="javascript: valid.validateInput(this);">
                    </select>
                    <div id="accountHeadNameError" class="validationError"	style="display: none"></div>
                </dd>
            </dl>
            <dl class="element">
                <dt style="width: 15%">
                    <label for="allowUpdate">Employee Update :</label>
                </dt>
                <dd style="width: 30%">
                    <select name="allowUpdate" id="allowUpdate" class="required"  title="enable update from employee"  tabindex="3" onchange="javascript: valid.validateInput(this);">
                    	<option value="y">Enable</option>
                    	<option value="n">Disable</option>
                    </select>
                    <div id="allowUpdateError" class="validationError"	style="display: none"></div>
                </dd>
                <dt style="width: 15%">
                    <label for="allowRound">Rounding Off :</label>
                </dt>
                <dd style="width: 30%">
                    <select name="allowRound" id="allowRound" class="required"  title="enable rounding off of amount"  tabindex="4" onchange="javascript: valid.validateInput(this);">
                    	<option value="y">Enable</option>
                    	<option value="n">Disable</option>
                    </select>
                    <div id="allowRoundError" class="validationError"	style="display: none"></div>
                </dd>
            </dl>
            <dl class="element">
                <dt style="width: 15%">
                    <label for="allowFraction">Allow Fraction :</label>
                </dt>
                <dd style="width: 30%">
                    <select name="allowFraction" id="allowFraction" class="required"  title="enable fraction of the amount"  tabindex="5" onchange="javascript: valid.validateInput(this);">
                    	<option value="y">Enable</option>
                    	<option value="n">Disable</option>
                    </select>
                    <div id="allowFractionError" class="validationError"	style="display: none"></div>
                </dd>
                <dt style="width: 15%">
                    <label for="contributoryFund">Employee Fund :</label>
                </dt>
                <dd style="width: 30%">
                    <select name="contributoryFund" id="contributoryFund" class="required"  title="if it is a contributory fund"  tabindex="6" onchange="javascript: valid.validateInput(this);">
                    	<option value="y">Yes</option>
                    	<option value="n">No</option>
                    </select>
                    <div id="contributoryFundError" class="validationError"	style="display: none"></div>
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
                <span id="legend_editForm">Allowance Record Update Form</span>
            </div>
            <dl class="element">
                <dt style="width: 15%">
                    <label for="allowanceName_u">Allowance Name :</label>
                </dt>
                <dd style="width: 30%">
                    <input type="text" name="allowanceName_u" id="allowanceName_u" class="required"  title="Enter The Allowance Name"  tabindex="1" value="" size="30" onchange="javascript: valid.validateInput(this);" />
                    <div id="allowanceName_uError" class="validationError"	style="display: none"></div>
                </dd>
                <dt style="width: 15%">
                    <label for="accountHeadName_u">Account Head :</label>
                </dt>
                <dd style="width: 30%">
                    <select name="accountHeadName_u" id="accountHeadName_u" class="required"  title="select accounthead of the allowance"  tabindex="2" onchange="javascript: valid.validateInput(this);">
                    </select>
                    <div id="accountHeadName_uError" class="validationError"	style="display: none"></div>
                </dd>
            </dl>
            <dl class="element">
                <dt style="width: 15%">
                    <label for="allowUpdate_u">Employee Update :</label>
                </dt>
                <dd style="width: 30%">
                    <select name="allowUpdate_u" id="allowUpdate_u" class="required"  title="enable update from employee"  tabindex="3" onchange="javascript: valid.validateInput(this);">
                    	<option value="y">Enable</option>
                    	<option value="n">Disable</option>
                    </select>
                    <div id="allowUpdate_uError" class="validationError"	style="display: none"></div>
                </dd>
                <dt style="width: 15%">
                    <label for="allowRound_u">Rounding Off :</label>
                </dt>
                <dd style="width: 30%">
                    <select name="allowRound_u" id="allowRound_u" class="required"  title="enable rounding off of amount"  tabindex="4" onchange="javascript: valid.validateInput(this);">
                    	<option value="y">Enable</option>
                    	<option value="n">Disable</option>
                    </select>
                    <div id="allowRound_uError" class="validationError"	style="display: none"></div>
                </dd>
            </dl>
            <dl class="element">
                <dt style="width: 15%">
                    <label for="allowFraction_u">Allow Fraction :</label>
                </dt>
                <dd style="width: 30%">
                    <select name="allowFraction_u" id="allowFraction_u" class="required"  title="enable fraction of the amount"  tabindex="5" onchange="javascript: valid.validateInput(this);">
                    	<option value="y">Enable</option>
                    	<option value="n">Disable</option>
                    </select>
                    <div id="allowFraction_uError" class="validationError"	style="display: none"></div>
                </dd>
                <dt style="width: 15%">
                    <label for="contributoryFund_u">Employee Fund :</label>
                </dt>
                <dd style="width: 30%">
                    <select name="contributoryFund_u" id="contributoryFund_u" class="required"  title="if it is a contributory fund"  tabindex="6" onchange="javascript: valid.validateInput(this);">
                    	<option value="y">Yes</option>
                    	<option value="n">No</option>
                    </select>
                    <div id="contributoryFund_uError" class="validationError"	style="display: none"></div>
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
                <span>Allowance Record Details Form </span>
            </div>
            <dl>
                <dt style="width: 15%;">
                    <label for="allowanceName_d">Allowance Name :</label>
                </dt>
                <dd style="width: 30%">
                    <span id="allowanceName_d"></span>
                </dd>
                <dt style="width: 15%;">
                    <label for="accountHeadName_d">Account Head :</label>
                </dt>
                <dd style="width: 30%">
                    <span id="accountHeadName_d"></span>
                </dd>
            </dl>
            <dl>
                <dt style="width: 15%;">
                    <label for="allowUpdate_d">Employee Update :</label>
                </dt>
                <dd style="width: 30%">
                    <span id="allowUpdate_d"></span>
                </dd>
                <dt style="width: 15%;">
                    <label for="allowRound_d">Rounding Off :</label>
                </dt>
                <dd style="width: 30%">
                    <span id="allowRound_d"></span>
                </dd>
            </dl>
            <dl>
                <dt style="width: 15%;">
                    <label for="allowFraction_d">Allow Fraction :</label>
                </dt>
                <dd style="width: 30%">
                    <span id="allowFraction_d"></span>
                </dd>
                <dt style="width: 15%;">
                    <label for="contributoryFund_d">Employee Fund :</label>
                </dt>
                <dd style="width: 30%">
                    <span id="contributoryFund_d"></span>
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
                <label for="hint">Allowance Name :</label>
                </dt>
                <dd style="width: 30%">
                	<input type="text" name="hint" id="hint" size="20" />
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
            <span>Tabulated Listing Of All Allowance Types</span>
        </div>
        <table  class="display" id="groupRecords">
            <thead>
            <tr>
            	<th>Allowance Name</th>
            	<th style="width: 200px">Definition</th>
                <th style="width: 160px">View Details</th>
                <th style="width: 150px">Edit Details</th>
            </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </fieldset>
</div>