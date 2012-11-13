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

class Notification extends general {

    public function __construct() {
        parent::__construct();
    }

    public function getNotificationIds($userId, $active){
        if($active){
            if($active === 'all')
                $sqlQuery = "SELECT id
                                FROM glb_push_notification
                                WHERE user_id like \"%$userId%\"
                                ORDER BY start_date DESC";
            else
                $sqlQuery = "SELECT id
                                FROM glb_push_notification
                                WHERE user_id like \"%$userId%\"
                                    AND active = \"y\"
                                ORDER BY start_date DESC";
        }else
            $sqlQuery = "SELECT id
                            FROM glb_push_notification
                            WHERE user_id like \"%$userId%\"
                                AND active != \"y\"
                            ORDER BY start_date DESC";
        return $this->getDataArray($this->processQuery($sqlQuery));
    }

    public function setNewNotification($userId, $notificationHeader, $notificationBody, $startDate, $endDate, $severity, $source){
    	$counter = $this->getCounter('pushNotification');
    	$sqlQuery = "INSERT 
    					INTO glb_push_notification 
    					(id, user_id, message_header, message_content, start_date, end_date, severity, source, last_update_date, last_updated_by, creation_date, created_by, active) 
    					VALUES (\"$counter\", \"$userId\", \"$notificationHeader\", \"$notificationBody\", \"$startDate\", \"$endDate\", \"$severity\", \"$source\", \"" . $this->getCurrentDateTime () . "\", \"" . $this->getLoggedUserId () . "\", \"" . $this->getCurrentDateTime () . "\", \"" . $this->getLoggedUserId () . "\", \"y\")";    	
    	if($this->processQuery($sqlQuery, $counter)){
    		$this->logOperation($counter, 'A new notification has been raised been created');
    		return $counter;
    	}
    	return false;
    }
    
    public function getNotificationId4Source($sourceId){
    	return $this->getValue('id', 'glb_push_notification', 'source', $sourceId);
    }
    
    public function commitNotificationUpdate($notificationId){
    	if ($this->sqlConstructQuery == "")
    		return $notificationId;
    	
    	return $this->commitUpdate($notificationId);
    }    
    
    public function dropNotification($notificationId){
    	if($this->dropTableId($notificationId, false)){
    		$this->logOperation($notificationId, "The notification has been dropped");
    		return true;
    	}
    	return false;
    }
    
    public function activateNotification($notificationId){
    	if($this->activateTableId($notificationId)){
    		$this->logOperation($notificationId, "The notification has been activated");
    		return true;
    	}
    	return false;
    }
}

?>