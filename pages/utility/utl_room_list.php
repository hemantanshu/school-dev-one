<?php
require_once 'config.php';
require_once BASE_PATH . 'include/global/class.body.php';
$body = new body ();

$body->startBody ( 'utility', 'LMENUL10', 'utl_room_list' );

?>
<div class="clear"></div>
<div id="content_header">
    <div id="pageButton" class="buttons">
        <button type="button" class="regular toggle" onclick="showHideSearchForm()"><span class="underline">T</span>oggle Search Form</button>
        <button type="button" class="regular toggle" onclick="showHideDatatable()"><span class="underline">T</span>oggle Tabulated Data</button>
    </div>
    <div id="contentHeader">Room Record Form</div>
</div>
<div class="clear"></div>
<div class="inputs">
	<form id="insertForm" class="insertForm" onsubmit="return valid.validateForm(this) ? processInsertForm() : false;">
		<fieldset class="formelements">
			<div class="legend">New Room Record Entry Form</div>
			<dl class="element">
				<dt style="width: 15%">
					<label for="roomName">Room Name :</label>
				</dt>
				<dd style="width: 30%">
					<input type="text" name="roomName" id="roomName" size="30" onchange="javascript: valid.validateInput(this);"
						title="Create New Room" class="required" />
					<div id="roomNameError" class="validationError" style="display: none"></div>	
				</dd>


				<dt style="width: 15%">
					<label for="roomNo">Room Number :</label>
				</dt>
				<dd style="width: 30%">
					<input type="text" name="roomNo" id="roomNo" size="30" onchange="javascript: valid.validateInput(this);"
						title="Enter the room number" class="required" />
					<div id="roomNoError" class="validationError" style="display: none"></div>	
				</dd>

			</dl>

			<dl class="element">
				<dt style="width: 15%">
					<label for="floorNO">Floor Number :</label>
				</dt>
				<dd style="width: 30%">
					<input type="text" name="floorNo" id="floorNo" size="30" onchange="javascript: valid.validateInput(this);"
						title="Enter the floor Number" class="required" />
					<div id="floorNoError" class="validationError" style="display: none"></div>	
				</dd>

				<dt style="width: 15%">
					<label for="buildingName">Building Name :</label>
				</dt>
				<dd style="width: 30%">
					<input type="hidden" name="buildingName_val" id="buildingName_val" 
						value="" /> <input type="text" name="buildingName"
						id="buildingName" size="30" title="Eneter The Building Name" onchange="javascript: valid.validateInput(this);"
						class="required autocomplete" />
					<div id="buildingNameError" class="validationError" style="display: none"></div>	
				</dd>
			</dl>
			<dl class="element">
				<dt style="width: 15%">
					<label for="seatingCapN">Seating Capacity Nor :</label>
				</dt>
				<dd style="width: 30%">
					<input type="text" name="seatingCapN" id="seatingCapN" size="30"
						maxlength="130" title="Input the Seating Capacity(Normal)" onchange="javascript: valid.validateInput(this);"
						class="required numeric" />
					<div id="seatingCapNError" class="validationError" style="display: none"></div>	
				</dd>

				<dt style="width: 15%">
					<label for="seatingCapE">Seating capacity E :</label>
				</dt>
				<dd style="width: 30%">
					<input type="text" name="seatingCapE" id="seatingCapE" size="30" onchange="javascript: valid.validateInput(this);"
						maxlength="130" title="Input the Seating Capacity(For Exam)"
						class="required numeric" />
					<div id="seatingCapEError" class="validationError" style="display: none"></div>	
				</dd>
			</dl>
			<dl class="element">
				<dt style="width: 15%">
					<label for="roomType">Room Type :</label>
				</dt>
				<dd style="width: 30%">
					<input type="hidden" name="roomType_val" id="roomType_val" value="" />
					<input type="text" name="roomType" id="roomType" size="30" onchange="javascript: valid.validateInput(this);"
						maxlength="130" title="Enter the Type of room"
						class="required autocomplete" />
					<div id="roomTypeError" class="validationError" style="display: none"></div>	
				</dd>


			</dl>
			<dl>
				<dt style="width: 15%">
					<label for="descriptionRoom">Description / Remarks :</label>
				</dt>
				<dd style="width: 30%">
					<textarea name="descriptionRoom" id="descriptionRoom" rows="4" onchange="javascript: valid.validateInput(this);"
						cols="60" title="Put The description of room" /></textarea>
					<div id="descriptionRoomError" class="validationError" style="display: none"></div>	
				</dd>
			</dl>
		</fieldset>
		<fieldset class="action buttons">
			<button type="button" name="submit" onclick="hideInsertForm()"
				accesskey="H" class="regular hide">
				<span class="underline">H</span>ide Insert Form
			</button>

			<button type="reset" name="insertReset" id="insertReset"
				accesskey="R" class="negative reset">
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
			<div class="legend">Update Room Record Details</div>
			<dl>
				<dt style="width: 15%">
					<label for="roomName_u">Room Name :</label>
				</dt>
				<dd style="width: 30%">
					<input type="text" name="roomName_u" id="roomName_u" size="30"
						maxlength="130" title="Update Room Name" class="required" onchange="javascript: valid.validateInput(this);" />
					<div id="roomName_uError" class="validationError" style="display: none"></div>	
				</dd>
				<dt style="width: 15%">
					<label for="roomNo_u">Room Number :</label>
				</dt>
				<dd style="width: 30%">
					<input type="text" name="roomNo_u" id="roomNo_u" size="30"
						maxlength="130" title="Update the room Number" onchange="javascript: valid.validateInput(this);"
						class="required" />
					<div id="roomNo_uError" class="validationError" style="display: none"></div>	
				</dd>
			</dl>
			<dl>
				<dt style="width: 15%">
					<label for="floorNo_u">Floor Nunber :</label>
				</dt>
				<dd style="width: 30%">
					<input type="text" name="floorNo_u" id="floorNo_u" size="30"
						maxlength="130" title="Input the floor Number" onchange="javascript: valid.validateInput(this);"
						class="required" />
					<div id="floorNo_uError" class="validationError" style="display: none"></div>	
				</dd>

				<dt style="width: 15%">
					<label for="buildingName_u">Building Name :</label>
				</dt>
				<dd style="width: 30%">
					<input type="hidden" name="buildingName_uval"
						id="buildingName_uval" value="" /> <input type="text"
						name="buildingName_u" id="buildingName_u" size="30" onchange="javascript: valid.validateInput(this);"
						title="Input The Building Name" class="required" />
					<div id="buildingName_uError" class="validationError" style="display: none"></div>	
				</dd>
				</dd>
			</dl>
			<dl>
				<dt style="width: 15%">
					<label for="seatingCapN_u">Seating capacity normal :</label>
				</dt>
				<dd style="width: 30%">
					<input type="text" name="seatingCapN_u" id="seatingCapN_u"
						size="30" maxlength="130" onchange="javascript: valid.validateInput(this);"
						title="Update the Seating Capacity(Normal)" class="required numeric" />
					<div id="seatingCapN_uError" class="validationError" style="display: none"></div>	
				</dd>

				<dt style="width: 15%">
					<label for="seatingCapE_u">Seating capacity exam :</label>
				</dt>
				<dd style="width: 30%">
					<input type="text" name="seatingCapE_u" id="seatingCapE_u"
						size="30" maxlength="130"
						title="Update the Seating Capacity(For Exam)" onchange="javascript: valid.validateInput(this);"
						class="required numeric" />
					<div id="seatingCapE_uError" class="validationError" style="display: none"></div>	
				</dd>
			</dl>
			<dl>
				<dt style="width: 15%">
					<label for="roomType_u">Room Type :</label>
				</dt>
				<dd style="width: 30%">
					<input type="hidden" name="roomType_uval" id="roomType_uval"
						value="" /> <input type="text" name="roomType_u" id="roomType_u"
						size="30" maxlength="130" title="Enter the Type of room" onchange="javascript: valid.validateInput(this);"
						class="required" />
					<div id="roomType_uError" class="validationError" style="display: none"></div>	
				</dd>
			</dl>
			<dl>
				<dt style="width: 15%">
					<label for="descriptionRoom_u">Description/Remarks :</label>
				</dt>

				<dd style="width: *">
					<textarea name="descriptionRoom_u" id="descriptionRoom_u" rows="4"
						cols="40" title="Update The description of room" onchange="javascript: valid.validateInput(this);" /></textarea>
					<div id="descriptionRoom_uError" class="validationError" style="display: none"></div>	
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

			<button type="button" name="submit" id="submit"
				onclick="hideUpdateForm()" class="regular hide">Hide Update Portion</button>
			<button type="submit" class="negative update">Update
				Room Detail</button>
		</fieldset>

	</form>
</div>
<div class="clear"></div>


<div class="display">
	<div id="roomDisplay">
		<fieldset class="displayElements">
			<div class="legend">
				<span id="legendDisplayDetail">Room Record Display Details</span>
			</div>
			<dl>
				<dt style="width: 15%;">
					<label for="roomName_d">Room Name : </label>
				</dt>
				<dd style="width: 30%">
					<span id="roomName_dDisplay"></span>
				</dd>
				<dt style="width: 15%">
					<label for="roomNo_d">Room Number :</label>
				</dt>
				<dd style="width: 30%">
					<span id="roomNo_dDisplay"></span>
				</dd>
			</dl>
			<dl>
				<dt style="width: 15%;">
					<label for="floorNo_d">Floor Number : </label>
				</dt>
				<dd style="width: 30%">
					<span id="floorNo_dDisplay"></span>
				</dd>
				<dt style="width: 15%;">
					<label for="buildingName_d">Building Name : </label>
				</dt>
				<dd style="width: 30%">
					<span id="buildingName_dDisplay"></span>
				</dd>
			</dl>
			<dl>
				<dt style="width: 15%;">
					<label for="seatingCapacityN_d">Seating Capacity(N) : </label>
				</dt>
				<dd style="width: 30%">
					<span id="seatingCapacityN_dDisplay"></span>
				</dd>
				<dt style="width: 15%;">
					<label for="seatingCapacityE_d">Seating capacity(E) : </label>
				</dt>
				<dd style="width: 30%">
					<span id="seatingCapacityE_dDisplay"></span>
				</dd>
			</dl>
			<dl>
				<dt style="width: 15%;">
					<label for="roomType_d">Room Type : </label>
				</dt>
				<dd style="width: 30%">
					<span id="roomType_dDisplay"></span>
				</dd>
				<dt style="width: 15%;">
					<label for="descriprionRoom_d">Description/Remarks : </label>
				</dt>
				<dd style="width: 30%">
					<span id="descriptionRoom_dDisplay"></span>
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
			<input type="hidden" name="valueId_d" id="valueId_d" value="" /> <input
				type="hidden" name="rowPosition_d" id="rowPosition_d" value="" />
			<button type="button" class="positive activate" name="activateRecord_d"
				id="activateRecord_d">Activate Record</button>
			<button type="button" class="negative drop" name="dropRecord_d"
				id="dropRecord_d">Drop Record</button>
			<button type="button" name="submit" id="submit" class="regular hide"
				onclick="hideDisplayPortion()">Hide Display Details Portion</button>
			<button type="button" class="positive edit" name="update_menu_button"
				id="update_menu_button">Update Room Detail</button>
		</fieldset>
	</div>
</div>
<div class="clear"></div>
<div class="inputs">
    <form id="searchForm" class="searchForm" onsubmit="return valid.validateForm(this) ? getRoomSearchDetails() : false;">
        <fieldset class="formelements">
            <div class="legend">Search Room List</div>
            <dl>
                <dt style="width: 15%">
                    <label for="room_hint">Room hint :</label>
                </dt>
                <dd style="width: 30%">
                    <input type="text" name="room_hint" id="room_hint"
                           style="width: 200px" title="Enter The Room Hint" />
                </dd>
                <dt style="width: 15%">
                    <label for="search_type">Search Type :</label>
                </dt>
                <dd>
                    <select name="search_type" id="search_type" style="width: 150px">
                        <option value="all">All Rooms</option>
                        <option value="1" selected="selected">Active Rooms</option>
                        <option value="0">In-Active Rooms</option>
                    </select>
                </dd>
            </dl>
        </fieldset>
        <fieldset class="action buttons">
            <button type="button" class="regular toggle" name="toggleInsert" id="toggleInsert"
                    onclick="toggleInsertForm()">Toggle Insert Form</button>
            <button type="submit" name="searchData" class="positive search" id="searchData">Get Search Results</button>

        </fieldset>
    </form>

</div>
<div class="clear"></div>

<div id="displayDatatable" class="datatable buttons">
	<fieldset class="tableElements">
		<div class="legend">
			<span>Room Record Tabulated View</span>
		</div>
		<table  class="display"
			id="groupRooms">
			<thead>
				<tr>
					<th>Room Name</th>
					<th>Room Number</th>
					<th>Building Name</th>
					<th style="width: 150px">View Details</th>
					<th style="width: 150px">Edit Details</th>

				</tr>
			</thead>
			<tbody>

			</tbody>
		</table>
	</fieldset>

</div>
<?php
$body->endBody ( 'global', 'default' );
?>