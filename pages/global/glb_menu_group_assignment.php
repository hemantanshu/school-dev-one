<?php
require_once 'config.php';
require_once BASE_PATH . 'include/global/class.body.php';
require_once BASE_PATH . 'include/global/class.options.php';

$body = new body ();
$options = new options ();

$body->startBody ( 'global', 'LMENUL6', 'Menu Assignment Group' );
?>
<div id="content_header">
    <div id="pageButton" class="buttons">
        <button type="button" class="regular toggle" onclick="showHideSearchForm()"><span class="underline">T</span>oggle Search Form</button>
        <button type="button" class="regular toggle" onclick="showHideDatatable()"><span class="underline">T</span>oggle Tabulated Data</button>
    </div>
    <div id="contentHeader">User Group Menu Assignment Form</div>
</div>
<div class="inputs">
	<form id="insertForm" class="insertForm"
		onsubmit="return valid.validateForm(this) ? processInsertForm() : false;">
		<fieldset class="formelements">
			<div class="legend">
				<span>Add New Assignment</span>
			</div>
			<dl class="element">
				<dt style="width: 15%">
					<label for="topMenu">Top Menu :</label>
				</dt>
				<dd style="width: 80%">
					<input type="hidden" name="topMenu_val" id="topMenu_val" value="" />
					<input type="text" name="topMenu" id="topMenu"
						onblur="getTopMenuDescription()" class="required autocomplete"
						title="The Top Menu To Be Assigned" value="" size="40" onchange="javascript: valid.validateInput(this);" /> <span
						id="topMenuDescription"></span>
					<div id="topMenuError" class="validationError" style="display: none"></div>	
				</dd>

			</dl>
			<dl class="element">
				<dt style="width: 15%">

					<label for="group">Usergroup :</label>
				</dt>
				<dd style="width: 80%">
					<input type="hidden" name="group_val" id="group_val" value="" /> <input
						type="text" name="group" id="group" class="required autocomplete"
						title="The Group To Whom The Top Menu Has To be Assigned"
						value="" size="40" onchange="javascript: valid.validateInput(this);"/> <span id="userGroupAssignment"></span>
					<div id="groupError" class="validationError" style="display: none"></div>	
				</dd>


			</dl>
			<dl class="element">
				<dt style="width: 15%;">
					<label for="sDate">Start Date :</label>
				</dt>
				<dd style="width: 30%">
					<input type="text" class="required date" id="sDate" name="sDate"
						value="<?php echo $body->getCurrentDate(); ?>"
						title="The Start Date Of The Menu Assignment" onchange="javascript: valid.validateInput(this);" />
					<div id="sDateError" class="validationError" style="display: none"></div>	
				</dd>
				<dt style="width: 15%;">
					<label for="eDate">End Date :</label>
				</dt>
				<dd style="width: 30%">
					<input type="text" class="date" id="eDate" name="eDate" value=""
						title="The end date of the menu assignment" onchange="javascript: valid.validateInput(this);" />
					<div id="eDateError" class="validationError" style="display: none"></div>	
				</dd>
			</dl>

			<dl class="element">
				<dt style="width: 15%;">
					<input type="checkbox" id="edit" name="edit" value="y"
						checked="checked"
						title="Whether the menu is editable or not" />
				</dt>
				<dd>
					<label for="edit">: Enable Editing Option To The User Group</label>
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
	<form id="updateForm" class="updateForm"
		onsubmit="return valid.validateForm(this) ? processUpdateForm() : false;">
		<fieldset class="formelements">
			<div class="legend">
				<span>Add New Assignment</span>
			</div>
			<dl class="element">
				<dt style="width: 15%">
					<label for="topMenu">Top Menu :</label>
				</dt>
				<dd style="width: 80%">
					<input type="hidden" name="topMenu_u_val" id="topMenu_u_val"
						value="" /> <input type="text" name="topMenu_u" id="topMenu_u"
						onblur="getTopMenuDescription1()" class="required autocomplete"
						title="The Top Menu To Be Assigned" value="" size="40" onchange="javascript: valid.validateInput(this);" /> <span
						id="topMenuDescription_u"></span>
					<div id="topMenu_uError" class="validationError" style="display: none"></div>
				</dd>

			</dl>
			<dl class="element">
				<dt style="width: 15%">

					<label for="group">Usergroup :</label>
				</dt>
				<dd style="width: 80%">
					<input type="hidden" name="group_u_val" id="group_u_val" value="" />
					<input type="text" name="group_u" id="group_u" class="required autocomplete"
						title="The Group To Whom The Top Menu Has To be Assigned" onchange="javascript: valid.validateInput(this);"
						value="" size="40" /> <span id="userGroupAssignment"></span>
					<div id="group_uError" class="validationError" style="display: none"></div>	
				</dd>


			</dl>
			<dl class="element">
				<dt style="width: 15%;">
					<label for="sDate">Start Date :</label>
				</dt>
				<dd style="width: 30%">
					<input type="text" class="required date" id="sDate_u" name="sDate_u"
						value="<?php echo $body->getCurrentDate(); ?>"
						title="The Start Date Of The Menu Assignment" onchange="javascript: valid.validateInput(this);" />
					<div id="sDate_uError" class="validationError" style="display: none"></div>	
				</dd>
				<dt style="width: 15%;">
					<label for="eDate">End Date :</label>
				</dt>
				<dd style="width: 30%">
					<input type="text" class="date" id="eDate_u" name="eDate_u" value=""
						title="The end date of the menu assignment" onchange="javascript: valid.validateInput(this);" />
					<div id="eDate_uError" class="validationError" style="display: none"></div>	
				</dd>
			</dl>

			<dl class="element">
				<dt style="width: 15%;">
					<input type="checkbox" id="edit_u" name="edit_u" value="y"
						title="Whether the menu is editable or not" />
				</dt>
				<dd>
					<label for="edit">: Enable Editing Option To The User Group</label>
				</dd>
			</dl>
		</fieldset>
		<fieldset class="action buttons">
			<input type="hidden" name="assignmentId_u" id="assignmentId_u"
				value="" /> <input type="hidden" name="rowPosition_u"
				id="rowPosition_u" value="" />
			<button type="button" class="positive activate" name="activateMenuUrl_u"
				id="activateMenuUrl_u">Activate Menu</button>
			<button type="button" class="negative drop" name="dropMenuUrl_u"
				id="dropMenuUrl_u">Drop Menu</button>
			<button type="button" name="submit" id="submit" class="regular hide"
				onclick="hideUpdateForm()">Hide Update Portion</button>
            <button type="submit" class="positive update">Update Assignment Details</button>
		</fieldset>
	</form>
</div>
<div class="clear"></div>
<div class="display">
	<div id="displayPortion">
		<fieldset class="displayElements">
			<div class="legend">
				<span id="legendDisplayAssignment">Details Of The Assignment Group :
				</span>
			</div>
			<dl>
				<dt style="width: 15%">
					<label for="group">Assignment ID :</label>
				</dt>
				<dd style="width: 30%">
					<span id="assignmentIdDisplay"></span>
				</dd>
				<dt style="width: 15%;">
					<label for="email">User Group : </label>
				</dt>
				<dd style="width: 30%">
					<span id="usrGroupDisplay"></span>
				</dd>
			</dl>
			<dl>
				<dt style="width: 15%">
					<label for="group">Top Menu :</label>
				</dt>
				<dd style="width: 30%">
					<span id="topMenuDisplay"></span>
				</dd>
				<dt style="width: 15%;">
					<label for="email">Edit Enabled : </label>
				</dt>
				<dd style="width: 30%">
					<span id="menuEditDisplay"></span>
				</dd>
			</dl>
			<dl>
				<dt style="width: 15%;">
					<label for="email">TopMenu Description : </label>
				</dt>
				<dd style="width: 75%">
					<span id="descriptionDisplay"></span>
				</dd>
			</dl>
			<dl>
				<dt style="width: 15%;">
					<label for="email">Assignment Active Date : </label>
				</dt>
				<dd style="width: 30%">
					<span id="startDateDisplay"></span>
				</dd>
				<dt style="width: 15%">
					<label for="group">Assignment End Date :</label>
				</dt>
				<dd style="width: 30%">
					<span id="endDateDisplay"></span>
				</dd>
			</dl>
			<dl>
				<dt style="width: 15%;">
					<label for="email">Last Update Date : </label>
				</dt>
				<dd style="width: 30%">
					<span id="lastUpdateDateDisplay"></span>
				</dd>
				<dt style="width: 15%">
					<label for="group">Updated BY :</label>
				</dt>
				<dd style="width: 30%">
					<span id="lastUpdatedByDisplay"></span>
				</dd>
			</dl>
			<dl>
				<dt style="width: 15%;">
					<label for="email">Creation Date : </label>
				</dt>
				<dd style="width: 30%">
					<span id="creationDateDisplay"></span>
				</dd>
				<dt style="width: 15%">
					<label for="group">Created BY :</label>
				</dt>
				<dd style="width: 30%">
					<span id="createdByDisplay"></span>
				</dd>
			</dl>
			<dl>
				<dt style="width: 15%">
					<label for="group">Active/Inactive :</label>
				</dt>
				<dd style="width: 30%">
					<span id="activeDisplay"></span>
				</dd>
			</dl>


		</fieldset>
		<fieldset class="action buttons">
			<input type="hidden" name="assignmentId_d" id="assignmentId_d"
				value="" /> <input type="hidden" name="rowPosition_d"
				id="rowPosition_d" value="" />
			<button type="button" class="positive activate" name="activateMenuUrl_d"
				id="activateMenuUrl_d">Activate Assignment</button>
			<button type="button" class="negative drop" name="dropMenuUrl_d"
				id="dropMenuUrl_d">Drop Assignment</button>
			<button type="button" name="submit" class="regular hide" id="submit"
				onclick="hideDisplayPortion()">Hide Display Details Portion</button>
            <button type="button" class="positive edit" id="update_menu_button">Update
                Assignment Details</button>
		</fieldset>
	</div>
</div>
<div class="clear"></div>
<div class="inputs">
	<form id="searchForm" class="searchForm"
		onsubmit="return valid.validateForm(this) ? getGroupAssignedMenus() : false;">
		<fieldset class="formelements">
			<div class="legend">Get User Group Assigned Menus</div>
			<dl>
				<dt style="width: 15%">
					<label for="menu_hint">Search UserGroup :</label>
				</dt>
				<dd style="width: 30%">
					<input type="hidden" name="menu_hint_val" id="menu_hint_val" /> <input
						type="text" name="menu_hint" id="menu_hint" style="width: 200px" class="autocomplete"
						title="Select The User Group" />
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
			<button type="button" name="toggleInsert1" class="regular toggle" id="toggleInsert1"
				onclick="toggleInsertForm()">Toggle Insert Form</button>
			<button type="submit" name="search" id="search" class="positive search">Get Search Results</button>
		</fieldset>
	</form>
</div>

<div class="clear"></div>


<div id="displayDatatable" class="datatable buttons">
	<fieldset class="tableElements">
		<div class="legend">
			<span>User Group Assigned Menus</span>
		</div>
		<table  class="display"
			id="groupMenus">
			<thead>
				<tr>
					<th>Top Menu</th>
					<th>Start Date</th>
					<th>End Date</th>
					<th style="width: 145px;">Show Details</th>
					<th style="width: 125px;">Edit Details</th>

				</tr>
			</thead>
			<tbody>

			</tbody>
		</table>
	</fieldset>
</div>
<?php
$body->endBody ( 'global', 'MENUL6' );
?>
