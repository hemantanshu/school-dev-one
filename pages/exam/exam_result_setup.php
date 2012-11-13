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
require_once BASE_PATH . 'include/exam/class.examination.php';

$body = new body ();
$examination = new Examination();

$body->startBody ( 'exam', 'LMENUL92', 'Result Setup' );

$resultId = $_GET['resultId'];
$sessionId = $_GET['sessionId'];

$resultDetails = $body->getTableIdDetails($resultId);
$sessionDetails = $body->getTableIdDetails($sessionId);

$examinationIds = $examination->getExaminationForResultPreparation($sessionId);
?>
<input type="hidden" name="resultId" id="resultId" value="<?php echo $resultId;  ?>" />
<input type="hidden" name="sessionId" id="sessionId" value="<?php echo $sessionId;  ?>" />

<div id="content_header">
    <div id="pageButton" class="buttons">
    </div>
    <div id="contentHeader">Result Setup Wizard </div>
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
                    <span id="resultName"><?php echo $resultDetails['result_name']; ?></span>
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
    <form id="insertForm" class="insertForm" onsubmit="return valid.validateForm(this) ? processInsertForm() : false;">
        <fieldset class="formelements">
            <div class="legend">
                <span>New Examination Result Entry</span>
            </div>
            <dl class="element">
                <dt style="width: 15%">
                    <label for="displayName">Display Name :</label>
                </dt>
                <dd style="width: 30%">
                    <input type="text" name="displayName" id="displayName" class="required"  title="Name to appear on the result" tabindex="0" value="" size="30" onchange="javascript: valid.validateInput(this);" />
                    <div id="displayNameError" class="validationError"	style="display: none"></div>
                </dd>
                <dt style="width: 15%">
                    <label for="displayOrder">Display Order :</label>
                </dt>
                <dd style="width: 30%">
                    <input type="text" name="displayOrder" id="displayOrder" class="required"  title="Order In Printing" tabindex="1" value="" size="15" onchange="javascript: valid.validateInput(this);" />
                    <div id="displayOrderError" class="validationError"	style="display: none"></div>
                </dd>
            </dl>
            <dl class="element">
                <dt style="width: 15%">
                    <label for="examinationGroup">Examination Name :</label>
                </dt>
                <dd style="width: 30%">
                    <select name="examinationGroup" id="examinationGroup" class="required"  title="Select Examination Group"  tabindex="2" onchange="javascript: valid.validateInput(this);" style="width: 200px">
                    	<?php 
                    		foreach ($examinationIds as $examinationId){
                    			$details = $examination->getTableIdDetails($examinationId);
                    			echo "<option value=\"$examinationId\">".$details['examination_name']."</option>";
                    		}
                    	?>
                    </select>
                    <div id="examinationGroupError" class="validationError"	style="display: none"></div>
                </dd>
                <dt style="width: 15%">
                    <label for="weightAge">Weightage (%) :</label>
                </dt>
                <dd style="width: 30%">
                    <input type="text" name="weightAge" id="weightAge" class="required"  title="Enter Weightage Of Marks" tabindex="3" value="" size="20" onchange="javascript: valid.validateInput(this);" />
                    <div id="weightAgeError" class="validationError"	style="display: none"></div>
                </dd>
            </dl>
        </fieldset>
        <fieldset class="action buttons">
            <button type="reset" name="insertReset" id="insertReset" class="negative reset">Reset Form</button>
            <button type="button" name="submit" class="regular hide" onclick="hideInsertForm()">Hide
                Insert Form</button>
            <button type="submit" name="submit" id="submit" class="positive insert" accesskey="I">Insert New Record</button>
        </fieldset>
    </form>
</div>
<div class="clear"></div>
<div class="inputs">
    <form id="updateForm" class="updateForm" onsubmit="return valid.validateForm(this) ? processUpdateForm() : false;" style="display:none">
        <fieldset class="formelements">
            <div class="legend">
                <span id="legend_editForm">Exam Result Setup</span>
            </div>
            <dl class="element">
                <dt style="width: 15%">
                    <label for="displayName_u">Display Name :</label>
                </dt>
                <dd style="width: 30%">
                    <input type="text" name="displayName_u" id="displayName_u" class="required"  title="Name to appear on the result" tabindex="10" value="" size="20" onchange="javascript: valid.validateInput(this);" />
                    <div id="displayName_uError" class="validationError"	style="display: none"></div>
                </dd>
                <dt style="width: 15%">
                    <label for="displayOrder_u">Display Order :</label>
                </dt>
                <dd style="width: 30%">
                    <input type="text" name="displayOrder_u" id="displayOrder_u" class="required"  title="Order In Printing" tabindex="11" value="" size="15" onchange="javascript: valid.validateInput(this);" />
                    <div id="displayOrder_uError" class="validationError"	style="display: none"></div>
                </dd>
            </dl>
            <dl class="element">
                <dt style="width: 15%">
                    <label for="examinationGroup_u">Examination Name :</label>
                </dt>
                <dd style="width: 30%">
                    <select name="examinationGroup_u" id="examinationGroup_u" class="required"  title="Select Examination Group"  tabindex="12" onchange="javascript: valid.validateInput(this);">
                        <?php
                        foreach ($examinationIds as $examinationId){
                            $details = $examination->getTableIdDetails($examinationId);
                            echo "<option value=\"$examinationId\">".$details['examination_name']."</option>";
                        }
                        ?>
                    </select>
                    <div id="examinationGroup_uError" class="validationError"	style="display: none"></div>
                </dd>
                <dt style="width: 15%">
                    <label for="weightAge">Weightage (%) :</label>
                </dt>
                <dd style="width: 30%">
                    <input type="text" name="weightAge_u" id="weightAge_u" class="required"  title="Enter Weightage Of Marks" tabindex="13" value="" size="20" onchange="javascript: valid.validateInput(this);" />
                    <div id="weightAge_uError" class="validationError"	style="display: none"></div>
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
            <button type="button" class="regular hide"
                    onclick="hideUpdateForm()">Hide Update Portion</button>
            <button type="submit" class="positive update" accesskey="U">Update Record</button>
        </fieldset>
    </form>
</div>

<div class="clear"></div>
<div class="display">
    <div id="displayRecord" style="display:none">
        <fieldset class="displayElements">
            <div class="legend">
                <span>Examination Record Details Form </span>
            </div>
            <dl>
                <dt style="width: 15%;">
                    <label for="displayName_d">Display Name : </label>
                </dt>
                <dd style="width: 30%">
                    <span id="displayName_d"></span>
                </dd>
                <dt style="width: 15%;">
                    <label for="displayOrder_d">Display Order : </label>
                </dt>
                <dd style="width: 30%">
                    <span id="displayOrder_d"></span>
                </dd>
                
            </dl>
            <dl>
                <dt style="width: 15%;">
                    <label for="examinationName_d">Examination Name : </label>
                </dt>
                <dd style="width: 30%">
                    <span id="examinationName_d"></span>
                </dd>
                <dt style="width: 15%">
                    <label for="weightAge_d">Weightage :</label>
                </dt>
                <dd style="width: 30%">
                    <span id="weightAge_d"></span>
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
            <input type="hidden" name="valueId_d" id="valueId_d" value="" /> <input
            type="hidden" name="rowPosition_d" id="rowPosition_d" value="" />
            <button type="button" class="positive activate" name="activateRecord_d"
                    id="activateRecord_d">Activate Record</button>
            <button type="button" class="negative drop" name="dropRecord_d"
                    id="dropRecord_d">Drop Record</button>
            <button type="button" name="submit" class="regular hide"
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
                    onclick="toggleInsertForm()" accesskey="T">Toggle Insert Form</button>
            <button type="submit" name="searchData" id="searchData" class="positive search">Get Search
                Results</button>
        </fieldset>
    </form>
</div>

<div class="clear"></div>


<div class="datatable buttons" id="displayDatatable" style="display:none">
    <fieldset class="formelements">
        <div class="legend">
            <span>Tabulated Listing Of Subject Components</span>
        </div>
        <table  class="display"
                id="groupRecords">
            <thead>
            <tr>
                <th>Display Name</th>
                <th>Examination Name</th>
                <th>Weightage</th>
                <th>Order</th>
                <th style="width: 160px">View Details</th>
                <th style="width: 150px">Edit Details</th>
            </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </fieldset>
</div>