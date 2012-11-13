<?php
require_once 'config.php';
require_once BASE_PATH.'include/global/class.options.php';
require_once BASE_PATH.'include/global/class.menu.php';

$menu = new menu();
$options = new options ();

$options->isRequestAuthorised4Form('LMENUL9');


if (isset ( $_POST ['task'] )) {
	if ($_POST ['task'] == 'insertOptionValue') {
		$id = $options->setNewOptionValue($_POST['valueName'],$_POST['optionId_glb'], $_POST['reserved']);		
		$outputArray = array ();
		$outputArray[0] = 0;
		if ($id) {
			$details = $options->getOptionIdDetails($id);
			$outputArray[0] = $details['id'];
			$outputArray[1] = $details ['value_name'];	
		}
		echo json_encode($outputArray);
	} else if ($_POST['task'] == 'search') {
        $hint = htmlentities(trim($_POST['menu_hint']));
        $search_type = htmlentities(trim($_POST['search_type']));
        $optionType = $_POST['optionId_glb'];
        $data = $options->getOptionSearchValueIds($hint, $optionType, $search_type);
        $i = 0;
        $outputArray[0][0] = 1;
        foreach ($data as $id) {
            $details = $options->getOptionIdDetails($id);
			$outputArray[$i][0] = $details['id'];
			$outputArray[$i][1] = $details ['value_name'];			
            $i++;
        }
        echo json_encode($outputArray);
    }else if ($_POST['task'] == 'getOptionValueDetails') {
        $id = htmlentities(trim($_POST['id']));
        $details = $options->getOptionIdDetails($id);  
        $outputArray[0] = $details['id'];
        $outputArray[1] = $details['value_name'];
        $outputArray[2] = $details['last_update_date'];
        $outputArray[3] = $menu->getOfficerName($details['last_updated_by']);
        $outputArray[4] = $details['creation_date'];
        $outputArray[5] = $menu->getOfficerName($details['created_by']);
        $outputArray[6] = $details['active'];         
        
        echo json_encode($outputArray);
    }elseif($_POST['task'] == 'updateOptionValue'){   	
    	$details = $options->getOptionIdDetails($_POST['valueId_u']);
    	if($details['value_name'] != $_POST['valueName_u']){
    		$menu->setUpdateLog('Name from '.$details['value_name'].' to '.$_POST['valueName_u']);
    		$options->updateTableParameter('value_name', $_POST['valueName_u']);    		
    	}
    	$options->commitOptionValueUpdate($_POST['valueId_u']);        
        
        $details = $options->getOptionIdDetails($_POST['valueId_u']);
        $outputArray[0] = $details['id'];
        $outputArray[1] = $details ['value_name'];     
        echo json_encode($outputArray);
        
    } elseif ($_POST['task'] == 'dropId') {
        $id = htmlentities(trim($_POST['id']));
        $options->dropOptionID($id);
        $outputArray[0] = $id; 
        echo json_encode($outputArray);       
     }elseif ($_POST['task'] == 'activateId') {
        $id = htmlentities(trim($_POST['id']));
        $options->activateOptionID($id);
        $outputArray[0] = $id; 
        echo json_encode($outputArray);       
     }else {
		$outputArray [0] = 0;
		echo json_encode ( $outputArray );
	}
}
?>