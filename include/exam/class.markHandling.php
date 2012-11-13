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

class MarkHandling extends general {
	
	public function __construct() {
		parent::__construct ();
	}
	
	public function checkValidityOfMarkSubmission($referenceId) {
		$taskDetails = $this->getTableIdDetails ( $referenceId );
		
		if ($taskDetails ['user_id'] != $this->getLoggedUserId ()) {
			$message = "You Are Not Authorised To Submit The Marks Against This Class";
		} elseif ($taskDetails ['completeFlag'] == 'y') {
			$message = "You Have Already Confirmed You Submission. Check With Your Examination Admin to Unlock It";
		} elseif ((strtotime ( $taskDetails ['end_date'] ) - strtotime ( $this->getCurrentDate () )) < 0) {
			$message = "You Have Already Missed The Deadline. Check With Your Examination Admin to Unlock It";
		} else {
			return false;
		}
		return $message;
	
	}
	
	public function checkValidityOfMarkVerification($referenceId) {
		$taskDetails = $this->getTableIdDetails ( $referenceId );
		
		if ($taskDetails ['user_id'] != $this->getLoggedUserId ()) {
			$message = "You Are Not Authorised To Verify The Marks Against This Class";
		} elseif ($taskDetails ['completeFlag'] == 'y') {
			$message = "You Have Already Confirmed You Verification. Check With Your Examination Admin to Unlock It";
		} elseif ((strtotime ( $taskDetails ['end_date'] ) - strtotime ( $this->getCurrentDate () )) < 0) {
			$message = "You Have Already Missed The Deadline. Check With Your Examination Admin to Unlock It";
		} else {
			return false;
		}
	}
	
	public function getCandidate4ExaminationDate($examDateId){
		$details = $this->getTableIdDetails ( $examDateId );		
		$sqlQuery = "SELECT a.subject_type FROM utl_class_subject_type a, utl_class_subjects_map b WHERE b.subject_id = \"" . $details ['subject_id'] . "\" AND a.class_id = \"" . $details ['class_id'] . "\" AND a.id = b.subject_type_id AND a.active = \"y\" ";		
		$sqlQuery = $this->processArray ( $sqlQuery );
		
		if ($sqlQuery [0] == 'c') {
			$sqlQuery = "SELECT a.candidate_id FROM utl_candidate_classes a, utl_candidate_registration b WHERE a.class_id = \"" . $details ['class_id'] . "\" AND a.section_id = \"" . $details ['section_id'] . "\" AND a.active = \"y\" AND b.candidate_id = a.candidate_id ORDER BY b.serial_number ASC";
		} else {
			$sqlQuery = "SELECT a.candidate_id 
							FROM utl_candidate_subject_map a, 
								utl_class_subject_type b, 
								utl_candidate_classes c,
								utl_class_subjects_map d,
								utl_candidate_registration e
							WHERE a.subject_type_id = b.id
								AND a.candidate_id = c.candidate_id
								AND c.class_id = \"".$details['class_id']."\"
								AND c.section_id = \"".$details['section_id']."\"
								AND a.subject_id = \"".$details['subject_id']."\"
								AND b.class_id = c.class_id
								AND d.subject_type_id = b.id
								AND d.subject_id = a.subject_id
								AND e.candidate_id = c.candidate_id
								AND a.active = \"y\" 
			                    AND b.active = \"y\"
			                    AND c.active = \"y\"
			                    AND d.active = \"y\"
			                    AND e.active = \"y\"			                    
							ORDER BY e.serial_number ASC ";
		
		}
		return $this->getDataArray ( $this->processQuery ( $sqlQuery ) );
	}
	
	public function getCandidate4MarkSubmission($examDateId) {
		$details = $this->getTableIdDetails ( $examDateId );		
		$sqlQuery = "SELECT a.subject_type FROM utl_class_subject_type a, utl_class_subjects_map b WHERE b.subject_id = \"" . $details ['subject_id'] . "\" AND a.class_id = \"" . $details ['class_id'] . "\" AND a.id = b.subject_type_id AND a.active = \"y\" ";		
		$sqlQuery = $this->processArray ( $sqlQuery );
		
		if ($sqlQuery [0] == 'c') {
			$sqlQuery = "SELECT a.candidate_id FROM utl_candidate_classes a, utl_candidate_registration b WHERE a.class_id = \"" . $details ['class_id'] . "\" AND a.section_id = \"" . $details ['section_id'] . "\" AND a.active = \"y\" AND a.candidate_id = b.candidate_id AND NOT EXISTS (SELECT 1 FROM exam_mark_records e WHERE e.candidate_id = a.candidate_id AND e.exam_date_id = \"$examDateId\") ORDER BY b.serial_number ASC";
		} else {
			$sqlQuery = "SELECT a.candidate_id 
							FROM utl_candidate_subject_map a, 
								utl_class_subject_type b, 
								utl_candidate_classes c,
								utl_class_subjects_map d,
								utl_candidate_registration e
							WHERE a.subject_type_id = b.id
								AND a.candidate_id = c.candidate_id
								AND c.class_id = \"".$details['class_id']."\"
								AND c.section_id = \"".$details['section_id']."\"
								AND a.subject_id = \"".$details['subject_id']."\"
								AND b.class_id = c.class_id
								AND d.subject_type_id = b.id
								AND d.subject_id = a.subject_id
								AND e.candidate_id = c.candidate_id
								AND a.active = \"y\"
								AND c.active = \"y\"
								AND d.active = \"y\"
								AND e.active = \"y\"
								AND b.active = \"y\" AND NOT EXISTS (SELECT 1 FROM exam_mark_records e WHERE e.candidate_id = a.candidate_id AND e.exam_date_id = \"$examDateId\")
							ORDER BY e.serial_number ASC ";
		
		}
		return $this->getDataArray ( $this->processQuery ( $sqlQuery ) );
	}
	
	public function getMarkSubmittedCandidateIds($examId, $flag) {
		if (! $flag)
			$sqlQuery = "SELECT candidate_id FROM exam_mark_records WHERE exam_date_id = \"$examId\" AND submission_officer_id = \"\" ";
		else
			$sqlQuery = "SELECT candidate_id FROM exam_mark_records WHERE exam_date_id = \"$examId\" AND submission_officer_id != \"\" ";
		return $this->getDataArray ( $this->processQuery ( $sqlQuery ) );
	}
	
	public function getMarkVerifiedCandidateIds($examId) {
		$sqlQuery = "SELECT b.candidate_id 
						FROM exam_mark_records a, 
							utl_candidate_registration b 
						WHERE a.exam_date_id = \"$examId\"
							AND a.candidate_id = b.candidate_id 
							AND verification_officer_id != \"\"
							AND a.active = \"y\"
							AND b.active = \"y\"
						ORDER BY b.serial_number ASC ";
		return $this->getDataArray ( $this->processQuery ( $sqlQuery ) );
	}
	
	public function setMarkRecord4Candidate($examId, $candidateId, $mark) {
		$submittedMark = $this->getMark4Candidate ( $examId, $candidateId );
		if (! $submittedMark) {
			$counter = $this->getCounter ( "markEntry" );
			$details = $this->getTableIdDetails ( $examId );
			$sqlQuery = "INSERT INTO exam_mark_records 
							(id, exam_date_id, examination_id, session_id, class_id, section_id, subject_id, subject_component_id, candidate_id, submitted_mark, absolute_mark, last_update_date, last_updated_by, creation_date, created_by, active) 
							VALUES (\"$counter\", \"$examId\", \"" . $details ['examination_id'] . "\", \"" . $details ['session_id'] . "\", 
			\"" . $details ['class_id'] . "\", \"".$details['section_id']."\", \"" . $details ['subject_id'] . "\", \"" . $details ['subject_component_id'] . "\", \"$candidateId\", \"$mark\",  \"$mark\", \"" . $this->getCurrentDateTime () . "\", \"" . $this->getLoggedUserId () . "\", \"" . $this->getCurrentDateTime () . "\", \"" . $this->getLoggedUserId () . "\", \"y\") ";
			if ($this->processQuery ( $sqlQuery, $counter )) {
				$this->logOperation ( $counter, "The Mark Has Been Submitted " . $mark );
				return $counter;
			}
			return false;
		} else {
			if ($mark == $submittedMark [0])
				return $submittedMark [1];
			else {
				$this->updateMark4Candidate ( $submittedMark [1], $mark );
				return $submittedMark [1];
			}
		}
		return false;
	}
	
	public function setGradeRecord4Candidate($examId, $candidateId, $mark, $gradeValue){
		$submittedMark = $this->getMark4Candidate ( $examId, $candidateId );
		if (! $submittedMark) {
			$counter = $this->getCounter ( "markEntry" );
			$details = $this->getTableIdDetails ( $examId );
			$sqlQuery = "INSERT INTO exam_mark_records
			(id, exam_date_id, examination_id, session_id, class_id, section_id, subject_id, subject_component_id, candidate_id, submitted_mark, absolute_mark, last_update_date, last_updated_by, creation_date, created_by, active)
			VALUES (\"$counter\", \"$examId\", \"" . $details ['examination_id'] . "\", \"" . $details ['session_id'] . "\",
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
				$this->updateGrade4Candidate ( $submittedMark [1], $mark , $gradeValue);
				return $submittedMark [1];
			}
			}
			return false;
	}
	
	public function getMark4Candidate($examId, $candidateId) {
		$sqlQuery = "SELECT submitted_mark, id FROM exam_mark_records WHERE candidate_id = \"$candidateId\" AND exam_date_id = \"$examId\" ";
		$sqlQuery = $this->processArray ( $sqlQuery );
		if ($sqlQuery [0] == "")
			return false;
		return $sqlQuery;
	}
	
	public function updateMark4Candidate($id, $mark) {
		$details = $this->getTableIdDetails($id);
		$this->setUpdateLog("Mark From ".$details['submitted_mark']." To " . $mark);
		$this->updateTableParameter ( 'submitted_mark', $mark );
		$this->updateTableParameter ( 'absolute_mark', $mark );
		if ($this->commitMarkUpdate ( $id )) {
			$this->logOperation ( $id, "New Mark " . $mark );
			return $id;
		}
		return false;
	}
	
	public function updateGrade4Candidate($id, $mark, $gradeValue){
		$details = $this->getTableIdDetails($id);
		$this->setUpdateLog("Mark From ".$details['submitted_mark']." To " . $mark);
		$this->updateTableParameter ( 'submitted_mark', $mark );
		$this->updateTableParameter ( 'absolute_mark', $gradeValue );
		if ($this->commitMarkUpdate ( $id )) {			
			return $id;
		}
		return false;
	}
	
	public function confirmMarkSubmission($id) {
		$this->setUpdateLog('Submitted');
		$this->updateTableParameter ( 'submission_officer_id', $this->getLoggedUserId () );
		$this->updateTableParameter ( 'submission_date', $this->getCurrentDateTime () );
		if ($this->commitMarkUpdate ( $id )) {			
			return $id;
		}
		return false;
	}
	
	public function confirmMarkVerification($id) {
		$this->setUpdateLog('Verified');
		$this->updateTableParameter ( 'verification_officer_id', $this->getLoggedUserId () );
		$this->updateTableParameter ( 'verification_date', $this->getCurrentDateTime () );
		if ($this->commitMarkUpdate ( $id )) {			
			return $id;
		}
		return false;
	}
	
	private function commitMarkUpdate($markId) {
		if ($this->sqlConstructQuery == "")
			return $markId;
		
		return $this->commitUpdate($markId);
	}
	
public function checkMarkFillingCompletionStatus($examinationId, $flag) {
		$details = $this->getTableIdDetails ( $examinationId );
		
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
								AND a.active = \"y\"
								AND b.active = \"y\"
								AND c.active = \"y\"
								AND d.active = \"y\"
								AND NOT EXISTS (SELECT 1 
													FROM exam_mark_records e 
													WHERE e.candidate_id = a.candidate_id 
														AND e.exam_date_id = \"$examinationId\" 
														AND e.verification_officer_id != \"\"
														AND e.active = \"y\" ) 
								AND a.active = \"y\"";
			else
				$sqlQuery = "SELECT a.candidate_id 
									FROM utl_candidate_classes a 
									WHERE a.class_id = \"" . $details ['class_id'] . "\" 
										AND a.section_id = \"" . $details ['section_id'] . "\" 
										AND NOT EXISTS (SELECT 1 
															FROM exam_mark_records e 
															WHERE e.candidate_id = a.candidate_id 
																AND e.exam_date_id = \"$examinationId\" 
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
															FROM exam_mark_records e 
															WHERE e.candidate_id = a.candidate_id 
																AND e.exam_date_id = \"$examinationId\" 
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
								AND a.active = \"y\" 
								AND b.active = \"y\"
								AND c.active = \"y\"
								AND d.active = \"y\"
							AND NOT EXISTS (SELECT 1 FROM exam_mark_records e WHERE e.candidate_id = a.candidate_id AND exam_date_id = \"$examinationId\" AND submission_officer_id != \"\" )";
		}
		$sqlQuery = $this->processQuery ( $sqlQuery );
		if (mysql_num_rows ( $sqlQuery ) == 0)
			return true;
		return false;
	
	}
	
	public function getExaminationDisplayDetails($examId) {
		$sqlQuery = "SELECT a.examination_name 'examination_name', 
							g.session_name 'session_name',
							f.class_name 'class_name',
							d.subject_code 'subject_code',
							d.subject_name 'subject_name',
							a.subject_component_id 'subject_component_id',
							a.max_mark 'max_mark',
							a.pass_mark 'pass_mark',
							b.section_name 'section_name',
							a.examination_date 'start_date',
							a.mark_submission_date 'submission_date',
							a.mark_verification_date 'verification_date'												 
							FROM exam_examination_dates a, 
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
}
?>