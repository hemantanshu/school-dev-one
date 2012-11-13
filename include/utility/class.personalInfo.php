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

class personalInfo extends general {

    private $_candidateDetails;
    private $_candidateGuardianDetails;

    public function __construct() {
        parent::__construct();
    }

    public function searchCandidateDetails($searchString, $active){
    	$sqlQuery = "SELECT a.id, c.value_name, a.first_name, a.middle_name, a.last_name, b.registration_number, a.dob, a.gender, a.photograph_name, d.value_name
    					FROM utl_personal_info a, utl_candidate_registration b, glb_option_values c, glb_option_values d
    					WHERE a.id = b.candidate_id
    						AND a.salutation_id = c.id
    						AND a.nationality = d.id
    						AND (a.first_name LIKE \"%$searchString%\" OR a.last_name LIKE \"%$searchString%\" OR a.middle_name LIKE \"%$searchString%\" )";
    	
    	return $this->getDataArray($this->processQuery($sqlQuery), 10);
    }
    
    public function searchEmployeeDetails($searchString, $active){
    	$sqlQuery = "SELECT a.id, c.value_name, a.first_name, a.middle_name, a.last_name, b.employee_code, a.dob, a.gender, a.photograph_name, d.value_name
    					FROM utl_personal_info a, utl_employee_registration b, glb_option_values c, glb_option_values d
				    	WHERE a.id = b.employee_id
				    	AND a.salutation_id = c.id
				    	AND a.nationality = d.id
				    	AND (a.first_name LIKE \"%$searchString%\" OR a.last_name LIKE \"%$searchString%\" OR a.middle_name LIKE \"%$searchString%\" )";
    	 
    	return $this->getDataArray($this->processQuery($sqlQuery), 10);
    }
    
    public function getUserIdDetails($userId) {
        $this->_candidateDetails = $this->getTableIdDetails($userId);
        return $this->_candidateDetails;
    }
    
    public function getUserGuardianIdDetails($guardian_id){
        $this->_candidateGuardianDetails = $this->getTableIdDetails($guardian_id);
        return $this->_candidateGuardianDetails;
    }
    
    public function getUserGuardianIds($userId){
        $sqlQuery = "SELECT id FROM utl_guardian_details WHERE candidate_id = \"$userId\" ORDER BY type";
        return $this->getDataArray($this->processQuery($sqlQuery));
    }
    
    public function getUserGuardianId($userId, $guardianType){
    	$sqlQuery = "SELECT id FROM utl_guardian_details WHERE candidate_id = \"$userId\" AND guardian_type = \"$guardianType\" ";
    	$sqlQuery = $this->processArray($sqlQuery);
    	return $sqlQuery[0];
    }
    
    public function getUserAddressIds($userId, $flag, $guardianType){
    	if($flag)
    		$sqlQuery = "SELECT address_id FROM utl_personal_info WHERE id = \"$userId\" ";
    	else
    		$sqlQuery = "SELECT address_id FROM utl_guardian_details WHERE candidate_id = \"$userId\" && guardian_type = \"$guardianType\" ";
    	
    	$sqlQuery = $this->processArray($sqlQuery);
    	return $sqlQuery[0];
    }

    public function setUserDetails($salutation, $fname, $mname, $lname, $dob, $gender, $emailId, $mobileNo, $religion, $landlineNo, $nationality, $maritalStatus = '', $personalEmailId = '') {
        $counter = $this->getCounter('candidate');
        $sqlQuery = "INSERT INTO utl_personal_info 
                            (id, salutation_id, first_name, middle_name, last_name, dob, official_email_id, mobile_no, gender, religion, landline_no, nationality, marital_status_id, personal_email_id, last_update_date, last_updated_by, creation_date, created_by, active) 
                            VALUES (\"$counter\", \"$salutation\", \"$fname\", \"$mname\", \"$lname\", \"$dob\", \"$emailId\", \"$mobileNo\", \"$gender\", \"$religion\", \"$landlineNo\", \"$nationality\", \"$maritalStatus\", \"$personalEmailId\", \"" . $this->getCurrentDateTime() . "\", \"" . $this->getLoggedUserId() . "\", \"" . $this->getCurrentDateTime() . "\", \"" . $this->getLoggedUserId() . "\", \"y\") ";
        if($this->processQuery($sqlQuery, $counter)){
            $this->logOperation($counter, "New User Has Been Registered");
            return $counter;
        }
        return false;        
    }
    
    public function setUserAddress($userId){
    	$counter = $this->getCounter("address");
    	$sqlQuery = "UPDATE utl_personal_info SET address_id = \"$counter\" WHERE id = \"$userId\" ";
    	$this->processQuery($sqlQuery);
    	
    	$sqlQuery = "INSERT INTO utl_address_details (id, creation_date, created_by, active) VALUES (\"$counter\", \"".$this->getCurrentDateTime()."\", \"".$this->getLoggedUserId()."\", \"y\" )";
    	$this->processQuery($sqlQuery);
    	
    	return $counter;
    }
    
    public function getUserAttributeDetails($attributeName){
        return $this->_candidateDetails[$attributeName];
    }
    
    public function getUserGuardianAttributeDetails($attributeName){
        return $this->_candidateGuardianDetails[$attributeName];
    }
    
    public function getUserName(){
        return $this->_candidateDetails['last_name'].', '.$this->_candidateDetails['middle_name'].' '.$this->_candidateDetails['first_name'];                
    }
    
    public function setUserGuardianDetails($type, $candidate_id, $salutation, $fname, $mname, $lname, $mobile_no, $landline_no, $emailId, $occupation){
        $counter = $this->getCounter('guardian');
        $sqlQuery = "INSERT INTO utl_guardian_details 
                            (id, candidate_id, guardian_type, salutation_id, first_name, middle_name, last_name, mobile_no, landline_no, email_id, occupation_id, last_update_date, last_updated_by, creation_date, created_by, active) 
                            VALUES (\"$counter\", \"$candidate_id\", \"$type\", \"$salutation\", \"$fname\", \"$mname\", \"$lname\", \"$mobile_no\", \"$landline_no\", \"$emailId\", \"$occupation\", \"".$this->getCurrentDateTime()."\", \"".$this->getLoggedUserId()."\", \"".$this->getCurrentDateTime()."\", \"".$this->getLoggedUserId()."\", \"y\")";        
        if($this->processQuery($sqlQuery, $counter)){
            $this->logOperation($counter, "New User Guardian Detail Has Been Setup");
            $this->logOperation($candidate_id, "New User Type $counter has been setup");
            return $counter;
        }
        return false;
    }
    
    public function commitUserDetailsUpdate($candidate_id){
        if ($this->sqlConstructQuery == "")
            return $candidate_id;

        return $this->commitUpdate($candidate_id);
    }
    
    public function commitUserGuardianUpdate($id){
        if ($this->sqlConstructQuery == "")
            return false;

        return $this->commitUpdate($id);
    }

}
?>