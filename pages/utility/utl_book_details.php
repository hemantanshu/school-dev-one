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
$body->startBody ( 'utility', 'LMENUL17', 'Book Details Entry Page' );
?>
<div id="content_header">
    <div id="pageButton" class="buttons">
        <button type="button" class="regular toggle" onclick="showHideSearchForm()"><span class="underline">T</span>oggle Search Form</button>
        <button type="button" class="regular toggle" onclick="showHideDatatable()"><span class="underline">T</span>oggle Tabulated Data</button>
    </div>
    <div id="contentHeader">Book Record Details Entry Form</div>
</div>
<div class="inputs">
	<form id="insertForm" class="insertForm"
		onsubmit="return valid.validateForm(this) ? processInsertForm() : false;">
		<fieldset class="formelements">
			<div class="legend">
				<span>Insert Form</span>
			</div>
			<dl class="element">
				<dt style="width: 15%">

					<label for="bookName">Book Name :</label>
				</dt>
				<dd style="width: 30%">

					<input type="text" name="bookName" id="bookName" class="required"
						title="Enter The Book Name" value="" size="40"
						onchange="javascript: valid.validateInput(this);" />
					<div id="bookNameError" class="validationError"
						style="display: none"></div>
				</dd>
				<dt style="width: 15%">

					<label for="authorName">Author Name :</label>
				</dt>
				<dd style="width: 30%">
					<input type="hidden" name="authorName_val" id="authorName_val"
						value="" /> <input type="text" name="authorName" id="authorName"
						onchange="javascript: valid.validateInput(this);" class="required"
						title="Enter The Author Name Of corresponding Book" value=""
						size="40" />
					<div id="authorNameError" class="validationError"
						style="display: none"></div>
				</dd>
			</dl>
			<dl class="element">
				<dt style="width: 15%">
					<label for="publication">Publication :</label>
				</dt>
				<dd style="width: 30%">
					<input type="hidden" name="publication_val" id="publication_val"
						value="" /> <input type="text" name="publication" id="publication"
						class="required"
						title="Enter The Publication Name Of corresponding Book"
						onchange="javascript: valid.validateInput(this);" value=""
						size="40" />
					<div id="publicationError" class="validationError"
						style="display: none"></div>
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

			<button type="submit" accesskey="I" class="positive insert">
				<span class="underline" title='I'>I</span>nsert New Record
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
				<span id="legend_updateForm">Update Insert Form </span>
			</div>
			<dl class="element">
				<dt style="width: 15%">

					<label for="bookName_u">Book Name :</label>
				</dt>
				<dd style="width: 30%">

					<input type="text" name="bookName_u" id="bookName_u"
						class="required" title="Update The Book Name" value=""
						onchange="javascript: valid.validateInput(this);" size="40" />
					<div id="bookName_uError" class="validationError"
						style="display: none"></div>
				</dd>
				<dt style="width: 15%">

					<label for="authorName_u">Author Name :</label>
				</dt>
				<dd style="width: 30%">
					<input type="hidden" name="authorName_uval" id="authorName_uval"
						value="" /> <input type="text" name="authorName_u"
						id="authorName_u" class="required"
						title="Update The Author Name Of corresponding Book"
						value="" size="40"
						onchange="javascript: valid.validateInput(this);" />
					<div id="authorName_uError" class="validationError"
						style="display: none"></div>
				</dd>
			</dl>
			<dl class="element">
				<dt style="width: 15%">

					<label for="publication_u">Publication :</label>
				</dt>
				<dd style="width: 30%">
					<input type="hidden" name="publication_uval" id="publication_uval"
						value="" /> <input type="text" name="publication_u"
						id="publication_u" class="required"
						title="Update The Publication Name Of corresponding Book"
						value="" size="40"
						onchange="javascript: valid.validateInput(this);" />
					<div id="publication_uError" class="validationError"
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
			<button type="submit" class="positive update" accesskey="U">
				<span class="underline">U</span>pdate Record
			</button>
		</fieldset>
	</form>
</div>

<div class="clear"></div>
<div class="display">
	<div id="bookdisplay">
		<fieldset class="displayElements">
			<div class="legend">
				<span id="legendDisplayDetail">Display Detail : </span>
			</div>
			<dl>
				<dt style="width: 15%;">
					<label for="bookId_d">Book ID : </label>
				</dt>
				<dd style="width: 30%">
					<span id="bookId_dDisplay"></span>
				</dd>
				<dt style="width: 15%">
					<label for="bookName_d">Book Name :</label>
				</dt>
				<dd style="width: 30%">
					<span id="bookName_dDisplay"></span>
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
				id="activateRecord_d">Activate Book Detail</button>
			<button type="button" class="negative drop" name="dropRecord_d"
				id="dropRecord_d">Drop Book Detail</button>
			<button type="button" class="positive edit" id="editRecordButton"
				class="editRecordButton">Update Book Detail</button>
			<button type="button" name="submit" class="regular hide"
				onclick="hideDisplayPortion()">Hide Display Details Portion</button>
		</fieldset>
	</div>
</div>
<div class="clear"></div>
<div class="inputs">
	<form id="searchForm" class="searchForm" onsubmit="return valid.validateForm(this) ? getSearchResults() : false;">
		<fieldset class="formelements">
			<div class="legend">Search Book Records</div>
			<dl>
				<dt style="width: 15%">
					<label for="option_hint">Book Name :</label>
				</dt>
				<dd style="width: 30%">
					<input type="text" name="book_hint" id="book_hint" class=""
						style="width: 200px" title="Enter The book Hint" />
				</dd>
				<dt style="width: 15%">
					<label for="search_type">Search Type :</label>
				</dt>
				<dd>
					<select name="search_type" id="search_type" style="width: 150px" title="Select The Book Type">
						<option value="all">All Books</option>
						<option value="1" selected="selected">Active Books</option>
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
<div id="datatable"class="buttons">
    <div id="bookDatatable">
        <fieldset class="tableElements">
            <div class="legend">
                <span>Tabulated Book Record Listing</span>
            </div>
            <table  class="display"
                   id="groupBooks">
                <thead>
                <tr>
                    <th>Book Name</th>
                    <th>Publication</th>
                    <th>Author</th>
                    <th style="width: 150px;">View Details</th>
                    <th style="width: 150px;">Edit Details</th>

                </tr>
                </thead>
                <tbody>

                </tbody>
            </table>
        </fieldset>
    </div>
</div>
<?php
$body->endBody ( 'global', 'default' );
?>
