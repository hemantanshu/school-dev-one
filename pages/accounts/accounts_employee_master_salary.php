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
$body->startBody ( 'accounts', 'LMENUL112', 'Employee Master Salary Record' );
?>
<div id="content_header">
    <div id="pageButton" class="buttons">
    	<button type="button" class="negative toggle" onclick="changeEmployeeName()">Change Employee </button>
        <button type="button" class="regular toggle" onclick="showHideSearchForm()">Toggle Search Form</button>
        <button type="button" class="regular toggle" onclick="showHideDatatable()">Toggle Tabulated Data</button>
    </div>
    <div id="contentHeader">Employee Master Salary Record</div>
</div>
<div class="clear"></div>
<div id="choiceListing">
    <div class="inputs">
        <fieldset class="formelements">
            <div class="legend">
                <span>Select Employee For Master Salary Record</span>
            </div>
            <dl class="element">
                <dt style="width: 15%"><label for="employee">Employee Name :</label>	</dt>
                <dd style="width: 80%">
                    <input type="hidden" name="employee_val" id="employee_val" />
                    <input type="text" class="required" name="employee" id="employee" size="40" id="session" onblur="checkEmployeeChange()" />
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
                        <label for="employeeName">Employee Name :</label>
                    </dt>
                    <dd style="width: 30%">
                        <span id="employeeName"></span>
                    </dd>
                    <dt style="width: 15%">
                        <label for="employeeCode">Employee Code :</label>
                    </dt>
                    <dd style="width: 30%">
                        <span id="employeeCode"></span>
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
                    <span>Employee Allowance Setup</span>
                </div>
                <dl class="element">
                    <dt style="width: 15%">
                        <label for="allowance">Allowance Name :</label>
                    </dt>
                    <dd style="width: 30%">
                        <input type="hidden" name="allowance_val" id="allowance_val" onchange="populateAllowanceFundDetails()"/>
                        <input type="text" name="allowance" id="allowance" class="required autocomplete"  title="select the allowance name"  tabindex="1" size="40" onblur="populateAllowanceFundDetails()" />
                        <div id="allowance_valError" class="validationError"	style="display: none"></div>
                    </dd>                    
                </dl>
                <dl class="element">                    
                    <dt style="width: 15%">
                        <label for="amount">Amount :</label>
                    </dt>
                    <dd style="width: 30%">
                        <input type="text" name="amount" id="amount" class="numeric"  title="Amount against the allowance"  tabindex="2" size="20" onchange="javascript: valid.validateInput(this);" />
                        <div id="amountError" class="validationError"	style="display: none"></div>
                    </dd>
                    <dt style="width: 15%">
                        <label for="calculatedAmount">Calculated Amount :</label>
                    </dt>
                    <dd style="width: 30%">
                    	<input type="hidden" name="calculatedAmount_i" id="calculatedAmount_i" />
                        <span id="calculatedAmount"></span>
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
                    <span id="legend_editForm">Allowance Master Record Update</span>
                </div>
                <dl class="element">
                    <dt style="width: 15%">
                        <label for="allowance_u">Allowance Name :</label>
                    </dt>
                    <dd style="width: 30%">
                    	<input type="hidden" name="allowance_uval" id="allowance_uval" />
                        <span id="allowance_u"></span>
                    </dd>
                    <dt style="width: 15%">
                        <label for="amount_u">Amount :</label>
                    </dt>
                    <dd style="width: 30%">
                        <input type="text" name="amount_u" id="amount_u" class="required"  title="Amount against the allowance"  tabindex="2" size="20" onchange="javascript: valid.validateInput(this);" />
                        <div id="amount_uError" class="validationError"	style="display: none"></div>
                    </dd>
                </dl>
                <dl class="element">
                    <dt style="width: 15%">
                        <label for="calculatedAmount_u">Calculated Amount :</label>
                    </dt>
                    <dd style="width: 30%">
                    	<input type="hidden" name="calculatedAmount_ui" id="calculatedAmount_ui" />
                        <span id="calculatedAmount_u"></span>
                    </dd>
                    <dt style="width: 15%">
                        <label for="type_u">Over Ridden :</label>
                    </dt>
                    <dd style="width: 30%">
                        <span id="type_u"></span>
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
                        <label for="allowance_d">Allowance Name :</label>
                    </dt>
                    <dd style="width: 30%">
                        <span id="allowance_d"></span>
                    </dd>
                    <dt style="width: 15%;">
                        <label for="type_d">Over Ridden :</label>
                    </dt>
                    <dd style="width: 30%">
                        <span id="type_d"></span>
                    </dd>
                </dl>
                <dl>
                    <dt style="width: 15%;">
                        <label for="actualAmount_d">Actual Amount :</label>
                    </dt>
                    <dd style="width: 30%">
                        <span id="actualAmount_d"></span>
                    </dd>
                    <dt style="width: 15%;">
                        <label for="calculatedAmount_d">Calculated Amount :</label>
                    </dt>
                    <dd style="width: 30%">
                        <span id="calculatedAmount_d"></span>
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
                <span>Tabulated Listing Of All Allowance Heads</span>
            </div>
            <table  class="display"
                    id="groupRecords">
                <thead>
                <tr>
                    <th>Allowance Head</th>
                    <th>Actual Amt</th>
                    <th>Calculated Amt</th>
                    <th>Change</th>
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
