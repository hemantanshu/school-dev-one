<?php
require_once 'config.php';

require_once BASE_PATH . 'include/accounts/class.loan.php';
require_once BASE_PATH . 'include/accounts/class.allowance.php';
require_once BASE_PATH . 'include/accounts/class.accountHead.php';
require_once BASE_PATH . 'include/accounts/class.accounts.php';
require_once BASE_PATH . 'include/accounts/class.directInsertion.php';

$allowance = new Allowance ();
$loan = new Loan ();
$accounts = new Accounts();
$directInsertion = new DirectInsertion();
$accountHead = new AccountHead();


$loan->isRequestAuthorised4Form ( 'LMENUL128' );

if (isset ( $_POST ['task'] )) {
	if ($_POST ['task'] == 'getSalaryDetails') {
		$employeeId = $_POST['employeeId'];		
		$month = date('Ym');
		$i = 1;
		$totalCredit = $totalDebit = 0;
		$masterSalaryIds = $accounts->getEmployeeMasterSalaryIds($employeeId, 1);
		echo "
			<tr class=\"even\">
				<th>SN</th>
				<th>Allowance Name</th>
				<th>AccountHead Name</th>
				<th>Amount</th>
				<th>Type</th>					
			</tr>
			<tr class=\"even\">
				<th colspan=\"5\"><hr />Master Salary Record</th>
			</tr>";
		foreach($masterSalaryIds as $masterSalaryId){
			$details = $accounts->getTableIdDetails($masterSalaryId);
			$allowanceDetails = $accounts->getTableIdDetails($details['allowance_id']);
			echo "
				<tr class=\"odd\">
					<th>".$i."</th>
					<th>".$allowance->getAllowanceName($details['allowance_id'])."</th>
					<th>".$accountHead->getAccountHeadName($allowanceDetails['accounthead_id'])."</th>";
			
			if($details['amount'] > 0){
				$type = '<font class="green">Credit</font>';
				$totalCredit += $details['amount'];
			}
			else{
				$type = '<font class="red">Debit</font>';
				$totalDebit += abs($details['amount']);
			}
			
			echo "					
				<th align=\"right\" style=\"padding-right: 10px\">".number_format(abs($details['amount']), 2, '.', ',')."</th>
					<th>".$type."</th>					
				</tr>
				<tr>
					<td height=\"5px\"></td>
				</tr>";
			++$i;
		}
				
		//checking for the additional salary inserts
		echo "
			<tr class=\"even\">
				<th colspan=\"5\"><hr />Direct Salary Addition</th>
			</tr>";
		$directInsertionIds = $directInsertion->getDirectSalaryIds4Employee($employeeId, $month, 1);
		foreach($directInsertionIds as $directInsertionId){
			$details = $accounts->getTableIdDetails($directInsertionId);
			$allowanceDetails = $accounts->getTableIdDetails($details['allowance_id']);
			echo "
				<tr class=\"odd\">
					<th>".$i."</th>
					<th>".$allowance->getAllowanceName($details['allowance_id'])."</th>
					<th>".$accountHead->getAccountHeadName($allowanceDetails['accounthead_id'])."</th>";
				
			if($details['amount'] > 0){
				$type = '<font class="green">Credit</font>';
				$totalCredit += $details['amount'];
			}
			else{
				$type = '<font class="red">Debit</font>';
				$totalDebit += abs($details['amount']);
			}
				
			echo "					
				<th align=\"right\" style=\"padding-right: 10px\">".number_format(abs($details['amount']), 2, '.', ',')."</th>
					<th>".$type."</th>					
				</tr>
				<tr>
					<td height=\"5px\"></td>
				</tr>";
			++$i;
		}
		echo "
			<tr class=\"even\">
				<th colspan=\"5\"><hr />Loan Installment Details</th>
			</tr>";
		//checking for the loan installments to be paid
		$loanAccountIds = $loan->getEmployeeLoanIds($employeeId, 1);
		foreach ($loanAccountIds as $loanAccountId){			
			$stopInstallmentId = $loan->getStopLoanInstallmentId4Month($loanAccountId, $month, 1);
			if(!$stopInstallmentId){
				$details = $loan->getTableIdDetails($loanAccountId);
				$loanTypeDetails = $loan->getTableIdDetails($details['loan_type_id']);
				$allowanceDetails = $loan->getTableIdDetails($loanTypeDetails['allowance_id']);
				
				$type = '<font class="red">Debit</font>';
				$totalDebit += abs($details['installment_amount']);
				echo "
					<tr class=\"odd\">
						<th>".$i."</th>
						<th>".$allowance->getAllowanceName($loanTypeDetails['allowance_id'])."</th>
						<th>".$accountHead->getAccountHeadName($allowanceDetails['accounthead_id'])."</th>
						<th align=\"right\" style=\"padding-right: 10px\">".number_format(abs($details['installment_amount']), 2, '.', ',')."</th>
						<th>".$type."</th>
					</tr>
					<tr>
						<td height=\"5px\"></td>
					</tr>";
				++$i;				
			}
		}
		
		echo "
		<tr class=\"even\">
			<th colspan=\"5\"><hr />Summary</th>
		</tr>";
		
		echo "
			<tr class=\"odd\">
				<th colspan=\"2\">Gross Salary : ".number_format($totalCredit, 2, '.', ',')."</th>
				<th colspan=\"2\">Total Deduction : ".number_format($totalDebit, 2, '.', ',')."</th>
				<th colspan=\"2\">Net Salary : ".number_format(($totalCredit - $totalDebit), 2, '.', ',')."</th>				
			</tr>
			<tr>
				<td height=\"5px\"></td>
			</tr>";
		
		
	}  else {
		$outputArray = 0;
		echo $outputArray ;
	}
}
?>