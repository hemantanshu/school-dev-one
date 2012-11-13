<?php
require_once 'config.php';
require_once BASE_PATH . 'include/global/class.body.php';
require_once BASE_PATH . 'include/utility/class.sections.php';
require_once BASE_PATH . 'include/global/class.options.php';

$body = new body ();
$sections = new sections ();
$options = new options();

$body->startBody ( 'exam', 'LMENUL99', 'Examination Class Assignment' );
$examinationId = $_GET ['examinationId'];
$sessionId = $_GET ['sessionId'];

$details = $body->getTableIdDetails ( $examinationId );
$sessionDetails = $body->getTableIdDetails ( $details ['session_id'] );
$classIdDetails = $sections->getCurrentSessionClassNameIds ( '', 1 );

$assignmentIds = $options->getAssignmentIds($examinationId, 'CLSSA', 1);
$assignedSections = array();
foreach($assignmentIds as $assignmentId){
	$assignmentDetails = $options->getTableIdDetails($assignmentId);
	array_push($assignedSections, $assignmentDetails['value_set']);
}
?>
<input type="hidden" value="<?php echo $examinationId; ?>"
	name="examinationId" id="examinationId" />
<input type="hidden" value="<?php echo $sessionId; ?>" name="sessionId"
	id="sessionId" />
<div id="content_header">
	<div id="pageButton" class="buttons">
		<button type="button" class="regular toggle"
			onclick="showHideSearchForm()">
			<span class="underline">T</span>oggle Search Form
		</button>
		<button type="button" class="regular toggle"
			onclick="showHideDatatable()">
			<span class="underline">T</span>oggle Tabulated Data
		</button>
	</div>
	<div id="contentHeader">Examination Class Assignment Form</div>
</div>
<div class="clear"></div>
<div class="display">
	<div id="displaySubjectRecord">
		<fieldset class="displayElements">
			<dl>
				<dt style="width: 15%;">
					<label for="examinationName">Examination Name :</label>
				</dt>
				<dd style="width: 30%">
					<span id="examinationName"><?php echo $details['examination_name']; ?></span>
				</dd>
				<dt style="width: 15%">
					<label for="sessionName">Session Name :</label>
				</dt>
				<dd style="width: 30%">
					<span id="sessionName"><?php echo $sessionDetails['session_name']; ?></span>
				</dd>
			</dl>
		</fieldset>
	</div>
</div>

<div class="inputs">
	<form id="insertForm" class="insertForm"
		onsubmit="return valid.validateForm(this) ? processInsertForm() : false;">
		<fieldset class="formelements">
			<div class="legend">
				<span>Assign Class To Exam</span>
			</div>
			<dl class="element">
				<dt style="width: 15%">
					<label for="classId">Class Name :</label>
				</dt>
				<dd style="width: 30%">
					<select name="sectionId" id="sectionId" class="required"
						style="width: 200px" onchange="javascript: valid.validateInput(this);">
                        <?php
							foreach ( $classIdDetails as $classId ) {
								$sectionIds = $sections->getClassSectionIds ( $classId [0], 1 );
								foreach ( $sectionIds as $sectionId ) {
									if(!in_array($sectionId, $assignedSections)){
										$details = $body->getTableIdDetails ( $sectionId );
										echo "<option id=\"".$sectionId."\" value=\"" . $sectionId . "\">" . $classId [1] . " " . $details ['section_name'] . "</option>";
									}									
								}							
							}
						?>
                    </select>
					<div id="sectionIdError" class="validationError"
						style="display: none"></div>
				</dd>
			</dl>
		</fieldset>
		<fieldset class="action buttons">
			<button type="button" name="submit" onclick="hideInsertForm()"
				class="regular hide" accesskey="H">
				<span class="underline">H</span>ide Insert Form
			</button>

			<button type="reset" name="insertReset" id="insertReset"
				class="negative reset" accesskey="R">
				<span class="underline">R</span>eset Form Fields
			</button>

			<button type="submit" accesskey="I" class="positive insert">
				<span class="underline">I</span>nsert New Record
			</button>
		</fieldset>
	</form>
</div>

<div class="clear"></div>
<div class="display">
	<div id="displayRecord" style="display: none">
		<fieldset class="displayElements">
			<div class="legend">
				<span id="legendDisplayAssignment">Examination Class Details : </span>
			</div>
			<dl>
				<dt style="width: 15%;">
					<label for="classDisplay">Class Name : </label>
				</dt>
				<dd style="width: 30%">
					<span id="classDisplay"></span>
				</dd>
				<dt style="width: 15%">
					<label for="sectionDisplay">Section Name :</label>
				</dt>
				<dd style="width: 30%">
					<span id="sectionDisplay"></span>
				</dd>
			</dl>
			<dl>
				<dt style="width: 15%;">
					<label for="lastUpdateDateDisplay">Last Update Date : </label>
				</dt>
				<dd style="width: 30%">
					<span id="lastUpdateDateDisplay"></span>
				</dd>
				<dt style="width: 15%">
					<label for="lastUpdatedByDisplay">Updated BY :</label>
				</dt>
				<dd style="width: 30%">
					<span id="lastUpdatedByDisplay"></span>
				</dd>
			</dl>
			<dl>
				<dt style="width: 15%;">
					<label for="creationDateDisplay">Creation Date : </label>
				</dt>
				<dd style="width: 30%">
					<span id="creationDateDisplay"></span>
				</dd>
				<dt style="width: 15%">
					<label for="createdByDisplay">Created BY :</label>
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
			<button type="button" class="positive activate"
				name="activateRecord_d" id="activateRecord_d">Activate
				Record</button>
			<button type="button" class="negative drop" name="dropRecord_d"
				id="dropRecord_d">Drop Record</button>
			<button type="button" name="submit" id="submit" class="regular hide"
				onclick="hideDisplayPortion()">Hide Display Details Portion</button>
		</fieldset>
	</div>
</div>
<div class="clear"></div>
<div class="inputs">
	<form id="searchForm" class="searchForm"
		onsubmit="return valid.validateForm(this) ? getSearchResults() : false;">
		<fieldset class="formelements">
			<div class="legend">Search Class Association</div>
			<dl>
				<dt style="width: 15%"></dt>
				<dd style="width: 30%"></dd>
				<dt style="width: 10%">
					<label for="search_type">Search Type :</label>
				</dt>
				<dd>
					<select name="search_type" id="search_type" style="width: 150px">
						<option value="all">All Records</option>
						<option value="1" selected="selected">Active Records</option>
						<option value="0">In-Active Records</option>
					</select>
				</dd>
			</dl>
		</fieldset>
		<fieldset class="action buttons">
			<button type="button" name="toggleInsert1" id="toggleInsert1"
				onclick="toggleInsertForm()" class="regular toggle">Toggle Insert
				Form</button>
			<button type="submit" name="toggleInsert1" id="toggleInsert1"
				class="positive search">Get Search Results</button>
		</fieldset>
	</form>
</div>


<div class="clear"></div>

<div id="displayDatatable" class="buttons">
	<div class="datatable" id="groupMenusM">
		<fieldset>
			<div class="legend">
				<span>Examination Class-Section Assignment</span>
			</div>
			<table class="display" id="groupRecords">
				<thead>
					<tr>
						<th>Class</th>
						<th>Section</th>
						<th>Assignment Date</th>
						<th>Assignment Officer</th>
						<th style="width: 150px">View Details</th>
					</tr>
				</thead>
				<tbody>

				</tbody>
			</table>
		</fieldset>
	</div>
</div>