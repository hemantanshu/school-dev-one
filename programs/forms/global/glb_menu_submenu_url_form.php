<?php
require_once 'config.php';
require_once BASE_PATH . 'include/global/class.editMenu.php';
require_once BASE_PATH . 'include/global/class.options.php';

$menu = new editMenu ();
$options = new options ();

$options->isRequestAuthorised4Form('LMENUL5');

if (isset ( $_POST ['task'] )) {
	if ($_POST ['task'] == 'insertMenuUrl') {
		$id = $menu->setNewSubmenuUrl ( $_POST ['menuName'], $_POST ['menuUrl_i'], $_POST ['submenuId'], $_POST ['cSubmenu_i'], $_POST ['redirect'], $_POST ['menuPriority'], 'y' );
		$outputArray = array ();
		if ($id) {
			$details = $menu->getMenuSubmenuUrlIdDetails ( $id );
			$menuDetails = $menu->getMenuUrlIdDetails ( $details ['menu_url_id'] );
			$outputArray [0] = $id;
			$outputArray [1] = $details ['menu_name'];
			$outputArray [2] = $menuDetails ['menu_name'];
			if ($details ['submenu_child_id'] != '') {
				$details = $menu->getMenuSubmenuIdDetails ( $details ['submenu_child_id'] );
				$outputArray [3] = $details ['submenu_name'];
			} else
				$outputArray [3] = $details ['submenu_child_id'];
			$outputArray [4] = $details ['menu_priority'];
			echo json_encode ( $outputArray );
		}
	} else if ($_POST ['task'] == 'search') {
		$hint = htmlentities ( trim ( $_POST ['menu_hint'] ) );
		$search_type = htmlentities ( trim ( $_POST ['search_type'] ) );
		$submenuId = $_POST ['submenuId_glb'];
		$data = $menu->getMenuSubmenuURLSearchIds ( $submenuId, $hint, $search_type );
		$i = 0;
		$outputArray [0] [0] = 1;
		foreach ( $data as $id ) {
			$details = $menu->getMenuSubmenuUrlIdDetails ( $id );
			$menuDetails = $menu->getMenuUrlIdDetails ( $details ['menu_url_id'] );
			
			$outputArray [$i] [0] = $details ['id'];
			$outputArray [$i] [1] = $details ['menu_name'];
			$outputArray [$i] [2] = $menuDetails ['menu_name'];
			$outputArray [$i] [4] = $details ['menu_priority'];
			if ($details ['submenu_child_id'] != '') {
				$details = $menu->getMenuSubmenuIdDetails ( $details ['submenu_child_id'] );
				$outputArray [$i] [3] = $details ['submenu_name'];
			} else
				$outputArray [$i] [3] = $details ['submenu_child_id'];
			
			
			$i ++;
		}
		echo json_encode ( $outputArray );
	} else if ($_POST ['task'] == 'getUrlIdDetails') {
		$id = htmlentities ( trim ( $_POST ['id'] ) );
		$details = $menu->getMenuSubmenuUrlIdDetails ( $id );
		$urlDetails = $menu->getMenuUrlIdDetails ( $details ['menu_url_id'] );
		
		$submenuDetails = $menu->getMenuSubmenuIdDetails ( $details ['submenu_parent_id'] );
		
		$outputArray [0] = $details ['id'];
		$outputArray [1] = $details ['menu_name'];
		$outputArray [2] = $urlDetails ['menu_name'];
		$outputArray [3] = $options->getOptionIdValue ( $urlDetails ['url_source_id'] ) . $urlDetails ['menu_url'];
		
		$outputArray [4] = $submenuDetails ['submenu_name'];
		
		$submenuDetails = $menu->getMenuSubmenuIdDetails ( $details ['submenu_child_id'] );
		
		$outputArray [5] = $submenuDetails ['submenu_name'];
		$outputArray [6] = $details ['menu_redirect'];
		$outputArray [7] = $details ['menu_priority'];
		$outputArray [8] = $details ['last_update_date'];
		$outputArray [9] = $menu->getOfficerName ( $details ['last_updated_by'] );
		$outputArray [10] = $details ['creation_date'];
		$outputArray [11] = $menu->getOfficerName ( $details ['created_by'] );
		$outputArray [12] = $details ['active'];
		
		// extra attributes provided for the edit form update
		$outputArray [13] = $details ['menu_url_id'];
		$outputArray [14] = $details ['submenu_child_id'];
		
		echo json_encode ( $outputArray );
	} elseif ($_POST ['task'] == 'updateMenuUrl') {
		if ($_POST ['cSubmenu_u'] == '')
			$childSubMenu = '';
		else
			$childSubMenu = $_POST ['cSubmenu_ui'];
		
		$details = $menu->getTableIdDetails($_POST['menuId_u']);
		if($details['menu_name'] != $_POST['menuName_u']){
			$menu->setUpdateLog('Name from '.$details['menu_name'].' to '.$_POST['menuName_u']);
			$menu->updateTableParameter ( 'menu_name', $_POST ['menuName_u'] );
		}
		if($details['menu_url_id'] != $_POST['menuUrl_ui']){
			$menu->setUpdateLog('URL from '.$details['menu_url_id'].' to '.$_POST['menuUrl_ui']);
			$menu->updateTableParameter ( 'menu_url_id', $_POST ['menuUrl_ui'] );
		}
		if($details['submenu_child_id'] != $childSubMenu){
			$menu->setUpdateLog('Submenu from '.$details['submenu_child_id'].' to '.$childSubMenu);
			$menu->updateTableParameter ( 'submenu_child_id', $childSubMenu );			
		}
		if($details['menu_redirect'] != $_POST['redirect_u']){
			$menu->setUpdateLog('Redirect from '.$details['menu_redirect'].' to '.$_POST['redirect_u']);
			$menu->updateTableParameter ( 'menu_redirect', $_POST ['redirect_u'] );				
		}
		if($details['menu_priority'] != $_POST['menuPriority_u']){
			$menu->setUpdateLog('Priority from '.$details['menu_priority'].' to '.$_POST['menuPriority_u']);
			$menu->updateTableParameter ( 'menu_priority', $_POST ['menuPriority_u'] );				
		}		
		$menu->commitSubmenuUrlUpdate ( $_POST ['menuId_u'] );
		
		$details = $menu->getMenuSubmenuUrlIdDetails ( $_POST ['menuId_u'] );
		$menuDetails = $menu->getMenuUrlIdDetails ( $details ['menu_url_id'] );
		$outputArray [0] = $_POST ['menuId_u'];
		$outputArray [1] = $details ['menu_name'];
		$outputArray [2] = $menuDetails ['menu_name'];
		if ($details ['submenu_child_id'] != '') {
			$details = $menu->getMenuSubmenuIdDetails ( $details ['submenu_child_id'] );
			$outputArray [3] = $details ['submenu_name'];
		} else
			$outputArray [3] = $details ['submenu_child_id'];
		$outputArray[4] = $details['menu_priority'];
		echo json_encode ( $outputArray );
	
	} elseif ($_POST ['task'] == 'dropId') {
		$id = htmlentities ( trim ( $_POST ['id'] ) );
		$menu->dropMenuSubmenu ( $id );
		$outputArray [0] = $id;
		echo json_encode ( $outputArray );
	} elseif ($_POST ['task'] == 'activateId') {
		$id = htmlentities ( trim ( $_POST ['id'] ) );
		$menu->activateMenuSubmenu ( $id );
		$outputArray [0] = $id;
		echo json_encode ( $outputArray );
	} elseif ($_POST ['task'] == 'checkMenuSubmenu') {
		$submenuId = $_POST ['submenuId'];
		$menuId = $_POST ['menuId'];
		if ($menu->checkChildSubmenuAssignment ( $menuId, $submenuId ))
			$outputArray [0] = 1; // problem with the menu
		else
			$outputArray [0] = 0;
		echo json_encode ( $outputArray );
	} else {
		$outputArray [0] = 0;
		echo json_encode ( $outputArray );
	}
}
?>