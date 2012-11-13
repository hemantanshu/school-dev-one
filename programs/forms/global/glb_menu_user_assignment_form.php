<?php
require_once 'config.php';
require_once BASE_PATH.'include/global/class.editMenu.php';
require_once BASE_PATH.'include/global/class.options.php';

$menu = new editMenu ();
$options = new options ();

$options->isRequestAuthorised4Form('LMENUL7');

if (isset ( $_POST ['task'] )) {	
	if ($_POST ['task'] == 'insertMenuUrl') {
		$i = 0;				
		if($_POST['topMenu_val'] != ""){
			$id = $menu->setMenuAssignment($_POST['user_val'], 'y', $_POST['topMenu_val'], 'y', $_POST['edit'], $_POST['sDate'], $_POST['eDate']);
			$menuDetails = $menu->getTableIdDetails($_POST['topMenu_val']);
			$outputArray[$i][0] = $id;
			$outputArray[$i][] = $menuDetails['menu_name'];
			$outputArray[$i][] = '-';
			$outputArray[$i][] = $_POST['edit'];
			$outputArray[$i][] = $options->getDisplayDate($_POST['sDate']);
			$outputArray[$i][] = $options->getDisplayDate($_POST['eDate']);
			$outputArray[$i][] = $_POST['user_val'];
			$outputArray[$i][] = $_POST['user'];
			++$i;				
		}
		
		if($_POST['menuUrl_val'] != ""){
			$id = $menu->setMenuAssignment($_POST['user_val'], 'y', $_POST['menuUrl_val'], '', $_POST['edit'], $_POST['sDate'], $_POST['eDate']);
			$menuDetails = $menu->getTableIdDetails($_POST['menuUrl_val']);
			$outputArray[$i][0] = $id;
			$outputArray[$i][] = '-';
			$outputArray[$i][] = $menuDetails['menu_name'];
			$outputArray[$i][] = $_POST['edit'];
			$outputArray[$i][] = $options->getDisplayDate($_POST['sDate']);
			$outputArray[$i][] = $options->getDisplayDate($_POST['eDate']);
			$outputArray[$i][] = $_POST['user_val'];
			$outputArray[$i][] = $_POST['user'];
		}
		echo json_encode($outputArray);
	} elseif ($_POST['task'] == 'search') {
        $hint = htmlentities(trim($_POST['userHint_val']));
        $search_type = htmlentities(trim($_POST['search_type']));
        $data = $menu->getMenuGroupAssignmentIds($hint, $search_type);
        $i = 0;
        $outputArray[0][0] = 1;
        foreach ($data as $id) {
            $details = $menu->getMenuAssignmentIdDetails($id);
            $menuDetails = $menu->getTableIdDetails($details['menu_id']);            
            
            $outputArray[$i][0] = $id;
            if($details['menu_top_flag'] == 'y'){
            	$outputArray[$i][] = $menuDetails['menu_name'];
            	$outputArray[$i][] = '-';            	 
            }else{
            	$outputArray[$i][] = '-';
            	$outputArray[$i][] = $menuDetails['menu_name'];            	
            }			
			$outputArray[$i][] = $details['editable'];			
			$outputArray[$i][] = $menu->getDisplayDate($details['start_date']);
			$outputArray[$i][] = $menu->getDisplayDate($details['end_date']);
            $i++;
        }
        echo json_encode($outputArray);
    }elseif ($_POST['task'] == 'getUrlIdDetails') {
        $id = htmlentities(trim($_POST['id']));
        $details = $menu->getMenuAssignmentIdDetails($id);
        $urlDetails = $menu->getTableIdDetails($details['menu_id']); 
         
        $urlName =  $urlDetails['menu_name'];
        $urlName .= $details['menu_top_flag'] == 'y' ? " ( Top Menu )" : " ( Single Menu )";
        $outputArray[0] = $details['id'];
        $outputArray[] = $options->getOfficerName($details['generic_id']);
        $outputArray[] = $urlName; 
        $outputArray[] = $details['editable'];
        $outputArray[] = $menu->getDisplayDate($details['start_date']);
        $outputArray[] = $menu->getDisplayDate($details['end_date']);
        $outputArray[] = $details['last_update_date'];
        $outputArray[] = $menu->getOfficerName($details['last_updated_by']);
        $outputArray[] = $details['creation_date'];
        $outputArray[] = $menu->getOfficerName($details['created_by']);
        $outputArray[] = $details['active'];
        
        $outputArray[] = $details['start_date'];
        $outputArray[] = $details['end_date'];
        $outputArray[] = $urlDetails['menu_name'];       
        
        echo json_encode($outputArray);
    }elseif($_POST['task'] == 'updateMenuUrl'){  	
        $assignmentId = $_POST['assignmentId_u'];
        $details = $menu->getTableIdDetails($assignmentId);
        
        if($details['start_date'] != $_POST['sDate_u']){
        	$menu->setUpdateLog('Start Date from '.$details['start_date'].' to '.$_POST['sDate_u']);
        	$menu->updateTableParameter('start_date', $_POST['sDate_u']);
        }
        if($details['end_date'] != $_POST['eDate_u']){
        	$menu->setUpdateLog('End Date from '.$details['end_date'].' to '.$_POST['eDate_u']);
        	$menu->updateTableParameter('end_date', $_POST['eDate_u']);
        }
        if($details['editable'] != $_POST['edit_u']){
        	$menu->setUpdateLog('Editable from '.$details['editable'].' to '.$_POST['edit_u']);
        	$menu->updateTableParameter('editable', $_POST['edit_u']);
        }    	        
        if($menu->commitMenuAssignment($assignmentId)){
        	$details = $menu->getTableIdDetails($assignmentId);
        	$menuDetails = $menu->getTableIdDetails($details['menu_id']);
        	$outputArray[0] = $id;
        	if($details['menu_top_flag'] == 'y'){
        		$outputArray[] = $menuDetails['menu_name'];
        		$outputArray[] = '-';
        	}else{
        		$outputArray[] = '-';
        		$outputArray[] = $menuDetails['menu_name'];        		
        	}        	
        	$outputArray[] = $details['editable'];
        	$outputArray[] = $options->getDisplayDate($details['start_date']);
        	$outputArray[] = $options->getDisplayDate($details['end_date']);
        }       
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