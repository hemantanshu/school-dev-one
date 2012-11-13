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

class ResultMarking extends general {
	
	public function __construct() {
		parent::__construct ();
	}
	
	public function setGradeRecord4Candidate($genericId, $candidateId, $mark, $gradeValue) {
		$submittedMark = $this->getMark4Candidate ( $genericId, $candidateId );
		if (! $submittedMark) {
			$counter = $this->getCounter ( "resultEntry" );
			$details = $this->getTableIdDetails ( $genericId );
			$sqlQuery = "INSERT INTO exam_result_records
			(id, result_id, generic_id, session_id, class_id, section_id, subject_id, subject_component_id, candidate_id, submitted_mark, absolute_mark, last_update_date, last_updated_by, creation_date, created_by, active)
			VALUES (\"$counter\", \"" . $details ['result_id'] . "\", \"$genericId\", \"" . $details ['session_id'] . "\",
			\"" . $details ['class_id'] . "\", \"".$details['section_id']."\", \"" . $details ['subject_id'] . "\", \"" . $details ['subject_component_id'] . "\", \"$candidateId\", \"$mark\",  \"$gradeValue\", \"" . $this->getCurrentDateTime () . "\", \"" . $this->getLoggedUserId () . "\", \"" . $this->getCurrentDateTime () . "\", \"" . $this->getLoggedUserId () . "\", \"y\") ";
			
			if ($this->processQuery ( $sqlQuery, $counter )) {
				$this->logOperation ( $counter, "The Mark Has Been Submitted " . $mark );
				return $counter;
			}
			return false;
		} else {
			if ($mark == $submittedMark [0])
				return $submittedMark [1];
			else {
				$this->updateGrade4Candidate ( $submittedMark [1], $mark, $gradeValue );
				return $submittedMark [1];
			}
		}
		return false;
	}
	
	public function getMark4Candidate($genericId, $candidateId) {
		$sqlQuery = "SELECT submitted_mark, id FROM exam_result_records WHERE candidate_id = \"$candidateId\" AND generic_id = \"$genericId\" AND active = \"y\"";
		$sqlQuery = $this->processArray ( $sqlQuery );
		if ($sqlQuery [0] == "")
			return false;
		return $sqlQuery;
	}
	
	public function updateGrade4Candidate($id, $mark, $gradeValue) {
		$this->updateTableParameter ( 'submitted_mark', $mark );
		$this->updateTableParameter ( 'absolute_mark', $gradeValue );
		$this->updateTableParameter ( 'active', 'y' );
		
		if ($this->commitMarkUpdate ( $id )) {
			$this->logOperation ( $id, "The Mark Has Been Updated To " . $mark );
			return $id;
		}
		return false;
	}
	
	public function confirmMarkSubmission($id) {
		$this->updateTableParameter ( 'submission_officer_id', $this->getLoggedUserId () );
		$this->updateTableParameter ( 'submission_date', $this->getCurrentDateTime () );
		if ($this->commitMarkUpdate ( $id )) {
			$this->logOperation ( $id, "The Mark Has Been Confirmed By " . $this->getLoggedUserId () );
			return $id;
		}
		return false;
	}
	
	public function confirmMarkVerification($id) {
		$this->updateTableParameter ( 'verification_officer_id', $this->getLoggedUserId () );
		$this->updateTableParameter ( 'verification_date', $this->getCurrentDateTime () );
		if ($this->commitMarkUpdate ( $id )) {
			$this->logOperation ( $id, "The Mark Has Been Verified By " . $this->getLoggedUserId () );
			return $id;
		}
		return false;
	}
	
	public function commitMarkUpdate($markId) {
		if ($this->sqlConstructQuery == "")
			return $markId;
		
		$this->sqlConstructQuery .= ", last_update_date=\"" . $this->getCurrentDateTime () . "\", last_updated_by=\"" . $this->getLoggedUserId () . "\"";
		$sqlQuery = "UPDATE exam_result_records
						SET $this->sqlConstructQuery
						WHERE id = \"$markId\" ";
		
		$this->sqlConstructQuery = "";
		if ($this->processQuery ( $sqlQuery, $markId )) {
			return true;
		}
		return false;
	}
	
	public function checkMarkFillingCompletionStatus($genericId, $flag) {
		$details = $this->getTableIdDetails ( $genericId );
		
		$sqlQuery = "SELECT a.subject_type 
								FROM utl_class_subject_type a, 
									utl_class_subjects_map b 
								WHERE b.subject_id = \"" . $details ['subject_id'] . "\" 
									AND a.class_id = \"" . $details ['class_id'] . "\" 
									AND a.id = b.subject_type_id 
									AND a.active = \"y\"
									AND b.active = \"y\" ";
		$query = $this->processArray ( $sqlQuery );
		if ($flag) { // final verification process is done or not
			if ($query [0] == 'o')
				$sqlQuery = "SELECT a.candidate_id 
							FROM utl_candidate_subject_map a, 
								utl_class_subject_type b, 
								utl_candidate_classes c,
								utl_class_subjects_map d
							WHERE a.subject_type_id = b.id
								AND a.candidate_id = c.candidate_id
								AND c.class_id = \"" . $details ['class_id'] . "\"
								AND c.section_id = \"" . $details ['section_id'] . "\"
								AND a.subject_id = \"" . $details ['subject_id'] . "\"
								AND b.class_id = c.class_id
								AND d.subject_type_id = b.id
								AND d.subject_id = a.subject_id
								AND b.active = \"y\" AND NOT EXISTS (SELECT 1 
																		FROM exam_result_records e 
																		WHERE e.candidate_id = a.candidate_id 
																			AND e.result_id = \"$genericId\" 
																			AND e.verification_officer_id != \"\"
																			AND e.active = \"y\" ) 
								AND a.active = \"y\"
								AND c.active = \"y\"
								AND d.active = \"y\" ";
			else
				$sqlQuery = "SELECT a.candidate_id 
									FROM utl_candidate_classes a 
									WHERE a.class_id = \"" . $details ['class_id'] . "\" 
										AND a.section_id = \"" . $details ['section_id'] . "\" 
										AND NOT EXISTS (SELECT 1 
															FROM exam_result_records e 
															WHERE e.candidate_id = a.candidate_id 
																AND e.generic_id = \"$genericId\" 
																AND e.verification_officer_id != \"\"
																AND e.active = \"y\" ) 
										AND a.active = \"y\"";
		} else { // final verification is not done
			if ($query [0] == 'c')
				$sqlQuery = "SELECT a.candidate_id 
									FROM utl_candidate_classes a 
									WHERE a.class_id = \"" . $details ['class_id'] . "\" 
										AND a.section_id = \"" . $details ['section_id'] . "\" 
										AND NOT EXISTS (SELECT 1 
															FROM exam_result_records e 
															WHERE e.candidate_id = a.candidate_id 
																AND e.generic_id = \"$genericId\" 
																AND e.submission_officer_id != \"\"
																AND e.active = \"y\" ) 
										AND a.active = \"y\"";
			else
				$sqlQuery = "SELECT a.candidate_id 
							FROM utl_candidate_subject_map a, 
								utl_class_subject_type b, 
								utl_candidate_classes c,
								utl_class_subjects_map d
							WHERE a.subject_type_id = b.id
								AND a.candidate_id = c.candidate_id
								AND c.class_id = \"" . $details ['class_id'] . "\"
								AND c.section_id = \"" . $details ['section_id'] . "\"
								AND a.subject_id = \"" . $details ['subject_id'] . "\"
								AND b.class_id = c.class_id
								AND d.subject_type_id = b.id
								AND d.subject_id = a.subject_id
								AND b.active = \"y\" AND NOT EXISTS (SELECT 1 FROM exam_result_records e WHERE e.candidate_id = a.candidate_id AND generic_id = \"$genericId\" AND submission_officer_id != \"\" )";
		}
		$sqlQuery = $this->processQuery ( $sqlQuery );
		if (mysql_num_rows ( $sqlQuery ) == 0)
			return true;
		return false;
	
	}
	
	public function getExaminationDisplayDetails($examId) {
		$sqlQuery = "SELECT a.activity_name 'examination_name', 
							g.session_name 'session_name',
							f.class_name 'class_name',
							d.subject_code 'subject_code',
							d.subject_name 'subject_name',
							b.section_name 'section_name',
							a.examination_date 'start_date',
							a.mark_submission_date 'submission_date',
							a.mark_verification_date 'verification_date'												 
							FROM exam_assessment_subjects a, 
									utl_class_sections b,
									utl_subject_details d, 
									utl_class_details e, 
									utl_class_name f, 
									glb_session_details g
							WHERE a.id = \"$examId\"
								AND a.session_id = g.id
								AND a.subject_id = d.id								
								AND a.class_id = e.id
								AND e.class_id = f.id
								AND b.id = a.section_id ";
		
		return $this->processArray ( $sqlQuery );
	}
	
	public function getCandidate4Activity($examDateId){
		$details = $this->getTableIdDetails ( $examDateId );
		$sqlQuery = "SELECT a.subject_type FROM utl_class_subject_type a, utl_class_subjects_map b WHERE b.subject_id = \"" . $details ['subject_id'] . "\" AND a.class_id = \"" . $details ['class_id'] . "\" AND a.id = b.subject_type_id AND a.active = \"y\" ";
		$sqlQuery = $this->processArray ( $sqlQuery );
	
		if ($sqlQuery [0] == 'c') {
			$sqlQuery = "SELECT a.candidate_id FROM utl_candidate_classes a WHERE a.class_id = \"" . $details ['class_id'] . "\" AND a.section_id = \"" . $details ['section_id'] . "\" AND a.active = \"y\"";
		} else {
			$sqlQuery = "SELECT a.candidate_id
							FROM utl_candidate_subject_map a,
								utl_class_subject_type b,
								utl_candidate_classes c,
								utl_class_subjects_map d
							WHERE a.subject_type_id = b.id
								AND a.candidate_id = c.candidate_id
								AND c.class_id = \"".$details['class_id']."\"
								AND c.section_id = \"".$details['section_id']."\"
								AND a.subject_id = \"".$details['subject_id']."\"
								AND b.class_id = c.class_id
								AND d.subject_type_id = b.id
								AND d.subject_id = a.subject_id
								AND a.active = \"y\"
								AND b.active = \"y\"
								AND c.active = \"y\"
								AND d.active = \"y\"
						";
	
		}
		return $this->getDataArray ( $this->processQuery ( $sqlQuery ) );
	}
	
	public function getCandidate4GradeSubmission($examDateId) {		
		$details = $this->getTableIdDetails ( $examDateId );
		$sqlQuery = "SELECT a.subject_type FROM utl_class_subject_type a, utl_class_subjects_map b WHERE b.subject_id = \"" . $details ['subject_id'] . "\" AND a.class_id = \"" . $details ['class_id'] . "\" AND a.id = b.subject_type_id AND a.active = \"y\" ";		
		$sqlQuery = $this->processArray ( $sqlQuery );
		
		if ($sqlQuery [0] == 'c') {
			$sqlQuery = "SELECT a.candidate_id FROM utl_candidate_classes a WHERE a.class_id = \"" . $details ['class_id'] . "\" AND a.section_id = \"" . $details ['section_id'] . "\" AND a.active = \"y\" AND NOT EXISTS (SELECT 1 FROM exam_result_records e WHERE e.candidate_id = a.candidate_id AND e.generic_id = \"$examDateId\")";
		} else {
			$sqlQuery = "SELECT a.candidate_id
							FROM utl_candidate_subject_map a,
								utl_class_subject_type b,
								utl_candidate_classes c,
								utl_class_subjects_map d
							WHERE a.subject_type_id = b.id
								AND a.candidate_id = c.candidate_id
								AND c.class_id = \"" . $details ['class_id'] . "\"
								AND c.section_id = \"" . $details ['section_id'] . "\"
								AND a.subject_id = \"" . $details ['subject_id'] . "\"
								AND b.class_id = c.class_id
								AND d.subject_type_id = b.id
								AND d.subject_id = a.subject_id
								AND a.active = \"y\" 
								AND b.active = \"y\"
								AND c.active = \"y\"
								AND d.active = \"y\"
								AND NOT EXISTS (SELECT 1 FROM exam_result_records e WHERE e.candidate_id = a.candidate_id AND e.generic_id = \"$examDateId\")";
		
		}
		return $this->getDataArray ( $this->processQuery ( $sqlQuery ) );
	}
	
	public function getGradeSubmittedCandidateIds($genericId, $flag) {
		if (! $flag)
			$sqlQuery = "SELECT candidate_id FROM exam_result_records WHERE generic_id = \"$genericId\" AND submission_officer_id = \"\" ";
		else
			$sqlQuery = "SELECT candidate_id FROM exam_result_records WHERE generic_id = \"$genericId\" AND submission_officer_id != \"\" ";
		return $this->getDataArray ( $this->processQuery ( $sqlQuery ) );
	}
	
	public function getGradeVerifiedCandidateIds($genericId) {
		$sqlQuery = "SELECT candidate_id FROM exam_result_records WHERE generic_id = \"$genericId\" AND verification_officer_id != \"\" AND active = \"y\"";
		return $this->getDataArray ( $this->processQuery ( $sqlQuery ) );
	}
	
	public function setMarkRecords($resultSetupId, $sectionId, $subjectId, $subjectComponentId, $candidateId, $mark, $markValue){
		$submittedMark = $this->getMarkEntry4Candidate ( $resultSetupId, $subjectId, $subjectComponentId, $candidateId, 'all' );		
		if (! $submittedMark) {
			$counter = $this->getCounter ( "resultEntry" );
			$details = $this->getTableIdDetails ( $resultSetupId );
			$sectionDetails = $this->getTableIdDetails($sectionId);
			$sqlQuery = "INSERT INTO exam_result_records
			(id, result_id, generic_id, session_id, class_id, section_id, subject_id, subject_component_id, candidate_id, submitted_mark, absolute_mark, submission_date, submission_officer_id, verification_date, verification_officer_id, last_update_date, last_updated_by, creation_date, created_by, active)
			VALUES (\"$counter\", \"" . $details ['result_id'] . "\", \"$resultSetupId\", \"" . $details ['session_id'] . "\",
			\"" . $sectionDetails ['class_id'] . "\", \"$sectionId\", \"$subjectId\", \"$subjectComponentId\", \"$candidateId\", \"$mark\",  \"$markValue\", \"" . $this->getCurrentDateTime () . "\", \"" . $this->getLoggedUserId () . "\", \"" . $this->getCurrentDateTime () . "\", \"" . $this->getLoggedUserId () . "\", \"" . $this->getCurrentDateTime () . "\", \"" . $this->getLoggedUserId () . "\", \"" . $this->getCurrentDateTime () . "\", \"" . $this->getLoggedUserId () . "\", \"y\") ";
				
			if ($this->processQuery ( $sqlQuery, $counter )) {
				$this->logOperation ( $counter, "The Mark Has Been Submitted " . $mark );
				return $counter;
			}
			return false;
		} else {
			if ($mark == $submittedMark [0]){
				$this->updateTableParameter('active', 'y');
				$this->commitMarkUpdate($submittedMark[1]);
				return $submittedMark [1];
			}
				
			else {
				$this->updateGrade4Candidate ( $submittedMark [1], $mark, $markValue );
				return $submittedMark [1];
			}
		}
		return false;
	}
	
	public function getMarkEntry4Candidate( $resultSetupId, $subjectId, $subjectComponentId, $candidateId , $flag){
		if($flag === "all")
			$sqlQuery = "SELECT submitted_mark, id FROM exam_result_records WHERE candidate_id = \"$candidateId\" AND generic_id = \"$resultSetupId\" AND subject_id = \"$subjectId\" AND subject_component_id = \"$subjectComponentId\"";
		else
			$sqlQuery = "SELECT submitted_mark, id FROM exam_result_records WHERE candidate_id = \"$candidateId\" AND generic_id = \"$resultSetupId\" AND subject_id = \"$subjectId\" AND subject_component_id = \"$subjectComponentId\" AND active = \"y\"";
				
		
		$sqlQuery = $this->processArray ( $sqlQuery );
		if ($sqlQuery [0] == "")
			return false;
		return $sqlQuery;
	}
	
	
	public function getTotalMarkResultEntry4CandidateSubject( $resultSetupId, $subjectId, $candidateId , $flag){
		if($flag === "all")
			$sqlQuery = "SELECT SUM(submitted_mark) FROM exam_result_records WHERE candidate_id = \"$candidateId\" AND generic_id = \"$resultSetupId\" AND subject_id = \"$subjectId\" ";
		else
			$sqlQuery = "SELECT SUM(submitted_mark) FROM exam_result_records WHERE candidate_id = \"$candidateId\" AND generic_id = \"$resultSetupId\" AND subject_id = \"$subjectId\" AND active = \"y\"";
				
		
		return $this->processSingleElementQuery($sqlQuery);
	}
	
	public function getTotalMarkResultEntry4CandidateSubjectComponent( $resultSetupId, $subjectId, $componentId, $candidateId , $flag){
		if($flag === "all")
			$sqlQuery = "SELECT SUM(submitted_mark) FROM exam_result_records WHERE candidate_id = \"$candidateId\" AND generic_id = \"$resultSetupId\" AND subject_id = \"$subjectId\" AND subject_component_id = \"$componentId\" ";
		else
			$sqlQuery = "SELECT SUM(submitted_mark) FROM exam_result_records WHERE candidate_id = \"$candidateId\" AND generic_id = \"$resultSetupId\" AND subject_id = \"$subjectId\" AND subject_component_id = \"$componentId\" AND active = \"y\"";
	
	
		return $this->processSingleElementQuery($sqlQuery);
	}
	
	public function getCandidateTotalMark4Result($candidateId, $resultId){
		$sqlQuery = "SELECT SUM(a.submitted_mark) 
						FROM exam_result_records a, 
								exam_result_setup b 
						WHERE a.candidate_id = \"$candidateId\"
							AND a.generic_id = b.id
							AND b.result_id = \"$resultId\"
							AND a.active = \"y\"
							AND b.active = \"y\" ";
		return $this->processSingleElementQuery($sqlQuery);
	}
	
	public function getTotalMarkEntry4Candidate($resultSetupId, $candidateId){
		$sqlQuery = "SELECT sum(submitted_mark) FROM exam_result_records WHERE candidate_id = \"$candidateId\" AND generic_id = \"$resultSetupId\" GROUP BY candidate_id";
		return $this->getDataArray($this->processQuery($sqlQuery));
	}
	
	public function getRankOnArray($needle, $haystack){
		return array_search($needle, $haystack)+1;	
	}
	
	public function dropResultRecordEntry($id){
		if($this->dropTableId($id, false)){
			$this->logOperation($id, "Dropped");
			return $id;
		}
		return false;
	}
	
	public function getIDForResultRecords($resultId, $genericId, $sectionId, $candidateId = ""){
		if($candidateId != "")
			$sqlQuery = "SELECT id FROM exam_result_records WHERE result_id = \"$resultId\" AND generic_id = \"$genericId\" AND section_id = \"$sectionId\" AND candidate_id = \"$candidateId\" AND active = \"y\" ";
		else
			$sqlQuery = "SELECT id FROM exam_result_records WHERE result_id = \"$resultId\" AND generic_id = \"$genericId\" AND section_id = \"$sectionId\" AND active = \"y\" ";
		return $this->getDataArray($this->processQuery($sqlQuery));
	}
	
	public function initialiseCandidateRecord4Processing($resultId, $genericId, $sectionId, $candidateId = ""){
		$resultRecordIds = $this->getIDForResultRecords($resultId, $genericId, $sectionId, $candidateId);
		foreach ($resultRecordIds as $resultRecordId){
			$this->dropResultRecordEntry($resultRecordId);
		}
		return true;
	}

}
?>