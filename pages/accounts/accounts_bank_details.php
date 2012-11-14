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
$body->startBody ( 'accounts', 'LMENUL108', 'Bank Name Lookup Form' );
?>
<div id="content_header">
    <div id="pageButton" class="buttons">
        <button type="button" class="regular toggle" onclick="showHideSearchForm()">Toggle Search Form</button>
        <button type="button" class="regular toggle" onclick="showHideDatatable()">Toggle Tabulated Data</button>
    </div>
    <div id="contentHeader">Bank Details Form </div>
</div>
<div class="clear"></div>
<div class="inputs">
    <form id="insertForm" class="insertForm" onsubmit="return valid.validateForm(this) ? processInsertForm() : false;">
        <fieldset class="formelements">
            <div class="legend">
                <span>New Bank Account Entry Form</span>
            </div>
            <dl class="element">
                <dt style="width: 15%">
                    <label for="bankName">Bank Name :</label>
                </dt>
                <dd style="width: 30%">
                    <input type="text" name="bankName" id="bankName" class="required"  title="Enter Bank Name"  tabindex="1" value="" size="30" onchange="javascript: valid.validateInput(this);" />
                    <div id="bankNameError" class="validationError"	style="display: none"></div>
                </dd>
                <dt style="width: 15%">
                    <label for="branchName">Branch Name :</label>
                </dt>
                <dd style="width: 30%">
                    <input type="text" name="branchName" id="branchName" class="required"  title="Enter The Bank Branch Name"  tabindex="2" value="" size="30" onchange="javascript: valid.validateInput(this);" />
                    <div id="branchNameError" class="validationError"	style="display: none"></div>
                </dd>
            </dl>
            <dl class="element">
                <dt style="width: 15%">
                    <label for="ifscCode">IFSC Code :</label>
                </dt>
                <dd style="width: 30%">
                    <input type="text" name="ifscCode" id="ifscCode" class="required"  title="IFSC Code of the bank"  tabindex="3" value="" size="30" onchange="javascript: valid.validateInput(this);" />
                    <div id="ifscCodeError" class="validationError"	style="display: none"></div>
                </dd>
                <dt style="width: 15%">
                    <label for="micrCode">MICR Code :</label>
                </dt>
                <dd style="width: 30%">
                    <input type="text" name="micrCode" id="micrCode" class="required"  title="MICR Code of the bank"  tabindex="4" value="" size="30" onchange="javascript: valid.validateInput(this);" />
                    <div id="micrCodeError" class="validationError"	style="display: none"></div>
                </dd>
            </dl>            
        </fieldset>
        <fieldset class="formelements">
			<div class="legend">
				<span>Address Details of the Bank</span>
			</div>
			<dl class="element">
				<dt style="width: 15%"><label for="streetAddress1">Flat / House No :</label>	</dt>
				<dd style="width: 80%">
						<input type="text" name="streetAddress1" size="50" id="streetAddress1" class="required" tabindex="4" onchange="javascript: valid.validateInput(this);" title="Enter the House Details" />
						<div id="streetAddress1Error" class="validationError" style="display: none"></div></dd>				
			</dl>
			<dl class="element">
				<dt style="width: 15%"><label for="streetAddress2">Street Address :</label>	</dt>
				<dd style="width: 80%">
						<input type="text" size="50" name="streetAddress2" id="streetAddress2" class="required" tabindex="5" onchange="javascript: valid.validateInput(this);" title="Enter the street address" />
						<div id="streetAddress2Error" class="validationError" style="display: none"></div></dd>				
			</dl>
			<dl class="element">
				<dt style="width: 15%"><label for="city">City :</label>	</dt>
				<dd style="width: 30%">
						<input type="hidden" name="city_val" id="city_val" />
						<input type="text" name="city" id="city" class="required autocomplete" tabindex="6" size="30" onchange="javascript: valid.validateInput(this);" title="Enter the city" />
						<div id="cityError" class="validationError" style="display: none"></div></dd>
				<dt style="width: 15%"><label for="state">State :</label>	</dt>
				<dd style="width: 30%">
						<input type="hidden" name="state_val" id="state_val" />
						<input type="text" name="state" id="state" class="required autocomplete" tabindex="7" size="30" onchange="javascript: valid.validateInput(this);" title="Enter the state" />
						<div id="stateError" class="validationError" style="display: none"></div></dd>
			</dl>			
			<dl class="element">
				<dt style="width: 15%"><label for="pincode">Pincode :</label>	</dt>
				<dd style="width: 30%">
						<input type="text" name="pincode" id="pincode" class="required numeric" tabindex="8" size="30" onchange="javascript: valid.validateInput(this);" title="Enter the pincode of the address" />
						<div id="pincodeError" class="validationError" style="display: none"></div></dd>
				<dt style="width: 15%"><label for="country">Country :</label>	</dt>
				<dd style="width: 30%">
						<input type="hidden" name="country_val" id="country_val" />
						<input type="text" name="country" id="country" class="required autocomplete" tabindex="9" size="30" onchange="javascript: valid.validateInput(this);" title="Enter the country name" />
						<div id="countryError" class="validationError" style="display: none"></div></dd>
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
                <span>Bank Account Record Update Form</span>
            </div>
            <dl class="element">
                <dt style="width: 15%">
                    <label for="bankName_u">Bank Name :</label>
                </dt>
                <dd style="width: 30%">
                    <input type="text" name="bankName_u" id="bankName_u" class="required"  title="Enter Bank Name"  tabindex="1" value="" size="30" onchange="javascript: valid.validateInput(this);" />
                    <div id="bankName_uError" class="validationError"	style="display: none"></div>
                </dd>
                <dt style="width: 15%">
                    <label for="branchName_u">Branch Name :</label>
                </dt>
                <dd style="width: 30%">
                    <input type="text" name="branchName_u" id="branchName_u" class="required"  title="Enter The Bank Branch Name"  tabindex="2" value="" size="30" onchange="javascript: valid.validateInput(this);" />
                    <div id="branchName_uError" class="validationError"	style="display: none"></div>
                </dd>
            </dl>
            <dl class="element">
                <dt style="width: 15%">
                    <label for="ifscCode_u">IFSC Code :</label>
                </dt>
                <dd style="width: 30%">
                    <input type="text" name="ifscCode_u" id="ifscCode_u" class="required"  title="IFSC Code of the bank"  tabindex="3" value="" size="30" onchange="javascript: valid.validateInput(this);" />
                    <div id="ifscCode_uError" class="validationError"	style="display: none"></div>
                </dd>
                <dt style="width: 15%">
                    <label for="micrCode_u">MICR Code :</label>
                </dt>
                <dd style="width: 30%">
                    <input type="text" name="micrCode_u" id="micrCode_u" class="required"  title="MICR Code of the bank"  tabindex="4" value="" size="30" onchange="javascript: valid.validateInput(this);" />
                    <div id="micrCode_uError" class="validationError"	style="display: none"></div>
                </dd>
            </dl>            
        </fieldset>
        <fieldset class="formelements">
			<div class="legend">
				<span>Address Details of the Bank</span>
			</div>
			<dl class="element">
				<dt style="width: 15%"><label for="streetAddress1_u">Flat / House No :</label>	</dt>
				<dd style="width: 80%">
						<input type="text" name="streetAddress1_u" size="50" id="streetAddress1_u" class="required" tabindex="4" onchange="javascript: valid.validateInput(this);" title="Enter the House Details" />
						<div id="streetAddress1_uError" class="validationError" style="display: none"></div></dd>				
			</dl>
			<dl class="element">
				<dt style="width: 15%"><label for="streetAddress2_u">Street Address :</label>	</dt>
				<dd style="width: 80%">
						<input type="text" size="50" name="streetAddress2_u" id="streetAddress2_u" class="required" tabindex="5" onchange="javascript: valid.validateInput(this);" title="Enter the street address" />
						<div id="streetAddress2_uError" class="validationError" style="display: none"></div></dd>				
			</dl>
			<dl class="element">
				<dt style="width: 15%"><label for="city_u">City :</label>	</dt>
				<dd style="width: 30%">
						<input type="hidden" name="city_uval" id="city_uval" />
						<input type="text" name="city_u" id="city_u" class="required autocomplete" tabindex="6" size="30" onchange="javascript: valid.validateInput(this);" title="Enter the city" />
						<div id="city_uError" class="validationError" style="display: none"></div></dd>
				<dt style="width: 15%"><label for="state_u">State :</label>	</dt>
				<dd style="width: 30%">
						<input type="hidden" name="state_uval" id="state_uval" />
						<input type="text" name="state_u" id="state_u" class="required autocomplete" tabindex="7" size="30" onchange="javascript: valid.validateInput(this);" title="Enter the state" />
						<div id="state_uError" class="validationError" style="display: none"></div></dd>
			</dl>			
			<dl class="element">
				<dt style="width: 15%"><label for="pincode_u">Pincode :</label>	</dt>
				<dd style="width: 30%">
						<input type="text" name="pincode_u" id="pincode_u" class="required numeric" tabindex="8" size="30" onchange="javascript: valid.validateInput(this);" title="Enter the pincode of the address" />
						<div id="pincode_uError" class="validationError" style="display: none"></div></dd>
				<dt style="width: 15%"><label for="country_u">Country :</label>	</dt>
				<dd style="width: 30%">
						<input type="hidden" name="country_uval" id="country_uval" />
						<input type="text" name="country_u" id="country_u" class="required autocomplete" tabindex="9" size="30" onchange="javascript: valid.validateInput(this);" title="Enter the country name" />
						<div id="country_uError" class="validationError" style="display: none"></div></dd>
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
                <th style="width: 150px">Edit Details</th>
            </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </fieldset>
</div>