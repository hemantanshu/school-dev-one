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
require_once BASE_PATH . 'include/utility/class.employeeRegistration.php';


$body = new body ();
$registration = new employeeRegistration();

$body->startBody('accounts', 'LMENUL124', 'Employee Direct Allowance Updation');

?>

<div id="content_header">
    <div id="pageButton" class="buttons">
    	<button type="button" class="negative toggle" onclick="changeAllowanceName()">Change Allowance Name </button>
        <button type="button" class="regular toggle" onclick="showHideSearchForm()">Toggle Search Form</button>
        <button type="button" class="regular toggle" onclick="showHideDatatable()">Toggle Tabulated Data</button>
    </div>
    <div id="contentHeader">Employee Direct Salary Addition</div>
</div>
<div class="clear"></div>
<div id="choiceListing">
    <div class="inputs">
    <form id="allowanceForm" class="allowanceForm" onsubmit="return valid.validateForm(this) ? processAllowanceForm() : false;">    
        <fieldset class="formelements">
            <div class="legend">
                <span>Select Allowance For Master Salary Record</span>
            </div>
            <dl class="element">
                <dt style="width: 15%"><label for="allowance">Allowance Name :</label>	</dt>
                <dd style="width: 30%">
                    <input type="hidden" name="allowance_val" id="allowance_val" class="required" onchange="javascript: valid.validateInput(this);"/>
                    <input type="text" class="required" name="allowance" id="allowance" size="30" onchange="javascript: valid.validateInput(this);"/>
                    <div id="allowance_valError" class="validationError"	style="display: none"></div>
                    <div id="allowanceError" class="validationError"	style="display: none"></div>
                <dt style="width: 15%"><label for="month">Month :</label>	</dt>
                <dd style="width: 30%">                    
                    <select class="required" name="month" id="month" style="width: 150px" onchange="javascript: valid.validateInput(this);">
                    <?php 
                    	for ($i = 0; $i < 12; ++$i){
                    		$month = date('Ym', mktime(0, 0, 0, date('m')+$i, 15, date('Y')));
                    		$monthName = date('F, Y', mktime(0, 0, 0, date('m')+$i, 15, date('Y')));
                    		echo "<option value=\"$month\">".$monthName."</option>";
                    	}
                    ?>
                    </select>  
                    <div id="monthError" class="validationError"	style="display: none"></div>                  
            </dl>
        </fieldset>
        <fieldset class="action buttons">
            <button type="reset" name="insertReset" id="insertReset" class="negative reset">Reset Form</button>
            <button type="button" name="submit" class="regular hide" onclick="hideInsertForm()">Hide
                Insert Form</button>
            <button type="submit" name="submit" id="submit" class="positive insert" accesskey="I">Direct Allowance Insert</button>
        </fieldset>
   	</form>     
    </div>
</div>
<div class="clear"></div>
<div id="completePageDisplay" style="display: none">
    <div class="display">
        <div id="displaySubjectRecord">
            <fieldset class="displayElements">
                <dl>
                    <dt style="width: 15%;">
                        <label for="allowanceName">Allowance Name :</label>
                    </dt>
                    <dd style="width: 30%">
                        <span id="allowanceName"></span>
                    </dd>
                    <dt style="width: 15%">
                        <label for="allowanceType">Allowance Type :</label>
                    </dt>
                    <dd style="width: 30%">
                        <span id="allowanceType"></span>
                    </dd>
                </dl>
                <dl>
                    <dt style="width: 15%;">
                        <label for="accountHeadName">AccountHead Name :</label>
                    </dt>
                    <dd style="width: 30%">
                        <span id="accountHeadName"></span>
                    </dd>
                    <dt style="width: 15%">
                        <label for="monthName">Month Name :</label>
                    </dt>
                    <dd style="width: 30%">
                        <span id="monthName"></span>
                    </dd>
                </dl>
            </fieldset>
        </div>
    </div>
    <div class="clear"></div>
    <div id="displayTable" class="display">
    <form id="entryForm" class="entryForm">
        <fieldset>
            <div class="legend">
                <span id="legendDisplayDetail">Update Employee Master Salary </span>
            </div>
            <dl>
                <table width="100%" align="left" border = "0" class="buttons">
                    <tr class="even">
                        <th>Emp Code</th>
                        <th>Employee Name</th>
                        <th>Amt</th>
                        <th>comments</th>
                        <th>Update</th>
                    </tr>
                    <tr>
                        <td colspan="7"><hr /></td>
                    </tr>
                    <?php
                    $i = 1;
                    $employeeIds = $registration->getEmployeeIds(1);
                    foreach ($employeeIds as $employeeId){
                    	$nextEmployeeId = $employeeIds[$i];
                        
                    	$rowId = "row".$employeeId;
                        $buttonId = "button".$employeeId;
                        
                        $insertedAmount = "insertedAmount".$employeeId;                        
                        $employeeAmount = "employeeAmount".$employeeId;
                        
                        $remarksInsert = "remarks".$employeeId;
                        $remarksActual = "remarksActual".$employeeId;
                        
                        echo "
                			<tr class=\"odd\" id=\"$rowId\">
			                    <th>".$registration->getEmployeeRegistrationNumber($employeeId)."</th>
			                    <th>".$registration->getOfficerName($employeeId)."</th>
			                    <th>
			                    	<input type=\"text\" name=\"$insertedAmount\" id=\"$insertedAmount\" class=\"required\" size=\"10\" style=\"padding-left: 10px;\" />
			                    	<input type=\"hidden\" name=\"$employeeAmount\" id=\"$employeeAmount\" class=\"required\" />
			                    	<input type=\"hidden\" name=\"$remarksActual\" id=\"$remarksActual\" class=\"required\" />			                    	
			                    </th>
			                    <th><textarea rows=\"2\" cols=\"30\" name=\"$remarksInsert\" id=\"$remarksInsert\" class=\"required\"></textarea></th>
			                    <th style=\"width: 160px\"><button type=\"button\" class=\"negative browse\" id=\"$buttonId\" onclick=\"insertEmployeeDirectSalary('".$employeeId."', '".$nextEmployeeId."')\">Add Direct Salary</th>
			                </tr>";
                        ++$i;
                    }
                    ?>

                </table>
            </dl>
        </fieldset>
    </form>
	</div>
	
	<div class="clear"></div>
    <div id="finalDisplayTable" class="display">
    <form>
        <fieldset>
            <div class="legend">
                <span id="legendDisplayDetails">Updated List Of Employee Allowance Details</span>
            </div>
            <dl>
                <table width="100%" align="left" border = "0" class="buttons">
                    <thead>
                    	<tr class="even">
	                        <th>Emp Code</th>
	                        <th>Employee Name</th>
	                        <th>Amount</th>
	                        <th>Comments</th>
	                    </tr>
	                    <tr>
	                        <td colspan="7"><hr /></td>
	                    </tr>
                    </thead>
                    <tbody id="updatedTableBody">
                    	
                    </tbody>
                </table>
            </dl>
        </fieldset>
    </form>
	</div>
</div>
<br /><br />