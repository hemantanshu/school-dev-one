<?php
require_once 'config.php';

require_once BASE_PATH . 'include/accounts/class.loan.php';
require_once BASE_PATH . 'include/accounts/class.bank.php';
require_once BASE_PATH . 'include/accounts/class.payment.php';
require_once BASE_PATH . 'include/global/class.options.php';
require_once BASE_PATH . 'include/utility/class.employeeRegistration.php';
require_once BASE_PATH . 'include/accounts/class.directInsertion.php';


$bank = new Bank();
$options = new options();
$loan = new Loan ();
$registration = new employeeRegistration();
$payment = new Payment();
$directInsertion = new DirectInsertion();


$loan->isRequestAuthorised4Form ( 'LMENUL127' );

if (isset ( $_POST ['task'] )) {
	if ($_POST ['task'] == 'fetchLoanTypes') {
		$loanTypeIds = $loan->getLoanIds(1);
		$i = 0;
		foreach($loanTypeIds as $loanId){
			$outputArray[$i][] = $loanId;
			$outputArray[$i][] = $loan->getLoanName($loanId);
			++$i;
		}		
		echo json_encode ( $outputArray );
	}elseif ($_POST ['task'] == 'fetchBankNames') {
		$bankIds = $bank->getBankIds(1);
		$i = 0;
		foreach($bankIds as $bankId){
			$outputArray[$i][] = $bankId;
			$outputArray[$i][] = $bank->getBankName($bankId);
			++$i;
		}		
		echo json_encode ( $outputArray );
	}elseif ($_POST ['task'] == 'fetchInterestTypes') {
		$optionIds = $options->getOptionValueIds('INTYP', 1);
		$i = 0;
		foreach($optionIds as $optionId){
			$outputArray[$i][] = $optionId;
			$outputArray[$i][] = $options->getOptionIdValue($optionId);
			++$i;
		}		
		echo json_encode ( $outputArray );
	}elseif ($_POST ['task'] == 'fetchPaymentTypes') {
		$optionIds = $options->getOptionValueIds('PAOPL', 1);
		$i = 0;
		foreach($optionIds as $optionId){
			$outputArray[$i][] = $optionId;
			$outputArray[$i][] = $options->getOptionIdValue($optionId);
			++$i;
		}		
		echo json_encode ( $outputArray );
	}elseif ($_POST ['task'] == 'fetchInputTypes') {
		$outputArray[0] = $bank->getOfficerName($_POST['employeeId_val']);
		$outputArray[] = $registration->getEmployeeRegistrationNumber($_POST['employeeId_val']);
		
		
		$outputArray[] = $loan->getLoanName($_POST['loanType']);
		
		$outputArray[] = date('M, Y', mktime(0, 0, 0, substr($_POST['repaymentMonth'], 4, 2), 15, substr($_POST['repaymentMonth'], 0, 4)));
		
		$outputArray[] = $options->getOptionIdValue($_POST['interestType']);
		$outputArray[] = $options->getOptionIdValue($_POST['paymentMode']);
		$outputArray[] = $_POST['flexiInstallment'] == 'y' ? '<font class="green">Enabled</font>' : '<font class="red">Disabled</font>';
		$outputArray[] = $bank->getBankName($_POST['bankName']);
		
		
		echo json_encode ( $outputArray );
	}elseif ($_POST ['task'] == 'insertRecord') {				
		$loanAccountId = $loan->sanctionLoan2Employee($_POST['employeeId_val'], $_POST['loanType'], $_POST['amount'], $_POST['installment'], $_POST['loanDate'], $_POST['interest'], $_POST['flexiInstallment'], $_POST['interestType'], $_POST['paymentMode']);
				
		if($_POST['paymentMode'] == 'LRESER16'){
			$chequeId = $payment->setChequeDetails($loanAccountId, $_POST['bankName'], $_POST['chequeNumber'], $loan->getCurrentDate(), $_POST['loanDate']);
		}elseif($_POST['paymentMode'] == 'LRESER15'){
			$loanDetails = $loan->getTableIdDetails($_POST['loanType']);
			$directInsertion->setDirectSalaryAdditionDetails($_POST['employeeId_val'], $loanDetails['allowance_id'], date('Ym', mktime(0, 0, 0, substr($_POST['loanDate'], 5, 2), 15, substr($_POST['loanDate'], 0, 4))), $_POST['amount'], "New Loan Sanction Amount");
		}
		$month = date('Ym', mktime(0, 0, 0, date('m'), 15, date('Y')));
		if($month != $_POST['repaymentMonth'])
			$loan->setStopLoanInstallment($loanAccountId, $month, $_POST['repaymentMonth'], 'Loan Sanction First Installment Break');
				
		$outputArray[0] = $loanAccountId;
		echo json_encode ( $outputArray );
	} else {
		$outputArray [0] = 0;
		echo json_encode ( $outputArray );
	}
}
?>