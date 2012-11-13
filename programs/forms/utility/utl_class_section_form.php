<?php
require_once 'config.php';

require_once BASE_PATH . 'include/global/class.options.php';
require_once BASE_PATH . 'include/utility/class.sections.php';
require_once BASE_PATH . 'include/utility/class.rooms.php';
require_once BASE_PATH . 'include/hrms/class.designation.php';
require_once BASE_PATH . 'include/global/class.notification.php';
require_once BASE_PATH . 'include/utility/class.personalInfo.php';
require_once BASE_PATH . 'include/global/class.session.php';

$section = new sections ();
$options = new options ();
$room = new rooms ();
$designation = new Designation();
$notification = new Notification();
$personalInfo = new personalInfo();
$session = new Session();

$options->isRequestAuthorised4Form('LMENUL23');

if (isset ( $_POST ['task'] )) {
	if ($_POST ['task'] == 'insertRecord') {
		$classDetails = $section->getTableIdDetails($_POST['classId']);
		if($session->isSessionEditable($classDetails['session_id'])){
			$sectionId = $section->setSectionDetails ( $_POST ['classId'], $classDetails['session_id'], $_POST ['sectionName'], $_POST ['studentCap'], $_POST ['roomAllocated_val'], $_POST['coordinator_val'] );
			
			//handling the designation notification
			$designationId = $designation->setUserRank($_POST['coordinator_val'], 'LRESER4', $section->getCurrentDate(), '', $sectionId);
			$notificationId = $notification->setNewNotification($_POST['coordinator_val'], 'Assigned as coordinator for Section '.$_POST['sectionName'], 'You have been assiged as the section coordinator for this session for the section '.$_POST['sectionName'], $section->getCurrentDate(), '', 100, $sectionId);
			
			$outputArray = array ();
			$outputArray [0] = 0;
			if ($sectionId) {
				$details = $section->getSectionIdDetails ( $sectionId );
				$roomDetails = $room->getRoomIdDetails ( $details ['room_id'] );
				$outputArray [0] = $details ['id'];
				$outputArray [1] = $details ['section_name'];
				$outputArray [2] = $details ['student_capacity'];
				$outputArray [3] = $roomDetails ['room_name'];
			}	
		}else{
			$outputArray[0] = 0;
		}		
		echo json_encode ( $outputArray );
	} else if ($_POST ['task'] == 'search') {
		$hint = htmlentities ( trim ( $_POST ['hint'] ) );
		$search_type = htmlentities ( trim ( $_POST ['search_type'] ) );
		$classId = $_POST ['classId'];
		$data = $section->getClassSectionSearchIds ( $classId, $hint, $search_type );
		$i = 0;
		$outputArray [0] [0] = 1;		
		foreach ( $data as $id ) {
			$details = $section->getSectionIdDetails ( $id );
			$roomDetails = $room->getRoomIdDetails ( $details ['room_id'] );
			if($i == 0){
				$editEnabled = 0;
				if($session->isSessionEditable($details['session_id']))
					$editEnabled = 1;
			}
			$outputArray [$i] [0] = $details ['id'];
			$outputArray [$i] [] = $details ['section_name'];
			$outputArray [$i] [] = $details ['student_capacity'];
			$outputArray [$i] [] = $roomDetails ['room_name'];
			$outputArray [$i] [] = $editEnabled;
			$i ++;
		}
		echo json_encode ( $outputArray );
	} else if ($_POST ['task'] == 'getRecordIdDetails') {
		$id = htmlentities ( trim ( $_POST ['id'] ) );
		$sectionDetails = $section->getSectionIdDetails ( $id );
        $classDetails = $section->getTableIdDetails($sectionDetails['class_id']);
        $classNameDetails = $section->getTableIdDetails($classDetails['class_id']);
		$roomDetails = $room->getRoomIdDetails ( $sectionDetails ['room_id'] );
		$personalInfo->getUserIdDetails($sectionDetails['section_coordinator_id']);
		
		$outputArray [0] = $sectionDetails['id'];
		$outputArray [] = $classNameDetails['class_name'];
		$outputArray [] = $sectionDetails ['student_capacity'];
		$outputArray [] = $sectionDetails ['section_name'];
		$outputArray [] = $roomDetails ['room_name'];
		$outputArray [] = $roomDetails ['room_no'];
		$outputArray [] = $roomDetails ['floor_no'];
		$outputArray [] = $options->getOptionIdValue ( $roomDetails ['building_id'] );
		$outputArray [] = $roomDetails ['seating_normal'];		
		
		$outputArray [] = $sectionDetails ['last_update_date'];
		$outputArray [] = $section->getOfficerName ( $sectionDetails ['last_updated_by'] );
		$outputArray [] = $sectionDetails ['creation_date'];
		$outputArray [] = $section->getOfficerName ( $sectionDetails ['created_by'] );
		$outputArray [] = $sectionDetails ['active']; //13
				
		$outputArray [] = $sectionDetails ['room_id'];
		
		$outputArray [] = $personalInfo->getUserName();
		$outputArray [] = $sectionDetails['section_coordinator_id'];
		$outputArray [] = $session->isSessionEditable($sectionDetails['session_id']) ? 1 : 0;
		
		echo json_encode ( $outputArray );
	} elseif ($_POST ['task'] == 'updateRecord') {		
		$details = $section->getSectionIdDetails ( $_POST['valueId_u'] );
		if($session->isSessionEditable($details['session_id'])){
			if($details['section_name'] != $_POST['sectionName_e']){
				$section->setUpdateLog('Name from '.$details['section_name'].' to '.$_POST['sectionName_e']);
				$section->updateTableParameter ( 'section_name', $_POST ['sectionName_e'] );
			}
			if($details['student_capacity'] != $_POST['studentCap_e']){
				$section->setUpdateLog('Student capacity from '.$details['student_capacity'].' to '.$_POST['studentCap_e']);
				$section->updateTableParameter ( 'student_capacity', $_POST ['studentCap_e'] );
			}
			if($details['room_id'] != $_POST['roomAllocated_eval']){
				$section->setUpdateLog('Room from '.$details['room_id'].' to '.$_POST['roomAllocated_eval']);
				$section->updateTableParameter ( 'room_id', $_POST ['roomAllocated_eval'] );
			}
			if($details['section_coordinator_id'] != $_POST['coordinator_uval']){
				$section->setUpdateLog('Coordiantor from '.$details['section_coordinator_id'].' to '.$_POST['coordinator_uval']);
				$section->updateTableParameter ( 'section_coordinator_id', $_POST ['coordinator_uval'] );
			}
			$section->commitSectionDetailsUpdate ( $_POST ['valueId_u'] );
			
			if($details['section_coordinator_id'] != $_POST['coordinator_uval']){
				$designation->unsetUserRank($details['section_coordinator_id'], 'LRESER4', $details['id']);
				$notification->setNewNotification($details['section_coordinator_id'], 'Decomissioned as coordinator for section '.$_POST['sectionName_e'], 'You have been removed as the section coordinator for the section '.$_POST['sectionName_e'], $section->getCurrentDate(), '', 100, $details['id']);
					
				$designationId = $designation->setUserRank($_POST['cooordinator_uval'], 'LRESER4', $section->getCurrentDate(), '', $details['id']);
				$notificationId = $notification->setNewNotification($_POST['coordinator_uval'], 'Assigned as coordinator for section '.$_POST['sectionName_e'], 'You have been assiged as the section coordinator for this session for the session '.$_POST['sessionName_e'], $section->getCurrentDate(), '', 100, $details['id']);
			}
			
			$details = $section->getSectionIdDetails ( $_POST['valueId_u'] );
			$roomDetails = $room->getRoomIdDetails ( $details ['room_id'] );
			$outputArray [0] = $details ['id'];
			$outputArray [1] = $details ['section_name'];
			$outputArray [2] = $details ['student_capacity'];
			$outputArray [3] = $roomDetails ['room_name'];
		}else{
			$outputArray[0] = 0;
		}
		
		
		echo json_encode ( $outputArray );
	
	} elseif ($_POST ['task'] == 'dropRecord') {
		$id = htmlentities ( trim ( $_POST ['id'] ) );
		$section->dropSectionDetails($id);
		$outputArray [0] = $id;
		echo json_encode ( $outputArray );
	} elseif ($_POST ['task'] == 'activateRecord') {
		$id = htmlentities ( trim ( $_POST ['id'] ) );
		$section->activateSectionDetails($id);
		$outputArray [0] = $id;
		echo json_encode ( $outputArray );
	} else {
		$outputArray [0] = 0;
		echo json_encode ( $outputArray );
	}
}
?>