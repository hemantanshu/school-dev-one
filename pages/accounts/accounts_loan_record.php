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
$body->startBody ( 'accounts', 'LMENUL144', 'Accounts Loan Record' );
?>
<div id="content_header">
    <div id="pageButton" class="buttons">
        <button type="button" class="regular toggle" onclick="showHideSearchForm()">Toggle Search Form</button>
        <button type="button" class="regular toggle" onclick="showHideDatatable()">Toggle Tabulated Data</button>
    </div>
    <div id="contentHeader">Loan Record Details</div>
</div>
<div class="clear"></div>
<div class="display">
    <div id="displayRecord" style="display:none">
        <fieldset class="displayElements">
            <div class="legend">
                <span>Loan Record Details</span>
            </div>
            <dl>
                <dt style="width: 15%;">
                    <label for="employeeNameDisplay">Employee Name :</label>
                </dt>
                <dd style="width: 30%">
                    <span id="employeeNameDisplay"></span>
                </dd>
                <dt style="width: 15%;">
                    <label for="employeeCodeDisplay">Emp Code :</label>
                </dt>
                <dd style="width: 30%">
                    <span id="employeeCodeDisplay"></span>
                </dd>
            </dl>
            <dl>
                <dt style="width: 15%;">
                    <label for="loanTypeDisplay">Loan Type :</label>
                </dt>
                <dd style="width: 30%">
                    <span id="loanTypeDisplay"></span>
                </dd>
                <dt style="width: 15%;">
                    <label for="loanSanctionDateDisplay">Sanction Date :</label>
                </dt>
                <dd style="width: 30%">
                    <span id="loanSanctionDateDisplay"></span>
                </dd>
            </dl>
            <dl>
                <dt style="width: 15%;">
                    <label for="loanAmountDiplay">Loan Amount :</label>
                </dt>
                <dd style="width: 30%">
                    <span id="loanAmountDisplay"></span>
                </dd>
                <dt style="width: 15%;">
                    <label for="installmentAmountDisplay">Installment :</label>
                </dt>
                <dd style="width: 30%">
                    <span id="installmentAmountDisplay"></span>
                </dd>
            </dl>
            <dl>
                <dt style="width: 15%;">
                    <label for="interestTypeDisplay">Interest Type :</label>
                </dt>
                <dd style="width: 30%">
                    <span id="interestTypeDisplay"></span>
                </dd>
                <dt style="width: 15%;">
                    <label for="paymentModeDisplay">Payment Mode :</label>
                </dt>
                <dd style="width: 30%">
                    <span id="paymentModeDisplay"></span>
                </dd>
            </dl>
            <dl>
                <dt style="width: 15%;">
                    <label for="flexiInstallmentDisplay">Flexi Installment :</label>
                </dt>
                <dd style="width: 30%">
                    <span id="flexiInstallmentDisplay"></span>
                </dd>
                <dt style="width: 15%;">
                    <label for="interestDisplay">Interest Rate :</label>
                </dt>
                <dd style="width: 30%">
                    <span id="interestDispaly"></span>
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
            <input type="hidden" name="valueId_d" id="valueId_d" value="" /> 
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
                    <label for="loanTypeHint">Loan Type :</label>
                </dt>
                <dd style="width: 30%">                	
                    <select name="laonTypeHint" id="loanTypeHint" class="required"
                           style="width: 200px" title="Enter The Vendor Name">
                    </select>
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
            <button type="submit" name="searchData" id="searchData" class="positive search">Get Loan Records</button>
        </fieldset>
</div>

<div class="clear"></div>


<div class="datatable buttons" id="displayDatatable" style="display:none">
    <fieldset class="formelements">
        <div class="legend">
            <span>Loan Records Listing</span>
        </div>
        <table  class="display"
               id="groupRecords">
            <thead>
            <tr>
            	<th>Loan Type</th>
            	<th>Employee</th>
            	<th>Loan Amt</th>
            	<th>Sanction Date</th>
                <th style="width: 170px">Statement</th>
                <th style="width: 150px">Details</th>
            </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </fieldset>
</div>