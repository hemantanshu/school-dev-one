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
class ResultType extends general {
	public function __construct() {
		parent::__construct ();
	}
	public function setResultType($resultType, $order, $description) {
		$counter = $this->getCounter ( "examResultType" );
		
		$sqlQuery = "INSERT INTO exam_result_type
						(id, result_type, result_order, result_description, last_update_date, last_updated_by, creation_date, created_by, active)
						VALUES (\"$counter\", \"$resultType\", \"$order\", \"$description\", \"" . $this->getCurrentDateTime () . "\", \"" . $this->getLoggedUserId () . "\", \"" . $this->getCurrentDateTime () . "\", \"" . $this->getLoggedUserId () . "\", \"y\") ";
		if ($this->processQuery ( $sqlQuery, $counter )) {
			$this->logOperation ( $counter, 'New Entry' );
			return $counter;
		}
		return false;
	}
	public function getResultType($hint, $active) {
		if ($active) {
			if ($active === 'all')
				$sqlQuery = "SELECT id
					FROM exam_result_type
					WHERE result_type LIKE \"%$hint%\"
            		ORDER BY result_order ASC ";
			else
				$sqlQuery = "SELECT id
					FROM exam_result_type
					WHERE result_type LIKE \"%$hint%\"
						AND active = \"y\" 
            		ORDER BY result_order ASC ";
		} else
			$sqlQuery = "SELECT id
					FROM exam_result_type
					WHERE result_type LIKE \"%$hint%\"
						AND active != \"y\" 
        			ORDER BY result_order ASC ";
		
		return $this->getDataArray ( $this->processQuery ( $sqlQuery ) );
	}
	public function commitResultTypeUpdate($resultTypeId) {
		if ($this->sqlConstructQuery == "")
			return $resultTypeId;
		
		return $this->commitUpdate ( $resultTypeId );
	}
	public function dropResultType($resultTypeId) {
		if ($this->dropTableId ( $resultTypeId, false )) {
			$this->logOperation ( $resultTypeId, "Dropped" );
			return true;
		}
		return false;
	}
	public function activateResultType($resultTypeId) {
		if ($this->activateTableId ( $resultTypeId )) {
			$this->logOperation ( $resultTypeId, "Activated" );
			return true;
		}
		return false;
	}
	
	// ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	public function setResultTypeFields($resultType, $displayName, $submissionUrl, $viewUrl, $code) {
		$counter = $this->getCounter ( "examResultTypeFields" );
		
		$sqlQuery = "INSERT INTO exam_result_type_fields
			    	(id, result_type_id, display_name, submission_url, view_url, code, last_update_date, last_updated_by, creation_date, created_by, active)
			    	VALUES (\"$counter\", \"$resultType\", \"$displayName\", \"$submissionUrl\", \"$viewUrl\", \"$code\", \"" . $this->getCurrentDateTime () . "\", \"" . $this->getLoggedUserId () . "\", \"" . $this->getCurrentDateTime () . "\", \"" . $this->getLoggedUserId () . "\", \"y\") ";
		if ($this->processQuery ( $sqlQuery, $counter )) {
			$this->logOperation ( $counter, 'New Entry' );
			return $counter;
		}
		return false;
	}
	public function getResultTypeFields($resultType, $hint, $active) {
		if ($active) {
			if ($active === 'all')
				$sqlQuery = "SELECT id
				    			FROM exam_result_type_fields
				    			WHERE result_type_id = \"$resultType\"
				    				AND display_name LIKE \"%$hint%\" 
								ORDER BY submission_url ASC";
			else
				$sqlQuery = "SELECT id
					    		FROM exam_result_type_fields
					    		WHERE result_type_id = \"$resultType\"
					    			AND display_name LIKE \"%$hint%\"
					    			AND active = \"y\" 
								ORDER BY submission_url ASC";
		} else
			$sqlQuery = "SELECT id
					    		FROM exam_result_type_fields
					    		WHERE result_type_id = \"$resultType\"
					    			AND display_name LIKE \"%$hint%\"
					    			AND active != \"y\" 
								ORDER BY submission_url ASC";
		
		return $this->getDataArray ( $this->processQuery ( $sqlQuery ) );
	}
	public function commitResultTypeFieldsUpdate($resultTypeFieldId) {
		if ($this->sqlConstructQuery == "")
			return $resultTypeFieldId;
		
		return $this->commitUpdate ( $resultTypeFieldId );
	}
	public function dropResultTypeField($resultTypeFieldId) {
		if ($this->dropTableId ( $resultTypeFieldId, false )) {
			$this->logOperation ( $resultTypeFieldId, "Dropped" );
			return true;
		}
		return false;
	}
	public function activateResultTypeField($resultTypeFieldId) {
		if ($this->activateTableId ( $resultTypeFieldId )) {
			$this->logOperation ( $resultTypeFieldId, "Activated" );
			return true;
		}
		return false;
	}
	
	// /////////////////////////////////////////////////////////////////////////////////////////////////////
	public function setResultTypeUrls($resultType, $displayName, $url, $code, $displayOrder) {
		$counter = $this->getCounter ( "examResultTypeUrls" );
		
		$sqlQuery = "INSERT INTO exam_result_type_url
						(id, result_type_id, display_name, url, url_type, display_order, last_update_date, last_updated_by, creation_date, created_by, active)
						VALUES (\"$counter\", \"$resultType\", \"$displayName\", \"$url\", \"$code\", \"$displayOrder\", \"" . $this->getCurrentDateTime () . "\", \"" . $this->getLoggedUserId () . "\", \"" . $this->getCurrentDateTime () . "\", \"" . $this->getLoggedUserId () . "\", \"y\") ";
		if ($this->processQuery ( $sqlQuery, $counter )) {
			$this->logOperation ( $counter, 'New Entry' );
			return $counter;
		}
		return false;
	}
	public function getResultTypeUrls($resultType, $hint, $active) {
		if ($active) {
			if ($active === 'all')
				$sqlQuery = "SELECT id
								FROM exam_result_type_url
								WHERE result_type_id = \"$resultType\"
									AND display_name LIKE \"%$hint%\"
								ORDER BY display_order ASC ";
			else
				$sqlQuery = "SELECT id
								FROM exam_result_type_url
								WHERE result_type_id = \"$resultType\"
									AND display_name LIKE \"%$hint%\"
									AND active = \"y\" 
								ORDER BY display_order ASC ";
		} else
			$sqlQuery = "SELECT id
							FROM exam_result_type_url
							WHERE result_type_id = \"$resultType\"
								AND display_name LIKE \"%$hint%\"
								AND active != \"y\" 
							ORDER BY display_order ASC ";
		
		return $this->getDataArray ( $this->processQuery ( $sqlQuery ) );
	}
	public function getResultTypeUrlIds4Code($resultType, $urlType, $hint, $active) {
		if ($active) {
			if ($active === 'all')
				$sqlQuery = "SELECT id
								FROM exam_result_type_url
								WHERE result_type_id = \"$resultType\"
									AND display_name LIKE \"%$hint%\"
									AND url_type = \"$urlType\"
								ORDER BY display_order ASC ";
			else
				$sqlQuery = "SELECT id
								FROM exam_result_type_url
								WHERE result_type_id = \"$resultType\"
									AND display_name LIKE \"%$hint%\"
									AND url_type = \"$urlType\"
									AND active = \"y\" 
								ORDER BY display_order ASC ";
		} else
			$sqlQuery = "SELECT id
								FROM exam_result_type_url
								WHERE result_type_id = \"$resultType\"
									AND display_name LIKE \"%$hint%\"
									AND url_type = \"$urlType\"
									AND active != \"y\" 
								ORDER BY display_order ASC ";
		
		return $this->getDataArray ( $this->processQuery ( $sqlQuery ) );
	}
	public function commitResultTypeUrlsUpdate($resultTypeUrlId) {
		if ($this->sqlConstructQuery == "")
			return $resultTypeUrlId;
		
		return $this->commitUpdate ( $resultTypeUrlId );
	}
	public function dropResultTypeUrl($resultTypeUrlId) {
		if ($this->dropTableId ( $resultTypeUrlId, false )) {
			$this->logOperation ( $resultTypeUrlId, "Dropped" );
			return true;
		}
		return false;
	}
	public function activateResultTypeUrl($resultTypeUrlId) {
		if ($this->activateTableId ( $resultTypeUrlId )) {
			$this->logOperation ( $resultTypeUrlId, "Activated" );
			return true;
		}
		return false;
	}
	
}
?>