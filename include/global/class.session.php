<?php
/**
 * This class will hold the functionalities regarding the different options and the option assignment.
 * This class extends the general class
 * @author Hemant Kumar Sah
 * @category Global
 * @license Support-Gurukul
 * @version 1.0.0
 */
require_once 'class.general.php';

class Session extends general {

    public function __construct() {
        parent::__construct();
    }

    public function getSessionIds($sessionName, $active, $sessionType){
        if($active){
            if($active === 'all')
                $sqlQuery = "SELECT id
                                FROM glb_session_details
                                WHERE session_name like \"%$sessionName%\"
                                  AND session_type = \"$sessionType\"
                                ORDER BY start_date DESC";
            else
                $sqlQuery = "SELECT id
                                FROM glb_session_details
                                WHERE session_name like \"%$sessionName%\"
                                    AND active = \"y\"
                                    AND session_type = \"$sessionType\"
                                ORDER BY start_date DESC";
        }else
            $sqlQuery = "SELECT id
                            FROM glb_session_details
                            WHERE session_name like \"%$sessionName%\"
                                AND active != \"y\"
                                AND session_type = \"$sessionType\"
                            ORDER BY start_date DESC";
        return $this->getDataArray($this->processQuery($sqlQuery));
    }

    public function setNewSession($sessionName, $sessionType, $startDate, $endDate){
    	$counter = $this->getCounter('session');
    	$sqlQuery = "INSERT 
    					INTO glb_session_details 
    					(id, session_name, session_type, start_date, end_date, last_update_date, last_updated_by, creation_date, created_by, active) 
    					VALUES (\"$counter\", \"$sessionName\", \"$sessionType\", \"$startDate\", \"$endDate\", \"" . $this->getCurrentDateTime () . "\", \"" . $this->getLoggedUserId () . "\", \"" . $this->getCurrentDateTime () . "\", \"" . $this->getLoggedUserId () . "\", \"y\")";
    	if($this->processQuery($sqlQuery, $counter)){
    		$this->logOperation($counter, 'New Session Of The Type '.$sessionType.' has been created');
    		return $counter;
    	}
    	return false;
    }
    
    public function commitSessionUpdate($sessionId){
    	if ($this->sqlConstructQuery == "")
    		return $sessionId;
    	
    	return $this->commitUpdate($sessionId);
    }
    
    public function getSessionDetails4Month($month, $type){
    	$monthNo = substr($month, 4, 2);
    	$year = substr($month, 0, 4);
    	$date = date('Y-m-d', mktime(0, 0, 0, $monthNo, cal_days_in_month(CAL_GREGORIAN, $monthNo, $year), $year));
    	
    	$sqlQuery = "SELECT start_date, end_date, session_name 
    					FROM glb_session_details 
    					WHERE start_date <= \"$date\" 
    						AND end_date >= \"$date\"
    						AND session_type = \"$type\" 
    						AND active = \"y\" ";
    	return $this->processArray($sqlQuery);
    }

    public function getSessionLatestEndDate($sessionType){
        $sqlQuery = "SELECT MAX(end_date) FROM glb_session_details WHERE session_type = \"$sessionType\" ";
        $sqlQuery = $this->processArray($sqlQuery);
        if($sqlQuery[0] != '')
            return $sqlQuery[0];
        return false;
    }
    
    public function isSessionEditable($sessionId){
    	$sqlQuery = "SELECT id FROM glb_session_details WHERE id = \"$sessionId\" AND end_date < \"".$this->getCurrentDate()."\" ";
    	$sqlQuery = $this->processQuery($sqlQuery);
    	if(mysql_num_rows($sqlQuery) == 0)
    		return true;
    	return false;
    }
}

?>