<?php
require_once 'config.php';
require_once BASE_PATH.'include/global/class.menu.php';
require_once BASE_PATH.'include/global/class.options.php';

$menu = new menu();
$options = new options();
$options->isRequestAuthorised4Form('LMENUL2');
if (isset($_POST['task'])) {
    if ($_POST['task'] == 'fetchDetails') {
        $id = htmlentities(trim($_POST['menuId']));        
        $details = $menu->getMenuUrlIdDetails($id);
        $parentUrlIdDetails = $menu->getMenuUrlIdDetails($details['parent_menu']);
        $outputArray[0] = $details['id'];
        $outputArray[1] = $details['menu_name'];
        $outputArray[2] = $details['menu_url'];
        $outputArray[3] = $details['menu_image_url'];
        $outputArray[4] = $details['menu_tagline'];
        $outputArray[5] = $details['menu_description'];
        $outputArray[6] = $details['menu_editable'];
        $outputArray[7] = $details['menu_authorized'];
        $outputArray[8] = $options->getOptionIdValue($details['url_source_id']);
        $outputArray[9] = $parentUrlIdDetails['menu_name'];
        $outputArray[10] = $details['last_update_date'];
        $outputArray[11] = $menu->getOfficerName($details['last_updated_by']);
        $outputArray[12] = $details['creation_date'];
        $outputArray[13] = $menu->getOfficerName($details['created_by']);        
        $outputArray[14] = $details['active'];
        
        $submenuIds = $menu->getMenuUrlAssociatedSubmenu($details['id']);
        
        foreach($submenuIds as $submenuId){
            $details = $menu->getMenuSubmenuIdDetails($submenuId);
            $outputArray[15] .= $details[1]." | ";
        }
        $topmenuIds = $menu->getMenuUrlAssociatedTopMenu($details[1]);
        foreach($topmenuIds as $topmenuId){
            $details = $menu->getMenuTopIdDetails($topmenuId);
            $outputArray[16] .= $details[1]." | ";
        }
        if(empty($outputArray[15]))
        	$outputArray[15] = 'N/A';
        if(empty($outputArray[16]))
        	$outputArray[16] = 'N/A';
        
        echo json_encode($outputArray);
        
    }elseif ($_POST['task'] == 'fetchLogDetails') {        
        $id = htmlentities(trim($_POST['menuId']));
        $logIds = $menu->getOperationLogIds($id);
        $outputArray[0][0] = 0;
        $i = 0;
        foreach($logIds as $logId){
            $details = $menu->getOperationLogIdDetails($logId);
            $outputArray[$i][0] = $menu->getOfficerName($details[2]);
            $outputArray[$i][1] = $menu->getDisplayDateTime($details[3]);
            $outputArray[$i][2] = $details[4];            
            ++$i;
        }
        echo json_encode($outputArray);
    }else{
        $outputArray[0] = 0;
        echo json_encode($outputArray);
    }
}
?>