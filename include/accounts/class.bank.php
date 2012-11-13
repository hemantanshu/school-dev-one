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

class Bank extends general {

    public function __construct() {
        parent::__construct();
    }

    public function getBankIds($active) {
        if ($active) {
            if ($active === 'all')
                $sqlQuery = "SELECT id 
                                FROM accounts_bank_details 
                                ORDER BY bank_name ASC";
            else
                $sqlQuery = "SELECT id 
                                FROM accounts_bank_details
                                WHERE active = \"y\"
                                ORDER BY bank_name ASC";
        }else
            $sqlQuery = "SELECT id 
                            FROM accounts_bank_details
                            WHERE active != \"y\"
                            ORDER BY bank_name ASC";
        
        return $this->getDataArray($this->processQuery($sqlQuery));
    }

    public function getBankNameSearchIds($hint, $active){    	
        if ($active) {
            if ($active === 'all')
                $sqlQuery = "SELECT id 
                                FROM accounts_bank_details 
                                WHERE bank_name LIKE \"%$hint%\"
                                ORDER BY bank_name ASC";
            else
                $sqlQuery = "SELECT id 
                                FROM accounts_bank_details
                                WHERE active = \"y\"
                                AND bank_name LIKE \"%$hint%\"
                                ORDER BY bank_name ASC";
        }else
            $sqlQuery = "SELECT id 
                            FROM accounts_bank_details
                            WHERE active != \"y\"
                            AND bank_name LIKE \"%$hint%\"
                            ORDER BY bank_name ASC";      
          
        
        return $this->getDataArray($this->processQuery($sqlQuery));
    }
    
    public function getBankName($bankId){
        $sqlQuery = "SELECT CONCAT(bank_name, ' \, ', branch_name) FROM accounts_bank_details WHERE id = \"$bankId\" ";

        
        $sqlQuery = $this->processArray($sqlQuery);
        return $sqlQuery[0];
    }
    

    public function dropBankDetails($bankId) {
        if($this->dropTableId($bankId, false)){
            $this->logOperation($bankId, "Dropped");
            return true;
        }
        return false;
    }

    public function activateBankDetails($bankId) {
        if($this->activateTableId($bankId)){
            $this->logOperation($bankId, "Activated");
            return true;
        }
        return false;
    }

    public function setBankDetails($bankName, $branchName, $ifsc, $micr, $addressId) {
        $counter = $this->getCounter('bankName');
        $sqlQuery = "INSERT INTO accounts_bank_details 
                            (id, bank_name, branch_name, ifsc_code, micr_code, address_id, last_update_date, last_updated_by, creation_date, created_by, active) 
                            VALUES (\"$counter\", \"$bankName\", \"$branchName\", \"$ifsc\", \"$micr\", \"$addressId\", \"" . $this->getCurrentDateTime() . "\", \"" . $this->getLoggedUserId() . "\", \"" . $this->getCurrentDateTime() . "\", \"" . $this->getLoggedUserId() . "\", \"y\")";
        if ($this->processQuery($sqlQuery, $counter)) {
            $this->logOperation($counter, "new record");
            return $counter;
        }
        return false;
    }
    
    public function commitBankDetailsUpdate($bankId){
        if ($this->sqlConstructQuery == "")
            return false;

        return $this->commitUpdate($bankId);
    }
    
    //functions related to the bank account details
    public function setBankAccountDetails($userId, $accountNumber, $bankName, $accountType){
    	$counter = $this->getCounter('bankAccountDetails');
        $sqlQuery = "INSERT INTO accounts_bankaccount_details 
                            (id, employee_id, account_type, bank_id, account_number, last_update_date, last_updated_by, creation_date, created_by, active) 
                            VALUES (\"$counter\", \"$userId\", \"$accountType\", \"$bankName\", \"$accountNumber\", \"" . $this->getCurrentDateTime() . "\", \"" . $this->getLoggedUserId() . "\", \"" . $this->getCurrentDateTime() . "\", \"" . $this->getLoggedUserId() . "\", \"y\")";
        if ($this->processQuery($sqlQuery, $counter)) {
            $this->logOperation($counter, "new record");
            return $counter;
        }
        return false;
    	
    }
    
    public function commitBankAccountDetailsUpdate($bankId){
    	if ($this->sqlConstructQuery == "")
    		return false;
    
    	return $this->commitUpdate($bankId);
    }
    
    public function getEmployeeBankAccountId($employeeId, $accountType){
    	$sqlQuery = "SELECT id FROM accounts_bankaccount_details WHERE employee_id = \"$employeeId\" AND account_type = \"$accountType\" ";
    	$sqlQuery = $this->processArray($sqlQuery);
    	
    	if($sqlQuery[0] != '')
    		return $sqlQuery[0];
    	return false;
    }
    
    

}

?>