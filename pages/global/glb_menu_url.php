<?php
require_once 'config.php';
require_once BASE_PATH . 'include/global/class.body.php';
require_once BASE_PATH . 'include/global/class.options.php';

$body = new body ();
$options = new options ();
$body->startBody ( 'global', 'LMENUL1', 'Menu Url Entry' );

?>
<div id="content_header">
    <div id="pageButton" class="buttons">
        <button type="button" class="regular toggle" onclick="showHideSearchForm()"><span class="underline">T</span>oggle Search Form</button>
        <button type="button" class="regular toggle" onclick="showHideDatatable()"><span class="underline">T</span>oggle Tabulated Data</button>
    </div>
    <div id="contentHeader">New Menu URL Entry Form</div>
</div>
<div class="clear"></div>
<div class="inputs">
	<form id="insertForm" class="insertForm"
		onsubmit="return valid.validateForm(this) ? processInsertMenuUrlForm() : false;">
		<fieldset class="formelements">
			<div class="legend">
				<span>New Menu Record Entry Form</span>

			</div>
			<dl class="element">
				<dt style="width: 15%;">
					<label for="display_name"> Display Name :</label>
				</dt>
				<dd style="width: 30%">
					<input type="text" class="required" id="display_name"
						name="display_name" tabindex="1" value="" size="30"
						title="Enter the Name you want to be displayed"
						onchange="javascript: valid.validateInput(this);" />
					<div id="display_nameError" class="validationError"
						style="display: none;"></div>
				</dd>
				<dt style="width: 15%">
					<label for="menu_url">Menu Url :</label>
				</dt>
				<dd style="width: 30%">
					<input type="text" class="required" id="menu_url" name="menu_url"
						tabindex="2" value="" size="30"
						title="Enter the Name you want to be displayed"
						onchange="javascript: valid.validateInput(this);" />
					<div id="menu_urlError" class="validationError"
						style="display: none;"></div>
				</dd>
			</dl>
			<dl class="element">
				<dt style="width: 15%;">
					<label for="image_url"> Image Url :</label>
				</dt>
				<dd style="width: 30%">
					<input type="text" class="required" id="image_url" name="image_url"
						tabindex="3" value="menu.png" size="30"
						title="Enter the Url of the image"
						onchange="javascript: valid.validateInput(this);" />
					<div id="image_urlError" class="validationError"
						style="display: none;"></div>
				</dd>
				<dt style="width: 15%">
					<label for="menu_tagline">Menu Tagline :</label>
				</dt>
				<dd style="width: 30%">
					<input type="text" class="required" id="menu_tagline"
						name="menu_tagline" tabindex="4" value="" size="30"
						title="Enter the Name you want to be displayed"
						onchange="javascript: valid.validateInput(this);" />
					<div id="menu_taglineError" class="validationError"
						style="display: none;"></div>
				</dd>
			</dl>
			<dl class="element">
				<dt style="width: 15%;">
					<label for="menu_description"> Menu Description :</label>
				</dt>
				<dd style="width: 30%">
					<textarea class="required" id="menu_description"
						name="menu_description" tabindex="5" value="" cols="50" rows="3"
						title="Enter the menu Description"
						onchange="javascript: valid.validateInput(this);"></textarea>
					<div id="menu_descriptionError" class="validationError"
						style="display: none;"></div>
				</dd>
				<dt style="width: 15%">
					<label for="menu_type"> Base Url :</label>
				</dt>
				<dd style="width: 30%">
					<select size="1" name="menu_type" tabindex="6" id="menu_type"
						class="" style="width: 250px"
						onchange="javascript: valid.validateInput(this);">

                        <?php
																								$optionIds = $options->getOptionValueIds ( 'MENUT' );
																								foreach ( $optionIds as $optionId )
																									echo "<option value=\"$optionId\">" . $options->getOptionIdValue ( $optionId ) . "</option>";
																								?>
                        <option value="">Direct Url</option>
                    </select>
					<div id="menu_typeError" class="validationError"
						style="display: none;"></div>
				</dd>
			</dl>
			<dl class="element">
				<dt style="width: 15%;">
					<input type="checkbox" id="menu_auth" name="menu_auth" tabindex="8"
						value="y" checked="checked"
						title="Whether the menu is authorised or not" />
				</dt>
				<div id="menu_authError" class="validationError"
					style="display: none;"></div>
				<dd style="width: 30%">
					<label for="edit">: Is Menu Login Authenticable</label>
				</dd>
			</dl>
			<dl class="element">
				<dt style="width: 15%;">
					<input type="checkbox" name="menu_edit" tabindex="9" id="menu_edit"
						value="y" checked="checked"
						title="Whether the menu is editable or not" />
				</dt>
				<div id="menu_editError" class="validationError"
					style="display: none;"></div>
				<dd style="width: 30%">
					<label for="menuedit">: Does This Menu Do Data Editing</label>
				</dd>
			</dl>
		</fieldset>
		<fieldset class="action buttons">
            <div class="buttons">
                <button type="button" name="showHide" class="regular hide" onclick="hideInsertForm()"
                        accesskey="H">
                    <span class="underline">H</span>ide Insert Form
                </button>
                <button type="reset" name="insertReset" class="negative reset" id="insertReset"
                        accesskey="R">
                    <span class="underline">R</span>eset Form Fields
                </button>

                <button type="submit" name="submit" id="submit" class="positive insert" accesskey="I">
                    <span class="underline">I</span>nsert New Record
                </button>
            </div>
		</fieldset>
	</form>
</div>
<div class="clear"></div>
<div class="inputs">
	<form id="updateForm" class="updateForm"
		onsubmit="return valid.validateForm(this) ? processUpdateForm() : false;">
		<fieldset class="formelements">
			<div class="legend">
				<span id="legend_updateForm">Update Menu Record</span>
			</div>
			<dl class="element">
				<dt style="width: 15%;">
					<label for="display_name_u"> Display Name :</label>
				</dt>
				<dd style="width: 30%">
					<input type="hidden" name="menu_url_id" id="menu_url_id" /> <input
						type="text" class="required" id="display_name_u"
						name="display_name_u" tabindex="12" value="" size="30"
						title="Enter the Name you want to be displayed"
						onchange="javascript: valid.validateInput(this);" />
					<div id="display_name_uError" class="validationError"
						style="display: none;"></div>
				</dd>
				<dt style="width: 15%">
					<label for="menu_url">Menu Url :</label>
				</dt>
				<dd style="width: 30%">
					<input type="text" class="required" id="menu_url_u"
						name="menu_url_u" tabindex="13" value="" size="30"
						title="Enter the Name you want to be displayed"
						onchange="javascript: valid.validateInput(this);" />
					<div id="menu_url_uError" class="validationError"
						style="display: none;"></div>
				</dd>
			</dl>
			<dl class="element">
				<dt style="width: 15%;">
					<label for="image_url"> Image Url :</label>
				</dt>
				<dd style="width: 30%">
					<input type="text" class="required" id="image_url_u"
						name="image_url_u" tabindex="14" value="" size="30"
						title="Enter the Url of the image"
						onchange="javascript: valid.validateInput(this);" />
					<div id="image_url_uError" class="validationError"
						style="display: none;"></div>
				</dd>
				<dt style="width: 15%">
					<label for="menu_tagline">Menu Tagline :</label>
				</dt>
				<dd style="width: 30%">
					<input type="text" class="required" id="menu_tagline_u"
						name="menu_tagline_u" tabindex="15" value="" size="30"
						title="Enter the Name you want to be displayed"
						onchange="javascript: valid.validateInput(this);" />
					<div id="menu_tagline_uError" class="validationError"
						style="display: none;"></div>
				</dd>
			</dl>
			<dl class="element">
				<dt style="width: 15%;">
					<label for="menu_description"> Menu Description :</label>
				</dt>
				<dd style="width: 30%">
					<textarea class="required" id="menu_description_u"
						name="menu_description_u" tabindex="5" value="" cols="50" rows="3"
						title="Enter the menu Description"
						onchange="javascript: valid.validateInput(this);"></textarea>
					<div id="menu_description_uError" class="validationError"
						style="display: none;"></div>
				</dd>
				<dt style="width: 15%">
					<label for="menu_type"> Menu Type :</label>
				</dt>
				<dd style="width: 30%">
					<select size="1" name="menu_type_u" tabindex="16" id="menu_type_u"
						class="" style="width: 250px"
						onchange="javascript: valid.validateInput(this);">                            
                        <?php
																								$optionIds = $options->getOptionValueIds ( 'MENUT' );
																								foreach ( $optionIds as $optionId )
																									echo "<option value=\"$optionId\">" . $options->getOptionIdValue ( $optionId ) . "</option>";
																								?>
                        <option value="">Direct Url</option>
                    </select>
					<div id="menu_type_uError" class="validationError"
						style="display: none;"></div>
				</dd>
			</dl>
			<dl class="element">
				<dt style="width: 15%;">
					<input type="checkbox" id="menu_auth_u" name="menu_auth_u"
						tabindex="18" checked="checked" value="y"
						title="Whether the menu is authorised or not"
						onchange="javascript: valid.validateInput(this);" />
				</dt>				
				<dd style="width: 30%">
					<label for="edit">: Is Menu Login Authenticable</label>
				</dd>
				<div id="menu_auth_uError" class="validationError"
					style="display: none;"></div>
			</dl>
			<dl class="element">
				<dt style="width: 15%;">
					<input type="checkbox" name="menu_edit_u" tabindex="19"
						id="menu_edit_u" checked="checked" value="y"
						title="Whether the menu is editable or not" />
				</dt>				
				<dd style="width: 30%">
					<label for="menuedit">: Does This Menu Do Data Editing</label>
				</dd>
				<div id="menu_edit_uError" class="validationError"
					style="display: none;"></div>
			</dl>
		</fieldset>
		<fieldset class="action buttons">
			<input type="hidden" name="menuId_u" tabindex="20" id="menuId_u"
				value="" /> <input type="hidden" name="rowPosition_u" tabindex="21"
				id="rowPosition_u" value="" />
			<button accesskey="A" type="button" class="positive activate"
				name="activateMenuUrl_u" tabindex="22" id="activateMenuUrl_u">
				<span class="underline">A</span>ctivate Menu URL
			</button>
			<button accesskey="D" type="button" class="negative drop" name="dropMenuUrl_u"
				tabindex="23" id="dropMenuUrl_u">
				<span class="underline">D</span>rop Menu URL
			</button>
			<button accesskey="H" type="button" name="toggleInsert" tabindex="24" class="regular hide"
				id="toggleInsert" onclick="hideUpdateForm()">
				<span class="underline">H</span>ide Update Form
			</button>
			<button acesskey="U" type="submit" name="submit" tabindex="25" class="positive update"
				id="submit">
				<span class="underline">U</span>pdate Menu Url
			</button>
		</fieldset>
	</form>
</div>
<div class="clear"></div>
<div class="display">
	<div id="menuDetails">
		<fieldset class="displayElements">
			<div class="legend">
				<span id="legendDisplayAssignment">Displaying Detailed Of Menu Record</span>
			</div>
			<dl>
				<dt style="width: 15%;">
					<label for="menu_id">Internal Menu Id : </label>
				</dt>
				<dd style="width: 25%">
					<span id="menuIdDisplay"></span>
				</dd>
				<dt style="width: 20%">
					<label for="menu_name">Menu Name :</label>
				</dt>
				<dd style="width: 25%">
					<span id="menuNameDisplay"></span>
				</dd>
			</dl>
			<dl>
				<dt style="width: 15%;">
					<label for="menu_url">Menu URL : </label>
				</dt>
				<dd style="width: 25%">
					<span id="menuUrlDisplay"></span>
				</dd>
				<dt style="width: 20%">
					<label for="menu_image_url">Menu Image Url :</label>
				</dt>
				<dd style="width: 25%">
					<span id="menuImageUrlDisplay"></span>
				</dd>
			</dl>
			<dl>
				<dt style="width: 15%;">
					<label for="menu_tagline">Menu Tagline : </label>
				</dt>
				<dd style="width: 25%">
					<span id="menuTaglineDisplay"></span>
				</dd>
				<dt style="width: 20%;">
					<label for="url_source_id">Url Source Id : </label>
				</dt>
				<dd style="width: 25%">
					<span id="urlSourceIdDisplay"></span>
				</dd>				
			</dl>
			<dl>
				<dt style="width: 15%;">
					<label for="menu_editable">Edit Enabled : </label>
				</dt>
				<dd style="width: 25%">
					<span id="menuEdit"></span>
				</dd>
				<dt style="width: 20%">
					<label for="menu_auth">Menu Authorisation :</label>
				</dt>
				<dd style="width: 25%">
					<span id="menuAuth"></span>
				</dd>
			</dl>
			<dl>
				<dt style="width: 15%">
					<label for="menu_description">Menu Description :</label>
				</dt>
				<dd style="width: 80%">
					<span id="menuDescription"></span>
				</dd>
			</dl>
			<dl>
				<dt style="width: 15%;">
					<label for="last_update_date">Last Update Date : </label>
				</dt>
				<dd style="width: 25%">
					<span id="lastUpdateDateDisplay"></span>
				</dd>
				<dt style="width: 20%">
					<label for="last_updated_by ">Last Updated By :</label>
				</dt>
				<dd style="width: 25%">
					<span id="lastUpdatedByDisplay"></span>
				</dd>
			</dl>
			<dl>
				<dt style="width: 15%;">
					<label for="creation_date">Creation Date : </label>
				</dt>
				<dd style="width: 25%">
					<span id="creationDateDisplay"></span>
				</dd>
				<dt style="width: 20%">
					<label for="created_by">Created By :</label>
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
			<button accesskey="A" type="button" class="positive activate"
				name="activateMenuUrl_d" tabindex="26" id="activateMenuUrl_d">
				<span class="underline">A</span>ctivate Menu URL
			</button>
			<button accesskey="D" type="button" class="negative drop" name="dropMenuUrl_d"
				tabindex="27" id="dropMenuUrl_d">
				<span class="underline">D</span>rop Menu URL
			</button>
			<button accesskey="H" type="button" name="submit" class="regular hide" tabindex="29"
				id="submit" onclick="hideDetailsPortion()">
				<span class="underline">H</span>ide Details Portion
			</button>
			<button accesskey="U" type="submit" name="update_menu_button" class="regular edit"
				tabindex="28" id="update_menu_button"
				onclick="updateMenuUrlDetails1()">
				<span class="underline">U</span>pdate Menu URL
			</button>

		</fieldset>
	</div>
</div>
<div class="clear"></div>
<div class="inputs">
	<form id="searchForm" class="searchForm"
		onsubmit="return valid.validateForm(this) ? getMenuUrlSearchDetails() : false;">
		<fieldset class="formelements">
			<div class="legend">Search Menu Record</div>
			<dl>
				<dt style="width: 15%">
					<label for="menu_hint">Menu Name :</label>
				</dt>
				<dd style="width: 30%">
					<input type="text" name="menu_hint" tabindex="30" id="menu_hint"
						class="" style="width: 200px"
						onchange="javascript: valid.validateInput(this);" />
					<div id="menu_hintError" class="validationError"
						style="display: none;"></div>
				</dd>
				<dt style="width: 15%">
					<label for="search_type">Search Type :</label>
				</dt>
				<dd>
					<select name="search_type" tabindex="31" id="search_type"
						style="width: 150px">
						<option value="all">All Menus</option>
						<option value="1" selected="selected">Active Menus</option>
						<option value="0">In-Active Menus</option>
					</select>
				</dd>
				<div id="search_typeError" class="validationError"
					style="display: none;"></div>
			</dl>
			<dl>
			</dl>
		</fieldset>
		<fieldset class="action buttons">
			<div class="buttons">
                <button accesskey="T" type="button" name="toggleInsert1" class="regular toggle"
                        tabindex="32" id="toggleInsert1" onclick="showHideInsertForm()">
                    <span class="underline">T</span>oggle Insert Form
                </button>
                <button type="reset" name="searchReset" id="searchReset" class="negative reset"
                        accesskey="L">
                    Reset Search Fie<span class="underline">l</span>ds
                </button>
                <button accesskey="S" type="submit" name="toggleInsert1" class="positive search"
                        tabindex="33" id="toggleInsert1">
                    Get <span class="underline">S</span>earch Results
                </button>
			</div>
		</fieldset>
	</form>

</div>

<div class="clear"></div>
<form>
	<div class="datatable buttons" id="groupMenus_s">
		<fieldset class="tableElements">
			<div class="legend">
				<span>Tabulated Menu Record Listing</span>
			</div>
			<table  class="display"
				id="groupMenus">
				<thead>
					<tr>
						<th style="width: 10%;">Menu Url Id</th>
						<th style="width: 25%;">Menu Name</th>
						<th>Menu url</th>
						<th style="width: 140px;">Show Details</th>
						<th style="width: 145px;">Edit Details</th>
					</tr>
				</thead>
				<tbody>

				</tbody>
			</table>
		</fieldset>

	</div>
</form>
</div>