<?php

/**
 * This class will hold the functionalities related to the editing of the menu items
 * This class extends the general class
 * @author Hemant Kumar Sah
 * @category Global
 * @license Support-Gurukul
 * @version 1.0.0
 */
require_once 'class.menu.php';

class editMenu extends menu {
	private $_menuIds;
	private $_submenuFailure;
	private $_dependentMenus;
	public function __construct() {
		parent::__construct ();
		$this->_menuIds = array ();
		$this->_submenuFailure = false;
	}
	
	/**
	 * start of the functions related to the menu url *
	 */
	
	/**
	 * This method is used to insert a new menu url in the base table
	 * 
	 * @param $menu_name Var
	 *       	 The name identifier of the menu url, should follow the pattern
	 *        	same as the menu name. Add suffix the name of the module
	 * @param $menu_url Var
	 *       	 The relative or absolute url of the page
	 * @param $url_source_id Var
	 *       	 The relative url id which will be appended to the url
	 * @param $menu_editable Flag
	 *       	 Y|N|Null Y is to be set if the page is going to perform any
	 *        	updation or insertion operation else N or Null
	 * @param $menu_authorized Flag
	 *       	 Y|N|Null Y is to be set if the page is to be authorised with
	 *        	login, or else N or Null
	 * @param $active Flag
	 *       	 Y|N|Null to be set, so that the menu active state can be
	 *        	determined. Y for Active or else N or Null
	 * @param $menu_image_url Var
	 *       	 The path of the image used in the image layout
	 * @param $menu_tagline Var
	 *       	 The tagline of the menu (short and descriptive)
	 * @param $menu_description Var
	 *       	 The description of the menu
	 * @return Var MenuId|False which was created on successful or false on
	 *         failure
	 */
	public function setNewMenuUrl($menu_name, $menu_url, $url_source_id, $menu_editable, $menu_authorized, $active, $menu_image_url = '', $menu_tagline = '', $menu_description = '', $parent_menu = '') {
		$counter = $this->getCounter ( "menu_url" );
		$sqlQuery = "INSERT INTO glb_menu_url 
						(id, menu_name, menu_url, menu_image_url, url_source_id, menu_tagline, menu_description, menu_editable, menu_authorized, last_update_date, last_updated_by, creation_date, created_by, active) 
						VALUES (\"$counter\", \"$menu_name\", \"$menu_url\", \"$menu_image_url\", \"$url_source_id\", \"$menu_tagline\", \"$menu_description\", \"$menu_editable\", \"$menu_authorized\", \"" . $this->getCurrentDateTime () . "\", \"" . $this->getLoggedUserId () . "\", \"" . $this->getCurrentDateTime () . "\", \"" . $this->getLoggedUserId () . "\", \"$active\")";
		
		if ($this->processQuery ( $sqlQuery, $counter )) {
			$this->logOperation ( $counter, "New Menu With Name $menu_name Created" );
			return $counter;
		}
		return false;
	}
	
	public function checkMenuUrlName($urlName) {
		$sqlQuery = "SELECT id FROM glb_menu_url WHERE upper(menu_name) = \"" . strtoupper ( $urlName ) . "\" ";
		$sqlQuery = $this->processQuery ( $sqlQuery );
		if (mysql_num_rows ( $sqlQuery ))
			return false;
		return true;
	}
	
	/**
	 * The update of the menu url upon finally creation of the set query using
	 * the other method updateTableParameter
	 * 
	 * @param $id Var
	 *       	 The menu id against which the update has to be performed
	 * @return Boolean True|False ,True on success and false on failure or no
	 *         updation
	 */
	public function commitUpdateMenuUrl($id) {
		if ($this->sqlConstructQuery == "")
			return $id;	
		return $this->commitUpdate($id);
	}
	
	/**
	 * The method to drop the menu url
	 * 
	 * @param $id Var
	 *       	 The menu id which has to be dropped
	 * @return Boolean True|False True on successful execution, False on failure
	 */
	public function dropMenuUrl($id) {
		if ($this->dropTableId ( $id, false )) {
			$this->logOperation ( $id, "The Menu Has Been Dropped" );
			return true;
		}
		return false;
	}
	
	/**
	 * The method to activate the inactive menus
	 * 
	 * @param $id Var
	 *       	 The menu id which has to be activated
	 * @return Boolean True|False , True on successful execution, False on
	 *         failure
	 */
	public function activateMenuUrl($id) {
		if ($this->activateTableId ( $id )) {
			$this->logOperation ( $id, "The Menu Has Been Activated" );
			return true;
		}
		return false;
	}
	
	/**
	 * end of the functions related to the menu url *
	 */
	/**
	 * start of the functions related to the menu submenu *
	 */
	
	/**
	 * The method is used to create a new submenu
	 * 
	 * @param $submenu_name Var
	 *       	 The name of the new created submenu, must be short and
	 *        	descriptive
	 * @param $submenu_description Var
	 *       	 The description of the submenu which will define the operation
	 *        	of the submenu
	 * @return Var Submenu_id|False , The submenu id on the success, false on
	 *         the failure to create so
	 */
	public function setNewSubMenu($submenu_name, $submenu_description = '') {
		$counter = $this->getCounter ( "menu_submenu" );
		$sqlQuery = "INSERT	INTO glb_menu_submenu 
						(id, submenu_name, submenu_description, last_update_date, last_updated_by, creation_date, created_by, active) 
						VALUES (\"$counter\", \"$submenu_name\", \"$submenu_description\", \"" . $this->getCurrentDateTime () . "\", \"" . $this->getLoggedUserId () . "\", \"" . $this->getCurrentDateTime () . "\", \"" . $this->getLoggedUserId () . "\", \"y\")";
		if ($this->processQuery ( $sqlQuery, $counter )) {
			$this->logOperation ( $counter, "New Submenu Created" );
			return $counter;
		}
		return false;
	}
	
	public function checkMenuSubmenuName($urlName) {
		$sqlQuery = "SELECT id FROM glb_menu_submenu WHERE upper(submenu_name) = \"" . strtoupper ( $urlName ) . "\" ";
		$sqlQuery = $this->processQuery ( $sqlQuery );
		if (mysql_num_rows ( $sqlQuery ))
			return false;
		return true;
	}
	/**
	 * The update of the menu submenu upon finally creation of the set query
	 * using the other method updateTableParameter
	 * 
	 * @param $id Var
	 *       	 The submenu id against which the update has to be performed
	 * @return Boolean True|False , True on success and false on failure or no
	 *         updation
	 */
	public function commitUpdateSubmenu($id) {
		if ($this->sqlConstructQuery == "")
			return false;
		
		$this->sqlConstructQuery .= ", last_update_date=\"" . $this->getCurrentDateTime () . "\", last_updated_by=\"" . $this->getLoggedUserId () . "\"";
		$sqlQuery = "UPDATE glb_menu_submenu 
						SET $this->sqlConstructQuery 
						WHERE id = \"$id\" ";
		
		$this->sqlConstructQuery = "";
		
		if ($this->processQuery ( $sqlQuery, $id )) {
			$this->logOperation ( $id, "The Menu Submenu Properties Has Been Updated" );
			return true;
		}
		return false;
	}
	
	/**
	 * The method to drop the submenu
	 * 
	 * @param $id Var
	 *       	 The menu id which has to be dropped
	 * @return Boolean True|False True on successful execution, False on failure
	 */
	public function dropMenuSubmenu($id) {
		if ($this->dropTableId ( $id, false )) {
			$this->logOperation ( $id, "The Submenu Has Been Dropped" );
			return true;
		}
		return false;
	}
	
	/**
	 * The method to activate the inactive submenu
	 * 
	 * @param $id Var
	 *       	 The submenu id which has to be activated
	 * @return Boolean True|False , True on successful execution, False on
	 *         failure
	 */
	public function activateMenuSubmenu($id) {
		if ($this->activateTableId ( $id )) {
			$this->logOperation ( $id, "The Submenu Has Been Activated" );
			return true;
		}
		return false;
	}
	
	/**
	 * start of the functions related to the menu submenu *
	 */
	/**
	 * start of the functions related to the menu submenu url assignment *
	 */
	
	/**
	 * The method is used to create a new url entry in the submenu, which will
	 * populate the submenu
	 * 
	 * @param $menu_name Var
	 *       	 The name displayed in the public against that menu
	 * @param $menu_url_id Var
	 *       	 The url id of the menu which is assigned to the given submenu
	 * @param $submenu_parent_id Var
	 *       	 The submenu against which the menu has been assigned
	 * @param $menu_redirect Flag
	 *       	 Y|N|Null, if set to y, would redirect to the blank tab and in
	 *        	parent tab in all other cases
	 * @param $menu_priority Num
	 *       	 The integer value which will determine the position of the
	 *        	menu relative to others in the menu
	 * @param $active Flag
	 *       	 Y|N|Null The menu url which is either active or not in the
	 *        	submenu with literals having their usual meaning
	 * @return Var Submenu_url_id|False , submenu url id on the successful
	 *         execution, false on failure
	 */
	public function setNewSubmenuUrl($menu_name, $menu_url_id, $submenu_parent_id, $submenu_child_id, $menu_redirect, $menu_priority, $active) {
		$counter = $this->getCounter ( "menu_submenu_url" );
		
		$sqlQuery = "INSERT INTO glb_menu_submenu_url 
							(id, menu_name, menu_url_id, submenu_parent_id, submenu_child_id, menu_redirect, menu_priority, last_update_date, last_updated_by, creation_date, created_by, active) 
							VALUES (\"$counter\", \"$menu_name\", \"$menu_url_id\", \"$submenu_parent_id\", \"$submenu_child_id\", \"$menu_redirect\", \"$menu_priority\", \"" . $this->getCurrentDateTime () . "\", \"" . $this->getLoggedUserId () . "\", \"" . $this->getCurrentDateTime () . "\", \"" . $this->getLoggedUserId () . "\", \"$active\")";
		if ($this->processQuery ( $sqlQuery, $counter )) {
			$this->logOperation ( $submenu_parent_id, "New URL For The submenu has been added" );
			return $counter;
		}
		return false;
	}
	
	/**
	 * The method is used to create a child submenu for a given menu url in a
	 * submenu url
	 * 
	 * @param $id Var
	 *       	 The submenu url id which has to be assigned the child submenu
	 * @param $submenu_child_id Var
	 *       	 The submenu id which has to be assigned
	 * @return Boolean True|False , True on successful execution , false on
	 *         failure
	 */
	public function setSubmenuUrlChildSubmenu($id, $submenu_child_id) {
		$sqlQuery = "UPDATE glb_menu_submenu_url 
							SET submenu_child_id = \"$submenu_child_id\" 
							WHERE id = \"$id\" ";
		if ($this->processQuery ( $sqlQuery, $id ))
			return true;
		return false;
	}
	
	/**
	 * The update of the submenu url upon finally creation of the set query
	 * using the other method updateTableParameter
	 * 
	 * @param $id Var
	 *       	 The submenu url id against which the update has to be
	 *        	performed
	 * @return Boolean True|False , True on success and false on failure or no
	 *         updation
	 */
	public function commitSubmenuUrlUpdate($id) {
		if ($this->sqlConstructQuery == "")
			return false;
		
		return $this->commitUpdate($id);
	}
	
	/**
	 * The method to drop the submenu url
	 * 
	 * @param $id Var
	 *       	 The submenu url id which has to be dropped
	 * @return Boolean True|False True on successful execution, False on failure
	 */
	public function dropSubmenuUrl($id) {
		if ($this->dropTableId ( $id, false )) {
			$this->logOperation ( $id, "The Submenu Url Has Been Dropped" );
			return true;
		}
		return false;
	}
	
	/**
	 * The method to activate the inactive submenu url
	 * 
	 * @param $id Var
	 *       	 The submenu id which has to be activated
	 * @return Boolean True|False , True on successful execution, False on
	 *         failure
	 */
	public function activateSubmenuUrl($id) {
		if ($this->activateTableId ( $id )) {
			$this->logOperation ( $id, "The Submenu URL Has Been Activated" );
			return true;
		}
		return false;
	}
	
	/**
	 * End of the functions related to the menu submenu url assignment *
	 */
	/**
	 * Start Of The functions related to the top menu *
	 */
	
	/**
	 * The method is used to create a new top menu
	 * 
	 * @param $menu_name Var
	 *       	 The name of the top menu which will be displayed atop
	 * @param $menu_description Var
	 *       	 The description of the menu top
	 * @param $menu_url_id Var
	 *       	 The menu url which has been assigned to the top menu
	 * @param $submenu_id Var
	 *       	 The submenu association of the top menu
	 * @param $menu_redirect Flag
	 *       	 Y|N|Null, if set to y, would redirect to the blank tab and in
	 *        	parent tab in all other cases
	 * @param $menu_priority Num
	 *       	 The integer value which will determine the position of the
	 *        	menu relative to others in the menu
	 * @param $authentication Flag
	 *       	 Y|N|NULL The authentication required for the display of this
	 *        	menu
	 * @param $active Flag
	 *       	 Y|N|NULL The top menu if is active or not
	 * @return Var Topmenu_id|False , top menu id on successful operation, false
	 *         on failure
	 */
	public function setNewTopMenu($menu_name, $menu_description, $menu_url_id, $submenu_id, $menu_redirect, $menu_priority, $authentication, $active) {
		$counter = $this->getCounter ( "menu_top" );
		
		$sqlQuery = "INSERT INTO glb_menu_top 
						(id, menu_name, menu_description, menu_url_id, submenu_id, menu_redirect, menu_priority, authentication, last_update_date, last_updated_by, creation_date, created_by, active) 
            			VALUES (\"$counter\", \"$menu_name\", \"$menu_description\", \"$menu_url_id\", \"$submenu_id\", \"$menu_redirect\", \"$menu_priority\", \"$authentication\", \"" . $this->getCurrentDateTime () . "\", \"" . $this->getLoggedUserId () . "\", \"" . $this->getCurrentDateTime () . "\", \"" . $this->getLoggedUserId () . "\", \"$active\")";
		if ($this->processQuery ( $sqlQuery, $counter )) {
			$this->logOperation ( $counter, "The New Top Menu Has Been Defined" );
			return $counter;
		}
		return false;
	}
	
	public function checkMenuTopName($urlName) {
		$sqlQuery = "SELECT id FROM glb_menu_top WHERE upper(menu_name) = \"" . strtoupper ( $urlName ) . "\" ";
		$sqlQuery = $this->processQuery ( $sqlQuery );
		if (mysql_num_rows ( $sqlQuery ))
			return false;
		return true;
	}
	/**
	 * The update of the topmenu upon finally creation of the set query using
	 * the other method updateTableParameter
	 * 
	 * @param $id Var
	 *       	 The topmenu id against which the update has to be performed
	 * @return Boolean True|False , True on success and false on failure or no
	 *         updation
	 */
	public function commitTopMenuUpdate($id) {
		if ($this->sqlConstructQuery == "")
			return false;
		
		return $this->commitUpdate($id);
	}
	
	/**
	 * The method to drop the topmenu url
	 * 
	 * @param $id Var
	 *       	 The topmenu id which has to be dropped
	 * @return Boolean True|False True on successful execution, False on failure
	 */
	public function dropTopMenu($id) {
		if ($this->dropTableId ( $id, false )) {
			$this->logOperation ( $id, "The top menu has been dropped" );
			return true;
		}
		return false;
	}
	
	/**
	 * The method to activate the inactive topmenu
	 * 
	 * @param $id Var
	 *       	 The topmenu id which has to be activated
	 * @return Boolean True|False , True on successful execution, False on
	 *         failure
	 */
	public function activateTopMenu($id) {
		if ($this->activateTableId ( $id )) {
			$this->logOperation ( $id, "The top menu has been activated" );
			return true;
		}
		return false;
	}
	
	/**
	 * End of the functions related to the top menu *
	 */
	
	/**
	 * start of the functions related to the group assignment of the menus *
	 */
	/**
	 * The method will be used to create new menu assignment for usergroups as
	 * well as users
	 * 
	 * @param $generic_id var
	 *       	 The generic id usergroup/individual against which the menu has
	 *        	been assigned
	 * @param $menu_id var
	 *       	 The topmenu/menu_url which has been assigned to the generic id
	 * @param $menu_top flag
	 *       	 y/n/blank Whether the menu assigned is a top menu or not. if
	 *        	yes then y flag
	 * @param $editable flag
	 *       	 y/n/blank Whether the assigned menu has editable priveleges as
	 *        	well or not
	 * @param $start_date date
	 *       	 the date from when the menu will be active for the generic
	 *        	user
	 * @param $end_date date
	 *       	 the last date as of when the menu will expire
	 * @return var Assignment Id on successful completion / false on failure
	 */
	public function setMenuAssignment($generic_id, $user_flag, $menu_id, $menu_top_flag, $editable, $start_date, $end_date, $sourceId = '') {
		$counter = $this->getCounter ( 'menu_assignment' );
		
		$sqlQuery = "INSERT INTO glb_menu_assignment 
                            (id, generic_id, user_flag, menu_id, menu_top_flag, editable, start_date, end_date, source_id, last_update_date, last_updated_by, creation_date, created_by, active) 
                            VALUES (\"$counter\", \"$generic_id\", \"$user_flag\", \"$menu_id\", \"$menu_top_flag\", \"$editable\", \"$start_date\", \"$end_date\", \"$sourceId\", \"" . $this->getCurrentDateTime () . "\", \"" . $this->getLoggedUserId () . "\", \"" . $this->getCurrentDateTime () . "\", \"" . $this->getLoggedUserId () . "\", \"y\")";
		if ($this->processQuery ( $sqlQuery, $counter )) {
			$this->logOperation ( $counter, 'The Menu Assignment Has Been Inserted' );
			return $counter;
		}
		return false;
	}
	
	/**
	 * This method is used to commit the update of the menu assignment
	 * 
	 * @param $id var
	 *       	 The assignment id which has to be commited for update
	 * @return boolean True/False. True on successful operation/ false on
	 *         failure
	 */
	public function commitMenuAssignment($id) {
		if ($this->sqlConstructQuery == "")
			return false;
		
		return $this->commitUpdate($id);
	}
	
	/**
	 * The method is used to drop a menu assignment
	 * 
	 * @param $id var
	 *       	 The assignment id that has to be dropped
	 * @return boolean True/False. True on successful operation/ false on
	 *         failure
	 */
	public function dropMenuAssignment($id) {
		if ($this->dropTableId ( $id, false )) {
			$this->logOperation ( $id, "The Assignment has been dropped" );
			return true;
		}
		return false;
	}
	
	/**
	 * The method is used to activate a dropped assignment
	 * 
	 * @param $id var
	 *       	 The assignment id that has to be activated
	 * @return boolean True/False. True on successful operation/ false on
	 *         failure
	 */
	
	public function activateMenuAssignment($id) {
		if ($this->activateTableId ( $id )) {
			$this->logOperation ( $id, "The Assignment has been activated" );
			return true;
		}
		return false;
	}
	
	public function setMenuUserAssignment($userId, $menuId, $topMenu, $priority) {
		$counter = $this->getCounter ( "menu_user_assignment" );
		$sqlQuery = "INSERT 
    					INTO glb_menu_user_assignment_bak 
    					(id, user_id, menu_id, top_menu, priority, last_update_date, last_updated_by, creation_date, created_by) VALUES 
    					(\"$counter\", \"$userId\", \"$menuId\", \"$topMenu\", \"$priority\", \"" . $this->getCurrentDateTime () . "\", \"" . $this->getLoggedUserId () . "\", \"" . $this->getCurrentDateTime () . "\", \"" . $this->getLoggedUserId () . "\")";
		$sqlQuery = $this->processQuery ( $sqlQuery, $counter );
		if ($sqlQuery) {
			$this->logOperation ( $counter, "New Menu Assigned" );
			$this->logOperation ( $menuId, "Assigned To User" );
			return $counter;
		}
		return false;
	}
	
	public function setMenuUserAuthentication($userId, $menuId, $topMenu, $editEnabled) {
		if ($topMenu) {
			$menuIds = $this->getTopMenuAssignedMenus ( $menuId );
			foreach ( $menuIds as $id ) {
				$counter = "menu_authentication";
				$sqlQuery = "INSERT 
    							INTO glb_menu_user_authentication_bak 
    							(id, user_id, menu_id, edit_enabled, assignment, last_update_date, last_updated_by, creation_date, created_by) VALUES 
    							(\"$counter\", \"$userId\", \"$id\", \"$editEnabled\", \"$topMenu\", \"" . $this->getCurrentDateTime () . "\", \"" . $this->getLoggedUserId () . "\", \"" . $this->getCurrentDateTime () . "\", \"" . $this->getLoggedUserId () . "\")";
				$this->processQuery ( $sqlQuery, $counter );
			}
		} else {
			$counter = "menu_authentication";
			$sqlQuery = "INSERT
    							INTO glb_menu_user_authentication_bak
    							(id, user_id, menu_id, edit_enabled, assignment, last_update_date, last_updated_by, creation_date, created_by) VALUES
    							(\"$counter\", \"$userId\", \"$id\", \"$editEnabled\", \"$topMenu\", \"" . $this->getCurrentDateTime () . "\", \"" . $this->getLoggedUserId () . "\", \"" . $this->getCurrentDateTime () . "\", \"" . $this->getLoggedUserId () . "\")";
			$this->processQuery ( $sqlQuery, $counter );
		}
		return true;
	}
	public function commitMenuUserAssignment() {
		$sqlQuery = "TRUNCATE TABLE glb_menu_user_assignment";
		$this->processQuery ( $sqlQuery );
		$sqlQuery = "INSERT INTO glb_menu_user_assignment (SELECT * FROM glb_menu_user_assignment_bak)";
		$this->processQuery ( $sqlQuery );
		
		$sqlQuery = "TRUNCATE TABLE glb_menu_user_authentication";
		$this->processQuery ( $sqlQuery );
		$sqlQuery = "INSERT INTO glb_menu_user_authentication (SELECT * FROM glb_menu_user_authentication_bak)";
		$this->processQuery ( $sqlQuery );
		
		return true;
	}
	public function getTopMenuAssignedMenus($topMenuId) {
		$menuIds = array ();
		$details = $this->getMenuTopIdDetails ( $topMenuId );
		array_push ( $menuIds, $details ['menu_url_id'] );
		if ($details ['submenu_id'] != "") {
			$this->getSubmenuAssignedMenus ( $details ['submenu_id'] );
		}
		$menuIds = $this->_menuIds;
		foreach($this->_menuIds as $menuId){
			$this->_dependentMenus = array();
			$dependentIds = $this->getDependentMenus($menuId);
			foreach ($dependentIds as $dependentId)
				array_push($menuIds, $dependentId);
		}
		$this->_menuIds = array ();
		$this->_submenuFailure = false;
		return array_unique($menuIds);
	}
	
	private function getSubmenuAssignedMenus($submenuId) {
		$assignmentIds = $this->getMenuSubmenuAssignedURLAssignmentIds ( $submenuId );
		foreach ( $assignmentIds as $assignmentId ) {
			$details = $this->getMenuAssignmentIdDetails ( $assignmentId );			
			array_push ( $this->_menuIds, $details ['menu_url_id'] );
			if ($details ['submenu_child_id'] != "")
				$this->getSubmenuAssignedMenus ( $details ['submenu_child_id'] );
		}
	}
	
	public function checkChildSubmenuAssignment($menuId, $submenuId) {
		// array_push($this->_menuIds, $menuId);
		$this->getSubmenuAssignedMenus ( $submenuId );
		$this->_menuIds = array ();
		$status = $this->_submenuFailure;
		$this->_submenuFailure = false;
		return $status;
	}
	/**
	 * End of the functions related to the group assignment of the menus *
	 */
	
	// functions related to menu parent
	public function setMenuChildParentDetails($parentMenu, $childMenu) {
		if ($this->checkParentChildMenuAvailability ( $parentMenu, $childMenu ))
			return false;
		
		$counter = $this->getCounter ( 'parentMenu' );
		$sqlQuery = "INSERT INTO glb_menu_parent (id, parent_menu_id, child_menu_id, last_update_date, last_updated_by, creation_date, created_by, active) 
        				VALUES (\"$counter\", \"$parentMenu\", \"$childMenu\", \"" . $this->getCurrentDateTime () . "\", \"" . $this->getLoggedUserId () . "\", \"" . $this->getCurrentDateTime () . "\", \"" . $this->getLoggedUserId () . "\", \"y\")";
		if ($this->processQuery ( $sqlQuery, $counter )) {
			$this->logOperation ( $counter, 'The menu parent has been successfully associated' );
			$this->logOperation ( $childMenu, 'The parent menu has been set' );
			return $counter;
		}
		return false;
	}
	public function dropMenuChildParentDetails($menuId) {
		if ($this->dropTableId ( $menuId, false )) {
			$this->logOperation ( $menuId, "The Parent Menu Assignment has been dropped" );
			return true;
		}
		return false;
	}
	public function activateMenuChildParentUpdate($menuId) {
		if ($this->activateTableId ( $menuId )) {
			$this->logOperation ( $menuId, "The Parent Menu Assignment has been activated" );
			return true;
		}
		return false;
	}
	public function checkParentChildMenuAvailability($parentMenu, $childMenu) {
		$sqlQuery = "SELECT id
                            FROM glb_menu_parent
                            WHERE parent_menu_id = \"$parentMenu\"
                             AND child_menu_id = \"$childMenu\" ";
		$sqlQuery = $this->processArray ( $sqlQuery );
		if ($sqlQuery [0] != '')
			return $sqlQuery [0];
		return false;
	}
	public function getMenuChildParentId($menuId, $active, $flag) {
		$columnName = $flag ? 'child_menu_id' : 'parent_menu_id';
		if ($active) {
			if ($active == 'all')
				$sqlQuery = "SELECT id 
    							FROM glb_menu_parent 
    							WHERE " . $columnName . " = \"$menuId\" ";
			else
				$sqlQuery = "SELECT id
    							FROM glb_menu_parent
    							WHERE " . $columnName . " = \"$menuId\" 
    								AND active = \"y\" ";
		} else
			$sqlQuery = "SELECT id
    						FROM glb_menu_parent
    							WHERE " . $columnName . " = \"$menuId\" 
    								AND active != \"y\"";
		return $this->getDataArray ( $this->processQuery ( $sqlQuery ) );
	}
	public function getMenuChildParentIdDetails($menuId) {
		return $this->getTableIdDetails ( $menuId );
	}
	
	// functions related to the form menu access
	public function setMenuAccessDetails($parentMenu, $childMenu) {
		if ($this->checkMenuAccessAvailability ( $parentMenu, $childMenu ))
			return false;
		
		$counter = $this->getCounter ( 'menuAccess' );
		$sqlQuery = "INSERT INTO glb_menu_access (id, menu_id, access_menu_id, last_update_date, last_updated_by, creation_date, created_by, active)
    					VALUES (\"$counter\", \"$parentMenu\", \"$childMenu\", \"" . $this->getCurrentDateTime () . "\", \"" . $this->getLoggedUserId () . "\", \"" . $this->getCurrentDateTime () . "\", \"" . $this->getLoggedUserId () . "\", \"y\")";
		if ($this->processQuery ( $sqlQuery, $counter )) {
			$this->logOperation ( $counter, 'The menu acl has been successfully associated' );
			$this->logOperation ( $childMenu, 'The acl for this menu has been set' );
			return $counter;
		}
		return false;
	}
	public function dropMenuAccessDetails($menuId) {
		if ($this->dropTableId ( $menuId, false )) {
			$this->logOperation ( $menuId, "The ACL Assignment has been dropped" );
			return true;
		}
		return false;
	}
	public function activateMenuAccessUpdate($menuId) {
		if ($this->activateTableId ( $menuId )) {
			$this->logOperation ( $menuId, "The ACL Assignment has been activated" );
			return true;
		}
		return false;
	}
	public function checkMenuAccessAvailability($parentMenu, $childMenu) {
		$sqlQuery = "SELECT id
    					FROM glb_menu_access
    					WHERE menu_id = \"$parentMenu\"
    						AND access_menu_id = \"$childMenu\" ";
		$sqlQuery = $this->processArray ( $sqlQuery );
		if ($sqlQuery [0] != '')
			return $sqlQuery [0];
		return false;
	}
	public function getMenuAccessIds($menuId, $active, $flag) {
		$columnName = $flag ? 'menu_id' : 'access_menu_id';
		if ($active) {
			if ($active == 'all')
				$sqlQuery = "SELECT id
		    		FROM glb_menu_access
		    		WHERE " . $columnName . " = \"$menuId\" ";
			else
				$sqlQuery = "SELECT id
		    		FROM glb_menu_access
		    		WHERE " . $columnName . " = \"$menuId\"
		    		AND active = \"y\" ";
		} else
			$sqlQuery = "SELECT id
					    	FROM glb_menu_access
					    	WHERE " . $columnName . " = \"$menuId\"
					    	AND active != \"y\"";
		return $this->getDataArray ( $this->processQuery ( $sqlQuery ) );
	}
	public function getMenuAccessIdDetails($menuId) {
		return $this->getTableIdDetails ( $menuId );
	}
	
	
	//functions related to the menu attribute assignment
	public function setMenuAttributes($id, $officerId, $startDate, $endDate, $attribute1){
		$counter = $this->getCounter('menuAttribute');
		$sqlQuery = "INSERT INTO glb_menu_attributes 
						(id, user_id, start_date, end_date, attribute1, last_update_date, last_updated_by, creation_date, created_by, active) 
						VALUES (\"$counter\", \"$officerId\", \"$startDate\", \"$endDate\", \"$attribute1\", \"" . $this->getCurrentDateTime () . "\", \"" . $this->getLoggedUserId () . "\", \"" . $this->getCurrentDateTime () . "\", \"" . $this->getLoggedUserId () . "\", \"y\")";
		if($this->processQuery($sqlQuery, $counter)){
			$this->logOperation($counter, "New menu has been assigned with attributes");
			$this->logOperation($officerId, "A new menu has been assigned");
			return $counter;
		}
		return false;
	}
	
	public function commitMenuAttributeUpdate($menuAttributeId){
		if ($this->sqlConstructQuery == "")
			return false;
		
		$this->sqlConstructQuery .= ", last_update_date=\"" . $this->getCurrentDateTime () . "\", last_updated_by=\"" . $this->getLoggedUserId () . "\"";
		$sqlQuery = "UPDATE glb_menu_attributes
						SET $this->sqlConstructQuery
						WHERE id = \"$menuAttributeId\" ";
		
		if ($this->processQuery ( $sqlQuery, $menuAttributeId )) {
		$this->logOperation ( $menuAttributeId, "The Menu Attribute Has Been Updated" );
		return true;
		}
		return false;
	}
	
	public function dropMenuAttribute($menuAttributeId){
		if ($this->dropTableId ( $menuAttributeId, false )) {
			$this->logOperation ( $menuAttributeId, "The menu attribute details has been removed" );
			return true;
		}
		return false;
	}
	
	public function activateMenuAttribute($menuAttributeId){
		if ($this->activateTableId ( $menuAttributeId )) {
			$this->logOperation ( $menuAttributeId, "The menu attribute details has been activated" );
			return true;
		}
		return false;
	}
	
	public function getDependentMenus($menuId){		
		$parentIds = $this->getMenuChildParentId($menuId, 1, false);
		foreach($parentIds as $parentId){
			$details = $this->getTableIdDetails($parentId);
			if (in_array($details['child_menu_id'], $this->_dependentMenus))
				continue;
			array_push($this->_dependentMenus, $details['child_menu_id']);
			$this->getDependentMenus($details['child_menu_id']);
		}
		return $this->_dependentMenus;
	}
}

?>