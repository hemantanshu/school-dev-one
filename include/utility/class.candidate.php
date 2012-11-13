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

class Candidate extends general {   

    public function __construct() {
        parent::__construct();
    }
    //function related to the candidate details
    
    
    //functions related to the candidate class association
    //manipulating data in the utl candidate classes record
    public function setCandidateClassDetails($candidateId, $sessionId, $classId, $sectionId, $houseId, $sDate, $eDate){
    	
    	$counter = $this->getCounter("candidate_class");    	 
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
    
    public function getClassCandidateAssociationIds($classId, $active){
    	if($active){
    		if($active === 'all')
    			$sqlQuery = "SELECT a.id FROM utl_candidate_classes a, utl_candidate_registration b WHERE a.class_id = \"$classId\" AND a.candidate_id = b.candidate_id ORDER BY b.serial_number ASC ";
    		else
    			$sqlQuery = "SELECT a.id FROM utl_candidate_classes a, utl_candidate_registration b WHERE a.class_id = \"$classId\" AND a.candidate_id = b.candidate_id AND a.active = \"y\" ORDER BY b.serial_number ASC ";
    	}else
    		$sqlQuery = "SELECT a.id FROM utl_candidate_classes a, utl_candidate_registration b WHERE a.class_id = \"$classId\" AND a.candidate_id = b.candidate_id AND a.active != \"y\" ORDER BY b.serial_number ASC ";
    	
    	
    	return $this->getDataArray($this->processQuery($sqlQuery));
    }
    
    public function getClassCandidateIds($classId, $active){
    	if($active){
    		if($active === 'all')
    			$sqlQuery = "SELECT a.candidate_id FROM utl_candidate_classes a, utl_candidate_registration b WHERE a.class_id = \"$classId\" AND a.candidate_id = b.candidate_id ORDER BY b.serial_number ASC ";
    		else
    			$sqlQuery = "SELECT a.candidate_id FROM utl_candidate_classes a, utl_candidate_registration b WHERE a.class_id = \"$classId\" AND a.candidate_id = b.candidate_id AND a.active = \"y\" ORDER BY b.serial_number ASC ";
    	}else
    		$sqlQuery = "SELECT a.candidate_id FROM utl_candidate_classes a, utl_candidate_registration b WHERE a.class_id = \"$classId\" AND a.candidate_id = b.candidate_id AND a.active != \"y\" ORDER BY b.serial_number ASC ";
    	 
    	 
    	return $this->getDataArray($this->processQuery($sqlQuery));
    }
    
    public function getSessionCandidateAssociationIds($sessionId, $active){
    	if($active){
    		if($active === 'all')
    			$sqlQuery = "SELECT a.id FROM utl_candidate_classes a, utl_candidate_registration b WHERE a.session_id = \"$sessionId\" AND a.candidate_id = b.candidate_id ORDER BY b.serial_number ASC";
    		else
    			$sqlQuery = "SELECT a.id FROM utl_candidate_classes a, utl_candidate_registration b WHERE a.session_id = \"$sessionId\" AND a.candidate_id = b.candidate_id AND a.active = \"y\" ORDER BY b.serial_number ASC";
    	}else
    		$sqlQuery = "SELECT a.id FROM utl_candidate_classes a, utl_candidate_registration b WHERE a.session_id = \"$sessionId\" AND a.candidate_id = b.candidate_id AND a.active != \"y\" ORDER BY b.serial_number ASC";
    	
    	return $this->getDataArray($this->processQuery($sqlQuery));
    }

    public function getSectionCandidateAssociationIds($sectionId, $active){
        if($active){
            if($active === 'all')
                $sqlQuery = "SELECT a.id FROM utl_candidate_classes a, utl_candidate_registration b WHERE a.section_id = \"$sectionId\" AND a.candidate_id = b.candidate_id ORDER BY b.serial_number ASC ";
            else
                $sqlQuery = "SELECT a.id FROM utl_candidate_classes a, utl_candidate_registration b WHERE a.section_id = \"$sectionId\" AND a.candidate_id = b.candidate_id AND a.active = \"y\" ORDER BY b.serial_number ASC ";
        }else
            $sqlQuery = "SELECT a.id FROM utl_candidate_classes a, utl_candidate_registration b WHERE a.section_id = \"$sectionId\" AND a.candidate_id = b.candidate_id AND a.active != \"y\" ORDER BY b.serial_number ASC ";

        return $this->getDataArray($this->processQuery($sqlQuery));
    }
    
    public function getCandidate4Section($sectionId, $active){
    	$sqlQuery = "SELECT a.candidate_id FROM utl_candidate_classes a, utl_candidate_registration b WHERE a.section_id = \"$sectionId\" AND a.candidate_id = b.candidate_id AND a.active = \"y\" ORDER BY b.serial_number ASC ";
    	return $this->getDataArray($this->processQuery($sqlQuery));
    }
    
    //functions related to the candidate subject association	
    public function setCandidateSubject($candidateId, $sessionId, $subjectType, $subjectId){    	    	
    	$subjectIds = $this->getCandidateSubject($candidateId, $subjectType);
    	if($subjectIds[0] == ''){
    		$counter = $this->getCounter('candidateSubject');    		 
    		$sqlQuery = "INSERT INTO utl_candidate_subject_map
    						(id, candidate_id, session_id, subject_type_id, subject_id, last_update_date, last_updated_by, creation_date, created_by, active)
    						VALUES (\"$counter\", \"$candidateId\", \"$sessionId\", \"$subjectType\", \"$subjectId\", \"" . $this->getCurrentDateTime() . "\", \"" . $this->getLoggedUserId() . "\", \"" . $this->getCurrentDateTime() . "\", \"" . $this->getLoggedUserId() . "\", \"y\") ";    		 		
    		if($this->processQuery($sqlQuery, $counter)){
	    		$this->logOperation($counter, 'New record inserted');
	    		$this->logOperation($candidateId, 'New subject has been assigned');
    			return $counter;
    		}
    		return false;
    	}else{
    		if($subjectIds[1] == $subjectId)
    			return $subjectIds[0];
    		else {
    			$this->dropCandidateSubjectId($subjectIds[0]);
    			return $this->setCandidateSubject($candidateId, $sessionId, $subjectType, $subjectId);
    		}    			
    	}
    	return false;    		    	
    }
    
    public function getSubjectAssociationIds4SubjectTypeId($subjectType){
    	$sqlQuery = "SELECT id FROM utl_candidate_subject_map WHERE subject_type_id = \"$subjectType\" ";
    	return $this->getDataArray($this->processQuery($sqlQuery));
    }
    
    public function getCandidateSubject($candidateId, $subjectType){
    	$sqlQuery = "SELECT id, subject_id  
    					FROM utl_candidate_subject_map 
    					WHERE candidate_id = \"$candidateId\" 
    						AND subject_type_id = \"$subjectType\" 
    						AND active = \"y\" ";
    	return $this->processArray($sqlQuery);
    }
    
    public function getCandidateSessionSubjectIds($candidateId, $sessionId){
    	$sqlQuery = "SELECT id 
    					FROM utl_candidate_subject_map 
    					WHERE candidate_id = \"$candidateId\" 
    						AND session_id = \"$sessionId\" 
    						AND active = \"y\" ";
    	return $this->getDataArray($this->processQuery($sqlQuery));
    }
    
    public function dropCandidateSubjectId($id){
    	if($this->dropTableId($id, false)){
    		$this->logOperation($id, "The subject details for candidate has been dropped");
    		return true;
    	}
    	return false;
    }
    
    public function activateCandidateSubjectId($id){
    	if($this->activateTableId($id)){
    		$this->logOperation($id, "The subject details has been activated");
    		return true;
    	}
    }
    
    
    
    
    
    
}
?>