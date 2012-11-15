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
$body->startBody ( 'accounts', 'LMENUL125', 'Employee Bank Account Record' );
?>
<div id="content_header">
    <div id="pageButton" class="buttons">
    	<button type="button" class="negative toggle" onclick="changeEmployeeName()">Change Employee </button>
        <button type="button" class="regular toggle" onclick="showHideSearchForm()">Toggle Search Form</button>
        <button type="button" class="regular toggle" onclick="showHideDatatable()">Toggle Tabulated Data</button>
    </div>
    <div id="contentHeader">Employee Bank Account Record</div>
</div>
<div class="clear"></div>
<div id="choiceListing">
    <div class="inputs">
        <fieldset class="formelements">
            <div class="legend">
                <span>Select Employee For Bank Account Details</span>
            </div>
            <dl class="element">
                <dt style="width: 15%"><label for="employee">Employee Name :</label>	</dt>
                <dd style="width: 80%">
                    <input type="hidden" name="employee_val" id="employee_val" />
                    <input type="text" class="required autocomplete" name="employee" id="employee" size="40" id="session" onblur="checkEmployeeChange()" />
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
        <form id="updateForm" class="updateForm" onsubmit="return valid.validateForm(this) ? processUpdateForm() : false;" style="display:none">
            <fieldset class="formelements">
                <div class="legend">
                    <span id="legend_editForm">Update Bank Account Details</span>
                </div>
                <dl class="element">
                	<dt style="width: 15%">
                        <label for="bankAccountNumber">Account Number :</label>
                    </dt>
                    <dd style="width: 30%">                        
                    	<input type="text" name="bankAccountNumber" id="bankAccountNumber" class="required"  title="Enter Account Number"  tabindex="1" size="40" onchange="javascript: valid.validateInput(this);" />
                    	<div id="bankAccountNumberError" class="validationError"	style="display: none"></div>                        
                    </dd>
                    <dt style="width: 15%">
                        <label for="accountType">Account Type :</label>
                    </dt>
                    <dd style="width: 30%">
                        <span id="accountType_u"></span>
                    </dd>                    
                </dl>
                <dl class="element">
                    <dt style="width: 15%">
                        <label for="BankName">Bank Name :</label>
                    </dt>
                    <dd style="width: 30%">
                    	<select name="bankName" id="bankName" class="required"  title="Select Bank Name"  tabindex="3" style="width: 250px" onchange="javascript: valid.validateInput(this);">
                        </select>
                        <div id="bankNameError" class="validationError"	style="display: none"></div>
                    </dd>
                </dl>
            </fieldset>
            <fieldset class="action buttons">
                <input type="hidden" name="valueId_u" id="valueId_u" value="" /> <input
                type="hidden" name="rowPosition_u" id="rowPosition_u" value="" />
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
                    <span>Bank Account Record Details Form </span>
                </div>
                <dl>
                    <dt style="width: 15%;">
                        <label for="accountNumber_d">Account Number :</label>
                    </dt>
                    <dd style="width: 30%">
                        <span id="accountNumber_d"></span>
                    </dd>
                    <dt style="width: 15%;">
                        <label for="accountType_d">Account Type :</label>
                    </dt>
                    <dd style="width: 30%">
                        <span id="accountType_d"></span>
                    </dd>
                </dl>
                <dl>
	                <dt style="width: 15%;">
	                    <label for="bankName_d">Bank Name :</label>
	                </dt>
	                <dd style="width: 30%">
	                    <span id="bankName_d"></span>
	                </dd>
	                <dt style="width: 15%;">
	                    <label for="branchName_d">Branch :</label>
	                </dt>
	                <dd style="width: 30%">
	                    <span id="branchName_d"></span>
	                </dd>
	            </dl>
	            <dl>
	                <dt style="width: 15%;">
	                    <label for="ifscCode_d">IFSC Code :</label>
	                </dt>
	                <dd style="width: 30%">
	                    <span id="ifscCode_d"></span>
	                </dd>
	                <dt style="width: 15%;">
	                    <label for="micrCode_d">MICR Code :</label>
	                </dt>
	                <dd style="width: 30%">
	                    <span id="micrCode_d"></span>
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
                <button type="submit" name="searchData" id="searchData" class="positive search">Get Search
                    Results</button>
            </fieldset>
        </form>
    </div>

    <div class="clear"></div>


    <div class="datatable buttons" id="displayDatatable" style="display:none">
        <fieldset class="formelements">
            <div class="legend">
                <span>Tabulated Listing Of All Bank Accounts</span>
            </div>
            <table  class="display"
                    id="groupRecords">
                <thead>
                <tr>
                    <th>Account Type</th>
                    <th>Bank Account</th>
                    <th>Bank Name</th>
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
