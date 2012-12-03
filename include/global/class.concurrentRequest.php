<?php
/**
 * This class will hold the functionalities regarding the user designation ranks
 * This class extends the general class
 * @author Hemant Kumar Sah
 * @category Global
 * @license Support-Gurukul
 * @version 1.0.0
 */
require_once 'class.concurrentProgram.php';

class concurrentRequest extends concurrentProgram {
	
	public function __construct() {
		parent::__construct ();
	}
	
	public function setConcurrentRequest($concurrentProgramId, $startTime, $completeTime, $outputFileType, $activeUser){
		$counter = $this->getCounter("globalConcurrentRequests");
		$sqlQuery = "INSERT INTO glb_concurrent_requests 
						(id, concurrent_program_id, phase_code, status_code, scheduled_start_time, scheduled_completion_time, actual_start_time, actual_completion_time, user_active, last_update_date, last_updated_by, creation_date, created_by, active) 
						VALUES (\"$counter\", \"$concurrentProgramId\", \"LRESER10\", \"LRESER10\", \"$startTime\", \"$completeTime\", \"" . $this->getLoggedUserId () . "\", \"" . $this->getCurrentDateTime () . "\", \"" . $this->getLoggedUserId () . "\", \"" . $this->getCurrentDateTime () . "\", \"" . $this->getLoggedUserId () . "\", \"y\") ";
		
		if($this->processQuery($sqlQuery, $counter)){			
			$this->logOperation($counter, "New entry made");
			return $counter;
		}
		return false;
	}
	
	public function commitConcurrentRequestUpdate($concurrentRequestId){		
		if ($this->sqlConstructQuery == ""){					
			return $concurrentRequestId;
		}
		return $this->commitUpdate($concurrentRequestId);
	}	
	
	public function holdConcurrentRequest($concurrentRequestId){
		$statusCode = $this->getConcurrentRequestStatus($concurrentRequestId, true);
		if($statusCode === 'LRESER33' || $statusCode === 'LRESER34'){
			return $this->setConcurrentRequestPhaseCode($concurrentRequestId, 'LRESER28');
		}
		return false;
	}
	
	public function unHoldConcurrentRequest($concurrentRequestId){
		$statusCode = $this->getConcurrentRequestStatus($concurrentRequestId, false);
		if($statusCode === 'LRESER28'){
			return $this->setConcurrentRequestPhaseCode($concurrentRequestId, 'LRESER32');
		}
		return false;
	}
	
	public function cancelConcurrentRequest($concurrentRequestId){
		;	
	}
	
	public function setConcurrentRequestStatusCode($concurrentRequestId, $code){
		$this->updateTableParameter('status_code', $code);
		return $this->commitConcurrentRequestUpdate($concurrentRequestId);
	}
	
	public function setConcurrentRequestPhaseCode($concurrentRequestId, $code){
		$this->updateTableParameter('phase_code', $code);
		return $this->commitConcurrentRequestUpdate($concurrentRequestId);
	}
	
	public function getConcurrentRequestStatus($concurrentRequestId, $type){
		if($type)
			$sqlQuery = "SELECT status_code FROM glb_concurrent_requests WHERE id = \"$concurrentRequestId\" ";
		else
			$sqlQuery = "SELECT phase_code FROM glb_concurrent_requests WHERE id = \"$concurrentRequestId\" ";
		
		return $this->processSingleElementQuery($sqlQuery);
	}
	
	
	//functions related to the setting up of attribute
	public function setConcurrentRequestAttribute($concurrentRequestId, $parameterName, $parameterValue, $order){
		$counter = $this->getCounter("globalConcurrentRequestAttributes");
		$sqlQuery = "INSERT INTO glb_concurrent_requests
					(id, concurrent_request_id, parameter_name, parameter_value, display_order, last_update_date, last_updated_by, creation_date, created_by, active)
					VALUES (\"$counter\", \"$concurrentRequestId\", \"$parameterName\", \"$parameterValue\", \"$order\", \"" . $this->getCurrentDateTime () . "\", \"" . $this->getLoggedUserId () . "\", \"" . $this->getCurrentDateTime () . "\", \"" . $this->getLoggedUserId () . "\", \"y\") ";
		
		if($this->processQuery($sqlQuery, $counter)){
				$this->logOperation($counter, "New entry made");
			return $counter;
		}
		return false;
	}
	
	public function getConcurrentRequestAttributeIds($concurrentRequestId, $active){
		if($active){
			if($active === 'all')
				$sqlQuery = "SELECT id FROM glb_concurrent_attributes WHERE concurrent_request_id = \"$concurrentRequestId\" ORDER BY display_order ASC";
			else
				$sqlQuery = "SELECT id FROM glb_concurrent_attributes WHERE concurrent_request_id = \"$concurrentRequestId\" AND active = \"y\" ORDER BY display_order ASC";			
		}else
			$sqlQuery = "SELECT id FROM glb_concurrent_attributes WHERE concurrent_request_id = \"$concurrentRequestId\" AND active != \"y\" ORDER BY display_order ASC";

		return $this->getDataArray($this->processQuery($sqlQuery));
	}
	
	public function getConcurrentRequestAttribute($concurrentRequestId){
		$attributeIds = $this->getConcurrentRequestAttributeIds($concurrentRequestId, 1);
		$i = 0;
		$urlString = '?';
		foreach ($attributeIds as $attributeId){
			$details = $this->getTableIdDetails($attributeId);
			if($i != 0)
				$urlString .= '&'.$details['parameter_name'].'='.$details['parameter_value'];
			else 
				$urlString .= $details['parameter_name'].'='.$details['parameter_value'];
			++$i;
		}
		return $urlString;		
	}
	
	public function commitConcurrentRequestAttributeUpdate($concurrentRequestAttributeId){
		if ($this->sqlConstructQuery == ""){
			return $concurrentRequestAttributeId;
		}
		return $this->commitUpdate($concurrentRequestAttributeId);
	}		
}
?>