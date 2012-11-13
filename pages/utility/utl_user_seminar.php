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
require_once BASE_PATH . 'include/global/class.options.php';

$body = new body ();
$options = new options ();
$body->startBody ( 'utility', 'LMENUL41', 'Employee Seminar Details Entry/Edit Page' );
$userId = $_GET ['userId'];
?>
<div id="content_header">
    <div id="pageButton" class="buttons">
        <button type="button" class="regular toggle" onclick="showHideSearchForm()"><span class="underline">T</span>oggle Search Form</button>
        <button type="button" class="regular toggle" onclick="showHideDatatable()"><span class="underline">T</span>oggle Tabulated Data</button>
    </div>
    <div id="contentHeader">Employee Seminar Record Details</div>
</div>
<input type="hidden" name="userId" value="<?php echo $userId; ?>" id="userId" />

<div class="display">
	<div id="candidateInfo">
		<fieldset class="displayElements">
			<div class="legend">
				<span id="legend_mailForm">Details Of The Employee</span>
			</div>
			<dl>
				<dt style="width: 20%">
					<label for="candidateName">Employee Name :</label>
				</dt>
				<dd style="width: 28%">
					<span id="candidateName"></span>
				</dd>
				<dt style="width: 20%">
					<label for="registrationNumber">Employee Code :</label>
				</dt>
				<dd style="width: 28%">
					<span id="registrationNumber"></span>
				</dd>
			</dl>
			<dl>
				<dt style="width: 20%">
					<label for="registeredEmail">Official Email :</label>
				</dt>
				<dd style="width: 28%">
					<span id="registeredEmail"></span>
				</dd>
				<dt style="width: 20%">
					<label for="designation">Designation :</label>
				</dt>
				<dd style="width: 28%">
					<span id="designation"></span>
				</dd>
			</dl>
		</fieldset>
	</div>
</div>
<div class="clear"></div>
<div class="inputs">
	<form id="insertForm" class="insertForm"
		onsubmit="return processInsertForm()">
		<fieldset class="formelements">
			<div class="legend">
				<span>New Seminar Entry Portion</span>
			</div>
			<dl class="element">
				<dt style="width: 15%"><label for="seminarTitle">Seminar Title :</label>	</dt>
				<dd style="width: 80%">
						<input type="text" size="40" name="seminarTitle" id="seminarTitle" class="required" tabindex="1" onchange="javascript: valid.validateInput(this);" title="Enter the seminar title" />
						<div id="seminarTitleError" class="validationError" style="display: none"></div></dd>				
			</dl>
			<dl class="element">
				<dt style="width: 15%"><label for="organizedBy">Organized By :</label>	</dt>
				<dd style="width: 80%">
						<input type="text" size="40" name="organizedBy" id="organizedBy" class="required" tabindex="2" onchange="javascript: valid.validateInput(this);" title="The organization which held the seminar" />
						<div id="organizedByError" class="validationError" style="display: none"></div></dd>				
			</dl>
			<dl class="element">
				<dt style="width: 15%"><label for="startDate">Date :</label>	</dt>
				<dd style="width: 30%">
						<input type="text" name="startDate" id="startDate" class="required date" tabindex="3" size="20" onchange="javascript: valid.validateInput(this);" title="Start date of the seminar" />
						<div id="startDateError" class="validationError" style="display: none"></div></dd>
				<dt style="width: 15%"><label for="duration">Duration :</label>	</dt>
				<dd style="width: 30%">
						<input type="text" name="duration" id="duration" class="required numeric" tabindex="4" size="20" onchange="javascript: valid.validateInput(this);" title="Duration In Days" /> Days
						<div id="durationError" class="validationError" style="display: none"></div></dd>
			</dl>
			<dl class="element">
				<dt style="width: 15%"><label for="remarks">Comments :</label>	</dt>
				<dd style="width: 80%">
						<textarea name="comments" id="comments" rows="3" cols="80" tabindex="5"></textarea></dd>				
			</dl>
		</fieldset>
		<fieldset class="action buttons">
			<button type="button" name="submit" tabindex="5" onclick="hideInsertForm()" class="regular hide"
				accesskey="H">
				<span class="underline">H</span>ide Insert Form
			</button>

			<button type="reset" name="insertReset" id="insertReset" tabindex="6" class="negative reset"
				accesskey="R">
				<span class="underline">R</span>eset Form Fields
			</button>

			<button type="submit" name="submit" id="submit" tabindex="7" accesskey="I" class="positive insert">
				<span class="underline">I</span>nsert New Record
			</button>
		</fieldset>
	</form>
</div>
<div class="clear"></div>
<div class="inputs">
	<form id="updateForm" class="updateForm"
		onsubmit="return processUpdateForm()">
		<fieldset class="formelements">
			<div class="legend">
				<span id="legend_updateForm">Update Seminar Record Details </span>
			</div>
			<dl class="element">
				<dt style="width: 15%"><label for="seminarTitle">Seminar Title :</label>	</dt>
				<dd style="width: 80%">
						<input type="text" size="40" name="seminarTitle_u" id="seminarTitle_u" class="required" tabindex="8" onchange="javascript: valid.validateInput(this);" title="Enter the seminar title" />
						<div id="seminarTitle_uError" class="validationError" style="display: none"></div></dd>				
			</dl>
			<dl class="element">
				<dt style="width: 15%"><label for="organizedBy">Organized By :</label>	</dt>
				<dd style="width: 80%">
						<input type="text" size="40" name="organizedBy_u" id="organizedBy_u" class="required" tabindex="9" onchange="javascript: valid.validateInput(this);" title="The organization which held the seminar" />
						<div id="organizedBy_uError" class="validationError" style="display: none"></div></dd>				
			</dl>
			<dl class="element">
				<dt style="width: 15%"><label for="startDate">Date :</label>	</dt>
				<dd style="width: 30%">
						<input type="text" name="startDate_u" id="startDate_u" class="required date" tabindex="10" size="20" onchange="javascript: valid.validateInput(this);" title="The start date of the seminar" />
						<div id="startDate_uError" class="validationError" style="display: none"></div></dd>
				<dt style="width: 15%"><label for="duration">Duration :</label>	</dt>
				<dd style="width: 30%">
						<input type="text" name="duration_u" id="duration_u" class="required" tabindex="11" size="20" onchange="javascript: valid.validateInput(this);" title="The duration in days" />
						<div id="duration_uError" class="validationError" style="display: none"></div></dd>
			</dl>
			<dl class="element">
				<dt style="width: 15%"><label for="remarks">Comments :</label>	</dt>
				<dd style="width: 80%">
						<textarea name="comments_u" id="comments_u" rows="3" cols="80" tabindex="12"></textarea></dd>				
			</dl>
		</fieldset>

		<fieldset class="action buttons">
			<input type="hidden" name="recordId_u" tabindex="20" id="recordId_u"
				value="" /> <input type="hidden" name="rowPosition_u" tabindex="21"
				id="rowPosition_u" value="" />
			<button accesskey="A" type="button" class="positive activate"
				name="activateRecord_u" tabindex="22" id="activateRecord_u">
				<span class="underline">A</span>ctivate Record
			</button>
			<button accesskey="D" type="button" class="negative drop" name="dropRecord_u"
				tabindex="23" id="dropRecord_u">
				<span class="underline">D</span>rop Record
			</button>
			<button accesskey="H" type="button" name="toggleInsert" tabindex="24" class="regular hide"
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
	<div id="displayPortion">
		<fieldset class="displayElements">
			<div class="legend">
				<span id="legendDisplayDetail">Seminar Details</span>
			</div>
			<dl>
				<dt style="width: 15%;">
					<label for="seminarTitle">Seminar Title :</label>
				</dt>
				<dd style="width: 80%">
					<span id="seminarTitle_d"></span>
				</dd>				
			</dl>
			<dl>
				<dt style="width: 15%;">
					<label for="organizedBy">Organized By :</label>
				</dt>
				<dd style="width: 80%">
					<span id="organizedBy_d"></span>
				</dd>				
			</dl>
			<dl>
				<dt style="width: 15%;">
					<label for="startDate">Date :</label>
				</dt>
				<dd style="width: 30%">
					<span id="startDate_d"></span>
				</dd>
				<dt style="width: 15%">
					<label for="duration">Duration :</label>
				</dt>
				<dd style="width: 30%">
					<span id="duration_d"></span>
				</dd>
			</dl>
			<dl>
				<dt style="width: 15%;">
					<label for="comments">Comments :</label>
				</dt>
				<dd style="width: 80%">
					<span id="comments_d"></span>
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
					<label for="updatedBy">Updated By :</label>
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
				id="submit" onclick="hideDisplayPortion()">
				<span class="underline">H</span>ide Details Portion
			</button>
			<button accesskey="U" type="submit" name="editRecordButton" class="positive edit"
				tabindex="28" id="editRecordButton">
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
			<div class="legend">Search Record</div>
			<dl>
				<dt style="width: 15%">
					<label for="search_type">Search Type :</label>
				</dt>
				<dd>
					<select name="search_type" tabindex="31" id="search_type"
						style="width: 150px">
						<option value="all">All Records</option>
						<option value="1" selected="selected">Active Records</option>
						<option value="0">In-Active Records</option>
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
				tabindex="32" id="toggleInsert1" onclick="toggleInsertForm()" class="regular toggle">
				<span class="underline">T</span>oggle Insert Form
			</button>
			<button type="reset" name="searchReset" id="searchReset" class="negative reset"
				accesskey="L">
				Reset Search Fie<span class="underline">l</span>ds
			</button>
			<button accesskey="S" type="submit" name="submitSearch" tabindex="33" class="positive search"
				id="submitSearch">
				Get <span class="underline">S</span>earch Results
			</button>
		</fieldset>
	</form>

</div>
<div class="clear"></div>
<div class="datatable buttons" id="displayDatatable">
	<fieldset class="tableElements">
		<div class="legend">
			<span>Seminar Record Tabulated Listing</span>
		</div>
		<table  class="display"
			id="groupRecord">
			<thead>
				<tr>
					<th>Title</th>
					<th>Organized By</th>
					<th>Date</th>
					<th style="width: 50px;">Duration</th>
					<th style="width: 150px;">View Details</th>
					<th style="width: 150px;">Edit Details</th>
				</tr>
			</thead>
			<tbody>
			</tbody>
		</table>
	</fieldset>
</div>
<div class="clear"></div>

<?php
	$body->endBody ( 'utility', 'MENUL41' );
?>
