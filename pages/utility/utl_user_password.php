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
require_once BASE_PATH . 'include/global/class.options.php';

$body = new body ();
$options = new options ();
$body->startBody ( 'utility', 'LMENUL74', 'Reset User Password' );

$userId = $_GET ['userId'];
?>
<div id="content_header">
    <div id="pageButton" class="buttons">
        <button type="button" class="regular toggle" onclick="showHideSearchForm()"><span class="underline">T</span>oggle Search Form</button>
        <button type="button" class="regular toggle" onclick="showHideDatatable()"><span class="underline">T</span>oggle Tabulated Data</button>
    </div>
    <div id="contentHeader">Employee Education Details Form</div>
</div>
<input type="hidden" name="userId"
       value="<?php echo $userId; ?>" id="userId" />

<div class="display">
    <div id="candidateInfo">
        <fieldset class="displayElements">
            <div class="legend">
                <span id="legend_mailForm">Details Of The Employee</span>
            </div>
            <dl>
                <dt style="width: 15%">
                    <label for="candidateName">Employee Name :</label>
                </dt>
                <dd style="width: 30%">
                    <span id="candidateName"></span>
                </dd>
                <dt style="width: 15%">
                    <label for="registrationNumber">Employee Code :</label>
                </dt>
                <dd style="width: 30%">
                    <span id="registrationNumber"></span>
                </dd>
            </dl>
            <dl>
                <dt style="width: 15%">
                    <label for="registeredEmail">Official Email :</label>
                </dt>
                <dd style="width: 30%">
                    <span id="registeredEmail"></span>
                </dd>
                <dt style="width: 15%">
                    <label for="designation">Designation :</label>
                </dt>
                <dd style="width: 30%">
                    <span id="designation"></span>
                </dd>
            </dl>
        </fieldset>
    </div>
</div>
<div class="clear"></div>
<div class="inputs">
    <form id="insertForm" class="insertForm" onsubmit="return processInsertForm()">
        <fieldset class="formelements">
            <div class="legend">
                <span>Set / Reset User Password</span>
            </div>
            <dl class="element">
                <dt style="width: 15%">
                    <label for="password">New Password :</label>
                </dt>
                <dd style="width: 30%">
                    <input type="password" name="password" id="password" class="required"
                           tabindex="1" size="30"
                           onchange="javascript: valid.validateInput(this);"
                           title="Set The New Password" />
                    <div id="passwordError" class="validationError" style="display: none"></div>
                </dd>
                <dt style="width: 15%">
                    <label for="newpassword">Retype Password :</label>
                </dt>
                <dd style="width: 30%">
                    <input type="password" name="newpassword" id="newpassword" class="required"
                           tabindex="2" size="required"
                           onchange="javascript: valid.validateInput(this);"
                           title="Retype The Password" />
                    <div id="newpasswordError" class="validationError" style="display: none"></div>
                </dd>
            </dl>

        </fieldset>
        <fieldset class="action buttons">
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
    <form id="userForm" class="userForm" onsubmit="return processUserForm()">
        <fieldset class="formelements">
            <div class="legend">
                <span>Set / Reset User Username</span>
            </div>
            <dl class="element">
                <dt style="width: 15%">
                    <label for="userName">User Name :</label>
                </dt>
                <dd style="width: 30%">
                    <input type="text" name="userName" id="userName" class="required"
                           tabindex="3" size="30"
                           onchange="javascript: valid.validateInput(this);"
                           title="Set The New Password" />
                    <div id="userNameError" class="validationError" style="display: none"></div>
                </dd>
            </dl>

        </fieldset>
        <fieldset class="action buttons">
            <button type="reset" name="userReset" id="userReset" class="negative reset"
                    accesskey="R">
                <span class="underline">R</span>eset Form Fields
            </button>

            <button type="submit" name="submit" id="submit" accesskey="I" class="positive insert">
                Change Username
            </button>
        </fieldset>
    </form>
</div>
<div class="clear"></div>

<div class="inputs">
    <form id="assignmentForm" class="assignmentForm" onsubmit="return processAssignmentForm()">
        <fieldset class="formelements">
            <div class="legend">
                <span>Set User Assignment</span>
            </div>
            <dl class="element">
                <dt style="width: 15%">
                    <label for="userGroup">User Group:</label>
                </dt>
                <dd style="width: 30%">
                    <input type="hidden" name="userGroup_val" id="userGroup_val" />
                    <input type="text" name="userGroup" id="userGroup" class="required autocomplete"
                           tabindex="4" size="30"
                           onchange="javascript: valid.validateInput(this);"
                           title="Set New Usergroup For Candidate" />
                    <div id="userGroup_valError" class="validationError" style="display: none"></div>
                </dd>
            </dl>

        </fieldset>
        <fieldset class="action buttons">
            <button type="reset" name="assignmentReset" id="assignmentReset" class="negative reset"
                    accesskey="R">
                <span class="underline">R</span>eset Form Fields
            </button>

            <button type="submit" class="positive insert">
               Assign New UserGroup
            </button>
        </fieldset>
    </form>
</div>
<div class="clear"></div>
    <div class="datatable buttons" id="groupRecordss">
        <fieldset class="tableElements">
            <div class="legend">
                <span>Tabulated Menu Record Listing</span>
            </div>
            <table  class="display"
                    id="groupRecords">
                <thead>
                <tr>
                    <th>User Group Name</th>
                    <th>Assignment Date</th>
                    <th>Assigned By</th>
                    <th style="width: 150px;">Show Details</th>
                </tr>
                </thead>
                <tbody>

                </tbody>
            </table>
        </fieldset>

    </div>
</form>