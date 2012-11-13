<?php
/**
 * This class will hold the functionalities regarding the user designation ranks
 * This class extends the general class
 * @author Hemant Kumar Sah
 * @category Global
 * @license Support-Gurukul
 * @version 1.0.0
 */
require_once 'config.php';
require_once BASE_PATH.'include/global/class.general.php';

class Employment extends general {
    
    public function __construct() {
        parent::__construct();
    }
    public function setUserEmploymentDetails($userId, $organizationId, $startDate, $endDate, $positionId){
    	$counter = $this->getCounter('employment');
    	$sqlQuery = "INSERT 
    					INTO hrms_employment_history
    					(id, user_id, organization_id, start_date, end_date, position_id, last_update_date, last_updated_by, creation_date, created_by, active) 
    					VALUES (\"$counter\", \"$userId\", \"$organizationId\", \"$startDate\", \"$endDate\", \"$positionId\", \"" . $this->getCurrentDateTime() . "\", \"" . $this->getLoggedUserId() . "\", \"" . $this->getCurrentDateTime() . "\", \"" . $this->getLoggedUserId() . "\", \"y\") ";
    	if($this->processQuery($sqlQuery, $counter)){
    		$this->logOperation($userId, "The new employment history has been added");
    		return $counter;
    	}
    	return false;
    }
    
    public function getUserEmploymentHistoryId($userId, $active){
    	if($active){
    		if($active === 'all')
    			$sqlQuery = "SELECT id 
    							FROM hrms_employment_history 
    							WHERE user_id = \"$userId\" 
    							ORDER BY start_date DESC";
    		else 
    			$sqlQuery = "SELECT id 
    							FROM hrms_employment_history 
    							WHERE user_id = \"$userId\"
    								AND active = \"y\" 
    							ORDER BY start_date DESC";
    	}else 
    		$sqlQuery = "SELECT id
				    		FROM hrms_employment_history
				    		WHERE user_id = \"$userId\"
				    			AND active != \"y\"
				    		ORDER BY start_date DESC";
    	
    	return $this->getDataArray($this->processQuery($sqlQuery));
    }
    
    public function getEmploymentIdDetails($employmentId){
    	return $this->getTableIdDetails($employmentId);
    }
       
    public function commitEmploymentDetailsUpdate($employmentId){
    	if ($this->sqlConstructQuery == ""){
    		return $employmentId;
    	}    		
    	
    	return $this->commitUpdate($employmentId);
    }

 	public function dropEmploymentDetails($employmentId) {
        if($this->dropTableId($employmentId, false)){
            $this->logOperation($employmentId, "The User Employment Details Has Been Dropped");
            return true;
        }
        return false;
    }
    
    

    public function activateEmploymentDetails($employmentId) {
        if($this->activateTableId($employmentId)){
            $this->logOperation($employmentId, "The User Employment Details Has Been Activated");
            return true;
        }
        return false;
    }

}
?>