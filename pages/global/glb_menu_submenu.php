<?php
require_once 'config.php';
require_once BASE_PATH . 'include/global/class.body.php';
$body = new body ();

$body->startBody ( 'global', 'LMENUL3', 'Menu Submenu Entry Page' );
?>

<div id="content_header">
    <div id="pageButton" class="buttons">
        <button type="button" class="regular toggle" onclick="showHideSearchForm()"><span class="underline">T</span>oggle Search Form</button>
        <button type="button" class="regular toggle" onclick="showHideDatatable()"><span class="underline">T</span>oggle Tabulated Data</button>
    </div>
    <div id="contentHeader">New Submenu Record Entry Form</div>
</div>
<div class="clear"></div>
<div class="inputs">
	<form id="insertForm" name="insertForm" class="insertForm"
		onsubmit="return valid.validateForm(this) ? processInsertForm() : false;">
		<fieldset class="formelements">
			<div class="legend">Addition of New Submenu Record</div>
			<dl class="element">
				<dt style="width: 15%;">
					<label for="sMenuName">Submenu Name :</label>
				</dt>
				<dd style="width: 50%">
					<input type="text" name="sMenuName" id="sMenuName" size="40"
						class="required" onblur="checkMenuName('sMenuName', 0)"
						title="Write New Submenu Name"
						onchange="javascript: valid.validateInput(this);" />
					<div id="sMenuNameError" class="validationError"
						style="display: none"></div>
				</dd>
			</dl>
			<dl class="element">
				<dt style="width: 15%;">
					<label for="sMenuDescription">Description :</label>
				</dt>
				<dd style="width: 50%">
					<textarea name="sMenuDescription" id="sMenuDescription" rows="4"
						cols="50" class="required"
						onchange="javascript: valid.validateInput(this);"></textarea>
					<div id="sMenuDescriptionError" class="validationError"
						style="display: none"></div>
				</dd>
			</dl>

		</fieldset>
		<fieldset class="action buttons" >
			<button type="button" name="showHide" class="regular hide" onclick="hideInsertForm()"
				accesskey="H">
				<span class="underline">H</span>ide Insert Form
			</button>

			<button type="reset" name="insertReset" id="insertReset" class="negative reset"
				accesskey="R">
				<span class="underline">R</span>eset Form Fields
			</button>

			<button type="submit" name="submit" id="submit" class="positive insert" accesskey="I">
				<span class="underline">I</span>nsert New Record
			</button>
		</fieldset>
	</form>
</div>
<div class="clear"></div>
<div class="inputs">
	<form id="updateForm" name="updateSmenu" class="updateForm"
		onsubmit="return valid.validateForm(this) ? processSubmenuUpdate() : false;">
		<fieldset class="formelements">
			<div class="legend">Update Submenu Record Entry</div>
			<dl class="element">
				<dt style="width: 15%;">
					<label for="sMenuName">New Submenu Name:</label>
				</dt>
				<dd style="width: 35%">
					<input type="text" name="sMenuName_u" id="sMenuName_u" size="25"
						class="required" onblur="checkMenuName('sMenuName_u', 1)"
						title="Write Update Submenu Name"
						onchange="javascript: valid.validateInput(this);" />
					<div id="sMenuName_uError" class="validationError"
						style="display: none"></div>
				</dd>
			</dl>
			<dl class="element">
				<dt style="width: 15%;">
					<label for="sMenuDescription">Description :</label>
				</dt>
				<dd style="width: 35%">
					<textarea name="sMenuDescription_u" id="sMenuDescription_u"
						rows="4" cols="50" class="required"
						onchange="javascript: valid.validateInput(this);"></textarea>
					<div id="sMenuDescriptionError" class="validationError"
						style="display: none"></div>
				</dd>
			</dl>

		</fieldset>
		<fieldset class="action buttons">
			<input type="hidden" name="sMenuName_ui" id="sMenuName_ui" value="" />
			<input type="hidden" name="menuId_u" id="menuId_u" value="" /> <input
				type="hidden" name="position_u" id="position_u" value="" />
			<button type="button" class="positive activate" name="activateMenuUrl_u"
				id="activateMenuUrl_u">Activate Submenu</button>
			<button type="button" class="negative drop" name="dropMenuUrl_u"
				id="dropMenuUrl_u">Drop Submenu</button>
			<button type="submit" class="positive update">Update Submenu</button>
			<button type="button" name="submit" class="regular hide" id="submit"
				onclick="hideUpdateForm()">Hide Update Portion</button>
		</fieldset>
	</form>
</div>
<div class="clear"></div>

<div class="display">
	<div id="displaySubmenu">
		<fieldset class="displayElements">
			<div class="legend">
				<span id="displaySubmenuLegend">Submenu Record Entry Details</span>
			</div>
			<dl>
				<dt style="width: 15%">
					<label for="submenuId">Internal Submenu Id :</label>
				</dt>
				<dd style="width: 25%">
					<span id="submenuIdDisplay"></span>
				</dd>
				<dt style="width: 15%;">
					<label for="sMenuName">Submenu Name : </label>
				</dt>
				<dd style="width: 25%">
					<span id="sMenuNameDisplay"></span>
				</dd>
			</dl>
			<dl>
				<dt style="width: 15%;">
					<label for="submenuDescription">Submenu Description: </label>
				</dt>
				<dd style="width: 80%">
					<span id="submenuDescription"></span>
				</dd>
			</dl>
			<dl>
				<dt style="width: 15%">
					<label for="last_updated_by ">Last Updated By :</label>
				</dt>
				<dd style="width: 25%">
					<span id="lastUpdatedByDisplay"></span>
				</dd>
				<dt style="width: 15%;">
					<label for="last_update_date">Last Update Date : </label>
				</dt>
				<dd style="width: 25%">
					<span id="lastUpdateDateDisplay"></span>
				</dd>
			</dl>
			<dl>
				<dt style="width: 15%">
					<label for="created_by">Created By :</label>
				</dt>
				<dd style="width: 25%">
					<span id="createdByDisplay"></span>
				</dd>
				<dt style="width: 15%;">
					<label for="creation_date">Creation Date : </label>
				</dt>
				<dd style="width: 25%">
					<span id="creationDateDisplay"></span>
				</dd>
			</dl>
			<dl>
				<dt style="width: 15%;">
					<label for="active">Active/ Inactive : </label>
				</dt>
				<dd style="width: 25%">
					<span id="activeDisplay"></span>
				</dd>
			</dl>

		</fieldset>

		<div class="clear"></div>
		<fieldset class="action buttons">
			<input type="hidden" name="menu_id_d" id="menu_id_d" value="" /> <input
				type="hidden" name="position_d" id="position_d" value="" />
			<button type="button" class="positive activate" name="activateMenuUrl_d"
				id="activateMenuUrl_d">Activate Submenu</button>
			<button type="button" class="negative drop" name="dropMenuUrl_d"
				id="dropMenuUrl_d">Drop Submenu</button>
			<button type="button" name="showHide" class="regular hide" id="submit"
				onclick="hideDisplayPortion()">Hide Display Details</button>
            <button type="button" class="regular edit" name="update_menu_button"
                    id="update_menu_button">Update Submenu</button>
		</fieldset>
	</div>
</div>

<div class="clear"></div>
<div class="inputs">
	<form id="searchForm" class="searchForm" onsubmit="return valid.validateForm(this) ? getMenuUrlSearchDetails() : false;">
		<fieldset class="tableElement">
			<div class="legend">Search Submenu</div>
			<dl>
				<dt style="width: 15%">
					<label for="menu_hint">Submenu Name :</label>
				</dt>
				<dd style="width: 30%">
					<input type="text" name="menu_hint" id="menu_hint" class=""
						style="width: 200px" />
				</dd>
				<dt style="width: 10%">
					<label for="search_type">Search Type :</label>
				</dt>
				<dd>
					<select name="search_type" id="search_type" style="width: 150px">
						<option value="all">All Menus</option>
						<option value="1" selected="selected">Active Menus</option>
						<option value="0">In-Active Menus</option>
					</select>
				</dd>
			</dl>
		</fieldset>
		<fieldset class="action buttons">
			<button type="button" name="toggleInsert1" class="regular toggle" id="toggleInsert1"
				onclick="showHideInsertForm()">Toggle Insert Form</button>
			<button type="submit" name="toggleInsert1" class="positive search" id="toggleInsert1">Get
				Search Results</button>
		</fieldset>
	</form>
</div>
<div class="clear"></div>

<div id="displayDatatable" class="buttons">
	<form>
		<div class="datatable" id="groupMenusM" >
			<fieldset>
				<div class="legend">
					<span>Submenu Lists</span>
				</div>
				<table class="display" id="groupMenus">
					<thead>
						<tr>
							<th style="width: 20%;">Submenu Name</th>
							<th>Description</th>
							<th style="width: 165px;">URL Associated</th>
							<th style="width: 150px;">Show Details</th>
							<th style="width: 125px;">Edit Details</th>
						</tr>
					</thead>
					<tbody>

					</tbody>
				</table>
			</fieldset>

		</div>
	</form>
</div>
