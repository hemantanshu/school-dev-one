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
require_once BASE_PATH . 'include/accounts/class.allowance.php';

class Accounts extends Allowance {
	private $_dependentIds;
	public function __construct() {
		parent::__construct ();
	}
	
	public function getEmployeeMasterSalaryId($employeeId, $allowanceId, $flag) {
		if($flag)
			$sqlQuery = "SELECT id
					    	FROM accounts_mastersalary_details
					    	WHERE employee_id = \"$employeeId\"
						    	AND allowance_id = \"$allowanceId\"
								AND active = \"y\" ";
		else
			$sqlQuery = "SELECT id
							FROM accounts_mastersalary_details
							WHERE employee_id = \"$employeeId\"
								AND allowance_id = \"$allowanceId\" ";
		$sqlQuery = $this->processArray ( $sqlQuery );
		if ($sqlQuery [0] != '')
			return $sqlQuery [0];
		return false;
	}
	
	public function getEmployeeMasterSalaryIds($employeeId, $active) {
		if ($active) {
			if ($active === 'all')
				$sqlQuery = "SELECT a.id 
    							FROM accounts_mastersalary_details a,
    								accounts_allowance_details b,
    								accounts_accounthead_details c
						    	WHERE a.employee_id = \"$employeeId\" 
									AND a.allowance_id = b.id
									AND c.id = b.accounthead_id
								ORDER BY c.display_order ASC ";
			else
				$sqlQuery = "SELECT a.id 
    							FROM accounts_mastersalary_details a,
    								accounts_allowance_details b,
    								accounts_accounthead_details c
						    	WHERE a.employee_id = \"$employeeId\" 
									AND a.allowance_id = b.id
									AND c.id = b.accounthead_id
									AND a.active = \"y\"
								ORDER BY c.display_order ASC ";
		} else
			$sqlQuery = "SELECT a.id
				    		FROM accounts_mastersalary_details a,
    								accounts_allowance_details b,
    								accounts_accounthead_details c
						    	WHERE a.employee_id = \"$employeeId\" 
									AND a.allowance_id = b.id
									AND c.id = b.accounthead_id
									AND a.active != \"y\"
								ORDER BY c.display_order ASC ";
		return $this->getDataArray ( $this->processQuery ( $sqlQuery ) );
	}
	
	public function getEmployeeMasterSalaryTotalAmount($employeeId){
		$sqlQuery = "SELECT SUM(amount) 
						FROM accounts_mastersalary_details 
						WHERE employee_id = \"$employeeId\" 
							AND active = \"y\" ";
		$sqlQuery = $this->processArray($sqlQuery);
		return $sqlQuery[0];
	}
	
	public function setEmployeeMasterSalaryInfo($employeeId, $allowanceId, $amount, $overRidden) {
		$masterSalaryId = $this->getEmployeeMasterSalaryId ( $employeeId, $allowanceId , false);
		if ($masterSalaryId) {
			
			$details = $this->getTableIdDetails ( $masterSalaryId );
			$details ['allowance_id'] == $allowanceId ? '' : $this->updateTableParameter ( 'allowance_id', $allowanceId );
			$details ['amount'] == $amount ? '' : $this->updateTableParameter ( 'amount', $amount );
			$details ['over_ridden'] == $overRidden ? '' : $this->updateTableParameter ( 'over_ridden', $overRidden );
			$details['active'] == 'y' ? '' : $this->updateTableParameter('active', 'y');
			
			$this->commitMasterSalaryDetailsUpdate ( $masterSalaryId );
			if ($amount != $details ['amount']) {				
				$this->updateNonOverRiddenEmployeeAllowanceAmount ( $employeeId, $allowanceId );
			}
			return $masterSalaryId;
		} else {
			$counter = $this->getCounter ( 'masterSalaryDetails' );
			$sqlQuery = "INSERT INTO accounts_mastersalary_details
				    		(id, employee_id, allowance_id, amount, over_ridden, last_update_date, last_updated_by, creation_date, created_by, active)
				    		VALUES (\"$counter\", \"$employeeId\", \"$allowanceId\", \"$amount\", \"$overRidden\", \"" . $this->getCurrentDateTime () . "\", \"" . $this->getLoggedUserId () . "\", \"" . $this->getCurrentDateTime () . "\", \"" . $this->getLoggedUserId () . "\", \"y\")";
			$sqlQuery = $this->processQuery ( $sqlQuery, $counter );
			if ($sqlQuery) {
				$this->logOperation ( $counter, "New MasterSalary Added" );
				return $counter;
			}
		}
		return false;
	}
	
	public function commitMasterSalaryDetailsUpdate($masterSalaryId) {
		if ($this->sqlConstructQuery == "")
			return false;
		
		return $this->commitUpdate($masterSalaryId);
	}
	
	public function dropMasterSalaryDetails($masterSalaryId) {
		if ($this->dropTableId ( $masterSalaryId, false )) {
			$this->logOperation ( $masterSalaryId, "Dropped" );
			return true;
		}
		return false;
	}
	
	public function activateMasterSalaryDetails($masterSalaryId) {
		if ($this->activateTableId ( $masterSalaryId )) {
			$this->logOperation ( $masterSalaryId, "Activated" );
			return true;
		}
		return false;
	}
	
	public function getEmployeeMasterSalaryAllowanceAmount($employeeId, $allowanceId) {
		$sqlQuery = "SELECT amount
				    	FROM accounts_mastersalary_details
				    	WHERE employee_id = \"$employeeId\"
					    	AND allowance_id = \"$allowanceId\"
					    	AND active = \"y\" ";
		
		$sqlQuery = $this->processArray ( $sqlQuery );
		if ($sqlQuery [0] != '')
			return $sqlQuery [0];
		return false;
	}
	
	// functions related to the calculation and manipulation of amount for a
	// given allowance head
	public function getAccountSum($employeeId, $allowanceId) {		
		$dependentIds = $this->getAllowanceComputationalIds ( $allowanceId, 1 );
		
		$total = 0;
		foreach ( $dependentIds as $dependentId ) {
			$details = $this->getTableIdDetails ( $dependentId );
			if ($details ['dependent_id'] == '') { // this is a absolute sum type allowance
				if ($details ['type'] == 'c')
					$total += $details ['magnitude'];
				else
					$total -= $details ['magnitude'];
			} else {
				if ($details ['type'] == 'c')
					$total += $details ['magnitude'] * .01 * abs ( $this->getAccountSum ( $employeeId, $details ['dependent_id'] ) );
				else
					$total -= $details ['magnitude'] * .01 * abs ( $this->getAccountSum ( $employeeId, $details ['dependent_id'] ) );
			}
		}
		if($total == 0)
			$total = $this->getEmployeeMasterSalaryAllowanceAmount ( $employeeId, $allowanceId );		
		$total = $total ? $total : 0;
		return $total;
	}
	
	public function getAllowanceDependentAllowanceIds($allowanceId) {
		$this->_dependentIds = array ();
		array_push ( $this->_dependentIds, $allowanceId );
		$this->populateAllowanceDependentAllowanceIds ( $allowanceId );
		
		return $this->_dependentIds;
	}
	
	private function populateAllowanceDependentAllowanceIds($allowanceId) {
		$sqlQuery = "SELECT allowance_id FROM accounts_allowance_combination WHERE dependent_id = \"$allowanceId\" AND active = \"y\" ";
		$ids = $this->getDataArray ( $this->processQuery ( $sqlQuery ) );
		foreach ( $ids as $id ) {
			array_push ( $this->_dependentIds, $id );
			$this->populateAllowanceDependentAllowanceIds ( $id );
		}
	}
	
	public function getAllowanceNonOverRiddenDependentEmployeeIds($allowanceId) {
		$sqlQuery = "SELECT employee_id FROM accounts_mastersalary_details WHERE allowance_id = \"$allowanceId\" AND active = \"y\" AND over_ridden != \"y\" ";
		return $this->getDataArray ( $this->processQuery ( $sqlQuery ) );
	}
	
	public function updateBulkNonOverRiddenEmployeeAllowanceAmount($changeAllowanceId) {
		$allowanceIds = $this->getAllowanceDependentAllowanceIds ( $changeAllowanceId );
		foreach ( $allowanceIds as $allowanceId ) {
			$employeeIds = $this->getAllowanceNonOverRiddenDependentEmployeeIds ( $allowanceId );
			foreach ( $employeeIds as $employeeId ) {
				$accountSum = $this->getAccountSum ( $employeeId, $allowanceId );
				$this->setEmployeeMasterSalaryInfo ( $employeeId, $allowanceId, $accountSum, 'n' );
			}
		}
	}
	
	public function updateNonOverRiddenEmployeeAllowanceAmount($employeeId, $changeAllowanceId) {
		$allowanceIds = $this->getAllowanceDependentAllowanceIds ( $changeAllowanceId );
		foreach ( $allowanceIds as $allowanceId ) {
			if ($changeAllowanceId == $allowanceId)
				continue;
			$masterSalaryId = $this->getEmployeeMasterSalaryId($employeeId, $allowanceId, true);
			if($masterSalaryId){
				$accountSum = $this->getAccountSum ( $employeeId, $allowanceId );
				$this->setEmployeeMasterSalaryInfo ( $employeeId, $allowanceId, $accountSum, 'n' );
			}			
		}
	}
}

?>