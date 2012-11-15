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
$body->startBody ( 'exam', 'LMENUL148', 'Exam Result Types' );
?>
<div id="content_header">
    <div id="pageButton" class="buttons">
        <button type="button" class="regular toggle" onclick="showHideSearchForm()">Toggle Search Form</button>
        <button type="button" class="regular toggle" onclick="showHideDatatable()">Toggle Tabulated Data</button>
    </div>
    <div id="contentHeader">Result Type Record Form </div>
</div>
<div class="clear"></div>
<div class="inputs">
    <form id="insertForm" class="insertForm" onsubmit="return valid.validateForm(this) ? processInsertForm() : false;">
        <fieldset class="formelements">
            <div class="legend">
                <span>New Result Type Entry Form</span>
            </div>
            <dl class="element">
                <dt style="width: 15%">
                    <label for="resultType">Result Type :</label>
                </dt>
                <dd style="width: 30%">
                    <input type="text" name="resultType" id="resultType" class="required"  title="result type"  tabindex="1" value="" size="40" onchange="javascript: valid.validateInput(this);" />
                    <div id="resultTypeError" class="validationError"	style="display: none"></div>
                </dd>
                <dt style="width: 15%">
                    <label for="resultOrder">Result Order :</label>
                </dt>
                <dd style="width: 30%">
                    <input type="text" name="resultOrder" id="resultOrder" class="required"  title="Order Of The Result"  tabindex="2" value="" size="40" onchange="javascript: valid.validateInput(this);" />
                    <div id="resultOrderError" class="validationError"	style="display: none"></div>
                </dd>
            </dl>
            <dl class="element">
                <dt style="width: 15%">
                    <label for="resultDescription">Description :</label>
                </dt>
                <dd style="width: 80%">
                    <textarea name="resultDescription" id="resultDescription" class="required"  title="Description Of The Result"  tabindex="3" rows="3" cols="50" onchange="javascript: valid.validateInput(this);"></textarea>
                    <div id="resultDescriptionError" class="validationError"	style="display: none"></div>
                </dd>
            </dl>
        </fieldset>
        <fieldset class="action buttons" id="insertButtonSet">
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
                <span>Result Type Record Update Form</span>
            </div>
            <dl class="element">
                <dt style="width: 15%">
                    <label for="resultType_u">Result Type :</label>
                </dt>
                <dd style="width: 30%">
                    <input type="text" name="resultType_u" id="resultType_u" class="required"  title="result type"  tabindex="1" value="" size="40" onchange="javascript: valid.validateInput(this);" />
                    <div id="resultType_uError" class="validationError"	style="display: none"></div>
                </dd>
                <dt style="width: 15%">
                    <label for="resultOrder_u">Result Order :</label>
                </dt>
                <dd style="width: 30%">
                    <input type="text" name="resultOrder_u" id="resultOrder_u" class="required"  title="Order Of The Result"  tabindex="2" value="" size="40" onchange="javascript: valid.validateInput(this);" />
                    <div id="resultOrder_uError" class="validationError"	style="display: none"></div>
                </dd>
            </dl>
            <dl class="element">
                <dt style="width: 15%">
                    <label for="resultDescription_u">Description :</label>
                </dt>
                <dd style="width: 80%">
                    <textarea name="resultDescription_u" id="resultDescription_u" class="required"  title="Description Of The Result"  tabindex="3" rows="3" cols="50" onchange="javascript: valid.validateInput(this);"></textarea>
                    <div id="resultDescription_uError" class="validationError"	style="display: none"></div>
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
                <span>Result Type Record Details Display</span>
            </div>
            <dl>
                <dt style="width: 15%;">
                    <label for="resultType_d">Result Type :</label>
                </dt>
                <dd style="width: 30%">
                    <span id="resultType_d"></span>
                </dd>
                <dt style="width: 15%;">
                    <label for="resultOrder_d">Order :</label>
                </dt>
                <dd style="width: 30%">
                    <span id="resultOrder_d"></span>
                </dd>
            </dl>
            <dl>
                <dt style="width: 15%;">
                    <label for="description_d">Description :</label>
                </dt>
                <dd style="width: 80%">
                    <span id="description_d"></span>
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
                    <label for="hint">Result Type :</label>
                </dt>
                <dd style="width: 30%">
                    <input type="text" name="hint" id="hint" class=""
                           style="width: 200px" title="Enter The Result Type" />
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
            <span>Tabulated Listing Of All Result Types</span>
        </div>
        <table  class="display"
               id="groupRecords">
            <thead>
            <tr>
            	<th>Result Type</th>
            	<th>Order</th>
            	<th style="width: 100px">Urls</th>
            	<th style="width: 100px">Fields</th>
                <th style="width: 100px">Details</th>
                <th style="width: 100px">Edit</th>
            </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </fieldset>
</div>