<?php
require_once 'config.php';
require_once BASE_PATH.'include/global/class.editMenu.php';
require_once BASE_PATH.'include/global/class.options.php';

$menu = new editMenu ();
$options = new options ();

$options->isRequestAuthorised4Form('LMENUL4');


if (isset ( $_POST ['task'] )) {
	if ($_POST ['task'] == 'insertMenuUrl') {				
		$id = $menu->setNewTopMenu($_POST['menuName'], $_POST['menuDescription'], $_POST['menuUrl_val'], $_POST['submenu_val'], $_POST['redirect'], $_POST['priority'], $_POST['authenication'], 'y');		
		$outputArray = array ();
		$outputArray[0] = 0;
		if ($id) {
			$details = $menu->getMenuTopIdDetails($id);
			$menuDetails = $menu->getMenuUrlIdDetails($details['menu_url_id']);
			$submenuDetails = $menu->getMenuSubmenuIdDetails($details['submenu_id']);
			$outputArray[0] = $id;
			$outputArray[1] = $details ['menu_name'];			
			$outputArray[2] = $menuDetails ['menu_name'];
			$outputArray[3] = $submenuDetails['submenu_name'];			
                        $outputArray[4] = $details['menu_priority'];
		}
		echo json_encode($outputArray);
	} else if ($_POST['task'] == 'search') {
        $hint = htmlentities(trim($_POST['menu_hint']));
        $search_type = htmlentities(trim($_POST['search_type']));
        $data = $menu->getMenuTopSearchIds($hint, $search_type);
        $i = 0;
        $outputArray[0][0] = 1;
        foreach ($data as $id) {
            $details = $menu->getMenuTopIdDetails($id);
			$menuDetails = $menu->getMenuUrlIdDetails($details['menu_url_id']);
			$submenuDetails = $menu->getMenuSubmenuIdDetails($details['submenu_id']);
			$outputArray[$i][0] = $id;
			$outputArray[$i][1] = $details ['menu_name'];			
			$outputArray[$i][2] = $menuDetails ['menu_name'];
			$outputArray[$i][3] = $submenuDetails['submenu_name'];
                        $outputArray[$i][4] = $details['menu_priority'];
            $i++;
        }
        echo json_encode($outputArray);
    }else if ($_POST['task'] == 'getUrlIdDetails') {
        $id = htmlentities(trim($_POST['id']));
        $details = $menu->getMenuTopIdDetails($id);
        $urlDetails = $menu->getMenuUrlIdDetails($details['menu_url_id']);        
        $submenuDetails = $menu->getMenuSubmenuIdDetails($details['submenu_id']);  
        
        $outputArray[0] = $details['id'];
        $outputArray[1] = $details['menu_name'];
        $outputArray[2] = $urlDetails['menu_name'];        
        $outputArray[3] = $options->getOptionIdValue($urlDetails['url_source_id']).$urlDetails['menu_url'];        
        $outputArray[4] = $submenuDetails['submenu_name'];                
        $outputArray[5] = $details['menu_priority'];
        $outputArray[6] = $details['menu_redirect'];
        $outputArray[7] = $details['authentication'];
        $outputArray[8] = $details['last_update_date'];
        $outputArray[9] = $menu->getOfficerName($details['last_updated_by']);
        $outputArray[10] = $details['creation_date'];
        $outputArray[11] = $menu->getOfficerName($details['created_by']);
        $outputArray[12] = $details['active'];     
        $outputArray[13] = $details['menu_description'];
        //extra attributes provided for the edit form update
        $outputArray[14] = $details['menu_url_id'];
        $outputArray[15] = $details['submenu_id'];        
        
        echo json_encode($outputArray);
    }elseif($_POST['task'] == 'updateMenuUrl'){        
    	if($_POST['submenu_u'] == '')
    		$childSubMenu = '';
    	else
    		$childSubMenu = $_POST['submenu_u_val'];
    	
    	$details = $menu->getTableIdDetails($_POST['menuId_u']);
    	if($details['menu_name'] != $_POST['menuName_u']){
    		$menu->setUpdateLog('Name from '.$details['menu_name'].' to '.$_POST['menuName_u']);
    		$menu->updateTableParameter('menu_name', $_POST['menuName_u']);    		
    	}
    	if($details['menu_description'] != $_POST['menuDescription_u']){
    		$menu->setUpdateLog('Description from '.$details['menu_description'].' to '.$_POST['menuDescription_u']);
    		$menu->updateTableParameter('menu_description', $_POST['menuDescription_u']);
    	}
    	if($details['menu_url_id'] != $_POST['menuUrl_u_val']){
    		$menu->setUpdateLog('URL from '.$details['menu_url_id'].' to '.$_POST['menuUrl_u_val']);    		
    		$menu->updateTableParameter('menu_url_id', $_POST['menuUrl_u_val']);    		
    	}
    	if($details['submenu_id'] != $childSubMenu){
    		$menu->setUpdateLog('Submenu from '.$details['submenu_id'].' to '.$childSubMenu);
    		$menu->updateTableParameter('submenu_id', $childSubMenu);    		
    	}
    	if($details['menu_redirect'] != $_POST['redirect_u']){
    		$menu->setUpdateLog('Redirect from '.$details['menu_redirect'].' to '.$_POST['redirect_u']);
    		$menu->updateTableParameter('menu_redirect', $_POST['redirect_u']);    		
    	}
    	if($details['menu_priority'] != $_POST['priority_u']){
    		$menu->setUpdateLog('Priority from '.$details['menu_priority'].' to '.$_POST['priority_u']);
    		$menu->updateTableParameter('menu_priority', $_POST['priority_u']);    		
    	}    	        
        $menu->commitTopMenuUpdate($_POST['menuId_u']);
        
        $details = $menu->getMenuTopIdDetails($_POST['menuId_u']);
        $menuDetails = $menu->getMenuUrlIdDetails($details['menu_url_id']);
        $submenuDetails = $menu->getMenuSubmenuIdDetails($details['submenu_id']);
        
        $outputArray[0] = $details['id'];
        $outputArray[1] = $details ['menu_name'];
        $outputArray[2] = $menuDetails ['menu_name'];
        $outputArray[3] = $submenuDetails['submenu_name'];
        $outputArray[4] = $details['menu_priority'];
        echo json_encode($outputArray);
        
    } elseif ($_POST['task'] == 'dropId') {
        $id = htmlentities(trim($_POST['id']));
        $menu->dropTopMenu($id);
        $outputArray[0] = $id; 
        echo json_encode($outputArray);       
     }elseif ($_POST['task'] == 'activateId') {
        $id = htmlentities(trim($_POST['id']));
        $menu->activateTopMenu($id);
        $outputArray[0] = $id; 
        echo json_encode($outputArray);       
     }elseif($_POST['task'] == 'authenticate'){
        $urlName = htmlentities(trim($_POST['menu']));
        
        if ($menu->checkMenuTopName($urlName))
            $outputArray[0] = 1;
        else
            $outputArray[0] = 0;
        echo json_encode($outputArray);
    }else {
		$outputArray [0] = 0;
		echo json_encode ( $outputArray );
	}
}
?>