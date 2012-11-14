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
$body->startBody ( 'utility', 'LMENUL57', 'Class Subject Assignment' );

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
    <div id="contentHeader">Subject Record Details </div>
</div>
<input type="hidden" name="class_global" id="class_global" value="<?php echo $classId; ?>" />
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
                    <label for="class">Class :</label>
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
                <span>New Subject Type Entry Form</span>
            </div>
            <dl class="element">
                <dt style="width: 15%"><label for="subject">Subject Display : </label>	</dt>
                <dd style="width: 30%">
                    <input type="text" name="subject" id="subject" class="required" tabindex="11" size="30" onchange="javascript: valid.validateInput(this);" title="Get Subject Name" />
                    <div id="subjectError" class="validationError" style="display: none"></div></dd>
                <dt style="width: 15%"><label for="type">Type :</label>	</dt>
                <dd style="width: 30%">
                    <select name="type" id="type" style="width: 150px" title="Select The Type Of Subject" class="required" tabindex="12">
                        <option value="c">Compulsory</option>
                        <option value="o">Optional</option>
                    </select>
                    <div id="typeError" class="validationError" style="display: none"></div></dd>
            </dl>
            <dl class="element">
                <dt style="width: 15%"><label for="associatedSubject">Subject Name :</label>	</dt>
                <dd style="width: 80%">
                    <input type="hidden" name="subjectName_val" id="subjectName_val" value="" />
                    <input type="text" size="30" name="subjectName" id="subjectName" class="required autocomplete" tabindex="13" onchange="javascript: valid.validateInput(this)" title="Name the subject" />
                    <div id="subjectName_valError" class="validationError" style="display: none"></div></dd>
            </dl>
        </fieldset>
        <fieldset class="action buttons">
            <button type="reset" name="insertReset" id="insertReset" class="negative reset">Reset Form</button>
            <button type="button" name="submit" class="regular hide" onclick="hideInsertForm()">Hide
                Insert Form</button>
            <button type="submit" name="submit" id="submit" class="positive insert" accesskey="I"><span class="underline">I</span>nsert New Record</button>
        </fieldset>
    </form>
</div>


<div class="clear"></div>
<div class="inputs">
    <form id="updateForm" class="updateForm" onsubmit="return valid.validateForm(this) ? processUpdateForm() : false;" style="display: none">
        <fieldset class="formelements">
            <div class="legend">
                <span id="legend_editForm">Update Subject Record Details</span>
            </div>
            <dl class="element">
                <dt style="width: 15%"><label for="subject">Subject Display : </label>	</dt>
                <dd style="width: 30%">
                    <input type="text" name="subject_u" id="subject_u" class="required" tabindex="1" size="30" onchange="javascript: valid.validateInput(this);" title="Get Subject Name" />
                    <div id="subject_uError" class="validationError" style="display: none"></div></dd>
                <dt style="width: 15%"><label for="type">Type :</label>	</dt>
                <dd style="width: 30%">
                    <select name="type_u" id="type_u" style="width: 150px" title="Select The Type Of Subject" class="required" tabindex="2">
                        <option value="c">Compulsory</option>
                        <option value="o">Optional</option>
                    </select>
                    <div id="type_uError" class="validationError" style="display: none"></div></dd>
            </dl>
            <dl class="element">
                <dt style="width: 15%"><label for="associatedSubject">Subject Name :</label>	</dt>
                <dd style="width: 80%">
                    <input type="hidden" name="subjectName_uval" id="subjectName_uval" />
                    <input type="text" size="40" name="subjectName_u" id="subjectName_u" class="required autocomplete" tabindex="3" onchange="javascript: valid.validateInput(this);" title="Name the subject" />
                    <div id="subjectName_uError" class="validationError" style="display: none"></div></dd>
            </dl>
        </fieldset>
        <fieldset class="action buttons">
            <input type="hidden" name="valueId_u" id="valueId_u" value="" /> <input
            type="hidden" name="rowPosition_u" id="rowPosition_u" value="" />
            <input
                type="hidden" name="associationId" id="associationId" value="" />
            <button type="button" class="positive activate" name="activateRecord_u"
                    id="activateRecord_u">Activate Record</button>
            <button type="button" class="negative drop" name="dropRecord_u"
                    id="dropRecord_u">Drop Record</button>
            <button type="button" class="regular hide"
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
                <dt style="width: 15%;">
                    <label for="subject">Subject Display : </label>
                </dt>
                <dd style="width: 30%">
                    <span id="subject_d"></span>
                </dd>
                <dt style="width: 15%">
                    <label for="type">Type :</label>
                </dt>
                <dd style="width: 30%">
                    <span id="type_d"></span>
                </dd>
            </dl>
            <dl>
                <dt style="width: 15%">
                    <label for="subjectName">Subject Name :</label>
                </dt>
                <dd style="width: 30%">
                    <span id="subjectName_d"></span>
                </dd>
                <dt style="width: 15%;">
                    <label for="count">Subject Options : </label>
                </dt>
                <dd style="width: 30%">
                    <span id="count"></span>
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
                </dt>
                <dd style="width: 30%">
                </dd>
                <dt style="width: 15%">
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
               id="groupSubjects">
            <thead>
            <tr>
                <th>Subject Display</th>
                <th>Subject Type</th>
                <th style="width: 50px">Subjects</th>
                <th style="width: 170px">Subject Lookup</th>
                <th style="width: 150px">View Details</th>
                <th style="width: 150px">Edit Details</th>
            </tr>
            </thead>
            <tbody>

            </tbody>
        </table>
    </fieldset>
</div>
