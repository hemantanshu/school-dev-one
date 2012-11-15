<?php
require_once 'config.php';
require_once BASE_PATH.'include/global/class.body.php';

$body = new body();
$body->startBody('utility', 'LMENUL45', 'Personal Bookmark Entry');
?>
<div id="content_header">
    <div id="pageButton" class="buttons">
        <button type="button" class="negative toggle" onclick="refreshBookmark()">Load Bookmark</button>
        <button type="button" class="regular toggle" onclick="showHideSearchForm()"><span class="underline">T</span>oggle Search Form</button>
        <button type="button" class="regular toggle" onclick="showHideDatatable()"><span class="underline">T</span>oggle Tabulated Data</button>
    </div>
    <div id="contentHeader">Personal Bookmark Management Module</div>
</div>
<div class="inputs">
    <form id="insertForm" class="insertForm"
          onsubmit="return valid.validateForm(this) ? processInsertForm() : false;" style="display: none">
        <fieldset class="formelements">
            <div class="legend">
                <span>New Bookmark Record Entry Form</span>
            </div>
            <dl class="element">
                <dt style="width: 15%"><label for="pageName">Bookmark Name :</label>	</dt>
                <dd style="width: 30%">
                    <input type="text" name="pageName" id="pageName" class="required" tabindex="1" size="40" onchange="javascript: valid.validateInput(this);" title="Enter Bookmark Identifier Name" />
                    <div id="pageNameError" class="validationError" style="display: none"></div></dd>
                <dt style="width: 15%"><label for="priority">Priority :</label>	</dt>
                <dd style="width: 30%">
                    <input type="text" name="priority" id="priority" class="required numeric" tabindex="2" size="10" onchange="javascript: valid.validateInput(this);" title="Lower the priority, higher will be order" />
                    <div id="priorityError" class="validationError" style="display: none"></div></dd>
            </dl>
            <dl class="element">
                <dt style="width: 15%"><label for="url">URL :</label>	</dt>
                <dd style="width: 80%">
                    <input type="text" size="60" name="url" id="url" class="required" tabindex="3" onchange="javascript: valid.validateInput(this);" title="Enter the url" />
                    <div id="urlError" class="validationError" style="display: none"></div></dd>
            </dl>
            <dl class="element">
                <dt style="width: 15%"><label for="redirect">Target :</label>	</dt>
                <dd style="width: 30%">
                    <select size="1" name="redirect" id="redirect" class="required" tabindex="4"
                            style="width: 150px" onchange="javascript: valid.validateInput(this);">
                        <option value="n">Parent</option>
                        <option value="y">Blank</option>

                    </select>
                    <div id="redirectError" class="validationError" style="display: none"></div></dd>
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
          onsubmit="return valid.validateForm(this) ? processUpdateForm() : false;" style="display: none">
        <fieldset class="formelements">
            <div class="legend">
                <span id="legend_updateForm">Bookmark Record Update Form </span>
            </div>
            <dl class="element">
                <dt style="width: 15%"><label for="pageName">Bookmark Name :</label>	</dt>
                <dd style="width: 30%">
                    <input type="text" name="pageName_u" id="pageName_u" class="required" tabindex="11" size="40" onchange="javascript: valid.validateInput(this);" title="Enter Bookmark Identifier Name" />
                    <div id="pageName_uError" class="validationError" style="display: none"></div></dd>
                <dt style="width: 15%"><label for="priority">Priority :</label>	</dt>
                <dd style="width: 30%">
                    <input type="text" name="priority_u" id="priority_u" class="required numeric" tabindex="12" size="10" onchange="javascript: valid.validateInput(this);" title="Lower the priority, higher will be order" />
                    <div id="priority_uError" class="validationError" style="display: none"></div></dd>
            </dl>
            <dl class="element">
                <dt style="width: 15%"><label for="url">URL :</label>	</dt>
                <dd style="width: 80%">
                    <input type="text" size="60" name="url_u" id="url_u" class="required" tabindex="13" onchange="javascript: valid.validateInput(this);" title="Enter the url" />
                    <div id="url_uError" class="validationError" style="display: none"></div></dd>
            </dl>
            <dl class="element">
                <dt style="width: 15%"><label for="redirect">Target :</label>	</dt>
                <dd style="width: 30%">
                    <select size="1" name="redirect_u" id="redirect_u" class="required" tabindex="14"
                            style="width: 150px" onchange="javascript: valid.validateInput(this);">
                        <option value="n">Parent</option>
                        <option value="y">Blank</option>

                    </select>
                    <div id="redirect_uError" class="validationError" style="display: none"></div></dd>
            </dl>
        </fieldset>
        <fieldset class="action buttons">
            <input type="hidden" name="valueId_u" tabindex="20" id="valueId_u"
                   value="" /> <input type="hidden" name="position_u" tabindex="21"
                                      id="position_u" value="" />
            <button accesskey="A" type="button" class="positive activate"
                    name="activateRecord_u" tabindex="22" id="activateRecord_u">
                <span class="underline">A</span>ctivate Menu URL
            </button>
            <button accesskey="D" type="button" class="negative drop" name="dropRecord_u"
                    tabindex="23" id="dropRecord_u">
                <span class="underline">D</span>rop Menu URL
            </button>
            <button accesskey="H" type="button" name="toggleInsert" tabindex="24" class="regular hide"
                    id="toggleInsert" onclick="hideUpdateForm()">
                <span class="underline">H</span>ide Update Form
            </button>
            <button acesskey="U" type="submit" name="submit" tabindex="25" class="positive update"
                    id="submit">
                <span class="underline">U</span>pdate Record
            </button>
        </fieldset>
    </form>
</div>
<div class="clear"></div>
<div class="display">
    <div id="displayRecord" style="display: none">
        <fieldset class="displayElements">
            <div class="legend">
                <span id="legendDisplayAssignment">Bookmark Record Details</span>
            </div>
            <dl>
                <dt style="width: 15%;">
                    <label for="bookmarkId">Bookmark ID :</label>
                </dt>
                <dd style="width: 30%">
                    <span id="bookmarkId"></span>
                </dd>
                <dt style="width: 15%">
                    <label for="pageName">Bookmark Name :</label>
                </dt>
                <dd style="width: 30%">
                    <span id="pageName_d"></span>
                </dd>
            </dl>
            <dl>
                <dt style="width: 15%;">
                    <label for="url">URL :</label>
                </dt>
                <dd style="width: 80%">
                    <span id="url_d"></span>
                </dd>
            </dl>
            <dl>
                <dt style="width: 15%;">
                    <label for="priority">Priority :</label>
                </dt>
                <dd style="width: 30%">
                    <span id="priority_d"></span>
                </dd>
                <dt style="width: 15%">
                    <label for="redirect">Redirect :</label>
                </dt>
                <dd style="width: 30%">
                    <span id="redirect_d"></span>
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
            type="hidden" name="position_d" id="position_d" value="" />
            <button accesskey="A" type="button" class="positive activate"
                    name="activateRecord_d" tabindex="26" id="activateRecord_d">
                <span class="underline">A</span>ctivate Record
            </button>
            <button accesskey="D" type="button" class="negative drop" name="dropRecord_d"
                    tabindex="27" id="dropRecord_d">
                <span class="underline">D</span>rop Record
            </button>
            <button accesskey="H" type="button" name="submit" tabindex="29" class="regular hide"
                    id="submit" onclick="hideDetailsPortion()">
                <span class="underline">H</span>ide Details Portion
            </button>
            <button accesskey="U" type="submit" name="editRecordButton" class="positive edit"
                    tabindex="28" id="editRecordButton">
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
            <div class="legend">Search Bookmark Records</div>
            <dl>
                <dt style="width: 15%">
                    <label for="menu_hint">Bookmark Name :</label>
                </dt>
                <dd style="width: 30%">
                    <input type="text" name="hint" tabindex="30" id="hint" class=""
                           style="width: 200px"
                           onchange="javascript: valid.validateInput(this);" />
                </dd>
                <dt style="width: 10%">
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
        </fieldset>
        <fieldset class="action buttons">
            <button accesskey="T" type="button" name="toggleInsert1" class="regular toggle"
                    tabindex="32" id="toggleInsert1" onclick="toggleInsertForm()">
                <span class="underline">T</span>oggle Insert Form
            </button>
            <button type="reset" name="searchReset" id="searchReset" class="negative reset"
                    accesskey="L">
                Reset Search Fie<span class="underline">l</span>ds
            </button>
            <button accesskey="S" type="submit" name="submitSearch" tabindex="33"
                    id="submitSearch" class="positive search">
                Get <span class="underline">S</span>earch Results
            </button>
        </fieldset>
    </form>

</div>

<div class="clear"></div>

<div id="displayDatatable" class="buttons" style="display: none">
    <div class="datatable" id="groupMenusM">
        <fieldset>
            <div class="legend">
                <span>Personal Bookmark Tabulated Listing</span>
            </div>
            <table  class="display"
                   id="groupValues">
                <thead>
                <tr>
                    <th>Bookmark Name</th>
                    <th>URL</th>
                    <th style="width: 50px">priority</th>
                    <th style="width: 120px">Browse</th>
                    <th style="width: 180px">View Details</th>
                    <th style="width: 160px">Edit Details</th>

                </tr>
                </thead>
                <tbody>

                </tbody>
            </table>
        </fieldset>
    </div>
</div>