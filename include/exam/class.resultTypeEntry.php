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
require_once BASE_PATH . 'include/exam/class.resultType.php';

class ResultTypeEntry extends ResultType {
	
	public function __construct() {
		parent::__construct ();		
	}
	
	public function setResultTypeDataEntry($resultId, $sectionId, $fieldId, $data){
		$dataId = $this->getResultTypeFieldDataId($resultId, $sectionId, $fieldId);
		if(!$dataId){
			$counter = $this->getCounter('examResultSectionStaticField');
			$sqlQuery = "INSERT INTO exam_result_section_setups
							(id, result_id, section_id, field_id, field_data, last_update_date, last_updated_by, creation_date, created_by, active)
							VALUES (\"$counter\", \"$resultId\", \"$sectionId\", \"$fieldId\", \"$data\", \"" . $this->getCurrentDateTime () . "\", \"" . $this->getLoggedUserId () . "\", \"" . $this->getCurrentDateTime () . "\", \"" . $this->getLoggedUserId () . "\", \"y\") ";
			
			if ($this->processQuery ( $sqlQuery, $counter )) {
				$this->logOperation ( $counter, "Entry" );
				return $counter;
			}
		}else{
			$details = $this->getTableIdDetails($dataId);
			if($details['field_data'] != $data){
				$this->updateTableParameter('field_data', $data);
				$this->commitResultTypeFieldDataUpdate($dataId);
				return $dataId;
			}
		}
		return $dataId;
	}
	
	public function getResultTypeFieldData($resultId, $sectionId, $fieldId){
		$sqlQuery = "SELECT field_data 
						FROM exam_result_section_setups 
						WHERE result_id = \"$resultId\" 
							AND section_id = \"$sectionId\" 
							AND field_id = \"$fieldId\" ";
		return $this->processSingleElementQuery($sqlQuery);
	}
	
	public function getResultTypeFieldDataId($resultId, $sectionId, $fieldId){
		$sqlQuery = "SELECT id
						FROM exam_result_section_setups
						WHERE result_id = \"$resultId\"
							AND section_id = \"$sectionId\"
							AND field_id = \"$fieldId\" ";
		return $this->processSingleElementQuery($sqlQuery);
	}
	
	public function commitResultTypeFieldDataUpdate($resultStaticDataId){
		if ($this->sqlConstructQuery == "")
			return $resultStaticDataId;
		
		return $this->commitUpdate($resultStaticDataId);
	}
	
	//functions for result type field submissions	
	public function setResultTypeSubmissionEntry($resultId, $sectionId, $fieldId, $submissionDate, $submissionOfficer){
		$dataId = $this->getResultTypeFieldSubmissionId($resultId, $sectionId, $fieldId);
		if(!$dataId){
			$counter = $this->getCounter('examResultSectionSubmissionField');
			$sqlQuery = "INSERT INTO exam_result_section_submissions
				(id, result_id, section_id, field_id, submission_date, submission_officer, last_update_date, last_updated_by, creation_date, created_by, active)
				VALUES (\"$counter\", \"$resultId\", \"$sectionId\", \"$fieldId\", \"$submissionDate\", \"$submissionOfficer\", \"" . $this->getCurrentDateTime () . "\", \"" . $this->getLoggedUserId () . "\", \"" . $this->getCurrentDateTime () . "\", \"" . $this->getLoggedUserId () . "\", \"y\") ";
				
			if ($this->processQuery ( $sqlQuery, $counter )) {
				$this->logOperation ( $counter, "Entry" );
				return $counter;
			}
		}else{
			$details = $this->getTableIdDetails($dataId);
			if($details['submission_date'] != $submissionDate || $details['submission_officer'] != $submissionOfficer){
				if($details['submission_date'] != $submissionDate){
					$this->setUpdateLog('Date from '.$details['submission_date'].' to '.$submissionDate);
					$this->updateTableParameter('submission_date', $submissionDate);
				}
				if($details['submission_officer'] != $submissionOfficer){
					$this->setUpdateLog('from '.$details['submission_officer'].' to '.$submissionOfficer);
					$this->updateTableParameter('submission_officer', $submissionOfficer);
				}
				$this->commitResultTypeFieldSubmissionUpdate($dataId);
				return $dataId;
			}
		}
		return $dataId;
	}
	
	public function getResultTypeFieldSubmissionId($resultId, $sectionId, $fieldId){
	$sqlQuery = "SELECT id
					FROM exam_result_section_submissions
					WHERE result_id = \"$resultId\"
						AND section_id = \"$sectionId\"
						AND field_id = \"$fieldId\" ";
	return $this->processSingleElementQuery($sqlQuery);
	}
	
	public function commitResultTypeFieldSubmissionUpdate($resultSubmissionId){
	if ($this->sqlConstructQuery == "")
		return $resultSubmissionId;
	
		return $this->commitUpdate($resultSubmissionId);
	}
	
	
}
?>