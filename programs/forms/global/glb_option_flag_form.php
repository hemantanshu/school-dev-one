<?php
require_once 'config.php';
require_once BASE_PATH.'include/global/class.options.php';

$options = new options ();

$options->isRequestAuthorised4Form('LMENUL8');


if (isset ( $_POST ['task'] )) {
	if($_POST['task'] == "checkCode"){
		$shortCode = trim($_POST['shortCode']);
		if($options->checkShortCode($shortCode))
			$outputArray[0] = 1;
		else
			$outputArray[0] = 0;
		echo json_encode($outputArray);
	}else if ($_POST ['task'] == 'insertMenuUrl') {				
		$id = $options->setOptionType($_POST['optionName'], $_POST['shortCode'], $_POST['sMenuDescription']);		
		$outputArray = array ();
		$outputArray[0] = 0;
		if ($id) {
			$details = $options->getOptionTypeIdDetails($id);
			$outputArray[0] = $details['id'];
			$outputArray[1] = $details ['flag'];			
			$outputArray[2] = $details['comments'];
			$outputArray[3] = $details['sMenuDescription'];			
		}
		echo json_encode($outputArray);
	} else if ($_POST['task'] == 'search') {
        $hint = htmlentities(trim($_POST['menu_hint']));
        $search_type = htmlentities(trim($_POST['search_type']));
        $data = $options->searchOptionFlag($hint, $search_type);
        $i = 0;
        $outputArray[0][0] = 1;
        foreach ($data as $id) {
            $details = $options->getTableIdDetails($id);
			$outputArray[$i][0] = $details['id'];
			$outputArray[$i][1] = $details ['flag'];			
			$outputArray[$i][2] = $details['comments'];
			$outputArray[$i][3] = $details['description'];
            $i++;
        }
        echo json_encode($outputArray);
    }else if ($_POST['task'] == 'getUrlIdDetails') {
        $id = htmlentities(trim($_POST['id']));
        $details = $options->getOptionTypeIdDetails($id);  
        
        $outputArray[0] = $details['flag'];
        $outputArray[1] = $details['comments'];
        $outputArray[2] = $details['description'];
        $outputArray[3] = $details['last_update_date'];
        $outputArray[4] = $options->getOfficerName($details['last_updated_by']);
        $outputArray[5] = $details['creation_date'];
        $outputArray[6] = $options->getOfficerName($details['created_by']);
        $outputArray[7] = $details['active'];   
        $outputArray[8] = $details['id'];       
        
        echo json_encode($outputArray);
    }elseif($_POST['task'] == 'updateMenuUrl'){      
    	$details = $options->getOptionTypeIdDetails($_POST['valueId_u']);
    	if($details['description'] != $_POST['sMenuDescription_u']){
    		$options->setUpdateLog('from '.$details['description'].' to '.$_POST['sMenuDescription_u']);
    		$options->updateTableParameter('description', $_POST['sMenuDescription_u']);
    	}
    	if($details['comments'] != $_POST['optionName_u']){
    		$options->setUpdateLog('from '.$details['comments'].' to '.$_POST['optionName_u']);    		
    		$options->updateTableParameter('comments', $_POST['optionName_u']);
    	}         
        $options->commitOptionTypeUpdate($_POST['valueId_u']);
        
        $details = $options->getOptionTypeIdDetails($_POST['valueId_u']);
        
        $outputArray[0] = $details['id'];
        $outputArray[1] = $details ['flag'];
        $outputArray[2] = $details['comments'];
        $outputArray[3] = $details['sMenuDescription'];       
        echo json_encode($outputArray);
        
    } elseif ($_POST['task'] == 'dropId') {
        $id = htmlentities(trim($_POST['id']));
        $options->dropOptionType($id);
        $outputArray[0] = $id; 
        echo json_encode($outputArray);       
     }elseif ($_POST['task'] == 'activateId') {
        $id = htmlentities(trim($_POST['id']));
        $options->activateOptionTYpe($id);
        $outputArray[0] = $id; 
        echo json_encode($outputArray);       
     }else {
		$outputArray [0] = 0;
		echo json_encode ( $outputArray );
	}
}
?>