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

class concurrentProgram extends general {
	
	public function __construct() {
		parent::__construct ();
	}
	
	public function setConcurrentProgram($programName, $schema, $programUrl, $subsequentRequest, $holdRequest, $cancelRequest, $notifyUser){
		$counter = $this->getCounter("globalConcurrentPrograms");
		$sqlQuery = "INSERT INTO glb_concurrent_programs 
						(id, concurrent_program_name, schema_name, concurrent_program_url, subsequent_request, hold_request, cancel_request, notify_user, last_update_date, last_updated_by, creation_date, created_by, active) 
						VALUES (\"$counter\", \"$programName\", \"$schema\", \"$programUrl\", \"$subsequentRequest\", \"$holdRequest\", \"$cancelRequest\", \"$notifyUser\", \"" . $this->getCurrentDateTime () . "\", \"" . $this->getLoggedUserId () . "\", \"" . $this->getCurrentDateTime () . "\", \"" . $this->getLoggedUserId () . "\", \"y\") ";
		
		if($this->processQuery($sqlQuery, $counter)){			
			$this->logOperation($counter, "New entry made");
			return $counter;
		}
		return false;
	}
	
	public function commitConcurrentProgramUpdate($concurrentProgramId){		
		if ($this->sqlConstructQuery == ""){					
			return $concurrentProgramId;
		}
		return $this->commitUpdate($concurrentProgramId);
	}	
		
	public function dropConcurrentProgramDetails($concurrentProgramId){
		if ($this->dropTableId ( $concurrentProgramId, false )) {
			$this->logOperation ( $concurrentProgramId, "Dropped" );
			return true;
		}
		return false;
	}
	
	public function activateConcurrentProgramDetails($concurrentProgramId){
		if ($this->activateTableId ( $concurrentProgramId )) {
			$this->logOperation ( $concurrentProgramId, "Activated" );
			return true;
		}
		return false;
	}
	
}
?>