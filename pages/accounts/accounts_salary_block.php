<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
require_once 'config.php';
require_once BASE_PATH . 'include/global/class.body.php';
$body = new body();
$body->startBody('accounts', 'LMENUL159', 'Accounts Salary Block');
?>

<div id="content_header">
    <div id="pageButton" class="buttons">
        <button type="button" name="toggleSearchForm" class="regular toggle" onclick="showHideSearchForm()" ><span class="underline">T</span>oggle Search Form</button>
        <button type="button" name="toggleTabulatedData" class="regular toggle" onclick="showHideDatatable()" ><span class="underline">T</span>oggle Tabulated Data</button>
    </div>
    <div id="contentHeader">Block Employee Salary</div>
</div>
<div class="clear"></div>

<div class="clear"></div>
<div class="inputs">
    <div id="inputRecords">
        <form name="insertForm" id="insertForm" onsubmit="return valid.validateForm(this) ? processInsertForm() : false;">
            <fieldset class="formelements">
                <div class="legend">
                    <span id="insertRecord">Insert Record</span>
                </div>
                <dl class="elements">
                    <div style="height: 5px"></div>
                    <dt style="width: 15%">
                    <label for="employeeName_i"> Employee Name : </label>
                    </dt>
                    <dd style="width: 30%">
                        <input type="hidden" name="employeeName_val" id="employeeName_val" value="" />
                        <input type="text" name="employeeName_i" id="employeeName_i" required="required" 
                               title="Enter Name of an Employee" size="30" class="required"
                               onchange="javascript: valid.validateInput(this);" tabindex="1"/>
                        <div class="validationError" id="employeeName_iError"></div>
                    </dd>
                </dl>
                <dl class="element">
                    <div style="height: 5px"></div>
                    <dt style="width: 15%">
                    <label for="startDate_i"> Start Date : </label>
                    </dt>
                    <dd style="width: 30%">
                        <input type="text" name="startDate_i" id="startDate_i" required="required" 
                                title="Enter Start Date of your Employeement" size="30"
                                class="date required" onchange="javascript: valid.validateInput(this)" tabindex="2"/>
                        <div id="startDate_iError" class="validationError"></div>
                    </dd>
                    <dt style="width: 15%">
                    <label for="endDate_i"> End Date : </label>
                    </dt>
                    <dd style="width: 30%">
                        <input type="text" name="endDate_i" id="endDate_i" class="date"
                               title="Enter end Date" size="30" tabindex="3"
                               onchange="javascript: valid.validateInput(this)"/>
                        <div id="endDate_iError" class="validationError"></div>
                    </dd>
                </dl>
                <dl>
                    <dt style="width: 15%">
                    <label for="comment_i"> Comments : </label>
                    </dt>
                    <dd style="width: 30%">                        
                        <textarea id="comment_i" name="comment_i" title="Your FeedBack" 
                                  onchange="javascript: valid.validateInput(this)" tabindex="4"
                                  cols="30" rows="3">
                        </textarea>
                        <div id="comment_iError" class="validationError"></div>
                    </dd>
                </dl>
            </fieldset>
        <fieldset class="action buttons">
            <button type="reset" name="insertReset_i" id="insertReset_i" class="negative reset">Reset Forms</button>
            <button type="button" name="hideInsertForm_i" id="hideInsertForm_i" class="regular hide" onclick="hideInsertForm()">Hide Insert Form</button>
            <button type="submit" name="submit" id="submit" class="positive insert" accesskey="I">Submit Record</button>
        </fieldset>
        </form>
    </div>
</div>
    
<div class="clear"></div>
<div class="inputs">
        <form name="updateForm" id="updateForm" class="updateForm" onsubmit="return valid.validateForm(this) ? processUpdateForm() : false" style="display: none">
        <fieldset class="formelements">
            <div class="legend">
                <span id="editRecord">Edit Record</span>
            </div>
            <dl class="elements">
                    <div style="height: 5px"></div>
                    <dt style="width: 15%">
                    <label for="employeeName_e"> Employee Name : </label>
                    </dt>
                    <dd style="width: 30%">
                        <input type="text" name="employeeName_e" id="employeeName_e" 
                               title="Enter Name of an Employee" size="30" readonly="readonly"
                               onchange="javascript: valid.validateInput(this);" tabindex="11"/>
                        <div class="validationError" id="employeeName_eError"></div>
                    </dd>
                    
                </dl>
                <dl class="element">
                    <dt style="width: 15%">
                    <label for="startDate_e"> Start Date : </label>
                    </dt>
                    <dd style="width: 30%">
                        <input type="text" name="startDate_e" id="startDate_e" 
                                title="Enter Start Date of your Employeement" size="30" readonly="readonly"
                                class="date" onchange="javascript: valid.validateInput(this)" tabindex="12"/>
                        <div id="startDate_eError" class="validationError"></div>
                    </dd>
                    <dt style="width: 15%">
                    <label for="endDate_e"> End Date : </label>
                    </dt>
                    <dd style="width: 30%">
                        <input type="text" name="endDate_e" id="endDate_e" class="date"
                               title="Enter end Date of Employeement" size="30" tabindex="13"
                               onchange="javascript: valid.validateInput(this)"/>
                        <div id="endDate_eError" class="validationError"></div>
                    </dd>
                </dl>
                <dl>
                    <dt style="width: 15%">
                    <label for="comment_e"> Comments : </label>
                    </dt>
                    <dd style="width: 30%">                        
                        <textarea id="comment_e" name="comment_e" title="Your FeedBack" 
                                  onchange="javascript: valid.validateInput(this)" tabindex="14"
                                  cols="30" rows="3" readonly="readonly">
                        </textarea>
                        <div id="endDate_iError" class="validationError"></div>
                    </dd>
                </dl>
            </fieldset>
        
        <fieldset class="action buttons">
            <input type="hidden" name="valueId_e" id="valueId_e" value="" /> 
            <input type="hidden" name="rowPosition_e" id="rowPosition_e" value="" />
            <button type="button" class="positive activate" name="activateRecord_e" id="activateRecord_e">Activate Record</button>
            <button type="button" class="negative drop" name="dropRecord_e" id="dropRecord_e" > Drop Record</button>
            <button type="button" class="regular hide" name="hideUpdatePortion" id="hideUpdatePortion" onclick="hideUpdateForm()">Hide Update Portion</button>
            <button type="button" class="positive update" name="updateRecord_e" id="updateRecord_e"> Update Record</button>
        </fieldset>
    </form>
</div>


<div class="clear"></div>
<div class="display">
    <div id="displayRecord" style="display:none">
        <fieldset class="displayElements">
            <div class="legend">
                <span id="displayRecord">Display Record</span>
            </div>
            <dl>
                <dt style="width: 15%;">
                    <label for="employeeName_d"> Employee Name :</label>
                </dt>
                <dd style="width: 30%">
                    <span id="employeeName_d"></span>
                </dd>
                <dt style="width: 15%;">
                    <label for="employeeCode_d"> Employee Code :</label>
                </dt>
                <dd style="width: 30%">
                    <span id="employeeCode_d"></span>
                </dd>
            </dl>
            <dl>
                <dt style="width: 15%;">
                    <label for="startDate_d">Start Date : </label>
                </dt>
                <dd style="width: 30%">
                    <span id="startDate_d"></span>
                </dd>
                <dt style="width: 15%">
                    <label for="endDate_d">End Date :</label>
                </dt>
                <dd style="width: 30%">
                    <span id="endDate_d"></span>
                </dd>
            </dl>
            <dl>
                <dt style="width: 15%">
                    <label for="comments_d">Comments :</label>
                </dt>
                <dd style="width: 30%">
                    <span id="comments_d"></span>
                </dd>
            </dl>
            <dl>
                <dt style="width: 15%;">
                    <label for="lastUpdateDate_d"> Last Update Date: </label>
                </dt>
                <dd style="width: 30%">
                    <span id="lastUpdateDate_d"></span>
                </dd>
                <dt style="width: 15%;">
                    <label for="lastUpdatedBy_d">Last Updated By : </label>
                </dt>
                <dd style="width: 30%">
                    <span id="lastUpdatedBy_d"></span>
                </dd>
            </dl>
            <dl>
                <dt style="width: 15%">
                <label for="createdDate_d">Created Date :</label>
                </dt>
                <dd style="width: 30%">
                    <span id="createdDate_d"></span>
                </dd>
                <dt style="width: 15%">
                <label for="createdBy_d">Created By :</label>
                </dt>
                <dd style="width: 30%">
                    <span id="createdBy_d"></span>
                </dd>
            </dl>
            <dl>
                <dt style="width: 15%">
                <label for="active_d">Active :</label>
                </dt>
                <dd style="width: 30%">
                    <span id="active_d"></span>
                </dd>
            </dl>
        </fieldset>
        <fieldset class="action buttons">
            <input type="hidden" name="valueId_d" id="valueId_d" value="" /> 
            <input type="hidden" name="rowPosition_d" id="rowPosition_d" value="" />
            
            <button type="button" class="positive activate" name="activateRecord_d" id="activateRecord_d">Activate Record</button>
            <button type="button" class="negative drop" name="dropRecord_d" id="dropRecord_d">Drop Record</button>
            <button type="button" class="regular hide" name="hideDisplayPortion_d" id="hideDisplayPortion_d" onclick="hideDisplayPortion()">Hide Display Detail Portion</button>
            <button type="button" class="negative edit" name="editRecord_d" id="editRecord_d">Edit Record</button>
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
                    <label for="searchKey">Search Key :</label>
                </dt>
                <dd style="width: 30%">
                    <input type="hidden" name="searchKey_val" id="searchKey_val" value="" />
                    <input type="text" name="searchKey" id="searchKey" class="required" required="required"
                           style="width: 200px" title="Enter key for efficient search" tabindex="31"/>
                    <div id="searchKey_valError" class="validationError"></div>
                </dd>
                
            </dl>
        </fieldset>
        <fieldset class="action buttons">
            <button type="button" name="toggleInsert" id="toggleInsert" class="regular toggle"
                    onclick="toggleInsertForm()" accesskey="T">Toggle Insert Form</button>
            <button type="submit" name="searchData" id="searchData" class="positive search">Search  Results</button>
        </fieldset>
    </form>
</div>

<!-- done here too -->


<div class="clear"></div>
<div class="datatable buttons" id="displayDatatable" style="display:none">
    <fieldset class="formelements">
        <div class="legend">
            <span>Tabulated Listing Of All</span>
        </div>
        <table  class="display"
               id="groupRecords">
            <thead>
                <tr>
                        <th style="width: 150px">Employee Code </th>
                        <th style="width: 170px">Employee Name </th>
                        <th style="width: 160px">Start Month </th>
                        <th style="width: 150px">View Details</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </fieldset>
</div>
