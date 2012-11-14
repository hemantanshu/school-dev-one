<?php
require_once 'config.php';
require_once BASE_PATH.'include/global/class.body.php';

$body = new body();
$body->startBody('global', 'LMENUL50', 'Menu Priority Details');
?>
<div id="content_header">
    <div id="pageButton" class="buttons">
        <button type="button" class="regular toggle" onclick="showChoiceListing()"><span class="underline">S</span>how Choice Listing</button>
    </div>
    <div id="contentHeader">Menu Priority Setup Form</div>
</div>
<div id="choiceListing">
    <div class="inputs">
        <fieldset class="formelements">
            <div class="legend">
                <span>Select The Child Menu</span>
            </div>
            <dl class="element">
                <dt style="width: 15%"><label for="parentUrl">Child Menu URL :</label>	</dt>
                <dd style="width: 80%">
                    <input type="hidden" name="childMenu_val" id="childMenu_val" />
                    <input type="text" class="required autocomplete" name="childMenu" size="40" id="childMenu" onblur="getChildMenuData()" />
            </dl>
        </fieldset>
    </div>
</div>
<div id="completePageListing">
    <div class="display">
        <div id="childMenuListingDetails">
            <fieldset class="displayElements">
                <div class="legend">
                    <span>Selected Child Menu</span>
                </div>
                <dl>
                    <dt style="width: 15%;"><label for="menu_name">Menu Name : </label></dt>
                    <dd style="width: 30%;"><span id="menuNameDisplay_2d"></span></dd>
                    <dt style="width: 15%;"><label for="menuIdDisplay">Menu ID :</label></dt>
                    <dd style="width: 30%;"><span id="menuIdDisplay_2d"></span></dd>
                </dl>
                <dl>
                    <dt style="width: 15%;"><label for="tagline">Tagline : </label></dt>
                    <dd style="width: 30%;"><span id="tagline_2d"></span></dd>
                    <dt style="width: 15%;"><label for="description">Description :</label></dt>
                    <dd style="width: 30%;"><span id="description_2d"></span></dd>
                </dl>
                <dl>
                    <dt style="width: 15%;"><label for="menuUrlDisplay">Menu URL : </label></dt>
                    <dd style="width: 30%;"><span id="menuUrlDisplay_2d"></span></dd>
                    <dt style="width: 15%;"><label for="active">Active/ Inactive : </label></dt>
                    <dd style="width: 30%;"><span id="active_2d"></span></dd>
                </dl>
            </fieldset>
        </div>
    </div>

    <div class="inputs">
        <form id="insertForm" class="insertForm"
              onsubmit="return valid.validateForm(this) ? processInsertForm() : false;">
            <fieldset class="formelements">
                <div class="legend">
                    <span>New Menu Parent Record Form</span>
                </div>
                <dl class="element">
                    <dt style="width: 15%"><label for="parentUrl">Parent URL :</label>	</dt>
                    <dd style="width: 80%">
                        <input type="hidden" name="parentMenu_val" id="parentMenu_val" />
                        <input type="text" size="30" name="parentMenu" id="parentMenu" class="required autocomplete" onblur="getInsertMenuDetails()" tabindex="1" onchange="javascript: valid.validateInput(this);" title="" />
                        <div id="Error" class="validationError" style="display: none"></div></dd>
                </dl>
                <div class="display">
                    <fieldset class="displayElements" id="insertMenuDetails">
                        <div id="inlineMenuDetailsDisplay">
                            <dl>
                                <dt style="width: 15%;"><label for="menu_name">Menu Name : </label></dt>
                                <dd style="width: 30%;"><span id="menuNameDisplay"></span></dd>
                                <dt style="width: 15%;"><label for="menuIdDisplay">Menu ID :</label></dt>
                                <dd style="width: 30%;"><span id="menuIdDisplay"></span></dd>
                            </dl>
                            <dl>
                                <dt style="width: 15%;"><label for="tagline">Tagline : </label></dt>
                                <dd style="width: 30%;"><span id="tagline"></span></dd>
                                <dt style="width: 15%;"><label for="description">Description :</label></dt>
                                <dd style="width: 30%;"><span id="description"></span></dd>
                            </dl>
                            <dl>
                                <dt style="width: 15%;"><label for="menuEdit">Menu Edit : </label></dt>
                                <dd style="width: 30%;"><span id="menuEdit"></span></dd>
                                <dt style="width: 15%;"><label for="menuAuth">Authentication Required :</label></dt>
                                <dd style="width: 30%;"><span id="menuAuth"></span></dd>
                            </dl>
                            <dl>
                                <dt style="width: 15%;"><label for="menuUrlDisplay">Menu URL : </label></dt>
                                <dd style="width: 30%;"><span id="menuUrlDisplay"></span></dd>
                                <dt style="width: 15%;"><label for="active">Active/ Inactive : </label></dt>
                                <dd style="width: 30%;"><span id="active"></span></dd>
                            </dl>
                        </div>
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
                </div>
            </fieldset>
        </form>
    </div>
    <div class="clear"></div>
    <div class="display">
        <div id="displayRecord">
            <fieldset class="displayElements">
                <div class="legend">
                    <span id="legendDisplayAssignment">Parent Menu Record Details</span>
                </div>
                <hr />
                <dl>
                    <dt style="width: 15%;"><label for="menu_name">Menu Name : </label></dt>
                    <dd style="width: 30%;"><span id="menuNameDisplay_1d"></span></dd>
                    <dt style="width: 15%;"><label for="menuIdDisplay">Menu ID :</label></dt>
                    <dd style="width: 30%;"><span id="menuIdDisplay_1d"></span></dd>
                </dl>
                <dl>
                    <dt style="width: 15%;"><label for="tagline">Tagline : </label></dt>
                    <dd style="width: 30%;"><span id="tagline_1d"></span></dd>
                    <dt style="width: 15%;"><label for="description">Description :</label></dt>
                    <dd style="width: 30%;"><span id="description_1d"></span></dd>
                </dl>
                <dl>
                    <dt style="width: 15%;"><label for="menuEdit">Menu Edit : </label></dt>
                    <dd style="width: 30%;"><span id="menuEdit_1d"></span></dd>
                    <dt style="width: 15%;"><label for="menuAuth">Authentication Required :</label></dt>
                    <dd style="width: 30%;"><span id="menuAuth_1d"></span></dd>
                </dl>
                <dl>
                    <dt style="width: 15%;"><label for="menuUrlDisplay">Menu URL : </label></dt>
                    <dd style="width: 30%;"><span id="menuUrlDisplay_1d"></span></dd>
                    <dt style="width: 15%;"><label for="active">Active/ Inactive : </label></dt>
                    <dd style="width: 30%;"><span id="active_1d"></span></dd>
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
                        name="activateMenuUrl_d" tabindex="26" id="activateMenuUrl_d">
                    <span class="underline">A</span>ctivate Record
                </button>
                <button accesskey="D" type="button" class="negative drop" name="dropMenuUrl_d"
                        tabindex="27" id="dropMenuUrl_d">
                    <span class="underline">D</span>rop Record
                </button>
                <button accesskey="H" type="button" name="submit" tabindex="29" class="regular hide"
                        id="submit" onclick="hideDisplayForm()">
                    <span class="underline">H</span>ide Details Portion
                </button>
            </fieldset>

        </div>
    </div>
    <div class="clear"></div>
    <div class="inputs">
        <form id="searchForm" class="searchForm"
              onsubmit="return valid.validateForm(this) ? getSearchDetails() : false;">
            <fieldset class="formelements">
                <div class="legend">Search Menu Parent Details</div>
                <dl>
                    <dt style="width: 15%">
                        <label for="menu_hint">Menu Name :</label>
                    </dt>
                    <dd style="width: 30%">

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

    <div id="displayDatatable" class="buttons">
        <div class="datatable" id="groupMenusM">
            <fieldset>
                <div class="legend">
                    <span>Personal Bookmark Tabulated Listing</span>
                </div>
                <table  class="display"
                       id="groupValues">
                    <thead>
                    <tr>
                        <th>Menu URL</th>
                        <th>Parent URL</th>
                        <th style="width: 180px">View Details</th>
                    </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </fieldset>
        </div>
    </div>
</div>