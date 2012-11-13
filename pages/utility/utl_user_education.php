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
$body->startBody ( 'utility', 'LMENUL40', 'Employee Education Details Entry Page' );

$userId = $_GET ['userId'];
?>
<div id="content_header">
    <div id="pageButton" class="buttons">
        <button type="button" class="regular toggle" onclick="showHideSearchForm()"><span class="underline">T</span>oggle Search Form</button>
        <button type="button" class="regular toggle" onclick="showHideDatatable()"><span class="underline">T</span>oggle Tabulated Data</button>
    </div>
    <div id="contentHeader">Employee Education Details Form</div>
</div>
<input type="hidden" name="userId"
	value="<?php echo $userId; ?>" id="userId" />

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
				<span>New Education History Entry Portion</span>
			</div>
			<dl class="element">
				<dt style="width: 15%">
					<label for="institute">Institute Name :</label>
				</dt>
				<dd style="width: 80%">
					<span style="float: left"><input type="hidden" name="institute_val" id="institute_val"
						value="" /> <input type="text" name="institute" id="institute"
						class="required" tabindex="1" size="30"
						onchange="javascript: valid.validateInput(this);"
						title="Select The Institute Name" /> </span>	<span style="float: left; padding-left: 20px;">
                    <button type="button" class="negative insert" onclick="addNewInstitute()" style="padding: 0 5px 2px 5px">Add New Institute</button></span>
					<div id="instituteError" class="validationError"
						style="display: none"></div>
				</dd>
			</dl>
			<dl class="element">
				<dt style="width: 15%">
					<label for="level">Class / Level :</label>
				</dt>
				<dd style="width: 30%">
					<input type="text" name="level" id="level" class="required"
						tabindex="2" size=""
						onchange="javascript: valid.validateInput(this);"
						title="Enter the level/class" />
					<div id="levelError" class="validationError" style="display: none"></div>
				</dd>
				<dt style="width: 15%">
					<label for="year">Passing Year :</label>
				</dt>
				<dd style="width: 30%">
					<input type="text" name="year" id="year" class="required numeric"
						tabindex="3" size="required numeric"
						onchange="javascript: valid.validateInput(this);"
						title="Enter the year of passing" />
					<div id="yearError" class="validationError" style="display: none"></div>
				</dd>
			</dl>
			<dl class="element">
				<dt style="width: 15%">
					<label for="score">Score :</label>
				</dt>
				<dd style="width: 30%">
					<input type="text" name="score" id="score" class="required numeric"
						tabindex="4" onchange="javascript: valid.validateInput(this);"
						title="Score of the exam" />
					<div id="scoreError" class="validationError" style="display: none"></div>
				</dd>
				<dt style="width: 15%">
					<label for="">Scoring System :</label>
				</dt>
				<dd style="width: 30%">
					<select name="markType" id="markType" style="width: 250px;"
						tabindex="5" class="required">
						<?php
						$valueIds = $options->getOptionSearchValueIds ( '', 'MSCHM', 1 );
						foreach ( $valueIds as $optionId ) {
							echo "<option value=\"" . $optionId . "\">" . $options->getOptionIdValue ( $optionId ) . "</option>";
						}
						?>		
						</select>
					<div id="markTypeError" class="validationError"
						style="display: none"></div>
				</dd>
			</dl>
			<dl class="element">
				<dt style="width: 15%"><label for="remarks">Comments :</label>	</dt>
				<dd style="width: 80%">
						<textarea name="comments" id="comments" rows="3" cols="80" tabindex="5"></textarea></dd>				
			</dl>
		</fieldset>
        <fieldset class="action buttons">
            <button type="button" name="submit" onclick="hideInsertForm()" class="regular hide"
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
<div id="inputs" class="buttons">
	<form id="updateForm" class="updateForm"
		onsubmit="return processUpdateForm()">
		<fieldset class="formelements">
			<div class="legend">
				<span id="legend_updateForm">Update Education History Details </span>
			</div>
			<dl class="element">
				<dt style="width: 15%">
					<label for="institute">Institute Name :</label>
				</dt>
				<dd style="width: 80%">
					<span style="float: left"><input type="hidden" name="institute_val" id="institute_val"
                                                     value="" /><input type="hidden" name="institute_uval" id="institute_uval"
						value="" /> <input type="text" name="institute_u" id="institute_u"
						class="required" tabindex="6" size="30"
						onchange="javascript: valid.validateInput(this);"
						title="Select The Institute Name" /></span><span style="float: left; padding-left: 20px;">
                        <button type="button" class="negative insert" onclick="addNewInstitute()" style="padding: 0 5px 2px 5px">Add New Institute</button></span>
					<div id="institute_uError" class="validationError"
						style="display: none"></div>
				</dd>
			</dl>
			<dl class="element">
				<dt style="width: 15%">
					<label for="level">Class / Level :</label>
				</dt>
				<dd style="width: 30%">
					<input type="text" name="level_u" id="level_u" class="required"
						tabindex="7" size=""
						onchange="javascript: valid.validateInput(this);"
						title="Enter the level/class" />
					<div id="level_uError" class="validationError"
						style="display: none"></div>
				</dd>
				<dt style="width: 15%">
					<label for="year">Passing Year :</label>
				</dt>
				<dd style="width: 30%">
					<input type="text" name="year_u" id="year_u"
						class="required numeric" tabindex="8" size="required numeric"
						onchange="javascript: valid.validateInput(this);"
						title="Enter the year of passing" />
					<div id="year_uError" class="validationError" style="display: none"></div>
				</dd>
			</dl>
			<dl class="element">
				<dt style="width: 15%">
					<label for="score">Score :</label>
				</dt>
				<dd style="width: 30%">
					<input type="text" name="score_u" id="score_u"
						class="required numeric" tabindex="9"
						onchange="javascript: valid.validateInput(this);"
						title="Score of the exam" />
					<div id="score_uError" class="validationError"
						style="display: none"></div>
				</dd>
				<dt style="width: 15%">
					<label for="">Scoring System :</label>
				</dt>
				<dd style="width: 30%">
					<select name="markType_u" id="markType_u" style="width: 250px;"
						tabindex="10" class="required">
						<?php
						$valueIds = $options->getOptionSearchValueIds ( '', 'MSCHM', 1 );
						foreach ( $valueIds as $optionId ) {
							echo "<option value=\"" . $optionId . "\">" . $options->getOptionIdValue ( $optionId ) . "</option>";
						}
						?>		
						</select>
					<div id="markType_uError" class="validationError"
						style="display: none"></div>
				</dd>
			</dl>
			<dl class="element">
				<dt style="width: 15%"><label for="remarks">Comments :</label>	</dt>
				<dd style="width: 80%">
						<textarea name="comments_u" id="comments_u" rows="3" cols="80" tabindex="5"></textarea></dd>				
			</dl>
		</fieldset>

		<fieldset class="action buttons">
            <input type="hidden" name="recordId_u" tabindex="20" id="recordId_u"
                   value="" /> <input type="hidden" name="position_u" tabindex="21"
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
				<span id="legendDisplayDetail">Education History Details</span>
			</div>
			<dl>
				<dt style="width: 15%;">
					<label for="institute">Institute Name :</label>
				</dt>
				<dd style="width: 30%">
					<span id="institute_d"></span>
				</dd>
				<dt style="width: 15%">
					<label for="university">University / Board :</label>
				</dt>
				<dd style="width: 30%">
					<span id="university_d"></span>
				</dd>
			</dl>
			<dl>
				<dt style="width: 15%;">
					<label for="level">Class / Level :</label>
				</dt>
				<dd style="width: 30%">
					<span id="level_d"></span>
				</dd>
				<dt style="width: 15%">
					<label for="year">Passing Year :</label>
				</dt>
				<dd style="width: 30%">
					<span id="year_d"></span>
				</dd>
			</dl>
			<dl>
				<dt style="width: 15%;">
					<label for="score">Score :</label>
				</dt>
				<dd style="width: 30%">
					<span id="score_d"></span>
				</dd>
				<dt style="width: 15%">
					<label for="markingType">Scoring Type :</label>
				</dt>
				<dd style="width: 30%">
					<span id="markType_d"></span>
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
                    id="submit" onclick="hideDetailsPortion()">
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
				<dt style="width: 10%">
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
            <button accesskey="S" type="submit" name="submitSearch" tabindex="33"
                    id="submitSearch" class="positive search">
                Get <span class="underline">S</span>earch Results
            </button>
        </fieldset>
	</form>

</div>
<div class="clear"></div>
<div id="displayDatatable" class="datatable buttons">
	<fieldset class="tableElements">
		<div class="legend">
			<span>Education History Tabulated Listing</span>
		</div>
		<table  class="display"
			id="groupRecord">
			<thead>
				<tr>
					<th>Level</th>
					<th>Institute</th>
					<th>Year</th>
					<th>Score</th>
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
