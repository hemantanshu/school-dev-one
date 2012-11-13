<?php
require_once 'config.php';
require_once BASE_PATH.'include/global/class.editMenu.php';
require_once BASE_PATH.'include/global/class.options.php';

$menu = new editMenu ();
$options = new options ();

$options->isRequestAuthorised4Form('LMENUL6');

if (isset ( $_POST ['task'] )) {	
	if ($_POST ['task'] == 'insertTabOut') {				
		$topMenu = $_POST['topMenu'];
		$details = $menu->getMenuTopIdDetails($topMenu);
		$outputArray[0] = $details['menu_description'] == "" ? 0 : $details['menu_description'];
		echo json_encode($outputArray);
	}elseif ($_POST ['task'] == 'insertMenuUrl') {				
		$id = $menu->setMenuAssignment($_POST['group_val'], '', $_POST['topMenu_val'], 'y', $_POST['edit'], $_POST['sDate'], $_POST['eDate']);		
		$outputArray = array ();
		$outputArray[0] = 0;
		if ($id) {
			$details = $menu->getMenuAssignmentIdDetails($id);
			$menuDetails = $menu->getMenuTopIdDetails($details['menu_id']);
			$outputArray[0] = $id;
			$outputArray[1] = $menuDetails['menu_name'];			
			$outputArray[2] = $menu->getDisplayDate($details['start_date']);
			$outputArray[3] = $menu->getDisplayDate($details['end_date']);	
			$outputArray[4]	= $details['generic_id'];	
			$outputArray[5] = $options->getOptionIdValue($details['generic_id']);
		}
		echo json_encode($outputArray);
	} elseif ($_POST['task'] == 'search') {
        $hint = htmlentities(trim($_POST['menu_hint_val']));
        $search_type = htmlentities(trim($_POST['search_type']));
        $data = $menu->getMenuGroupAssignmentIds($hint, $search_type);
        $i = 0;
        $outputArray[0][0] = 1;
        foreach ($data as $id) {
            $details = $menu->getMenuAssignmentIdDetails($id);
			$menuDetails = $menu->getMenuTopIdDetails($details['menu_id']);
			$outputArray[$i][0] = $id;
			$outputArray[$i][1] = $menuDetails['menu_name'];			
			$outputArray[$i][2] = $menu->getDisplayDate($details['start_date']);
			$outputArray[$i][3] = $menu->getDisplayDate($details['end_date']);
            $i++;
        }
        echo json_encode($outputArray);
    }elseif ($_POST['task'] == 'getUrlIdDetails') {
        $id = htmlentities(trim($_POST['id']));
        $details = $menu->getMenuAssignmentIdDetails($id);
        $urlDetails = $menu->getMenuTopIdDetails($details['menu_id']);  
        
        $outputArray[0] = $details['id'];
        $outputArray[1] = $options->getOptionIdValue($details['generic_id']);
        $outputArray[2] = $urlDetails['menu_name'];        
        $outputArray[3] = $urlDetails['menu_description'];                       
        $outputArray[4] = $details['editable'];
        $outputArray[5] = $details['active'];
        $outputArray[6] = $menu->getDisplayDate($details['start_date']);
        $outputArray[7] = $menu->getDisplayDate($details['end_date']);
        $outputArray[8] = $details['last_update_date'];
        $outputArray[9] = $menu->getOfficerName($details['last_updated_by']);
        $outputArray[10] = $details['creation_date'];
        $outputArray[11] = $menu->getOfficerName($details['created_by']);
             
        $outputArray[12] = $details['menu_id'];
        $outputArray[13] = $details['generic_id'];    
        $outputArray[14] = $details['start_date'];
        $outputArray[15] = $details['end_date'];
        
        
        echo json_encode($outputArray);
    }elseif($_POST['task'] == 'updateMenuUrl'){  	
        $menu->updateTableParameter('menu_id', $_POST['topMenu_u_val']);
        $menu->updateTableParameter('generic_id', $_POST['group_u_val']);
        $menu->updateTableParameter('start_date', $_POST['sDate_u']);
        $menu->updateTableParameter('end_date', $_POST['eDate_u']);
        $menu->updateTableParameter('editable', $_POST['edit_u']);
        
        $menu->commitMenuAssignment($_POST['assignmentId_u']);
        $details = $menu->getMenuTopIdDetails($_POST['assignmentId_u']);
        $menuDetails = $menu->getMenuTopIdDetails($details['menu_id']);
        $outputArray[0] = $details['id'];
		$outputArray[1] = $menuDetails['menu_name'];			
		$outputArray[2] = $menu->getDisplayDate($details['start_date']);
		$outputArray[3] = $menu->getDisplayDate($details['end_date']);
		$outputArray[4]	= $details['generic_id'];
		$outputArray[5] = $options->getOptionIdValue($details['generic_id']);
        
        echo json_encode($outputArray);
        
    } elseif ($_POST['task'] == 'dropId') {
        $id = htmlentities(trim($_POST['id']));
        $menu->dropMenuAssignment($id);
        $outputArray[0] = $id; 
        echo json_encode($outputArray);       
     }elseif ($_POST['task'] == 'activateId') {
        $id = htmlentities(trim($_POST['id']));
        $menu->activateMenuAssignment($id);
        $outputArray[0] = $id; 
        echo json_encode($outputArray);       
     }else {
		$outputArray [0] = 0;
		echo json_encode ( $outputArray );
	}
}
?>