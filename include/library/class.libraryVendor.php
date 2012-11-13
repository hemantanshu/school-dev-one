<?php
/**
 * This class will hold the functionalities regarding the different personal info of the user.
 * This class extends the general class
 * @author Hemant Kumar Sah
 * @category Global
 * @license Support-Gurukul
 * @version 1.0.0
 */
require_once 'config.php';
require_once BASE_PATH.'include/global/class.general.php';

class Vendor extends general {
	
    public function __construct() {
        parent::__construct();
    }
	
    public function setVendorRecord($vendorName, $address, $weightage, $contactNo, $email){
    	$counter = $this->getCounter('libraryVendorEntry');
		$sqlQuery = "INSERT INTO library_vendor_details
				(id, vendor_name, vendor_address, weightage, contact_number, email_id, last_update_date, last_updated_by, creation_date, created_by, active) 
				VALUES (\"$counter\", \"$vendorName\", \"$address\", \"$weightage\", \"$contactNo\", \"$email\", \"" . $this->getCurrentDateTime() . "\", \"" . $this->getLoggedUserId() . "\", \"" . $this->getCurrentDateTime() . "\", \"" . $this->getLoggedUserId() . "\", \"y\")";
				
		if($this->processQuery($sqlQuery, $counter)){
    		$this->logOperation($counter, 'New Entry');
    		return $counter;
    	}
    	return false;
    }   
    
    public function getVendorName($vendorId){
    	$sqlQuery = "SELECT vendor_name FROM library_vendor_details WHERE id = \"$vendorId\" ";
    	return $this->processSingleElementQuery($sqlQuery);
    }    
    
    
    public function commitVendorRecordUpdate($vendorId){
    	if($this->commitUpdate($vendorId))
    		return $vendorId;
    	return false;
    }  
    
    public function dropVendorRecord($vendorId){
    	if($this->dropTableId($vendorId, false)){
    		$this->logOperation($vendorId, 'dropped');
    		return true;
    	}
    	return false;
    }
    
    public function activateVendorRecord($vendorId){
    	if($this->activateTableId($vendorId)){
    		$this->logOperation($vendorId, 'activated');
    		return true;
    	}
    	return false;
    }
    
    public function setVendorCategory($vendorId, $categoryId){    	
    	if($this->checkVendorCategory($vendorId, $categoryId))
    		return true;
    	
    	$counter = $this->getCounter('libraryVendorCategory');
    	$sqlQuery = "INSERT INTO library_vendor_category
	    	(id, vendor_id, category_id, last_update_date, last_updated_by, creation_date, created_by, active)
	    	VALUES (\"$counter\", \"$vendorId\", \"$categoryId\", \"" . $this->getCurrentDateTime() . "\", \"" . $this->getLoggedUserId() . "\", \"" . $this->getCurrentDateTime() . "\", \"" . $this->getLoggedUserId() . "\", \"y\")";
    	
    	if($this->processQuery($sqlQuery, $counter)){
    		$this->logOperation($counter, 'New Entry');
    		return $counter;
    	}
    	return false;
    }
    
    public function setVendorTag($vendorId, $tagId){
    	if($this->checkVendorTag($vendorId, $tagId))
    		return true;
    
    	$counter = $this->getCounter('libraryVendorTag');
    	$sqlQuery = "INSERT INTO library_vendor_tag
				    	(id, vendor_id, tag_id, last_update_date, last_updated_by, creation_date, created_by, active)
				    	VALUES (\"$counter\", \"$vendorId\", \"$tagId\", \"" . $this->getCurrentDateTime() . "\", \"" . $this->getLoggedUserId() . "\", \"" . $this->getCurrentDateTime() . "\", \"" . $this->getLoggedUserId() . "\", \"y\")";
    	 
    	if($this->processQuery($sqlQuery, $counter)){
    		$this->logOperation($counter, 'New Entry');
    		return $counter;
    	}
    	return false;
    }    
    
    public function dropVendorCategory($vendorCategoryId){
    	if($this->dropTableId($vendorCategoryId, false)){
    		$this->logOperation($vendorCategoryId, 'dropped');
    		return true;
    	}
    	return false;
    }
    public function activateVendorCategory($vendorCategoryId){
    	if($this->activateTableId($vendorCategoryId)){
    		$this->logOperation($vendorCategoryId, 'Activated');
    		return true;
    	}
    	return false;
    }
    
    public function dropVendorTag($vendorTagId){
    	if($this->dropTableId($vendorTagId, false)){
    		$this->logOperation($vendorTagId, 'dropped');
    		return true;
    	}
    	return false;
    }
    public function activateVendorTag($vendorTagId){
    	if($this->activateTableId($vendorTagId)){
    		$this->logOperation($vendorTagId, 'Activated');
    		return true;
    	}
    	return false;
    }

    //functions related to fetching data from the server
    public function getVendorIds($hint, $active){
    	if($active){
    		if($active === 'all')
    			$sqlQuery = "SELECT id FROM library_vendor_details WHERE vendor_name LIKE \"%$hint%\" ORDER BY vendor_name ASC ";
    		else
    			$sqlQuery = "SELECT id FROM library_vendor_details WHERE vendor_name LIKE \"%$hint%\" AND active = \"y\" ORDER BY vendor_name ASC ";
    	}else
    		$sqlQuery = "SELECT id FROM library_vendor_details WHERE vendor_name LIKE \"%$hint%\" AND active != \"y\" ORDER BY vendor_name ASC ";
    	
    	return $this->getDataArray($this->processQuery($sqlQuery));
    }
    
    public function checkVendorCategory($vendorId, $categoryId){
    	$sqlQuery = "SELECT id FROM library_vendor_category WHERE vendor_id =\"$vendorId\" AND category_id = \"$categoryId\" AND active = \"y\" ";
    	return $this->processSingleElementQuery($sqlQuery);
    }
    
    public function checkVendorTag($vendorId, $tagId){
    	$sqlQuery = "SELECT id FROM library_vendor_tag WHERE vendor_id =\"$vendorId\" AND tag_id = \"$tagId\" AND active = \"y\" ";
    	return $this->processSingleElementQuery($sqlQuery);
    }
    
    public function getVendorCategoryIds($vendorId, $active){
    	if($active){
    		if($active === 'all')
    			$sqlQuery = "SELECT id FROM library_vendor_category WHERE vendor_id = \"$vendorId\" ";
    		else
    			$sqlQuery = "SELECT id FROM library_vendor_category WHERE vendor_id = \"$vendorId\" AND active = \"y\" ";
    	}else
    		$sqlQuery = "SELECT id FROM library_vendor_category WHERE vendor_id = \"$vendorId\" AND active != \"y\" ";
    		
    	return $this->getDataArray($this->processQuery($sqlQuery));
    }
    
    public function getVendorCategories($vendorId){
    	$sqlQuery = "SELECT category_id FROM library_vendor_category WHERE vendor_id = \"$vendorId\" AND active = \"y\" ";
    	return $this->getDataArray($this->processQuery($sqlQuery));
    }
    
    public function getVendorTagIds($vendorId, $active){
    	if($active){
    		if($active === 'all')
    			$sqlQuery = "SELECT id FROM library_vendor_tag WHERE vendor_id = \"$vendorId\" ";
    		else
    			$sqlQuery = "SELECT id FROM library_vendor_tag WHERE vendor_id = \"$vendorId\" AND active = \"y\" ";
    	}else
    		$sqlQuery = "SELECT id FROM library_vendor_tag WHERE vendor_id = \"$vendorId\" AND active != \"y\" ";
    	
    	return $this->getDataArray($this->processQuery($sqlQuery));
    }
    
    public function getVendorTags($vendorId){
    	$sqlQuery = "SELECT tag_id FROM library_vendor_tag WHERE vendor_id = \"$vendorId\" AND active = \"y\" ";
    	return $this->getDataArray($this->processQuery($sqlQuery));
    }    
}
?>