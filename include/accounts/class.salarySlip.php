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
require_once BASE_PATH . 'include/accounts/class.salary.php';

class SalarySlip extends Salary {
	public function __construct() {
		parent::__construct ();
	}
	
	public function checkSalaryProcessInfo($month){
		$sqlQuery = "SELECT COUNT(id) FROM accounts_salary_process_record WHERE month = \"$month\" AND active = \"y\" ";
		return $this->processSingleElementQuery($sqlQuery);
	}
	
	public function getEmployeeIds4Department($departmentId, $month){
		$sqlQuery = "SELECT employee_id 
						FROM accounts_salary_employee_record 
						WHERE department_id = \"$departmentId\" 
							AND month = \"$month\" 
							AND active = \"y\" ";
		return $this->getDataArray($this->processQuery($sqlQuery));
	}
	
	public function getEmployeeIds4EmployeeType($employeeType, $month){
		$sqlQuery = "SELECT employee_id
						FROM accounts_salary_employee_record
						WHERE employee_type = \"$employeeType\"
							AND month = \"$month\"
							AND active = \"y\" ";
		return $this->getDataArray($this->processQuery($sqlQuery));
	}
	
	public function getSalaryProcessId4EmployeeId($employeeId, $month, $active){
		if($active){
			if($active === 'all')
				$sqlQuery = "SELECT id FROM accounts_salary_employee_record WHERE employee_id = \"$employeeId\" AND month = \"$month\" ";
			else
				$sqlQuery = "SELECT id FROM accounts_salary_employee_record WHERE employee_id = \"$employeeId\" AND month = \"$month\" AND active = \"y\" ";
		}else
			$sqlQuery = "SELECT id FROM accounts_salary_employee_record WHERE employee_id = \"$employeeId\" AND month = \"$month\" AND active != \"y\" ";
		
		
		return $this->getDataArray($this->processQuery($sqlQuery));
	}
	
	public function getEmployeeIds($month){
		$sqlQuery = "SELECT employee_id
						FROM accounts_salary_employee_record
						WHERE month = \"$month\"
							AND active = \"y\" ";
		return $this->getDataArray($this->processQuery($sqlQuery));
	}
	
	public function getEmployeeSalaryDetails($employeeId, $startMonth, $endMonth, $type){
		if($type)
			$sqlQuery = "SELECT accounthead_id, SUM(amount)
							FROM accounts_salary_record a,
								accounts_accounthead_details b
							WHERE employee_id = \"$employeeId\"
								AND a.month BETWEEN \"$startMonth\" AND \"$endMonth\"
								AND a.active = \"y\"
								AND a.accounthead_id = b.id
							GROUP BY accounthead_id
								HAVING SUM(amount) > 0
							ORDER BY b.display_order ASC ";
		else
			$sqlQuery = "SELECT accounthead_id, SUM(amount)
							FROM accounts_salary_record a,
								accounts_accounthead_details b
							WHERE employee_id = \"$employeeId\"
								AND a.month BETWEEN \"$startMonth\" AND \"$endMonth\"
								AND a.active = \"y\"
								AND a.accounthead_id = b.id
							GROUP BY accounthead_id
								HAVING SUM(amount) < 0
							ORDER BY b.display_order ASC ";
		
		return $this->getDataArray($this->processQuery($sqlQuery), 2);
	}
	
	public function getTotalAccountHeadListing4EmployeeType($employeeType, $month){
		$sqlQuery = "SELECT DISTINCT accounthead_id 
							FROM accounts_salary_record a, 
								accounts_salary_employee_record b,
								accounts_accounthead_details c 
							WHERE a.month = \"$month\"
								AND b.month = \"$month\"
								AND a.employee_id = b.employee_id
								AND b.employee_type = \"$employeeType\"
								AND a.accounthead_id = c.id
								AND a.active = \"y\"
								AND b.active = \"y\" 
							ORDER BY account_type ASC, display_order ASC ";
		return $this->getDataArray($this->processQuery($sqlQuery));
	}
	
	public function getEmployeeSalaryInfo4AccountHead($employeeId, $month, $accountHeadId){
		$sqlQuery = "SELECT SUM(amount) FROM accounts_salary_record WHERE employee_id = \"$employeeId\" AND month = \"$month\" AND accounthead_id = \"$accountHeadId\" AND active = \"y\" ";
		return $this->processSingleElementQuery($sqlQuery);
	}
	
	
}

?>