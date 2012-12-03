<?php
/**
 * This class will hold the functionalities regarding the user designation ranks
 * This class extends the general class
 * @author Hemant Kumar Sah
 * @category Global
 * @license Support-Gurukul
 * @version 1.0.0
 */
require_once 'class.general.php';

class MenuTask extends general {
	
	public function __construct() {
		parent::__construct ();
	}
	
	public function setMenuTaskAssignment($userId, $displayName, $url, $sourceId, $comments, $startDate, $endDate){
		$counter = $this->getCounter("taskMenuAssigned");
		$sqlQuery = "INSERT INTO glb_menu_task_assignment 
						(id, user_id, menu_display_name, menu_url, source_id, comments, start_date, end_date, last_update_date, last_updated_by, creation_date, created_by, active) 
						VALUES (\"$counter\", \"$userId\", \"$displayName\", \"$url\", \"$sourceId\", \"$comments\", \"$startDate\", \"$endDate\", \"" . $this->getCurrentDateTime () . "\", \"" . $this->getLoggedUserId () . "\", \"" . $this->getCurrentDateTime () . "\", \"" . $this->getLoggedUserId () . "\", \"y\") ";
		
		if($this->processQuery($sqlQuery, $counter)){
			$this->logOperation($userId, "The user has been assigned a new task");
			$this->logOperation($counter, "New entry made");
			return $counter;
		}
		return false;
	}
	
	public function commitMenuTaskAssignmentUpdate($menuTaskId){		
		if ($this->sqlConstructQuery == ""){					
			return $menuTaskId;
		}
		$this->commitUpdate($menuTaskId);
	}
	
	public function getMenuTaskId4SourceId($sourceId){
		return $this->getValue('id', 'glb_menu_task_assignment', 'source_id', $sourceId);
	}
	
	public function getUserMenuTaskRecords($userId, $active){		
		if($active){
			if($active === "all")
				$sqlQuery = "SELECT id FROM glb_menu_task_assignment
								WHERE user_id = \"$userId\" 
								ORDER BY end_date DESC ";
			else
				$sqlQuery = "SELECT id FROM glb_menu_task_assignment
								WHERE user_id = \"$userId\" 
									AND start_date <= \"".$this->getCurrentDate()."\"
									AND end_date >= \"".$this->getCurrentDate()."\"
									AND complete_flag != \"y\"
									AND active = \"y\"
								ORDER BY menu_display_name, end_date DESC ";
		}else 
			$sqlQuery = "SELECT id FROM glb_menu_task_assignment
							WHERE user_id = \"$userId\"
								AND complete_flag = \"y\"
							ORDER BY end_date DESC ";
		return $this->getDataArray($this->processQuery($sqlQuery));
	}
	
	public function getUserFutureMenuTaskRecords($userId, $active){
		$sqlQuery = "SELECT id FROM glb_menu_task_assignment
						WHERE  user_id = \"$userId\" 
							AND start_date > \"".$this->getCurrentDate()."\"
							AND active = \"y\"
						ORDER BY menu_display_name, end_date DESC ";
		return $this->getDataArray($this->processQuery($sqlQuery));
	}
	
	public function dropMenuTaskAssignment($menuTaskId){
		if ($this->dropTableId ( $menuTaskId, false )) {
			$this->logOperation ( $menuTaskId, "The menu task details Has Been Dropped" );
			return true;
		}
		return false;
	}
	
	public function activateMenuTaskAssignment($menuTaskId){
		if ($this->activateTableId ( $menuTaskId )) {
			$this->logOperation ( $menuTaskId, "The Menu Task  Details Has Been Activated" );
			return true;
		}
		return false;
	}
	
		
	//functions related to the menu attribute assignment
	public function getMenuTaskAttributes($menuTaskId){
		$sqlQuery = "SELECT * from glb_menu_attributes WHERE menu_task_id = \"$menuTaskId\" ";
		return $this->processArray($sqlQuery);
	}
	
	public function setMenuTaskAttributes($menuTaskId, $attributeNo, $attributeArray){
		$counter = $this->getCounter("menuAttribute");
		
		$sqlQuery = "INSERT INTO glb_menu_attributes (id, menu_task_id, last_update_date, last_updated_by, creation_date, created_by, active) 
						VALUES (\"$counter\", \"$menuTaskId\", \"" . $this->getCurrentDateTime () . "\", \"" . $this->getLoggedUserId () . "\", \"" . $this->getCurrentDateTime () . "\", \"" . $this->getLoggedUserId () . "\", \"y\") ";
		if($this->processQuery($sqlQuery, $counter)){
			$this->logOperation($counter, "Menu attribute has been added");
			$this->logOperation($menuTaskId, "The attribute has been added");
			
			//adding the attributes
			
			for($i = 1; $i <= $attributeNo; ++$i){
				$attributeName = "attribute".$i;
				$this->updateTableParameter($attributeName, $attributeArray[$i - 1]);
			}
			$this->commitMenuTaskAttributeUpdate($counter);
			
			return $counter;
		}
		return false;
	}
	
	public function commitMenuTaskAttributeUpdate($attributeId){
		if ($this->sqlConstructQuery == "")
			return $attributeId;
		
		$this->sqlConstructQuery .= ", last_update_date=\"" . $this->getCurrentDateTime () . "\", last_updated_by=\"" . $this->getLoggedUserId () . "\"";
		$sqlQuery = "UPDATE glb_menu_attributes
						SET $this->sqlConstructQuery
						WHERE id = \"$attributeId\" ";
		
		$this->sqlConstructQuery = "";
		if ($this->processQuery ( $sqlQuery, $attributeId )) {
			$this->logOperation ( $attributeId, "The Attribute Details Has Been Updated" );
			return true;
		}
		return false;
	}
	
	public function dropMenuTaskAttribute($attributeId){
		if ($this->dropTableId ( $attributeId, false )) {
			$this->logOperation ( $attributeId, "The Attribute Details Has Been Dropped" );
			return true;
		}
		return false;
	}
	
	public function activateMenuTaskAttribute($attributeId){
		if ($this->activateTableId ( $attributeId )) {
			$this->logOperation ( $attributeId, "The Attribute  Details Has Been Activated" );
			return true;
		}
		return false;
	}
	
}
?>