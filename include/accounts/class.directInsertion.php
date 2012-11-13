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
require_once BASE_PATH . 'include/accounts/class.accounts.php';
class DirectInsertion extends Accounts {
	public function __construct() {
		parent::__construct ();
	}
	public function getDirectSalaryIds4Month($month, $active) {
		if ($active) {
			if ($active === 'all')
				$sqlQuery = "SELECT id 
                                FROM accounts_salaryaddition_details 
                                WHERE month = \"$month\"
                                ORDER BY amount ASC";
			else
				$sqlQuery = "SELECT id 
                                FROM accounts_salaryaddition_details
                                WHERE month = \"$month\"
                                	AND active = \"y\"
                                ORDER BY amount ASC";
		} else
			$sqlQuery = "SELECT id 
                            FROM accounts_salaryaddition_details
                            WHERE month = \"$month\"
                            	AND active != \"y\"
                            ORDER BY amount ASC";
		
		return $this->getDataArray ( $this->processQuery ( $sqlQuery ) );
	}
	public function getDirectSalaryIds4Employee($employeeId, $month, $active) {
		if ($active) {
			if ($active === 'all')
				$sqlQuery = "SELECT id
                                FROM accounts_salaryaddition_details 
                                WHERE month = \"$month\"
                                  AND employee_id = \"$employeeId\"
                                ORDER BY amount ASC";
			else
				$sqlQuery = "SELECT id
                                FROM accounts_salaryaddition_details
                                WHERE month = \"$month\"
                                	AND employee_id = \"$employeeId\"
                                	AND active = \"y\"
                                ORDER BY amount ASC";
		} else
			$sqlQuery = "SELECT id
                            FROM accounts_salaryaddition_details
                            WHERE month = \"$month\"
                            	AND employee_id = \"$employeeId\"
                            	AND active != \"y\"
                            ORDER BY amount ASC";
		
		return $this->getDataArray ( $this->processQuery ( $sqlQuery ) );
	}
	public function getDirectSalaryAmount4Employee($employeeId, $month) {
		$sqlQuery = "SELECT SUM(amount) 
						FROM accounts_salaryaddition_details 
						WHERE employee_id = \"$employeeId\" 
							AND month = \"$month\" 
							AND active = \"y\" ";
		$sqlQuery = $this->processArray ( $sqlQuery );
		if ($sqlQuery [0] != '')
			return $sqlQuery [0];
		return false;
	}
	public function getDirectSalaryId4EmployeeAllowance($employeeId, $allowanceId, $month, $active) {
		if ($active) {
			if ($active === 'all')
				$sqlQuery = "SELECT id
                                FROM accounts_salaryaddition_details
                                WHERE month = \"$month\"
                                  AND employee_id = \"$employeeId\"
                                  AND allowance_id = \"$allowanceId\"
                                ORDER BY amount ASC";
			else
				$sqlQuery = "SELECT id
                                FROM accounts_salaryaddition_details
                                WHERE month = \"$month\"
                                	AND employee_id = \"$employeeId\"
                                	AND allowance_id = \"$allowanceId\"
                                	AND active = \"y\"
                                ORDER BY amount ASC";
		} else
			$sqlQuery = "SELECT id
                            FROM accounts_salaryaddition_details
                            WHERE month = \"$month\"
                            	AND employee_id = \"$employeeId\"
                            	AND allowance_id = \"$allowanceId\"
                            	AND active != \"y\"
                            ORDER BY amount ASC";
		
		$sqlQuery = $this->processArray ( $sqlQuery );
		if ($sqlQuery [0] != '')
			return $sqlQuery [0];
		return false;
	}
	public function setDirectSalaryAdditionDetails($employeeId, $allowanceId, $month, $amount, $remarks, $sourceId) {
		$salaryAdditionId = $this->getDirectSalaryId4EmployeeAllowance ( $employeeId, $allowanceId, $month, 'all' );
		if ($salaryAdditionId) {
			$details = $this->getTableIdDetails ( $salaryAdditionId );
			
			$details ['amount'] == $amount ? '' : $this->updateTableParameter ( 'amount', $amount );
			$details ['comments'] == $remarks ? '' : $this->updateTableParameter ( 'comments', $remarks );
			$this->commitDirectSalaryAdditionUpdate ( $salaryAdditionId );
			
			return $salaryAdditionId;
		} else {
			$counter = $this->getCounter ( 'salaryAddition' );
			$sqlQuery = "INSERT INTO accounts_salaryaddition_details
                            (id, employee_id, allowance_id, amount, month, comments, source_id, last_update_date, last_updated_by, creation_date, created_by, active)
                            VALUES (\"$counter\", \"$employeeId\", \"$allowanceId\", \"$amount\", \"$month\", \"$remarks\", \"$sourceId\", \"" . $this->getCurrentDateTime () . "\", \"" . $this->getLoggedUserId () . "\", \"" . $this->getCurrentDateTime () . "\", \"" . $this->getLoggedUserId () . "\", \"y\")";
			if ($this->processQuery ( $sqlQuery, $counter )) {
				$this->logOperation ( $counter, "new record" );
				return $counter;
			}
			return false;
		}
		return false;
	}
	public function commitDirectSalaryAdditionUpdate($salaryAdditionId) {
		if ($this->sqlConstructQuery == "")
			return $salaryAdditionId;
		
		return $this->commitUpdate ( $salaryAdditionId );
	}
	public function dropDirectSalaryAdditionDetails($salaryAdditionId) {
		if ($this->dropTableId ( $salaryAdditionId, false )) {
			$this->logOperation ( $salaryAdditionId, "Dropped" );
			return true;
		}
		return false;
	}
	public function activateDirectSalaryAdditionDetails($salaryAdditionId) {
		if ($this->activateTableId ( $salaryAdditionId )) {
			$this->logOperation ( $salaryAdditionId, "Activated" );
			return true;
		}
		return false;
	}
	
	// functions related to adding up extra components for the given direct
	// salary details
	public function setDirectAllowanceComponentsInsertion($sourceId, $employeeId, $changeAllowanceId, $remarks, $month, $amount) {
		$allowanceIds = $this->getAllowanceDependentAllowanceIds ( $changeAllowanceId );
		foreach ( $allowanceIds as $allowanceId ) {
			if ($changeAllowanceId == $allowanceId)
				continue;
			$masterSalaryId = $this->getEmployeeMasterSalaryId ( $employeeId, $allowanceId, true );
			if ($masterSalaryId) {
				$accountSum = $this->getDirectAccountSum ( $employeeId, $allowanceId, $month, $changeAllowanceId, $amount );
				$this->setDirectSalaryAdditionDetails ( $employeeId, $allowanceId, $month, $accountSum, $remarks, $sourceId );
			}
		}
	}
	public function getDirectAccountSum($employeeId, $allowanceId, $month, $actualAllowanceId, $amount) {
		if ($allowanceId == $actualAllowanceId)
			return $amount;
		
		$dependentIds = $this->getAllowanceComputationalIds ( $allowanceId, 1 );
		$total = 0;
		foreach ( $dependentIds as $dependentId ) {
			$details = $this->getTableIdDetails ( $dependentId );
			if ($details ['dependent_id'] == '') { // this is a absolute sum type
			                                       // allowance
				if ($details ['type'] == 'c')
					$total += $details ['magnitude'];
				else
					$total -= $details ['magnitude'];
			} else {
				if ($details ['type'] == 'c')
					$total += $details ['magnitude'] * .01 * abs ( $this->getDirectAccountSum ( $employeeId, $details ['dependent_id'], $month, $actualAllowanceId, $amount ) );
				else
					$total -= $details ['magnitude'] * .01 * abs ( $this->getDirectAccountSum ( $employeeId, $details ['dependent_id'], $month, $actualAllowanceId, $amount ) );
			}
		}
		return $total;
	}
}

?>