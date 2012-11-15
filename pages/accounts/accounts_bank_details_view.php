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
$body->startBody ( 'accounts', 'LMENUL160', 'View Bank Name Lookup Form' );
?>
<div id="content_header">
    <div id="pageButton" class="buttons">
        <button type="button" class="regular toggle" onclick="showHideSearchForm()">Toggle Search Form</button>
        <button type="button" class="regular toggle" onclick="showHideDatatable()">Toggle Tabulated Data</button>
    </div>
    <div id="contentHeader">Bank Details Form </div>
</div>



<div class="clear"></div>
<div class="display">
    <div id="displayRecord" style="display:none">
        <fieldset class="displayElements">
            <div class="legend">
                <span>Bank Name Details </span>
            </div>
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
                    <label for="bankAddress_d">Address :</label>
                </dt>
                <dd style="width: 80%">
                    <span id="bankAddress_d"></span>
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
        </fieldset>
    </div>
</div>
<div class="clear"></div>
<div class="inputs">
    <form id="searchForm" class="searchForm" onsubmit="return getSearchResults()">
        <fieldset class="formelements">
            <div class="legend">Search Value</div>
            <dl>
                <dt style="width: 15%"><label for"hint">Bank Name :</label>
                </dt>
                <dd style="width: 30%"><input type="text" name="hint" id="hint" size="20"/>
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
            <span>Tabulated Listing Of All Banks</span>
        </div>
        <table  class="display"
               id="groupRecords">
            <thead>
            <tr>
            	<th>Bank Name</th>
            	<th>Branch</th>
            	<th>IFSC Code</th>
            	<th>MICR Code</th>
                <th style="width: 160px">View Details</th>
            </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </fieldset>
</div>