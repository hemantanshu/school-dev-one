<?php
require_once 'config.php';
require_once BASE_PATH . 'include/global/class.body.php';
$body = new body ();

$body->startBody ( 'global', 'LMENUL4', 'Menu Top Entry Page' );
?>
<div id="content_header">
    <div id="pageButton" class="buttons">
        <button type="button" class="regular toggle" onclick="showHideSearchForm()"><span class="underline">T</span>oggle Search Form</button>
        <button type="button" class="regular toggle" onclick="showHideDatatable()"><span class="underline">T</span>oggle Tabulated Data</button>
    </div>
    <div id="contentHeader">Top Menu Record Entry page </div>
</div>
<div class="inputs">
	<form id="insertForm" name="insertForm" class="insertForm"
		onsubmit="return valid.validateForm(this) ? processInsertForm() : false;">
		<fieldset class="formelements">
			<div class="legend">Insertion Of New Top Menu</div>
			<dl class="element">
				<dt style="width: 15%;">
					<label for="menuName">Menu Name:</label>
				</dt>
				<dd style="width: 30%">
					<input type="text" class="required" id="menuName" name="menuName"
						onblur="checkMenuName('menuName', 0)" value="" size="30"
						title="Enter The Menu Name"
						onchange="javascript: valid.validateInput(this);" />
					<div id="menuNameError" class="validationError"
						style="display: none"></div>
				</dd>
				<dt style="width: 15%">

					<label for="menuUrl">Menu URL :</label>
				</dt>
				<dd style="width: 30%">
					<input type="hidden" name="menuUrl_val" id="menuUrl_val" value="" />
					<input type="text" name="menuUrl" id="menuUrl" class="required"
						title="Enter The Menu URL" value="" size="30"
						onchange="javascript: valid.validateInput(this);" />
					<div id="menuUrlError" class="validationError"
						style="display: none"></div>
				</dd>
			</dl>
			<dl class="element">
				<dt style="width: 15%;">
					<label for="menuDescription">Menu Description :</label>
				</dt>
				<dd style="width: 80%">
					<textarea rows="3" cols="60" name="menuDescription"
						id="menuDescription" class="required"
						onchange="javascript: valid.validateInput(this);"></textarea>
					<div id="menuDescriptionError" class="validationError"
						style="display: none"></div>
				</dd>
			</dl>
			<dl class="element">

				<dt style="width: 15%">

					<label for="Submenu"> Submenu :</label>
				</dt>
				<dd style="width: 30%">
					<input type="hidden" name="submenu_val" id="submenu_val" value="" />
					<input type="text" name="submenu" id="submenu"
						title="Enter The Submenu Name" value="" size="30"
						onchange="javascript: valid.validateInput(this);" />
					<div id="submenuError" class="validationError"
						style="display: none"></div>
				</dd>
				<dt style="width: 15%">

					<label for="redirect">Redirect :</label>
				</dt>
				<dd style="width: 30%">
					<select size="1" name="redirect" id="redirect" class="required"
						title="Select The Redirect " style="width: 200px"
						onchange="javascript: valid.validateInput(this);">
						<option value="n">Parent</option>
						<option value="y">Blank</option>

					</select>
					<div id="redirectError" class="validationError"
						style="display: none"></div>
				</dd>
			</dl>
			<dl class="element">
				<dt style="width: 15%">
					<label for="priority"> Priority:</label>
				</dt>
				<dd style="width: 30%">
					<input type="text" name="priority" id="priority"
						title="[NUMERIC VALUE ONLY]Enter The Priority " class="required numeric"
						value="" size="10"
						onchange="javascript: valid.validateInput(this);" />
					<div id="priorityError" class="validationError"
						style="display: none"></div>
				</dd>
				<dt style="width: 15%">

					<label for="authentication">Authentication :</label>
				</dt>
				<dd style="width: 30%">
					<select size="1" name="authentication" id="authentication" class="required"
						title="Enter The Authentication" style="width: 200px"
						onchange="javascript: valid.validateInput(this);">
						<option value="y">Enable</option>
						<option value="n">Disable</option>
					</select>
					<div id="authenticationError" class="validationError"
						style="display: none"></div>
				</dd>
			</dl>
		</fieldset>
		<fieldset class="action buttons">
			<button type="reset" name="submit" id="resetInsert" class="negative reset">Reset Form Fields</button>
			<button type="button" name="submit" onclick="hideInsertForm()" class="regular hide">Hide
				Insert Form</button>
			<button type="submit" name="submit" id="addsmenu" class="positive insert">Insert New Menu</button>

		</fieldset>
	</form>
</div>
<div class="clear"></div>
<div class="inputs">
	<form id="updateForm" name="updateForm" class="updateForm"
		onsubmit="return valid.validateForm(this) ? processTopMenuUpdate() : false;">
		<fieldset class="formelements">
			<div class="legend">Update Top Menu Record</div>
			<dl class="element">
				<dt style="width: 15%;">
					<label for="menuName_u">Menu Name:</label>
				</dt>
				<dd style="width: 30%">
					<input type="text" class="required" id="menuName_u"
						name="menuName_u" value="" onblur="checkMenuName('menuName_u', 1)"
						size="40" title="Enter The Menu Name"
						onchange="javascript: valid.validateInput(this);" />
					<div id="menuName_uError" class="validationError"
						style="display: none"></div>
				</dd>
				<dt style="width: 15%">

					<label for="menuUrl_u"> Menu URL :</label>
				</dt>
				<dd style="width: 30%">
					<input type="hidden" name="menuUrl_u_val" id="menuUrl_u_val"
						value="" /> <input type="text" name="menuUrl_u" id="menuUrl_u"
						class="required" title="Enter The Menu Url" value=""
						size="40" onchange="javascript: valid.validateInput(this);" />
					<div id="menuUrl_uError" class="validationError"
						style="display: none"></div>
				</dd>
			</dl>
			<dl class="element">
				<dt style="width: 15%;">
					<label for="menuDescription_u">Menu Description :</label>
				</dt>
				<dd style="width: 80%">
					<textarea rows="3" cols="60" name="menuDescription_u"
						id="menuDescription_u" class="required"
						onchange="javascript: valid.validateInput(this);"></textarea>
					<div id="menuDescription_uError" class="validationError"
						style="display: none"></div>
				</dd>
			</dl>
			<dl class="element">

				<dt style="width: 15%">

					<label for="Submenu_u"> Submenu :</label>
				</dt>
				<dd style="width: 30%">
					<input type="hidden" name="submenu_u_val" id="submenu_u_val"
						value="" /> <input type="text" name="submenu_u" id="submenu_u"
						title="Enter The Submenu Name" size="40"
						onchange="javascript: valid.validateInput(this);" />
					<div id="submenu_uError" class="validationError"
						style="display: none"></div>
				</dd>
				<dt style="width: 15%">

					<label for="redirect_u">Redirect :</label>
				</dt>
				<dd style="width: 30%">
					<select size="1" name="redirect_u" id="redirect_u" class="requried"
						title="Enter The Redirect" style="width: 200px"
						onchange="javascript: valid.validateInput(this);">
						<option value="n">Parent</option>
						<option value="y">Blank</option>
					</select>
					<div id="redirect_uError" class="validationError"
						style="display: none"></div>
				</dd>
			</dl>
			<dl class="element">


				<dt style="width: 15%">

					<label for="priority_u"> Priority:</label>
				</dt>
				<dd style="width: 30%">
					<input type="text" name="priority_u" id="priority_u" class="required numeric"
						title="Enter The Priority" value="" size="15"
						onchange="javascript: valid.validateInput(this);" />
					<div id="priority_uError" class="validationError"
						style="display: none"></div>
				</dd>
				<dt style="width: 15%">

					<label for="authentication_u">Authentication :</label>
				</dt>
				<dd style="width: 30%">
					<select size="1" name="authentication_u" id="authentication_u"
						class="required" title="Enter The Authentication"
						onchange="javascript: valid.validateInput(this);"
						style="width: 200px">
						<option value="y">Enable</option>
						<option value="n">Disable</option>
					</select>
					<div id="authentication_uError" class="validationError"
						style="display: none"></div>
				</dd>
			</dl>
			<dl>
				<dd height="30px"></dd>
			</dl>
		</fieldset>
		<fieldset class="action buttons">
			<input type="hidden" name="menuName_ui" id="menuName_ui" value="" />
			<input type="hidden" name="menuId_u" id="menuId_u" value="" /> <input
				type="hidden" name="position_u" id="position_u" value="" />
			<button type="button" class="positive activate" name="activateMenuUrl_u"
				id="activateMenuUrl_u">Activate Top Menu</button>
			<button type="button" class="negative drop" name="dropMenuUrl_u"
				id="dropMenuUrl_u">Drop Top Menu</button>
			<button type="button" name="submit" id="submit" class="regular hide"
				onclick="hideUpdateForm()">Hide Update Portion</button>
			<button type="submit" class="positive update">Update Top Menu Details</button>
		</fieldset>
	</form>
</div>

<div class="clear"></div>
<div class="display">
	<div id="displaySubmenu">
		<fieldset class="displayElements">
			<div class="legend">
				<span id="displaySubmenuLegend">Top Menu Record Details</span>
			</div>
			<dl>
				<dt style="width: 15%;">
					<label for="topmenuId">Top Menu ID : </label>
				</dt>
				<dd style="width: 33%">
					<span id="topMenuIdDisplay"></span>
				</dd>
				<dt style="width: 15%">
					<label for="menuName">Menu Name :</label>
				</dt>
				<dd style="width: 33%">
					<span id="menuNameDisplay"></span>
				</dd>
			</dl>
			<dl>
				<dt style="width: 15%;">
					<label for="menuDescritpion">Description : </label>
				</dt>
				<dd style="width: 75%">
					<span id="descriptionDisplay"></span>
				</dd>
			</dl>
			<dl>
				<dt style="width: 15%;">
					<label for="urlName">URL Name : </label>
				</dt>
				<dd style="width: 33%">
					<span id="urlNameDisplay"></span>
				</dd>
				<dt style="width: 15%">
					<label for="url">URL :</label>
				</dt>
				<dd style="width: 33%">
					<span id="urlDisplay"></span>
				</dd>
			</dl>
			<dl>
				<dt style="width: 15%;">
					<label for="submenuName">Submenu Name : </label>
				</dt>
				<dd style="width: 33%">
					<span id="submenuNameDisplay"></span>
				</dd>
				<dt style="width: 15%">
					<label for="priority">Priority :</label>
				</dt>
				<dd style="width: 33%">
					<span id="priorityDisplay"></span>
				</dd>
			</dl>
			<dl>
				<dt style="width: 15%;">
					<label for="redirect">Redirect : </label>
				</dt>
				<dd style="width: 33%">
					<span id="redirectDisplay"></span>
				</dd>
				<dt style="width: 15%">
					<label for="authentication">Authentication :</label>
				</dt>
				<dd style="width: 33%">
					<span id="authenticationDisplay"></span>
				</dd>
			</dl>
			<dl>
				<dt style="width: 15%;">
					<label for="lastUpdateDate">Last Update Date : </label>
				</dt>
				<dd style="width: 33%">
					<span id="lastUpdateDateDisplay"></span>
				</dd>
				<dt style="width: 15%">
					<label for="lastUpdatedBy ">Last Updated By :</label>
				</dt>
				<dd style="width: 33%">
					<span id="lastUpdatedByDisplay"></span>
				</dd>
			</dl>
			<dl>
				<dt style="width: 15%;">
					<label for="creationDate">Creation Date : </label>
				</dt>
				<dd style="width: 33%">
					<span id="creationDateDisplay"></span>
				</dd>
				<dt style="width: 15%">
					<label for="createdBy">Created By :</label>
				</dt>
				<dd style="width: 33%">
					<span id="createdByDisplay"></span>
				</dd>
			</dl>
			<dl>
				<dt style="width: 15%;">
					<label for="active">Active/Inactive : </label>
				</dt>
				<dd style="width: 33%">
					<span id="activeDisplay"></span>
				</dd>

			</dl>

		</fieldset>
		<fieldset class="action buttons">
			<input type="hidden" name="menu_id_d" id="menu_id_d" value="" /> <input
				type="hidden" name="position_d" id="position_d" value="" />
			<button type="button" class="positive activate" name="activateMenuUrl_d"
				id="activateMenuUrl_d">Activate Top Menu</button>
			<button type="button" class="negative drop" name="dropMenuUrl_d"
				id="dropMenuUrl_d">Drop Top Menu</button>
			<button type="button" class="positive edit" name="update_menu_button"
				id="update_menu_button">Update Top Menu</button>
			<button type="button" name="submit" class="regular hide" id="submit"
				onclick="hideDisplayPortion()">Hide Display Details Portion</button>
		</fieldset>
	</div>

</div>

<div class="clear"></div>
<div class="inputs">
	<form id="searchForm" class="searchForm"
		onsubmit="return valid.validateForm(this) ? getMenuTopSearchDetails() : false;">
		<fieldset class="formelements">
			<div class="legend">Search Top Menu </div>
			<dl>
				<dt style="width: 15%">
					<label for="menu_hint">Topmenu Name :</label>
				</dt>
				<dd style="width: 30%">
					<input type="text" name="menu_hint" id="menu_hint" class=""
						style="width: 200px" title="Enter The Top Menu Name" />
				</dd>
				<dt style="width: 15%">
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
			<button type="button" name="toggleInsert1" id="toggleInsert1"
				onclick="toggleInsertForm()" class="regular toggle">Toggle Insert Form</button>
			<button type="submit" name="toggleInsert1" id="toggleInsert1" class="positive search">Get
				Search Results</button>
		</fieldset>
	</form>
</div>
<div class="clear"></div>
<div id="displayDatatable" class="buttons">
	<form>
		<div class="datatable" id="groupMenusM">
			<fieldset class="tableElements">
				<div class="legend">
					<span>Top Menu Record Listing</span>
				</div>
				<table class="display" id="groupMenus">
					<thead>
						<tr>
							<th>Top Menu</th>
							<th>Menu Name</th>
							<th>Submenu</th>
							<th>Priority</th>
							<th>View Details</th>
							<th>Edit Details</th>
						</tr>
					</thead>
					<tbody>

					</tbody>
				</table>
			</fieldset>

		</div>
	</form>
</div>