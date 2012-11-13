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
require_once BASE_PATH . 'include/accounts/class.salarySlip.php';
require_once BASE_PATH . 'include/accounts/class.accountHead.php';
require_once BASE_PATH . 'include/accounts/class.fund.php';


$body = new body ();
$registration = new employeeRegistration ();
$salary = new SalarySlip ();
$accountHead = new AccountHead();
$fund = new Fund();

$body->startBody ( 'accounts', 'LMENUL158', 'Employee Salary Info','', false );

$employeeId = $_GET['employeeId'];
$month = $_GET['month'];
?>
<div class="clear"></div>
<div class="display">
    <div>
        <fieldset class="displayElements">
			<div class="legend">
				<span>Employee Details</span>
			</div>
			
			<dl>
                <dt style="width: 15%;">
                    <label for="employeeName">Employee Name :</label>
                </dt>
                <dd style="width: 30%">
                    <span id="employeeName"><?php echo $body->getOfficerName($employeeId); ?></span>
                </dd>
                <dt style="width: 15%">
                    <label for="employeeCode">Emp Code :</label>
                </dt>
                <dd style="width: 30%">
                    <span id="employeeCode"><?php echo $body->getEmployeeCode($employeeId); ?></span>
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
				<span>Employee Salary Details</span>
			</div>
			<dl>
				<table border="1px" cellpadding="0" cellspacing="0" width="100%" align="center">
                            <tr>
                                <th width="33%">EARNINGS</th>
                                <th width="33%">DEDUCTIONS</th>
                                <th width="*">SUMMARY</th>
                            </tr>
                            <tr>
                                <td>
                                    <table align="center" width="100%" border="0">
               <?php                      
                            $salaryCreditIds = $salary->getEmployeeSalaryDetails($employeeId, $month, $month, true);
                            $earnings = 0;
                            $deductions = 0;
                            foreach ($salaryCreditIds as $salaryDetails) {
                                echo "
                                        <tr>
                                            <td align=\"right\" width=\"60%\">".$accountHead->getReservedAccountHeadName($salaryDetails[0], $month)." :</td>
                                            <td align=\"center\" width=\"3%\"></td>
                                            <td align=\"left\" width=\"*\"><font class=\"salaryPrint\">Rs. ".number_format($salaryDetails[1], 2, '.', '')."</font></td>
                                        </tr>
                                        <tr>
                                            <td colspan=\"3\" height=\"5px\"></td>
                                       </tr>";
                                $earnings += $salaryDetails[1];
                            }
              ?>			
              						</table>
                            	</td>
                            	<td>
                            		<table align="center" width="100%" border="0">
             <?php 
             
                            $salaryDebitIds = $salary->getEmployeeSalaryDetails($employeeId, $month, $month, false);
							foreach ($salaryDebitIds as $salaryDetails) {
                                echo "
                                        <tr>
                                            <td align=\"right\" width=\"60%\">".$accountHead->getReservedAccountHeadName($salaryDetails[0], $month)." :</td>
                                            <td align=\"center\" width=\"3%\"></td>
                                            <td align=\"left\" width=\"*\"><font class=\"salaryPrint\">Rs. ".number_format(abs($salaryDetails[1]), 2, '.', '')."</font></td>
                                        </tr>
                                        <tr>
                                            <td colspan=\"3\" height=\"5px\"></td>
                                       </tr>";
                                $deductions += abs($salaryDetails[1]);
                            }
       		?>
                            
                            		</table>
                            	</td>
                            	<td>
                            	
           <?php 
           			echo "          
                            		<table align=\"center\" width=\"100%\" border=\"0\">
                            			<tr>
				                            <td align=\"right\" width=\"60%\">Total Earnings :</td>
				                            <td align=\"center\" width=\"3%\"></td>
				                            <td align=\"left\" width=\"*\"><font class=\"salaryPrint\">Rs. ".number_format($earnings, 2, '.', '')."</font></td>
			                            </tr>
			                            <tr>
			                            	<td colspan=\"3\" height=\"5px\"></td>
			                            </tr>
			                            <tr>
				                            <td align=\"right\" width=\"60%\">Total Deductions :</td>
				                            <td align=\"center\" width=\"3%\"></td>
				                            <td align=\"left\" width=\"*\"><font class=\"salaryPrint\">Rs. ".number_format($deductions, 2, '.', '')."</font></td>
			                            </tr>
			                            <tr>
			                            	<td colspan=\"3\" height=\"5px\"></td>
			                            </tr>
			                            <tr>
				                            <td align=\"right\" width=\"60%\">Net Pay :</td>
				                            <td align=\"center\" width=\"3%\"></td>
				                            <td align=\"left\" width=\"*\"><font class=\"salaryPrint\">Rs. ".number_format(($earnings - $deductions), 2, '.', '')."</font></td>
			                            </tr>";
                            $collegeContribution = $fund->getInstituteContributionFund4EmployeeMonth($employeeId, $month);
                            if ($collegeContribution){
                            	echo "
                            			<tr>
                            				<td colspan=\"3\" height=\"5px\"></td>
                            			</tr>
                            			<tr>
			                            	<td align=\"right\" width=\"60%\">Inst. Contribution :</td>
			                            	<td align=\"center\" width=\"3%\"></td>
			                            	<td align=\"left\" width=\"*\"><font class=\"salaryPrint\">Rs. ".number_format($collegeContribution, 2, '.', '')."</font></td>
                            			</tr>";
                            }
                            ?>
                            			<tr>
                            				<td colspan="3" height="5px"></td>
                            			</tr>
                            		</table>
                            	</td>
                            </tr>
                         </table>
                         </dl>
              		</fieldset>
	</form>
</div>
