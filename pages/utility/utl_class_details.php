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
$body->startBody('utility', 'LMENUL22', 'Class Entry Details');
?>
<div id="content_header">
    <div id="pageButton" class="buttons">
        <button type="button" class="negative toggle" onclick="changeActiveSession()">Change Class Session </button>
        <button type="button" class="positive toggle" onclick="showHideSearchForm()"><span class="underline">T</span>oggle Search Form </button>
        <button type="button" class="regular toggle" onclick="showHideDatatable()"><span class="underline">T</span>oggle Tabulated Data </button>
    </div>
    <div id="contentHeader">Class Details Record Entry Form</div>
</div>
<div class="clear"></div>
<div id="choiceListing" style="display: none">
    <div class="inputs">
        <fieldset class="formelements">
            <div class="legend">
                <span>Change Active Session For The Form</span>
            </div>
            <dl class="element">
                <dt style="width: 15%"><label for="sessionId">Session Name :</label>	</dt>
                <dd style="width: 80%">
                    <input type="hidden" name="session_val" id="session_val" />
                    <input type="text" class="required autocomplete" name="session" size="40" id="session" onchange="checkSessionChange()" />
            </dl>
        </fieldset>
    </div>
</div>
<div class="clear"></div>
<div id="completePageDisplay">
<div class="inputs">
    <form id="insertForm" class="insertForm"
          onsubmit="return valid.validateForm(this) ? processInsertForm() : false;">
        <fieldset class="formelements">
            <div class="legend">
                <span>New Class Entry Form</span>
            </div>
            <dl align="center">
					<span class="inlineFormDisplay">If there are no sections associated
						with the class, leave the section portion blank or else put any
						one section name, others can be added in subsequent screen</span>
            </dl>
            <dl class="element">
                <dt style="width: 15%">
                    <label for="sectionName">Class Name :</label>
                </dt>
                <dd style="width: 30%">
                    <input type="text" name="className" id="className"
                           class="required" title="Enter The Class Name" value=""
                           size="40" onchange="javascript: valid.validateInput(this);"/>

                    <div id="classNameError" class="validationError"
                         style="display: none"></div>
                </dd>
                <dt style="width: 15%">
                    <label for="sectionName">Section Name :</label>
                </dt>
                <dd style="width: 30%">
                    <input type="text" name="sectionName" id="sectionName" class=""
                           title="Enter The Class Section" value="" size="40"
                           onchange="javascript: valid.validateInput(this);"/>

                    <div id="sectionNameError" class="validationError"
                         style="display: none"></div>
                </dd>
            </dl>
            <dl class="element">
                <dt style="width: 15%">
                    <label for="studentCap">Student Capacity :</label>
                </dt>
                <dd style="width: 30%">
                    <input type="text" name="studentCap" id="studentCap"
                           class="required numeric" value="" size="40"
                           title="Enter The Capacity of Class in terms of Students"
                           onchange="javascript: valid.validateInput(this);"/>

                    <div id="studentCapError" class="validationError"
                         style="display: none"></div>
                </dd>
                <dt style="width: 15%">
                    <label for="roomAllocated">Room Allocated :</label>
                </dt>
                <dd style="width: 30%">
                    <input type="hidden" name="roomAllocated_val"
                           id="roomAllocated_val" value=""/> <input type="text"
                                                                    name="roomAllocated" id="roomAllocated"
                                                                    class="required autocomplete"
                                                                    onchange="javascript: valid.validateInput(this);"
                                                                    title="Enter The Room that has been allocated"
                                                                    value=""
                                                                    size="40"/>

                    <div id="roomAllocatedError" class="validationError"
                         style="display: none"></div>
                    </textarea>
                </dd>
            </dl>
            <dl class="element">
                <dt style="width: 15%">
                    <label for="classCoordinator">Class Co-ordinator :</label>
                </dt>
                <dd style="width: 30%">
                    <input type="hidden" name="classCoordinator_val"
                           id="classCoordinator_val"/> <input type="text" size="40"
                                                              name="classCoordinator" id="classCoordinator" class="autocomplete"
                                                              tabindex=""
                                                              onchange="javascript: valid.validateInput(this);"
                                                              title="Class Coordinator"/>

                    <div id="classCoordinator_valError" class="validationError"
                         style="display: none"></div>
                </dd>
                <dt style="width: 15%">
                    <label for="level">Level :</label>
                </dt>
                <dd style="width: 30%">

                    <input type="text" size="15"
                                                              name="level" id="level" class="required numeric"
                                                              tabindex=""
                                                              onchange="javascript: valid.validateInput(this);"
                                                              title="The hierarchy of the class"/>

                    <div id="levelError" class="validationError"
                         style="display: none"></div>
                </dd>
            </dl>
        </fieldset>
        <fieldset class="action buttons">
            <button type="button" name="submit" onclick="hideInsertForm()"
                    class="regular hide" accesskey="H">
                <span class="underline">H</span>ide Insert Form
            </button>

            <button type="reset" name="insertReset" id="insertReset"
                    class="negative reset" accesskey="R">
                <span class="underline">R</span>eset Form Fields
            </button>

            <button type="submit" name="submit" id="submit" accesskey="I"
                    class="positive insert">
                <span class="underline">I</span>nsert New Record
            </button>
        </fieldset>
    </form>
</div>
<div class="clear"></div>
<div class="inputs">
    <form id="updateForm" class="updateForm" style="display: none"
          onsubmit="return valid.validateForm(this) ? processUpdateForm() : false;">
        <fieldset class="formelements">
            <div class="legend">
                <span>Update Class Details</span>
            </div>
            <dl align="center">
					<span class="inlineFormDisplay">A random section is associated with
						the form right now, yet it may have multiple sections which can be
						updated on in the sections form</span>
            </dl>
            <dl class="element">
                <dt style="width: 15%">
                    <label for="sectionName_u">Class Name :</label>
                </dt>
                <dd style="width: 30%">
                    <input type="text" name="className_u" id="className_u"
                           class="required"
                           onchange="javascript: valid.validateInput(this);"
                           title="Enter The Class Name" value="" size="40"/>

                    <div id="className_uError" class="validationError"
                         style="display: none"></div>
                </dd>
                <dt style="width: 15%">
                    <label for="sectionName_u">Section Name :</label>
                </dt>
                <dd style="width: 30%">
                    <input type="text" name="sectionName_u" id="sectionName_u"
                           class="" title="Enter The Class Section" value="" size="40"
                           onchange="javascript: valid.validateInput(this);"/>

                    <div id="sectionName_uError" class="validationError"
                         style="display: none"></div>
                </dd>
            </dl>
            <dl class="element">
                <dt style="width: 15%">
                    <label for="studentCap_u">Student Capacity :</label>
                </dt>
                <dd style="width: 30%">
                    <input type="text" name="studentCap_u" id="studentCap_u"
                           class="required numeric"
                           title="Enter The Capacity of Class in terms of Students"
                           onchange="javascript: valid.validateInput(this);" value=""
                           size="40"/>

                    <div id="studentCap_uError" class="validationError"
                         style="display: none"></div>
                </dd>
                <dt style="width: 15%">
                    <label for="roomAllocated">Room Allocated :</label>
                </dt>
                <dd style="width: 30%">
                    <input type="hidden" name="roomAllocated_uval"
                           id="roomAllocated_uval" value=""/> <input type="text"
                                                                     name="roomAllocated_u" id="roomAllocated_u"
                                                                     class="required autocomplete"
                                                                     onchange="javascript: valid.validateInput(this);"
                                                                     title="Enter The Room that has been allocated"
                                                                     value=""
                                                                     size="40"/>

                    <div id="roomAllocated_uError" class="validationError"
                         style="display: none"></div>
                    </textarea>
                </dd>
            </dl>
            <dl class="element">
                <dt style="width: 15%">
                    <label for="classCoordinator">Class Co-ordinator :</label>
                </dt>
                <dd style="width: 30%">
                    <input type="hidden" name="classCoordinator_uval"
                           id="classCoordinator_uval"/> <input type="text" size="40"
                                                              name="classCoordinator_u" id="classCoordinator_u" class="autocomplete"
                                                              tabindex=""
                                                              onchange="javascript: valid.validateInput(this);"
                                                              title="Class Coordinator"/>

                    <div id="classCoordinator_uError" class="validationError"
                         style="display: none"></div>
                </dd>
                <dt style="width: 15%">
                    <label for="level">Level :</label>
                </dt>
                <dd style="width: 30%">

                    <input type="text" size="15"
                           name="level_u" id="level_u" class="required numeric"
                           tabindex=""
                           onchange="javascript: valid.validateInput(this);"
                           title="The hierarchy of the class"/>

                    <div id="level_uError" class="validationError"
                         style="display: none"></div>
                </dd>
            </dl>
        </fieldset>
        <fieldset class="action buttons">
            <input type="hidden" name="valueId_u" id="valueId_u" value=""/> <input
            type="hidden" name="rowPosition_u" id="rowPosition_u" value=""/> <input
            type="hidden" name="associationId_u" id="associationId_u" value=""/>
            <input type="hidden" name="detailsId_u" id="detailsId_u" value=""/>
            <button type="button" name="showCandidateButtonU"
                    class="positive browse" id="showCandidateButtonU">Show Candidates
            <button type="button" name="showSubjectButtonU"
                    class="positive browse" id="showSubjectButtonU">Show Sections
            </button>
            <button type="button" name="showSectionButtonD"
                    class="regular browse" id="showSectionButtonU">Show Sections
            </button>
            <button type="button" class="positive activate"
                    name="activateRecord_u" id="activateRecord_u">Activate Record
            </button>
            <button type="button" class="negative drop" name="dropRecord_u"
                    id="dropRecord_u">Drop Record
            </button>
            <button type="button" name="submit" class="regular hide" id="submit"
                    onclick="hideUpdateForm()">Hide
            </button>
            <button type="submit" class="positive update" accesskey="u">
                <span class="underline">U</span>pdate Record
            </button>
        </fieldset>
    </form>
</div>
<div class="clear"></div>
<div class="display">
    <div id="displayRecord" style="display: none;">
        <fieldset class="displayElements">
            <div class="legend">
                <span id="legendDisplayDetail">Showing Details Of The Class : </span>
            </div>
            <dl>
                <dt style="width: 15%">
                    <label for="className_d">Class Name :</label>
                </dt>
                <dd style="width: 30%">
                    <span id="className_dDisplay"></span>
                </dd>
                <dt style="width: 15%">
                    <label for="capacity_d">Class Capacity :</label>
                </dt>
                <dd style="width: 30%">
                    <span id="capacity_dDisplay"></span>
                </dd>
            </dl>
            <dl>
                <dt style="width: 15%;">
                    <label for="sectionName_d">Section Name : </label>
                </dt>
                <dd style="width: 30%">
                    <span id="sectionName_d"></span>
                </dd>
                <dt style="width: 15%;">
                    <label for="roomName_d">Room Name : </label>
                </dt>
                <dd style="width: 30%">
                    <span id="roomName_dDisplay"></span>
                </dd>
            </dl>
            <dl>
                <dt style="width: 15%;">
                    <label for="roomNo_d">Room No : </label>
                </dt>
                <dd style="width: 30%">
                    <span id="roomNo_dDisplay"></span>
                </dd>
                <dt style="width: 15%;">
                    <label for="floorNo_d">Floor No : </label>
                </dt>
                <dd style="width: 30%">
                    <span id="floorNo_dDisplay"></span>
                </dd>
            </dl>
            <dl>
                <dt style="width: 15%;">
                    <label for="buildingName_d">Building Name : </label>
                </dt>
                <dd style="width: 30%">
                    <span id="buildingName_dDisplay"></span>
                </dd>
                <dt style="width: 15%;">
                    <label for="seatingCapacityN_d">Seating Capacity(N) : </label>
                </dt>
                <dd style="width: 30%">
                    <span id="seatingCapacityN_dDisplay"></span>
                </dd>
            </dl>
            <dl>
                <dt style="width: 15%;">
                    <label for="classCoordinator">Class Co-ordinator :</label>
                </dt>
                <dd style="width: 30%">
                    <span id="classCoordinator_d"></span>
                </dd>
                <dt style="width: 15%;">
                    <label for="level_d">Level : </label>
                </dt>
                <dd style="width: 30%">
                    <span id="level_d"></span>
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
            <input type="hidden" name="valueId_d" id="valueId_d" value=""/> <input
            type="hidden" name="rowPosition_d" id="rowPosition_d" value=""/>
            <button type="button" name="showCandidateButtonD"
                    class="positive browse" id="showCandidateButtonD">Show Candidates
            </button>
            <button type="button" name="showSectionButtonD"
                    class="negative browse" id="showSectionButtonD">Show Sections
            </button>
            <button type="button" name="showSubjectButtonD"
                    class="regular browse" id="showSubjectButtonD">Show Subjects
            </button>
            <button type="button" class="regular hide"
                    onclick="hideDisplayPortion()">Hide
            </button>
            <button type="button" class="positive activate"
                    name="activateRecord_d" id="activateRecord_d">Activate Record
            </button>
            <button type="button" class="negative drop" name="dropRecord_d"
                    id="dropRecord_d">Drop Record
            </button>
            <button type="button" class="positive edit" id="editRecordButton"
                    class="editRecordButton">Edit Record
            </button>

        </fieldset>
    </div>
</div>
<div class="clear"></div>
<div class="inputs">
    <form id="searchForm" class="searchForm"
          onsubmit="return valid.validateForm(this) ? getSearchResults() : false;">
        <fieldset class="formelements">
            <div class="legend">Search Value</div>
            <dl>
                <dt style="width: 15%">
                    <label for="option_hint">Class Name :</label>
                </dt>
                <dd style="width: 30%">
                    <input type="text" name="class_hint" id="class_hint" class=""
                           style="width: 200px" title="Enter The Class Hint"/>
                </dd>
                <dt style="width: 15%">
                    <label for="search_type">Search Type :</label>
                </dt>
                <dd>
                    <select name="search_type" id="search_type" style="width: 150px">
                        <option value="all">All Classes</option>
                        <option value="1" selected="selected">Active Classes</option>
                        <option value="0">In-Active Classes</option>
                    </select>
                </dd>
            </dl>
        </fieldset>
        <fieldset class="action buttons">
            <button type="button" name="toggleInsert" id="toggleInsert"
                    class="regular toggle" onclick="toggleInsertForm()">Toggle Insert
                Form
            </button>
            <button type="submit" name="searchData" id="searchData"
                    class="positive search">Get Search Results
            </button>
        </fieldset>
    </form>
</div>
<div class="clear"></div>
<div id="displayDatatable" class="datatable buttons" style="display: none">
    <fieldset class="tableElements">
        <div class="legend">
            <span>Tabulated Listing Of Class Records</span>
        </div>
        <table  class="display"
               id="groupClasses">
            <thead>
            <tr>
                <th>Class Name</th>
                <th>Level</th>
                <th width="130px">Candidates</th>
                <th width="120px">Subjects</th>
                <th width="120px">Sections</th>
                <th width="100px">View</th>
                <th width="100px">Edit</th>
            </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </fieldset>
</div>
</div>
