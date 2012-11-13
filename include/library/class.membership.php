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

class Membership extends general {
	
    public function __construct() {
        parent::__construct();
    }

    public function fetchPendingRegisteredMembers($type){
    	if($type){
    		if($type === 'all')
    		$sqlQuery = "SELECT a.id FROM utl_personal_info a,
				    		library_user_membership b
				    		WHERE a.id != b.user_id
					    		AND b.active = \"y\"
					    		AND a.active = \"y\" ";
    		else 
    			$sqlQuery = "SELECT a.id FROM utl_personal_info a,
    							utl_employee_registration b
				    			WHERE a.id = b.employee_id
				    				AND b.active = \"y\"
				    				AND a.active = \"y\"
    								AND NOT EXISTS (SELECT 1 FROM library_user_membership c WHERE c.user_id = a.id ) ";    		
    	}else
    		$sqlQuery = "SELECT a.id FROM utl_personal_info a,
				    		utl_candidate_registration b
				    		WHERE a.id = b.candidate_id
					    		AND b.active = \"y\"
					    		AND a.active = \"y\"
					    		AND NOT EXISTS (SELECT 1 FROM library_user_membership c WHERE c.user_id = a.id ) ";
    	return $this->getDataArray($this->processQuery($sqlQuery));    	
    }
    
    public function setUserMembership($userId, $membershipType){
    	$counter = $this->getCounter('libraryUserMembership');
    	$sqlQuery = "INSERT INTO library_user_membership
						(id, user_id, membership_type, last_update_date, last_updated_by, creation_date, created_by, active) 
						VALUES (\"$counter\", \"" . $this->getCurrentDateTime() . "\", \"" . $this->getLoggedUserId() . "\", \"" . $this->getCurrentDateTime() . "\", \"" . $this->getLoggedUserId() . "\", \"y\")";
    	if($this->processQuery($sqlQuery, $counter)){
    		$this->logOperation($counter, 'New Entry');
    		return $counter;
    	}
    	return false;
    }
    
    public function getUserMembershipIds($userId, $active){
    	if($active){
    		if($active === 'all')
    			$sqlQuery = "SELECT id FROM library_user_membership WHERE user_id = \"$userId\" ORDER BY creation_date DESC";
    		else
    			$sqlQuery = "SELECT id FROM library_user_membership WHERE user_id = \"$userId\" AND active = \"y\" ORDER BY creation_date DESC";
    	}else
    		$sqlQuery = "SELECT id FROM library_user_membership WHERE user_id = \"$userId\" AND active != \"y\" ORDER BY creation_date DESC";
    	
    	return $this->getDataArray($this->processQuery($sqlQuery));
    }
    
    public function getUserMembershipType($userId, $flag){
    	if($flag)
    		$sqlQuery = "SELECT a.membership_type 
    						FROM library_user_membership a, utl_personal_info b 
    						WHERE a.user_id = b.id 
    							AND a.user_id = \"$userId\"
    							AND b.active = \"y\" 
    							AND a.active = \"y\" ";
    	else
    		$sqlQuery = "SELECT membership_type FROM library_user_membership WHERE user_id = \"$userId\" AND active = \"y\" ";
    	
    	$sqlQuery = $this->processArray($sqlQuery);
    	if($sqlQuery[0] != ''){
    		return $sqlQuery[0];
    	}
    	return false;
    }
    
    public function dropUserMembership($membershipId){
    	if($this->dropTableId($membershipId, false)){
    		$this->logOperation($membershipId, 'Dropped');
    		return $membershipId;
    	}
    	return false;
    }
}
?>