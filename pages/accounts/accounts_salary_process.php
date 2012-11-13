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
require_once BASE_PATH . 'include/accounts/class.salary.php';
require_once BASE_PATH . 'include/global/class.options.php';

$body = new body ();
$registration = new employeeRegistration ();
$salary = new Salary ();
$options = new options ();

$body->startBody ( 'accounts', 'LMENUL129', 'Employee Monthly Salary Process' );
$optionIds = $options->getOptionValueIds ( 'PASAL', 1 );


$totalDays = cal_days_in_month ( CAL_GREGORIAN, date ( 'm' ), date ( 'Y' ) );
$month = date('Ym');

$i = 0;
$paymentOption = '';
foreach ( $optionIds as $optionId ) {
	$paymentOption .= '<option value="' . $optionId . '">' . $options->getOptionIdValue ( $optionId ) . '</option>';
}
?>
<div class="clear"></div>
<div id="displayTable" class="display">
	<form id="entryForm" class="entryForm">
		<fieldset>
			<div class="legend">
				<span id="legendDisplayDetail">Process Employee Salary </span>
			</div>
			<dl>
				<table width="100%" align="left" border="0" class="buttons">
					<tr class="even">
						<th>Emp Code</th>
						<td>Employee Name</td>
						<th>Days</th>
						<th>Payment</th>
						<th>Amount</th>
						<th>Details</th>
						<th>Process</th>
					</tr>
					<tr>
						<td colspan="7"><hr /></td>
					</tr>
             	<?php
					$employeeIds = $salary->getEmployeeIds4SalaryProcessing($month);
					foreach ( $employeeIds as $employeeId ) {						
						$nextEmployeeId = $employeeIds [$i];
						$rowId = "row" . $employeeId;
						$days = "days" . $employeeId;
						$payment = "payment" . $employeeId;
						$buttonId = "button".$employeeId;
						
						echo "
                			<tr class=\"odd\" id=\"$rowId\">
			                    <th>" . $registration->getEmployeeRegistrationNumber ( $employeeId ) . "</th>
			                    <td>" . $registration->getOfficerName ( $employeeId ) . "</td>
			                    <th>
			                    	<input type=\"text\" name=\"$days\" id=\"$days\" class=\"required\" value=\"$totalDays\" size=\"5\" style=\"padding-left: 10px;\" />			                    	
			                    </th>
			                    <th>
			                    	<select name=\"$payment\" id=\"$payment\" class=\"required\" style=\"width: 200px;\">" . $paymentOption . "
			                    	</select>			                    	
			                    </th>
			                    <th id=\"$employeeId\" align=\"right\" style=\"padding-right: 10px\"></th>
			                    <th style=\"width: 180px\"><button type=\"button\" class=\"positive browse\" onclick=\"getEmployeeSalaryDetails('" . $employeeId . "')\">Salary Details</th>
			                    <th style=\"width: 180px\"><button type=\"button\" class=\"negative insert\" id=\"$buttonId\" onclick=\"processEmployeeSalary('" . $employeeId . "', '" . $nextEmployeeId . "')\">Process Salary</th>			                    
			                </tr>";
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
				<span id="legendDisplayDetails">Updated List Of Employee Allowance
					Details</span>
			</div>
			<dl>
				<table width="100%" align="left" border="0" class="buttons">
					<thead>
						<tr class="even">
							<th>Emp Code</th>
							<th>Employee Name</th>
							<th>Days</th>
							<th>Payment</th>
							<th>Amount</th>
						</tr>
						<tr>
							<td colspan="7"><hr /></td>
						</tr>
					</thead>
					<tbody id="updatedTableBody">
					<?php 
						$salaryProcessedEmployeeIds = $salary->getEmployeeIds4SalaryProcessed($month);
						foreach ($salaryProcessedEmployeeIds as $employeeId){
							$salaryProcessId = $salary->checkSalaryProcessRecord($employeeId, $month, 1);
							$details = $salary->getTableIdDetails($salaryProcessId[0]);
							echo "
							<tr class=\"odd\" id=\"$rowId\">
								<th>" . $registration->getEmployeeRegistrationNumber ( $employeeId ) . "</th>
								<td>" . $registration->getOfficerName ( $employeeId ) . "</td>
								<th>".$details['salary_days']."</th>
								<th>".$options->getOptionIdValue($details['payment_mode'])."</th>
								<th>".number_format($salary->getEmployeeSalaryAmount($employeeId, $month), 2, '.', ',')."</th>
							</tr>";
						}
							
					?>
					</tbody>
				</table>
			</dl>
		</fieldset>
	</form>
</div>
<br /><br />