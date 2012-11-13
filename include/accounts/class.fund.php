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
require_once BASE_PATH.'include/global/class.general.php';
require_once BASE_PATH.'include/accounts/class.salary.php';

class Fund extends general {
	private $_salaryClass;
    public function __construct() {
        parent::__construct();
        $this->_salaryClass = new Salary();
    }

    // functions related to the fund contribution computational formulae
    public function setFundComputationalFormula($allowanceId, $dependentId, $value, $type) {
        $counter = $this->getCounter ( 'fundCombination' );
        $sqlQuery = "INSERT INTO accounts_employeefund_combination
    					(id, allowance_id, magnitude, dependent_id, type, last_update_date, last_updated_by, creation_date, created_by, active)
    					VALUES (\"$counter\", \"$allowanceId\", \"$value\", \"$dependentId\", \"$type\", \"" . $this->getCurrentDateTime () . "\", \"" . $this->getLoggedUserId () . "\", \"" . $this->getCurrentDateTime () . "\", \"" . $this->getLoggedUserId () . "\", \"y\")";
        if ($this->processQuery ( $sqlQuery, $counter )) {
            $this->logOperation ( $counter, "new record" );
            return $counter;
        }
        return false;
    }
    
    public function getFundAllowanceIds(){
    	$sqlQuery = "SELECT id, allowance_name
    					FROM accounts_allowance_details 
    					WHERE contribution = \"y\" 
    						AND active = \"y\" ";
    	return $this->getDataArray($this->processQuery($sqlQuery), 2);    	
    }

    public function getFundComputationalIds($allowanceId, $active) {
        if ($active) {
            if ($active === 'all')
                $sqlQuery = "SELECT id
				    			FROM accounts_employeefund_combination
				    			WHERE allowance_id = \"$allowanceId\"
				    			ORDER BY type ASC";
            else
                $sqlQuery = "SELECT id
				    			FROM accounts_employeefund_combination
				    			WHERE allowance_id = \"$allowanceId\"
				    				AND active = \"y\"
				    			ORDER BY type ASC";
        } else
            $sqlQuery = "SELECT id
				    		FROM accounts_employeefund_combination
				    		WHERE allowance_id = \"$allowanceId\"
				    			AND active != \"y\"
				    		ORDER BY type ASC";

        return $this->getDataArray ( $this->processQuery ( $sqlQuery ) );
    }
    
    public function getFundAmount4EmployeeAllowanceMonthType($employeeId, $startMonth, $endMonth, $allowanceId, $type){
    	$sqlQuery = "SELECT SUM(amount) 
    					FROM accounts_employeefund_installment 
    					WHERE employee_id = \"$employeeId\" 
    						AND month BETWEEN \"$startMonth\" AND \"$endMonth\" 
    						AND allowance_id = \"$allowanceId\" 
    						AND type = \"$type\" 
    						AND active = \"y\" ";
    	$sqlQuery = $this->processArray($sqlQuery);
    	
    	return $sqlQuery[0];    	
    }
    
    public function getFundAmount4EmployeeAllowanceMonth($employeeId, $startMonth, $endMonth, $allowanceId){
    	$sqlQuery = "SELECT SUM(amount)
				    	FROM accounts_employeefund_installment
				    	WHERE employee_id = \"$employeeId\"
					    	AND month BETWEEN \"$startMonth\" AND \"$endMonth\"
					    	AND allowance_id = \"$allowanceId\"
					    	AND active = \"y\" ";
    	$sqlQuery = $this->processArray($sqlQuery);
    	 
    	return $sqlQuery[0];
    }
    
    public function getInstituteContributionFund4EmployeeMonth($employeeId, $month){
    	$sqlQuery = "SELECT SUM(amount)
				    	FROM accounts_employeefund_installment
				    	WHERE employee_id = \"$employeeId\"
					    	AND month = \"$month\"
					    	AND type = \"LRESER21\"
					    	AND active = \"y\" ";
    	$sqlQuery = $this->processArray($sqlQuery);    	 
    	return $sqlQuery[0];
    }

    public function dropFundCombinationDetails($combinationId) {
        if ($this->dropTableId ( $combinationId, false )) {
            $this->logOperation ( $combinationId, "Dropped" );
            return true;
        }
        return false;
    }

    public function activateFundCombinationDetails($combinationId) {
        if ($this->activateTableId ( $combinationId )) {
            $this->logOperation ( $combinationId, "Activated" );
            return true;
        }
        return false;
    }

    public function commitFundCombinationDetailsUpdate($combinationId) {
        if ($this->sqlConstructQuery == "")
            return false;

        return $this->commitUpdate($combinationId);
    }
    
    public function getEmployeeFundComputationalAmount($employeeId, $fundId, $month){
    	$combinationIds = $this->getFundComputationalIds($fundId, 1);
    	$total = 0;
    	foreach($combinationIds as $combinationId){
    		$details = $this->getTableIdDetails($combinationId);
    		if ($details ['dependent_id'] == '') { // this is a absolute sum type
    			// allowance
    			if ($details ['type'] == 'c')
    				$total += $details ['magnitude'];
    			else
    				$total -= $details ['magnitude'];
    		} else {
    			if ($details ['type'] == 'c')
    				$total += $details ['magnitude'] * .01 * abs ( $this->_salaryClass->getEmployeeAllowanceSalaryAmount ( $employeeId, $details ['dependent_id'], $month ) );
    			else
    				$total -= $details ['magnitude'] * .01 * abs ( $this->_salaryClass->getEmployeeAllowanceSalaryAmount ( $employeeId, $details ['dependent_id'], $month ) );
    		}
    	}
    	return $total;
    }

    public function processEmployeeFund($employeeId, $month){
    	$fundAllowanceIds = $this->getFundAllowanceIds();
    	foreach($fundAllowanceIds as $fundAllowanceId){
    		//inserting the salary amount in the fund
    		$salaryAmount = $this->_salaryClass->getEmployeeAllowanceSalaryAmount($employeeId, $fundAllowanceId[0], $month);
    		if($salaryAmount != 0){
    			//inserting the employee contribution
    			$this->setFundInstallmentAmount($employeeId, $fundAllowanceId[0], abs($salaryAmount), 'LRESER20', $month);
    			
    			//inserting the institute contribution
    			$amount = $this->getEmployeeFundComputationalAmount($employeeId, $fundAllowanceId[0], $month);
    			if($amount != 0){
    				$contributionAmount = $amount > $salaryAmount ? $salaryAmount : $amount;
    				$this->setFundInstallmentAmount($employeeId, $fundAllowanceId[0], abs($contributionAmount), 'LRESER21', $month);
    			}    			    			
    		}
    	}
    	return true;
    }    
    //functions related to the insertion of fund
    public function getEmployeeFundInstallmentIds($employeeId, $allowanceId, $type, $month, $active){
    	if($active){
    		if($active === 'all')
    			$sqlQuery = "SELECT id 
    							FROM accounts_employeefund_installment 
    							WHERE employee_id = \"$employeeId\"
    								AND allowance_id = \"$allowanceId\" 
    								AND type = \"$type\"
    								AND month = \"$month\" ";
    		else
    			$sqlQuery = "SELECT id
				    			FROM accounts_employeefund_installment
				    			WHERE employee_id = \"$employeeId\"
					    			AND allowance_id = \"$allowanceId\"
					    			AND type = \"$type\"
					    			AND month = \"$month\"
	    							AND active = \"y\" ";
    	}else 
    		$sqlQuery = "SELECT id
				    		FROM accounts_employeefund_installment
				    		WHERE employee_id = \"$employeeId\"
					    		AND allowance_id = \"$allowanceId\"
					    		AND type = \"$type\"
					    		AND month = \"$month\"
					    		AND active = \"y\" ";
    	return $this->getDataArray($this->processQuery($sqlQuery));
    }
    
    
    public function setFundInstallmentAmount($employeeId, $allowanceId, $amount, $type, $month){
        $counter = $this->getCounter ( 'fundInstallment' );
        $sqlQuery = "INSERT INTO accounts_employeefund_installment
    					(id, employee_id, allowance_id, amount, month, type, last_update_date, last_updated_by, creation_date, created_by, active)
    					VALUES (\"$counter\", \"$employeeId\", \"$allowanceId\", \"$amount\", \"$month\", \"$type\", \"" . $this->getCurrentDateTime () . "\", \"" . $this->getLoggedUserId () . "\", \"" . $this->getCurrentDateTime () . "\", \"" . $this->getLoggedUserId () . "\", \"y\")";
        if ($this->processQuery ( $sqlQuery, $counter )) {
            $this->logOperation ( $counter, "new record" );
            return $counter;
        }
        return false;
    }

    public function dropFundInstallmentAmount($fundInstallmentId) {
        if ($this->dropTableId ( $fundInstallmentId, false )) {
            $this->logOperation ( $fundInstallmentId, "Dropped" );
            return true;
        }
        return false;
    }

    public function activateFundInstallmentAmount($fundInstallmentId) {
        if ($this->activateTableId ( $fundInstallmentId )) {
            $this->logOperation ( $fundInstallmentId, "Activated" );
            return true;
        }
        return false;
    }
    
    

}

?>