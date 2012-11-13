<?php
require_once 'config.php';

require_once BASE_PATH . 'include/global/class.options.php';
require_once BASE_PATH . 'include/global/class.session.php';

require_once BASE_PATH . 'include/utility/class.employeeRegistration.php';
require_once BASE_PATH . 'include/hrms/class.designation.php';

require_once BASE_PATH . 'include/accounts/class.salarySlip.php';
require_once BASE_PATH . 'include/accounts/class.payment.php';

require_once BASE_PATH . 'include/accounts/class.accountHead.php';
require_once BASE_PATH . 'include/accounts/class.fund.php';
require_once BASE_PATH . 'include/accounts/class.loan.php';


$options = new options ();
$registration = new employeeRegistration();
$salary = new SalarySlip();
$ranks = new Designation();
$payment = new Payment();
$accountHead = new AccountHead();
$fund = new Fund();
$session = new Session();
$loan = new Loan();

$options->isRequestAuthorised4Form ( 'LMENUL132' );

if (isset ( $_POST ['task'] )) {
	if ($_POST ['task'] == 'fetchDepartmentEmployees') {
		$month = $_POST['month'];
		$departmentId = $_POST['departmentId'];
		
		$employeeIds = $salary->getEmployeeIds4Department($departmentId, $month);
		if(sizeof($employeeIds, 0))
			$outputArray = $employeeIds;
		else
			$outputArray[0] = 1;
		
		echo json_encode($outputArray);
	}elseif ($_POST ['task'] == 'fetchEmployeeTypeEmployeeIds') {
		$month = $_POST['month'];
		$employeeType = $_POST['employeeType'];
		
		$employeeIds = $salary->getEmployeeIds4EmployeeType($employeeType, $month);
		if(sizeof($employeeIds, 0))
			$outputArray = $employeeIds;
		else
			$outputArray[0] = 1;
		
		echo json_encode($outputArray);
	} elseif ($_POST ['task'] == 'fetchAllEmployeeIds') {
		$month = $_POST['month'];
		
		$employeeIds = $salary->getEmployeeIds($month);
		if(sizeof($employeeIds, 0))
			$outputArray = $employeeIds;
		else
			$outputArray[0] = 1;
		
		echo json_encode($outputArray);
	} elseif($_POST['task'] == 'printSlip'){
		$month = $_POST['month'];
		$employeeId = $_POST['employeeId'];		
		$monthDetails = $session->getSessionDetails4Month($month, 'class'); 
		
		$startMonthName = $options->getMonthNameOfDate($monthDetails[0]);
		$endMonthName = $options->getMonthNameOfDate($monthDetails[1]);
		$currentMonthName = $options->getMonthNameOfMonth($month);

		$startMonth = $options->getMonthOfDate($monthDetails[0]);
		$endMonth = $options->getMonthOfDate($monthDetails[1]);
		
		echo "
			<table width=\"100%\" align=\"center\" border=\"0\">
				<tr>
					<td colspan=\"3\" width=\"100%\">
					<table border=\"0\" align=\"center\" width=\"100%\">
						<tr>
							<td align=\"center\" width=\"160\" height=\"111px\">
								<img src=\"".$options->getBaseServer()."/images/global/school_logo.png\" alt=\"mnnit logo\" width=\"126px\" height=\"111px\" align=\"left\" /></td>
							<td align=\"center\" width=\"*\">
								<font class=\"bigheader\">DELHI PUBLIC SCHOOL, KASHI</font><br />
								<font class=\"smallheader\">VARANASI - 221001<br /><br />ACCOUNTS DEPARTMENT -- SALARY SLIP FOR </font>";
			echo "<font class=\"month\">".$currentMonthName."</font>";
			echo "</td>
			
						</tr>
					</table>
				</td>
				</tr>			
				<tr>
					<td height=\"10px\" colspan=\"3\"></td>
				</tr>";
			$details = $registration->getTableIdDetails($registration->getEmployeeRegistrationId($employeeId));
			echo "
				<tr>
					<td colspan=\"3\" align=\"center\" width=\"100%\">
					<table align=\"center\" border=\"0\" width=\"100%\">
						<tr>
							<td height=\"10px\" colspan=\"5\"></td>
						</tr>
						<tr>
							<td width=\"13%\" align=\"right\"><font class=\"salarySlip\">Name :</font></td>
							<td width=\"32%\" align=\"left\"><font class=\"salaryPrint\">".$options->getOfficerName($employeeId)."</font></td>
							<td width=\"5%\" align=\"center\">||</td>
							<td align=\"right\" width=\"13%\"><font class=\"salarySlip\">Employee Code :</font></td>
							<td align=\"left\" width=\"*\"><font class=\"salaryPrint\">".$details['employee_code']."</font></td>
						</tr>
						<tr>
							<td height=\"10px\" colspan=\"5\"></td>
						</tr>
						<tr>
							<td align=\"right\"><font class=\"salarySlip\">Department :</font></td>
							<td align=\"left\"><font class=\"salaryPrint\">".$options->getOptionIdValue($details['department_id'])."</font></td>
							<td align=\"center\">||</td>
							<td align=\"right\"><font class=\"salarySlip\">Designation :</font></td>
							<td align=\"left\"><font class=\"salaryPrint\">";
			$designationIds = $ranks->getUserRanks($employeeId, 1);
			foreach ($designationIds as $designationId){
				$details = $options->getTableIdDetails($designationId);
				echo $options->getOptionIdValue($details['rank_id'])."<br />";
				break;
			}
						
			echo "</font></td>
						</tr>
						<tr>
							<td height=\"10px\" colspan=\"5\"></td>
						</tr>";
			$salaryProcessId = $salary->checkSalaryProcessRecord($employeeId, $month, 1);
			$details = $salary->getTableIdDetails($salaryProcessId[0]);
			
			if($details['payment_mode'] == 'LRESER17'){
				$bankTransferId = $payment->getBankTransferId4Source($salaryProcessId[0], 1);
				$details = $salary->getTableIdDetails($bankTransferId[0]);
				$bankDetails = $salary->getTableIdDetails($details['bank_id']);
			
				echo "
				<tr>
					<td align=\"right\"><font class=\"salarySlip\">Bank Name :</font></td>
					<td align=\"left\"><font class=\"salaryPrint\">".$bankDetails['bank_name'].", ". $bankDetails['branch_name']."<br />IFSC Code : ".$bankDetails['ifsc_code']."</font></td>
					<td align=\"center\">||</td>
					<td align=\"right\"><font class=\"salarySlip\">Account No :</font></td>
					<td align=\"left\"><font class=\"salaryPrint\">".$details['account_number']."</font></td>
				</tr>";				
			}elseif($details['payment_mode'] == 'LRESER19'){
				$chequeDetails = $payment->getPaymentChequeDetails($salaryProcessId[0], 1);
				$details = $salary->getTableIdDetails($chequeDetails[0]);
				$bankDetails = $salary->getTableIdDetails($details['bank_id']);
				echo "
				<tr>
					<td align=\"right\"><font class=\"salarySlip\">Bank Name :</font></td>
					<td align=\"left\"><font class=\"salaryPrint\">".$bankDetails['bank_name'].", ". $bankDetails['branch_name']."<br />IFSC Code : ".$bankDetails['ifsc_code']."</font></td>
					<td align=\"center\">||</td>
					<td align=\"right\"><font class=\"salarySlip\">Cheque/Draft No :</font></td>
					<td align=\"left\"><font class=\"salaryPrint\">".$details['cheque_number']."</font></td>
				</tr>";
				
			}else{
				echo "
				<tr>
					<td align=\"right\"><font class=\"salarySlip\">Payment Mode :</font></td>
					<td align=\"left\"><font class=\"salaryPrint\">Cash Payment</font></td>
					<td align=\"center\">||</td>
				</tr>";
			}			
			echo "
						
						<tr>
							<td height=\"10px\" colspan=\"5\"></td>
						</tr>
					</table>
					</td>
				</tr>
				<tr>
					<td colspan=\"3\" height=\"10px\"></td>
				</tr>";			
			echo "
				<tr>
                    <td align=\"center\" colspan=\"3\">
                         <table border=\"1px\" cellpadding=\"0\" cellspacing=\"0\" width=\"90%\" align=\"center\">
                            <tr>
                                <th width=\"33%\">EARNINGS</th>
                                <th width=\"33%\">DEDUCTIONS</th>
                                <th width=\"*\">SUMMARY</th>
                            </tr>
                            <tr>
                                <td>
                                    <table align=\"center\" width=\"100%\" border=\"0\">";
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
                            echo "
                            		</table>
                            	</td>
                            	<td>
                            		<table align=\"center\" width=\"100%\" border=\"0\">";
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
                            
                            echo "
                            		</table>
                            	</td>
                            	<td>
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
                            
                            
                            echo "                  
                            			<tr>
                            				<td colspan=\"3\" height=\"5px\"></td>
                            			</tr>
                            		</table>
                            	</td>
                            </tr>
                         </table>
                         </td>
                      </tr>
                      <tr>
                      		<td colspan=\"3\" height=\"10px\"></td>
                      </tr>
                        <tr>
                            <td colspan=\"3\" align=\"left\" style=\"padding-left:20px\"><font class=\"salaryPrint\"><br />NET PAYABLE AMOUNT : ".number_format(($earnings - $deductions), 2, '.', '')."/-  (".strtoupper($salary->nameAmount($earnings - $deductions))." ONLY )</font></td>
                        </tr>
                        <tr>
                            <td colspan=\"3\" height=\"10px\"><hr /></td>
                        </tr>
                        <tr>
                            <td align=\"center\" colspan=\"3\" width=\"100%\">
                                <table border=\"0\" align=\"center\" width=\"100%\">
                                    <tr>
                                        <td colspan=\"7\" align=\"center\" width=\"100%\"><font class=\"salaryPrint\">SUMMARY OF EARNINGS FROM ".$startMonthName." TO ".$currentMonthName."</font><td>
                        			</tr>";
	        $i = 0;
	        $earnings = 0;
	        $deductions = 0;

        $salaryCreditIds = $salary->getEmployeeSalaryDetails($employeeId, $startMonth, $month, true);
        foreach ($salaryCreditIds as $salaryDetails) {
            if($i % 2 == 0)
                				echo "<tr>
                                        <td align=\"right\" width=\"20%\">".$accountHead->getReservedAccountHeadName($salaryDetails[0], $month)." :</td>
                                        <td width=\"2%\"></td>
                                        <td align=\"left\"  width=\"26%\"><font class=\"salaryPrint\">Rs. ".number_format($salaryDetails[1], 2, '.', '')."</font></td>
                                        <td width=\"2%\"></td>";
            else
                echo "
		                                    <td align=\"right\" width=\"20%\">".$accountHead->getReservedAccountHeadName($salaryDetails[0], $month)." :</td>
		                                    <td width=\"2%\"></td>
		                                    <td align=\"left\" width=\"*\"><font class=\"salaryPrint\">Rs. ".number_format($salaryDetails[1], 2, '.', '')."</font></td>
		                                </tr>
		                                <tr>
		                                    <td height=\"5px\"></td>
		                                </tr>
		                                ";
            $earnings += $salaryDetails[1];
            ++$i;
        }
        if($i % 2 == 0 || $i == 1)
            echo "
		                                </tr>";
        echo "
                                        </table>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan=\"3\" height=\"10px\"></td>
                                </tr>
                                <tr>
                                    <td align=\"center\" colspan=\"3\" width=\"100%\">
                                        <table border=\"0\" align=\"center\" width=\"100%\">
                                            <tr>
                                                <td colspan=\"7\" align=\"center\" width=\"100%\"><font class=\"salaryPrint\">SUMMARY OF DEDUCTIONS FROM ".$startMonthName." TO ".$currentMonthName."</font><td>
                                            </tr>";
        $i = 0;
        $salaryCreditIds = $salary->getEmployeeSalaryDetails($employeeId, $startMonth, $month, false);
        foreach ($salaryCreditIds as $salaryDetails) {
            if($i % 2 == 0)
                echo "<tr>
                                        <td align=\"right\" width=\"20%\">".$accountHead->getReservedAccountHeadName($salaryDetails[0], $month)." :</td>
                                        <td width=\"2%\"></td>
                                        <td align=\"left\"  width=\"26%\"><font class=\"salaryPrint\">Rs. ".number_format(abs($salaryDetails[1]), 2, '.', '')."</font></td>
                                        <td width=\"2%\"></td>";
            else
                echo "
                                    <td align=\"right\" width=\"20%\">".$accountHead->getReservedAccountHeadName($salaryDetails[0], $month)." :</td>
                                    <td width=\"2%\"></td>
                                    <td align=\"left\" width=\"*\"><font class=\"salaryPrint\">Rs. ".number_format(abs($salaryDetails[1]), 2, '.', '')."</font></td>
                                </tr>
                                <tr>
                                    <td height=\"5px\"></td>
                                </tr>
                                ";
            $deductions += abs($salaryDetails[1]);
            ++$i;
        }
        if($i % 2 == 0)
            echo "
                                </tr>";
        echo "

                                        </table>
                                    </td>
                                </tr>
                                <tr>
		                            <td colspan=\"3\" height=\"10px\"></td>
		                        </tr>
                                <tr>
                                    <td align=\"center\" colspan=\"3\">
										<table align=\"center\" width=\"100%\" border=\"0\">
											<tr>
												<td colspan=\"9\" align=\"center\"><font class=\"salaryPrint\">TOTAL SUMMARY FROM ".$startMonthName." TO ".$currentMonthName."</font></td>
											</tr>
											<tr>
												<td align=\"right\" width=\"15%\">Total Earnings</td>
												<td align=\"center\" width=\"5%\">:</td>
												<td align=\"left\" width=\"13%\"><font class=\"salaryPrint\">Rs. ".number_format($earnings, 2, '.', '')."</font></td>
												<td align=\"right\" width=\"15%\">Total Deductions</td>
												<td width=\"5%\" align=\"center\">:</td>
												<td align=\"left\" width=\"13%\"><font class=\"salaryPrint\">Rs. ".number_format($deductions, 2, '.', '')."</font></td>
												<td align=\"right\" width=\"15%\">Total Net Pay</td>
												<td width=\"5%\" align=\"center\">:</td>
												<td align=\"left\" width=\"*\"><font class=\"salaryPrint\">Rs. ".number_format(($earnings - $deductions), 2, '.', '')."</font></td>
											</tr>
										</table>
                                	</td>
                                </tr>";
        echo "
								<tr>
		                            <td colspan=\"3\" height=\"10px\"></td>
		                        </tr>
                                <tr>
                                    <td align=\"center\" colspan=\"3\">
										<table align=\"center\" width=\"100%\" border=\"0\" style=\"padding-top:10px;\">
											<tr>
												<td colspan=\"9\" align=\"center\"><font class=\"salaryPrint\">FUND SUMMARY FROM ".$startMonthName." TO ".$currentMonthName."</font></td>
											</tr>";
        // checking for annual gpf summary is present
        $allowanceFundIds = $fund->getFundAllowanceIds();
        foreach($allowanceFundIds as $allowanceFundId){
        	$amount = $fund->getFundAmount4EmployeeAllowanceMonthType($employeeId, $startMonth, $month, $allowanceFundId[0], "LRESER20");
        	if($amount > 0){
        		$details = $salary->getTableIdDetails($allowanceFundId[0]);
        		$contribution = $fund->getFundAmount4EmployeeAllowanceMonthType($employeeId, $startMonth, $month, $allowanceFundId[0], "LRESER21");
        		echo "
        		<tr>
	        		<td align=\"right\" width=\"15%\"><font class=\"salaryPrint\">".$accountHead->getAccountHeadName($details['accounthead_id'])."</font></td>
	        		<td align=\"center\" width=\"5%\">:</td>
	        		<td align=\"left\" width=\"13%\"><font class=\"salaryPrint\">Rs. ".number_format($amount, 2, '.', '')."</font></td>
	        		<td align=\"right\" width=\"15%\"><font class=\"salaryPrint\">Inst. Contribution</font></td>
	        		<td width=\"5%\" align=\"center\">:</td>
	        		<td align=\"left\" width=\"13%\"><font class=\"salaryPrint\">Rs. ".number_format($contribution, 2, '.', '')."</font></td>
	        		<td align=\"right\" width=\"15%\"><font class=\"salaryPrint\">Total</font></td>
	        		<td width=\"5%\" align=\"center\">:</td>
	        		<td align=\"left\" width=\"*\"><font class=\"salaryPrint\">Rs. ".number_format(($amount + $contribution), 2, '.', '')."</font></td>
        		</tr>";
        	}
        }
        echo "
        								</table>
        							</td>
        						</tr>";
        echo "
        <tr>
        <td colspan=\"3\" height=\"15px\"><hr size=\"2\" /></td>
        </tr>";
        $loanIds = $loan->getEmployeeLoanRecordDetailsIds($employeeId, $month);
        if(sizeof($loanIds, 0)){
        	echo "
        						<tr>
        							<td align=\"center\" colspan=\"3\">
        								<table align=\"center\" width=\"100%\" border=\"0\"  style=\"padding-top:10px;\">";        	
        	foreach ($loanIds as $loanId){       		
        		$details = $loan->getTableIdDetails($loanId);
        		$loanDetails = $loan->getTableIdDetails($details['loan_id']);
        		
        		echo "
        		<tr>
	        		<td align=\"center\" width=\"33%\"><font class=\"salaryPrint\">".$loan->getLoanName($loanDetails['loan_type_id'])."</font></td>
	        		<td align=\"right\" width=\"15%\"><font class=\"salaryPrint\">Total Loan</font></td>
	        		<td width=\"5%\" align=\"center\">:</td>
	        		<td align=\"left\" width=\"13%\"><font class=\"salaryPrint\">Rs. ".number_format($details['amount_loan'], 2, '.', '')."</font></td>
	        		<td align=\"right\" width=\"15%\"><font class=\"salaryPrint\">Amount Left</font></td>
	        		<td width=\"5%\" align=\"center\">:</td>
	        		<td align=\"left\" width=\"*\"><font class=\"salaryPrint\">".$details['amount_left']."</font></td>
        		</tr>";
        	}
        	echo "
        								</table>
        							</td>
        						</tr>";
        }
        
        foreach($allowanceFundIds as $allowanceFundId){
        	$amount = $fund->getFundAmount4EmployeeAllowanceMonth($employeeId, '000000', $month, $allowanceFundId[0]);
        	if($amount > 0){
        		$details = $fund->getTableIdDetails($allowanceFundId[0]);
        		echo "
        		<tr>
	        		<td colspan=\"3\" align=\"left\">
		        		<table align=\"left\" width=\"100%\" border=\"0\">
			        		<tr>
				        		<td align=\"right\" width=\"40%\"><font class=\"salaryPrint\">Total ".$accountHead->getAccountHeadName($details['accounthead_id'])." Balance</font></td>
				        		<td align=\"center\" width=\"5%\">:</td>
				        		<td align=\"left\" width=\"13%\"><font class=\"salaryPrint\">Rs. ".number_format($amount, 2, '.', '')."</font></td>
				        		<td width=\"*\"></td>
			        		</tr>
		        		</table>
	        		</td>
        		</tr>";
        	}
        }
        echo "
        	
        		<tr>
                            <td colspan=\"3\" height=\"20px\"></td>
                        </tr>
        	<tr>
                                    <td colspan=\"3\" align=\"center\" style8=\"padding-top:10px; padding-bottom:10px;\"><font class=\"salaryPrint\">This is a computer generated statement and does not need any signature.</font><font size=\"1.2px\"> Designed and Developed By Support Gurukul India</font></td>
                                </tr>
                            </table>";
        echo "<br /><br />";
        echo "<div style=\"page-break-after:always;\"></div>";
        
			
	}else {
		$outputArray [0] = 0;
		echo json_encode ( $outputArray );
	}
}
?>