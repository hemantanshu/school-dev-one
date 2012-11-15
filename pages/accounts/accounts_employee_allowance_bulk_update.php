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

$body->startBody('accounts', 'LMENUL123', 'Employee Bulk Allowance Updation');

?>

<div id="content_header">
    <div id="pageButton" class="buttons">
    	<button type="button" class="negative toggle" onclick="changeAllowanceName()">Change Allowance Name </button>
        <button type="button" class="regular toggle" onclick="showHideSearchForm()">Toggle Search Form</button>
        <button type="button" class="regular toggle" onclick="showHideDatatable()">Toggle Tabulated Data</button>
    </div>
    <div id="contentHeader">Bulk Employee Master Salary Allowance Update</div>
</div>
<div class="clear"></div>
<div id="choiceListing">
    <div class="inputs">
        <fieldset class="formelements">
            <div class="legend">
                <span>Select Allowance For Master Salary Record</span>
            </div>
            <dl class="element">
                <dt style="width: 15%"><label for="allowance">Allowance Name :</label>	</dt>
                <dd style="width: 80%">
                    <input type="hidden" name="allowance_val" id="allowance_val" />
                    <input type="text" class="required autocomplete" name="allowance" id="allowance" size="40" onblur="checkAllowanceChange()" />
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
                        <th>Salary Amt</th>
                        <th>Calc Amt</th>
                        <th>Copy Amt</th>
                        <th>Zero Amt</th>                        
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
                        $calculatedAmount = "calculatedAmount".$employeeId;
                        $employeeAmount = "employeeAmount".$employeeId;
                        
                        echo "
                			<tr class=\"odd\" id=\"$rowId\">
			                    <th>".$registration->getEmployeeRegistrationNumber($employeeId)."</th>
			                    <th>".$registration->getOfficerName($employeeId)."</th>
			                    <th>
			                    	<input type=\"text\" name=\"$insertedAmount\" id=\"$insertedAmount\" class=\"required\" size=\"10\" style=\"padding-left: 10px;\" />
			                    	<input type=\"hidden\" name=\"$employeeAmount\" id=\"$employeeAmount\" class=\"required\" />
			                    	<input type=\"hidden\" name=\"$calculatedAmount\" id=\"$calculatedAmount\" class=\"required\" />
			                    </th>
			                    <th id=\"$employeeId\"></th>
			                    <th style=\"width: 160px\"><button type=\"button\" class=\"regular browse\" onclick=\"copyCalculatedAmount('".$employeeId."')\">Copy Amount</th>
			                    <th style=\"width: 160px\"><button type=\"button\" class=\"negative drop\" onclick=\"nullifyAmount('".$employeeId."')\">Nullify Amount</th>
			                    <th style=\"width: 220px\"><button type=\"button\" class=\"positive insert\" id=\"$buttonId\" onclick=\"updateEmployeeMasterSalary('".$employeeId."', '".$nextEmployeeId."')\">Update Master Salary</th>
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
	                        <th>MasterSalary Amt</th>
	                        <th>Calculated Amt</th>
	                        <th>Over Ridden</th>
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