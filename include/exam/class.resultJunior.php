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

class ResultJunior extends general {
	
	public function __construct() {
		parent::__construct ();
	}
	
	public function setResultJuniorDetails($resultId, $sectionId, $weightDate, $heightDate, $achievementDate, $weightOfficer, $heightOfficer, $achievementOfficer) {
		$counter = $this->getCounter ( "resultJunior" );
		$details = $this->getTableIdDetails ( $sectionId );
		$sqlQuery = "INSERT INTO exam_result_junior
				(id, session_id, result_id, class_id, section_id, weight_date, weight_officer, height_date, height_officer, achievement_date, achievement_officer, last_update_date, last_updated_by, creation_date, created_by, active)
			VALUES (\"$counter\", \"" . $details ['session_id'] . "\", \"$resultId\", \"" . $details ['class_id'] . "\", \"$sectionId\", \"$weightDate\", \"$weightOfficer\", \"$heightDate\", \"$heightOfficer\", \"$achievementDate\", \"$achievementOfficer\", \"" . $this->getCurrentDateTime () . "\", \"" . $this->getLoggedUserId () . "\", \"" . $this->getCurrentDateTime () . "\", \"" . $this->getLoggedUserId () . "\", \"y\") ";
		
		if ($this->processQuery ( $sqlQuery, $counter )) {
			$this->logOperation ( $counter, "Entry" );
			return $counter;
		}
		return false;
	}
	
	public function commitSectionDetailsUpdate($resultSectionId) {
		if ($this->sqlConstructQuery == "")
			return $resultSectionId;
		
		return $this->commitUpdate($resultSectionId);
	}
	
	public function getResultJuniorId($resultId, $sectionId) {
		$sqlQuery = "SELECT id FROM exam_result_junior WHERE section_id = \"$sectionId\" AND result_id = \"$resultId\" ";
		$sqlQuery = $this->processArray ( $sqlQuery );
		if ($sqlQuery [0] != "")
			return $sqlQuery [0];
		return false;
	
	}
}
?>