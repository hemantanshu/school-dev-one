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

class Seminar extends general {
    
    public function __construct() {
        parent::__construct();
    }
    
    public function setUserSeminarDetails($userId, $seminarTitle, $organizedBy, $startDate, $duration){
    	$counter = $this->getCounter('seminar');
    	$sqlQuery = "INSERT 
    					INTO hrms_seminar_details 
    					(id, user_id, seminar_title, organized_by, duration, seminar_date, last_update_date, last_updated_by, creation_date, created_by, active) 
    					VALUES (\"$counter\", \"$userId\", \"$seminarTitle\", \"$organizedBy\", \"$duration\", \"$startDate\", \"" . $this->getCurrentDateTime() . "\", \"" . $this->getLoggedUserId() . "\", \"" . $this->getCurrentDateTime() . "\", \"" . $this->getLoggedUserId() . "\", \"y\") ";
    	if($this->processQuery($sqlQuery, $counter)){
    		$this->logOperation($userId, "New Seminar entry has been made");
    		return $counter;
    	}
    	return false;
    }
    
    public function getUserSeminarIds($userId, $active){
    	if($active){
    		if($active == "all")
    			$sqlQuery = "SELECT id 
    							FROM hrms_seminar_details 
    							WHERE user_id = \"$userId\" 
    							ORDER BY seminar_date DESC";
    		else
    			$sqlQuery = "SELECT id 
    							FROM hrms_seminar_details 
    							WHERE user_id = \"$userId\" 
    								AND active = \"y\" 
    							ORDER BY seminar_date DESC";    		
    	}else 
    		$sqlQuery = "SELECT id 
    						FROM hrms_seminar_details 
    						WHERE user_id = \"$userId\" 
    							AND actice != \"y\" 
    						ORDER BY seminar_date DESC";
    	
    	return $this->getDataArray($this->processQuery($sqlQuery));
    }

    public function getSeminarIdDetails($seminarId){
    	return $this->getTableIdDetails($seminarId);
    }    
   
    public function commitSeminarUpdate($seminarId){
    	if ($this->sqlConstructQuery == ""){
    		return $seminarId;
    	}    		
    	
    	return $this->commitUpdate($seminarId);
    }

 	public function dropSeminarDetails($seminarId) {
        if($this->dropTableId($seminarId, false)){
            $this->logOperation($seminarId, "The User Seminar Has Been Dropped");
            return true;
        }
        return false;
    }
    
    

    public function activateSeminarDetails($seminarId) {
        if($this->activateTableId($seminarId)){
            $this->logOperation($seminarId, "The User Seminar Has Been Activated");
            return true;
        }
        return false;
    }

}
?>