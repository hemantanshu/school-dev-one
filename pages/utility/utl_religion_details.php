<?php
require_once 'config.php';
require_once BASE_PATH.'include/global/class.body.php';

$body = new body();
$body->startBody('global', 'LMENUL9', 'Religion Entry Page', 'LMENUL28');
?>
<input type="hidden" value="RELIG" name="optionType_glb" id="optionType_glb" />
<div id="content_header">
    <div id="pageButton" class="buttons">
        <button type="button" class="regular toggle" onclick="showHideSearchForm()"><span class="underline">T</span>oggle Search Form</button>
        <button type="button" class="regular toggle" onclick="showHideDatatable()"><span class="underline">T</span>oggle Tabulated Data</button>
    </div>
    <div id="contentHeader">Religion Record Details Form</div>
</div>
<div class="inputs">
    <form id="insertForm" class="insertForm"
          onsubmit="return valid.validateForm(this) ? processMainForm() : false;">
        <fieldset class="formelements">
            <div class="legend">
                <span>New Religion Record Entry Form</span>
            </div>
            <dl class="element">
                <dt style="width: 15%">
                    <label for="valueName">Religion Name :</label>
                </dt>
                <dd style="width: 35%">
                    <input type="text" name="valueName" id="valueName" class="required"
                           title="Enter The New Value" onchange="javascript: valid.validateInput(this);" value="" size="40" />
                    <div id="valueNameError" class="validationError"
                         style="display: none"></div>
                </dd>
            </dl>
        </fieldset>
        <fieldset class="action buttons">
            <button type="button" name="submit" class="regular hide" onclick="hideInsertForm()"
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
                <span id="legend_updateForm">Religion Record Update Form </span>
            </div>
            <dl class="element">
                <dt style="width: 15%">

                    <label for="valueName_u">Religion Name :</label>
                </dt>
                <dd style="width: 35%">
                    <input type="text" name="valueName_u" id="valueName_u"
                           class="required" onchange="javascript: valid.validateInput(this);" title="Enter The Value For Update" value=""
                           size="40" />
                    <div id="valueName_uError" class="validationError"
                         style="display: none"></div>
                </dd>
            </dl>
        </fieldset>

        <fieldset class="action buttons">
            <input type="hidden" name="valueId_u" id="valueId_u" value="" /> <input
            type="hidden" name="rowPosition_u" id="rowPosition_u" value="" />
            <button type="button" class="positive activate" name="activateOptionValue_u"
                    id="activateOptionValue_u">Activate Value</button>
            <button type="button" class="negative drop" name="dropOptionValue_u"
                    id="dropOptionValue_u">Drop Value</button>
            <button type="button" name="submit" class="regular hide" id="submit"
                    onclick="hideUpdateForm()">Hide Update Portion</button>
            <button type="submit" class="positive update" accesskey="U">
                <span class="underline">U</span>pdate Record
            </button>
        </fieldset>
    </form>
</div>
<div class="clear"></div>
<div class="display">
    <div id="displayValue">
        <fieldset class="displayElements">
            <div class="legend">
                <span id="legendDisplayAssignment">Religion Details : </span>
            </div>
            <dl>
                <dt style="width: 15%">
                    <label for="optionId">Religion Id : </label>
                </dt>
                <dd style="width: 30%">
                    <span id="optionId"></span>
                </dd>
            </dl>
            <dl>
                <dt style="width: 15%;">
                    <label for="valueName_d">Religion Name : </label>
                </dt>
                <dd style="width: 30%">
                    <span id="valueName_dDisplay"></span>
                </dd>
                <dt style="width: 15%">
                    <label for="total">Total Assignment :</label>
                </dt>
                <dd style="width: 30%">
                    <span id="totalDisplay"></span>
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
            <button type="button" class="positive activate" name="activateOptionValue_d"
                    id="activateOptionValue_d">Activate Record</button>
            <button type="button" class="negative drop" name="dropOptionValue_d"
                    id="dropOptionValue_d">Drop Record</button>
            <button type="button" name="submit" id="submit" class="regular hide"
                    onclick="hideDisplayPortion()">Hide Display Details Portion</button>
            <button type="button" class="positive edit" id="update_value_button"
                    onclick="processDisplayForm()">Edit Record</button>
        </fieldset>

    </div>
</div>
<div class="clear"></div>
<div class="inputs">
    <form id="searchForm" class="searchForm"
          onsubmit="return valid.validateForm(this) ? getOptionValueSearchDetails() : false;">
        <fieldset class="formelements">
            <div class="legend">Search Religion Name</div>
            <dl>
                <dt style="width: 15%">
                    <label for="menu_hint">Religion Name :</label>
                </dt>
                <dd style="width: 30%">
                    <input type="text" name="menu_hint" id="menu_hint" class=""
                           style="width: 200px" title="Enter The Religion Name" />
                </dd>
                <dt style="width: 10%">
                    <label for="search_type">Search Type :</label>
                </dt>
                <dd>
                    <select name="search_type" id="search_type" style="width: 150px">
                        <option value="all">All Religions</option>
                        <option value="1" selected="selected">Active Religion</option>
                        <option value="0">In-Active Religion</option>
                    </select>
                </dd>
            </dl>
        </fieldset>

        <fieldset class="action buttons">
            <button type="button" name="toggleInsert1" id="toggleInsert1"
                    onclick="toggleInsertForm()" class="regular toggle">Toggle Insert Form</button>
            <button type="submit" name="toggleInsert1" id="toggleInsert1" class="positive search">Get
                Search Results</button>
        </fieldset>
    </form>
</div>


<div class="clear"></div>

<div id="displayDatatable" class="buttons">
    <div class="datatable" id="groupMenusM">
        <fieldset>
            <div class="legend">
                <span>Religion Record Tabulated Listing</span>
            </div>
            <table  class="display"
                   id="groupValues">
                <thead>
                <tr>
                    <th>Religion Id</th>
                    <th>Religion Name</th>
                    <th style="width: 150px">View Details</th>
                    <th style="width: 150px">Edit Details</th>

                </tr>
                </thead>
                <tbody>

                </tbody>
            </table>
        </fieldset>
    </div>
</div>