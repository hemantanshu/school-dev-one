<?php
require_once 'config.php';

require_once BASE_PATH . 'include/accounts/class.salary.php';
require_once BASE_PATH . 'include/accounts/class.payment.php';
require_once BASE_PATH . 'include/accounts/class.bank.php';
require_once BASE_PATH . 'include/global/class.options.php';


$salary = new Salary();
$payment = new Payment();
$bank = new Bank();
$options = new options();

$options->isRequestAuthorised4Form ( 'LMENUL133' );

if (isset ( $_POST ['task'] )) {
    $employeeTypes = $options->getOptionSearchValueIds('', 'EMPTY', 1);
	if ($_POST ['task'] == 'printBankSlip') {
		foreach($employeeTypes as $employeeType){
            $month = $_POST['month'];
            $salaryProcessIds = $payment->getSalaryProcessId4PaymentTypeEmployeeType('LRESER17', $employeeType, $month);
            if(sizeof($salaryProcessIds, 0)){
                echo "
            <table width=\"100%\" align=\"center\" >
                <tr>
                    <td colspan=\"3\" width=\"100%\">
                        <table border=\"0\" align=\"center\" width=\"100%\">
                            <tr>
                                <td align=\"center\" width=\"160px\" height=\"111px\"><img src=\"".$options->getBaseServer()."/images/global/school_logo.png\" alt=\"mnnit logo\" width=\"126px\" height=\"111px\" align=\"left\" /></td>
                                <td align=\"center\" width=\"*\"><font class=\"bigheader\">DELHI PUBLIC SCHOOL, KASHI</font><br /><font class=\"smallheader\">
                                    VARANASI - 221001<br /><br />
                                    BANK TRANSFER PAYMENT DETAILS </font><br />";
                echo "<font class=\"bigheader\">".$options->getOptionIdValue($employeeType)." </font> <font class=\"month\">".$salary->getMonthNameOfMonth($month)."</font>";
                echo						"</td>

                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                	<td colspan=\"3\">";
                echo "
                <table align=\"center\" cellpadding=\"1px\" cellspacing = \"1px\" width=\"100%\" id=\"bankTransferDetails\" >
                    <tr>
                        <th>SN</th>
                        <th>Employee Name</th>
                        <th>Bank Name</th>
                        <th>IFSC Code</th>
                        <th>Account No</th>
                        <th>Salary</th>
                    </tr>
            		</tr>
                		<th height=\"10px\"></th>
                	</tr>";
                $i = 1;
                foreach($salaryProcessIds as $salaryProcessId){
                    $details = $salary->getTableIdDetails($salaryProcessId);
                    $bankTransferId = $payment->getBankTransferId4Source($salaryProcessId, 1);
                    $bankTransferDetails = $payment->getTableIdDetails($bankTransferId[0]);
                    $bankDetails = $payment->getTableIdDetails($bankTransferDetails['bank_id']);
                    echo "
                    <tr>
                        <th><font class=\"salaryPrint\">".$i."</font></th>
                        <td><font class=\"salaryPrint\"><font class=\"salaryPrint\">".$salary->getCandidateName($details['employee_id'])."</font></td>
                        <th><font class=\"salaryPrint\">".$bankDetails['bank_name'].", ".$bankDetails['branch_name']."</font></th>
                        <th><font class=\"salaryPrint\">".$bankDetails['ifsc_code']."</font></th>
                        <th><font class=\"salaryPrint\">".$bankTransferDetails['account_number']."</font></th>
                        <th align=\"right\"><font class=\"salaryPrint\">".number_format($salary->getEmployeeSalaryAmount($details['employee_id'], $month), 2, '.', ',')."</font></th>
                    </tr>
                	</tr>
                		<th height=\"5px\"></th>
                	</tr>";
                    ++$i;
                }
                echo "
                	</td>
                </tr>
            </table>
                </td></tr></table>";



                echo "<div style=\"page-break-after:always;\"></div>";
            }
        }
	}  elseif($_POST['task'] == 'printCashSlip'){
		foreach($employeeTypes as $employeeType){
            $month = $_POST['month'];
            //other means of transfer
            $salaryProcessIds = $payment->getSalaryProcessId4PaymentTypeEmployeeType('LRESER18', $employeeType, $month);
            if(sizeof($salaryProcessIds, 0)){
                echo "
            <table width=\"100%\" align=\"center\" border=\".2\">
                <tr>
                    <td colspan=\"3\" width=\"100%\">
                        <table border=\"0\" align=\"center\" width=\"100%\">
                            <tr>
                                <td align=\"center\" width=\"160px\" height=\"111px\"><img src=\"".$options->getBaseServer()."/images/global/school_logo.png\" alt=\"mnnit logo\" width=\"126px\" height=\"111px\" align=\"left\" /></td>
                                <td align=\"center\" width=\"*\"><font class=\"bigheader\">DELHI PUBLIC SCHOOL, KASHI</font><br /><font class=\"smallheader\">
                                    VARANASI - 221001<br /><br />
                                    CASH PAYMENT DETAILS </font><br/>";
                echo "<font class=\"bigheader\">".$options->getOptionIdValue($employeeType)." </font> <font class=\"month\">".$salary->getMonthNameOfMonth($month)."</font>";
                echo						"</td>

                            </tr>
                        </table>
                    </td>
                </tr>
            </table>";

                echo "
                <table border=\"1px\" align=\"center\" cellpadding=\"0px\" cellspacing = \"0px\" width=\"100%\">
                    <tr>
                        <th>SN</th>
                        <th>Employee Name</th>
                        <th>Salary</th>
                    </tr>";
                $i = 1;
                foreach($salaryProcessIds as $salaryProcessId){
                    $details = $salary->getTableIdDetails($salaryProcessId);
                    echo "
                    <tr>
                        <th>".$i."</th>
                        <th><font class=\"salaryPrint\">".$salary->getCandidateName($details['employee_id'])."</font></th>
                        <th  align=\"right\"><font class=\"salaryPrint\">".number_format($salary->getEmployeeSalaryAmount($details['employee_id'], $month), 2, '.', ',')."</font></th>
                    </tr>";
                    ++$i;
                }
                echo "</table></td></tr></table>";
                echo "<div style=\"page-break-after:always;\"></div>";
            }

        }
        
	}elseif($_POST['task'] == 'printChequeSlip'){
		$month = $_POST['month'];
        //for cash and draft type

        foreach($employeeTypes as $employeeType){
        	$salaryProcessIds = $payment->getSalaryProcessId4PaymentTypeEmployeeType('LRESER19', $employeeType, $month);
            if(sizeof($salaryProcessIds, 0)){
                echo "
			<table width=\"100%\" align=\"center\" border=\".2\">
				<tr>
					<td colspan=\"3\" width=\"100%\">
						<table border=\"0\" align=\"center\" width=\"100%\">
							<tr>
								<td align=\"center\" width=\"160px\" height=\"111px\"><img src=\"".$options->getBaseServer()."/images/global/school_logo.png\" alt=\"mnnit logo\" width=\"126px\" height=\"111px\" align=\"left\" /></td>
								<td align=\"center\" width=\"*\"><font class=\"bigheader\">DELHI PUBLIC SCHOOL, KASHI</font><br /><font class=\"smallheader\">
									VARANASI - 221001<br /><br />
									CHEQUE / DRAFT PAYMENT DETAILS </font><br />";
                echo "<font class=\"bigheader\">".$options->getOptionIdValue($employeeType)." </font> <font class=\"month\">".$salary->getMonthNameOfMonth($month)."</font>";
                echo						"</td>

							</tr>
						</table>
					</td>
				</tr>
			</table>";
                echo "
				<table border=\"1px\" align=\"center\" cellpadding=\"0px\" cellspacing = \"0px\" width=\"100%\">
					<tr>
						<th>SN</th>
						<th>Employee Name</th>
						<th>Bank Name</th>
						<th>Cheque No</th>
						<th>Salary</th>
					</tr>";
                $i = 1;
                foreach($salaryProcessIds as $salaryProcessId){
                    $details = $salary->getTableIdDetails($salaryProcessId);
                    $chequeId = $payment->getPaymentChequeDetails($salaryProcessId, 1);
                    $chequeDetails = $payment->getTableIdDetails($chequeId);
                    echo "
					<tr>
						<th>".$i."</th>
						<th><font class=\"salaryPrint\">".$salary->getCandidateName($details['employee_id'])."</font></th>
						<th><font class=\"salaryPrint\">".$bank->getBankName($chequeDetails['bank_id'])."</font></th>
						<th><font class=\"salaryPrint\">".$chequeDetails['cheque_number']."</font></th>
						<th align=\"right\"><font class=\"salaryPrint\">".number_format($salary->getEmployeeSalaryAmount($details['employee_id'], $month), 2, '.', ',')."</font></th>
					</tr>";
                    ++$i;
                }
                echo "</table></td></tr></table>";
                echo "<div style=\"page-break-after:always;\"></div>";
            }
        }
	}else {
		$outputArray = 0;
		echo $outputArray ;
	}
}
?>