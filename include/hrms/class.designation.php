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

class Designation extends general {
    
    public function __construct() {
        parent::__construct();
    }

    public function setUserRank($userId, $rankId, $startDate, $endDate, $source){
    	$counter = $this->getCounter('designation');    	
    	$sqlQuery = "INSERT INTO hrms_user_ranks 
    					(id, user_id, rank_id, start_date, end_date, source, last_update_date, last_updated_by, creation_date, created_by, active) 
    					VALUES (\"$counter\", \"$userId\", \"$rankId\", \"$startDate\", \"$endDate\", \"$source\", \"" . $this->getCurrentDateTime() . "\", \"" . $this->getLoggedUserId() . "\", \"" . $this->getCurrentDateTime() . "\", \"" . $this->getLoggedUserId() . "\", \"y\") ";  	
    	
    	if ($this->processQuery($sqlQuery, $counter)){
    		$this->logOperation($userId, 'The new designation has been added to the user');
    		return $counter;
    	}
    	return false;
    }
    
    public function unsetUserRank($userId, $rankId, $source){
    	$sqlQuery = "SELECT id FROM hrms_user_ranks WHERE user_id = \"\" AND rank_id = \"\" AND source = \"\" AND (end_date = \"0000-00-00\" OR end_date > \"".$this->getCurrentDate()."\")";
    	$sqlQuery = $this->processArray($sqlQuery);
    	if($sqlQuery[0] != ''){
    		$designationId = $sqlQuery[0];
    		$this->updateTableParameter('end_date', $this->getCurrentDate());
    		if($this->commitRankUpdate($designationId))
    			return true;    		
    	}
    	return false;
    }
    
    public function getUserRanks($userId, $active){
    	if($active){
    		if($active === 'all')
    			$sqlQuery = "SELECT id 
    							FROM hrms_user_ranks
    							WHERE user_id = \"$userId\" 
    							ORDER BY start_date DESC ";
    		else
    			$sqlQuery = "SELECT id
    							FROM hrms_user_ranks
    							WHERE user_id = \"$userId\"
    								AND active = \"y\"  
    							ORDER BY start_date DESC ";
    	}else
    		$sqlQuery = "SELECT id
    						FROM hrms_user_ranks
    						WHERE user_id = \"$userId\"
    							AND active != \"y\"
    						ORDER BY start_date DESC ";
    	
    	return $this->getDataArray($this->processQuery($sqlQuery));
    }
    
    public function getRankIdDetails($rankId){
    	return $this->getTableIdDetails($rankId);
    }
    
    public function commitRankUpdate($rankId){
    	if ($this->sqlConstructQuery == "")
    		return false;
    	
    	$this->sqlConstructQuery .= ", last_update_date=\"" . $this->getCurrentDateTime() . "\", last_updated_by=\"" . $this->getLoggedUserId() . "\"";
    	$sqlQuery = "UPDATE hrms_user_ranks
    					SET $this->sqlConstructQuery
    				WHERE id = \"$rankId\" ";
    	
    	$this->sqlConstructQuery = "";
    	
    	if ($this->processQuery($sqlQuery, $rankId)) {
    		$this->logOperation($rankId, "The User Designation Details Has Been Updated");
    		return true;
    	}
    	return false;
    }

 	public function dropRankDetails($rankId) {
        if($this->dropTableId($rankId, false)){
            $this->logOperation($rankId, "The User Designation Has Been Dropped");
            return true;
        }
        return false;
    }
    
    

    public function activateRankDetails($rankId) {
        if($this->activateTableId($rankId)){
            $this->logOperation($rankId, "The User Designation Has Been Activated");
            return true;
        }
        return false;
    }

}
?>