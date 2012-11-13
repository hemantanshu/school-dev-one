<?php

/**
 * This class will hold the functionalities related to the subject insert details
 * This class extends the general class
 * @author Hemant Kumar Sah
 * @category Global
 * @license Support-Gurukul
 * @version 1.0.0
 */
require_once 'config.php';
require_once BASE_PATH . 'include/global/class.general.php';

class subjects extends general {
	
	public function __construct() {
		parent::__construct ();
	}
	
	public function getSubjectIds($active) {
		if ($active) {
			if ($active === 'all')
				$sqlQuery = "SELECT id 
                                FROM utl_subject_details 
                                ORDER BY subject_name ASC";
			else
				$sqlQuery = "SELECT id 
                                FROM utl_subject_details
                                WHERE active = \"y\"
                                ORDER BY subject_name ASC";
		} else
			$sqlQuery = "SELECT id 
                            FROM utl_subject_details
                            WHERE active != \"y\"
                            ORDER BY subject_name ASC";
		return $this->getDataArray ( $this->processQuery ( $sqlQuery ) );
	}
	
	public function getSubjectSearchIds($hint, $active) {
		if ($active) {
			if ($active === 'all')
				$sqlQuery = "SELECT id
				    			FROM utl_subject_details
				    			WHERE (subject_name LIKE \"%$hint%\"
				    				OR subject_code LIKE \"%$hint%\")
				    			ORDER BY subject_name ASC";
			else
				$sqlQuery = "SELECT id
				    			FROM utl_subject_details
				    			WHERE active = \"y\"
				    			AND (subject_name LIKE \"%$hint%\"
				    				OR subject_code LIKE \"%$hint%\")				    			
				    			ORDER BY subject_name ASC";
		} else
			$sqlQuery = "SELECT id
						    	FROM utl_subject_details
						    	WHERE active != \"y\"
						    	AND (subject_name LIKE \"%$hint%\"
						    		OR subject_code LIKE \"%$hint%\")
						    	ORDER BY subject_name ASC";
		
		return $this->getDataArray ( $this->processQuery ( $sqlQuery ) );
	}
	
	public function getSubjectIdDetails($subjectId) {
		return $this->getTableIdDetails ( $subjectId );
	}	
	
	public function dropSubjectDetails($subjectId) {
		if ($this->dropTableId ( $subjectId, false )) {
			$this->logOperation ( $subjectId, "The Subject Details Has Been Dropped" );
			return true;
		}
		return false;
	}
	
	public function activateSubjectDetails($subjectId) {
		if ($this->activateTableId ( $subjectId )) {
			$this->logOperation ( $subjectId, "The Subject Details Has Been Activated" );
			return true;
		}
		return false;
	}
	
	public function setSubjectDetails($subjectCode, $subjectName) {
		$counter = $this->getCounter ( 'subject' );
		$sqlQuery = "INSERT INTO utl_subject_details 
                            (id, subject_code, subject_name, last_update_date, last_updated_by, creation_date, created_by, active) 
                            VALUES (\"$counter\", \"$subjectCode\", \"$subjectName\", \"" . $this->getCurrentDateTime () . "\", \"" . $this->getLoggedUserId () . "\", \"" . $this->getCurrentDateTime () . "\", \"" . $this->getLoggedUserId () . "\", \"y\")";
		if ($this->processQuery ( $sqlQuery, $counter )) {
			$this->logOperation ( $counter, "New Subject Has Been Defined" );
			return $counter;
		}
		return false;
	}
	
	public function commitSubjectDetailsUpdate($subjectId) {
		if ($this->sqlConstructQuery == "")
			return $subjectId;
		
		$this->commitUpdate($subjectId);
	}
	
	// functions related to teh subject book combination
	public function getSubjectBookIds($subjectId, $type = '', $active = '') {
		if (empty ( $type )) {
			if (empty ( $active ) || $active == 'all')
				$sqlQuery = "SELECT id 
                                FROM utl_subject_books 
                                WHERE subject_id = \"$subjectId\" ";
			elseif ($active)
				$sqlQuery = "SELECT id 
                                FROM utl_subject_books 
                                WHERE active = \"y\"
                                AND subject_id = \"$subjectId\" ";
			else
				$sqlQuery = "SELECT id 
                                FROM utl_subject_books 
                                WHERE active != \"y\" 
                                AND subject_id = \"$subjectId\" ";
		} else {
			if ($type) {
				if (empty ( $active ) || $active == 'all')
					$sqlQuery = "SELECT id 
                                    FROM utl_subject_books 
                                    WHERE subject_id = \"$subjectId\" 
                                    AND type = \"y\" ";
				elseif ($active)
					$sqlQuery = "SELECT id 
                                    FROM utl_subject_books 
                                    WHERE active = \"y\"
                                    AND type = \"y\"
                                    AND subject_id = \"$subjectId\" ";
				else
					$sqlQuery = "SELECT id 
                                    FROM utl_subject_books 
                                    WHERE active != \"y\"
                                    AND type = \"y\"
                                    AND subject_id = \"$subjectId\" ";
			} else {
				if (empty ( $active ) || $active == 'all')
					$sqlQuery = "SELECT id 
                                    FROM utl_subject_books 
                                    WHERE subject_id = \"$subjectId\" 
                                    AND type != \"y\" ";
				elseif ($active)
					$sqlQuery = "SELECT id 
                                    FROM utl_subject_books 
                                    WHERE active = \"y\"
                                    AND type != \"y\"
                                    AND subject_id = \"$subjectId\" ";
				else
					$sqlQuery = "SELECT id 
                                    FROM utl_subject_books 
                                    WHERE active != \"y\"
                                    AND type != \"y\"
                                    AND subject_id = \"$subjectId\" ";
			}
		}
		return $this->getDataArray ( $this->processQuery ( $sqlQuery ) );
	}
	
	public function getSubjectBookSearchIds($subjectId, $hint, $active) {
		if ($active) {
			if ($active === 'all')
				$sqlQuery = "SELECT id
			    			FROM utl_subject_books
			    			WHERE subject_id = \"$subjectId\"
			    				AND book_id IN (SELECT id FROM utl_book_details WHERE book_name LIKE \"%$hint%\")";
			else
				$sqlQuery = "SELECT id
			    			FROM utl_subject_books
			    			WHERE subject_id = \"$subjectId\"
			    				AND book_id IN (SELECT id FROM utl_book_details WHERE book_name LIKE \"%$hint%\")
    							AND active = \"y\"";
		} else
			$sqlQuery = "SELECT id
			    			FROM utl_subject_books
			    			WHERE subject_id = \"$subjectId\"
			    				AND book_id IN (SELECT id FROM utl_book_details WHERE book_name LIKE \"%$hint%\")
    							AND active != \"y\"";
		
		return $this->getDataArray ( $this->processQuery ( $sqlQuery ) );
	}
	
	public function getSubjectBookIdDetails($id) {
		return $this->getTableIdDetails ( $id );
	}
	
	public function dropSubjectBookDetails($id) {
		if ($this->dropTableId ( $id, false )) {
			$this->logOperation ( $id, "The Subject Book Combination Has Been Dropped" );
			return true;
		}
		return false;
	}
	
	public function activateSubjectBookDetails($id) {
		if ($this->activateTableId ( $id )) {
			$this->logOperation ( $id, "The Subject Book Combination Has Been Activated" );
			return true;
		}
		return false;
	}
	
	public function setSujectBookDetails($subjectId, $bookId, $type, $priority) {
		$counter = $this->getCounter ( 'subject_book' );
		$flag = $type ? 'y' : '';
		
		$sqlQuery = "INSERT 
                        INTO utl_subject_books 
                        (id, subject_id, book_id, core, priority, last_update_date, last_updated_by, creation_date, created_by, active) 
                        VALUES (\"$counter\",\"$subjectId\",\"$bookId\",\"$flag\", \"$priority\", \"" . $this->getCurrentDateTime () . "\", \"" . $this->getLoggedUserId () . "\", \"" . $this->getCurrentDateTime () . "\", \"" . $this->getLoggedUserId () . "\", \"y\")";
		if ($this->processQuery ( $sqlQuery, $counter )) {
			$this->logOperation ( $counter, "The Subject Book Combination Has Been Set" );
			return $counter;
		}
		return false;
	}
	
	public function commitSubjectBookDetailsUpdate($subjectId) {
		if ($this->sqlConstructQuery == "")
			return $subjectId;
		
		$this->commitUpdate($subjectId);
	}
	
	
	//functions related to class subject mapping
	public function getClassSubjectTypeIds ($classId, $type, $active){		
		if($active){
			if($active === 'all')
				$sqlQuery = "SELECT id
								FROM utl_class_subject_type
								WHERE class_id = \"$classId\"
									AND subject_type LIKE \"%$type%\"
								ORDER BY subject_type ASC ";
			else
				$sqlQuery = "SELECT id
								FROM utl_class_subject_type
								WHERE class_id = \"$classId\"
									AND subject_type LIKE \"%$type%\" 
									AND active = \"y\" 
								ORDER BY subject_type ASC ";
		}else 
			$sqlQuery = "SELECT id
							FROM utl_class_subject_type
							WHERE class_id = \"$classId\"
								AND subject_type LIKE \"%$type%\" 
								AND active != \"y\" 
							ORDER BY subject_type ASC ";
		return $this->getDataArray($this->processQuery($sqlQuery));
	}
	
	public function setClassSubjectTypeEntry($classId, $subjectName, $type){
		$counter = $this->getCounter('class_subject_types');
		$sqlQuery = "INSERT INTO utl_class_subject_type 
						(id, class_id, subject_name, subject_type, last_update_date, last_updated_by, creation_date, created_by, active) 
						VALUES (\"$counter\", \"$classId\", \"$subjectName\", \"$type\", \"" . $this->getCurrentDateTime () . "\", \"" . $this->getLoggedUserId () . "\", \"" . $this->getCurrentDateTime () . "\", \"" . $this->getLoggedUserId () . "\", \"y\")";
		if($this->processQuery($sqlQuery, $counter)){
			$this->logOperation($counter, 'New Subject Type Has Been Defined');
			return $counter;
		}
		return false;
	}
	
	public function commitClassSubjectTypeUpdate($id){
		if ($this->sqlConstructQuery == "")
			return false;
		
		$this->sqlConstructQuery .= ", last_update_date=\"" . $this->getCurrentDateTime () . "\", last_updated_by=\"" . $this->getLoggedUserId () . "\"";
		$sqlQuery = "UPDATE utl_class_subject_type
						SET $this->sqlConstructQuery
						WHERE id = \"$id\" ";
		
		$this->sqlConstructQuery = "";
		
		if ($this->processQuery ( $sqlQuery, $id )) {
			$this->logOperation ( $id, "The Class Subject Details Has Been Updated" );
			return true;
		}
		return false;
	}
	
	public function dropClassSubjectTypeDetails($id){
		if ($this->dropTableId ( $id, false )) {
			$this->logOperation ( $id, "The Class Subject Combination Has Been Dropped" );
			return true;
		}
		return false;
	}
	
	public function activateClassSubjectTypeDetails($id){
		if ($this->activateTableId ( $id )) {
			$this->logOperation ( $id, "The Class Subject Combination Has Been Activated" );
			return true;
		}
		return false;
	}
	
	//functions related to the class subject type mapping
		
	public function getClassSubjectMappingIds($classSubjectId, $active){
		if($active){
			if($active === 'all')
				$sqlQuery = "SELECT id 
								FROM utl_class_subjects_map 
								WHERE subject_type_id =\"$classSubjectId\" ";
			else
				$sqlQuery = "SELECT id 
								FROM utl_class_subjects_map 
								WHERE subject_type_id =\"$classSubjectId\" 
									AND active = \"y\" ";			
		}else 
			$sqlQuery = "SELECT id
							FROM utl_class_subjects_map
							WHERE subject_type_id =\"$classSubjectId\"
								AND active != \"y\" ";	
		return $this->getDataArray($this->processQuery($sqlQuery));
	}
	
	public function setClassSubjectMapping($classSubjectId, $subjectId){
		$counter = $this->getCounter('class_subject_map');
		$sqlQuery = "INSERT INTO utl_class_subjects_map 
						(id, subject_type_id, subject_id, last_update_date, last_updated_by, creation_date, created_by, active) 
						VALUES (\"$counter\", \"$classSubjectId\", \"$subjectId\", \"" . $this->getCurrentDateTime () . "\", \"" . $this->getLoggedUserId () . "\", \"" . $this->getCurrentDateTime () . "\", \"" . $this->getLoggedUserId () . "\", \"y\")";
		
		if($this->processQuery($sqlQuery, $counter)){
			$this->logOperation($counter, 'New Subject Has Been Added To The Class Subject Map');
			return $counter;
		}
		return false;
	}
	
	public function commitClassSubjectMapping($id){
		if ($this->sqlConstructQuery == "")
			return $id;
		
		return $this->commitUpdate($id);
	}
	
	public function dropClassSubjectMapping($id){
		if ($this->dropTableId ( $id, false )) {
			$this->logOperation ( $id, "The Class Subject Combination Has Been Dropped" );
			return true;
		}
		return false;
	}
	
	public function activateClassSubjectMapping($id){
		if ($this->activateTableId ( $id )) {
			$this->logOperation ( $id, "The Class Subject Combination Has Been Activated" );
			return true;
		}
		return false;
	}
	
}

?>