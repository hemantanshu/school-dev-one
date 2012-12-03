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
require_once BASE_PATH . 'include/utility/class.employeeRegistration.php';

class Salary extends Accounts {
	private $_employeeRegistration;
	public function __construct() {
		parent::__construct ();
		$this->_employeeRegistration = new employeeRegistration();
	}
	
	public function checkEmployeeDetailsRecord($employeeId, $month, $active){
		if($active)
			$sqlQuery = "SELECT id FROM accounts_salary_employee_record WHERE employee_id = \"$employeeId\" AND month = \"$month\" AND active = \"y\" ";
		else
			$sqlQuery = "SELECT id FROM accounts_salary_employee_record WHERE employee_id = \"$employeeId\" AND month = \"$month\" AND active != \"y\" ";
		
		$sqlQuery = $this->processArray($sqlQuery);
		if($sqlQuery[0] != '')
			return $sqlQuery[0];
		return false;
	}
	
	
	public function setEmployeeDetailsRecord($employeeId, $month){
		$employeeDetailsId = $this->checkEmployeeDetailsRecord($employeeId, $month, false);
		if($employeeDetailsId){
			$registrationId = $this->_employeeRegistration->getEmployeeRegistrationId($employeeId);
			$details = $this->getTableIdDetails($registrationId);
			
			$employeeDetails = $this->getTableIdDetails($employeeDetailsId);
			
			$details['department_id'] == $employeeDetails['department_id'] ? '' : $this->updateTableParameter('department_id', $details['department_id']);
			$details['employee_type'] == $employeeDetails['employee_type'] ? '' : $this->updateTableParameter('employee_type', $details['employee_type']);
			$this->updateTableParameter('active', 'y');
			$this->commitEmployeeDetailsRecordUpdate($employeeDetailsId);
			
			return $employeeDetailsId;
		}else{
			$counter = $this->getCounter('accountsEmployeeRecord');
			
			$registrationId = $this->_employeeRegistration->getEmployeeRegistrationId($employeeId);
			$details = $this->getTableIdDetails($registrationId);
			
			$sqlQuery = "INSERT INTO accounts_salary_employee_record
				    		(id, employee_id, department_id, employee_type, month, last_update_date, last_updated_by, creation_date, created_by, active)
				    		VALUES (\"$counter\", \"$employeeId\", \"".$details['department_id']."\", \"".$details['employee_type']."\", \"$month\", \"" . $this->getCurrentDateTime () . "\", \"" . $this->getLoggedUserId () . "\", \"" . $this->getCurrentDateTime () . "\", \"" . $this->getLoggedUserId () . "\", \"y\")";
			
			if ($this->processQuery ( $sqlQuery, $counter )) {
				$this->logOperation ( $counter, "New Record" );
				return $counter;
			}
		}
	}
	
	public function commitEmployeeDetailsRecordUpdate($employeeDetailsId) {
		if ($this->sqlConstructQuery == "")
			return false;
	
		return $this->commitUpdate($employeeDetailsId);
	}
	
	public function dropEmployeeDetailsRecord($employeeDetailsId) {
		if ($this->dropTableId ( $employeeDetailsId, false )) {
			$this->logOperation ( $employeeDetailsId, "Dropped" );
			return true;
		}
		return false;
	}
	
	public function activateEmployeeDetailsRecord($employeeDetailsId) {
		if ($this->activateTableId ( $employeeDetailsId )) {
			$this->logOperation ( $employeeDetailsId, "Activated" );
			return true;
		}
		return false;
	}
	
	//functions related to the salary processing info	
    public function checkSalaryProcessRecord($employeeId, $month, $active){
        if($active){
        	if($active === 'all')
            	$sqlQuery = "SELECT id FROM accounts_salary_process_record WHERE employee_id = \"$employeeId\" AND month = \"$month\" ";
        	else
        		$sqlQuery = "SELECT id FROM accounts_salary_process_record WHERE employee_id = \"$employeeId\" AND month = \"$month\" AND active = \"y\" ";
        }
        else
            $sqlQuery = "SELECT id FROM accounts_salary_process_record WHERE employee_id = \"$employeeId\" AND month = \"$month\" AND active != \"y\" ";

        
        return $this->getDataArray($this->processQuery($sqlQuery));
    }


    public function setSalaryProcessRecord($employeeId, $days, $paymentMode, $month){
        $counter = $this->getCounter('accountsSalaryProcessRecord');

        $sqlQuery = "INSERT INTO accounts_salary_process_record
				    		(id, employee_id, salary_days, payment_mode, month, last_update_date, last_updated_by, creation_date, created_by, active)
				    		VALUES (\"$counter\", \"$employeeId\", \"$days\", \"$paymentMode\", \"$month\", \"" . $this->getCurrentDateTime () . "\", \"" . $this->getLoggedUserId () . "\", \"" . $this->getCurrentDateTime () . "\", \"" . $this->getLoggedUserId () . "\", \"y\")";

        if ($this->processQuery ( $sqlQuery, $counter )) {
            $this->logOperation ( $counter, "New Record" );
            return $counter;
        }
    }

    public function commitSalaryProcessRecordUpdate($employeeDetailsId) {
        if ($this->sqlConstructQuery == "")
            return false;

        return $this->commitUpdate($employeeDetailsId);
    }

    public function dropSalaryProcessRecord($employeeDetailsId) {
        if ($this->dropTableId ( $employeeDetailsId, false )) {
            $this->logOperation ( $employeeDetailsId, "Dropped" );
            return true;
        }
        return false;
    }

    public function activateSalaryProcessRecord($employeeDetailsId) {
        if ($this->activateTableId ( $employeeDetailsId )) {
            $this->logOperation ( $employeeDetailsId, "Activated" );
            return true;
        }
        return false;
    }
    
    //functions related to salaryProcessing
    public function setSalaryRecord($employeeId, $accountHeadId, $allowanceId, $amount, $month){
        $counter = $this->getCounter('salaryRecord');

        $sqlQuery = "INSERT INTO accounts_salary_record
				    		(id, employee_id, accounthead_id, allowance_id, amount, month, last_update_date, last_updated_by, creation_date, created_by, active)
				    		VALUES (\"$counter\", \"$employeeId\", \"$accountHeadId\", \"$allowanceId\", \"$amount\", \"$month\", \"" . $this->getCurrentDateTime () . "\", \"" . $this->getLoggedUserId () . "\", \"" . $this->getCurrentDateTime () . "\", \"" . $this->getLoggedUserId () . "\", \"y\")";

        if ($this->processQuery ( $sqlQuery, $counter )) {
            $this->logOperation ( $counter, "New Record" );
            return $counter;
        }
    }    

    public function dropSalaryRecord($salaryId) {
        if ($this->dropTableId ( $salaryId, false )) {
            $this->logOperation ( $salaryId, "Dropped" );
            return true;
        }
        return false;
    }
    
    public function getEmployeeAllowanceSalaryAmount($employeeId, $allowanceId, $month){
    	$sqlQuery = "SELECT SUM(amount)
				    	FROM accounts_salary_record
				    	WHERE employee_id = \"$employeeId\"
					    	AND month = \"$month\"
					    	AND allowance_id = \"$allowanceId\"
					    	AND active = \"y\" ";
    	$sqlQuery = $this->processArray($sqlQuery);
    	return $sqlQuery[0];
    }
    
    public function getEmployeeSalaryAmount($employeeId, $month){
    	$sqlQuery = "SELECT SUM(amount) 
    					FROM accounts_salary_record 
    					WHERE employee_id = \"$employeeId\" 
    						AND month = \"$month\" 
    						AND active = \"y\" ";
    	$sqlQuery = $this->processArray($sqlQuery);
    	return $sqlQuery[0];
    }
    
    public function getSalaryIds($employeeId, $month, $type){
    	if($type){
            if($type == 'all')
                $sqlQuery = "SELECT a.id
    					FROM accounts_salary_record a,
    						accounts_salary_accounthead_record b
    					WHERE a.employee_id = \"$employeeId\"
    						AND a.month = \"$month\"
    						AND a.accounthead_id = b.accounthead_id
    						AND b.month = \"$month\"
    						AND a.active = \"y\"
    					ORDER BY b.display_order ASC ";
            else
                $sqlQuery = "SELECT a.id
    					FROM accounts_salary_record a,
    						accounts_salary_accounthead_record b
    					WHERE a.employee_id = \"$employeeId\"
    						AND a.month = \"$month\"
    						AND a.amount > 0
    						AND a.accounthead_id = b.accounthead_id
    						AND b.month = \"$month\"
    						AND a.active = \"y\"
    					ORDER BY b.display_order ASC ";
        }else
            $sqlQuery = "SELECT a.id
    					FROM accounts_salary_record a,
    						accounts_salary_accounthead_record b
    					WHERE a.employee_id = \"$employeeId\"
    						AND a.month = \"$month\"
    						AND a.amount < 0
    						AND a.accounthead_id = b.accounthead_id
    						AND b.month = \"$month\"
    						AND a.active = \"y\"
    					ORDER BY b.display_order ASC ";
        
        return $this->getDataArray($this->processQuery($sqlQuery));
    }

    //functions related to saving the accounthead
    public function preserveAccountHead($month){
    	$sqlQuery = "SELECT id, accounthead_name, display_order FROM accounts_accounthead_details WHERE active = \"y\" ";
    	$query = $this->processQuery($sqlQuery);
    	while($result = mysql_fetch_array($query)){
    		if($this->checkAccountHeadRecord($result[0], $month))
    			continue;
    		$counter = $this->getCounter('accountHeadPreserve');
            $sqlQuery = "INSERT INTO accounts_salary_accounthead_record
				    		(id, accounthead_id, accounthead_name, display_order, month, last_update_date, last_updated_by, creation_date, created_by, active)
				    		VALUES (\"$counter\", \"".$result[0]."\", \"".$result[1]."\", \"".$result[2]."\", \"$month\", \"" . $this->getCurrentDateTime () . "\", \"" . $this->getLoggedUserId () . "\", \"" . $this->getCurrentDateTime () . "\", \"" . $this->getLoggedUserId () . "\", \"y\")";
            if($this->processQuery($sqlQuery, $counter))
            	$this->logOperation($counter, 'New Entry');
    	}
    }

    public function checkAccountHeadRecord($accountHeadId, $month){
    	$sqlQuery = "SELECT id FROM accounts_salary_accounthead_record WHERE accounthead_id = \"$accountHeadId\" AND month = \"$month\" ";
    	$sqlQuery = $this->processArray($sqlQuery);
    	if($sqlQuery[0] != "")
    		return $sqlQuery[0];
    	return false;
    }
    
    //functions related to the employee whose salary has to be processed
    public function getEmployeeIds4SalaryProcessing($month){
    	$sqlQuery = "SELECT a.employee_id 
    					FROM utl_employee_registration a 
    					WHERE a.active = \"y\"
    						AND NOT EXISTS (SELECT 1 
    											FROM accounts_salary_record b 
    											WHERE a.employee_id = b.employee_id
    												AND b.month = \"$month\"
    												AND b.active = \"y\")
    						AND NOT EXISTS (SELECT 1 
    											FROM accounts_blocksalary_record c
    											WHERE a.employee_id = c.employee_id
    												AND c.start_month <= \"$month\"
    												AND (c.end_month = \"0\" 
    													OR c.end_month >= \"$month\" )
    												AND c.active = \"y\" )
    					ORDER BY employee_code ASC ";
    	return $this->getDataArray($this->processQuery($sqlQuery));
    }
    
    public function getEmployeeIds4SalaryProcessed($month){
    	$sqlQuery = "SELECT DISTINCT employee_id FROM accounts_salary_record WHERE month = \"$month\" AND active = \"y\" ";
    	return $this->getDataArray($this->processQuery($sqlQuery));
    }

    public function setEmployeeSalaryRollBack($employeeId, $month){
    	$counter = $this->getCounter('accountSalaryRollback');
    	
    	$sqlQuery = "INSERT INTO accounts_salary_rollback
				    	(id, employee_id, month, last_update_date, last_updated_by, creation_date, created_by, active)
				    	VALUES (\"$counter\", \"$employeeId\", \"$month\", \"" . $this->getCurrentDateTime () . "\", \"" . $this->getLoggedUserId () . "\", \"" . $this->getCurrentDateTime () . "\", \"" . $this->getLoggedUserId () . "\", \"y\")";
    	
    	if ($this->processQuery ( $sqlQuery, $counter )) {
    		$this->logOperation ( $counter, "New Record" );
    		return $counter;
    	}
    	return false;
    }
    
}

?>