<?php
require_once 'config.php';
require_once BASE_PATH . 'include/global/class.body.php';
require_once BASE_PATH . 'include/global/class.menu.php';
$body = new body ();
$menu = new menu ();

$submenuId = $_GET ['submenuId'];
$details = $menu->getMenuSubmenuIdDetails ( $submenuId );
if ($details ['submenu_name'] == '')
	exit(0);
$body->startBody ( 'global', 'LMENUL5', 'Menu Submenu URL Entry Page' );
?>
<div class="clear"></div>
<div id="content_header">
    <div id="pageButton" class="buttons">
        <button type="button" class="regular toggle" onclick="showHideSearchForm()"><span class="underline">T</span>oggle Search Form</button>
        <button type="button" class="regular toggle" onclick="showHideDatatable()"><span class="underline">T</span>oggle Tabulated Data</button>
    </div>
    <div id="contentHeader">Submenu : <?php echo strtoupper($details[1]); ?> URL Record Entry </div>
</div>
<input type="hidden" name="submenuId_glb" id="submenuId_glb" value="<?php echo $submenuId; ?>" />
<div class="clear"></div>
<div class="inputs">
	<form id="insertForm" name="insertForm" class="insertForm" onsubmit="return valid.validateForm(this) ? processInsertForm() : false;">
		<fieldset class="formelements">
			<div class="legend">Insertion Of New Menu URL</div>
			<dl class="element">
				<dt style="width: 15%;">
					<label for="menuName">Menu Name:</label>
				</dt>
				<dd style="width: 30%">
					<input type="text" class="required" id="menuName" name="menuName"
						value="" size="40"
						title="Enter the Name you want to be displayed" onchange="javascript: valid.validateInput(this);" />
					<div id="menuNameError" class="validationError" style="display: none"></div>		
				</dd>
				<dt style="width: 15%">

					<label for="menuUrl"> Menu URL :</label>
				</dt>
				<dd style="width: 30%">
					<input type="hidden" name="menuUrl_i" id="menuUrl_i" /> <input
						type="text" name="menuUrl" id="menuUrl" class="required" value=""
						size="40" title="The Menu URL Assigned To This Menu" onchange="javascript: valid.validateInput(this);" />
					<div id="menuUrlError" class="validationError" style="display: none"></div>	
				</dd>
			</dl>
			<dl class="element">
				<dt style="width: 15%">

					<label for="redirect">Menu Redirect :</label>
				</dt>
				<dd style="width: 30%">
					<select size="1" name="redirect" id="redirect" class="required"
						style="width: 200px" onchange="javascript: valid.validateInput(this);">
						<option value="n">Parent</option>
						<option value="y">Blank</option>

					</select>
					<div id="redirectError" class="validationError" style="display: none"></div>
				</dd>
				<dt style="width: 15%">

					<label for="menuPriority"> Menu Priority :</label>
				</dt>
				<dd style="width: 30%">
					<input type="text" name="menuPriority" id="menuPriority" class="required numeric"
						value="" size="10"
						title="[Numeric Value] Order Of Display Of The Menu In The Menu" onchange="javascript: valid.validateInput(this);" />
					<div id="menuPriorityError" class="validationError" style="display: none"></div>	
				</dd>

			</dl>
			<dl class="element">
				<dt style="width: 15%">
					<label for="cSubmenu"> Child Submenu:</label>
				</dt>
				<dd style="width: 30%">
					<input type="hidden" name="cSubmenu_i" id="cSubmenu_i" /> <input
						type="text" name="cSubmenu" id="cSubmenu"
						onblur="checkChildMenuAssignmentInsert()" size="40"
						title="The Submenu Assigned To This Menu" onchange="javascript: valid.validateInput(this);" />
				</dd>

			</dl>

		</fieldset>
		<fieldset class="action buttons">
			<button type="button" name="submit" onclick="hideInsertForm()" class="regular hide"	accesskey="H">
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
	<form id="updateForm" name="updateForm" class="updateForm" onsubmit="return valid.validateForm(this) ? processSubmenuURLUpdate() : false;">
		<fieldset class="formelements">
			<div class="legend">Updation Of New Menu URL</div>
			<dl class="element">
				<dt style="width: 15%;">
					<label for="menuName_u">Menu Name:</label>
				</dt>
				<dd style="width: 30%">
					<input type="text" class="required" id="menuName_u"
						name="menuName_u" value="" size="40"
						title="Enter the Name you want to be displayed" onchange="javascript: valid.validateInput(this);" />
					<div id="menuName_uError" class="validationError" style="display: none"></div>	
				</dd>
				<dt style="width: 15%">

					<label for="menuUrl_u"> Menu URL :</label>
				</dt>
				<dd style="width: 30%">
					<input type="hidden" name="menuUrl_ui" id="menuUrl_ui" value="" />
					<input type="text" name="menuUrl_u" id="menuUrl_u" class="required"
						value="" size="40"
						title="The Menu URL Assigned To This Menu" onchange="javascript: valid.validateInput(this);" />
					<div id="menuUrl_uError" class="validationError" style="display: none"></div>	
				</dd>
			</dl>
			<dl class="element">
				<dt style="width: 15%">

					<label for="redirect_u">Menu Redirect :</label>
				</dt>
				<dd style="width: 30%">
					<select size="1" name="redirect_u" id="redirect_u" class="required"
						style="width: 200px" onchange="javascript: valid.validateInput(this);">

						<option value="n">Parent</option>
						<option value="y">Blank</option>

					</select>
					<div id="redirect_uError" class="validationError" style="display: none"></div>
				</dd>
				<dt style="width: 15%">

					<label for="menuPriority_u"> Menu Priority :</label>
				</dt>
				<dd style="width: 30%">
					<input type="text" name="menuPriority_u" id="menuPriority_u"
						value="" size="20" class="required numeric"
						title="[Numeric Value] Order Of Display Of The Menu In The Menu" onchange="javascript: valid.validateInput(this);" />
					<div id="menuPriority_uError" class="validationError" style="display: none"></div>	
				</dd>

			</dl>
			<dl class="element">


				<dt style="width: 15%">

					<label for="cSubmenu_u"> Child Submenu:</label>
				</dt>
				<dd style="width: 30%">
					<input type="hidden" name="cSubmenu_ui" id="cSubmenu_ui" /> <input
						type="text" name="cSubmenu_u" id="cSubmenu_u" value="" size="40"
						title="The Submenu Assigned To This Menu" />
				</dd>

			</dl>

		</fieldset>
		<fieldset class="action buttons">
			<input type="hidden" name="menuId_u" id="menuId_u" value="" /> <input
				type="hidden" name="position_u" id="position_u" value="" />
			<button type="button" class="positive activate" name="activateMenuUrl_u"
				id="activateMenuUrl_u">Activate Submenu URL</button>
			<button type="button" class="negative drop" name="dropMenuUrl_u"
				id="dropMenuUrl_u">Drop Submenu</button>
			<button type="button" name="submit" id="submit" class="regular hide"
				onclick="hideUpdateForm()">Hide Update Portion</button>
			<button type="submit" class="positive update" accesskey="U"><span class="underline">U</span>pdate Submenu</button>
		</fieldset>
	</form>
</div>

<div class="clear"></div>
<div class="display">
	<div id="displaySubmenu">
		<fieldset class="displayElements">
			<div class="legend">
				<span id="displaySubmenuLegend">Details Portion of Menu Url :</span>
			</div>
			<dl>
				<dt style="width: 15%;">
					<label for="menuId">Menu Id : </label>
				</dt>
				<dd style="width: 25%">
					<span id="menuIdDisplay"></span>
				</dd>
				<dt style="width: 20%">
					<label for="menuName">Menu Name :</label>
				</dt>
				<dd style="width: 25%">
					<span id="menuNameDisplay"></span>
				</dd>
			</dl>
			<dl>
				<dt style="width: 15%;">
					<label for="menuUrlName">Menu URL Name : </label>
				</dt>
				<dd style="width: 25%">
					<span id="menuUrlNameDisplay"></span>
				</dd>
				<dt style="width: 20%">
					<label for="menuUrl">Menu URL :</label>
				</dt>
				<dd style="width: 25%">
					<span id="menuUrlDisplay"></span>
				</dd>
			</dl>
			<dl>
				<dt style="width: 15%;">
					<label for="parentSubmenu">Parent Submenu : </label>
				</dt>
				<dd style="width: 25%">
					<span id="parentSubmenuDisplay"></span>
				</dd>
				<dt style="width: 20%">
					<label for="childSubmenu">Child Submenu :</label>
				</dt>
				<dd style="width: 25%">
					<span id="childSubmenuDisplay"></span>
				</dd>
			</dl>
			<dl>
				<dt style="width: 15%;">
					<label for="redirect">Redirect : </label>
				</dt>
				<dd style="width: 25%">
					<span id="redirectDisplay"></span>
				</dd>
				<dt style="width: 20%">
					<label for="priority">Priority :</label>
				</dt>
				<dd style="width: 25%">
					<span id="priorityDisplay"></span>
				</dd>
			</dl>
			<dl>
				<dt style="width: 15%;">
					<label for="lastUpdateDate">Last Update Date : </label>
				</dt>
				<dd style="width: 25%">
					<span id="lastUpdateDateDisplay"></span>
				</dd>
				<dt style="width: 20%">
					<label for="lastUpdatedBy ">Last Updated By :</label>
				</dt>
				<dd style="width: 25%">
					<span id="lastUpdatedByDisplay"></span>
				</dd>
			</dl>


			<dl>
				<dt style="width: 15%;">
					<label for="creationDate">Creation Date : </label>
				</dt>
				<dd style="width: 25%">
					<span id="creationDateDisplay"></span>
				</dd>
				<dt style="width: 20%">
					<label for="createdBy">Created By :</label>
				</dt>
				<dd style="width: 25%">
					<span id="createdByDisplay"></span>
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
		<fieldset class="action buttons">
			<input type="hidden" name="menu_id_d" id="menu_id_d" value="" /> <input
				type="hidden" name="position_d" id="position_d" value="" />
			<button type="button" class="positive activate" name="activateMenuUrl_d"
				id="activateMenuUrl_d">Activate Submenu</button>
			<button type="button" class="negative drop" name="dropMenuUrl_d"
				id="dropMenuUrl_d">Drop Submenu</button>
			<button type="button" class="positive edit" name="update_menu_button"
				id="update_menu_button">Update Submenu</button>
			<button type="button" name="submit" class="regular hide" id="submit"
				onclick="hideDisplayPortion()">Hide Display Details Portion</button>
		</fieldset>
	</div>

</div>

<div class="clear"></div>
<div class="inputs">
	<form id="searchForm" class="searchForm" onsubmit="return valid.validateForm(this) ? getMenuUrlSearchDetails() : false;">
		<fieldset class="formelements">
			<div class="legend">View Menu Url</div>
			<dl>
				<dt style="width: 15%">
					<label for="menu_hint">Url Name :</label>
				</dt>
				<dd style="width: 30%">
					<input type="text" name="menu_hint" id="menu_hint" class=""
						style="width: 200px" />
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
			<button type="submit" name="submit" id="submit" class="positive search">Get Search Results</button>
		</fieldset>
	</form>
</div>
<div class="clear"></div>

<div id="displayDatatable" class="buttons">
	<form>
		<div class="datatable" id="groupMenusM">
			<fieldset class="tableElements">
				<div class="legend">
					<span>Submenu Lists</span>
				</div>
				<table class="display" id="groupMenus">
					<thead>
						<tr>
							<th>Menu Name</th>
							<th>Menu Url</th>
							<th>Child Submenu</th>
							<th style="width: 80px;">Priority</th>
							<th style="width: 140px;">View Details</th>
							<th style="width: 140px;">Edit Details</th>
						</tr>
					</thead>
					<tbody>

					</tbody>
				</table>
			</fieldset>

		</div>
	</form>
</div>