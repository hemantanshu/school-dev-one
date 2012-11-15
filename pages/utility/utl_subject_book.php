<?php
require_once 'config.php';
require_once BASE_PATH . 'include/global/class.body.php';
require_once BASE_PATH . 'include/utility/class.subject.php';

$body = new body ();
$subject = new subjects ();
$body->startBody ( 'utility', 'LMENUL19', 'Subject Book Mapping' );

$subjectId = $_GET ['subjectId'];
$details = $subject->getSubjectIdDetails ( $subjectId );

if ($details [1] == "")
	exit(0);


?>
<input type="hidden" name="subject_global" id="subject_global"
	value="<?php echo $details['id']; ?>" />
<div id="content_header">
    <div id="pageButton" class="buttons">
        <button type="button" class="regular toggle" onclick="showHideSearchForm()"><span class="underline">T</span>oggle Search Form</button>
        <button type="button" class="regular toggle" onclick="showHideDatatable()"><span class="underline">T</span>oggle Tabulated Data</button>
    </div>
    <div id="contentHeader">Book Association For Subject <?php echo $details['subject_name'];?></div>
</div>
<div class="inputs">
	<form id="insertForm" class="insertForm"
		onsubmit="return valid.validateForm(this) ? processInsertForm() : false;">
		<fieldset class="formelements">
			<div class="legend">
				<span>New Book Record Entry Form</span>
			</div>
			<dl class="element">
				<dt style="width: 15%">
					<label for="bookName">Book Name :</label>
				</dt>
				<dd style="width: 30%">
					<input type="hidden" name="bookName_val" id="bookName_val" value="" />
					<input type="text" name="bookName" id="bookName" class="required autocomplete"
						onchange="javascript: valid.validateInput(this);"
						title="Enter The Book Name" value="" size="40" />
					<div id="bookNameError" class="validationError"
						style="display: none"></div>
				</dd>
				<dt style="width: 15%">

					<label for="bookType">Book Type :</label>
				</dt>
				<dd style="width: 30%">
					<select size="1" name="bookType" id="bookType" class="required"
						onchange="javascript: valid.validateInput(this);">
						<option value="y">Core Book</option>
						<option value="n">Reference Book</option>
					</select>
					<div id="bookTypeError" class="validationError"
						style="display: none"></div>
				</dd>
			</dl>
			<dl class="element">
				<dt style="width: 15%">

					<label for="priority">Priority :</label>
				</dt>
				<dd style="width: 30%">
					<input type="text" name="priority" id="priority"
						class="required numeric"
						title="Enter The Priority Of corresponding Book" value=""
						onchange="javascript: valid.validateInput(this);" size="20" />
					<div id="priorityError" class="validationError"
						style="display: none"></div>
					</textarea>
				</dd>

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
<div class="inputs">
	<form id="updateForm" class="updateForm"
		onsubmit="return valid.validateForm(this) ? processUpdateForm() : false;">
		<fieldset class="formelements">
			<div class="legend">
				<span id="legend_editForm">Update Book Record Details</span>
			</div>
			<dl class="element">
				<dt style="width: 15%">

					<label for="bookName_e">Book Name :</label>
				</dt>
				<dd style="width: 30%">
					<input type="hidden" name="bookName_eval" id="bookName_eval"
						value="" /> <input type="text" name="bookName_e" id="bookName_e"
						class="required" title="Edit The Book Name" value=""
						size="40" />
					<div id="bookName_eError" class="validationError"
						style="display: none"></div>
				</dd>
				<dt style="width: 15%">

					<label for="bookType_e">Book Type :</label>
				</dt>
				<dd style="width: 30%">
					<select size="1" name="bookType_e" id="bookType_e" class="required">
						<option value="y">Core Book</option>
						<option value="n">Reference Book</option>
					</select>
					<div id="bookType_eError" class="validationError"
						style="display: none"></div>

				</dd>
			</dl>
			<dl class="element">
				<dt style="width: 15%">

					<label for="priority_e">Priority :</label>
				</dt>
				<dd style="width: 30%">
					<input type="text" name="priority_e" id="priority_e"
						class="required"
						title="Edit The Priority Of corresponding Book" value=""
						size="20" />
					<div id="priority_eError" class="validationError"
						style="display: none"></div>
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
	<div id="displayRecord">
		<fieldset class="displayElements">
			<div class="legend">
				<span id="legendDisplayDetail">Subject Book Association Record Details : </span>
			</div>
			<dl>
				<dt style="width: 15%;">
					<label for="assId_d">Association ID : </label>
				</dt>
				<dd style="width: 30%">
					<span id="assId_dDisplay"></span>
				</dd>
				<dt style="width: 15%">
					<label for="subName_d">Subject Name :</label>
				</dt>
				<dd style="width: 30%">
					<span id="subName_dDisplay"></span>
				</dd>
			</dl>
			<dl>
				<dt style="width: 15%;">
					<label for="bookName_d">Book Name : </label>
				</dt>
				<dd style="width: 30%">
					<span id="bookName_dDisplay"></span>
				</dd>
				<dt style="width: 15%;">
					<label for="bookType_d">Book Type : </label>
				</dt>
				<dd style="width: 30%">
					<span id="bookType_dDisplay"></span>
				</dd>
			</dl>
			<dl>
				<dt style="width: 15%;">
					<label for="authorName_d">Author Name : </label>
				</dt>
				<dd style="width: 30%">
					<span id="authorName_dDisplay"></span>
				</dd>
				<dt style="width: 15%;">
					<label for="publication_d">Publication : </label>
				</dt>
				<dd style="width: 30%">
					<span id="publication_dDisplay"></span>
				</dd>
			</dl>
			<dl>
				<dt style="width: 15%;">
					<label for="priority_d">Priority : </label>
				</dt>
				<dd style="width: 30%">
					<span id="priority_dDisplay"></span>
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
			<button type="button" class="regular hide" name="submit" id="submit"
				onclick="hideDisplayForm()">Hide Display Details Portion</button>
		</fieldset>

	</div>
</div>
<div class="clear"></div>
<div class="inputs">
	<form id="searchForm" class="searchForm"
		onsubmit="return valid.validateForm(this) ? getSearchResults() : false;">
		<fieldset class="formelements">
			<div class="legend">Search Value</div>
			<dl>
				<dt style="width: 15%">
					<label for="option_hint">Book hint :</label>
				</dt>
				<dd style="width: 30%">
					<input type="text" name="book_hint" id="book_hint" class=""
						style="width: 200px" title="Enter The book Hint" />
				</dd>
				<dt style="width: 15%">
					<label for="search_type">Search Type :</label>
				</dt>
				<dd>
					<select name="search_type" id="search_type" style="width: 150px">
						<option value="all">All Books</option>
						<option value="1">Active Books</option>
						<option value="0">In-Active Books</option>
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
	<fieldset>
		<div class="legend">
			<span>Tabulated Listing Of Books</span>
		</div>
		<table  class="display"
			id="groupBook">
			<thead>
				<tr>
					<th>Book Name</th>
					<th>Author</th>
					<th>Publication</th>
					<th style="width: 50px;">Priority</th>
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
$body->endBody ( 'global', 'default' );
?>
