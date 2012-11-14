<?php
require_once 'config.php';
require_once BASE_PATH . 'include/global/class.body.php';


$body = new body ();
$body->startBody ( 'utility', 'LMENUL32', 'Institute Entry Page' );
?>
<div id="content_header">
    <div id="pageButton" class="buttons">
        <button type="button" class="regular toggle" onclick="showHideSearchForm()"><span class="underline">T</span>oggle Search Form</button>
        <button type="button" class="regular toggle" onclick="showHideDatatable()"><span class="underline">T</span>oggle Tabulated Data</button>
    </div>
    <div id="contentHeader">Institute / College Record Entry Form</div>
</div>
<div class="clear"></div>
<div class="inputs">
	<form id="insertForm" class="insertForm" onsubmit="return valid.validateForm(this) ? processInsertForm() : false;">
		<fieldset class="formelements">
			<div class="legend">
				<span>New Institute / College Record Entry</span>
			</div>
			<dl class="element">
				<dt style="width: 15%"><label for="collegeName">Institute Name :</label>	</dt>
				<dd style="width: 80%">
						<input type="text" size="80" name="collegeName" id="collegeName" class="required" tabindex="1" onchange="javascript: valid.validateInput(this);" title="Insert The College Name" />
						<div id="collegeNameError" class="validationError" style="display: none"></div></dd>				
			</dl>
			<dl class="element">
				<dt style="width: 15%"><label for="university">University / Board :</label>	</dt>
				<dd style="width: 30%">
						<input type="hidden" name="university_val" id="university_val" value="" />
						<input type="text" name="university" id="university" class="required autocomplete" tabindex="2" size="30" onchange="javascript: valid.validateInput(this);" title="Select the univerysity name" />
						<div id="universityError" class="validationError" style="display: none"></div></dd>
				<dt style="width: 15%"><label for="contactno">Contact No :</label>	</dt>
				<dd style="width: 30%">
						<input type="text" name="contactno" id="contactno" class="required numeric " tabindex="3" size="30" onchange="javascript: valid.validateInput(this);" title="Insert the contact no" />
						<div id="contactnoError" class="validationError" style="display: none"></div></dd>
			</dl>
		</fieldset>
		<fieldset class="formelements">
			<div class="legend">
				<span>Address Details of the Institute</span>
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
			<button type="button" name="submit" onclick="hideInsertForm()"
				accesskey="H" class="regular hide">
				<span class="underline">H</span>ide Insert Form
			</button>

			<button type="reset" name="insertReset" class="negative reset" id="insertReset"
				accesskey="R">
				<span class="underline">R</span>eset Form Fields
			</button>

			<button type="submit" name="submit" id="submit" accesskey="I" class="positive insert">
				<span class="underline">I</span>nsert New Record
			</button>
		</fieldset>
	</form>
</div>
<div class="clear"></div>
<div class="inputs">
	<form id="updateForm" class="updateForm"
		onsubmit="return valid.validateForm(this) ? processUpdateForm() : false;">
		<fieldset class="formelements">
			<div class="legend">
				<span>Update Institute / College Record Details</span>
			</div>
			<dl class="element">
				<dt style="width: 15%"><label for="collegeName_u">Institute Name :</label>	</dt>
				<dd style="width: 80%">
						<input type="text" size="80" name="collegeName_u" id="collegeName_u" class="required" tabindex="10" onchange="javascript: valid.validateInput(this);" title="Insert The College Name" />
						<div id="collegeName_uError" class="validationError" style="display: none"></div></dd>				
			</dl>
			<dl class="element">
				<dt style="width: 15%"><label for="university_u">University / Board :</label>	</dt>
				<dd style="width: 30%">
						<input type="hidden" name="university_uval" id="university_uval" value="" />
						<input type="text" name="university_u" id="university_u" class="required" tabindex="11" size="30" onblur="resetFieldValue('university_uval');" onchange="javascript: valid.validateInput(this);" title="Select the univerysity name" />
						<div id="university_uError" class="validationError" style="display: none"></div></dd>
				<dt style="width: 15%"><label for="contactno_u">Contact No :</label>	</dt>
				<dd style="width: 30%">
						<input type="text" name="contactno_u" id="contactno_u" class="required numeric" tabindex="12" size="30" onchange="javascript: valid.validateInput(this);" title="Insert the contact no" />
						<div id="contactno_uError" class="validationError" style="display: none"></div></dd>
			</dl>
		</fieldset>
		<fieldset>
			<div class="legend">
				<span>Address Details of the Institute</span>
			</div>
			<dl class="element">
				<dt style="width: 15%"><label for="streetAddress1_u">Flat / House No :</label>	</dt>
				<dd style="width: 80%">
						<input type="text" name="streetAddress1_u" size="50" id="streetAddress1_u" class="required" tabindex="13" onchange="javascript: valid.validateInput(this);" title="Enter the House Details" />
						<div id="streetAddress1_uError" class="validationError" style="display: none"></div></dd>				
			</dl>
			<dl class="element">
				<dt style="width: 15%"><label for="streetAddress2_u">Street Address :</label>	</dt>
				<dd style="width: 80%">
						<input type="text" size="50" name="streetAddress2_u" id="streetAddress2_u" class="required" tabindex="14" onchange="javascript: valid.validateInput(this);" title="Enter the street address" />
						<div id="streetAddress2_uError" class="validationError" style="display: none"></div></dd>				
			</dl>
			<dl class="element">
				<dt style="width: 15%"><label for="city_u">City :</label>	</dt>
				<dd style="width: 30%">
						<input type="hidden" name="city_uval" id="city_uval" />
						<input type="text" name="city_u" id="city_u" class="required" tabindex="15" size="30"onblur="resetFieldValue('city_uval');" onchange="javascript: valid.validateInput(this);" title="Enter the city" />
						<div id="city_uError" class="validationError" style="display: none"></div></dd>
				<dt style="width: 15%"><label for="state_u">State :</label>	</dt>
				<dd style="width: 30%">
						<input type="hidden" name="state_uval" id="state_uval" />
						<input type="text" name="state_u" id="state_u" class="required" tabindex="16" size="30" onblur="resetFieldValue('state_uval');" onchange="javascript: valid.validateInput(this);" title="Enter the state" />
						<div id="state_uError" class="validationError" style="display: none"></div></dd>
			</dl>			
			<dl class="element">
				<dt style="width: 15%"><label for="pincode_u">Pincode :</label>	</dt>
				<dd style="width: 30%">
						<input type="text" name="pincode_u" id="pincode_u" class="required numeric" tabindex="17" size="30" onchange="javascript: valid.validateInput(this);" title="Enter the pincode of the address" />
						<div id="pincode_uError" class="validationError" style="display: none"></div></dd>
				<dt style="width: 15%"><label for="country_u">Country :</label>	</dt>
				<dd style="width: 30%">
						<input type="hidden" name="country_uval" id="country_uval" />
						<input type="text" name="country_u" id="country_u" class="required" tabindex="18" size="30" onblur="resetFieldValue('country_uval');" onchange="javascript: valid.validateInput(this);" title="Enter the country name" />
						<div id="country_uError" class="validationError" style="display: none"></div></dd>
			</dl>
		</fieldset>
		<fieldset class="action buttons">
			<input type="hidden" name="recordId_u" tabindex="20" id="recordId_u"
				value="" /> <input type="hidden" name="rowPosition_u" tabindex="21"
				id="rowPosition_u" value="" />
			<button accesskey="A" type="button" class="positive activate"
				name="activateRecord_u" tabindex="22" id="activateRecord_u">
				<span class="underline">A</span>ctivate Menu URL
			</button>
			<button accesskey="D" type="button" class="negative drop" name="dropRecord_u"
				tabindex="23" id="dropRecord_u">
				<span class="underline">D</span>rop Menu URL
			</button>
			<button accesskey="H" type="button" name="toggleInsert" class="regular hide" tabindex="24"
				id="toggleInsert" onclick="hideUpdateForm()">
				<span class="underline">H</span>ide Update Form
			</button>
			<button acesskey="U" type="submit" name="submit" tabindex="25" class="positive update"
				id="submit">
				<span class="underline">U</span>pdate Record
			</button>
		</fieldset>
	</form>
</div>
<div class="clear"></div>
<div class="display">
	<div id="recordDetails">
		<fieldset class="displayElements">
			<div class="legend">
				<span id="legendDisplayAssignment">Institute Record Details</span>
			</div>
			<dl>
				<dt style="width: 15%;">
					<label for="schoolName_d">Institute :</label>
				</dt>
				<dd style="width: 80%">
					<span id="schoolName_d"></span>
				</dd>				
			</dl>
			<dl>
				<dt style="width: 15%;">
					<label for="university_d">University :</label>
				</dt>
				<dd style="width: 30%">
					<span id="university_d"></span>
				</dd>
				<dt style="width: 15%">
					<label for="contactno_d">Contact No:</label>
				</dt>
				<dd style="width: 30%">
					<span id="contactno_d"></span>
				</dd>
			</dl>
			<dl>
				<dt style="width: 15%;">
					<label for="streetAddress_d">Address :</label>
				</dt>
				<dd style="width: 80%">
					<span id="streetAddress_d"></span>
				</dd>				
			</dl>
			<dl>
				<dt style="width: 15%;">
					<label for="updateDate">Last Update Date : </label>
				</dt>
				<dd style="width: 30%">
					<span id="lastUpdateDateDisplay"></span>
				</dd>
				<dt style="width: 15%">
					<label for="updatedBy">Updated BY :</label>
				</dt>
				<dd style="width: 30%">
					<span id="lastUpdatedByDisplay"></span>
				</dd>
			</dl>
			<dl>
				<dt style="width: 15%;">
					<label for="creationDate">Creation Date : </label>
				</dt>
				<dd style="width: 30%">
					<span id="creationDateDisplay"></span>
				</dd>
				<dt style="width: 15%">
					<label for="createdBy">Created BY :</label>
				</dt>
				<dd style="width: 30%">
					<span id="createdByDisplay"></span>
				</dd>
			</dl>
			<dl>
				<dt style="width: 15%;">
					<label for="active">Active/Inactive : </label>
				</dt>
				<dd style="width: 30%">
					<span id="activeDisplay"></span>
				</dd>

			</dl>
		</fieldset>
		<fieldset class="action buttons">
			<input type="hidden" name="recordId_d" id="recordId_d" value="" /> <input
				type="hidden" name="position_d" id="position_d" value="" />
			<button accesskey="A" type="button" class="positive activate"
				name="activateRecord_d" tabindex="26" id="activateRecord_d">
				<span class="underline">A</span>ctivate Record
			</button>
			<button accesskey="D" type="button" class="negative drop" name="dropRecord_d"
				tabindex="27" id="dropRecord_d">
				<span class="underline">D</span>rop Record
			</button>
			<button accesskey="H" type="button" name="submit" tabindex="29" class="regular hide"
				id="submit" onclick="hideDetailsPortion()">
				<span class="underline">H</span>ide Details Portion
			</button>
			<button accesskey="U" type="submit" name="editRecordButton" class="positive edit"
				tabindex="28" id="editRecordButton" >
				<span class="underline">U</span>pdate Record
			</button>

		</fieldset>
	</div>
</div>
<div class="clear"></div>
<div class="inputs">
	<form id="searchForm" class="searchForm"
		onsubmit="return valid.validateForm(this) ? getSearchDetails() : false;">
		<fieldset class="formelements">
			<div class="legend">Search Institute Details</div>
			<dl>
				<dt style="width: 15%">
					<label for="menu_hint">Type Hint :</label>
				</dt>
				<dd style="width: 30%">
					<input type="text" name="hint" tabindex="30" id="hint"
						class="" style="width: 200px"
						onchange="javascript: valid.validateInput(this);" />
				</dd>
				<dt style="width: 10%">
					<label for="search_type">Search Type :</label>
				</dt>
				<dd>
					<select name="search_type" tabindex="31" id="search_type"
						style="width: 150px">
						<option value="all">All Institutes</option>
						<option value="1" selected="selected">Active Institutes</option>
						<option value="0">In-Active Institutes</option>
					</select>
				</dd>
				<div id="search_typeError" class="validationError"
					style="display: none;"></div>
			</dl>
			<dl>
			</dl>
		</fieldset>
		<fieldset class="action buttons">
			<button accesskey="T" type="button" name="toggleInsert1"
				tabindex="32" id="toggleInsert1" class="regular toggle" onclick="toggleInsertForm()">
				<span class="underline">T</span>oggle Insert Form
			</button>
			<button type="reset" name="searchReset" id="searchReset" class="negative reset"
				accesskey="L">
				Reset Search Fie<span class="underline">l</span>ds
			</button>
			<button accesskey="S" type="submit" name="submitSearch" class="positive search"
				tabindex="33" id="submitSearch">
				Get <span class="underline">S</span>earch Results
			</button>
		</fieldset>
	</form>

</div>

<div class="clear"></div>
<form>
	<div class="datatable buttons" id="groupMenus_s">
		<fieldset class="tableElements">
			<div class="legend">
				<span>Institute Listing Details</span>
			</div>
			<table  class="display" id="groupRecord">
				<thead>
					<tr>
						<th>Institute Name</th>
						<th>University/Board</th>
						<th style="width: 150px">Show Details</th>
						<th style="width: 150px">Edit Details</th>
					</tr>
				</thead>
				<tbody>
				</tbody>
			</table>
		</fieldset>

	</div>
</form>
</div>
