<?php

require_once '../global/class.sqlFunction.php';
require_once 'class.accounts.php';
require_once 'class.bank.php';
require_once 'class.payment.php';

class accountsTesting extends sqlFunction {
	private $_accounts, $bank, $payment;
	
	public function __construct() {
		parent::__construct ();
		$this->_accounts = new Accounts();
		$this->bank = new Bank();
		$this->payment = new Payment();
	}
	
	public function correctBankTransfer(){
		$sqlQuery = "SELECT id from accounts_salary_process_record WHERE month = \"201209\" AND payment_mode = \"LRESER17\" ";
		$sourceIds = $this->getDataArray($this->processQuery($sqlQuery));
		
		foreach ($sourceIds as $sourceId){
			$details = $this->getTableIdDetails($sourceId);
			
			$employeeBankId = $this->bank->getEmployeeBankAccountId($details['employee_id'], 'LRESER7');
			$bankDetails = $this->getTableIdDetails($employeeBankId);
			$this->payment->setBankTransferDetails($sourceId, $bankDetails['bank_id'], $bankDetails['account_number']);
		}
		
		$sqlQuery = "UPDATE accounts_loan_installments SET payment_type = REPLACE(payment_type, \"RESER\", \"LRESER\") WHERE MONTH =\"201209\" ";
		$this->processQuery($sqlQuery);
	}
	
	public function correctEmployeeType(){
		$sqlQuery = "SELECT id FROM utl_employee_registration ";
		$registrationIds = $this->getDataArray($this->processQuery($sqlQuery));
		foreach($registrationIds as $registrationId){
			$details = $this->getTableIdDetails($registrationId);
			
			$sqlQuery = "UPDATE accounts_salary_employee_record SET department_id = \"".$details['deparment_id']."\", employee_type = \"".$details['employee_type']."\" WHERE employee_id = \"".$details['employee_id']."\" ";
			$this->processQuery($sqlQuery);
		}
	}
	
}

?>