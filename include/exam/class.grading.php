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

class Grading extends general {
	
	public function __construct() {
		parent::__construct ();
	}
	
	public function setGradingType($gradingName) {
		$counter = $this->getCounter ( 'gradingType' );
		$sqlQuery = "INSERT INTO exam_grading_type 
    					(id, grading_name, last_update_date, last_updated_by, creation_date, created_by, active) 
    					VALUES (\"$counter\", \"$gradingName\", \"" . $this->getCurrentDateTime () . "\", \"" . $this->getLoggedUserId () . "\", \"" . $this->getCurrentDateTime () . "\", \"" . $this->getLoggedUserId () . "\", \"y\") ";
		if ($this->processQuery ( $sqlQuery, $counter )) {
			$this->logOperation ( $counter, 'New Entry Made' );
			return $counter;
		}
		return false;
	}
	
	public function getGradingType($hint, $active) {
		if ($active) {
			if ($active === 'all')
				$sqlQuery = "SELECT id 
    							FROM exam_grading_type 
    							WHERE grading_name LIKE \"%$hint%\" ";
			else
				$sqlQuery = "SELECT id
				    			FROM exam_grading_type
				    			WHERE grading_name LIKE \"%$hint%\"
    								AND active = \"y\" ";
		} else
			$sqlQuery = "SELECT id
				    		FROM exam_grading_type
				    		WHERE grading_name LIKE \"%$hint%\" 
    							AND active != \"y\" ";
		return $this->getDataArray ( $this->processQuery ( $sqlQuery ) );
	}
	
	public function getGradingName($gradingId) {
		return $this->getValue ( 'grading_name', 'exam_grading_type', 'id', $gradingId );
	}
	
	public function commitGradingTypeUpdate($gradingId) {
		if ($this->sqlConstructQuery == "")
			return false;
		
		return $this->commitUpdate($gradingId);
	}
	
	public function dropGradingType($gradingId) {
		if ($this->dropTableId ( $gradingId, false )) {
			$this->logOperation ( $gradingId, "The grading type Has Been Dropped" );
			return true;
		}
		return false;
	}
	
	public function activateGradingType($gradingId) {
		if ($this->activateTableId ( $gradingId )) {
			$this->logOperation ( $gradingId, "The Grading Details Has Been Activated" );
			return true;
		}
		return false;
	}
	
	// functions related to the grading options
	public function setGradingOptions($gradingId, $grade, $weight) {
		$counter = $this->getCounter ( 'gradingOption' );
		$sqlQuery = "INSERT INTO exam_grading_option
    					(id, grading_id, grade_name, weight, last_update_date, last_updated_by, creation_date, created_by, active)
    					VALUES (\"$counter\", \"$gradingId\", \"$grade\", \"$weight\", \"" . $this->getCurrentDateTime () . "\", \"" . $this->getLoggedUserId () . "\", \"" . $this->getCurrentDateTime () . "\", \"" . $this->getLoggedUserId () . "\", \"y\") ";
		if ($this->processQuery ( $sqlQuery, $counter )) {
			$this->logOperation ( $counter, 'New Entry Made' );
			return $counter;
		}
		return false;
	}
	
	public function getGradingOptionIds($gradingId, $active) {
		if ($active) {
			if ($active === 'all')
				$sqlQuery = "SELECT id
    							FROM exam_grading_option
    							WHERE grading_id =\"$gradingId\" ";				
    	
			else
				$sqlQuery = "SELECT id
    							FROM exam_grading_option
    							WHERE grading_id =\"$gradingId\"
    								AND active = \"y\" 
								ORDER BY weight DESC";
		} else
			$sqlQuery = "SELECT id						    
    						FROM exam_grading_option
    						WHERE grading_id =\"$gradingId\" 
							AND active != \"y\" ";
		return $this->getDataArray ( $this->processQuery ( $sqlQuery ) );
	}
	
	public function getGradingOptions($gradingId) {
		$sqlQuery = "SELECT id, grade_name
						FROM exam_grading_option
						WHERE grading_id =\"$gradingId\" 
						ORDER BY weight DESC";
		return $this->getDataArray ( $this->processQuery ( $sqlQuery ), 2 );
	}
		
	public function commitGradingOptionUpdate($gradingOptionId) {
		if ($this->sqlConstructQuery == "")
			return false;
		
		return $this->commitUpdate($gradingOptionId);
	}
	
	public function dropGradingOption($gradingOptionId) {
		if ($this->dropTableId ( $gradingOptionId, false )) {
			$this->logOperation ( $gradingOptionId, "The Grading Option Has Been Dropped" );
			return true;
		}
		return false;
	}
	
	public function activateGradingOption($gradingOptionId) {
		if ($this->activateTableId ( $gradingOptionId )) {
			$this->logOperation ( $gradingOptionId, "The Grading Option Details Has Been Activated" );
			return true;
		}
		return false;
	}
	
	public function getGradingOptionName($optionId){
		return $this->getValue("grade_name", "exam_grading_option", "id", $optionId);
	}
	
	public function getGradingOptionWeight($optionId){
		return $this->getValue("weight", "exam_grading_option", "id", $optionId);
	}
	
	public function getGradeForScore($originalScore, $gradingType){		
		$multiplicationFactor = $this->getGradingTypeMaxScore($gradingType);
		$score = $originalScore * $multiplicationFactor;			
		$grades = $this->getGradingOptionIds($gradingType, 1);
		$i = 0;
		$currentGrade = "";
		foreach($grades as $grade){
			++$i;
			$previousGrade = $currentGrade;
			$currentGrade = $grade;
			if($i == 1)
				continue;
			
			if($score > $this->getGradingOptionWeight($grade))
				return $this->getGradingOptionName($previousGrade);			
		}
		return $this->getGradingOptionName($currentGrade);
	}
	
	public function getGradingTypeMaxScore($gradingId){
		$sqlQuery = "SELECT MAX(weight) FROM exam_grading_option WHERE grading_id = \"$gradingId\" AND active = \"y\" ";
		return $this->processSingleElementQuery($sqlQuery);
	}

}
?>