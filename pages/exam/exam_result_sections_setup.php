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

$body = new body ();
$body->startBody ( 'exam', 'LMENUL156', 'Exam Result Sections Setup' );

$resultId = $_GET['resultId'];
$sectionId = $_GET['sectionId'];
?>
<input type="hidden" name="resultIdGlobal" id="resultIdGlobal" value="<?php echo $resultId; ?>" />
<input type="hidden" name="sectionIdGlobal" id="sectionIdGlobal" value="<?php echo $sectionId; ?>" />
<div id="content_header">
    <div id="pageButton" class="buttons">
        <button type="button" class="regular toggle" onclick="showHideSearchForm()">Toggle Search Form</button>
        <button type="button" class="regular toggle" onclick="showHideDatatable()">Toggle Tabulated Data</button>
    </div>
    <div id="contentHeader">Exam Result Sections Setup</div>
</div>
<div class="clear"></div>
<div class="display">
    <div id="displaySubjectRecord">
        <fieldset class="displayElements">
			<dl>
                <dt style="width: 15%;">
                    <label for="resultName">Result Name :</label>
                </dt>
                <dd style="width: 30%">
                    <span id="resultName"></span>
                </dd>
                <dt style="width: 15%">
                    <label for="className">Class :</label>
                </dt>
                <dd style="width: 30%">
                    <span id="className"></span>
                </dd>
            </dl>
            <dl>
                <dt style="width: 15%;">
                    <label for="resultType">Result Type :</label>
                </dt>
                <dd style="width: 30%">
                    <span id="resultType"></span>
                </dd>
                <dt style="width: 15%">
                    <label for="resultDescription">Result Description :</label>
                </dt>
                <dd style="width: 30%">
                    <span id="resultDescription"></span>
                </dd>
            </dl>
        </fieldset>
    </div>
</div>
<div class="clear"></div>
<div class="inputs">
    <form id="insertForm" class="insertForm" onsubmit="return valid.validateForm(this) ? processStaticForm() : false;" style="display:none">
        <fieldset class="formelements">
            <div class="legend">
                <span>Static Data Update</span>
            </div>
            <dl class="element">
                <dt style="width: 15%">
                    <label for="staticData" id="staticFieldName"> </label>
                </dt>
                <dd style="width: 30%">
                    <input type="text" name="staticData" id="staticData" class="required"  title="The Data For The Label"  tabindex="1" value="" size="30" onchange="javascript: valid.validateInput(this);" />
                    <div id="staticDataError" class="validationError"	style="display: none"></div>
                </dd>
            </dl>
        </fieldset>
        <fieldset class="action buttons" id="insertButtons">
        	<input type="hidden" name="valueId_su" id="valueId_su" value="" />
        	<button type="button" name="submit" id="submit" class="regular hide"
                    onclick="hideTheDiv('insertForm')">Hide Update Portion</button>
            <button type="submit" name="submit" id="submit" class="positive insert" accesskey="I">Update Record</button>
        </fieldset>
    </form>
</div>
<div class="clear"></div>
<div class="inputs">
    <form id="updateForm" class="updateForm" onsubmit="return valid.validateForm(this) ? processUpdateForm() : false;" style="display:none">
        <fieldset class="formelements">
            <div class="legend">
                <span></span>
            </div>
            <dl class="element">
                <dt style="width: 15%;">
                    <label for="submissionData">Field Name :</label>
                </dt>
                <dd style="width: 30%">
                    <span id="submissionData"></span>
                </dd>                
            </dl>
            <dl class="element">
                <dt style="width: 15%">
                    <label for="submissionDate">Submission Date :</label>
                </dt>
                <dd style="width: 30%">
                    <input type="text" name="submissionDate" id="submissionDate" class="required"  title="The date of submission"  tabindex="1" value="" size="30" onchange="javascript: valid.validateInput(this);" />
                    <div id="submissionDateError" class="validationError"	style="display: none"></div>
                </dd>
                <dt style="width: 15%">
                    <label for="submissionOfficer">Submission Officer :</label>
                </dt>
                <dd style="width: 30%">
                	<input type="hidden" name="submissionOfficer_val" id="submissionOfficer_val" onchange="javascript: valid.validateInput(this);" />
                    <input type="text" name="submissionOfficer" id="submissionOfficer" class="required"  title="The submission officer"  tabindex="2" value="" size="30" onchange="javascript: valid.validateInput(this);" />
                    <div id="submissionOfficerError" class="validationError"	style="display: none"></div>
                    <div id="submissionOfficer_valError" class="validationError"	style="display: none"></div>
                </dd>
            </dl>
        </fieldset>
        <fieldset class="action buttons">
        	<input type="hidden" name="valueId_u" id="valueId_u" value="" />
        	<button type="button" name="submit" id="submit" class="regular hide"
                    onclick="hideTheDiv('updateForm')">Hide Update Portion</button>
            <button type="submit" class="positive update" accesskey="U">Update Record</button>
        </fieldset>
    </form>
</div>

<div class="clear"></div>
<div class="display">
    <div id="displayRecord" style="display:none">
        <fieldset class="displayElements">
            <div class="legend">
                <span>Details of the record</span>
            </div>
            <div id="staticDataDetails">
            	<dl>
	                <dt style="width: 15%;">
	                    <label for="fieldName">Field Name :</label>
	                </dt>
	                <dd style="width: 30%">
	                    <span id="fieldName"></span>
	                </dd>
	                <dt style="width: 15%;">
	                    <label for="fieldData">Field Data :</label>
	                </dt>
	                <dd style="width: 30%">
	                    <span id="fieldData"></span>
	                </dd>
	            </dl>
            </div>
            <div id="submissionDataDetails">
            	<dl>
	                <dt style="width: 15%;">
	                    <label for="submissionFieldName">Field Name :</label>
	                </dt>
	                <dd style="width: 30%">
	                    <span id="submissionFieldName"></span>
	                </dd>
	            </dl>
	            <dl>
	                <dt style="width: 15%;">
	                    <label for="submissionDate_d">Submission Date :</label>
	                </dt>
	                <dd style="width: 30%">
	                    <span id="submissionDate_d"></span>
	                </dd>
	                <dt style="width: 15%;">
	                    <label for="submissionOfficer_d">Submission Officer :</label>
	                </dt>
	                <dd style="width: 30%">
	                    <span id="submissionOfficer_d"></span>
	                </dd>
	            </dl>
            </div>
            <dl>
                <dt style="width: 15%;">
                    <label for="lastUpdateDateDisplay">Last Update Date : </label>
                </dt>
                <dd style="width: 30%">
                    <span id="lastUpdateDateDisplay"></span>
                </dd>
                <dt style="width: 15%">
                    <label for="lastUpdatedByDisplay">Updated By :</label>
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
                    <label for="createdByDisplay">Created By :</label>
                </dt>
                <dd style="width: 30%">
                    <span id="createdByDisplay"></span>
                </dd>

            </dl>
            <dl>
                <dt style="width: 15%;">
                    <label for="activeDisplay">Active/Inactive : </label>
                </dt>
                <dd style="width: 30%">
                    <span id="activeDisplay"></span>
                </dd>
            </dl>
        </fieldset>
        <fieldset class="action buttons">
            <button type="button" name="submit" class="regular hide"
                    onclick="hideDisplayPortion()">Hide Display Details Portion</button>     
            <button type="button" class="negative edit" id="editRecordButton"
                    class="editRecordButton">Edit Record</button>               
        </fieldset>
    </div>
</div>
<div class="clear"></div>
<div class="datatable buttons" id="displayDatatable" style="display:nones">
    <fieldset class="formelements">
        <div class="legend">
            <span>Tabulated Listing Of All Setups</span>
        </div>
        <table  class="display"
               id="groupRecords">
            <thead>
            <tr>
            	<th>Field Name</th>
            	<th>Field Type</th>            	
                <th style="width: 160px">View Details</th>
                <th style="width: 150px">Edit Details</th>
            </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </fieldset>
</div>