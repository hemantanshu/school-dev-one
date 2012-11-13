<?php
	require_once 'config.php';
	require_once BASE_PATH.'include/global/class.general.php';
	
	class menuAssignmentCP extends general{
		public function __construct(){
			parent::__construct();
		}	
		
		public function checkUserAssignment($userId, $menuId){
			$sqlQuery = "SELECT id 
								FROM glb_menu_user_assignment 
								WHERE user_id = \"$userId\" 
									AND menu_id = \"$menuId\" ";
			$sqlQuery = $this->processArray($sqlQuery);
			if($sqlQuery['id'] != ""){
				$this->updateUserAssignment($sqlQuery['id']);
				return false;			
			}
			return true;		
		}
		public function checkUserAuthentication($userId, $menuId){
			//@todo work on the editable poriton need to see that the edit enabled has the priority over all others 
			$sqlQuery = "SELECT id 
							FROM glb_menu_user_authentication 
							WHERE user_id = \"$userId\"								
								AND menu_id = \"$menuId\" ";
			$sqlQuery = $this->processArray($sqlQuery);
			if($sqlQuery['id'] != ""){
				$this->updateUserAuthentication($sqlQuery['id']);
				return false;
			}
			return true;
		}
		public function insertUserAssignment($userId, $menuId, $topMenu, $priority){
			$counter = $this->getCounter("menu_user_assignment");
			$sqlQuery = "INSERT 
							INTO glb_menu_user_assignment 
							(id, user_id, menu_id, top_menu_flag, priority, last_update_date, last_updated_by, creation_date, created_by, operation_flag) VALUES 
							(\"$counter\", \"$userId\", \"$menuId\", \"$topMenu\", \"$priority\", \"".$this->getCurrentDateTime()."\", \"".$this->getLoggedUserId()."\", \"".$this->getCurrentDateTime()."\", \"".$this->getLoggedUserId()."\", \"y\")";			
			$this->processQuery($sqlQuery);
			return $counter;
		}
		public function insertUserAuthentication($userId, $menuId, $edit, $assignment){
			$counter = $this->getCounter("menu_authentication");
			$sqlQuery = "INSERT 
							INTO glb_menu_user_authentication 
							(id, user_id, menu_id, edit_enabled, assignment, last_update_date, last_updated_by, creation_date, created_by, operation_flag) VALUES 
							(\"$counter\", \"$userId\", \"$menuId\", \"$edit\", \"$assignment\", \"".$this->getCurrentDateTime()."\", \"".$this->getLoggedUserId()."\", \"".$this->getCurrentDateTime()."\", \"".$this->getLoggedUserId()."\", \"y\")";
			
			$this->processQuery($sqlQuery);
		}
		public function cleanupFlag(){
			$sqlQuery = "UPDATE glb_menu_user_authentication SET operation_flag = \"\" ";
			$this->processQuery($sqlQuery);
			
			$sqlQuery = "UPDATE glb_menu_user_assignment SET operation_flag = \"\" ";
			$this->processQuery($sqlQuery);
		}
		
		public function dropUnusedFlag(){
			$sqlQuery = "DELETE 
							FROM glb_menu_user_authentication 
							WHERE operation_flag = \"\"";
			$this->processQuery($sqlQuery);
			
			$sqlQuery = "DELETE
							FROM glb_menu_user_assignment
							WHERE operation_flag = \"\"";
			$this->processQuery($sqlQuery);
		}
		public function updateUserAssignment($id){
			$sqlQuery = "UPDATE glb_menu_user_assignment 
							SET operation_flag = \"y\" 
							WHERE id = \"$id\" ";
			$this->processQuery($sqlQuery);
		}
		public function updateUserAuthentication($id){
			$sqlQuery = "UPDATE glb_menu_user_authentication 
							SET operation_flag = \"y\" 
							WHERE id =\"$id\" ";
			$this->processQuery($sqlQuery);
		}		
	}
	
	require_once BASE_PATH.'include/global/class.editMenu.php';
	require_once BASE_PATH.'include/global/class.options.php';
	
	$assignment = new menuAssignmentCP();
	$menu = new editMenu();
	$options = new options();
	
	//processing for all the assignment ids when no parameter has been provided
	$assignmentIds = $menu->getActiveAssignmentIds();
	foreach($assignmentIds as $assignmentId){
		$details = $menu->getMenuAssignmentIdDetails($assignmentId);
		if($details['user_flag'] == 'y'){
			//the assignment is for a user
			if($details['menu_top_flag'] == 'y'){
				//the menu is a top menu
				$topMenuDetails = $menu->getMenuTopIdDetails($details['menu_id']);
				$menuIds = $menu->getTopMenuAssignedMenus($details['menu_id']);
				foreach($menuIds as $menuId){
					//assigning it to the authentication table
					if($assignment->checkUserAuthentication($details['generic_id'], $menuId))
						$assignment->insertUserAuthentication($details['generic_id'], $menuId, $details['editable'], $details['menu_id']);
				}				
				//assigning it to the assignment table
				if($assignment->checkUserAssignment($details['generic_id'], $details['menu_id']))
					$assignment->insertUserAssignment($details['generic_id'], $details['menu_id'], 'y', $topMenuDetails['menu_priority']);
			}else{		
				//the menu is a normal menu url
				if($assignment->checkUserAssignment($details['generic_id'], $details['menu_id']))
					$assignment->insertUserAssignment($details['generic_id'], $details['menu_id'], '', 1);
				if($assignment->checkUserAuthentication($details['generic_id'], $details['menu_id']))
					$assignment->insertUserAuthentication($details['generic_id'], $details['menu_id'], $details['editable'], '');			
			}
		}else{
			//the assignment is for a usergroup
			$ids = $options->getAssignmentIds($details['generic_id'], 'USRGP', true, 1, 10000);
			foreach($ids as $id){
				$userId = $options->getAssignmentIdValue($id);
				if($details['menu_top_flag'] == 'y'){
					//the menu is a top menu
					$topMenuDetails = $menu->getMenuTopIdDetails($details['menu_id']);
					$menuIds = $menu->getTopMenuAssignedMenus($details['menu_id']);
					foreach($menuIds as $menuId){
						//assigning it to the authentication table
						if($assignment->checkUserAuthentication($userId, $menuId))
							$assignment->insertUserAuthentication($userId, $menuId, $details['editable'], $details['menu_id']);
					}
					//assigning it to the assignment table					
					if($assignment->checkUserAssignment($userId, $details['menu_id'])){
						$assignment->insertUserAssignment($userId, $details['menu_id'], 'y', $topMenuDetails['menu_priority']);
					}
						
				}else{					
					//the menu is a normal menu url
					if($assignment->checkUserAssignment($details['generic_id'], $details['menu_id']))
						$assignment->insertUserAssignment($details['generic_id'], $details['menu_id'], '', 1);
					if($assignment->checkUserAuthentication($details['generic_id'], $details['menu_id']))
						$assignment->insertUserAuthentication($details['generic_id'], $details['menu_id'], $details['editable'], '');
				}
			}
		}
	}
	$assignment->dropUnusedFlag();
	$assignment->cleanupFlag();

	
?>
<script>
	alert('The Menus Have Been Successfully Created');
	window.close();
</script>