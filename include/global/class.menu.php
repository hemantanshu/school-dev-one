<?php

/**
 * This class will hold the functionalities related to the menu items
 * This class extends the general class
 * @author Hemant Kumar Sah
 * @category Global
 * @license Support-Gurukul
 * @version 1.0.0
 */
require_once 'class.options.php';

class menu extends options {	
	public function __construct() {
		parent::__construct ();		
	}
	
	/**
	 * Start of the functions related to the menu url*
	 */
	
	/**
	 * The method is used to get the menu ids to be displayed generally in the
	 * page
	 * 
	 * @return Var The menu ids for the given attribute combinations
	 * @param $active Flag
	 *       	 True|False|All|Null. If true is passed then all the active
	 *        	menu urls, False then inactive menu urls and in All or Null,
	 *        	all the menu url ids are returned
	 * @param $page Num
	 *       	 The pager on which page view is required by cutting down the
	 *        	returning list
	 * @param $totalView Num
	 *       	 The size of the each return which by default takes 100 if not
	 *        	passed on
	 */
	public function getMenuUrlIds($active = '', $page = '', $totalView = '') {
		$pager = empty ( $page ) || ! is_numeric ( $page ) ? 0 : $page - 1;
		$view = empty ( $totalView ) || ! is_numeric ( $totalView ) ? 100 : $totalView;
		
		$startLimit = $view * $pager;
		
		if (empty ( $active ) || $active) {
			if ($active == 'all')
				// the id for all the menu, when the active attribute is set to
				// all
				$sqlQuery = "SELECT id 
								FROM glb_menu_url 
									ORDER BY menu_name ASC 
									LIMIT $startLimit, $view";
			else
				// the menu id for the active menu id, when either active
				// attribute is set to true or not set
				$sqlQuery = "SELECT id 
								FROM glb_menu_url
								WHERE active = \"y\" 
									ORDER BY menu_name ASC 
									LIMIT $startLimit, $view";
		} else
			// the value menu id of all the inactive menus, when the active
			// attribute is set to false
			$sqlQuery = "SELECT id 
							FROM glb_menu_url 
							WHERE active != \"y\" 
								ORDER BY menu_name ASC 
								LIMIT $startLimit, $view";
		return $this->getDataArray ( $this->processQuery ( $sqlQuery ) );
	}
	
	/**
	 * This method is used to get the menu ids corresponding to the given menu
	 * name string
	 * 
	 * @param $str var
	 *       	 The menu name string variable
	 * @return array The array of menu url ids
	 */
	public function getMenuUrlSearchIds($str, $active) {
		if ($active) {
			if ($active === "all")
				// the id for all the menu, when the active attribute is set to
				// all
				$sqlQuery = "SELECT id 
								FROM glb_menu_url 
                                                                WHERE menu_name LIKE \"%$str%\"
									ORDER BY menu_name ASC";
			else
				// the menu id for the active menu id, when either active
				// attribute is set to true or not set
				$sqlQuery = "SELECT id 
								FROM glb_menu_url
								WHERE active = \"y\" 
                                                                    AND menu_name LIKE \"%$str%\"
									ORDER BY menu_name ASC";
		} else
			// the value menu id of all the inactive menus, when the active
			// attribute is set to false
			$sqlQuery = "SELECT id 
							FROM glb_menu_url 
							WHERE active != \"y\" 
                                                            AND menu_name LIKE \"%$str%\"
								ORDER BY menu_name ASC";
		return $this->getDataArray ( $this->processQuery ( $sqlQuery ) );
	}
	
	/**
	 * The method is used to popluate the details of the unique id
	 * 
	 * @param $id var
	 *       	 The unique id against which the details has been sought
	 * @return Array The array of values, and is in order as per the column
	 *         position in the db schema
	 */
	public function getMenuUrlIdDetails($id) {
		return $this->getTableIdDetails ( $id );
	}
	
	public function getMenuUrlAssociatedSubmenu($urlId) {
		$sqlQuery = "SELECT submenu_parent_id 
                            FROM glb_menu_submenu_url 
                            WHERE menu_url_id = \"$urlId\" ";
		return $this->getDataArray ( $this->processQuery ( $sqlQuery ) );
	}
	
	public function getMenuUrlAssociatedTopMenu($urlId) {
		$sqlQuery = "SELECT id 
                        FROM glb_menu_top 
                        WHERE menu_url_id = \"$urlId\" 
                            || submenu_id IN (select submenu_parent_id 
                                                FROM glb_menu_submenu_url 
                                                WHERE menu_url_id = \"$urlId\" ) 
                            || submenu_id IN (select submenu_child_id 
                                                FROM glb_menu_submenu_url 
                                                WHERE menu_url_id = \"$urlId\" )";
		return $this->getDataArray ( $this->processQuery ( $sqlQuery ) );
	}
	
	/**
	 * The method is used to generate the complete menu url of a given url
	 * 
	 * @param $urlId var
	 *       	 The urlid whose url has to be retrived
	 * @return var The complete url of the given url
	 */
	public function generateMenuUrl($urlId) {
		$details = $this->getMenuUrlIdDetails ( $urlId );
		$data[0] = empty ( $details ['url_source_id'] ) ? $details ['menu_url'] : $this->getOptionIdValue ( $details ['url_source_id'] ) . $details ['menu_url'];
		$data[1] = $this->getBaseServer()."images/global/icons/".$details['menu_image_url'];
		return $data;
	}
	
	/**
	 * End of the functions related to the menu url*
	 */
	/**
	 * Start of the functions related to the menu submenu *
	 */
	
	/**
	 * The method is used to get the sub menu ids to be displayed generally in
	 * the page
	 * 
	 * @return Array The menu ids for the given attribute combinations
	 * @param $active Flag
	 *       	 True|False|All|Null. If true is passed then all the active
	 *        	submenu, False then inactive submenu and in All or Null, all
	 *        	the submenu ids are returned
	 * @param $page Num
	 *       	 The pager on which page view is required by cutting down the
	 *        	returning list
	 * @param $totalView Num
	 *       	 The size of the each return which by default takes 100 if not
	 *        	passed on
	 */
	public function getMenuSubmenuIds($active = '', $page = '', $totalView = '') {
		$pager = empty ( $page ) || ! is_numeric ( $page ) ? 0 : $page - 1;
		$view = empty ( $totalView ) || ! is_numeric ( $totalView ) ? 100 : $totalView;
		
		$startLimit = $view * $pager;
		
		if (empty ( $active ) || $active) {
			if ($active == 'all')
				// the id for all the menu, when the active attribute is set to
				// all
				$sqlQuery = "SELECT id 
								FROM glb_menu_submenu 
									ORDER BY submenu_name ASC 
									LIMIT $startLimit, $view";
			else
				// the menu id for the active menu id, when either active
				// attribute is set to true or not set
				$sqlQuery = "SELECT id 
								FROM glb_menu_submenu 
								WHERE active = \"y\" 
									ORDER BY submenu_name ASC 
									LIMIT $startLimit, $view";
		} else
			// the value menu id of all the inactive menus, when the active
			// attribute is set to false
			$sqlQuery = "SELECT id 
							FROM glb_menu_submenu 
							WHERE active != \"y\" 
								ORDER BY submenu_name ASC 
								LIMIT $startLimit, $view";
		return $this->getDataArray ( $this->processQuery ( $sqlQuery ) );
	}
	
	/**
	 * This method is used to get the menu ids corresponding to the given
	 * submenu name string
	 * 
	 * @param $str var
	 *       	 The menu name string variable
	 * @return array The array of menu url ids
	 */
	public function getMenuSubmenuSearchIds($str, $active) {
		if ($active) {
			if ($active === "all")
				// the id for all the menu, when the active attribute is set to
				// all
				$sqlQuery = "SELECT id 
								FROM glb_menu_submenu 
                                                                WHERE submenu_name LIKE \"%$str%\"
									ORDER BY submenu_name ASC";
			else
				// the menu id for the active menu id, when either active
				// attribute is set to true or not set
				$sqlQuery = "SELECT id 
								FROM glb_menu_submenu
								WHERE active = \"y\" 
                                                                    AND submenu_name LIKE \"%$str%\"
									ORDER BY submenu_name ASC";
		} else
			// the value menu id of all the inactive menus, when the active
			// attribute is set to false
			$sqlQuery = "SELECT id 
							FROM glb_menu_submenu 
							WHERE active != \"y\" 
                                                            AND submenu_name LIKE \"%$str%\"
								ORDER BY submenu_name ASC";
		return $this->getDataArray ( $this->processQuery ( $sqlQuery ) );
	}
	
	/**
	 * The method is used to popluate the details of the unique id
	 * 
	 * @param $id var
	 *       	 The unique id against which the details has been sought
	 * @return Array The array of values, and is in order as per the column
	 *         position in the db schema
	 */
	public function getMenuSubmenuIdDetails($id) {
		return $this->getTableIdDetails ( $id );
	}
	
	/**
	 * End of the functions related to the menu submenu *
	 */
	/**
	 * Start of the functions related to the menu submenu urls *
	 */
	
	/**
	 * The method is used to get the submenu url ids to be displayed generally
	 * in the page
	 * 
	 * @return Array The menu ids for the given attribute combinations
	 * @param $active Flag
	 *       	 True|False|All|Null. If true is passed then all the active
	 *        	submenu urls, False then inactive submenu urls and in All or
	 *        	Null, all the submenu url ids are returned
	 * @param $page Num
	 *       	 The pager on which page view is required by cutting down the
	 *        	returning list
	 * @param $totalView Num
	 *       	 The size of the each return which by default takes 100 if not
	 *        	passed on
	 */
	public function getMenuSubmenuUrlIds($active = '', $page = '', $totalView = '') {
		$pager = empty ( $page ) || ! is_numeric ( $page ) ? 0 : $page - 1;
		$view = empty ( $totalView ) || ! is_numeric ( $totalView ) ? 100 : $totalView;
		
		$startLimit = $view * $pager;
		
		if (empty ( $active ) || $active) {
			if ($active == 'all')
				// the id for all the submenu urls, when the active attribute is
				// set to all
				$sqlQuery = "SELECT id 
								FROM glb_menu_submenu_url 
									ORDER BY priority ASC 
									LIMIT $startLimit, $view";
			else
				// the menu id for the active submenu url ids, when either
				// active attribute is set to true or not set
				$sqlQuery = "SELECT id 
								FROM glb_menu_submenu_url 
								WHERE active = \"y\" 
									ORDER BY priority ASC 
									LIMIT $startLimit, $view";
		} else
			// the value menu id of all the submenu url ids, when the active
			// attribute is set to false
			$sqlQuery = "SELECT id 
							FROM glb_menu_submenu_url 
							WHERE active != \"y\" 
								ORDER BY priority ASC 
								LIMIT $startLimit, $view";
		return $this->getDataArray ( $this->processQuery ( $sqlQuery ) );
	}
	
	/**
	 * This method is used to get the menu ids corresponding to the given
	 * submenu url name string
	 * 
	 * @param $str var
	 *       	 The menu name string variable
	 * @return array The array of menu url ids
	 */
	public function getMenuSubmenuURLSearchIds($submenuId, $str, $active) {
		if ($active) {
			if ($active === "all")
				// the id for all the menu, when the active attribute is set to
				// all
				$sqlQuery = "SELECT id
    							FROM glb_menu_submenu_url
				    			WHERE menu_name LIKE \"%$str%\"
				    				AND submenu_parent_id = \"$submenuId\"
				    			ORDER BY menu_priority ASC";
			else
				// the menu id for the active menu id, when either active
				// attribute is set to true or not set
				$sqlQuery = "SELECT id
					    		FROM glb_menu_submenu_url
					    		WHERE active = \"y\"
					    			AND submenu_parent_id = \"$submenuId\"
					    			AND menu_name LIKE \"%$str%\"
					    		ORDER BY menu_priority ASC";
		} else
			// the value menu id of all the inactive menus, when the active
			// attribute is set to false
			$sqlQuery = "SELECT id
					    	FROM glb_menu_submenu_url					    		
					    	WHERE active != \"y\"
					    		AND submenu_parent_id = \"$submenuId\"
					    		AND menu_name LIKE \"%$str%\"
					    	ORDER BY menu_priority ASC";
		return $this->getDataArray ( $this->processQuery ( $sqlQuery ) );
	}
	
	/**
	 * The method is used to popluate the details of the unique id
	 * 
	 * @param $id var
	 *       	 The unique id against which the details has been sought
	 * @return Array The array of values, and is in order as per the column
	 *         position in the db schema
	 */
	public function getMenuSubmenuUrlIdDetails($id) {
		return $this->getTableIdDetails ( $id );
	}
	
	public function getMenuSubmenuAssignedURLAssignmentIds($submenuId){
		$sqlQuery = "SELECT id 
						FROM glb_menu_submenu_url 
						WHERE submenu_parent_id = \"$submenuId\"							
							AND active = \"y\" 
						ORDER BY menu_priority ASC";
		return $this->getDataArray($this->processQuery($sqlQuery));
	}
	
	/**
	 * End of the functions related to the menu submenu urls *
	 */
	/**
	 * Start of the functions related to the menu top*
	 */
	
	/**
	 * The method is used to get the topmenu ids to be displayed generally in
	 * the page
	 * 
	 * @return Array The menu ids for the given attribute combinations
	 * @param $active Flag
	 *       	 True|False|All|Null. If true is passed then all the active
	 *        	topmenu urls, False then inactive topmenu urls and in All or
	 *        	Null, all the topmenu url ids are returned
	 * @param $page Num
	 *       	 The pager on which page view is required by cutting down the
	 *        	returning list
	 * @param $totalView Num
	 *       	 The size of the each return which by default takes 100 if not
	 *        	passed on
	 */
	public function getMenuTopIds($active = '', $page = '', $totalView = '') {
		$pager = empty ( $page ) || ! is_numeric ( $page ) ? 0 : $page - 1;
		$view = empty ( $totalView ) || ! is_numeric ( $totalView ) ? 100 : $totalView;
		
		$startLimit = $view * $pager;
		
		if (empty ( $active ) || $active) {
			if ($active == 'all')
				// the id for all the top menus, when the active attribute is
				// set to all
				$sqlQuery = "SELECT id 
								FROM glb_menu_top 
									ORDER BY priority ASC 
									LIMIT $startLimit, $view";
			else
				// the menu id for the active top menus, when either active
				// attribute is set to true or not set
				$sqlQuery = "SELECT id 
								FROM glb_menu_top 
								WHERE active = \"y\" 
									ORDER BY priority ASC 
									LIMIT $startLimit, $view";
		} else
			// the value menu id of all the submenu url ids, when the active
			// attribute is set to false
			$sqlQuery = "SELECT id 
							FROM glb_menu_top
							WHERE active != \"y\" 
								ORDER BY priority ASC 
								LIMIT $startLimit, $view";
		return $this->getDataArray ( $this->processQuery ( $sqlQuery ) );
	}
	
	/**
	 * This method is used to get the menu ids corresponding to the given
	 * submenu url name string
	 * 
	 * @param $str var
	 *       	 The menu name string variable
	 * @return array The array of menu url ids
	 */
	public function getMenuTopSearchIds($str, $active) {
		if ($active) {
			if ($active === "all")
				// the id for all the menu, when the active attribute is set to
				// all
				$sqlQuery = "SELECT id
	    			FROM glb_menu_top
	    			WHERE menu_name LIKE \"%$str%\"
	    			ORDER BY menu_name ASC";
			else
				// the menu id for the active menu id, when either active
				// attribute is set to true or not set
				$sqlQuery = "SELECT id
				    		FROM glb_menu_top
				    		WHERE active = \"y\"
				    		AND menu_name LIKE \"%$str%\"
				    		ORDER BY menu_name ASC";
		} else
			// the value menu id of all the inactive menus, when the active
			// attribute is set to false
			$sqlQuery = "SELECT id
			    		FROM glb_menu_top
			    		WHERE active != \"y\"
			    		AND menu_name LIKE \"%$str%\"
			    		ORDER BY menu_name ASC";
		return $this->getDataArray ( $this->processQuery ( $sqlQuery ) );
	}
	
	/**
	 * The method is used to popluate the details of the unique id
	 * 
	 * @param $id var
	 *       	 The unique id against which the details has been sought
	 * @return Array The array of values, and is in order as per the column
	 *         position in the db schema
	 */
	public function getMenuTopIdDetails($id) {
		return $this->getTableIdDetails ( $id );
	}
	
	/**
	 * End of the functions related to the the menu top*
	 */
	/**
	 * Start of the functions related to the menu group assignment *
	 */
	
	/**
	 * The method is used to get the topmenus assigned to a given user
	 * 
	 * @param $userId var
	 *       	 The userid against whom the top menu has to be populated
	 * @param $type boolean
	 *       	 true/false/empty if empty then the active topmenus will be
	 *        	populated. false then all the disabled menus. true then all
	 *        	the topmenus ever assigned to that user
	 * @return array The array of assignmentids which will correspond to the
	 *         topmenus assigned that user
	 */
	public function getMenuTopUserAssignmentIds($userId, $type = '') {
		if ($type) {
			// getting the top menu assignment for the user
			if ($type == "all")
				$sqlQuery = "SELECT id 
								FROM glb_menu_assignment 
									WHERE 
										(generic_id IN (SELECT type 
															FROM glb_login 
															WHERE id = \"$userId\") 
										OR generic_id = \"$userId\") 
										AND start_date <= \"". $this->getCurrentDate()."\"
										AND end_date >= \"".$this->getCurrentDate (). "\"
										AND active = \"y\"
										AND menu_top = \"y\" ";
			else
				$sqlQuery = "SELECT id
					            	FROM glb_menu_assignment
					            	WHERE generic_id IN (SELECT type
								            	FROM glb_login
								            	WHERE id = \"$userId\")
								            	OR generic_id = \"$userId\"
								            	AND menu_top = \"y\"";
		} else
			$sqlQuery = "SELECT id
				        	FROM glb_menu_assignment
				        			WHERE (generic_id IN (SELECT type
				        						FROM glb_login
									        	WHERE id = \"$userId\")
									        	OR generic_id = \"$userId\")
									        	AND (start_date > \"".$this->getCurrentDate()."\"
									        	OR end_date < \"".$this->getCurrentDate()."\"
									        	OR active != \"y\")
									        	AND menu_top = \"y\" ";
		return $this->getDataArray ( $this->processQuery ( $sqlQuery ) );
	}
	
	/**
	 * The method is used to get the top
	 * 
	 * @param $groupId var
	 *       	 The usergroup for whom the assignment ids has to be populated
	 * @param $type boolean
	 *       	 true/false/empty if empty then the active topmenus will be
	 *        	populated. false then all the disabled menus. true then all
	 *        	the topmenus ever assigned to that usergroup
	 * @return array The array of assignmentids which will correspond to the
	 *         topmenus assigned that usergroup
	 */
	public function getMenuGroupAssignmentIds($groupId, $type) {
		if ($type) {
			if ($type == "all")
				// getting the top menu assignment for the user
				$sqlQuery = "SELECT id
				        		FROM glb_menu_assignment
				        		WHERE generic_id = \"$groupId\" ";
			else
				$sqlQuery = "SELECT id 
								FROM glb_menu_assignment 
									WHERE 
										generic_id = \"$groupId\"
										AND start_date <= \"" . $this->getCurrentDate () . "\"
										AND (end_date >= \"" . $this->getCurrentDate () . "\" OR end_date = \"0000-00-00\")
										AND active = \"y\" ";
		
		} else
			$sqlQuery = "SELECT id
						        	FROM glb_menu_assignment
						        	WHERE generic_id = \"$groupId\"
						        	AND active = \"y\" ";
		return $this->getDataArray ( $this->processQuery ( $sqlQuery ) );
	}
	
	/**
	 * The method is used to get the details of a given assignment id
	 * 
	 * @param $id var
	 *       	 The assignmentId whose details has been sought
	 * @return array The array of details of the assignment Id
	 */
	public function getMenuAssignmentIdDetails($id) {
		return $this->getTableIdDetails ( $id );
	}
	
	public function getUserAssignedTopMenus($userId){
		$sqlQuery = "SELECT menu_id
								FROM glb_menu_user_assignment
								WHERE user_id = \"$userId\"
									AND top_menu_flag = \"y\"
								ORDER BY priority ASC";
		
		return $this->getDataArray($this->processQuery($sqlQuery));
	}
	
	public function getActiveAssignmentIds(){
		$sqlQuery = "SELECT id 
							FROM glb_menu_assignment 
							WHERE start_date <= \"".$this->getCurrentDate()."\" 
								AND (end_date = \"0000-00-00\" OR end_date >= \"".$this->getCurrentDate()."\") 
								AND active = \"y\" ";
		return $this->getDataArray($this->processQuery($sqlQuery));
	}
	
	/**
	 * End of the functions related to the menu group assignment *
	 */
	/**
	 * Start of the functions related to the menu authentication *
	 */
	
	/**
	 * The method is used to retrieve whether the menu is login protected or not
	 * 
	 * @param $menuId var
	 *       	 The menu id whose flag has been sought
	 * @return boolean True/False. True is the menu is login protected else
	 *         false
	 */
	public function isMenuAuthenticable($menuId) {
		return $this->getValue ( 'menu_authorized', 'glb_menu_url', 'id', $menuId ) == 'y' ? true : false;
	}
	
	/**
	 * The method is used to know whether the given menu is edit operational
	 * (doing dml operation)
	 * 
	 * @param $menuId var
	 *       	 The menu id against which the flag has been sought
	 * @return boolean True/False. True if it is does dml operation else false
	 */
	public function isMenuEditFeatured($menuId) {
		return $this->getValue ( 'menu_editable', 'glb_menu_url', 'id', $menuId ) == 'y' ? true : false;
	}
	
	/**
	 * The method is used to check whether the officer is eligible for the given
	 * menu id or not
	 * 
	 * @param $userId var
	 *       	 The office id against which the check has to be done
	 * @param $menuId var
	 *       	 The menud id which has to be authenticated
	 * @return boolean True/False. True on authorized and false on
	 *         non-authorized
	 */
	public function isOfficerAuthorized4Menu($userId, $menuId, $type) {
		if($type != "y")
			$sqlQuery = "SELECT id 
							FROM glb_menu_user_authentication 
							WHERE user_id = \"$userId\" 
							AND menu_id = \"$menuId\" ";
		else
			$sqlQuery = "SELECT id 
							FROM glb_menu_user_authentication 
							WHERE user_id = \"$userId\" 
							AND menu_id = \"$menuId\" 
							AND edit_enabled = \"y\" ";
		
		$sqlQuery = $this->processArray($sqlQuery);
		if($sqlQuery[0] != "")
			return true;
		return false;
	}
	
	/**
	 * End of the functions related to the menu authentication *
	 */
	/**
	 * start of the functions related to the menu generation *
	 */
	
	/**
	 * The method is used to generate the submenu urls with html for the given
	 * submenuid
	 * 
	 * @param $submenuId var
	 *       	 The submenu which has to generate the url
	 */
	private function generateSubmenu($submenuId) {		
		echo "<ul>";
		$subMenuUrlIds = $this->getMenuSubmenuAssignedURLAssignmentIds($submenuId);
		foreach ( $subMenuUrlIds as $subMenuUrlId ) {
			$details = $this->getMenuSubmenuUrlIdDetails ( $subMenuUrlId );
			$urlDetails = $this->generateMenuUrl ( $details ['menu_url_id'] );
			$target = $details ['menu_redirect'] == 'y' ? '_blank' : '_parent';
			
			if ($details ['submenu_child_id'] != "") { // there are child submenu associated with it
				echo "<li><a href=\"$urlDetails[0]\" target=\"$target\"  class=\"sub\"><img src=\"$urlDetails[1]\" class=\"menuIcon\" alt=\"\" />" . $details ['menu_name'] . "</a>";
				$this->generateSubmenu ( $details ['submenu_child_id'] );
				echo "</li>";
			} else {
				echo "<li><a href=\"$urlDetails[0]\" target=\"$target\"><img src=\"$urlDetails[1]\" class=\"menuIcon\" alt=\"\" />" . $details ['menu_name'] . "</a></li>";
			}
		}
		$url = $this->getBaseServer()."pages/global/glb_menu_submenu_url.php?submenuId=".$submenuId;
		echo "<li><a href=\"$url\" target=\"_parent\">Update Assigned Menus</a></li>";
		echo "</ul>";
	}
	
	/**
	 * The method is used to generate the menu for the logged user
	 */
	public function generateTopMenu() {		
		echo "<ul>";
		$userId = $this->getLoggedUserId ();
		if(!$userId)
			$this->redirectUrl($this->getBaseServer());
		$topMenuIds = $this->getUserAssignedTopMenus($userId);
		foreach ( $topMenuIds as $topMenuId ) {
			$details = $this->getMenuTopIdDetails ( $topMenuId );
			$urlDetails = $this->generateMenuUrl ( $details ['menu_url_id'] );
			$target = $details ['menu_redirect'] == 'y' ? '_blank' : '_parent';
			if ($details ['submenu_id'] != "") {
				echo "<li><a href=\"$urlDetails[0]\" target=\"$target\" class=\"menulink\"><img src=\"$urlDetails[1]\" class=\"menuIcon\" alt=\"\" />" . $details ['menu_name'] . "</a>";
				$this->generateSubmenu ( $details ['submenu_id'] );
				echo "</li>";
			} else {
				echo "<li><a href=\"$urlDetails[0]\" target=\"$target\" class=\"menulink_non\">" . $details ['menu_name'] . "</a></li>";
			}
		}
		echo "</ul>";
	}

	/**
 * End of the functions related to the menu generation*
 */
	
	
}

?>