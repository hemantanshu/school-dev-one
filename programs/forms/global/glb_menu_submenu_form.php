<?php
require_once 'config.php';
require_once BASE_PATH.'include/global/class.editMenu.php';
require_once BASE_PATH.'include/global/class.options.php';

$menu = new editMenu();
$options = new options();

$options->isRequestAuthorised4Form('LMENUL3');

if (isset($_POST['task'])) {
    if ($_POST['task'] == 'insertMenuUrl') {
        $counter = $menu->setNewSubMenu($_POST['sMenuName'], $_POST['sMenuDescription']);
        $details = $menu->getMenuSubmenuIdDetails($counter);
        if($counter != ""){
            $outputArray[0] = 1;
            $outputArray[1] = $counter;
            $outputArray[2] = $details['submenu_name'];
            $outputArray[3] = $details['submenu_description'];
        }
        echo json_encode($outputArray);
    } else if ($_POST['task'] == 'search') {
        $hint = htmlentities(trim($_POST['menu_hint']));
        $search_type = htmlentities(trim($_POST['search_type']));
        $data = $menu->getMenuSubmenuSearchIds($hint, $search_type);
        $i = 0;        
        $outputArray[0][0] = 1;
        foreach ($data as $id) {
        	$outputArray[$i] = array();
            $details = $menu->getMenuSubmenuIdDetails($id);
            $outputArray[$i][0] = $details['id'];
            $outputArray[$i][1] = $details['submenu_name'];
            $outputArray[$i][2] = $details['submenu_description'];
            $i++;            
        }
        echo json_encode($outputArray);
    }else if ($_POST['task'] == 'getUrlIdDetails') {
        $id = htmlentities(trim($_POST['id']));
        $details = $menu->getMenuSubmenuIdDetails($_POST['id']);
        
        $outputArray[0] = $details['id'];
        $outputArray[1] = $details['submenu_name'];
        $outputArray[2] = $details['submenu_description'];
        $outputArray[3] = $details['last_update_date'];
        $outputArray[4] = $menu->getOfficerName($details['last_updated_by']);
        $outputArray[5] = $details['creation_date'];
        $outputArray[6] = $menu->getOfficerName($details['created_by']);        
        $outputArray[7] = $details['active'];
        echo json_encode($outputArray);
    }elseif($_POST['task'] == 'updateMenuUrl'){        
        $menu->updateTableParameter('submenu_name', $_POST['sMenuName_u']);
        $menu->updateTableParameter('submenu_description', $_POST['sMenuDescription_u']);
                
        $menu->commitUpdateSubmenu($_POST['menuId_u']);
        $details = $menu->getMenuSubmenuIdDetails($_POST['menuId_u']);
        $outputArray[0] = $details['id'];
        $outputArray[1] = $details['submenu_name'];
        $outputArray[2] = $details['submenu_description'];
        echo json_encode($outputArray);
    } elseif ($_POST['task'] == 'dropId') {
        $id = htmlentities(trim($_POST['id']));
        $menu->dropMenuSubmenu($id);
        $outputArray[0] = $id; 
        echo json_encode($outputArray);       
     }elseif ($_POST['task'] == 'activateId') {
        $id = htmlentities(trim($_POST['id']));
        $menu->activateMenuSubmenu($id);
        $outputArray[0] = $id; 
        echo json_encode($outputArray);       
     }elseif($_POST['task'] == 'authenticate'){
        $urlName = htmlentities(trim($_POST['menu']));
        
        if ($menu->checkMenuSubmenuName($urlName))
            $outputArray[0] = 1;
        else
            $outputArray[0] = 0;
        echo json_encode($outputArray);
    }else{
     	$outputArray[0] = 0;
        echo json_encode($outputArray);
    }
}
?>