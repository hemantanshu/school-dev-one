<?php

/**
 * This class will hold the functionalities related to the bank details
 * This class extends the general class
 * @author Hemant Kumar Sah
 * @category Global
 * @license Support-Gurukul
 * @version 1.0.0
 */
require_once 'config.php';
require_once BASE_PATH . 'include/global/class.general.php';

class Payment extends general {
	
	public function __construct() {
		parent::__construct ();
	}
	
	public function setChequeDetails($sourceId, $bankId, $chequeNumber, $issueDate, $chequeDate) {
		$counter = $this->getCounter ( 'bankName' );
		$sqlQuery = "INSERT INTO accounts_cheque_details
				    	(id, source_id, bank_id, cheque_number, issue_date, cheque_date, last_update_date, last_updated_by, creation_date, created_by, active)
				    	VALUES (\"$counter\", \"$sourceId\", \"$bankId\", \"$chequeNumber\", \"$issueDate\", \"$chequeDate\", \"" . $this->getCurrentDateTime () . "\", \"" . $this->getLoggedUserId () . "\", \"" . $this->getCurrentDateTime () . "\", \"" . $this->getLoggedUserId () . "\", \"y\")";
		if ($this->processQuery ( $sqlQuery, $counter )) {
			$this->logOperation ( $counter, "new record" );
			return $counter;
		}
		return false;
	}
	
	public function getPaymentChequeDetails($sourceId, $active){
		if($active){
			if($active === 'all')
				$sqlQuery = "SELECT id FROM accounts_cheque_details WHERE source_id = \"$sourceId\" ";
			else
				$sqlQuery = "SELECT id FROM accounts_cheque_details WHERE source_id = \"$sourceId\" AND active = \"y\" ";
		}else
			$sqlQuery = "SELECT id FROM accounts_cheque_details WHERE source_id = \"$sourceId\" AND active != \"y\" ";
		
		return $this->getDataArray($this->processQuery($sqlQuery));
	}
	
			
	public function dropChequeDetails($chequeId) {
		if ($this->dropTableId ( $chequeId, false )) {
			$this->logOperation ( $chequeId, "Dropped" );
			return true;
		}
		return false;
	}
	
	public function activateChequeDetails($chequeId) {
		if ($this->activateTableId ( $chequeId )) {
			$this->logOperation ( $chequeId, "Activated" );
			return true;
		}
		return false;
	}
	
	public function commitChequeDetailsUpdate($chequeId) {
		if ($this->sqlConstructQuery == "")
			return false;
		
		return $this->commitUpdate($chequeId);
	}
	
	//functions related to bank transfer
	public function setBankTransferDetails($sourceId, $bankId, $accountNumber){
		$counter = $this->getCounter ( 'bankTransferDetails' );
		$sqlQuery = "INSERT INTO accounts_banktransfer_details
						(id, source_id, bank_id, account_number, last_update_date, last_updated_by, creation_date, created_by, active)
						VALUES (\"$counter\", \"$sourceId\", \"$bankId\", \"$accountNumber\", \"". $this->getCurrentDateTime () . "\", \"" . $this->getLoggedUserId () . "\", \"" . $this->getCurrentDateTime () . "\", \"" . $this->getLoggedUserId () . "\", \"y\")";
		if ($this->processQuery ( $sqlQuery, $counter )) {
			$this->logOperation ( $counter, "new record" );
			return $counter;
		}
		return false;
	}
	
	public function getBankTransferId4Source($sourceId, $active){
		if($active){
			if($active === 'all')
				$sqlQuery = "SELECT id FROM accounts_banktransfer_details WHERE source_id = \"$sourceId\" ";
			else
				$sqlQuery = "SELECT id FROM accounts_banktransfer_details WHERE source_id = \"$sourceId\" AND active = \"y\" ";			
		}else
			$sqlQuery = "SELECT id FROM accounts_banktransfer_details WHERE source_id = \"$sourceId\" AND active != \"y\" ";
		
		return $this->getDataArray($this->processQuery($sqlQuery));
	}
	
	public function getSalaryProcessId4PaymentType($paymentType, $month){
		$sqlQuery = "SELECT id 
						FROM accounts_salary_process_record 
						WHERE payment_mode = \"$paymentType\" 
							AND month = \"$month\" 
							AND active = \"y\" ";
		return $this->getDataArray($this->processQuery($sqlQuery));
	}
	
	public function getSalaryProcessId4PaymentTypeEmployeeType($paymentType, $employeeType, $month){
		$sqlQuery = "SELECT a.id
						FROM accounts_salary_process_record a,
							accounts_salary_employee_record b
						WHERE a.employee_id = b.employee_id
						AND b.employee_type = \"$employeeType\"
						AND b.month = \"$month\"
						AND a.payment_mode = \"$paymentType\"
						AND a.month = \"$month\"
						AND a.active = \"y\"
						AND b.active = \"y\"
						ORDER BY a.employee_id ASC
						 ";
		return $this->getDataArray($this->processQuery($sqlQuery));
	}
	
	

}

?>