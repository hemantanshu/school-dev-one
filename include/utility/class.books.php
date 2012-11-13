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
require_once BASE_PATH.'include/global/class.loggedInfo.php';

class books extends loggedInfo {

    public function __construct() {
        parent::__construct();
    }
    
    public function getBookIds($active){
        if($active){
            if($active == 'all')
                $sqlQuery = "SELECT id 
                                FROM utl_book_details
                                ORDER BY book_name ASC";
            else
                $sqlQuery = "SELECT id 
                                FROM utl_book_details
                                WHERE active = \"y\"
                                ORDER BY book_name ASC";
        }else
            $sqlQuery = "SELECT id 
                                FROM utl_book_details
                                WHERE active != \"y\"
                                ORDER BY book_name ASC";
        return $this->getDataArray($this->processQuery($sqlQuery));
    }
    
    public function getBookSearchIds($str, $type){
    	if($type){
    		if($type == 'all')
    			$sqlQuery = "SELECT * 
    							FROM utl_book_details 
    							WHERE book_name LIKE \"%$str%\" ";
    		else
    			$sqlQuery = "SELECT * 
    							FROM utl_book_details 
    							WHERE book_name LIKE \"%$str%\" 
    								AND active = \"y\"";
    	}else
    		$sqlQuery = "SELECT * 
    							FROM utl_book_details 
    							WHERE book_name LIKE \"%$str%\" 
    								AND active != \"y\"";
    	
    	return $this->getDataArray($this->processQuery($sqlQuery));
    }
    
    public function getBookIdDetails($bookId){
        return $this->getTableIdDetails($bookId);
    }
    
    public function dropBookDetails($bookId){
        if($this->dropTableId($bookId, false)){
            $this->logOperation($bookId, "The Book Details Has Been Dropped");
            return true;
        }
        return false;
    }
    
    public function activateBookDetails($bookId){
        if($this->activateTableId($bookId)){
            $this->logOperation($bookId, "The Book Details Has Been Activated");
            return true;
        }
        return false;
    }
    
    public function setBookDetails($bookName, $author, $publication){
        $counter = $this->getCounter('book');
        $sqlQuery = "INSERT INTO utl_book_details
                            (id, book_name, author_id, publication_id, last_update_date, last_updated_by,
                            creation_date, created_by, active)
                            VALUES (\"$counter\", \"$bookName\", \"$author\", \"$publication\", \"" . $this->getCurrentDateTime() . "\", \"" . $this->getLoggedUserId() . "\", \"" . $this->getCurrentDateTime() . "\", \"" . $this->getLoggedUserId() . "\", \"y\")";
        if($this->processQuery($sqlQuery, $counter)){
            $this->logOperation($counter, "The Book Details Has Been Inserted");
            return $counter;
        }
        return false;
    }
    
    public function commitBookDetailsUpdate($bookId){
        if ($this->sqlConstructQuery == "")
            return false;
        return $this->commitUpdate($bookId);
    }
    
    

    

}

?>