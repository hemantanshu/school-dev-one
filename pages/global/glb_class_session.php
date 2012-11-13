<?php
require_once 'config.php';
require_once BASE_PATH . 'include/global/class.body.php';

$body = new body ();
$body->startBody ( 'global', 'LMENUL55', 'Menu Url Entry' );
?>
<div id="content_header">
    <div id="pageButton" class="buttons">
        <button type="button" class="regular toggle" onclick="showHideSearchForm()"><span class="underline">T</span>oggle Search Form</button>
        <button type="button" class="regular toggle" onclick="showHideDatatable()"><span class="underline">T</span>oggle Tabulated Data</button>
    </div>
    <div id="contentHeader">Class Session Management Module</div>
</div>
<div class="clear"></div>
<div class="inputs">
    <form id="insertForm" class="insertForm" onsubmit="return valid.validateForm(this) ? processInsertForm() : false;">
        <fieldset class="formelements">
            <div class="legend">
                <span>New Class Session Entry Form</span>

            </div>
            <dl class="element">
                <dt style="width: 18%"><label for="sessionName">Session Name :</label>	</dt>
                <dd style="width: 80%">
                    <input type="text" size="30" name="sessionName" id="sessionName" class="required" tabindex="1" onchange="javascript: valid.validateInput(this);" title="Name Identifier For Session" />
                    <div id="sessionNameError" class="validationError" style="display: none"></div></dd>
            </dl>
            <dl class="element">
                <dt style="width: 18%"><label for="startDate">Start Date :</label>	</dt>
                <dd style="width: 30%">
                    <input type="text" name="startDate" id="startDate" class="required date" tabindex="2" size="" onchange="javascript: valid.validateInput(this);" title="Start Date Of The Session" />
                    <div id="startDateError" class="validationError" style="display: none"></div></dd>
                <dt style="width: 18%"><label for="endDate">End Date :</label>	</dt>
                <dd style="width: 30%">
                    <input type="text" name="endDate" id="endDate" class="required date" tabindex="3" size="" onchange="javascript: valid.validateInput(this);" title="" />
                    <div id="endDateError" class="validationError" style="display: none"></div></dd>
            </dl>
        </fieldset>
        <fieldset class="action buttons">
            <div class="buttons">
                <button type="button" name="showHide" class="regular hide" onclick="hideInsertForm()"
                        accesskey="H">
                    <span class="underline">H</span>ide Insert Form
                </button>
                <button type="reset" name="insertReset" class="negative reset" id="insertReset"
                        accesskey="R">
                    <span class="underline">R</span>eset Form Fields
                </button>

                <button type="submit" name="insertSubmit" id="insertSubmit" class="positive insert" accesskey="I">
                    <span class="underline">I</span>nsert New Record
                </button>
            </div>
        </fieldset>
    </form>
</div>
<div class="clear"></div>
<div class="inputs">
    <form id="updateForm" class="updateForm" style="display: none"
          onsubmit="return valid.validateForm(this) ? processUpdateForm() : false;">
        <fieldset class="formelements">
            <div class="legend">
                <span id="legend_updateForm">Class Session Record Update Form</span>
            </div>
            <dl class="element">
                <dt style="width: 18%"><label for="sessionName">Session Name :</label>	</dt>
                <dd style="width: 80%">
                    <input type="text" size="30" name="sessionName_u" id="sessionName_u" class="required" tabindex="11" onchange="javascript: valid.validateInput(this);" title="Name Identifier For Session" />
                    <div id="sessionName_uError" class="validationError" style="display: none"></div></dd>
            </dl>
            <dl class="element">
                <dt style="width: 18%"><label for="startDate">Start Date :</label>	</dt>
                <dd style="width: 30%">
                    <input type="text" name="startDate_u" id="startDate_u" class="required date" tabindex="12" size="" onchange="javascript: valid.validateInput(this);" title="Start Date Of The Session" />
                    <div id="startDate_uError" class="validationError" style="display: none"></div></dd>
                <dt style="width: 18%"><label for="endDate_u">End Date :</label>	</dt>
                <dd style="width: 30%">
                    <input type="text" name="endDate_u" id="endDate_u" class="required date" tabindex="13" size="" onchange="javascript: valid.validateInput(this);" title="" />
                    <div id="endDate_uError" class="validationError" style="display: none"></div></dd>
            </dl>
        </fieldset>
        <fieldset class="action buttons">
            <input type="hidden" name="valueId_u" tabindex="20" id="valueId_u" value="" />
            <input type="hidden" name="position_u" tabindex="21" id="position_u" value="" />
            <button accesskey="H" type="button" name="toggleInsert" tabindex="24" class="regular hide"
                    id="toggleInsert" onclick="hideUpdateForm()">
                <span class="underline">H</span>ide Update Form
            </button>
            <button acesskey="U" type="submit" name="submit" class="positive update"
                    id="submit">
                <span class="underline">U</span>pdate Record
            </button>
        </fieldset>
    </form>
</div>
<div class="clear"></div>
<div class="display">
    <div id="displayPortion" style="display: none">
        <fieldset class="displayElements">
            <div class="legend">
                <span id="legendDisplayAssignment">Class Session Record Details</span>
            </div>
            <dl>
                <dt style="width: 18%;">
                    <label for="sessionName">Session Name :</label>
                </dt>
                <dd style="width: 30%">
                    <span id="sessionName_d"></span>
                </dd>
                <dt style="width: 18%">
                    <label for="sesssionId">Session Id :</label>
                </dt>
                <dd style="width: 30%">
                    <span id="sessionId"></span>
                </dd>
            </dl>
            <dl>
                <dt style="width: 18%;">
                    <label for="startDate">Start Date :</label>
                </dt>
                <dd style="width: 30%">
                    <span id="startDate_d"></span>
                </dd>
                <dt style="width: 18%">
                    <label for="endDate">End Date :</label>
                </dt>
                <dd style="width: 30%">
                    <span id="endDate_d"></span>
                </dd>
            </dl>
            <dl>
                <dt style="width: 18%;">
                    <label for="updateDate">Last Update Date : </label>
                </dt>
                <dd style="width: 30%">
                    <span id="lastUpdateDateDisplay"></span>
                </dd>
                <dt style="width: 18%">
                    <label for="updatedBy">Updated By :</label>
                </dt>
                <dd style="width: 30%">
                    <span id="lastUpdatedByDisplay"></span>
                </dd>
            </dl>
            <dl>
                <dt style="width: 18%;">
                    <label for="creationDate">Creation Date : </label>
                </dt>
                <dd style="width: 30%">
                    <span id="creationDateDisplay"></span>
                </dd>
                <dt style="width: 18%">
                    <label for="createdBy">Created By :</label>
                </dt>
                <dd style="width: 30%">
                    <span id="createdByDisplay"></span>
                </dd>
            </dl>
            <dl>
                <dt style="width: 18%;">
                    <label for="active">Active/Inactive : </label>
                </dt>
                <dd style="width: 30%">
                    <span id="activeDisplay"></span>
                </dd>

            </dl>
        </fieldset>
        <fieldset class="action buttons">
            <input type="hidden" name="recordId_d" id="recordId_d" value="" />
            <input type="hidden" name="position_d" id="position_d" value="" />
            <button accesskey="H" type="button" name="submit" class="regular hide" tabindex="29"
                    id="submit" onclick="hideDisplayPortion()">
                <span class="underline">H</span>ide Details Portion
            </button>
            <button accesskey="U" type="submit" name="updateButton" class="regular edit"
                    tabindex="28" id="updateButton">
                <span class="underline">U</span>pdate Record
            </button>
        </fieldset>
    </div>
</div>
<div class="clear"></div>
<div class="inputs">
    <form id="searchForm" class="searchForm"
          onsubmit="return valid.validateForm(this) ? getSearchDetails() : false;">
        <fieldset class="formelements">
            <div class="legend">Search Record</div>
            <dl>
                <dt style="width: 18%">
                    <label for="menu_hint">Session Name :</label>
                </dt>
                <dd style="width: 30%">
                    <input type="text" name="hint" tabindex="30" id="hint"
                           class="" style="width: 200px"
                           onchange="javascript: valid.validateInput(this);" />
                    <div id="menu_hintError" class="validationError"
                         style="display: none;"></div>
                </dd>
                <dt style="width: 18%">
                    <label for="search_type">Search Type :</label>
                </dt>
                <dd>
                    <select name="search_type" tabindex="31" id="search_type"
                            style="width: 150px">
                        <option value="all">All Records</option>
                        <option value="1" selected="selected">Active Records</option>
                        <option value="0">In-Active Records</option>
                    </select>
                </dd>
                <div id="search_typeError" class="validationError"
                     style="display: none;"></div>
            </dl>
            <dl>
            </dl>
        </fieldset>
        <fieldset class="action buttons">
            <div class="buttons">
                <button accesskey="T" type="button" name="toggleInsert" class="regular toggle"
                        tabindex="32" id="toggleInsert1" onclick="toggleInsertForm()">
                    <span class="underline">T</span>oggle Insert Form
                </button>
                <button type="reset" name="searchReset" id="searchReset" class="negative reset"
                        accesskey="L">
                    Reset Search Fie<span class="underline">l</span>ds
                </button>
                <button accesskey="S" type="submit" name="submitSearch" class="positive search"
                        tabindex="33" id="toggleInsert1">
                    Get <span class="underline">S</span>earch Results
                </button>
            </div>
        </fieldset>
    </form>
</div>
<div class="clear"></div>
<form>
    <div class="datatable buttons" id="tabulatedRecords">
        <fieldset class="tableElements">
            <div class="legend">
                <span>Tabulated Listing Of Session Details</span>
            </div>
            <table  class="display" id="recordListing">
                <thead>
                <tr>
                    <th>Session Name</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                    <th style="width: 140px;">Show Details</th>
                    <th style="width: 145px;">Edit Details</th>
                </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </fieldset>
    </div>
</form>
</div>
<?php
    $body->endBody('global', 'MENUL55');
?>