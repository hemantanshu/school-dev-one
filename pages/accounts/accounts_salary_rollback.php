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

$body->startBody ( 'accounts', 'LMENUL157', 'Employee Monthly Salary Rollback' );
$i = 0;
$month = date('Ym');
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
						<th>unProcess</th>
					</tr>
					<tr>
						<td colspan="7"><hr /></td>
					</tr>
             	<?php
					$employeeIds = $salary->getEmployeeIds4SalaryProcessed ( $month );
					foreach ( $employeeIds as $employeeId ) {
						$salaryProcessId = $salary->checkSalaryProcessRecord($employeeId, $month, 1);
						$rowId = "row" . $employeeId;
						$buttonId = "button" . $employeeId;
						$details = $salary->getTableIdDetails($salaryProcessId[0]);
						echo "
                			<tr class=\"odd\" id=\"$rowId\">
			                    <th>" . $registration->getEmployeeRegistrationNumber ( $employeeId ) . "</th>
			                    <td>" . $registration->getOfficerName ( $employeeId ) . "</td>
		                    	<td>" . $details['salary_days'] . "</td>			                    	
		                    	<td>" . $options->getOptionIdValue($details['payment_mode']) . "</td>			                    	
			                    <th align=\"right\" style=\"padding-right: 15px\">" . number_format($salary->getEmployeeSalaryAmount ( $employeeId, $month ), 2, '.', ',') . "</th>
			                    <th style=\"width: 180px\"><button type=\"button\" class=\"positive browse\" onclick=\"getEmployeeSalaryDetails('" . $employeeId . "', '" . $month . "')\">Salary Details</th>
			                    <th style=\"width: 180px\"><button type=\"button\" class=\"negative drop\" id=\"$buttonId\" onclick=\"unProcessEmployeeSalary('" . $employeeId . "')\">Rollback Salary</th>			                    
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
				<span id="legendDisplayDetails">List Of Employee Whose Salary Processing Is Left</span>
			</div>
			<dl>
				<table width="100%" align="left" border="0" class="buttons">
					<thead>
						<tr class="even">
							<th>Emp Code</th>
							<th>Employee Name</th>
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
<br />
<br />