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
require_once BASE_PATH . 'include/global/class.general.php';

class Examination extends general {
	
	public function __construct() {
		parent::__construct ();
	}
	
	public function setExaminationType($examinationName, $sessionId, $examinationDescription, $startDate, $endDate) {
		$counter = $this->getCounter ( 'examinationDefinitions' );
		$sqlQuery = "INSERT INTO exam_examination_definitions 
    					(id, session_id, examination_name, examination_description, start_date, end_date, last_update_date, last_updated_by, creation_date, created_by, active) 
    					VALUES (\"$counter\", \"$sessionId\", \"$examinationName\", \"$examinationDescription\", \"$startDate\", \"$endDate\", \"" . $this->getCurrentDateTime () . "\", \"" . $this->getLoggedUserId () . "\", \"" . $this->getCurrentDateTime () . "\", \"" . $this->getLoggedUserId () . "\", \"y\") ";
		if ($this->processQuery ( $sqlQuery, $counter )) {
			$this->logOperation ( $counter, 'New Examination Entry Made' );
			return $counter;
		}
		return false;
	}
	
	public function getExaminationType($sessionId, $active) {
		if ($active) {
			if ($active === 'all')
				$sqlQuery = "SELECT id 
    							FROM exam_examination_definitions 
    							WHERE session_id = \"$sessionId\" ";
			else
				$sqlQuery = "SELECT id
				    			FROM exam_examination_definitions
				    			WHERE session_id = \"$sessionId\"
				    				AND ( end_date >= \"".$this->getCurrentDate()."\" OR end_date = \"0000-00-00\" )				    				
    								AND active = \"y\" ";
		} else
			$sqlQuery = "SELECT id
				    		FROM exam_examination_definitions
				    		WHERE session_id = \"$sessionId\" 
				    			AND (end_date < \"".$this->getCurrentDate()."\"
    							OR active != \"y\" )";
		return $this->getDataArray ( $this->processQuery ( $sqlQuery ) );
	}
	
	public function getExaminationRecords($sessionId, $active) {
		if ($active) {
			if ($active === 'all')
				$sqlQuery = "SELECT id
								FROM exam_examination_definitions
								WHERE session_id = \"$sessionId\" ";
			else
				$sqlQuery = "SELECT id
								FROM exam_examination_definitions
								WHERE session_id = \"$sessionId\"
									AND active = \"y\" ";
		} else
			$sqlQuery = "SELECT id
				    		FROM exam_examination_definitions
					    		WHERE session_id = \"$sessionId\"
					    			AND active != \"y\" )";
		return $this->getDataArray ( $this->processQuery ( $sqlQuery ) );
	}
	
	
	public function getExaminationForResultPreparation($sessionId){
		$sqlQuery = "SELECT id
						FROM exam_examination_definitions
						WHERE session_id = \"$sessionId\"						
						AND active = \"y\" ";
		return $this->getDataArray($this->processQuery($sqlQuery));
	}
	
	public function commitExaminationTypeUpdate($examinationId) {
		if ($this->sqlConstructQuery == "")
			return $examinationId;
		
		return $this->commitUpdate($examinationId);
	}
	
	public function dropExaminationType($examinationId) {
		if ($this->dropTableId ( $examinationId, false )) {
			$this->logOperation ( $examinationId, "The examination details Has Been Dropped" );
			return true;
		}
		return false;
	}
	
	public function activateExaminationType($examinationId) {
		if ($this->activateTableId ( $examinationId )) {
			$this->logOperation ( $examinationId, "The examination  Details Has Been Activated" );
			return true;
		}
		return false;
	}	
	
	
	//functions related to the examination dates setup
	public function setExaminationSubjectDate($sessionId, $examinationId, $classId, $sectionId, $subjectId, $subjectComponentId, $examinationName, $examinationDate, $startTime, $duration, $markingType, $subjectCredit, $maxMark, $passMark, $submissionDate, $submissionOfficer, $verificationDate, $verificationOfficer){
		$counter = $this->getCounter('examinationDates');
		$sqlQuery = "INSERT INTO exam_examination_dates 
						(id, session_id, examination_id, class_id, section_id, subject_id, subject_component_id, examination_name, examination_date, examination_start_time, examination_duration, marking_type, subject_credit, max_mark, pass_mark, mark_submission_date, mark_submission_officer, mark_verification_date, mark_verification_officer, last_update_date, last_updated_by, creation_date, created_by, active) 
						VALUES (\"$counter\", \"$sessionId\", \"$examinationId\", \"$classId\", \"$sectionId\", \"$subjectId\", \"$subjectComponentId\", \"$examinationName\", \"$examinationDate\", \"$startTime\", \"$duration\", \"$markingType\", \"$subjectCredit\", \"$maxMark\", \"$passMark\", \"$submissionDate\", \"$submissionOfficer\", \"$verificationDate\", \"$verificationOfficer\", \"" . $this->getCurrentDateTime () . "\", \"" . $this->getLoggedUserId () . "\", \"" . $this->getCurrentDateTime () . "\", \"" . $this->getLoggedUserId () . "\", \"y\") ";
		
		if($this->processQuery($sqlQuery, $counter)){
			$this->logOperation($counter, 'New examination date has been setup');
			return $counter;
		}
		return false;
	}	
	
	public function getExaminationSubjectDateIds($sessionId, $examinationId, $sectionId, $type){
		if($type){
			if($type === 'all')
				$sqlQuery = "SELECT id FROM exam_examination_dates 
								WHERE session_id = \"$sessionId\"
									AND examination_id = \"$examinationId\"
									AND section_id = \"$sectionId\" ";
			else
				$sqlQuery = "SELECT id FROM exam_examination_dates
								WHERE session_id = \"$sessionId\"
									AND examination_id = \"$examinationId\"
									AND section_id = \"$sectionId\"
									AND active = \"y\" ";
		}else{
			$sqlQuery = "SELECT id FROM exam_examination_dates
							WHERE session_id = \"$sessionId\"
								AND examination_id = \"$examinationId\"
								AND section_id = \"$sectionId\"
								AND active != \"y\" ";
		}
		
		return $this->getDataArray($this->processQuery($sqlQuery));
	}
	
	public function commitExaminationSubjectDateUpdate($examinationSubjectId){
		if ($this->sqlConstructQuery == "")
			return false;
		
		return $this->commitUpdate($examinationSubjectId);
	}
	
	public function dropExaminationSubjectDate($examinationSubjectId){
		if ($this->dropTableId ( $examinationSubjectId, false )) {
			$sqlQuery = "UPDATE exam_mark_records SET active = \"\" WHERE exam_date_id = \"$examinationSubjectId\" ";
			$this->processQuery($sqlQuery);
			$this->logOperation ( $examinationSubjectId, "The examination subject date details Has Been Dropped" );
			return true;
		}
		return false;
	}
	
	public function activateExaminationSubjectDate($examinationSubjectId){
		if ($this->activateTableId ( $examinationSubjectId )) {
			$this->logOperation ( $examinationSubjectId, "The examination  subject date Details Has Been Activated" );
			return true;
		}
		return false;
	}
	
	
	//functions related to the candidate exam exclusion
	public function setExaminationCandidateExclusion($examinationSubjectId, $candidateId, $reason){
		$counter = $this->getCounter('examCandidateExclusion');
		$sqlQuery = "INSERT INTO exam_candidate_exclusion 
						(id, examination_id, candidate_id, exclusion_reason, last_update_date, last_updated_by, creation_date, created_by, active) 
						VALUES (\"$counter\", \"$examinationSubjectId\", \"$candidateId\", \"$reason\", \"" . $this->getCurrentDateTime () . "\", \"" . $this->getLoggedUserId () . "\", \"" . $this->getCurrentDateTime () . "\", \"" . $this->getLoggedUserId () . "\", \"y\") ";
		if($this->processQuery($sqlQuery, $counter)){
			$this->logOperation($examinationSubjectId, "A candidate has been debarred from exam");
			$this->logOperation($candidateId, "The candidate has been debarred from subject exam due to ".$reason);
			$this->logOperation($counter, "A candidate entry has been made");
			return $counter;
		}
		return false;
	}	
	
	public function commitExaminationCandidateExclusionUpdate($exclusionId){
		if ($this->sqlConstructQuery == "")
			return $exclusionId;
		
		return $this->commitUpdate($exclusionId);
	}
	
	public function dropExaminationCandidateExclusion($exclusionId){
		if ($this->dropTableId ( $exclusionId, false )) {
			$this->logOperation ( $exclusionId, "The examination candidate exclusion has been removed" );
			return true;
		}
		return false;
	}
	
	public function activateExaminationCandidateExclusion($exclusionId){
		if ($this->activateTableId ( $exclusionId )) {
			$this->logOperation ( $exclusionId, "The examination  candidate exclusion has been activated" );
			return true;
		}
		return false;
	}
	
	//functions related to the subject combination mapping
	public function setSubjectCombination($subjectId, $subjectComponent, $order){
		if($this->checkSubjectCombination($subjectId, $subjectComponent)){
			$counter = $this->getCounter('examSubjectComponents');
			
			$sqlQuery = "INSERT INTO exam_subject_components 
							(id, subject_id, subject_component_name, subject_component_order, last_update_date, last_updated_by, creation_date, created_by, active) 
							VALUES (\"$counter\", \"$subjectId\", \"$subjectComponent\", \"$order\", \"" . $this->getCurrentDateTime () . "\", \"" . $this->getLoggedUserId () . "\", \"" . $this->getCurrentDateTime () . "\", \"" . $this->getLoggedUserId () . "\", \"y\") ";
			if($this->processQuery($sqlQuery, $counter)){
				$this->logOperation($subjectId, "The subject type named $subjectComponent has been added");
				$this->logOperation($counter, "New subject type has been created");
				
				return $counter;
			}
			return false;
		}
		return $subjectId;
	}
	
	public function checkSubjectCombination($subjectId, $subjectComponent){
		$sqlQuery = "SELECT id 
						FROM exam_subject_components
							WHERE subject_id = \"$subjectId\" && 
								UPPER(subject_component_name) = UPPER(\"$subjectComponent\") ";
		
		$sqlQuery = $this->processQuery($sqlQuery);
		if(mysql_num_rows($sqlQuery) == 0)
			return true;
		return false;	
	}
	
	public function getSubjectCombinationIds($subjectId, $active){
		if($active){
			if($active === 'all')
				$sqlQuery = "SELECT id FROM exam_subject_components WHERE subject_id = \"$subjectId\" ";
			else
				$sqlQuery = "SELECT id FROM exam_subject_components WHERE subject_id = \"$subjectId\" AND active = \"y\" ";
		}else
			$sqlQuery = "SELECT id FROM exam_subject_components WHERE subject_id = \"$subjectId\" AND active != \"y\"";
			
		return $this->getDataArray($this->processQuery($sqlQuery));
	}
	
	public function getActiveSubjectCombinationNames($subjectId){
		$sqlQuery = "SELECT id, subject_component_name FROM exam_subject_components WHERE subject_id = \"$subjectId\" ";
		return $this->getDataArray($this->processQuery($sqlQuery), 2);		
	}
	
	public function commitSubjectCombinationDetailsUpdate($subjectCombinationId){
		if ($this->sqlConstructQuery == "")
			return $subjectCombinationId;
		
		return $this->commitUpdate($subjectCombinationId);
	}
	
	public function dropSubjectCombinationDetails($subjectCombinationId){
		if ($this->dropTableId ( $subjectCombinationId, false )) {
			$this->logOperation ( $subjectCombinationId, "The subject combination record has been removed" );
			return true;
		}
		return false;
	}
	
	public function activateSubjectCombinationDetails($subjectCombinationId){
		if ($this->activateTableId ( $subjectCombinationId )) {
			$this->logOperation ( $subjectCombinationId, "The subject combination record has been activated" );
			return true;
		}
		return false;
	}
	
	//functions related to the subject class exam mapping
	public function getClassSubjectIdDetails($classId){
		$sqlQuery = "SELECT a.id, a.subject_code, a.subject_name FROM utl_subject_details a, utl_class_subject_type b, utl_class_subjects_map c
						WHERE c.subject_id = a.id AND c.subject_type_id = b.id AND b.class_id = \"$classId\" AND b.active = \"y\" ORDER BY a.subject_name ASC";
							
		return $this->getDataArray($this->processQuery($sqlQuery), 3);
	}
	
	
	
}
?>