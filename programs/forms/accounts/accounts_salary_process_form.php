<?php
require_once 'config.php';

require_once BASE_PATH . 'include/accounts/class.loan.php';
require_once BASE_PATH . 'include/accounts/class.allowance.php';
require_once BASE_PATH . 'include/accounts/class.salary.php';
require_once BASE_PATH . 'include/accounts/class.directInsertion.php';
require_once BASE_PATH . 'include/accounts/class.payment.php';
require_once BASE_PATH . 'include/accounts/class.bank.php';
require_once BASE_PATH . 'include/accounts/class.fund.php';
require_once BASE_PATH . 'include/global/class.menuTask.php';
require_once BASE_PATH . 'include/global/class.notification.php';
require_once BASE_PATH . 'include/global/class.options.php';


$allowance = new Allowance ();
$loan = new Loan ();
$salary = new Salary();
$directInsertion = new DirectInsertion();
$payment = new Payment();
$bank = new Bank();
$notification = new Notification();
$menuTask = new MenuTask();
$fund = new Fund();
$options = new options();

$loan->isRequestAuthorised4Form ( 'LMENUL129' );

if (isset ( $_POST ['task'] )) {
	if ($_POST ['task'] == 'getEmployeeIds') {
		$month = date('Ym');
		$outputArray = $salary->getEmployeeIds4SalaryProcessing($month);
		
		
		echo json_encode(array_reverse($outputArray));		
	}elseif ($_POST ['task'] == 'getEmployeeSalaryAmount') {		
		$employeeId = $_POST['employeeId'];
		$month = date('Ym');
		
		$amount = $salary->getEmployeeMasterSalaryTotalAmount($employeeId);
		
		$loanAccountIds = $loan->getEmployeeLoanIds($employeeId, 1);
		foreach($loanAccountIds as $loanAccountId){
			if($loan->getStopLoanInstallmentId4Month($loanAccountId, $month, 1))
				continue;
			$amount -= $loan->getLoanAccountInstallmentAmount($loanAccountId, false);
		}		
		
		$amount += $directInsertion->getDirectSalaryAmount4Employee($employeeId, $month);
		$outputArray[0] = number_format($amount, 2, '.', ',');
		
		echo json_encode($outputArray);		
	}elseif ($_POST ['task'] == 'processEmployeeSalary') {	
		$employeeId = $_POST['employeeId'];		
		$workingDays = $_POST['workingDays'];
		$paymentMode = $_POST['paymentMode'];
		
		$totalDays = cal_days_in_month ( CAL_GREGORIAN, date ( 'm' ), date ( 'Y' ) );
		$factor = $workingDays / $totalDays;
		
		$month = date('Ym');
		
		//processing master salary items
		$totalAmount = 0;
		$masterSalaryIds = $salary->getEmployeeMasterSalaryIds($employeeId, 1);
		foreach($masterSalaryIds as $masterSalaryId){
			$details = $salary->getTableIdDetails($masterSalaryId);
			$allowanceDetails = $salary->getTableIdDetails($details['allowance_id']);
			
			$amount = $details['amount'];
			if($allowanceDetails['fraction'] == 'y')
				$amount *= $factor;
			
			if($allowanceDetails['round_off'] == 'y')
				$amount = round($amount);
						
			$totalAmount += $amount;			
			$salary->setSalaryRecord($employeeId, $allowance->getAllowanceAccountHeadId($details['allowance_id']), $details['allowance_id'], $amount, $month);
		}
		
		//processing direct salary addition items
		$directSalaryAdditionIds = $directInsertion->getDirectSalaryIds4Employee($employeeId, $month, 1);
		foreach($directSalaryAdditionIds as $directSalaryAdditionId){
			$details = $salary->getTableIdDetails($directSalaryAdditionId);
			$totalAmount += $details['amount'];
			
			$salary->setSalaryRecord($employeeId, $allowance->getAllowanceAccountHeadId($details['allowance_id']), $details['allowance_id'], $details['amount'], $month);			
		}
		
		//setting the fund collection
		$fund->processEmployeeFund($employeeId, $month);
		
		//processing loan installment details
		$loanAccountIds = $loan->getEmployeeLoanIds($employeeId, 1);
		foreach($loanAccountIds as $loanAccountId){
			$details = $loan->getTableIdDetails($loanAccountId);
			if(!$loan->getStopLoanInstallmentId4Month($loanAccountId, $month, 1)){				
				$loanDetails = $loan->getTableIdDetails($details['loan_type_id']);
				
				$loanAmount = 	0 - $loan->getLoanAccountInstallmentAmount($loanAccountId, true);
				$totalAmount += $loanAmount;
				
				$salary->setSalaryRecord($employeeId, $allowance->getAllowanceAccountHeadId($loanDetails['allowance_id']), $loanDetails['allowance_id'], $loanAmount, $month);
				
				//inserting loan installment amount
				$loan->setLoanInstallmentAmount($loanAccountId, $loanAmount, 'LRESER9');				
			}
			//inserting the loan record details
			$amountLoan = $loan->getLoanAmount4Type($loanAccountId, $month, 'LRESER8');
			$amountLeft = $loan->getLoanAmountLeft($loanAccountId, $month);
			$loanAmount = abs($loanAmount);
			$amountInstallment = $loanAmount ? $loanAmount : $loan->getLoanAccountInstallmentAmount($loanAccountId, false);			
			$loan->setEmployeeLoanRecordDetails($employeeId, $loanAccountId, $amountLoan, $amountLeft, $amountInstallment, $month);
		}		
		
		//inserting the salary processing result
		$paymentId = $salary->setSalaryProcessRecord($employeeId, $workingDays, $paymentMode, $month);		
		
		//setting the payment option type
		if($paymentMode == 'LRESER17'){
			//the payment is made by bank transfer
			$employeeBankId = $bank->getEmployeeBankAccountId($employeeId, 'LRESER7');
			$details = $bank->getTableIdDetails($employeeBankId);
			$payment->setBankTransferDetails($paymentId, $details['bank_id'], $details['account_number']);			
		}elseif($paymentMode == 'LRESER19'){
			//setup the job to finish off the cheque/draft details entry
			$url = "pages/accounts/accounts_cheque_entry.php";
			$endDate = date("Y-m-d", mktime(0, 0, 0, date('m')+3, date('d'), date('Y')));
			
			$displayName = "Salary Cheque Entry ".$loan->getOfficerName($employeeId);
			$comments = "Draft / Cheque Details For Salary Payment";
			
			$menuTaskId = $menuTask->setMenuTaskAssignment($loan->getLoggedUserId(), $displayName, $url, $paymentId, $comments, $loan->getCurrentDate(), $endDate);
			
			$attributeArray[0] = $paymentId;			
			$menuTask->setMenuTaskAttributes($menuTaskId, 1, $attributeArray);
			
			$notification->setNewNotification($loan->getLoggedUserId(), $displayName, $comments, $loan->getCurrentDate(), $endDate, 10, $paymentId);
		}	
		
		//inserting the employee personal record details
		$salary->setEmployeeDetailsRecord($employeeId, $month);
		
		$salary->preserveAccountHead($month);

		$outputArray[0] = number_format($totalAmount, 2, '.', ',');
		$outputArray[] = $options->getOptionIdValue($paymentMode);
		
		echo json_encode($outputArray);		
	}  else {
		$outputArray = 0;
		echo $outputArray ;
	}
}
?>