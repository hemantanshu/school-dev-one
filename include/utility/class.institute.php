<?php

/**
 * This class will hold the functionalities related to the subject insert details
 * This class extends the general class
 * @author Hemant Kumar Sah
 * @category Global
 * @license Support-Gurukul
 * @version 1.0.0
 */
require_once 'config.php';
require_once BASE_PATH.'include/global/class.loggedInfo.php';

class institute extends loggedInfo {
    public function __construct() {
        parent::__construct();
    }
        
    public function getInstituteSearchIds($str, $type){
    	if($type){
    		if($type == 'all')
    			$sqlQuery = "SELECT * 
    							FROM utl_institute_details 
    							WHERE institute_name LIKE \"%$str%\" ";
    		else
    			$sqlQuery = "SELECT * 
    							FROM utl_institute_details 
    							WHERE institute_name LIKE \"%$str%\" 
    								AND active = \"y\"";
    	}else
    		$sqlQuery = "SELECT * 
    							FROM utl_institute_details 
    							WHERE institute_name LIKE \"%$str%\" 
    								AND active != \"y\"";
    	
    	return $this->getDataArray($this->processQuery($sqlQuery));
    }
    
    public function getInstituteIdDetails($instituteId){
        return $this->getTableIdDetails($instituteId);
    }
    
    public function getInstituteName($instituteId){
    	return $this->getValue('institute_name', 'utl_institute_details', 'id', $instituteId);
    }
    
    public function dropInstituteDetails($instituteId){
        if($this->dropTableId($instituteId, false)){
            $this->logOperation($instituteId, "The Institute Details Has Been Dropped");
            return true;
        }
        return false;
    }
    
    public function activateInstituteDetails($instituteId){
        if($this->activateTableId($instituteId)){
            $this->logOperation($instituteId, "The Institute Details Has Been Activated");
            return true;
        }
        return false;
    }
    
    public function setInstituteDetails($instituteName, $universityId, $contactNo){
    	$counter = $this->getCounter('institute');
    	
    	$sqlQuery = "INSERT INTO utl_institute_details 
    					(id, institute_name, university_id, contact_number, last_update_date, last_updated_by, creation_date, created_by, active) 
    				VALUES (\"$counter\", \"$instituteName\", \"$universityId\", \"$contactNo\", \"" . $this->getCurrentDateTime() . "\", \"" . $this->getLoggedUserId() . "\", \"" . $this->getCurrentDateTime() . "\", \"" . $this->getLoggedUserId() . "\", \"y\")";
    	
    	if($this->processQuery($sqlQuery, $counter)){
    		$this->logOperation($counter, "New Institute Has Been Defined");
    		return $counter;
    	}
    	return false;
    }
    
    public function commitInstituteDetailsUpdate($instituteId){
        if ($this->sqlConstructQuery == "")
            return $instituteId;

        return $this->commitUpdate($instituteId);
    }
    
    

    

}

?>