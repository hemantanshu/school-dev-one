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

class BlockSalary extends general {

    public function __construct() {
        parent::__construct();
    }

    public function getBlockSalaryIds($active) {
    	$currentMonth = date('Ym');
        if ($active) {
            if ($active === 'all')
                $sqlQuery = "SELECT id 
                                FROM accounts_blocksalary_record                			 
                                ORDER BY start_month ASC";
            else
                $sqlQuery = "SELECT id 
                                FROM accounts_blocksalary_record
                                WHERE active = \"y\"
                					AND start_month <= \"$currentMonth\"
                					AND (end_month = \"0\" OR end_month >= \"$currentMonth\")
                                ORDER BY start_month ASC";
        }else
            $sqlQuery = "SELECT id 
                                FROM accounts_blocksalary_record
                                WHERE active != \"y\"
                					OR start_month > \"$currentMonth\"
                					OR end_month < \"$currentMonth\"
                                ORDER BY start_month ASC";
        
        return $this->getDataArray($this->processQuery($sqlQuery));
    }
        
    public function dropBlockSalaryRecord($blockSalaryId) {
        if($this->dropTableId($blockSalaryId, false)){
            $this->logOperation($blockSalaryId, "Dropped");
            return true;
        }
        return false;
    }

    public function activateAccountHeadDetails($blockSalaryId) {
        if($this->activateTableId($blockSalaryId)){
            $this->logOperation($blockSalaryId, "Activated");
            return true;
        }
        return false;
    }

    public function setBlockSalaryDetails($employeeId, $startDate, $endDate, $type, $comments) {
        $counter = $this->getCounter('accountSalaryBlock');
        $sqlQuery = "INSERT INTO accounts_blocksalary_record 
                            (id, employee_id, start_date, end_date, type, comments, last_update_date, last_updated_by, creation_date, created_by, active) 
                            VALUES (\"$counter\", \"$employeeId\", \"$startDate\", \"$endDate\", \"$type\", \"$comments\", \"" . $this->getCurrentDateTime() . "\", \"" . $this->getLoggedUserId() . "\", \"" . $this->getCurrentDateTime() . "\", \"" . $this->getLoggedUserId() . "\", \"y\")";
        if ($this->processQuery($sqlQuery, $counter)) {
            $this->logOperation($counter, "new record");
            return $counter;
        }
        return false;
    }
    
    public function commitBlockSalaryDetails($blockSalaryId){
        if ($this->sqlConstructQuery == "")
            return $blockSalaryId;        
        
        return $this->commitUpdate($blockSalaryId);
    }

}

?>