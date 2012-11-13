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
$body->startBody ( 'utility', 'LMENUL18', 'Subject Details Entry Form' );
?>
<div id="content_header">
    <div id="pageButton" class="buttons">
        <button type="button" class="regular toggle" onclick="showHideSearchForm()"><span class="underline">T</span>oggle Search Form</button>
        <button type="button" class="regular toggle" onclick="showHideDatatable()"><span class="underline">T</span>oggle Tabulated Data</button>
    </div>
    <div id="contentHeader">Subject Record Details Form</div>
</div>
<div class="inputs">
	<form id="insertForm" class="insertForm" onsubmit="return valid.validateForm(this) ? processInsertForm() : false;">
		<fieldset class="formelements">
			<div class="legend">
				<span>New Subject Entry</span>
			</div>
			<dl class="element">
				<dt style="width: 15%">

					<label for="subCode">Subject Code :</label>
				</dt>
				<dd style="width: 30%">
					<input type="text" name="subCode" id="subCode" class="required" onchange="javascript: valid.validateInput(this);"
						title="Enter The Subject Code" value="" size="40" />
					<div id="subCodeError" class="validationError" style="display: none"></div>	
				</dd>
				<dt style="width: 15%">

					<label for="subName">Subject Name :</label>
				</dt>
				<dd style="width: 30%">
					<input type="text" name="subName" id="subName" class="required" onchange="javascript: valid.validateInput(this);"
						title="Enter The Subject Name" value="" size="40" />
					<div id="subNameError" class="validationError" style="display: none"></div>	
				</dd>
			</dl>
			<dl class="element">
				<dt style="width: 15%">

					<label for="subjectOrder">Subject Order :</label>
				</dt>
				<dd style="width: 30%">
					<input type="text" name="subjectOrder" id="subjectOrder" class="required" onchange="javascript: valid.validateInput(this);"
						title="Enter The Subject Code" value="" size="15" />
					<div id="subjectOrderError" class="validationError" style="display: none"></div>	
				</dd>
			</dl>			
		</fieldset>
		<fieldset class="action buttons">
			<button type="button" name="submit" class="regular hide" onclick="hideInsertForm()">Hide
				Insert Form</button>
			<button type="reset" name="insertReset" id="insertReset" class="negative reset">Reset Form
				Fields</button>
			<button type="submit" name="submit" id="submit" class="positive insert">Insert New Record</button>
		</fieldset>
	</form>
</div>


<div class="clear"></div>
<div class="inputs">
	<form id="updateForm" class="updateForm" onsubmit="return valid.validateForm(this) ? processUpdateForm() : false;">
		<fieldset class="formelements">
			<div class="legend">
				<span id="legend_updateForm">Update Subject Details </span>
			</div>
			<dl class="element">
				<dt style="width: 15%">

					<label for="subCode_u">Subject Code :</label>
				</dt>
				<dd style="width: 30%">
					<input type="text" name="subCode_u" id="subCode_u" class="required" onchange="javascript: valid.validateInput(this);"
						title="Update The Subject Code" value="" size="40" />
					<div id="subCode_uError" class="validationError" style="display: none"></div>	
				</dd>
				<dt style="width: 15%">

					<label for="subName_u">Subject Name :</label>
				</dt>
				<dd style="width: 30%">
					<input type="text" name="subName_u" id="subName_u" class="required" onchange="javascript: valid.validateInput(this);"
						title="Input The Subject Name"
						value="" size="40" />
					<div id="subName_uError" class="validationError" style="display: none"></div>	
				</dd>
			</dl>
			<dl class="element">
				<dt style="width: 15%">
					<label for="subjectOrde_u">Subject Order :</label>
				</dt>
				<dd style="width: 30%">
					<input type="text" name="subjectOrder_u" id="subjectOrder_u" class="required" onchange="javascript: valid.validateInput(this);"
						title="Enter The Subject Code" value="" size="15" />
					<div id="subjectOrder_uError" class="validationError" style="display: none"></div>	
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
	<div id="subdisplay">
		<fieldset class="displayElements">
			<div class="legend">
				<span id="legendDisplayDetail">Subject Detail : </span>
			</div>
			<dl>
				<dt style="width: 15%;">
					<label for="subId_d">Subject ID : </label>
				</dt>
				<dd style="width: 30%">
					<span id="subId_dDisplay"></span>
				</dd>
				<dt style="width: 15%">
					<label for="subCode_d">Subject Code :</label>
				</dt>
				<dd style="width: 30%">
					<span id="subCode_dDisplay"></span>
				</dd>
			</dl>
			<dl>
				<dt style="width: 15%;">
					<label for="subName_d">Subject Name : </label>
				</dt>
				<dd style="width: 30%">
					<span id="subName_dDisplay"></span>
				</dd>
				<dt style="width: 15%;">
					<label for="subjectOrder_d">Subject Order : </label>
				</dt>
				<dd style="width: 30%">
					<span id="subjectOrder_dDisplay"></span>
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
			<button type="button" class="positive edit" id="editRecordButton"
				class="editRecordButton">Update Book Detail</button>
			<button type="button" name="submit" class="regular hide" id="submit"
				onclick="hideDisplayForm()">Hide Display Details Portion</button>
		</fieldset>


	</div>
</div>
<div class="clear"></div>
<div class="inputs">
	<form id="searchForm" class="searchForm" onsubmit="return valid.validateForm(this) ? getSearchResults() : false;">
		<fieldset class="formelements">
			<div class="legend">Search Value </div>
			<dl>
				<dt style="width: 15%">
					<label for="option_hint">Subject hint :</label>
				</dt>
				<dd style="width: 30%">
					<input type="text" name="subject_hint" id="subject_hint"
						class="" style="width: 200px"
						title="Enter The Subject Hint" />
				</dd>
				<dt style="width: 10%">
					<label for="search_type">Search Type :</label>
				</dt>
				<dd>
					<select name="search_type" id="search_type" style="width: 150px">
						<option value="all">All Subjects</option>
						<option value="1" selected="selected">Active Subjects</option>
						<option value="0">In-Active Subjects</option>
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
<div class="datatable buttons" id="displayDatatable">
	<fieldset class="tableElements">
		<div class="legend">
			<span>Tabulated Listing Of Subjects</span>
		</div>
		<table  class="display"
			id="groupSubjects">
			<thead>
				<tr>
					<th>Code</th>
					<th>Subject Name</th>
					<th>Order</th>					
					<th style="width: 180px;">Books Associated</th>
					<th style="width: 150px;">View Details</th>
					<th style="width: 150px;">Edit Details</th>
				</tr>
			</thead>
			<tbody>

			</tbody>
		</table>
	</fieldset>

</div>
