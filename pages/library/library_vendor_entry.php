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
$body->startBody ( 'library', 'LMENUL139', 'New Vendor Entry Form' );
?>
<div id="content_header">
    <div id="pageButton" class="buttons">
        <button type="button" class="regular toggle" onclick="showHideSearchForm()">Toggle Search Form</button>
        <button type="button" class="regular toggle" onclick="showHideDatatable()">Toggle Tabulated Data</button>
    </div>
    <div id="contentHeader">New Vendor Entry Form </div>
</div>
<div class="clear"></div>
<div class="inputs">
    <form id="insertForm" class="insertForm" onsubmit="return valid.validateForm(this) ? processInsertForm() : false;">
        <fieldset class="formelements">
            <div class="legend">
                <span>New Vendor Entry Form</span>
            </div>
            <dl class="element">
                <dt style="width: 15%">
                    <label for="vendorName">Vendor Name :</label>
                </dt>
                <dd style="width: 80%">
                    <input type="text" name="vendorName" id="vendorName" class="required"  title="Enter The Vendor Name"  tabindex="1" value="" size="50" onchange="javascript: valid.validateInput(this);" />
                    <div id="vendorNameError" class="validationError"	style="display: none"></div>
                </dd>
            </dl>
            <dl class="element">
                <dt style="width: 15%">
                    <label for="weightage">Win Rate :</label>
                </dt>
                <dd style="width: 30%">
                    <input type="text" name="weightage" id="weightage" class="required numeric"  title="The Win Probability Rate <100%"  tabindex="2" value="" size="15" onchange="javascript: valid.validateInput(this);" />
                    <div id="weightageError" class="validationError"	style="display: none"></div>
                </dd>
            </dl>    
            <dl class="element">
                <dt style="width: 15%">
                    <label for="contactNo">Contact Number :</label>
                </dt>
                <dd style="width: 30%">
                    <input type="text" name="contactNo" id="contactNo" class="required"  title="The contact number of the vendor"  tabindex="3" value="" size="30" onchange="javascript: valid.validateInput(this);" />
                    <div id="contactNoError" class="validationError"	style="display: none"></div>
                </dd>
                <dt style="width: 15%">
                    <label for="emailId">Email Id :</label>
                </dt>
                <dd style="width: 30%">
                    <input type="text" name="emailId" id="emailId" class=""  title="The email id of the vendor"  tabindex="4" value="" size="30" onchange="javascript: valid.validateInput(this);" />
                    <div id="emailIdError" class="validationError"	style="display: none"></div>
                </dd>
            </dl>        
        </fieldset>
        <fieldset>
			<div class="legend"><span id="lengendAddress">Address Details Of The Vendor</span></div>
			<dl></dl>
			<dl class="element">
				<dt style="width: 15%"><label for="streetAddress1">Flat / House No :</label>	</dt>
				<dd style="width: 80%">
						<input type="text" name="streetAddress1" size="50" id="streetAddress1" class="required" tabindex="5" onchange="javascript: valid.validateInput(this);" title="Enter the House Details" />
						<div id="streetAddress1Error" class="validationError" style="display: none"></div></dd>				
			</dl>
			<dl class="element">
				<dt style="width: 15%"><label for="streetAddress2">Street Address :</label>	</dt>
				<dd style="width: 80%">
						<input type="text" size="50" name="streetAddress2" id="streetAddress2" class="required" tabindex="6" onchange="javascript: valid.validateInput(this);" title="Enter the street address" />
						<div id="streetAddress2Error" class="validationError" style="display: none"></div></dd>				
			</dl>
			<dl class="element">
				<dt style="width: 15%"><label for="city">City :</label>	</dt>
				<dd style="width: 30%">
						<input type="hidden" name="city_val" id="city_val" />
						<input type="text" name="city" id="city" class="required autocomplete" tabindex="7" size="30" onchange="javascript: valid.validateInput(this);" title="Enter the city" />
						<div id="cityError" class="validationError" style="display: none"></div></dd>
				<dt style="width: 15%"><label for="state">State :</label>	</dt>
				<dd style="width: 30%">
						<input type="hidden" name="state_val" id="state_val" />
						<input type="text" name="state" id="state" class="required autocomplete" tabindex="8" size="30" onchange="javascript: valid.validateInput(this);" title="Enter the state" />
						<div id="stateError" class="validationError" style="display: none"></div></dd>
			</dl>			
			<dl class="element">
				<dt style="width: 15%"><label for="pincode">Pincode :</label>	</dt>
				<dd style="width: 30%">
						<input type="text" name="pincode" id="pincode" class="required numeric" tabindex="9" size="30" onchange="javascript: valid.validateInput(this);" title="Enter the pincode of the address" />
						<div id="pincodeError" class="validationError" style="display: none"></div></dd>
				<dt style="width: 15%"><label for="country">Country :</label>	</dt>
				<dd style="width: 30%">
						<input type="hidden" name="country_val" id="country_val" />
						<input type="text" name="country" id="country" class="required autocomplete" tabindex="10" size="30" onchange="javascript: valid.validateInput(this);" title="Enter the country name" />
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
            <fieldset class="formelements">
            <div class="legend">
                <span>Existing Vendor Record Edit Form</span>
            </div>            
            <dl class="element">
                <dt style="width: 15%">
                    <label for="vendorName_u">Vendor Name :</label>
                </dt>
                <dd style="width: 80%">
                    <input type="text" name="vendorName_u" id="vendorName_u" class="required"  title="Enter The Vendor Name"  tabindex="11" value="" size="50" onchange="javascript: valid.validateInput(this);" />
                    <div id="vendorName_uError" class="validationError"	style="display: none"></div>
                </dd>
            </dl>
            <dl class="element">
                <dt style="width: 15%">
                    <label for="weightage_u">Win Rate :</label>
                </dt>
                <dd style="width: 30%">
                    <input type="text" name="weightage_u" id="weightage_u" class="required numeric"  title="The Win Probability Rate <100%"  tabindex="12" value="" size="15" onchange="javascript: valid.validateInput(this);" />
                    <div id="weightage_uError" class="validationError"	style="display: none"></div>
                </dd>
            </dl>    
            <dl class="element">
                <dt style="width: 15%">
                    <label for="contactNo_u">Contact Number :</label>
                </dt>
                <dd style="width: 30%">
                    <input type="text" name="contactNo_u" id="contactNo_u" class="required"  title="The contact number of the vendor"  tabindex="13" value="" size="30" onchange="javascript: valid.validateInput(this);" />
                    <div id="contactNo_uError" class="validationError"	style="display: none"></div>
                </dd>
                <dt style="width: 15%">
                    <label for="emailId_u">Email Id :</label>
                </dt>
                <dd style="width: 30%">
                    <input type="text" name="emailId_u" id="emailId_u" class=""  title="The email id of the vendor"  tabindex="14" value="" size="30" onchange="javascript: valid.validateInput(this);" />
                    <div id="emailId_uError" class="validationError"	style="display: none"></div>
                </dd>
            </dl>        
        </fieldset>
        <fieldset>
			<div class="legend"><span id="lengendAddress">Address Details Of The Vendor</span></div>
			<dl></dl>
			<dl class="element">
				<dt style="width: 15%"><label for="streetAddress1_u">Flat / House No :</label>	</dt>
				<dd style="width: 80%">
						<input type="text" name="streetAddress1_u" size="50" id="streetAddress1_u" class="required" tabindex="15" onchange="javascript: valid.validateInput(this);" title="Enter the House Details" />
						<div id="streetAddress1_uError" class="validationError" style="display: none"></div></dd>				
			</dl>
			<dl class="element">
				<dt style="width: 15%"><label for="streetAddress2_u">Street Address :</label>	</dt>
				<dd style="width: 80%">
						<input type="text" size="50" name="streetAddress2_u" id="streetAddress2_u" class="required " tabindex="16" onchange="javascript: valid.validateInput(this);" title="Enter the street address" />
						<div id="streetAddress2_uError" class="validationError" style="display: none"></div></dd>				
			</dl>
			<dl class="element">
				<dt style="width: 15%"><label for="city_u">City :</label>	</dt>
				<dd style="width: 30%">
						<input type="hidden" name="city_uval" id="city_uval" />
						<input type="text" name="city_u" id="city_u" class="required autocomplete" tabindex="17" size="30" onchange="javascript: valid.validateInput(this);" title="Enter the city" />
						<div id="city_uError" class="validationError" style="display: none"></div></dd>
				<dt style="width: 15%"><label for="state">State :</label>	</dt>
				<dd style="width: 30%">
						<input type="hidden" name="state_uval" id="state_uval" />
						<input type="text" name="state_u" id="state_u" class="required autocomplete" tabindex="18" size="30" onchange="javascript: valid.validateInput(this);" title="Enter the state" />
						<div id="state_uError" class="validationError" style="display: none"></div></dd>
			</dl>			
			<dl class="element">
				<dt style="width: 15%"><label for="pincode_u">Pincode :</label>	</dt>
				<dd style="width: 30%">
						<input type="text" name="pincode_u" id="pincode_u" class="required numeric" tabindex="19" size="30" onchange="javascript: valid.validateInput(this);" title="Enter the pincode of the address" />
						<div id="pincode_uError" class="validationError" style="display: none"></div></dd>
				<dt style="width: 15%"><label for="country_u">Country :</label>	</dt>
				<dd style="width: 30%">
						<input type="hidden" name="country_uval" id="country_uval" />
						<input type="text" name="country_u" id="country_u" class="required autocomplete" tabindex="20" size="30" onchange="javascript: valid.validateInput(this);" title="Enter the country name" />
						<div id="country_uError" class="validationError" style="display: none"></div></dd>
			</dl>
		</fieldset>
            
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
                <span>Vendor Record Details</span>
            </div>
            <dl>
                <dt style="width: 15%;">
                    <label for="vendorName_d">Vendor Name :</label>
                </dt>
                <dd style="width: 30%">
                    <span id="vendorName_d"></span>
                </dd>
                <dt style="width: 15%;">
                    <label for="weightage_d">Win Rate :</label>
                </dt>
                <dd style="width: 30%">
                    <span id="weightage_d"></span>
                </dd>
            </dl>
            <dl>
                <dt style="width: 15%;">
                    <label for="contactNo_d">Contact Number :</label>
                </dt>
                <dd style="width: 30%">
                    <span id="contactNo_d"></span>
                </dd>
                <dt style="width: 15%;">
                    <label for="emailId_d">Email ID :</label>
                </dt>
                <dd style="width: 30%">
                    <span id="emailId_d"></span>
                </dd>
            </dl>
            <dl>
                <dt style="width: 15%;">
                    <label for="address_d">Address :</label>
                </dt>
                <dd style="width: 80%">
                    <span id="address_d"></span>
                </dd>
            </dl>
            <dl>
                <dt style="width: 15%;">
                    <label for="categories_d">Categories :</label>
                </dt>
                <dd style="width: 80%">
                    <span id=""></span>
                </dd>
            </dl>
            <dl>
                <dt style="width: 15%;">
                    <label for="tags">Tags :</label>
                </dt>
                <dd style="width: 80%">
                    <span id="tags"></span>
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
            <button type="button" class="regular browse" id="categoryButton"
                    >Manage Category</button>
            <button type="button" class="regular browse" id="tagButton"
                    >Manage Tag</button>        
                    
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
                    <label for="menu_hint">Vendor Name :</label>
                </dt>
                <dd style="width: 30%">
                    <input type="text" name="hint" id="hint" class=""
                           style="width: 200px" title="Enter The Vendor Name" />
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
            <span>Tabulated Listing Of All Vendors</span>
        </div>
        <table  class="display"
               id="groupRecords">
            <thead>
            <tr>
            	<th>Vendor Name</th>
            	<th>Win Rate</th>
            	<th>Contact No</th>
                <th style="width: 160px">View Details</th>
                <th style="width: 150px">Edit Details</th>
            </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </fieldset>
</div>