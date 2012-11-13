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

class ResultGrade extends general {
	public function __construct() {
		parent::__construct ();
	}
	
	public function setResultSubjectComponentGrade($resultId, $sectionId, $subjectId, $type, $subjectComponentId, $gradingType){
		$resultSubjectComponentId = $this->getResultSubjectComponentGradeId($resultId, $sectionId, $type, $subjectId, $subjectComponentId);
		if(!$resultSubjectComponentId){
			$counter = $this->getCounter('examResultSubjectComponentGrade');
			$sqlQuery = "INSERT INTO exam_result_subject_component_grade
							(id, result_id, section_id, data_type, subject_id, subject_component_id, grade_type, last_update_date, last_updated_by, creation_date, created_by, active)
							VALUES (\"$counter\", \"$resultId\", \"$sectionId\", \"$type\", \"$subjectId\", \"$subjectComponentId\", \"$gradingType\", \"" . $this->getCurrentDateTime () . "\", \"" . $this->getLoggedUserId () . "\", \"" . $this->getCurrentDateTime () . "\", \"" . $this->getLoggedUserId () . "\", \"y\") ";			
			if ($this->processQuery ( $sqlQuery, $counter )) {
				$this->logOperation ( $counter, "Entry" );
				return $counter;
			}
			return false;
		}else{
			$details = $this->getTableIdDetails($resultSubjectComponentId);
			if($details['grade_type'] != $gradingType){
				if($details['grade_type'] != $gradingType){
					$this->setUpdateLog('from '.$details['grade_type'].' to '.$gradingType);
					$this->updateTableParameter('grade_type', $gradingType);
				}
				$this->commitResultSubjectComponentGradeUpdate($resultSubjectComponentId);
			}
		}
		return $resultSubjectComponentId;
	}
	
	public function getResultSubjectComponentGradeId($resultId, $sectionId, $type, $subjectId, $subjectComponentId){
		$sqlQuery = "SELECT id 
						FROM exam_result_subject_component_grade 
						WHERE result_id = \"$resultId\"
							AND section_id = \"$sectionId\"
							AND data_type = \"$type\"
							AND subject_id = \"$subjectId\"
							AND subject_component_id = \"$subjectComponentId\"
							AND active = \"y\" ";
		return $this->processSingleElementQuery($sqlQuery);
	}
	
	public function getResultSubjectComponentGradeData($resultId, $sectionId, $type, $subjectId, $subjectComponentId){
		$sqlQuery = "SELECT grade_type
						FROM exam_result_subject_component_grade
						WHERE result_id = \"$resultId\"
							AND section_id = \"$sectionId\"
							AND data_type = \"$type\"
							AND subject_id = \"$subjectId\"
							AND subject_component_id = \"$subjectComponentId\"
							AND active = \"y\" ";
		return $this->processSingleElementQuery($sqlQuery);
	}
	
	public function commitResultSubjectComponentGradeUpdate($resultSubjectComponentId){
		if ($this->sqlConstructQuery == "")
			return $resultSubjectComponentId;
	
		return $this->commitUpdate($resultSubjectComponentId);
	}
	
	
}
?>