<?php
require_once 'config.php';

require_once BASE_PATH . 'include/accounts/class.salarySlip.php';
require_once BASE_PATH . 'include/accounts/class.payment.php';
require_once BASE_PATH . 'include/accounts/class.accountHead.php';
require_once BASE_PATH . 'include/global/class.options.php';


$salary = new SalarySlip();
$options = new options();
$accountHead = new AccountHead();
$payment = new Payment();

$options->isRequestAuthorised4Form ( 'LMENUL155' );

if (isset ( $_POST ['task'] )) {
    $employeeTypes = $options->getOptionSearchValueIds('', 'EMPTY', 1);
	if ($_POST ['task'] == 'printConsolidatedSlip') {
		$optionIds = $options->getOptionSearchValueIds('', 'PASAL', 1);	
		foreach ($optionIds as $optionId){
			foreach($employeeTypes as $employeeType){
				$month = $_POST['month'];
				$salaryProcessIds = $payment->getSalaryProcessId4PaymentTypeEmployeeType($optionId, $employeeType, $month);
				if(count($salaryProcessIds) > 0){
					echo "
            <table width=\"100%\" align=\"center\" >
                <tr>
                    <td colspan=\"3\" width=\"100%\">
                        <table border=\"0\" align=\"center\" width=\"100%\">
                            <tr>
                                <td align=\"center\" width=\"160px\" height=\"111px\"><img src=\"".$options->getBaseServer()."/images/global/school_logo.png\" alt=\"mnnit logo\" width=\"126px\" height=\"111px\" align=\"left\" /></td>
                                <td align=\"center\" width=\"*\"><font class=\"bigheader\">DELHI PUBLIC SCHOOL, KASHI</font><br /><font class=\"smallheader\">
                                    VARANASI - 221001<br /><br />
                                    Consolidated Slip </font><br />";
					echo "<font class=\"bigheader\">".$options->getOptionIdValue($optionId)." ".$options->getOptionIdValue($employeeType)." </font> <font class=\"month\"> ".$salary->getMonthNameOfMonth($month)."</font>";
					echo						"</td>
			
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                	<td colspan=\"3\">";
					$accountHeadIds = $salary->getTotalAccountHeadListing4EmployeeType($employeeType, $month);
					echo "
                <table align=\"center\" cellpadding=\"1px\" cellspacing = \"1px\" border=\"1\" width=\"100%\" id=\"bankTransferDetails\" >
                    <tr>
                        <th>SN</th>
                        <th>Employee Name</th>";
					foreach($accountHeadIds as $accountHeadId){
						$accountHeadTotal[$accountHeadId] = 0;
						echo "<th>".$accountHead->getAccountHeadName($accountHeadId)."</th>";
					}
					echo "
                	<th>Total</th>
                    </tr>
            		</tr>
                		<th colspan=\"20\" height=\"10px\"></th>
                	</tr>";
					$i = 1;
					$grossTotalAmount = 0;
					foreach($salaryProcessIds as $salaryProcessId){
						$totalAmount = 0;
						$details = $payment->getTableIdDetails($salaryProcessId);
						$employeeId = $details['employee_id'];
						echo "
                    <tr>
                        <th><font class=\"salaryPrint\">".$i."</font></th>
                        <td><font class=\"salaryPrint\"><font class=\"salaryPrint\">".$salary->getCandidateName($employeeId)."</font></td>";
						foreach($accountHeadIds as $accountHeadId){
							$amount = $salary->getEmployeeSalaryInfo4AccountHead($employeeId, $month, $accountHeadId);
							$accountHeadTotal[$accountHeadId] += $amount;
							$totalAmount += $amount;
							echo "<th align=\"right\"><font class=\"salaryPrint\">".number_format($amount, 2, '.', ',')."</font></th>";
						}
						$grossTotalAmount += $totalAmount;
						echo "<th align=\"right\"><font class=\"salaryPrint\">".number_format($totalAmount, 2, '.', ',')."</font></th>";
						echo "
                    </tr>";
						++$i;
					}
					echo "<tr>
                		<td colspan=\"2\">Total</td>";
					foreach ($accountHeadIds as $accountHeadId){
						echo "<th align=\"right\"><font class=\"salaryPrint\">".number_format($accountHeadTotal[$accountHeadId], 2, '.', ',')."</font></th>";
					}
					echo "<th align=\"right\"><font class=\"salaryPrint\">".number_format($grossTotalAmount, 2, '.', ',')."</font></th>";
					 
					echo "</tr>";
					echo "
                	</td>
                </tr>
            </table>
                </td></tr></table>";
					echo "<div style=\"page-break-after:always;\"></div>";
				}
			}
		}
	} else {
		$outputArray = 0;
		echo $outputArray ;
	}
}
?>