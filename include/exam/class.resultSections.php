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

class ResultSections extends general {
	
	public function __construct() {
		parent::__construct ();
	}
	
	public function setResultSectionDetails($resultId, $sectionId, $attendance, $totalMarks, $date, $dateOfficer, $remarksDate, $remarksOfficer) {
		$counter = $this->getCounter ( "resultSection" );
		$details = $this->getTableIdDetails ( $sectionId );
		$sqlQuery = "INSERT INTO exam_result_sections
				(id, session_id, result_id, class_id, section_id, total_attendance, total_mark, attendance_date, attendance_officer, remarks_date, remarks_officer, last_update_date, last_updated_by, creation_date, created_by, active)
			VALUES (\"$counter\", \"" . $details ['session_id'] . "\", \"$resultId\", \"" . $details ['class_id'] . "\", \"$sectionId\", \"$attendance\", \"$totalMarks\", \"$date\", \"$dateOfficer\", \"$remarksDate\", \"$remarksOfficer\", \"" . $this->getCurrentDateTime () . "\", \"" . $this->getLoggedUserId () . "\", \"" . $this->getCurrentDateTime () . "\", \"" . $this->getLoggedUserId () . "\", \"y\") ";
		
		if ($this->processQuery ( $sqlQuery, $counter )) {
			$this->logOperation ( $counter, "The New Class Entry Has Been Made " );
			return $counter;
		}
		return false;
	}
	
	public function commitSectionDetailsUpdate($resultSectionId) {
		if ($this->sqlConstructQuery == "")
			return $resultSectionId;
		
		return $this->commitUpdate($resultSectionId);
	}
	
	public function getResultSectionId($resultId, $sectionId) {
		$sqlQuery = "SELECT id FROM exam_result_sections WHERE section_id = \"$sectionId\" AND result_id = \"$resultId\" ";
		$sqlQuery = $this->processArray ( $sqlQuery );
		if ($sqlQuery [0] != "")
			return $sqlQuery [0];
		return false;
	
	}
	
	// functions related to candidate attendance entry
	public function setResultSectionData($resultSectionId, $candidateId, $data, $type) {
		$dataDetails = $this->getCandidateData ( $resultSectionId, $candidateId, $type );
		if ($dataDetails[0] != "" ) {
			if($data != $dataDetails[1]){
				$this->updateTableParameter('data', $data);
				$this->commitResultDataUpdate($dataDetails[0]);
			}
			return $dataDetails[0];
			
		} else {
			$counter = $this->getCounter ( "resultData" );
			$details = $this->getTableIdDetails ( $resultSectionId );
			$sqlQuery = "INSERT INTO exam_result_data
				(id, session_id, result_id, class_id, section_id, result_section_id, candidate_id, data, type, last_update_date, last_updated_by, creation_date, created_by, active)
				VALUES (\"$counter\", \"" . $details ['session_id'] . "\", \"" . $details ['result_id'] . "\", \"" . $details ['class_id'] . "\", \"" . $details ['section_id'] . "\", \"$resultSectionId\", \"$candidateId\", \"$data\", \"$type\", \"" . $this->getCurrentDateTime () . "\", \"" . $this->getLoggedUserId () . "\", \"" . $this->getCurrentDateTime () . "\", \"" . $this->getLoggedUserId () . "\", \"y\") ";
			
			if ($this->processQuery ( $sqlQuery, $counter )) {
				$this->logOperation ( $counter, "entry " );
				return $counter;
			}
		}
		return false;
	}
	
	public function getCandidateData($resultSectionId, $candidateId, $type) {
		$sqlQuery = "SELECT id, data FROM exam_result_data WHERE result_section_id = \"$resultSectionId\" AND candidate_id = \"$candidateId\" AND type = \"$type\" ";
		
		return $this->processArray ( $sqlQuery );
	}
	
	public function commitResultDataUpdate($dataId) {
		if ($this->sqlConstructQuery == "")
			return $dataId;
		
		$this->sqlConstructQuery .= ", last_update_date=\"" . $this->getCurrentDateTime () . "\", last_updated_by=\"" . $this->getLoggedUserId () . "\"";
		$sqlQuery = "UPDATE exam_result_data
		SET $this->sqlConstructQuery
		WHERE id = \"$dataId\" ";
		
		$this->sqlConstructQuery = "";
		if ($this->processQuery ( $sqlQuery, $dataId )) {
			return true;
		}
		return false;
	}
	
	public function getCandidate4Data($resultSectionId, $type) {
		$sqlQuery = "SELECT candidate_id FROM exam_result_data WHERE result_section_id = \"$resultSectionId\" AND type = \"$type\" ";
		return $this->getDataArray ( $this->processQuery ( $sqlQuery ) );
	}
	
	public function checkDataFillingCompletionStatus($resultSectionId, $sectionId, $type){
		$sqlQuery = "SELECT a.id 
						FROM exam_result_data a 
						WHERE a.type = \"$type\" 
							AND a.section_id = \"$sectionId\" 
							AND a.result_section_id = \"$resultSectionId\" 
							AND a.active = \"y\" 
							AND NOT EXISTS (SELECT 1 
												FROM utl_candidate_classes b 
												WHERE b.section_id = \"$sectionId\" 
													AND a.candidate_id = b.candidate_id 
												AND b.active = \"y\")";
		$sqlQuery = $this->processQuery($sqlQuery);
		
		if(mysql_num_rows($sqlQuery))
			return false;
		return true;
	}
	
	//functions related to the remarks entry
	
	public function setResultSectionRemarks($resultSectionId, $candidateId, $remarks) {
		$remarksDetails = $this->getCandidateRemarks ( $resultSectionId, $candidateId );
		if ($remarksDetails[0] != "" ) {			
			if($remarks != $remarksDetails[1]){
				$this->updateTableParameter('remark_id', $remarks);
				$this->commitResultRemarksUpdate($remarksDetails[0]);
			}
			return $remarksDetails[0];
				
		} else {
			$counter = $this->getCounter ( "resultRemarks" );
			$details = $this->getTableIdDetails ( $resultSectionId );
			$sqlQuery = "INSERT INTO exam_result_remarks
			(id, session_id, result_id, class_id, section_id, result_section_id, candidate_id, remark_id, last_update_date, last_updated_by, creation_date, created_by, active)
			VALUES (\"$counter\", \"" . $details ['session_id'] . "\", \"" . $details ['result_id'] . "\", \"" . $details ['class_id'] . "\", \"" . $details ['section_id'] . "\", \"$resultSectionId\", \"$candidateId\", \"$remarks\", \"" . $this->getCurrentDateTime () . "\", \"" . $this->getLoggedUserId () . "\", \"" . $this->getCurrentDateTime () . "\", \"" . $this->getLoggedUserId () . "\", \"y\") ";
							
			if ($this->processQuery ( $sqlQuery, $counter )) {
				$this->logOperation ( $counter, "The New Remark Entry Has Been Made " );
				return $counter;
			}
		}
		return false;
	}
	
	public function getCandidateRemarks($resultSectionId, $candidateId) {
		$sqlQuery = "SELECT id, remark_id FROM exam_result_remarks WHERE result_section_id = \"$resultSectionId\" AND candidate_id = \"$candidateId\" ";	
			
		return $this->processArray ( $sqlQuery );
	}
	
	public function commitResultRemarksUpdate($remarksId) {
		if ($this->sqlConstructQuery == "")
			return $remarksId;
	
		$this->sqlConstructQuery .= ", last_update_date=\"" . $this->getCurrentDateTime () . "\", last_updated_by=\"" . $this->getLoggedUserId () . "\"";
		$sqlQuery = "UPDATE exam_result_remarks
					SET $this->sqlConstructQuery
					WHERE id = \"$remarksId\" ";
		
		$this->sqlConstructQuery = "";
		if ($this->processQuery ( $sqlQuery, $remarksId )) {
			return true;
		}
		return false;
	}
	
	//

}
?>