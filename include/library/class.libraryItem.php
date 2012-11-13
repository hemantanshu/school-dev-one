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

class LibraryItem extends general {
	
    public function __construct() {
        parent::__construct();
    }
	
    public function setItemRecord($itemName, $itemType, $issueFlag, $consumeFlag){
    	$counter = $this->getCounter('libraryItemEntry');
    	$sqlQuery = "INSERT INTO library_item_details
	    	(id, item_name, item_type, issueable, consumeable, last_update_date, last_updated_by, creation_date, created_by, active)
	    	VALUES (\"$counter\", \"$itemName\", \"$itemType\", \"$issueFlag\", \"$consumeFlag\", \"" . $this->getCurrentDateTime() . "\", \"" . $this->getLoggedUserId() . "\", \"" . $this->getCurrentDateTime() . "\", \"" . $this->getLoggedUserId() . "\", \"y\")";
    	if($this->processQuery($sqlQuery, $counter)){
    		$this->logOperation($counter, 'New Entry');
    		return $counter;
    	}
    	return false;
    }   
    
    public function setItemCost($itemId, $vendorId, $costPrice, $costCurrency, $sellPrice, $sellCurrency){
    	$counter = $this->getCounter('');
    	$sqlQuery = "INSERT INTO library_item_cost
	    	(id, item_id, vendor_id, cost_price, cost_currency, sale_price, sale_currency, last_update_date, last_updated_by, creation_date, created_by, active)
	    	VALUES (\"$counter\", \"$itemId\", \"$vendorId\", \"$costPrice\", \"$costCurrency\", \"$sellPrice\", \"$sellCurrency\", \"" . $this->getCurrentDateTime() . "\", \"" . $this->getLoggedUserId() . "\", \"" . $this->getCurrentDateTime() . "\", \"" . $this->getLoggedUserId() . "\", \"y\")";
    	
    	if($this->processQuery($sqlQuery, $counter)){
    		$this->logOperation($counter, 'New Entry');
    		return $counter;
    	}
    	return false;
    }
    
    public function commitItemRecordUpdate($itemId){
    	if($this->commitUpdate($itemId))
    		return true;
    	return false;
    }  
    
    public function commitItemCostUpdate($itemCostId){
    	if($this->commitUpdate($itemCostId))
    		return true;
    	return false;    	
    }
    
    public function setItemCategory($itemId, $categoryId){    	
    	if($this->checkItemCategory($itemId, $categoryId))
    		return true;
    	
    	$counter = $this->getCounter('libraryItemCategory');
    	$sqlQuery = "INSERT INTO library_item_category
	    	(id, item_id, category_id, last_update_date, last_updated_by, creation_date, created_by, active)
	    	VALUES (\"$counter\", \"$itemId\", \"$categoryId\", \"" . $this->getCurrentDateTime() . "\", \"" . $this->getLoggedUserId() . "\", \"" . $this->getCurrentDateTime() . "\", \"" . $this->getLoggedUserId() . "\", \"y\")";
    	
    	if($this->processQuery($sqlQuery, $counter)){
    		$this->logOperation($counter, 'New Entry');
    		return $counter;
    	}
    	return false;
    }
    
    public function setItemTag($itemId, $tagId){
    	if($this->checkItemTag($itemId, $tagId))
    		return true;
    
    	$counter = $this->getCounter('libraryItemTag');
    	$sqlQuery = "INSERT INTO library_item_tag
				    	(id, item_id, tag_id, last_update_date, last_updated_by, creation_date, created_by, active)
				    	VALUES (\"$counter\", \"$itemId\", \"$tagId\", \"" . $this->getCurrentDateTime() . "\", \"" . $this->getLoggedUserId() . "\", \"" . $this->getCurrentDateTime() . "\", \"" . $this->getLoggedUserId() . "\", \"y\")";
    	 
    	if($this->processQuery($sqlQuery, $counter)){
    		$this->logOperation($counter, 'New Entry');
    		return $counter;
    	}
    	return false;
    }    

    //functions related to fetching data from the server
    public function getItemCostId($itemId){
    	$sqlQuery = "SELECT id FROM library_item_cost WHERE item_id = \"$itemId\" ";
    	return $this->processSingleElementQuery($sqlQuery);
    }
    
    public function checkItemCategory($itemId, $categoryId){
    	$sqlQuery = "SELECT id FROM library_item_category WHERE item_id =\"$itemId\" AND category_id = \"$categoryId\" AND active = \"y\" ";
    	return $this->processSingleElementQuery($sqlQuery);
    }
    
    public function checkItemTag($itemId, $tagId){
    	$sqlQuery = "SELECT id FROM library_item_tag WHERE item_id =\"$itemId\" AND tag_id = \"$tagId\" AND active = \"y\" ";
    	return $this->processSingleElementQuery($sqlQuery);
    }
    
    public function getItemCategoryIds($itemId, $active){
    	if($active){
    		if($active === 'all')
    			$sqlQuery = "SELECT id FROM library_item_category WHERE item_id = \"$itemId\" ";
    		else
    			$sqlQuery = "SELECT id FROM library_item_category WHERE item_id = \"$itemId\" AND active = \"y\" ";
    	}else
    		$sqlQuery = "SELECT id FROM library_item_category WHERE item_id = \"$itemId\" AND active != \"y\" ";
    		
    	return $this->getDataArray($this->processQuery($sqlQuery));
    }
    
    public function getItemCategories($itemId){
    	$sqlQuery = "SELECT category_id FROM library_item_category WHERE item_id = \"$itemId\" AND active = \"y\" ";
    	return $this->getDataArray($this->processQuery($sqlQuery));
    }
    
    public function getItemTagIds($itemId, $active){
    	if($active){
    		if($active === 'all')
    			$sqlQuery = "SELECT id FROM library_item_tag WHERE item_id = \"$itemId\" ";
    		else
    			$sqlQuery = "SELECT id FROM library_item_tag WHERE item_id = \"$itemId\" AND active = \"y\" ";
    	}else
    		$sqlQuery = "SELECT id FROM library_item_tag WHERE item_id = \"$itemId\" AND active != \"y\" ";
    	
    	return $this->getDataArray($this->processQuery($sqlQuery));
    }
    
    public function getItemTags($itemId){
    	$sqlQuery = "SELECT tag_id FROM library_item_tag WHERE item_id = \"$itemId\" AND active = \"y\" ";
    	return $this->getDataArray($this->processQuery($sqlQuery));
    }
    
    
    
    
    
}
?>