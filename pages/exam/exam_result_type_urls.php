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
$body->startBody ( 'exam', 'LMENUL150', 'Exam Result Type URL Definition' );

$resultTypeId = $_GET['resultTypeId'];
$details = $body->getTableIdDetails($resultTypeId);
?>
<input type="hidden" name="resultTypeIdGlobal" id="resultTypeIdGlobal" value="<?php echo $resultTypeId; ?>" />
<input type="hidden" name="resultTypeNameGlobal" id="resultTypeNameGlobal" value="<?php echo $details['result_type']; ?>" />

<div id="content_header">
    <div id="pageButton" class="buttons">
        <button type="button" class="regular toggle" onclick="showHideSearchForm()">Toggle Search Form</button>
        <button type="button" class="regular toggle" onclick="showHideDatatable()">Toggle Tabulated Data</button>
    </div>
    <div id="contentHeader">Exam Result Type URL Listing</div>
</div>
<div class="clear"></div>
<div class="display">
    <div id="displaySubjectRecord">
        <fieldset class="displayElements">
			<dl>
                <dt style="width: 15%;">
                    <label for="resultTypeName">Result Type :</label>
                </dt>
                <dd style="width: 30%">
                    <span id="resultTypeName"><?php echo $details['result_type']; ?></span>
                </dd>
                <dt style="width: 15%">
                    <label for="resultTypeDescription">Description :</label>
                </dt>
                <dd style="width: 30%">
                    <span id="resultTypeDescription"><?php echo $details['result_description']; ?></span>
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
                <span>New Result URL Entry Form</span>
            </div>
            <dl class="element">
                <dt style="width: 15%">
                    <label for="displayName">Display Name :</label>
                </dt>
                <dd style="width: 30%">
                    <input type="text" name="displayName" id="displayName" class="required"  title="The display name"  tabindex="1" value="" size="30" onchange="javascript: valid.validateInput(this);" />
                    <div id="displayNameError" class="validationError"	style="display: none"></div>
                </dd>
                <dt style="width: 15%">
                    <label for="urlType">URL Type :</label>
                </dt>
                <dd style="width: 30%">                	
                    <select name="urlType" id="urlType" class="required"  title="The type of url"  tabindex="2"  onchange="javascript: valid.validateInput(this);">
                    <?php 
                    	$optionIds = $body->getOptionSearchValueIds('', 'RESTY', 1);
                    	foreach($optionIds as $optionId)
                    		$optionValues .= "<option value=\"$optionId\">".$body->getOptionIdValue($optionId)."</option>";
                    	echo $optionValues;
                    ?>
                    </select>
                    <div id="urlTypeError" class="validationError"	style="display: none"></div>
                </dd>
            </dl>
            <dl class="element">
                <dt style="width: 15%">
                    <label for="url">URL :</label>
                </dt>
                <dd style="width: 80%">
                	<input type="hidden" name="url_val" id="url_val"  />
                    <input type="text" name="url" id="url" class="required autocomplete"  title="The url for the type"  tabindex="3" value="" size="40" onchange="javascript: valid.validateInput(this);" />
                    <div id="urlError" class="validationError"	style="display: none"></div>
                </dd>
            </dl>   
            <dl class="element">
                <dt style="width: 15%">
                    <label for="displayOrder">Display Order :</label>
                </dt>
                <dd style="width: 30%">
                    <input type="text" name="displayOrder" id="displayOrder" class="required numeric"  title="The display order "  tabindex="4" value="" size="15" onchange="javascript: valid.validateInput(this);" />
                    <div id="displayOrderError" class="validationError"	style="display: none"></div>
                </dd>
            </dl>            
        </fieldset>
        <fieldset class="action buttons" id="insertButtons">
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
                <span>Field Record Update</span>
            </div>
            <dl class="element">
                <dt style="width: 15%">
                    <label for="displayName_u">Display Name :</label>
                </dt>
                <dd style="width: 30%">
                    <input type="text" name="displayName_u" id="displayName_u" class="required"  title="The display name"  tabindex="11" value="" size="30" onchange="javascript: valid.validateInput(this);" />
                    <div id="displayName_uError" class="validationError"	style="display: none"></div>
                </dd>
                <dt style="width: 15%">
                    <label for="urlType_u">URL Type :</label>
                </dt>
                <dd style="width: 30%">
                    <select name="urlType_u" id="urlType_u" class="required"  title="The type of url"  tabindex="12"  onchange="javascript: valid.validateInput(this);">
                    <?php
                    	echo $optionValues;
                    ?>
                    </select>
                    <div id="urlType_uError" class="validationError"	style="display: none"></div>
                </dd>
            </dl>
            <dl class="element">
                <dt style="width: 15%">
                    <label for="url_u">URL :</label>
                </dt>
                <dd style="width: 80%">
                	<input type="hidden" name="url_uval" id="url_uval"  />
                    <input type="text" name="url_u" id="url_u" class="required"  title="The url for the type"  tabindex="13" value="" size="40" onchange="javascript: valid.validateInput(this);" />
                    <div id="url_uError" class="validationError"	style="display: none"></div>
                </dd>
            </dl>
            <dl class="element">
                <dt style="width: 15%">
                    <label for="displayOrder_u">Display Order :</label>
                </dt>
                <dd style="width: 30%">
                    <input type="text" name="displayOrder_u" id="displayOrder_u" class="required numeric"  title="The display order "  tabindex="14" value="" size="15" onchange="javascript: valid.validateInput(this);" />
                    <div id="displayOrder_uError" class="validationError"	style="display: none"></div>
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
                <span>Result Type URL Display Details</span>
            </div>
            <dl>
                <dt style="width: 15%;">
                    <label for="resultType_d">Result Type :</label>
                </dt>
                <dd style="width: 30%">
                    <span id="resultType_d"></span>
                </dd>
                <dt style="width: 15%;">
                    <label for="displayName_d">Display Name :</label>
                </dt>
                <dd style="width: 30%">
                    <span id="displayName_d"></span>
                </dd>
            </dl>
            <dl>
                <dt style="width: 15%;">
                    <label for="url_d">URL :</label>
                </dt>
                <dd style="width: 80%">
                    <span id="url_d"></span>
                </dd>
            </dl>
            <dl>
            	
                <dt style="width: 15%;">
                    <label for="urlType_d">Type :</label>
                </dt>
                <dd style="width: 30%">
                    <span id="urlType_d"></span>
                </dd>
                <dt style="width: 15%;">
                    <label for="displayOrder_d">Display Order :</label>
                </dt>
                <dd style="width: 30%">
                    <span id="displayOrder_d"></span>
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
                    <label for="hint">Result Field :</label>
                </dt>
                <dd style="width: 30%">
                    <input type="text" name="hint" id="hint" class=""
                           style="width: 200px" title="Enter The Field Name" />
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
            <span>Tabulated Listing Of All URLs</span>
        </div>
        <table  class="display"
               id="groupRecords">
            <thead>
            <tr>
            	<th>Field</th>
            	<th>Name</th>
            	<th>Type</th>
                <th style="width: 160px">View Details</th>
                <th style="width: 150px">Edit Details</th>
            </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </fieldset>
</div>