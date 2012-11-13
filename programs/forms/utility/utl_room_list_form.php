<?php
require_once 'config.php';

require_once BASE_PATH.'include/utility/class.rooms.php';
require_once BASE_PATH.'include/global/class.options.php';

$rooms = new rooms();
$options = new options ();

$options->isRequestAuthorised4Form('LMENUL10');

if (isset ( $_POST ['task'] )) {
	if ($_POST ['task'] == 'insertRecord') {				
		$id = $rooms->setRoomDetails($_POST['roomName'], $_POST['roomNo'], $_POST['floorNo'], $_POST['buildingName_val'], $_POST['seatingCapN'], $_POST['seatingCapE'], $_POST['roomType_val'], $_POST['descriptionRoom']);		
		$outputArray = array ();
		$outputArray[0] = 0;
		if ($id) {
			$details = $rooms->getRoomIdDetails($id);
			$outputArray[0] = $details['id'];
			$outputArray[1] = $details['room_name'];			
			$outputArray[2] = $details['room_no'];
			$outputArray[3] = $options->getOptionIdValue($details['building_id']);			
		}
		echo json_encode($outputArray);
	} else if ($_POST['task'] == 'search') {
                $hint = htmlentities(trim($_POST['room_hint']));
                $search_type = htmlentities(trim($_POST['search_type']));
                $data = $rooms->getRoomSearchIds($hint, $search_type);
                $i = 0;
                $outputArray[0][0] = 1;
                foreach ($data as $id) {
			$details = $rooms->getRoomIdDetails($id);
			$outputArray[$i][0] = $details['id'];
			$outputArray[$i][1] = $details['room_name'];			
			$outputArray[$i][2] = $details['room_no'];
			$outputArray[$i][3] = $options->getOptionIdValue($details['building_id']);
                        $i++;
                }
        echo json_encode($outputArray);
    }else if ($_POST['task'] == 'getRecordIdDetails') {
                $id = htmlentities(trim($_POST['id']));
                $details = $rooms->getRoomIdDetails($id);
                $outputArray[0] = $details['id'];
                $outputArray[1]  = $details['room_name'];
                $outputArray[2]  = $details['room_no'];
                $outputArray[3]  = $details['floor_no'];
                $outputArray[4]  = $options->getOptionIdValue($details['building_id']);
                $outputArray[5]  = $details['seating_normal'];
                $outputArray[6]  = $details['seating_exam'];
                $outputArray[7]  = $options->getOptionIdValue($details['room_type']);
                $outputArray[8]  = $details['description'];
                $outputArray[9] = $details['last_update_date'];
                $outputArray[10] = $rooms->getOfficerName($details['last_updated_by']);
                $outputArray[11] = $details['creation_date'];
                $outputArray[12] = $rooms->getOfficerName($details['created_by']);
                $outputArray[13] = $details['active'];
                
                //extra details for the edit form
                $outputArray[14]  = $details['building_id'];
                $outputArray[15]  = $details['room_type'];
                      
                echo json_encode($outputArray);
    }elseif($_POST['task'] == 'updateRecord'){     
    	$roomId = $_POST['valueId_u'];
    	$details = $rooms->getRoomIdDetails($roomId);
    	
    	if($details['room_name'] != $_POST['roomName_u']){
    		$rooms->setUpdateLog('Name from '.$details['room_name'].' to '.$_POST['roomName_u']);
    		$rooms->updateTableParameter('room_name', $_POST['roomName_u']);
    	}
    	if($details['room_no'] != $_POST['roomNo_u']){
    		$rooms->setUpdateLog('Room No from '.$details['room_no'].' to '.$_POST['roomNo_u']);
    		$rooms->updateTableParameter('room_no', $_POST['roomNo_u']);    		
    	}
    	if($details['floor_no'] != $_POST['floorNo_u']){
    		$rooms->setUpdateLog('Floor no from '.$details['floor_no'].' to '.$_POST['floorNo_u']);
    		$rooms->updateTableParameter('floor_no', $_POST['floorNo_u']);
    	}
    	if($details['building_id'] != $_POST['buildingName_uval']){
    		$rooms->setUpdateLog('Building from '.$details['building_id'].' to '.$_POST['buildingName_uval']);
    		$rooms->updateTableParameter('building_id', $_POST['buildingName_uval']);
    	}
    	if($details['seating_normal'] != $_POST['seatingCapN_u']){
    		$rooms->setUpdateLog('Seating Normal from '.$details['seating_normal'].' to '.$_POST['seatingCapN_u']);
    		$rooms->updateTableParameter('seating_normal', $_POST['seatingCapN_u']);
    	}
    	if($details['seating_exam'] != $_POST['seatingCapE_u']){
    		$rooms->setUpdateLog('Seating Exam from '.$details['seating_exam'].' to '.$_POST['seatingCapE_u']);
    		$rooms->updateTableParameter('seating_exam', $_POST['seatingCapE_u']);
    	}
    	if($details['room_type'] != $_POST['roomType_uval']){
    		$rooms->setUpdateLog('Room Type from '.$details['room_type'].' to '.$_POST['roomType_uval']);
    		$rooms->updateTableParameter('room_type', $_POST['roomType_uval']);
    	}
    	if($details['description'] != $_POST['descriptionRoom_u']){
    		$rooms->setUpdateLog('Description from '.$details['description'].' to '.$_POST['descriptionRoom_u']);
    		$rooms->updateTableParameter('description', $_POST['descriptionRoom_u']);
    	}    	
        $rooms->commitRoomDetailsUpdate($roomId);
        $details = $rooms->getRoomIdDetails($roomId);
        
        $outputArray[0] = $details['id'];
        $outputArray[1] = $details['room_name'];
        $outputArray[2] = $details['room_no'];
        $outputArray[3] = $options->getOptionIdValue($details['building_id']);
        echo json_encode($outputArray);
        
    } elseif ($_POST['task'] == 'dropRecord') {
        $id = htmlentities(trim($_POST['id']));
        $rooms->dropRoomDetails($id);
        $outputArray[0] = $id; 
        echo json_encode($outputArray);       
     }elseif ($_POST['task'] == 'activateRecord') {
        $id = htmlentities(trim($_POST['id']));
        $rooms->activateRoomDetails($id);
        $outputArray[0] = $id; 
        echo json_encode($outputArray);       
     }else {
		$outputArray [0] = 0;
		echo json_encode ( $outputArray );
	}
}
?>