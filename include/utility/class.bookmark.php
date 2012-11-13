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

class Bookmark extends general {
	
    public function __construct() {
        parent::__construct();
    }

    public function getUserBookmarkIds($userId, $type, $hint){
    	if($type){
    		if($type === 'all')
    			$sqlQuery = "SELECT id FROM utl_personal_bookmark
    							WHERE user_id = \"$userId\"
    								AND page_name LIKE \"%$hint%\"
    							ORDER BY priority DESC ";
    		else
    			$sqlQuery = "SELECT id FROM utl_personal_bookmark
    							WHERE user_id = \"$userId\"
    								AND active = \"y\"
    								AND page_name LIKE \"%$hint%\"
    							ORDER BY priority DESC ";
    	}else 
    		$sqlQuery = "SELECT id FROM utl_personal_bookmark
    							WHERE user_id = \"$userId\"
    								AND active != \"y\"
    								AND page_name LIKE \"%$hint%\"
    							ORDER BY priority DESC ";
    	return $this->getDataArray($this->processQuery($sqlQuery));
    }    
    
    public function getBookmarkIdDetails($id){
    	return $this->getTableIdDetails($id);
    }   
    
    public function setUserBookmarkDetails($userId, $name, $url, $priority, $redirect){
    	$counter = $this->getCounter("personalBookmark");
    	$sqlQuery = "INSERT INTO utl_personal_bookmark (id,user_id, page_name, url, priority, redirect, last_update_date, last_updated_by, creation_date, created_by, active) 
    				VALUES (\"$counter\", \"$userId\", \"$name\", \"$url\", \"$priority\", \"$redirect\", \"".$this->getCurrentDateTime()."\", \"".$this->getLoggedUserId()."\", \"".$this->getCurrentDateTime()."\", \"".$this->getLoggedUserId()."\", \"y\")";
    	
    	if($this->processQuery($sqlQuery, $counter)){
    		$this->logOperation($userId, "New Bookmark Has Been Added");
    		return $counter;
    	}
    	return false;
    }
    public function commitBookmarkDetailsUpdate($id){
        if ($this->sqlConstructQuery == "")
            return $id;

        $this->commitUpdate($id);
    }

    public function dropBookmarkDetails($id){
    	if($this->dropTableId($id, false)){
    		$this->logOperation($id, "The personal bookmark details has been dropped");
    		return true;
    	}
    	return false;
    }
    
    public function activateBookmarkDetails($id){
    	if($this->activateTableId($id)){
    		$this->logOperation($id, "The personal bookmark details has been activated");
    		return true;
    	}
    }
}
?>