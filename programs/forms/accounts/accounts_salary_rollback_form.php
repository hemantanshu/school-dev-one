<?php
require_once 'config.php';

require_once BASE_PATH . 'include/accounts/class.loan.php';
require_once BASE_PATH . 'include/accounts/class.salarySlip.php';
require_once BASE_PATH . 'include/accounts/class.payment.php';
require_once BASE_PATH . 'include/accounts/class.fund.php';
require_once BASE_PATH . 'include/global/class.menuTask.php';
require_once BASE_PATH . 'include/global/class.notification.php';
require_once BASE_PATH . 'include/global/class.options.php';


$loan = new Loan ();
$salary = new SalarySlip();
$payment = new Payment();
$notification = new Notification();
$menuTask = new MenuTask();
$fund = new Fund();
$options = new options();

$loan->isRequestAuthorised4Form ( 'LMENUL157' );

if (isset ( $_POST ['task'] )) {
	if ($_POST ['task'] == 'unProcessEmployeeSalary') {
			$month = date('Ym');
			$employeeId = $_POST['employeeId'];
			
			//dropping all entries of the salary record
			$salaryRecordIds = $salary->getSalaryIds($employeeId, $month, 'all');
			foreach($salaryRecordIds as $salaryRecordId)
				$salary->dropSalaryRecord($salaryRecordId);
			
			//dropping the salary process record
			$salaryProcessId = $salary->checkSalaryProcessRecord($employeeId, $month, 1);
			$salary->dropSalaryProcessRecord($salaryProcessId[0]);
						
			$salaryEmployeeRecordId = $salary->getSalaryProcessId4EmployeeId($employeeId, $month, 1);			
			$salary->dropSalaryProcessRecord($salaryEmployeeRecordId[0]);			
			
			$salaryProcessDetails = $salary->getTableIdDetails($salaryProcessId[0]);			
			//dropping the menutask and notification 
			if($salaryProcessDetails['payment_mode'] == 'LRESER19'){
				//setup the job to finish off the cheque/draft details entry
				$menuTaskId = $menuTask->getMenuTaskId4SourceId($salaryProcessId[0]);
				$menuTask->dropMenuTaskAssignment($menuTaskId);
			
				$notificationId = $notification->getNotificationId4Source($salaryProcessId[0]);
				$notification->dropNotification($notificationId);
			}
			//dropping the fund record
			$allowanceIds = $fund->getFundAllowanceIds();
			foreach ($allowanceIds as $allowanceId){
				$fundIds = $fund->getEmployeeFundInstallmentIds($employeeId, $allowanceId, 'LRESER20', $month, 1);
				foreach ($fundIds as $fundId)
					$fund->dropFundInstallmentAmount($fundId);
				
				$fundIds = $fund->getEmployeeFundInstallmentIds($employeeId, $allowanceId, 'LRESER21', $month, 1);
				foreach ($fundIds as $fundId)
					$fund->dropFundInstallmentAmount($fundId);
			}
			//dropping the loan record
			$loanRecordIds = $loan->getEmployeeLoanRecordDetailsIds($employeeId, $month);
			foreach ($loanRecordIds as $loanRecordId){
				$details = $loan->getTableIdDetails($loanRecordId);
				$loan->dropLoanInstallmentDetails($details['loan_id'], $month, 'LRESER9');
				$loan->dropEmployeeLoanRecordDetails($loanRecordId);
			}			
			
			$salary->setEmployeeSalaryRollBack($employeeId, $month);
			$outputArray[0] = 1;
			echo json_encode($outputArray);
			
	}  else {
		$outputArray = 0;
		echo $outputArray ;
	}
}
?>