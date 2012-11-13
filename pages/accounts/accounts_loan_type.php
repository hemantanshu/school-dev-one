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
$body->startBody ( 'accounts', 'LMENUL126', 'Accounts Loan Type Definition' );
?>
<div id="content_header">
    <div id="pageButton" class="buttons">
        <button type="button" class="regular toggle" onclick="showHideSearchForm()">Toggle Search Form</button>
        <button type="button" class="regular toggle" onclick="showHideDatatable()">Toggle Tabulated Data</button>
    </div>
    <div id="contentHeader">Accounts Loan Type </div>
</div>
<div class="clear"></div>
<div class="inputs">
    <form id="insertForm" class="insertForm" onsubmit="return valid.validateForm(this) ? processInsertForm() : false;">
        <fieldset class="formelements">
            <div class="legend">
                <span>New Loan Type Definition</span>
            </div>
            <dl class="element">
                <dt style="width: 15%">
                    <label for="loanName">Loan Name :</label>
                </dt>
                <dd style="width: 30%">
                    <input type="text" name="loanName" id="loanName" class="required"  title="Enter the new loan type"  tabindex="1" size="30" onchange="javascript: valid.validateInput(this);" />
                    <div id="loanNameError" class="validationError"	style="display: none"></div>
                </dd>
                <dt style="width: 15%">
                    <label for="allowanceId">Allowance Mapping :</label>
                </dt>
                <dd style="width: 30%">
                    <select name="allowanceId" id="allowanceId" class="required"  title="select the allowance to map"  tabindex="2" style="width: 200px" onchange="javascript: valid.validateInput(this);">
                    </select>
                    <div id="allowanceIdError" class="validationError"	style="display: none"></div>
                </dd>
            </dl>
            <dl class="element">
                <dt style="width: 15%">
                    <label for="minAmount">Minimum Amount :</label>
                </dt>
                <dd style="width: 30%">
                    <input type="text" name="minAmount" id="minAmount" class="required"  title="Enter the minimum amount to sanction"  tabindex="3" size="15" onchange="javascript: valid.validateInput(this);" />
                    <div id="minAmountError" class="validationError"	style="display: none"></div>
                </dd>
                <dt style="width: 15%">
                    <label for="Maximum Amount">Maximum Amount :</label>
                </dt>
                <dd style="width: 30%">
                    <input type="text" name="maxAmount" id="maxAmount" class="required"  title="Enter the maximum amount to sanction"  tabindex="4" size="15" onchange="javascript: valid.validateInput(this);" />
                    <div id="maxAmountError" class="validationError"	style="display: none"></div>
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
                <span id="legend_editForm">Loan Type Record Update Form</span>
            </div>
            <dl class="element">
                <dt style="width: 15%">
                    <label for="loanName_u">Loan Name :</label>
                </dt>
                <dd style="width: 30%">
                    <input type="text" name="loanName_u" id="loanName_u" class="required"  title="Enter the new loan type"  tabindex="1" size="30" onchange="javascript: valid.validateInput(this);" />
                    <div id="loanName_uError" class="validationError"	style="display: none"></div>
                </dd>
                <dt style="width: 15%">
                    <label for="allowanceId_u">Allowance Mapping :</label>
                </dt>
                <dd style="width: 30%">
                    <select name="allowanceId_u" id="allowanceId_u" class="required"  title="select the allowance to map"  tabindex="2" style="width: 200px" onchange="javascript: valid.validateInput(this);">
                    </select>
                    <div id="allowanceId_uError" class="validationError"	style="display: none"></div>
                </dd>
            </dl>
            <dl class="element">
                <dt style="width: 15%">
                    <label for="minAmount_u">Minimum Amount :</label>
                </dt>
                <dd style="width: 30%">
                    <input type="text" name="minAmount_u" id="minAmount_u" class="required"  title="Enter the minimum amount to sanction"  tabindex="3" size="15" onchange="javascript: valid.validateInput(this);" />
                    <div id="minAmount_uError" class="validationError"	style="display: none"></div>
                </dd>
                <dt style="width: 15%">
                    <label for="maxAmount_u">Maximum Amount :</label>
                </dt>
                <dd style="width: 30%">
                    <input type="text" name="maxAmount_u" id="maxAmount_u" class="required"  title="Enter the maximum amount to sanction"  tabindex="4" size="15" onchange="javascript: valid.validateInput(this);" />
                    <div id="maxAmount_uError" class="validationError"	style="display: none"></div>
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
                <span>Loan Type Record Details Form </span>
            </div>
            <dl>
                <dt style="width: 15%;">
                    <label for="loanName_d">Loan Name :</label>
                </dt>
                <dd style="width: 30%">
                    <span id="loanName_d"></span>
                </dd>
                <dt style="width: 15%;">
                    <label for="allowanceName">Allowance Mapping :</label>
                </dt>
                <dd style="width: 30%">
                    <span id="allowanceName"></span>
                </dd>
            </dl>
            <dl>
                <dt style="width: 15%;">
                    <label for="minAmount_d">Minimum Amount :</label>
                </dt>
                <dd style="width: 30%">
                    <span id="minAmount_d"></span>
                </dd>
                <dt style="width: 15%;">
                    <label for="maxAmount_d">Maximum Amount :</label>
                </dt>
                <dd style="width: 30%">
                    <span id="maxAmount_d"></span>
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
            <span>Tabulated Listing Of All Loan Types</span>
        </div>
        <table  class="display"
               id="groupRecords">
            <thead>
            <tr>
            	<th>Loan Name</th>
            	<th>Allowance Map</th>
            	<th>Min Amount</th>
            	<th>Max Amount</th>
                <th style="width: 160px">View Details</th>
                <th style="width: 150px">Edit Details</th>
            </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </fieldset>
</div>