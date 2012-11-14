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
$body->startBody ( 'utility', 'LMENUL42', 'Employee Employment History Entry/Edit Page' );
$userId = $_GET ['userId'];
?>
<div id="content_header">
    <div id="pageButton" class="buttons">
        <button type="button" class="regular toggle" onclick="showHideSearchForm()"><span class="underline">T</span>oggle Search Form</button>
        <button type="button" class="regular toggle" onclick="showHideDatatable()"><span class="underline">T</span>oggle Tabulated Data</button>
    </div>
    <div id="contentHeader">Employee Employment History</div>
</div>
<input type="hidden" name="userId" value="<?php echo $userId; ?>" id="userId" />

<div class="display">
	<div id="candidateInfo">
		<fieldset class="displayElements">
			<div class="legend">
				<span id="legend_mailForm">Details Of The Employee</span>
			</div>
			<dl>
				<dt style="width: 15%">
					<label for="candidateName">Employee Name :</label>
				</dt>
				<dd style="width: 30%">
					<span id="candidateName"></span>
				</dd>
				<dt style="width: 15%">
					<label for="registrationNumber">Employee Code :</label>
				</dt>
				<dd style="width: 30%">
					<span id="registrationNumber"></span>
				</dd>
			</dl>
			<dl>
				<dt style="width: 15%">
					<label for="registeredEmail">Official Email :</label>
				</dt>
				<dd style="width: 30%">
					<span id="registeredEmail"></span>
				</dd>
				<dt style="width: 15%">
					<label for="designation">Designation :</label>
				</dt>
				<dd style="width: 30%">
					<span id="designation"></span>
				</dd>
			</dl>
		</fieldset>
	</div>
</div>
<div class="clear"></div>
<br />

<div class="inputs">
	<form id="insertForm" class="insertForm"
		onsubmit="return processInsertForm()">
		<fieldset class="formelements">
			<div class="legend">
				<span>New Employment Record Entry Portion</span>
			</div>			
			<dl class="element">
				<dt style="width: 15%"><label for="organizationId">Organization Name :</label>	</dt>
				<dd style="width: 30%">
						<input type="hidden" name="organization_val" id="organization_val" />
						<input type="text" name="organization" id="organization" class="required autocomplete" tabindex="1" size="30" onchange="javascript: valid.validateInput(this);" title="Enter the organization name" />
						<button type="button" class="addInstitute">Add New Org</button>
						<div id="organizationError" class="validationError" style="display: none"></div></dd>
				<dt style="width: 15%"><label for="position">Position Held :</label>	</dt>
				<dd style="width: 30%">
						<input type="hidden" name="position_val" id="position_val" />
						<input type="text" name="position" id="position" class="required autocomplete" tabindex="2" size="30" onchange="javascript: valid.validateInput(this);" title="Enter the position held at the organization" />
						<div id="positionError" class="validationError" style="display: none"></div></dd>
			</dl>
			<dl class="element">
				<dt style="width: 15%"><label for="startDate">Joining Date :</label>	</dt>
				<dd style="width: 30%">
						<input type="text" name="startDate" id="startDate" class="required date" tabindex="3" size="20" onchange="javascript: valid.validateInput(this);" title="Select the joining date" />
						<div id="startDateError" class="validationError" style="display: none"></div></dd>
				<dt style="width: 15%"><label for="endDate">Leaving Date :</label>	</dt>
				<dd style="width: 30%">
						<input type="text" name="endDate" id="endDate" class="required date" tabindex="4" size="20" onchange="javascript: valid.validateInput(this);" title="Select the leaving date" />
						<div id="endDateError" class="validationError" style="display: none"></div></dd>
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
				<span id="legend_updateForm">Update Employment Details </span>
			</div>			
			<dl class="element">
				<dt style="width: 15%"><label for="organizationId">Organization Name :</label>	</dt>
				<dd style="width: 30%">
						<input type="hidden" name="organization_uval" id="organization_uval" />
						<input type="text" name="organization_u" id="organization_u" class="required" tabindex="6" size="30" onchange="javascript: valid.validateInput(this);" title="Enter the organization name" />
						<button type="button" class="addInstitute">Add New Org</button>
						<div id="organization_uError" class="validationError" style="display: none"></div></dd>
				<dt style="width: 15%"><label for="position">Position Held :</label>	</dt>
				<dd style="width: 30%">
						<input type="hidden" name="position_uval" id="position_uval" />
						<input type="text" name="position_u" id="position_u" class="required" tabindex="7" size="30" onchange="javascript: valid.validateInput(this);" title="Enter the position held at the organization" />
						<div id="position_uError" class="validationError" style="display: none"></div></dd>
			</dl>
			<dl class="element">
				<dt style="width: 15%"><label for="startDate">Joining Date :</label>	</dt>
				<dd style="width: 30%">
						<input type="text" name="startDate_u" id="startDate_u" class="required date" tabindex="8" size="20" onchange="javascript: valid.validateInput(this);" title="Select the joining date" />
						<div id="startDate_uError" class="validationError" style="display: none"></div></dd>
				<dt style="width: 15%"><label for="endDate">Leaving Date :</label>	</dt>
				<dd style="width: 30%">
						<input type="text" name="endDate_u" id="endDate_u" class="required date" tabindex="9" size="20" onchange="javascript: valid.validateInput(this);" title="Select the leaving date" />
						<div id="endDate_uError" class="validationError" style="display: none"></div></dd>
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
					<label for="organizationId">Organization Name :</label>
				</dt>
				<dd style="width: 30%">
					<span id="organization_d"></span>
				</dd>
				<dt style="width: 15%">
					<label for="position">Position Held :</label>
				</dt>
				<dd style="width: 30%">
					<span id="position_d"></span>
				</dd>
			</dl>
			<dl>
				<dt style="width: 15%;">
					<label for="startDate">Joining Date :</label>
				</dt>
				<dd style="width: 30%">
					<span id="startDate_d"></span>
				</dd>
				<dt style="width: 15%">
					<label for="endDate">Leaving Date :</label>
				</dt>
				<dd style="width: 30%">
					<span id="endDate_d"></span>
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
			<button accesskey="T" type="button" name="toggleInsert1" class="regular toggle"
				tabindex="32" id="toggleInsert1" onclick="toggleInsertForm()">
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
	<fieldset>
		<div class="legend">
			<span>Employment Record Tabulated Listing</span>
		</div>
		<table  class="display"
			id="groupRecord">
			<thead>
				<tr>
					<th>Organization Name</th>
					<th>Position</th>
					<th>Start Date</th>
					<th>End Date</th>
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
	$body->endBody ( 'utility', 'MENUL42' );
?>
