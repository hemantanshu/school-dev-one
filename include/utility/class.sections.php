<?php

/**
 * This class will hold the functionalities related to the room details
 * This class extends the general class
 * @author Hemant Kumar Sah
 * @category Global
 * @license Support-Gurukul
 * @version 1.0.0
 */
require_once 'config.php';
require_once BASE_PATH.'include/global/class.loggedInfo.php';

class sections extends loggedInfo {

    public function __construct() {
        parent::__construct();
    }
    
    public function getClassIds($sessionId, $active){
    	if($active){
    		if($active === 'all')
    			$sqlQuery = "SELECT id
    							FROM utl_class_details 
    							WHERE session_id = \"$sessionId\" 
    							ORDER BY level ASC ";
    		else 
    			$sqlQuery = "SELECT id
    							FROM utl_class_details 
    							WHERE session_id = \"$sessionId\" 
    								AND active = \"y\" 
    							ORDER BY level ASC ";
    	}else
    		$sqlQuery = "SELECT id
    							FROM utl_class_details 
    							WHERE session_id = \"$sessionId\" 
    								AND active != \"y\" 
    							ORDER BY level ASC ";
    	return $this->getDataArray($this->processQuery($sqlQuery));
    }

    public function getClassSectionIds($classId, $active) {
        if ($active) {
            if ($active === 'all')
                $sqlQuery = "SELECT id 
                                FROM utl_class_sections
                                WHERE class_id = \"$classId\" 
                                ORDER BY section_name ASC";
            else
                $sqlQuery = "SELECT id 
                                FROM utl_class_sections
                                WHERE active = \"y\"
                                	AND class_id = \"$classId\"
                                ORDER BY section_name ASC";
        }else
            $sqlQuery = "SELECT id 
                            FROM utl_class_sections
                            WHERE active != \"y\"
                            	AND class_id = \"$classId\"
                            ORDER BY section_name ASC";
        
        return $this->getDataArray($this->processQuery($sqlQuery));
    }

    public function getClassSectionSearchIds($classId, $str, $active) {
    	if ($active) {
    		if ($active === 'all')
    			$sqlQuery = "SELECT id
				    			FROM utl_class_sections				    			
				    			WHERE class_id = \"$classId\"
				    				AND section_name LIKE \"%$str%\"
				    			ORDER BY section_name ASC";
    		else
    			$sqlQuery = "SELECT id
				    			FROM utl_class_sections
				    			WHERE active = \"y\"
				    				AND section_name LIKE \"%$str%\"
					    			AND class_id = \"$classId\"
					    		ORDER BY section_name ASC";
    	}else
		    	$sqlQuery = "SELECT id
						    	FROM utl_class_sections
						    	WHERE active != \"y\"
						    		AND class_id = \"$classId\"
						    		AND section_name LIKE \"%$str%\"
						    	ORDER BY section_name ASC";
    	return $this->getDataArray($this->processQuery($sqlQuery));
    }
    public function getSectionIdDetails($sectionId) {
        return $this->getTableIdDetails($sectionId);
    }

    public function getSectionName($sectionId){
    	return $this->getValue('section_name', 'utl_class_sections', 'id', $sectionId);
    }
    
    public function dropSectionDetails($sectionId) {
        if($this->dropTableId($sectionId, false)){
            $this->logOperation($sectionId, "The Section Details Has Been Dropped");
            return true;
        }
        return false;
    }
    
    

    public function activateSectionDetails($sectionId) {
        if($this->activateTableId($sectionId)){
            $this->logOperation($sectionId, "The Section Details Has Been Activated");
            return true;
        }
        return false;
    }

    public function setSectionDetails($classId, $sessionId, $sectionName, $studentCapacity, $roomId, $coordinatorId = '') {
    	$classSectionId = $this->isClassSectionDetailsVacant($classId, $sessionId);
    	if($classSectionId){
    		$sqlQuery = "UPDATE utl_class_sections 
    						SET section_name = \"$sectionName\", 
    							student_capacity =\"$studentCapacity\", 
    							room_id = \"$roomId\" 
    							section_coordinator_id = \"$coordinatorId\"
    						WHERE id = \"$classSectionId\" ";
    		
    		if($this->processQuery($sqlQuery, $classSectionId))
    			return $classSectionId;
    		return false;
    	}
    	
        $counter = $this->getCounter('sections');
        $sqlQuery = "INSERT INTO utl_class_sections 
                            (id, class_id, session_id, section_name, student_capacity, room_id, section_coordinator_id, last_update_date, last_updated_by, creation_date, created_by, active) 
                            VALUES (\"$counter\", \"$classId\",  \"$sessionId\", \"$sectionName\",  \"$studentCapacity\",  \"$roomId\", \"$coordinatorId\", \"" . $this->getCurrentDateTime() . "\", \"" . $this->getLoggedUserId() . "\", \"" . $this->getCurrentDateTime() . "\", \"" . $this->getLoggedUserId() . "\", \"y\")";
        if ($this->processQuery($sqlQuery, $counter)) {
            $this->logOperation($counter, "New Section Has Been Defined");
            return $counter;
        }
        return false;
    }
    
    public function getClassName4Section($sectionId){
    	$sqlQuery = "SELECT a.class_name FROM utl_class_name a, utl_class_details b, utl_class_sections c WHERE c.class_id = b.id AND b.class_id = a.id AND c.id = \"$sectionId\" ";
    	$sqlQuery = $this->processArray($sqlQuery);
    	return $sqlQuery[0];
    }
    
    public function isClassSectionDetailsVacant($classId, $sessionId){
    	$sqlQuery = "SELECT id 
    					FROM utl_class_sections 
    					WHERE class_id = \"$classId\" 
    						AND session_id = \"$sessionId\"
    						AND section_name = \"\" ";
    	$sqlQuery = $this->processArray($sqlQuery);
    	if($sqlQuery[0] != "")
    		return $sqlQuery[0];
    	
    	return false;
    	
    }
    
    public function commitSectionDetailsUpdate($sectionId){
        if ($this->sqlConstructQuery == "")
            return $sectionId;
        $this->commitUpdate($sectionId);
    }
    
    
    //manipulating data in the utl candidate classes record
    public function setCandidateClassDetails($candidateId, $classId, $sectionId, $houseId, $sDate, $eDate){
    	$counter = $this->getCounter("candidate_class");
    	$sessionId = $_SESSION['currentClassSessionId'];
    	$sqlQuery = "INSERT 
    					INTO utl_candidate_classes 
    						(id, candidate_id, session_id, class_id, section_id, house_id, start_date, end_date, last_update_date, last_updated_by, creation_date, created_by, active)
    					VALUES 
    						(\"$counter\", \"$candidateId\", \"$sessionId\", \"$classId\", \"$sectionId\", \"$houseId\", \"$sDate\", \"$eDate\", \"" . $this->getCurrentDateTime() . "\", \"" . $this->getLoggedUserId() . "\", \"" . $this->getCurrentDateTime() . "\", \"" . $this->getLoggedUserId() . "\", \"y\")";
    	if($this->processQuery($sqlQuery, $counter)){
    		$this->logOperation($candidateId, "Class Information has Been Inserted");
    		$this->logOperation($counter, "Candidate Class Information has been inserted");
    		return $counter;
    	}
    	return false;
    }
    
    
    //manipulating data in the utl class details table
    public function setClassDetails($className, $sessionId, $level, $classCoordinator){
    	$counter1 = $this->getCounter("className");
    	
    	$sqlQuery = "INSERT
    					INTO utl_class_name
				    	(id, class_name, last_update_date, last_updated_by, creation_date, created_by, active)
				    	VALUES (\"$counter1\", \"$className\", \"" . $this->getCurrentDateTime() . "\", \"" . $this->getLoggedUserId() . "\", \"" . $this->getCurrentDateTime() . "\", \"" . $this->getLoggedUserId() . "\", \"y\")";
    	if($this->processQuery($sqlQuery, $counter1)){
    		$counter = $this->getCounter('classDetails');
    		 
    		$sqlQuery = "INSERT
				    		INTO utl_class_details
				    		(id, class_id, session_id, level, class_coordinator_id, last_update_date, last_updated_by, creation_date, created_by, active)
				    		VALUES (\"$counter\", \"$counter1\", \"$sessionId\", \"$level\", \"$classCoordinator\", \"" . $this->getCurrentDateTime() . "\", \"" . $this->getLoggedUserId() . "\", \"" . $this->getCurrentDateTime() . "\", \"" . $this->getLoggedUserId() . "\", \"y\")";
    		 
    		if($this->processQuery($sqlQuery, $counter)){
	    		$this->logOperation($counter, 'The class details has been setup');
	    		return $counter;
    		}	
    	}
    	
    	
    	return false;
    }
    
    public function getClassDetailsId($classId, $sessionId){
    	$sqlQuery = "SELECT id 
    						FROM utl_class_details  
    						WHERE session_id = \"$sessionId\" 
    							AND class_id = \"$classId\" ";
    	$sqlQuery = $this->processArray($sqlQuery);
    	if($sqlQuery[0] != '')
    		return $sqlQuery[0];
    	return false;
    }
    
    public function getCurrentSessionClassNameIds($hint, $active){
    	$sessionId = $_SESSION['currentClassSessionId'];
    	if($active){
    		if($active === 'all')
    			$sqlQuery = "SELECT a.id, b.class_name FROM utl_class_details a, utl_class_name b WHERE b.class_name LIKE \"%$hint%\" AND b.id = a.class_id AND a.session_id = \"$sessionId\" ";
    		else
    			$sqlQuery = "SELECT a.id, b.class_name FROM utl_class_details a, utl_class_name b WHERE b.class_name LIKE \"%$hint%\" AND b.id = a.class_id AND a.session_id = \"$sessionId\" AND b.active = \"y\" AND a.active = \"y\" ORDER BY a.level";    		
    	}else
    		$sqlQuery = "SELECT a.id, b.class_name FROM utl_class_details a, utl_class_name b WHERE b.class_name LIKE \"%$hint%\" AND b.id = a.class_id AND a.session_id = \"$sessionId\" AND (b.active != \"y\" OR a.active != \"y\")";
    	
    	return $this->getDataArray($this->processQuery($sqlQuery), 2);
    }
    
    public function commitClassDetailsUpdate($classDetailsId){
    	if ($this->sqlConstructQuery == "")
    		return $classDetailsId;
    	
    	return $this->commitUpdate($classDetailsId);
    }
    
    public function commitClassNameDetailsUpdate($classId){
    	if ($this->sqlConstructQuery == "")
    		return $classId;
    	 
	    	$this->sqlConstructQuery .= ", last_update_date=\"" . $this->getCurrentDateTime() . "\", last_updated_by=\"" . $this->getLoggedUserId() . "\"";
	    	$sqlQuery = "UPDATE utl_class_name
	    					SET $this->sqlConstructQuery
					    	WHERE id = \"$classId\" ";
			$this->sqlConstructQuery = "";
	    	 
	    	if ($this->processQuery($sqlQuery, $classId)) {
	    		$this->logOperation($classId, "The Class Details Has Been Updated");
	    	return true;
    	}
    	return false;
    }

}

?>