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

class Result extends general {
	
	public function __construct() {
		parent::__construct ();
	}
	
	public function setResultDefinition($sessionId, $name, $displayName, $comments, $gradingType){
		$counter = $this->getCounter("resultDefinition");
		
		$sqlQuery = "INSERT INTO exam_result_definitions 
    					(id, session_id, result_name, display_name, description, result_type, last_update_date, last_updated_by, creation_date, created_by, active) 
    					VALUES (\"$counter\", \"$sessionId\", \"$name\", \"$displayName\", \"$comments\", \"$gradingType\", \"" . $this->getCurrentDateTime () . "\", \"" . $this->getLoggedUserId () . "\", \"" . $this->getCurrentDateTime () . "\", \"" . $this->getLoggedUserId () . "\", \"y\") ";
		if ($this->processQuery ( $sqlQuery, $counter )) {
			$this->logOperation ( $counter, 'New Result Entry Made' );
			return $counter;
		}
		return false;		
	}
	
	public function getResultDefinitions($sessionId, $active) {
		if ($active) {
			if ($active === 'all')
				$sqlQuery = "SELECT id
					FROM exam_result_definitions
					WHERE session_id = \"$sessionId\" ";
			else
				$sqlQuery = "SELECT id
					FROM exam_result_definitions
					WHERE session_id = \"$sessionId\"
					AND active = \"y\" ";
		} else
			$sqlQuery = "SELECT id
					FROM exam_result_definitions
					WHERE session_id = \"$sessionId\"
					AND active != \"y\" ";
		
		return $this->getDataArray ( $this->processQuery ( $sqlQuery ) );
	}
	
	
	public function commitResultTypeUpdate($resultId) {
		if ($this->sqlConstructQuery == "")
			return $resultId;
	
		return $this->commitUpdate($resultId);
	}
	
	public function dropResultType($resultId) {
		if ($this->dropTableId ( $resultId, false )) {
			$this->logOperation ( $resultId, "The result details Has Been Dropped" );
			return true;
		}
		return false;
	}
	
	public function activateResultType($resultId) {
		if ($this->activateTableId ( $resultId )) {
			$this->logOperation ( $resultId, "The result  Details Has Been Activated" );
			return true;
		}
		return false;
	}
	
	
	//functions related to the result accessment

    public function setResultAssessment($sessionId, $resultId, $classId, $sectionId, $name, $order, $markingScheme){
        $counter = $this->getCounter("resultAssessment");

        $sqlQuery = "INSERT INTO exam_result_assessment
    					(id, session_id, result_id, class_id, section_id, assessment_name, assessment_order, marking_scheme, last_update_date, last_updated_by, creation_date, created_by, active)
    					VALUES (\"$counter\", \"$sessionId\", \"$resultId\", \"$classId\", \"$sectionId\", \"$name\", \"$order\", \"$markingScheme\", \"" . $this->getCurrentDateTime () . "\", \"" . $this->getLoggedUserId () . "\", \"" . $this->getCurrentDateTime () . "\", \"" . $this->getLoggedUserId () . "\", \"y\") ";
        if ($this->processQuery ( $sqlQuery, $counter )) {
            $this->logOperation ( $counter, 'New Result Assessment Entry Made' );
            return $counter;
        }
        return false;
    }

    public function getResultAssessment($resultId, $sectionId, $active) {
        if ($active) {
            if ($active === 'all')
                $sqlQuery = "SELECT id
					FROM exam_result_assessment
					WHERE result_id = \"$resultId\" 
						AND section_id = \"$sectionId\" 
            			ORDER BY assessment_order ASC";
            else
                $sqlQuery = "SELECT id
					FROM exam_result_assessment
					WHERE result_id = \"$resultId\"
						AND section_id = \"$sectionId\"
					AND active = \"y\"
            		ORDER BY assessment_order ASC ";
        } else
            $sqlQuery = "SELECT id
					FROM exam_result_assessment
					WHERE result_id = \"$resultId\"
						AND section_id = \"$sectionId\"
					AND active != \"y\" 
        			ORDER BY assessment_order ASC";
        
        return $this->getDataArray ( $this->processQuery ( $sqlQuery ) );
    }


    public function commitResultAssessmentUpdate($assessmentId) {
        if ($this->sqlConstructQuery == "")
            return $assessmentId;

        $this->sqlConstructQuery .= ", last_update_date=\"" . $this->getCurrentDateTime () . "\", last_updated_by=\"" . $this->getLoggedUserId () . "\"";
        $sqlQuery = "UPDATE exam_result_assessment
					SET $this->sqlConstructQuery
					WHERE id = \"$assessmentId\" ";

        $this->sqlConstructQuery = "";
        if ($this->processQuery ( $sqlQuery, $assessmentId )) {
            $this->logOperation ( $assessmentId, "The Result Assessment Type Details Has Been Updated" );
            return true;
        }
        return false;
    }

    public function dropResultAssessment($assessmentId) {
        if ($this->dropTableId ( $assessmentId, false )) {
            $this->logOperation ( $assessmentId, "The result Assessment details Has Been Dropped" );
            return true;
        }
        return false;
    }

    public function activateResultAssessment($assessmentId) {
        if ($this->activateTableId ( $assessmentId )) {
            $this->logOperation ( $assessmentId, "The result assessment  Details Has Been Activated" );
            return true;
        }
        return false;
    }
    
    //functions relatd to assessment subject setup
    public function setAssessmentSubject($assessmentId, $activityName, $activityOrder, $subjectId, $submissionDate, $submissionOfficer, $verificationDate, $verificationOfficer){
    	$counter = $this->getCounter("assessmentSubject");
    	$details = $this->getTableIdDetails($assessmentId);
    	$sqlQuery = "INSERT INTO exam_assessment_subjects (id, session_id, result_id, class_id, section_id, assessment_id, activity_name, activity_order, subject_id, marking_type, mark_submission_date, mark_submission_officer, mark_verification_date, mark_verification_officer, last_update_date, last_updated_by, creation_date, created_by, active) 
    					VALUES (\"$counter\", \"".$details['session_id']."\", \"".$details['result_id']."\", \"".$details['class_id']."\", \"".$details['section_id']."\", \"$assessmentId\", \"$activityName\", \"$activityOrder\", \"$subjectId\", \"".$details['marking_scheme']."\", \"$submissionDate\", \"$submissionOfficer\", \"$verificationDate\", \"$verificationOfficer\", \"" . $this->getCurrentDateTime () . "\", \"" . $this->getLoggedUserId () . "\", \"" . $this->getCurrentDateTime () . "\", \"" . $this->getLoggedUserId () . "\", \"y\") ";
    	if($this->processQuery($sqlQuery, $counter)){
    		$this->logOperation($counter, "New activity has been added");
    		return $counter;
    	}
    	return false;
    }
    
    public function commitAssessmentSubjectUpdate($activityId){
    	if ($this->sqlConstructQuery == "")
    		return $activityId;
    	
    	return $this->commitUpdate($activityId);
    }
    
    public function getActivityIds($assessmentId, $active){
    	if($active){
    		if($active == "all")
    			$sqlQuery = "SELECT id FROM exam_assessment_subjects WHERE assessment_id = \"$assessmentId\" ORDER BY activity_order ASC ";
    		else
    			$sqlQuery = "SELECT id FROM exam_assessment_subjects WHERE assessment_id = \"$assessmentId\" AND active = \"y\" ORDER BY activity_order ASC ";
    	}else
    		$sqlQuery = "SELECT id FROM exam_assessment_subjects WHERE assessment_id = \"$assessmentId\" AND active != \"y\" ORDER BY activity_order ASC ";
    	
    	return $this->getDataArray($this->processQuery($sqlQuery));
    }
    
    public function dropAssessmentActivity($assessmentId) {
    	if ($this->dropTableId ( $assessmentId, false )) {
    		$this->logOperation ( $assessmentId, "The result Assessment details Has Been Dropped" );
    		return true;
    	}
    	return false;
    }
    
    public function activateAssessmentActivity($assessmentId) {
    	if ($this->activateTableId ( $assessmentId )) {
    		$this->logOperation ( $assessmentId, "The result assessment  Details Has Been Activated" );
    		return true;
    	}
    	return false;
    }
    
    //functions related to the result setup examination
    public function setResultSetup($sessionId, $resultId, $displayName, $examinationId, $weightAge, $displayOrder){
    	$counter = $this->getCounter("resultSetup");

        $sqlQuery = "INSERT INTO exam_result_setup
    					(id, session_id, result_id, display_name, examination_id, weightage, display_order, last_update_date, last_updated_by, creation_date, created_by, active)
    					VALUES (\"$counter\", \"$sessionId\", \"$resultId\", \"$displayName\", \"$examinationId\", \"$weightAge\", \"$displayOrder\", \"" . $this->getCurrentDateTime () . "\", \"" . $this->getLoggedUserId () . "\", \"" . $this->getCurrentDateTime () . "\", \"" . $this->getLoggedUserId () . "\", \"y\") ";
        if ($this->processQuery ( $sqlQuery, $counter )) {
            $this->logOperation ( $counter, 'New' );
            return $counter;
        }
        return false; 
    }
    
    public function getResultSetupIds($resultId, $active){
    	if($active){
    		if($active === "all")
    			$sqlQuery = "SELECT id FROM exam_result_setup WHERE result_id = \"$resultId\" ORDER BY display_order ASC";
    		else
    			$sqlQuery = "SELECT id FROM exam_result_setup WHERE result_id = \"$resultId\" AND active = \"y\" ORDER BY display_order ASC";
    	}else{
    		$sqlQuery = "SELECT id FROM exam_result_setup WHERE result_id = \"$resultId\" AND active != \"y\" ORDER BY display_order ASC";
    	}
    	
    	
    	return $this->getDataArray($this->processQuery($sqlQuery));
    }
    
    public function commitResultSetupUpdate($resultSetupId){
    	if ($this->sqlConstructQuery == "")
    		return $resultSetupId;
    	 
    	return $this->commitUpdate($resultSetupId);
    }
    
    public function dropResultSetupDetails($resultSetupId) {
    	if ($this->dropTableId ( $resultSetupId, false )) {
    		$this->logOperation ( $resultSetupId, "Dropped" );
    		return true;
    	}
    	return false;
    }
    
    public function activateResultSetupDetails($resultSetupId) {
    	if ($this->activateTableId ( $resultSetupId )) {
    		$this->logOperation ( $resultSetupId, "Activated" );
    		return true;
    	}
    	return false;
    }
    
    
    
    public function getResultSubjectIds($resultId, $sectionId){
    	$sqlQuery = "SELECT distinct(b.subject_id) 
    					FROM exam_result_setup a, exam_examination_dates b, utl_subject_details c 
    					WHERE a.examination_id = b.examination_id
    					AND a.result_id = \"$resultId\"
    					AND b.section_id = \"$sectionId\"
    					AND c.id = b.subject_id
    					AND b.active = \"y\"
    					AND a.active = \"y\"  
    					
    					ORDER BY c.subject_order ASC";
    	
    	return $this->getDataArray($this->processQuery($sqlQuery));
    }
    
    public function getResultSubjectComponentIds($resultId, $sectionId){
    	$sqlQuery = "SELECT b.subject_id, b.subject_component_id
				    	FROM exam_result_setup a, 
				    		exam_examination_dates b, 
				    		utl_subject_details c,
				    		exam_subject_components d
				    	WHERE a.examination_id = b.examination_id
					    	AND a.result_id = \"$resultId\"
					    	AND b.section_id = \"$sectionId\"
					    	AND c.id = b.subject_id
					    	AND d.id = b.subject_component_id
					    	AND b.active = \"y\"
					    	AND a.active = \"y\"
					    					
					    GROUP BY b.subject_id, b.subject_component_id    		
				    	ORDER BY c.subject_order ASC, d.subject_component_order ASC, c.subject_code  ";
    	 
    	return $this->getDataArray($this->processQuery($sqlQuery), 2);
    }
    
    public function getResultExaminationSubjectTotal($examinationId, $sectionId, $subjectId){
    	$sqlQuery = "SELECT SUM(max_mark) 
    					FROM exam_examination_dates 
    					WHERE examination_id = \"$examinationId\" 
    						AND section_id = \"$sectionId\" 
    						AND subject_id = \"$subjectId\" 
    						AND active = \"y\" ";
    	return $this->processSingleElementQuery($sqlQuery);
    }

    public function getResultExaminationSubjectComponentTotal($examinationId, $sectionId, $subjectId, $componentId){
    	$sqlQuery = "SELECT SUM(max_mark)
				    	FROM exam_examination_dates
				    	WHERE examination_id = \"$examinationId\"
					    	AND section_id = \"$sectionId\"
					    	AND subject_id = \"$subjectId\"
					    	AND subject_component_id = \"$componentId\"
					    	AND active = \"y\" ";
    	return $this->processSingleElementQuery($sqlQuery);
    }

   
    
    
    public function getTotalMark4Result($resultId, $sectionId){
    	$sqlQuery = "SELECT sum(max_mark) 
    					FROM exam_result_setup a, exam_examination_dates b 
    					WHERE a.examination_id = b.examination_id
    					AND a.result_id = \"$resultId\"
    					AND b.section_id = \"$sectionId\" 
    					AND b.active = \"y\"
    					AND a.active = \"y\"  ";
    	
    	$sqlQuery = $this->processArray($sqlQuery);
    	return $sqlQuery[0];
    }
    
    public function getCandidateSubjectMark($candidateId, $examinationId, $subjectId){
    	$sqlQuery = "SELECT sum(absolute_mark) FROM exam_mark_records WHERE candidate_id = \"$candidateId\" AND examination_id = \"$examinationId\" AND subject_id = \"$subjectId\" and active = \"y\" ";    	
    	$sqlQuery = $this->processArray($sqlQuery);
    	if($sqlQuery[0] != "")
    		return $sqlQuery[0];
    	return false;    	
    }
    
    public function getCandidateSubjectComponentMark($candidateId, $examinationId, $subjectId, $componentId){
    	$sqlQuery = "SELECT sum(absolute_mark) FROM exam_mark_records 
    					WHERE candidate_id = \"$candidateId\" 
    						AND examination_id = \"$examinationId\" 
    						AND subject_id = \"$subjectId\"
    						AND subject_component_id = \"$componentId\" 
    						AND active = \"y\" ";
    	$sqlQuery = $this->processArray($sqlQuery);
    	if($sqlQuery[0] != "")
    		return $sqlQuery[0];
    	return false;
    }
    
    public function checkCandidateSubject($candidateId, $subjectId, $sectionId){
    	
    	
    	$sqlQuery = "SELECT a.id, a.subject_type, c.session_id FROM utl_class_subject_type a, utl_class_subjects_map b, utl_class_sections c
    					WHERE b.subject_type_id = a.id
    						AND b.subject_id = \"$subjectId\" 
    						AND a.class_id = c.class_id
    						AND c.id = \"$sectionId\"
    						AND a.active = \"y\" 
    						AND b.active = \"y\"
    						AND c.active = \"y\" LIMIT 1";
    	$sqlQuery = $this->processArray($sqlQuery);
    	if($sqlQuery[1] == 'c')
    		return true;
    	
    	$sqlQuery = "SELECT a.id FROM utl_candidate_subject_map a
    					WHERE a.candidate_id = \"$candidateId\" 
    						AND a.subject_id = \"$subjectId\"
    						AND a.subject_type_id = \"".$sqlQuery[0]."\"
    						AND a.active = \"y\" LIMIT 1";
    	$sqlQuery = $this->processArray($sqlQuery);
    	
    	if($sqlQuery[0] != "")
    		return true;    	
    	return false;
    }    
    
    public function getResultProcessingIds($resultId, $candidateId){
    	$sqlQuery = "SELECT id FROM exam_result_process WHERE candidate_id = \"$candidateId\" AND result_id = \"$resultId\" ORDER BY creation_date DESC";    	
    	return $this->getDataArray($this->processQuery($sqlQuery));
    }
    
    public function setResultProcessing($resultId, $sectionId, $candidateId){
    	$counter = $this->getCounter("resultProcess");
    	$sectionDetails = $this->getTableIdDetails($sectionId);
    	
    	$sqlQuery = "INSERT INTO exam_result_process
    					(id, session_id, result_id, class_id, section_id, candidate_id, processing_date, processing_officer, last_update_date, last_updated_by, creation_date, created_by, active)
    					VALUES (\"$counter\", \"".$sectionDetails['session_id']."\", \"$resultId\", \"".$sectionDetails['class_id']."\", \"$sectionId\",  \"$candidateId\", \"" . $this->getCurrentDateTime () . "\", \"" . $this->getLoggedUserId () . "\", \"" . $this->getCurrentDateTime () . "\", \"" . $this->getLoggedUserId () . "\", \"" . $this->getCurrentDateTime () . "\", \"" . $this->getLoggedUserId () . "\", \"y\") ";
        if ($this->processQuery ( $sqlQuery, $counter )) {
            return $counter;
        }
        return false;
    }
    
    public function getSectionIds(){
    	$sqlQuery = "SELECT id FROM utl_class_sections ORDER BY class_id";
    	return $this->getDataArray($this->processQuery($sqlQuery));
    }
    
    
	
}
?>