<?php

/**
 * This class will hold the functionalities related to the subject insert details
 * This class extends the general class
 * @author Hemant Kumar Sah
 * @category Global
 * @license Support-Gurukul
 * @version 1.0.0
 */
require_once 'class.loggedInfo.php';

class loginInfo extends loggedInfo {

    public function __construct() {
        parent::__construct();
    }
    
    public function setCandidatePassword($id, $password){
    	$sqlQuery = "SELECT id FROM glb_login WHERE id = \"$id\" ";
    	$sqlQuery = $this->processQuery($sqlQuery);
    	if(mysql_num_rows($sqlQuery)){
    		$sqlQuery = "UPDATE glb_login
				    		SET password = \"".  md5(md5($password.$this->getPasswordSalt()))."\"
				    		WHERE id = \"$id\" ";
    		if($this->processQuery($sqlQuery)){
    			$this->logOperation($id, "The password has been set");
    			return true;
    		}	
    	}else{
    		$sqlQuery = "INSERT INTO glb_login (id, username, password, attempts, type, start_date, end_date, last_update_date, last_updated_by, creation_date, created_by, active) 
    					VALUES (\"$id\", \"$id\", \"".  md5(md5($password.$this->getPasswordSalt()))."\", \"0\", \"LRESER1\", \"".$this->getCurrentDate()."\", \"2020-12-12\", \"" . $this->getCurrentDateTime() . "\", \"" . $this->getLoggedUserId() . "\", \"" . $this->getCurrentDateTime() . "\", \"" . $this->getLoggedUserId() . "\", \"y\")";
    		
    		$this->processQuery($sqlQuery);
    		
    		$this->logOperation($id, "The password has been set");
    		return true;
    	}
    	
        
        return false;
    }
    
    public function setCandidateUsername($id, $userName){
    	$password = "DPSKASHI2010";
    	$sqlQuery = "SELECT id FROM glb_login WHERE id = \"$id\" ";
    	$sqlQuery = $this->processQuery($sqlQuery);
    	if(mysql_num_rows($sqlQuery)){
    		$sqlQuery = "UPDATE glb_login
    		SET username = \"".  $userName."\"
    		WHERE id = \"$id\" ";
    		if($this->processQuery($sqlQuery)){
    			$this->logOperation($id, "The username has been set");
    			return true;
    		}
    	}else{
    		$sqlQuery = "INSERT INTO glb_login (id, username, password, attempts, type, start_date, end_date, last_update_date, last_updated_by, creation_date, created_by, active)
    		VALUES (\"$id\", \"$userName\", \"".  md5(md5($password.$this->getPasswordSalt()))."\", \"0\", \"LRESER1\", \"".$this->getCurrentDate()."\", \"2020-12-12\", \"" . $this->getCurrentDateTime() . "\", \"" . $this->getLoggedUserId() . "\", \"" . $this->getCurrentDateTime() . "\", \"" . $this->getLoggedUserId() . "\", \"y\")";
    	
    		$this->processQuery($sqlQuery);
    	
    		$this->logOperation($id, "The username has been set");
    		return true;
    	}
    	 
    	
    	return false;
    }
    
    public function checkPassword($id, $password){
        $sqlQuery = "SELECT password 
                        FROM glb_login 
                        WHERE id = \"$id\" ";
        $sqlQuery = $this->processArray($sqlQuery);
        if($sqlQuery[0] == md5(md5($password.$this->getPasswordSalt())));
    }
    
    public function getLoginLogIds($userId){
        $sqlQuery = "SELECT id 
                        FROM glb_login_log 
                        WHERE officer_id = \"$userId\" 
                        ORDER BY login_datetime DESC";
        return $this->getDataArray($this->processQuery($sqlQuery));
    }
    
    public function getLoginLogIdDetails($id){
        return $this->getTableIdDetails($id);
    }
    
    public function getUserLoginUsernameId($userId){
    	return $this->getValue('username', 'glb_login', 'id', $userId);	
    }
    
    public function checkUsernameAvailability($userName){
    	$sqlQuery = "SELECT id FROM glb_login WHERE username = \"$userName\" ";
    	$sqlQuery = $this->processQuery($sqlQuery);
    	if(mysql_num_rows($sqlQuery))
    		return false;
    	return true;
    }
    
    
}

?>