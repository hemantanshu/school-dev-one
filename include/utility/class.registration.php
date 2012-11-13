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

class registrationInfo extends general {

    private $_registrationDetails;

    public function __construct() {
        parent::__construct();
    }

    
    public function setCandidateRegistrationDetails($candidateId, $entranceId, $registrationNo, $date, $classId, $sectionId, $houseId, $record1, $record2, $record3){
    	if($this->getCandidateRegistrationNumberId($registrationNo))
    		return false;
    	//if($this->getEntranceIdRegistrationId($entranceId))
    		//return false;
    	
    	$counter = $this->getCounter("candidate_registration");
    	$sqlQuery = "INSERT 
    					INTO utl_candidate_registration 
    						(id, candidate_id, entrance_id, registration_number, registration_date, class_id, section_id, house_id, record1_id, record2_id, record3_id, last_update_date, last_updated_by, creation_date, created_by, active) 
    					VALUES 
    						(\"$counter\", \"$candidateId\", \"$entranceId\", \"$registrationNo\", \"$date\", \"$classId\", \"$sectionId\", \"$houseId\", \"$record1\", \"$record2\", \"$record3\", \"" . $this->getCurrentDateTime() . "\", \"" . $this->getLoggedUserId() . "\", \"" . $this->getCurrentDateTime() . "\", \"" . $this->getLoggedUserId() . "\", \"y\") ";
    	$sqlQuery = $this->processQuery($sqlQuery, $counter);
    	if($sqlQuery){
    		$this->logOperation($candidateId, "Registration Details Successfully Inserted");
    		$this->logOperation($counter, "Candidate Information Inserted");
    		return $counter;
    	} 
    	return false;    	
    }
    
    public function getCandidateRegistrationNumber($candidateId){
    	$sqlQuery = "SELECT registration_number FROM utl_candidate_registration WHERE candidate_id = \"$candidateId\" ";
    	$sqlQuery = $this->processArray($sqlQuery);
    	
    	if($sqlQuery[0] != "")
    		return $sqlQuery[0];
    	return false;
    }
    
    public function getCandidateRegistrationNumberId($registrationNo){
    	$sqlQuery = "SELECT id FROM utl_candidate_registration WHERE registration_number = \"$registrationNo\" ";
    	$sqlQuery = $this->processArray($sqlQuery);
    	
    	if($sqlQuery[0] != "")
    		return $sqlQuery[0];
    	return false;
    }
    
    public function getEntranceIdRegistrationId($entranceId){
    	$sqlQuery = "SELECT id FROM utl_candidate_registration WHERE entrance_id = \"$entranceId\" ";
    	$sqlQuery = $this->processArray($sqlQuery);
    	 
    	if($sqlQuery[0] != "")
    		return $sqlQuery[0];
    	return false;
    }
    
    public function getRegistrationIdDetails($id){
    	return $this->getTableIdDetails($id);
    }
    
    public function getCandidateRegistrationId($candidateId){
    	$sqlQuery = "SELECT id FROM utl_candidate_registration WHERE candidate_id = \"$candidateId\" ";
    	$sqlQuery = $this->processArray($sqlQuery);
    	 
    	if($sqlQuery[0] != "")
    		return $sqlQuery[0];
    	return false;
    }
    public function commitRegistrationDetailsUpdate($registrationId){
        if ($this->sqlConstructQuery == "")
            return $registrationId;

        $this->commitUpdate($registrationId);
    }
    
    public function dropRegistrationDetails($registrationId){
    	if($this->dropTableId($registrationId, false)){
    		$this->logOperation($registrationId, "The registration details has been dropped");
    		return true;
    	}
    	return false;    	
    }
    
    public function activateRegistrationDetails($registrationId){
    	if($this->activateTableId($registrationId)){
    		$this->logOperation($registrationId, "The registration details has been activated");
    		return true;
    	}
    }   
    
}
?>