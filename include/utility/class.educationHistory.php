<?php
/**
 * This class will hold the functionalities regarding the different personal info of the user.
 * This class extends the general class
 * @author Hemant Kumar Sah
 * @category Global
 * @license Support-Gurukul
 * @version 1.0.0
 */
require_once 'config.php';
require_once BASE_PATH.'include/global/class.general.php';

class educationHistory extends general {
	
    public function __construct() {
        parent::__construct();
    }

    public function getUserEducationIds($userId, $type){
    	if($type){
    		if($type === 'all')
    			$sqlQuery = "SELECT id FROM utl_education_history
    							WHERE user_id = \"$userId\"
    							ORDER BY year ";
    		else
    			$sqlQuery = "SELECT id FROM utl_education_history
    							WHERE user_id = \"$userId\"
    								AND active = \"y\"
    							ORDER BY year ";
    	}else 
    		$sqlQuery = "SELECT id FROM utl_education_history
    							WHERE user_id = \"$userId\"
    								AND active != \"y\"
    							ORDER BY year ";
    	return $this->getDataArray($this->processQuery($sqlQuery));
    }    
    
    public function getEducationHistoryIdDetails($id){
    	return $this->getTableIdDetails($id);
    }   
    
    public function setUserEducationDetails($userId, $instituteId, $year, $level, $score, $scoreType){
    	$counter = $this->getCounter("educationHistory");
    	$sqlQuery = "INSERT INTO utl_education_history (id,user_id, institute_id, level, year, score, scoring_type, last_update_date, last_updated_by, creation_date, created_by, active) 
    				VALUES (\"$counter\", \"$userId\", \"$instituteId\", \"$level\", \"$year\", \"$score\", \"$scoreType\", \"".$this->getCurrentDateTime()."\", \"".$this->getLoggedUserId()."\", \"".$this->getCurrentDateTime()."\", \"".$this->getLoggedUserId()."\", \"y\")";
    	
    	if($this->processQuery($sqlQuery, $counter)){
    		$this->logOperation($userId, "New Education History Has Been Added");
    		return $counter;
    	}
    	return false;
    }
    public function commitEducationDetailsUpdate($id){
        if ($this->sqlConstructQuery == "")
            return $id;

        return $this->commitUpdate($id);
    }

    public function dropEducationHistory($id){
    	if($this->dropTableId($id, false)){
    		$this->logOperation($id, "The history details has been dropped");
    		return true;
    	}
    	return false;
    }
    
    public function activateEducationHistory($id){
    	if($this->activateTableId($id)){
    		$this->logOperation($id, "The history details has been activated");
    		return true;
    	}
    }
    

}
?>