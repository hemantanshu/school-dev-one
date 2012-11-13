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

class Allowance extends general {
	
	public function __construct() {
		parent::__construct ();
	}
	
	public function getAllowanceIds($active) {
		if ($active) {
			if ($active === 'all')
				$sqlQuery = "SELECT id 
                                FROM accounts_allowance_details 
                                ORDER BY allowance_name ASC";
			else
				$sqlQuery = "SELECT id 
                                FROM accounts_allowance_details
                                WHERE active = \"y\"
                                ORDER BY allowance_name ASC";
		} else
			$sqlQuery = "SELECT id 
                            FROM accounts_allowance_details
                            WHERE active != \"y\"
                            ORDER BY allowance_name ASC";
		
		return $this->getDataArray ( $this->processQuery ( $sqlQuery ) );
	}
	
	public function getAllowanceNameSearchIds($hint, $active) {
		if ($active) {
			if ($active === 'all')
				$sqlQuery = "SELECT id 
                                FROM accounts_allowance_details 
                                WHERE allowance_name LIKE \"%$hint%\"
                                ORDER BY allowance_name ASC";
			else
				$sqlQuery = "SELECT id 
                                FROM accounts_allowance_details
                                WHERE active = \"y\"
                                AND allowance_name LIKE \"%$hint%\"
                                ORDER BY allowance_name ASC";
		} else
			$sqlQuery = "SELECT id 
                            FROM accounts_allowance_details
                            WHERE active != \"y\"
                            AND allowance_name LIKE \"%$hint%\"
                            ORDER BY allowance_name ASC";
		
		return $this->getDataArray ( $this->processQuery ( $sqlQuery ) );
	}
	
	public function getAllowanceAccountHeadId($allowanceId){
		$sqlQuery = "SELECT accounthead_id FROM accounts_allowance_details WHERE id = \"$allowanceId\" ";
		$sqlQuery = $this->processArray($sqlQuery);
		if($sqlQuery[0] != '')
			return $sqlQuery[0];
		return false;
	}
	
	public function getAllowanceName($allowanceId) {
		$sqlQuery = "SELECT allowance_name FROM accounts_allowance_details WHERE id = \"$allowanceId\" ";
		$sqlQuery = $this->processArray ( $sqlQuery );
		
		if($sqlQuery[0] != '')
			return $sqlQuery[0];
		return false;
	}
	
	public function isAllowanceFractionEnabled($allowanceId){
		$sqlQuery = "SELECT fraction FROM accounts_allowance_details WHERE id = \"$allowanceId\" ";
		$sqlQuery = $this->processArray ( $sqlQuery );
		
		if($sqlQuery[0] != '')
			if($sqlQuery[0] == 'y')
				return true;
		return false;
	}
	
	public function dropAllowanceDetails($allowanceId) {
		if ($this->dropTableId ( $allowanceId, false )) {
			$this->logOperation ( $allowanceId, "Dropped" );
			return true;
		}
		return false;
	}
	
	public function activateAllowanceDetails($allowanceId) {
		if ($this->activateTableId ( $allowanceId )) {
			$this->logOperation ( $allowanceId, "Activated" );
			return true;
		}
		return false;
	}
	
	public function setAllowanceDetails($allowanceName, $accountHeadId, $allowUpdate, $allowRounding, $allowFraction, $contributoryFund) {
		$counter = $this->getCounter ( 'allowanceDetails' );
		$sqlQuery = "INSERT INTO accounts_allowance_details 
                            (id, allowance_name, accounthead_id, allow_update, round_off, contribution, fraction, last_update_date, last_updated_by, creation_date, created_by, active) 
                            VALUES (\"$counter\", \"$allowanceName\", \"$accountHeadId\", \"$allowUpdate\", \"$allowRounding\", \"$contributoryFund\", \"$allowFraction\", \"" . $this->getCurrentDateTime () . "\", \"" . $this->getLoggedUserId () . "\", \"" . $this->getCurrentDateTime () . "\", \"" . $this->getLoggedUserId () . "\", \"y\")";
		if ($this->processQuery ( $sqlQuery, $counter )) {
			$this->logOperation ( $counter, "new record" );
			return $counter;
		}
		return false;
	}
	
	public function commitAllowanceDetailsUpdate($allowanceId) {
		if ($this->sqlConstructQuery == "")
			return false;
		
		return $this->commitUpdate($allowanceId);
	}
	
	// functions related to the allowance computational formulae
	public function setAllowanceComputationalFormula($allowanceId, $dependentId, $value, $type) {
		$counter = $this->getCounter ( 'allowanceCombination' );
		$sqlQuery = "INSERT INTO accounts_allowance_combination
    					(id, allowance_id, magnitude, dependent_id, type, last_update_date, last_updated_by, creation_date, created_by, active)
    					VALUES (\"$counter\", \"$allowanceId\", \"$value\", \"$dependentId\", \"$type\", \"" . $this->getCurrentDateTime () . "\", \"" . $this->getLoggedUserId () . "\", \"" . $this->getCurrentDateTime () . "\", \"" . $this->getLoggedUserId () . "\", \"y\")";
		if ($this->processQuery ( $sqlQuery, $counter )) {
			$this->logOperation ( $counter, "new record" );
			return $counter;
		}
		return false;
	}
	
	public function getAllowanceComputationalIds($allowanceId, $active) {
		if ($active) {
			if ($active === 'all')
				$sqlQuery = "SELECT id
				    			FROM accounts_allowance_combination
				    			WHERE allowance_id = \"$allowanceId\"
				    			ORDER BY type ASC";
			else
				$sqlQuery = "SELECT id
				    			FROM accounts_allowance_combination
				    			WHERE allowance_id = \"$allowanceId\"
				    				AND active = \"y\"
				    			ORDER BY type ASC";	
		} else
			$sqlQuery = "SELECT id
				    		FROM accounts_allowance_combination
				    		WHERE allowance_id = \"$allowanceId\"
				    			AND active != \"y\"
				    		ORDER BY type ASC";
					
		return $this->getDataArray ( $this->processQuery ( $sqlQuery ) );
	}	
	
	public function dropAllowanceCombinationDetails($combinationId) {
		if ($this->dropTableId ( $combinationId, false )) {
			$this->logOperation ( $combinationId, "Dropped" );
			return true;
		}
		return false;
	}
	
	public function activateAllowanceCombinationDetails($combinationId) {
		if ($this->activateTableId ( $combinationId )) {
			$this->logOperation ( $combinationId, "Activated" );
			return true;
		}
		return false;
	}

    public function commitAllowanceCombinationDetailsUpdate($combinationId) {
        if ($this->sqlConstructQuery == "")
            return false;

        return $this->commitUpdate($combinationId);
        return false;
    }

}

?>