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
$options = new options();

$body->startBody('accounts', 'LMENUL132', 'Employee Pay Slip Printing');

?>

<div id="content_header" class="noPrint">
    <div id="pageButton" class="buttons">
    	<button type="button" class="negative toggle" onclick="changeEmployeeName()">Change Employee Group </button>
    </div>
    <div id="contentHeader">Employee Salary Slip Printing</div>
</div>
<div class="clear"></div>
<div id="choiceListing">
    <div class="inputs">
    <form id="allowanceForm" class="allowanceForm" onsubmit="return valid.validateForm(this) ? processAllowanceForm() : false;">    
        <fieldset class="formelements buttons">
            <div class="legend">
                <span>Select Employee Group For Salary Slip Printing</span>
            </div>
            <dl class="element">
                <dt style="width: 15%"><label for="employee">Employee Name :</label>	</dt>
                <dd style="width: 20%">
                	<input type="hidden" name="employee_val" id="employee_val" />
                    <input type="text" name="employee" id="employee" size="40" class="autocomplete" onchange="javascript: valid.validateInput(this);" title="Enter the name of the employee" />
                    <div id="employeeError" class="validationError"	style="display: none"></div></dd>
              	<dt style="width: 15%"><label for="month">Month :</label>	</dt>
              	<dd style="width: 20%">
              		<select name="monthEmployee" id="monthEmployee" onchange="javascript: valid.validateInput(this);" title="select the month of the pay slip">
              		<?php 
              			$i = 0;
              			$monthOptions = '';
              			while(true){              				
              				$month = date('Ym', mktime(0, 0, 0, date('m') - $i, 15, date('Y')));
              				if($month < 201204)
              					break;
              				$monthName = date('F, Y', mktime(0, 0, 0, date('m') - $i, 15, date('Y')));
              				$monthOptions .= "<option value=\"$month\">".$monthName."</option>";
              				++$i;
              			}
              			echo $monthOptions;
              		?>
                    </select>                    
              	</dd>
                <dd style="width: 25%">
                	<button type="button" style="width: 250px; text-align: left" class="positive insert" onclick="printEmployeePaySlip()">Print Employee Salary Slip</button></dd>                                 
            </dl>
            <dl class="element">
                <dt style="width: 15%"><label for="departmentName">Department Name :</label>	</dt>
                <dd style="width: 20%">
                    <select name="departmentName" id="departmentName" style="width: 150px" onchange="javascript: valid.validateInput(this);" title="select the department name">
                    <?php 
                    	$optionIds = $options->getOptionValueIds('DEPTY', 1);
                    	foreach($optionIds as $optionId){
                    		echo "<option value=\"$optionId\">".$options->getOptionIdValue($optionId)."</option>";
                    	}
                    ?>
                    </select>
              	<dt style="width: 15%"><label for="monthDepartment">Month :</label>	</dt>
              	<dd style="width: 20%">
              		<select name="monthDepartment" id="monthDepartment" onchange="javascript: valid.validateInput(this);" title="select the month of the payslip">
              		<?php 
              			echo $monthOptions;
              		?>
                    </select>
              	</dd>
                <dd style="width: 20%">
                	<button type="button" style="width: 250px; text-align: left" class="print insert" onclick="printDepartmentEmployeePaySlip()">Print Department Salary Slip</button></dd>  
                                 
            </dl>
            <dl class="element">
                <dt style="width: 15%"><label for="employeeType">Employee Type :</label>	</dt>
                <dd style="width: 20%">
                    <select name="employeeType" id="employeeType" style="width: 150px" onchange="javascript: valid.validateInput(this);" title="select the employee type">
                    <?php 
                    	$optionIds = $options->getOptionValueIds('EMPTY', 1);
                    	foreach($optionIds as $optionId){
                    		echo "<option value=\"$optionId\">".$options->getOptionIdValue($optionId)."</option>";
                    	}
                    ?>
                    </select>
                    <div id="employeeTypeError" class="validationError"	style="display: none"></div></dd>
              	<dt style="width: 15%"><label for="monthType">Month :</label>	</dt>
              	<dd style="width: 20%">
              		<select name="monthType" id="monthType" onchange="javascript: valid.validateInput(this);" title="select the month of the pay slip">
              		<?php               			
              			echo $monthOptions;
              		?>
                    </select>
              	</dd>
                <dd style="width: 20%">
                	<button type="button" class="regular insert" style="width: 250px; text-align: left" onclick="printEmployeeTypeEmployeePaySlip()">Print Employee Type Salary Slip</button></dd>  
                                 
            </dl>
            <dl class="element">
                <dt style="width: 15%"><label for="allEmployee">All employee :</label>	</dt>
                <dd style="width: 20%">
              	<dt style="width: 15%"><label for="monthAll">Month :</label>	</dt>
              	<dd style="width: 20%">
              		<select name="monthAll" id="monthAll" onchange="javascript: valid.validateInput(this);" title="select the month of the pay slip">
              		<?php               			
              			echo $monthOptions;
              		?>
                    </select>
              	</dd>
                <dd style="width: 20%">
                	<button type="button" class="positive insert" style="width: 250px; text-align: left" onclick="printAllEmployeePaySlip()">Print All Employee Salary Slip</button></dd>  
                                 
            </dl>
        </fieldset>
   	</form>     
    </div>
</div>
<div class="clear"></div>
<div id="completePageDisplay" style="margin: auto; display: none">
      
</div>