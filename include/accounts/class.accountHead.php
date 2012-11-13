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

class AccountHead extends general {

    public function __construct() {
        parent::__construct();
    }

    public function getAccountHeadIds($active) {
        if ($active) {
            if ($active === 'all')
                $sqlQuery = "SELECT id 
                                FROM accounts_accounthead_details 
                                ORDER BY display_order ASC";
            else
                $sqlQuery = "SELECT id 
                                FROM accounts_accounthead_details
                                WHERE active = \"y\"
                                ORDER BY display_order ASC";
        }else
            $sqlQuery = "SELECT id 
                            FROM accounts_accounthead_details
                            WHERE active != \"y\"
                            ORDER BY display_order ASC";
        
        return $this->getDataArray($this->processQuery($sqlQuery));
    }

    public function getAccountHeadNameSearchIds($hint, $active){    	
        if ($active) {
            if ($active === 'all')
                $sqlQuery = "SELECT id 
                                FROM accounts_accounthead_details 
                                WHERE accounthead_name LIKE \"%$hint%\"
                                ORDER BY display_order ASC";
            else
                $sqlQuery = "SELECT id 
                                FROM accounts_accounthead_details
                                WHERE active = \"y\"
                                AND accounthead_name LIKE \"%$hint%\"
                                ORDER BY display_order ASC";
        }else
            $sqlQuery = "SELECT id 
                            FROM accounts_accounthead_details
                            WHERE active != \"y\"
                            AND accounthead_name LIKE \"%$hint%\"
                            ORDER BY display_order ASC";      
          
        
        return $this->getDataArray($this->processQuery($sqlQuery));
    }
    
    public function getAccountHeadName($accountHeadId){
        $sqlQuery = "SELECT accounthead_name FROM accounts_accounthead_details WHERE id = \"$accountHeadId\" ";
        $sqlQuery = $this->processArray($sqlQuery);
        return $sqlQuery[0];
    }
    
    public function getReservedAccountHeadName($accountHeadId, $month){
    	$sqlQuery = "SELECT accounthead_name FROM accounts_salary_accounthead_record WHERE accounthead_id = \"$accountHeadId\" AND month = \"$month\" AND active = \"y\"";
    	$sqlQuery = $this->processArray($sqlQuery);
    	return $sqlQuery[0];
    }
    

    public function dropAccountHeadDetails($accountHeadId) {
        if($this->dropTableId($accountHeadId, false)){
            $this->logOperation($accountHeadId, "Dropped");
            return true;
        }
        return false;
    }

    public function activateAccountHeadDetails($accountHeadId) {
        if($this->activateTableId($accountHeadId)){
            $this->logOperation($accountHeadId, "Activated");
            return true;
        }
        return false;
    }

    public function setAccountHeadDetails($accountHeadName, $type, $order) {
        $counter = $this->getCounter('accountHeadDetails');
        $sqlQuery = "INSERT INTO accounts_accounthead_details 
                            (id, accounthead_name, account_type, display_order, last_update_date, last_updated_by, creation_date, created_by, active) 
                            VALUES (\"$counter\", \"$accountHeadName\", \"$type\", \"$order\", \"" . $this->getCurrentDateTime() . "\", \"" . $this->getLoggedUserId() . "\", \"" . $this->getCurrentDateTime() . "\", \"" . $this->getLoggedUserId() . "\", \"y\")";
        if ($this->processQuery($sqlQuery, $counter)) {
            $this->logOperation($counter, "new record");
            return $counter;
        }
        return false;
    }
    
    public function commitAccountHeadDetailsUpdate($accountHeadId){
        if ($this->sqlConstructQuery == "")
            return false;

        $this->sqlConstructQuery .= ", last_update_date=\"" . $this->getCurrentDateTime() . "\", last_updated_by=\"" . $this->getLoggedUserId() . "\"";
        $sqlQuery = "UPDATE accounts_accounthead_details 
                                                    SET $this->sqlConstructQuery 
                                                    WHERE id = \"$accountHeadId\" ";

        $this->sqlConstructQuery = "";

        if ($this->processQuery($sqlQuery, $accountHeadId)) {
            $this->logOperation($accountHeadId, "Updated");            
            return true;
        }
        return false;
    }

}

?>