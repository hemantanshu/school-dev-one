<?php
require_once 'config.php';
require_once BASE_PATH.'include/global/class.body.php';
require_once BASE_PATH.'include/global/class.options.php';

$body = new body ();
$options = new options ();

$body->startBody ('global', 'LMENUL8', 'New Option Flag' );
?>
<div id="content_header">
    <div id="pageButton" class="buttons">
        <button type="button" class="regular toggle" onclick="showHideSearchForm()"><span class="underline">T</span>oggle Search Form</button>
        <button type="button" class="regular toggle" onclick="showHideDatatable()"><span class="underline">T</span>oggle Tabulated Data</button>
    </div>
    <div id="contentHeader">New Option Record Entry Form</div>
</div>
<div class="clear"></div>
<div class="inputs">
	<form id="insertForm" class="insertForm" onsubmit="return valid.validateForm(this) ? processInsertForm() : false;">
		<fieldset class="formelements">
			<div class="legend">
				<span>Set New Option Type </span>
			</div>
			<dl class="element">
				<dt style="width: 15%">
					<label for="optionName">Option Name :</label>
				</dt>
				<dd style="width: 30%">
					<input type="text" name="optionName" id="optionName"
						class="required" title="Enter The New Option" value=""
						size="30" onchange="javascript: valid.validateInput(this);"/>
					<div id="optionNameError" class="validationError" style="display: none"></div>	
				</dd>
				<dt style="width: 15%">
					<label for="shortCode">Option ShortCode :</label>
				</dt>
				<dd style="width: 30%">

					<input type="text" name="shortCode" id="shortCode"
						onblur="checkShortCode()" class="required" onchange="javascript: valid.validateInput(this);"
						title="Enter The New Option ShortCode" value="" size="20" />
					<div id="shortCodeError" class="validationError" style="display: none"></div>	
				</dd>
			</dl>
			<dl class="element">
				<dt style="width: 15%">

					<label for="description">Description :</label>
				</dt>
				<dd style="width: 30%">

					<textarea name="sMenuDescription"
                                              id="sMenuDescription" rows="4" cols="50" class="required" onchange="javascript: valid.validateInput(this);"></textarea>
					</textarea>
					<div id="aMenuDescriptionError" class="validationError" style="display: none"></div>
				</dd>

			</dl>
		</fieldset>

		<fieldset class="action buttons">
			<button type="button" name="submit" class="regular hide" onclick="hideInsertForm()"
				accesskey="H">
				<span class="underline">H</span>ide Insert Form
			</button>

			<button type="reset" name="insertReset" id="insertReset" class="negative reset"
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
	<form id="updateForm" class="updateForm" onsubmit="return valid.validateForm(this) ? processUpdateForm() : false;">
		<fieldset class="formelements">
			<div class="legend">
				<span>Update Option Record Details </span>
			</div>
			<dl class="element">
				<dt style="width: 15%">
					<label for="optionName">Option Name :</label>
				</dt>
				<dd style="width: 30%">

					<input type="text" name="optionName_u" id="optionName_u"
						class="required" title="Enter The New Option" value="" onchange="javascript: valid.validateInput(this);"
						size="30" />
					<div id="optionName_uError" class="validationError" style="display: none"></div>	
				</dd>
				<dt style="width: 15%">

					<label for="shortCode">Option ShortCode :</label>
				</dt>
				<dd style="width: 30%">

					<input type="text" name="shortCode_u" id="shortCode_u"
						disabled="disabled" title="Enter The New Option ShortCode" onchange="javascript: valid.validateInput(this);"
						value="" size="20" />
					<div id="shortCode_uError" class="validationError" style="display: none"></div>	
				</dd>
			</dl>
			<dl class="element">
				<dt style="width: 15%">

					<label for="description">Description :</label>
				</dt>
				<dd style="width: 30%">

					<textarea name="sMenuDescription_u"
                                              id="sMenuDescription_u" rows="4" cols="50" class="required" onchange="javascript: valid.validateInput(this);"></textarea>
					</textarea>
					<div id="sMenuDescription_uError" class="validationError" style="display: none"></div>
				</dd>

			</dl>
		</fieldset>

		<fieldset class="action buttons">
			<input type="hidden" name="valueId_u" id="valueId_u" value="" /> <input
				type="hidden" name="rowPosition_u" id="rowPosition_u" value="" />
			<button type="button" class="positive activate" name="activateMenuUrl_u"
				id="activateMenuUrl_u">Activate Option</button>
			<button type="button" class="negative drop" name="dropMenuUrl_u"
				id="dropMenuUrl_u">Drop Option</button>
			<button type="button" name="submit" class="regular hide" id="submit"
				onclick="hideUpdateForm()">Hide Update Portion</button>
            <button type="submit" class="positive update" >Update
                Option Type</button>
		</fieldset>
	</form>
</div>

<div class="clear"></div>
<div class="display">
<div id="displayPortion">
	<fieldset class="displayElements">
		<div class="legend">
			<span id="legendDisplayOption">Option Type Details : </span>
		</div>
		<dl>
			<dt style="width: 15%;">
				<label for="shortCode_d">ShortCode : </label>
			</dt>
			<dd style="width: 30%">
				<span id="shortCode_dDisplay"></span>
			</dd>
			<dt style="width: 15%">
				<label for="optionName_d">Option Name :</label>
			</dt>
			<dd style="width: 30%">
				<span id="optionName_dDisplay"></span>
			</dd>
		</dl>
		<dl>
			<dt style="width: 15%;">
				<label for="description_d">Description : </label>
			</dt>
			<dd style="width: 80%">
				<span id="description_dDisplay"></span>
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
				<label for="lastUpdatedBy">Updated By :</label>
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
				<label for="createdBy">Created By :</label>
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
		<input type="hidden" name="valueId_d" id="valueId_d" value="" /> <input
			type="hidden" name="rowPosition_d" id="rowPosition_d" value="" />
		<button type="button" class="positive activate" name="activateMenuUrl_d"
			id="activateMenuUrl_d">Activate Option</button>
		<button type="button" class="negative drop" name="dropMenuUrl_d"
			id="dropMenuUrl_d">Drop Option</button>
		<button type="button" name="submit" class="regular hide" id="submit"
			onclick="hideDisplayPortion()">Hide Display Details Portion</button>
        <button type="button" class="positive edit" id="update_menu_button">Update
            Option Type</button>
	</fieldset>
</div>
	
</div>
<div class="clear"></div>
<div class="inputs">
	<form id="searchForm" class="searchForm" onsubmit="return valid.validateForm(this) ? getOptionSearchResults() : false;">
		<fieldset class="formelements">
			<div class="legend">Search Option Type Records</div>
			<dl>
				<dt style="width: 15%">
					<label for="menu_hint">Search Option Type :</label>
				</dt>
				<dd style="width: 30%">
					<input type="text" name="menu_hint" id="menu_hint" class=""
						style="width: 200px" title="Enter The Submenu Hint" />
				</dd>
				<dt style="width: 15%">
					<label for="search_type">Search Type :</label>
				</dt>
				<dd>
					<select name="search_type" id="search_type" style="width: 150px">
						<option value="all">All Options</option>
						<option value="1" selected="selected">Active Options</option>
						<option value="0">In-Active Options</option>
					</select>
				</dd>
			</dl>
		</fieldset>

		<fieldset class="action buttons">
			<button type="button" name="toggleInsert1" id="toggleInsert1" class="regular toggle"
				onclick="toggleInsertForm()">Toggle Insert Form</button>
			<button type="submit" name="toggleInsert1" id="toggleInsert1" class="positive search">Get Search Results</button>
		</fieldset>
	</form>
</div>

<div class="clear"></div>


<div id="displayDatatable" class="datatable buttons">
	<fieldset>
		<div class="legend">
			<span>Option Record Listing</span>
		</div>
		<table  class="display"
			id="groupMenus">
			<thead>
				<tr>
					<th style="width: 150px">Short Code</th>
					<th>Option Name</th>
					<th style="width: 150px">Values</th>
					<th style="width: 150px">View Details</th>
					<th style="width: 150px">Edit Details</th>

				</tr>
			</thead>
			<tbody>

			</tbody>
		</table>
	</fieldset>
</div>