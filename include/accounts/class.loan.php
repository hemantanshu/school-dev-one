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
class Loan extends general {
	public function __construct() {
		parent::__construct ();
	}
	public function getLoanIds($active) {
		if ($active) {
			if ($active === 'all')
				$sqlQuery = "SELECT id 
                                FROM accounts_loan_type 
                                ORDER BY loan_name ASC";
			else
				$sqlQuery = "SELECT id 
                                FROM accounts_loan_type
                                WHERE active = \"y\"
                                ORDER BY loan_name ASC";
		} else
			$sqlQuery = "SELECT id 
                            FROM accounts_loan_type
                            WHERE active != \"y\"
                            ORDER BY loan_name ASC";
		
		return $this->getDataArray ( $this->processQuery ( $sqlQuery ) );
	}
	public function getLoanName($loanTypeId) {
		$sqlQuery = "SELECT loan_name FROM accounts_loan_type WHERE id = \"$loanTypeId\" ";
		$sqlQuery = $this->processArray ( $sqlQuery );
		return $sqlQuery [0];
	}
	public function dropLoanTypeDetails($loanTypeId) {
		if ($this->dropTableId ( $loanTypeId, false )) {
			$this->logOperation ( $loanTypeId, "Dropped" );
			return true;
		}
		return false;
	}
	public function activateLoanTypeDetails($loanTypeId) {
		if ($this->activateTableId ( $loanTypeId )) {
			$this->logOperation ( $loanTypeId, "Activated" );
			return true;
		}
		return false;
	}
	public function setLoanTypeDetails($allowanceId, $loanName, $minAmount, $maxAmount) {
		$counter = $this->getCounter ( 'loanType' );
		$sqlQuery = "INSERT INTO accounts_loan_type 
                            (id, allowance_id, loan_name, min_amount, max_amount, last_update_date, last_updated_by, creation_date, created_by, active) 
                            VALUES (\"$counter\", \"$allowanceId\", \"$loanName\", \"$minAmount\", \"$maxAmount\", \"" . $this->getCurrentDateTime () . "\", \"" . $this->getLoggedUserId () . "\", \"" . $this->getCurrentDateTime () . "\", \"" . $this->getLoggedUserId () . "\", \"y\")";
		if ($this->processQuery ( $sqlQuery, $counter )) {
			$this->logOperation ( $counter, "new record" );
			return $counter;
		}
		return false;
	}
	public function commitLoanTypeDetailsUpdate($loanTypeId) {
		if ($this->sqlConstructQuery == "")
			return false;
		
		return $this->commitUpdate ( $loanTypeId );
	}
	
	// functions related to the bank account details
	public function getLoanTypeLoanAccountIds($loanType, $active) {		
		if ($active) {
			if ($active === 'all')
				$sqlQuery = "SELECT id
				    			FROM accounts_loan_account
				    			WHERE loan_type_id = \"$loanType\"
				    			ORDER BY loan_date ASC";
			else
				$sqlQuery = "SELECT id
					    		FROM accounts_loan_account
						    	WHERE loan_type_id = \"$loanType\"
						    		AND active = \"y\"
						    	ORDER BY loan_date ASC";
		} else
			$sqlQuery = "SELECT id
					    	FROM accounts_loan_account
					    	WHERE loan_type_id = \"$loanType\"
					    		AND active != \"y\"
					    	ORDER BY loan_date ASC";
		
		return $this->getDataArray ( $this->processQuery ( $sqlQuery ) );
	}
	public function getEmployeeLoanIds($employeeId, $active) {
		if ($active) {
			if ($active === 'all')
				$sqlQuery = "SELECT id
                                FROM accounts_loan_account
                                	WHERE employee_id = \"$employeeId\"
                                ORDER BY loan_date ASC";
			else
				$sqlQuery = "SELECT id
                                FROM accounts_loan_account
                                WHERE employee_id = \"$employeeId\"
                                	AND active = \"y\"
                                ORDER BY loan_date ASC";
		} else
			$sqlQuery = "SELECT id
                            FROM accounts_loan_account
                            WHERE employee_id = \"$employeeId\"
                            	AND active != \"y\"
                            ORDER BY loan_date ASC";
		
		return $this->getDataArray ( $this->processQuery ( $sqlQuery ) );
	}
	public function getLoanAccountIds($active) {
		if ($active) {
			if ($active === 'all')
				$sqlQuery = "SELECT id
                                FROM accounts_loan_account
                                ORDER BY loan_date ASC";
			else
				$sqlQuery = "SELECT id
                                FROM accounts_loan_account
                                WHERE active = \"y\"
                                ORDER BY loan_date ASC";
		} else
			$sqlQuery = "SELECT id
                            FROM accounts_loan_account
                            WHERE active != \"y\"
                            ORDER BY loan_date ASC";
		
		return $this->getDataArray ( $this->processQuery ( $sqlQuery ) );
	}
	public function sanctionLoan2Employee($employeeId, $loanType, $amount, $installment, $loanDate, $interest, $flexiInsallment, $interestType, $paymentMode) {
		$counter = $this->getCounter ( 'loanAccount' );
		$installmentAmount = ceil ( $amount / $installment );
		$sqlQuery = "INSERT INTO accounts_loan_account 
    					(id, employee_id, loan_type_id, amount, interest, total_installment, loan_date, installment_amount, flexible_installment, interest_type, payment_mode, last_update_date, last_updated_by, creation_date, created_by, active) 
    					VALUES (\"$counter\", \"$employeeId\", \"$loanType\", \"$amount\", \"$interest\", \"$installment\", \"$loanDate\", \"$installmentAmount\", \"$flexiInsallment\", \"$interestType\", \"$paymentMode\", \"" . $this->getCurrentDateTime () . "\", \"" . $this->getLoggedUserId () . "\", \"" . $this->getCurrentDateTime () . "\", \"" . $this->getLoggedUserId () . "\", \"y\")";
		
		if ($this->processQuery ( $sqlQuery, $counter )) {
			$counter1 = $this->getCounter ( 'accountsLoanInstallment' );
			$month = date ( 'Ym', mktime ( 0, 0, 0, substr ( $loanDate, 5, 2 ), substr ( $loanDate, 8, 2 ), substr ( $loanDate, 0, 4 ) ) );
			$sqlQuery = "INSERT INTO accounts_loan_installment
				    		(id, loan_account_id, loan_type_id, employee_id, amount, payment_date, month, payment_type, last_update_date, last_updated_by, creation_date, created_by, active)
				    		VALUES (\"$counter1\", \"$counter\", \"$loanType\", \"$employeeId\", \"$amount\", \"$loanDate\", \"$month\", \"LRESER8\", \"" . $this->getCurrentDateTime () . "\", \"" . $this->getLoggedUserId () . "\", \"" . $this->getCurrentDateTime () . "\", \"" . $this->getLoggedUserId () . "\", \"y\")";
			
			if ($this->processQuery ( $sqlQuery, $counter1 )) {
				$this->logOperation ( $counter, 'New Entry' );
				$this->logOperation ( $counter1, 'New Entry' );
				return $counter;
			}
			return false;
		}
		return false;
	}
	public function dropEmployeeLoanAccount($loanAccountId) {
		if ($this->dropTableId ( $loanAccountId, false )) {
			$this->logOperation ( $loanAccountId, "Dropped" );
			return true;
		}
		return false;
	}
	public function setLoanInstallmentAmount($loanAccountId, $amount, $type) {
		$counter = $this->getCounter ( 'accountsLoanInstallment' );
		$details = $this->getTableIdDetails ( $loanAccountId );
		
		$sqlQuery = "INSERT INTO accounts_loan_installment
                            (id, loan_account_id, loan_type_id, employee_id, amount, payment_date, month, payment_type, last_update_date, last_updated_by, creation_date, created_by, active)
				    		VALUES (\"$counter\", \"" . $details ['id'] . "\", \"" . $details ['loan_type_id'] . "\", \"" . $details ['employee_id'] . "\", \"$amount\", \"" . $this->getCurrentDate () . "\", \"" . date ( 'Ym' ) . "\", \"LRESER9\", \"" . $this->getCurrentDateTime () . "\", \"" . $this->getLoggedUserId () . "\", \"" . $this->getCurrentDateTime () . "\", \"" . $this->getLoggedUserId () . "\", \"y\")";
		if ($this->processQuery ( $sqlQuery, $counter )) {
			$this->logOperation ( $counter, "new record" );
			return $counter;
		}
		return false;
	}
	public function getLoanAmountLeft($loanAccountId, $month) {
		$sqlQuery = "SELECT SUM(amount) 
    					FROM accounts_loan_installment 
    					WHERE loan_account_id = \"$loanAccountId\" 
    						AND month <= \"$month\" 
    						AND active = \"y\" ";
		$sqlQuery = $this->processArray ( $sqlQuery );
		return $sqlQuery [0];
	}
	public function getLoanAmount4Type($loanAccountId, $month, $type) {
		$sqlQuery = "SELECT SUM(amount)
				    	FROM accounts_loan_installment
				    	WHERE loan_account_id = \"$loanAccountId\"
					    	AND month <= \"$month\"
					    	AND payment_type = \"$type\"
					    	AND active = \"y\" ";
		$sqlQuery = $this->processArray ( $sqlQuery );
		return $sqlQuery [0];
	}
	
	public function getLoanAccountInstallmentIds($loanAccountId){
		$sqlQuery = "SELECT id FROM accounts_loan_installment 
							WHERE loan_account_id = \"$loanAccountId\" 
								AND active = \"y\" 
							ORDER BY payment_date ASC ";
		return $this->getDataArray($this->processQuery($sqlQuery));
	}
	public function getLoanInstallmentIds($loanAccountId, $month, $type, $active) {
		if ($active) {
			if ($active === 'all')
				$sqlQuery = "SELECT id
				   				FROM accounts_loan_installment
				   				WHERE loan_account_id = \"$loanAccountId\"
					   				AND month = \"$month\"
					   				AND payment_type = \"$type\" ";
			else
				$sqlQuery = "SELECT id
				   				FROM accounts_loan_installment
				   				WHERE loan_account_id = \"$loanAccountId\"
					   				AND month = \"$month\"
					   				AND payment_type = \"$type\"
   									AND active = \"y\" ";
		} else
			$sqlQuery = "SELECT id
                            FROM accounts_loan_installment
                            WHERE loan_account_id = \"$loanAccountId\"
                                AND month = \"$month\"
                                AND payment_type = \"$type\"
                                AND active != \"y\" ";
		
		return $this->getDataArray ( $this->processQuery ( $sqlQuery ) );
	}
	public function getLoanAccountInstallmentAmount($loanAccountId, $flag) {
		$sqlQuery = "SELECT installment_amount FROM accounts_loan_account WHERE id = \"$loanAccountId\" ";
		$sqlQuery = $this->processArray ( $sqlQuery );
		$loanInstallmentAmount = $sqlQuery [0];
		
		$loanLeft = $this->getLoanAmountLeft ( $loanAccountId, date ( 'Ym' ) );
		if ($loanLeft > $loanInstallmentAmount)
			return $loanInstallmentAmount;
		else {
			if ($flag)
				$this->dropEmployeeLoanAccount ( $loanAccountId );
			return $loanLeft;
		}
		return false;
	}
	public function dropLoanInstallmentDetails($loanAccountId, $month, $type) {
		$loanInstallmentIds = $this->getLoanAccountIds ( $loanAccountId, $month, $type, 1 );
		foreach ( $loanInstallmentIds as $loanInstallmentId ) {
			if ($this->dropTableId ( $loanInstallmentId, false ))
				$this->logOperation ( $loanInstallmentId, 'Dropped' );
		}
		return true;
	}
	
	// functions related to the stop loan installment
	private function setStopLoanInstallmentMonth($loanAccountId, $employeeId, $month, $comments) {
		$counter = $this->getCounter ( 'stopLoanInstallment' );
		$sqlQuery = "INSERT INTO accounts_stoploan_installment
				   		(id, loan_account_id, employee_id, month, comments, last_update_date, last_updated_by, creation_date, created_by, active)
				   		VALUES (\"$counter\", \"$loanAccountId\", \"$employeeId\", \"$month\", \"$comments\", \"" . $this->getCurrentDateTime () . "\", \"" . $this->getLoggedUserId () . "\", \"" . $this->getCurrentDateTime () . "\", \"" . $this->getLoggedUserId () . "\", \"y\")";
		if ($this->processQuery ( $sqlQuery, $counter )) {
			$this->logOperation ( $counter, "new record" );
			return $counter;
		}
		return false;
	}
	public function setStopLoanInstallment($loanAccountId, $startMonth, $endMonth, $comments) {
		$details = $this->getTableIdDetails ( $loanAccountId );
		$employeeId = $details ['employee_id'];
		$i = 0;
		while ( true ) {
			$month = date ( 'Ym', mktime ( 0, 0, 0, substr ( $startMonth, 4, 2 ) + $i, 15, substr ( $startMonth, 0, 4 ) ) );
			if ($month >= $endMonth)
				break;
			$stopLoanInstallmentId = $this->getStopLoanInstallmentId4Month ( $loanAccountId, $month, 'all' );
			if ($stopLoanInstallmentId) {
				$details = $this->getTableIdDetails ( $stopLoanInstallmentId );
				$remarks = $comments . "<br />" . $details ['comments'];
				
				$this->updateTableParameter ( 'comments', $remarks );
				$this->updateTableParameter ( 'active', 'y' );
				$this->commitStopLoanInstallmentDetailsUpdate ( $stopLoanInstallmentId );
			} else
				$this->setStopLoanInstallmentMonth ( $loanAccountId, $employeeId, $month, $comments );
			++ $i;
		}
		
		return true;
	}
	public function getStopLoanInstallmentId4Month($loanAccountId, $month, $active) {
		if ($active) {
			if ($active === 'all')
				$sqlQuery = "SELECT id
   								FROM accounts_stoploan_installment
   								WHERE loan_account_id = \"$loanAccountId\"
   									AND month = \"$month\" ";
			else
				$sqlQuery = "SELECT id
                            FROM accounts_stoploan_installment
                            WHERE loan_account_id = \"$loanAccountId\"
                                AND month = \"$month\"
   								AND active = \"y\" ";
		} else
			$sqlQuery = "SELECT id
                            FROM accounts_stoploan_installment
                            WHERE loan_account_id = \"$loanAccountId\"
                                AND month = \"$month\" 
   								AND active != \"y\" ";
		$sqlQuery = $this->processArray ( $sqlQuery );
		if ($sqlQuery [0] != '')
			return $sqlQuery [0];
		return false;
	}
	public function getStopLoanInstallmentId($loanAccountId, $active) {
		if ($active) {
			if ($active === 'all')
				$sqlQuery = "SELECT id
                            FROM accounts_stoploan_installment
                            WHERE loan_account_id = \"$loanAccountId\" ";
			else
				$sqlQuery = "SELECT id
                        FROM accounts_stoploan_installment
                        WHERE loan_account_id = \"$loanAccountId\"
                            AND active = \"y\" ";
		} else
			$sqlQuery = "SELECT id
                        FROM accounts_stoploan_installment
                        WHERE loan_account_id = \"$loanAccountId\"
                            AND active != \"y\" ";
		
		return $this->getDataArray ( $this->processQuery ( $sqlQuery ) );
	}
	public function commitStopLoanInstallmentDetailsUpdate($stopLoanInstallmentId) {
		if ($this->sqlConstructQuery == "")
			return $stopLoanInstallmentId;
		
		return $this->commitUpdate ( $stopLoanInstallmentId );
	}
	public function dropStopLoanInstallmentId($stopLoanInstallmentId) {
		if ($this->dropTableId ( $stopLoanInstallmentId, false )) {
			$this->logOperation ( $stopLoanInstallmentId, 'dropped' );
			return true;
		}
		return false;
	}
	public function activateStopLoanInstallmentId($stoploanInstallmentId) {
		if ($this->activateTableId ( $stoploanInstallmentId )) {
			$this->logOperation ( $stoploanInstallmentId, 'activated' );
			return true;
		}
		return false;
	}
	
	// functions related to the loan installment
	public function setEmployeeLoanRecordDetails($employeeId, $loanId, $amountLoan, $amountLeft, $amountInstallment, $month) {
		$counter = $this->getCounter ( 'salaryLoanRecord' );
		$sqlQuery = "INSERT INTO accounts_salary_loan_record
				   		(id, employee_id, loan_id, amount_loan, amount_left, amount_installment, month, last_update_date, last_updated_by, creation_date, created_by, active)
				   		VALUES (\"$counter\", \"$employeeId\", \"$loanId\", \"$amountLoan\", \"$amountLeft\", \"$amountInstallment\", \"$month\", \"" . $this->getCurrentDateTime () . "\", \"" . $this->getLoggedUserId () . "\", \"" . $this->getCurrentDateTime () . "\", \"" . $this->getLoggedUserId () . "\", \"y\")";
		if ($this->processQuery ( $sqlQuery, $counter )) {
			$this->logOperation ( $counter, "new record" );
			return $counter;
		}
		return false;
	}
	public function dropEmployeeLoanRecordDetails($loanRecordId) {
		if ($this->dropTableId ( $loanRecordId, false )) {
			$this->logOperation ( $loanRecordId, 'dropped' );
			return true;
		}
		return false;
	}
	public function activateEmployeeLoanRecordDetails($loanRecordId) {
		if ($this->activateTableId ( $loanRecordId )) {
			$this->logOperation ( $loanRecordId, 'activated' );
			return true;
		}
		return false;
	}
	public function getEmployeeLoanRecordDetailsIds($employeeId, $month) {
		$sqlQuery = "SELECT id 
   						FROM accounts_salary_loan_record 
   						WHERE employee_id = \"$employeeId\" 
   							AND month = \"$month\" 
   							AND active = \"y\" ";
		return $this->getDataArray ( $this->processQuery ( $sqlQuery ) );
	}
}

?>