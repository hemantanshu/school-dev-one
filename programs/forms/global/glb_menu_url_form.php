<?php
require_once 'config.php';
require_once BASE_PATH.'include/global/class.editMenu.php';
require_once BASE_PATH.'include/global/class.options.php';

$menu = new editMenu();
$options = new options();

$options->isRequestAuthorised4Form('LMENUL1');
if (isset($_POST['task'])) {
    if ($_POST['task'] == 'insertMenuUrl') {
        $error = 0;
        $display_name = htmlentities(trim($_POST['display_name']));
        if (empty($display_name)) {
            $error++;
        }
        $menu_url = htmlentities(trim($_POST['menu_url']));
        if (empty($menu_url)) {
            $error++;
        }
        $image_url = htmlentities(trim($_POST['image_url']));
        if (empty($image_url)) {
            $error++;
        }
        $menu_tagline = htmlentities(trim($_POST['menu_tagline']));
        if (empty($menu_tagline)) {
            $error++;
        }
        $menu_description = htmlentities(trim($_POST['menu_description']));
        if (empty($menu_description)) {
            $error++;
        }
        $menu_type = htmlentities(trim($_POST['menu_type']));
        if (empty($menu_type)) {
            $error++;
        }        
        $menu_auth = htmlentities(trim($_POST['menu_auth']));
        if ($menu_auth == 'y') {
            $menu_auth = 'y';
        }else
            $menu_auth = 'n';
        $menu_edit = htmlentities(trim($_POST['menu_edit']));
        if ($menu_edit == 'y') {
            $menu_edit = 'y';
        }else
            $menu_edit = 'n';
        if (!$error) {
            $id = $menu->setNewMenuUrl($display_name, $menu_url, $menu_type, $menu_edit, $menu_auth, 'y', $image_url, $menu_tagline, $menu_description);
            $outputArray = array();
            if ($id) {
                $outputArray[0] = 1;                          //Sucess
                $details = $menu->getMenuUrlIdDetails($id);
                $outputArray[1] = $id;
                $outputArray[2] = $details['menu_name'];
                $outputArray[3] = $details['menu_url'];
                $outputArray[4] = $details['menu_editable'];
                $outputArray[5] = $details['active'];
            }
        } else {
            $outputArray[0] = 0; //Invalid data
            $outputArray[1] = 0;
        }
        echo json_encode($outputArray);
    } else if ($_POST['task'] == 'search') {
        $hint = htmlentities(trim($_POST['menu_hint']));
        $search_type = htmlentities(trim($_POST['search_type']));
        $data = $menu->getMenuUrlSearchIds($hint, $search_type);
        $i = 0;
        $outputArray[0][0] = 1;
        foreach ($data as $id) {
            $details = $menu->getMenuUrlIdDetails($id);
            $outputArray[$i][0] = $details['id'];
            $outputArray[$i][1] = $details['menu_name'];
            $outputArray[$i][2] = $details['menu_url'];
            $outputArray[$i][3] = $details['active'];
            $i++;
        }
        echo json_encode($outputArray);
    }
    else if ($_POST['task'] == 'dropId') {
        $id = htmlentities(trim($_POST['id']));
        if ($menu->dropMenuUrl($id)) {
            $outputArray[0] = 1;                          //Sucess            
            echo json_encode($outputArray);
        } 
        else{
            $outputArray[0] = 0;
            echo json_encode($outputArray);
        }
    } else if ($_POST['task'] == 'activateId') {
        $id = htmlentities(trim($_POST['id']));
        if ($menu->activateMenuUrl($id)) {
            $outputArray[0] = 1;
            echo json_encode($outputArray);
        } else {
            $outputArray[0] = 0;
            echo json_encode($outputArray);
        }
    } else if ($_POST['task'] == 'getUrlIdDetails') {
        $id = htmlentities(trim($_POST['id']));        
        $details = $menu->getMenuUrlIdDetails($id);
        
        $outputArray[0] = $details['id'];
        $outputArray[1] = $details['menu_name'];
        $outputArray[2] = $details['menu_url'];
        $outputArray[3] = $details['menu_image_url'];
        $outputArray[4] = $details['menu_tagline'];
        $outputArray[5] = $details['menu_description'];
        $outputArray[6] = $details['menu_editable'];
        $outputArray[7] = $details['menu_authorized'];
        $outputArray[8] = $options->getOptionIdValue($details['url_source_id']);
        $outputArray[9] = '';
        $outputArray[10] = $details['last_update_date'];
        $outputArray[11] = $menu->getOfficerName($details['last_updated_by']);
        $outputArray[12] = $details['creation_date'];
        $outputArray[13] = $menu->getOfficerName($details['created_by']);        
        $outputArray[14] = $details['active'];
        $outputArray[15] = $details['url_source_id'];
        echo json_encode($outputArray);
    }elseif($_POST['task'] == 'updateMenuUrl'){
        $menu_auth = htmlentities(trim($_POST['menu_auth_u']));
        if ($menu_auth == 'y') {
            $menu_auth = 'y';
        }else
            $menu_auth = 'n';
        $menu_edit = htmlentities(trim($_POST['menu_edit_u']));
        if ($menu_edit == 'y') {
            $menu_edit = 'y';
        }else
            $menu_edit = 'n';
        
        $details = $menu->getMenuUrlIdDetails($_POST['menuId_u']);        
        
    	if($details['menu_name'] != $_POST['display_name_u']){
        	$menu->setUpdateLog('Name from '.$details['menu_name'].' to '.$_POST['display_name_u']);
        	$menu->updateTableParameter('menu_name', $_POST['display_name_u']);
        }
        if($details['menu_url'] != $_POST['menu_url_u']){
        	$menu->setUpdateLog('URL from '.$details['menu_url'].' to '.$_POST['menu_url_u']);
        	$menu->updateTableParameter('menu_url', $_POST['menu_url_u']);
        }
        
        if($details['menu_image_url'] != $_POST['image_url_u']){
        	$menu->setUpdateLog('Image from '.$details['menu_image_url'].' to '.$_POST['image_url_u']);
        	$menu->updateTableParameter('menu_image_url', $_POST['image_url_u']);
        }
        if($details['menu_tagline'] != $_POST['menu_tagline_u']){
        	$menu->setUpdateLog('Tagline from '.$details['menu_tagline'].' to '.$_POST['menu_tagline_u']);
        	$menu->updateTableParameter('menu_tagline', $_POST['menu_tagline_u']);
        }
        if($details['menu_description'] != $_POST['menu_description_u']){
        	$menu->setUpdateLog('Description from '.$details['menu_description'].' to '.$_POST['menu_description_u']);
        	$menu->updateTableParameter('menu_description', $_POST['menu_description_u']);
        }
        if($details['url_source_id'] != $_POST['menu_type_u']){
        	$menu->setUpdateLog('Source from '.$details['url_source_id'].' to '.$_POST['menu_type_u']);
        	$menu->updateTableParameter('url_source_id', $_POST['menu_type_u']);
        }
        if($details['menu_authorized'] != $menu_auth){
        	$menu->setUpdateLog('Authentication from '.$details['menu_authorized'].' to '.$menu_auth);
        	$menu->updateTableParameter('menu_authorized', $menu_auth);
        }
        if($details['menu_editable'] != $menu_edit){
        	$menu->setUpdateLog('Editable from '.$details['menu_editable'].' to '.$menu_edit);
        	$menu->updateTableParameter('menu_editable', $menu_edit);
        } 
        $menu->commitUpdateMenuUrl($_POST['menuId_u']);
        
        $outputArray[0] = 1;                          //Sucess
        $details = $menu->getMenuUrlIdDetails($_POST['menuId_u']);
        
        $outputArray[1] = $details['id'];
        $outputArray[2] = $details['menu_name'];
        $outputArray[3] = $details['menu_url'];
        $outputArray[4] = $details['menu_editable'];
        $outputArray[5] = $details['active'];
        echo json_encode($outputArray);
    }elseif($_POST['task'] == 'authenticate'){
        $urlName = htmlentities(trim($_POST['menu']));
        
        if ($menu->checkMenuUrlName($urlName))
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