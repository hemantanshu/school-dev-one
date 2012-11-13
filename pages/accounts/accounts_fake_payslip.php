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
$body->startBody ( 'accounts', 'LMENUL128', 'Employee Salary Fake Slip For Current Month' );
?>
<div id="content_header">
    <div id="pageButton" class="buttons">
    	<button type="button" class="negative toggle" onclick="changeEmployeeName()">Change Employee </button>
    </div>
    <div id="contentHeader">Employee Salary Details For Current Month</div>
</div>
<div class="clear"></div>
<div id="choiceListing">
    <div class="inputs">
        <fieldset class="formelements">
            <div class="legend">
                <span>Select Employee Salary Details</span>
            </div>
            <dl class="element">
                <dt style="width: 15%"><label for="employee">Employee Name :</label>	</dt>
                <dd style="width: 80%">
                    <input type="hidden" name="employee_val" id="employee_val" />
                    <input type="text" class="required" name="employee" id="employee" size="30" id="session" onblur="checkEmployeeChange()" />
            </dl>
        </fieldset>
    </div>
</div>
<div class="clear"></div>
<div id="completePageDisplay" style="display: none">
    <div class="display">
        <div id="displaySubjectRecord">
            <fieldset class="displayElements">
                <dl>
                    <dt style="width: 15%;">
                        <label for="employeeName">Employee Name :</label>
                    </dt>
                    <dd style="width: 30%">
                        <span id="employeeName"></span>
                    </dd>
                    <dt style="width: 15%">
                        <label for="employeeCode">Employee Code :</label>
                    </dt>
                    <dd style="width: 30%">
                        <span id="employeeCode"></span>
                    </dd>
                </dl>
            </fieldset>
        </div>
    </div>
    <div class="clear"></div>
    <div class="display">
    	<table id="employeeSalary" id="employeeSalary" cellpadding="0" cellspacing="0" border="0" width="100%">
    	
    	</table>    	
    </div>
</div>
