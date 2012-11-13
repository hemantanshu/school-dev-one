<?php
/**
 * 
 * @author shubhamkesarwani@supportgurukul.com(html)
 * @category Utility
 * @license Support-Gurukul
 * @version 1.0.0
 */

require_once 'config.php';
require_once BASE_PATH . 'include/global/class.body.php';

$body = new body ();
$body->startBody ( 'utility', 'LMENUL23', 'Class Section Assignment' );

$classId = $_GET ['classId'];
$details = $body->getTableIdDetails($classId);
if ($details['class_id'] == "")
	exit(0);


?>
<div id="content_header">
    <div id="pageButton" class="buttons">
        <button type="button" class="regular toggle" onclick="showHideSearchForm()"><span class="underline">T</span>oggle Search Form</button>
        <button type="button" class="regular toggle" onclick="showHideDatatable()"><span class="underline">T</span>oggle Tabulated Data</button>
    </div>
    <div id="contentHeader">Section Record Details </div>
</div>
<input type="hidden" name="class_global" id="class_global"
	value="<?php echo $classId; ?>" />
<div class="clear"></div>
<div class="display">
    <div id="sessionRecord" style="display: none">
        <fieldset class="displayElements">
            <dl>
                <dt style="width: 15%">
                    <label for="session_d">Session :</label>
                </dt>
                <dd style="width: 30%">
                    <span id="session_d"></span>
                </dd>
                <dt style="width: 15%">
                    <label for="class_d">Class :</label>
                </dt>
                <dd style="width: 30%">
                    <span id="class_d"></span>
                </dd>
            </dl>
        </fieldset>
    </div>
</div>
<div class="clear"></div>
<div class="inputs">
	<form id="insertForm" class="insertForm" onsubmit="return valid.validateForm(this) ? processInsertForm() : false;">
		<fieldset class="formelements">
			<div class="legend">
				<span>New Section Record Entry Form</span>
			</div>
			<dl class="element">
				<dt style="width: 15%">
					<label for="sectionName">Section Name :</label>
				</dt>
				<dd style="width: 30%">
					<input type="text" name="sectionName" id="sectionName"
						class="required" title="Enter The Class Section" value=""
						size="40" onchange="javascript: valid.validateInput(this);" />
					<div id="sectionNameError" class="validationError"
						style="display: none;"></div>
				</dd>
				<dt style="width: 15%">

					<label for="studentCap">Student Capacity :</label>
				</dt>
				<dd style="width: 30%">
					<input type="text" name="studentCap" id="studentCap"
						class="required numeric"
						title="Enter The Capacity of Class in terms of Students"
						value="" size="40"
						onchange="javascript: valid.validateInput(this);" />
					<div id="studentCapError" class="validationError"
						style="display: none;"></div>
				</dd>
			</dl>
			<dl class="element">
				<dt style="width: 15%">

					<label for="coordinator">Section Coordinator :</label>
				</dt>
				<dd style="width: 30%">
					<input type="hidden" name="coordinator_val"
						id="coordinator_val" value="" /> <input type="text"
						name="coordinator" id="coordinator" class=""
						title="Enter The Section Coordinator" value=""
						size="40" onchange="javascript: valid.validateInput(this);" />
					<div id="coordinatorError" class="validationError"
						style="display: none;"></div>
				</dd>
                <dt style="width: 15%">

                    <label for="roomAllocated">Room Allocated :</label>
                </dt>
                <dd style="width: 30%">
                    <input type="hidden" name="roomAllocated_val"
                           id="roomAllocated_val" value="" /> <input type="text"
                                                                     name="roomAllocated" id="roomAllocated" class="required"
                                                                     title="Enter The Room that has been allocated" value=""
                                                                     size="40" onchange="javascript: valid.validateInput(this);" />
                    <div id="roomAllocatedError" class="validationError"
                         style="display: none;"></div>
                </dd>
			</dl>
		</fieldset>

		<fieldset class="action buttons">
			<button type="reset" name="insertReset" id="insertReset" class="negative reset">Reset Form</button>
			<button type="button" name="submit" class="regular hide" onclick="hideInsertForm()">Hide
				Insert Form</button>
			<button type="submit" name="submit" id="submit" class="positive insert">Insert New Record</button>
		</fieldset>
	</form>
</div>


<div class="clear"></div>
<div class="inputs">
	<form id="updateForm" class="updateForm" onsubmit="return valid.validateForm(this) ? processUpdateForm() : false;" style="display: none">
		<fieldset class="formelements">
			<div class="legend">
				<span id="legend_editForm">Update Section Record Details</span>
			</div>
			<dl class="element">
				<dt style="width: 15%">

					<label for="sectionName_e">Section Name :</label>
				</dt>
				<dd style="width: 30%">
					<input type="text" name="sectionName_e" id="sectionName_e"
						class="required" title="Edit The Class Section" value=""
						size="40" onchange="javascript: valid.validateInput(this);" />
					<div id="sectionName_eError" class="validationError"
						style="display: none;"></div>
				</dd>
				<dt style="width: 15%">

					<label for="studentCap_e">Student Capacity :</label>
				</dt>
				<dd style="width: 30%">
					<input type="text" name="studentCap_e" id="studentCap_e"
						class="required"
						title="Edit The Capacity of Class in terms of Students"
						value="" size="40"
						onchange="javascript: valid.validateInput(this);" />
					<div id="studentCap_eError" class="validationError"
						style="display: none;"></div>
				</dd>
			</dl>
			<dl class="element">
                <dt style="width: 15%">

                    <label for="coordinator">Section Coordinator :</label>
                </dt>
                <dd style="width: 30%">
                    <input type="hidden" name="coordinator_uval"
                           id="coordinator_uval" value="" /> <input type="text"
                                                                   name="coordinator_u" id="coordinator_u" class=""
                                                                   title="Enter The Section Coordinator" value=""
                                                                   size="40" onchange="javascript: valid.validateInput(this);" />
                    <div id="coordinator_uError" class="validationError"
                         style="display: none;"></div>
                </dd>
				<dt style="width: 15%">

					<label for="roomAllocated_e">Room Allocated :</label>
				</dt>
				<dd style="width: 30%">
					<input type="hidden" name="roomAllocated_eval"
						id="roomAllocated_eval" value="" /> <input type="text"
						name="roomAllocated_e" id="roomAllocated_e" class="required"
						title="Edit The Room that has been allocated" value=""
						size="40" onchange="javascript: valid.validateInput(this);" />
					<div id="roomAllocated_eError" class="validationError"
						style="display: none;"></div>
				</dd>

			</dl>
		</fieldset>
		<fieldset class="action buttons">
			<input type="hidden" name="valueId_u" id="valueId_u" value="" /> <input
				type="hidden" name="rowPosition_u" id="rowPosition_u" value="" />
			<button type="button" class="positive activate" name="activateRecord_u"
				id="activateRecord_u">Activate Record</button>
			<button type="button" class="negative drop" name="dropRecord_u"
				id="dropRecord_u">Drop Record</button>
			<button type="button" name="submit" id="submit" class="regular hide"
				onclick="hideUpdateForm()">Hide Update Portion</button>
			<button type="submit" class="positive update">Update Record</button>
		</fieldset>

	</form>
</div>

<div class="clear"></div>
<div class="display">
	<div id="displayRecord" style="display: none">
		<fieldset class="displayElements">
			<div class="legend">
				<span id="legendDisplayDetail">Showing Details Of The Section </span>
			</div>
			<dl>
				<dt style="width: 15%">
					<label for="className_d">Class Name :</label>
				</dt>
				<dd style="width: 30%">
					<span id="className_dDisplay"></span>
				</dd>
				<dt style="width: 15%">
					<label for="capacity_d">Class Capacity :</label>
				</dt>
				<dd style="width: 30%">
					<span id="capacity_dDisplay"></span>
				</dd>
			</dl>
			<dl>
				<dt style="width: 15%;">
					<label for="sectionName_d">Section Name : </label>
				</dt>
				<dd style="width: 30%">
					<span id="sectionName_dDisplay"></span>
				</dd>
				<dt style="width: 15%;">
					<label for="roomName_d">Room Name : </label>
				</dt>
				<dd style="width: 30%">
					<span id="roomName_dDisplay"></span>
				</dd>
			</dl>
            <dl>
                <dt style="width: 15%;">
                    <label for="coordinator">Coordinator Name : </label>
                </dt>
                <dd style="width: 30%">
                    <span id="coordinator_d"></span>
                </dd>
            </dl>
			<dl>
				<dt style="width: 15%;">
					<label for="roomNo_d">Room No : </label>
				</dt>
				<dd style="width: 30%">
					<span id="roomNo_dDisplay"></span>
				</dd>
				<dt style="width: 15%;">
					<label for="floorNo_d">Floor No : </label>
				</dt>
				<dd style="width: 30%">
					<span id="floorNo_dDisplay"></span>
				</dd>
			</dl>
			<dl>
				<dt style="width: 15%;">
					<label for="buildingName_d">Building Name : </label>
				</dt>
				<dd style="width: 30%">
					<span id="buildingName_dDisplay"></span>
				</dd>
				<dt style="width: 15%;">
					<label for="seatingCapacityN_d">Seating Capacity(N) : </label>
				</dt>
				<dd style="width: 30%">
					<span id="seatingCapacityN_dDisplay"></span>
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
			<input type="hidden" name="valueId_d" id="valueId_d" value="" /> <input
				type="hidden" name="rowPosition_d" id="rowPosition_d" value="" />
			<button type="button" class="positive activate" name="activateRecord_d"
				id="activateRecord_d">Activate Record</button>
			<button type="button" class="negative drop" name="dropRecord_d"
				id="dropRecord_d">Drop Record</button>
            <button type="button" name="submit" class="regular hide" id="submit"
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
					<label for="Section_hint">Section Name :</label>
				</dt>
				<dd style="width: 30%">
					<input type="text" name="hint" id="hint" class="required"
						style="width: 200px" title="Enter The Room Hint" />
				</dd>
				<dt style="width: 15%">
					<label for="search_type">Search Type :</label>
				</dt>
				<dd>
					<select name="search_type" id="search_type" style="width: 150px">
						<option value="all">All Section</option>
						<option value="1" selected="selected">Active Sections</option>
						<option value="0">In-Active Sections</option>
					</select>
				</dd>
			</dl>
		</fieldset>
		<fieldset class="action buttons">
			<button type="button" name="toggleInsert" id="toggleInsert" class="regular toggle"
				onclick="toggleInsertForm()">Toggle Insert Form</button>
			<button type="submit" name="searchData" id="searchData" class="positive search">Get Search
				Results</button>
		</fieldset>
	</form>
</div>

<div class="clear"></div>


<div class="datatable buttons" id="displayDatatable" style="display: none">
	<fieldset class="formelements">
		<div class="legend">
			<span>Tabulated Listing Of Sections</span>
		</div>
		<table  class="display"
			id="groupSections">
			<thead>
				<tr>
					<th>Section Name</th>
					<th style="width: 150px">Student capacity</th>
					<th>Room Name</th>
                    <th style="width: 170px">Candidates</th>
					<th style="width: 160px">View Details</th>
					<th style="width: 150px">Edit Details</th>
				</tr>
			</thead>
			<tbody>

			</tbody>
		</table>
	</fieldset>
</div>
