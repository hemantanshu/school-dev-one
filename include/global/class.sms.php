<?php
/**
 * This class will hold the functionalities regarding the user designation ranks
 * This class extends the general class
 * @author Hemant Kumar Sah
 * @category Global
 * @license Support-Gurukul
 * @version 1.0.0
 */
require_once 'class.general.php';

class Sms extends general {
	
	public function __construct() {
		parent::__construct ();
	}	
	
	public function getPendingSMSIds(){
		$sqlQuery = "SELECT id FROM glb_sms_pending WHERE active = \"y\" ORDER BY priority ASC, created_by ASC";
		return $this->getDataArray($this->processQuery($sqlQuery));
	}
	
	public function setProcessedSMS($sourceId, $confirmationId, $userName, $mobile, $sms, $type, $priority, $status){
		$counter = $this->getCounter('smsProcessed');
		$sqlQuery = "INSERT INTO glb_sms_processed 
						(id, source_id, confirmation_id, user_name, mobile_number, sms_content, sms_type, priority, sms_status, last_update_date, last_updated_by, creation_date, created_by, active) 
						VALUES (\"$counter\", \"$sourceId\", \"$confirmationId\", \"$userName\", \"$mobile\", \"$sms\", \"$type\", \"$priority\", \"$status\", \"".$this->getCurrentDateTime()."\", \"LUSERS0\", \"".$this->getCurrentDateTime()."\", \"LUSERS0\", \"y\")";
		
		if($this->processQuery($sqlQuery, $counter)){
			$this->logOperation($counter, 'inserted');
			return $counter;
		}
		return false;
	}
	
	public function dropPendingSms($id){
		$this->dropTableId($id, true);
	}
}
?>