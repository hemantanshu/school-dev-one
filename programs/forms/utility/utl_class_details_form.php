<?php
error_reporting(1);
require_once 'config.php';

require_once BASE_PATH . 'include/global/class.options.php';
require_once BASE_PATH . 'include/utility/class.sections.php';
require_once BASE_PATH . 'include/utility/class.rooms.php';
require_once BASE_PATH . 'include/hrms/class.designation.php';
require_once BASE_PATH . 'include/global/class.notification.php';
require_once BASE_PATH . 'include/global/class.session.php';
require_once BASE_PATH . 'include/utility/class.personalInfo.php';

$section = new sections ();
$options = new options ();
$room = new rooms ();
$notification = new Notification();
$designation = new Designation();
$session = new Session();
$personalInfo = new personalInfo();

$options->isRequestAuthorised4Form('LMENUL22');

if (isset ( $_POST ['task'] )) {
	if ($_POST ['task'] == 'insertRecord') {
		
		//setting up the class details
		$classId = $section->setClassDetails($_POST['className'], $_POST['sessionId'], $_POST['level'], $_POST['classCoordinator_val']);
				
		//setting up the class section details
		$sectionId = $section->setSectionDetails ( $classId, $_POST['sessionId'], $_POST ['sectionName'], $_POST ['studentCap'], $_POST ['roomAllocated_val'] );		
		
		//setting up the employee designation details
		if($_POST['classCoordinator_val'] != ''){
			$designationId = $designation->setUserRank($_POST['classCoordinator_val'], 'LRESER3', $section->getCurrentDate(), '', $classId);
			$notificationId = $notification->setNewNotification($_POST['classCoordinator_val'], 'Assigned as coordinator for class '.$_POST['className'], 'You have been assiged as the class coordinator for this session for the class '.$_POST['className'], $section->getCurrentDate(), '', 100, $classId);
		}
			
		
		//setting up the notification for the employee for this post
		
		
		
		$outputArray = array ();
		$outputArray [0] = 0;
		if ($classId) {
			$outputArray [0] = $classId;
			$outputArray [] = $_POST['className'];
			$outputArray [] = $_POST['level'];
		}
		echo json_encode ( $outputArray );
	} else if ($_POST ['task'] == 'search') {
		$search_type = htmlentities ( trim ( $_POST ['search_type'] ) );
		$sessionId = $_POST['sessionId'];
		$editEnabled = 0;
		if($session->isSessionEditable($sessionId))
			$editEnabled = 1;
		$data = $section->getClassIds($sessionId, $search_type);
		$i = 0;
		$outputArray [0] [0] = 1;
		foreach ( $data as $id ) {
			$details = $section->getTableIdDetails($id);
			$classDetails = $section->getTableIdDetails($details['class_id']);
			$outputArray [$i] [0] = $id;
			$outputArray [$i] [1] = $classDetails['class_name'];
			$outputArray [$i] [] = $details['level'];
			$outputArray [$i] [] = $editEnabled;
			$i ++;
		}
		echo json_encode ( $outputArray );
	} else if ($_POST ['task'] == 'getRecordIdDetails') {
		$id = htmlentities ( trim ( $_POST ['id'] ) );
		$details = $section->getTableIdDetails($id);
		$classDetails = $section->getTableIdDetails($details['class_id']);
		$sectionAssociationIds = $section->getClassSectionIds ( $id, 1 );
		$sectionDetails = $section->getSectionIdDetails ( $sectionAssociationIds [0] );
		$roomDetails = $room->getRoomIdDetails ( $sectionDetails ['room_id'] );
		
		$editEnabled = 0;
		if($session->isSessionEditable($sessionId))
			$editEnabled = 1;
		$personalInfo->getUserIdDetails($details ['class_coordinator_id']);
		
		$outputArray [0] = $details ['id'];
		$outputArray [] = $classDetails ['class_name'];
		$outputArray [] = $sectionDetails ['student_capacity'];
		$outputArray [] = $sectionDetails ['section_name'];
		$outputArray [] = $roomDetails ['room_name'];
		$outputArray [] = $roomDetails ['room_no'];
		$outputArray [] = $roomDetails ['floor_no'];
		$outputArray [] = $options->getOptionIdValue ( $roomDetails ['building_id'] );
		$outputArray [] = $roomDetails ['seating_normal'];		
		$outputArray [] = $details ['']; //don't delete this array element
		$outputArray [] = $details ['']; //don't delete this array element
		$outputArray [] = $personalInfo->getUserName();
		$outputArray [] = $details ['level'];
		
		$outputArray [] = $details ['last_update_date'];
		$outputArray [] = $section->getOfficerName ( $details ['last_updated_by'] );
		$outputArray [] = $details ['creation_date'];
		$outputArray [] = $section->getOfficerName ( $details ['created_by'] );
		$outputArray [] = $details ['active'];
		$outputArray [] = $editEnabled;
		
		$outputArray [] = $sectionDetails ['id'];
		$outputArray [] = $sectionDetails ['room_id'];
		$outputArray [] = $details ['class_coordinator_id']; //21
		
		echo json_encode ( $outputArray );
	} elseif ($_POST ['task'] == 'updateRecord') {	
			
		$classId = $_POST['valueId_u'];
		$sectionId = $_POST['associationId_u'];
		$classDetails = $section->getTableIdDetails($classId);
		$sectionDetails = $section->getSectionIdDetails($sectionId);		

		if($session->isSessionEditable($sectionDetails['session_id']) && $sectionDetails['class_id'] == $classDetails['id']){
			if($sectionDetails['section_name'] != $_POST['sectionName_u']){
				$section->setUpdateLog('Name from '.$sectionDetails['section_name'].' to '.$_POST['sectionName_u']);
				$section->updateTableParameter ( 'section_name', $_POST ['sectionName_u'] );
			}
			if($sectionDetails['student_capacity'] != $_POST['studentCap_u']){
				$section->setUpdateLog('Student Capacity from '.$sectionDetails['student_capacity'].' to '.$_POST['studentCap_u']);
				$section->updateTableParameter ( 'student_capacity', $_POST ['studentCap_u'] );
			}
			if($sectionDetails['room_id'] != $_POST['roomAllocated_uval']){
				$section->setUpdateLog('Room from '.$sectionDetails['room_id'].' to '.$_POST['roomAllocated_uval']);
				$section->updateTableParameter ( 'room_id', $_POST ['roomAllocated_uval'] );
			}			
			$section->commitSectionDetailsUpdate ( $sectionDetails['id'] );
			
			//updating the class details	
			if($classDetails['class_coordinator_id'] != $_POST['classCoordinator_uval']){
				$section->setUpdateLog('Class coordinator from '.$classDetails['class_coordinator_id'].' to '.$_POST['classCoordinator_uval']);
				$section->updateTableParameter('class_coordinator_id', $_POST['classCoordinator_uval']);
			}
			if($classDetails['level'] != $_POST['level_u']){
				$section->setUpdateLog('Level from '.$classDetails['level'].' to '.$_POST['level_u']);				
				$section->updateTableParameter('level', $_POST['level_u']);
			}			
			$section->commitClassDetailsUpdate($classId);
			
			//updating the class name
			$_POST['className_u'] == $classDetails['class_name'] ? '' : $section->updateTableParameter('class_name', $_POST['className_u']);
			$section->commitClassNameDetailsUpdate($classDetails['class_id']);

			if($_POST['classCoordinator_uval'] != $classDetails['class_coordinator_id']){
				$designation->unsetUserRank($classDetails['class_coordinator_id'], 'LRESER3', $classId);
				$notification->setNewNotification($classDetails['class_coordinator_id'], 'Decomissioned as coordinator for class '.$_POST['className_u'], 'You have been removed as the class coordinator for the class '.$_POST['className'], $section->getCurrentDate(), '', 100, $classId);
				
				$designationId = $designation->setUserRank($_POST['classCoordinator_uval'], 'LRESER3', $section->getCurrentDate(), '', $classId);
				$notificationId = $notification->setNewNotification($_POST['classCoordinator_uval'], 'Assigned as coordinator for class '.$_POST['className_u'], 'You have been assiged as the class coordinator for this session for the class '.$_POST['className_u'], $section->getCurrentDate(), '', 100, $classId);				
			}
			
			$outputArray [0] = $classId;
			$outputArray [] = $_POST['className_u'];
			$outputArray [] = $_POST['level_u'];
			$outputArray [] = 1;
		}else{
			$outputArray [] = 0;
		}		
		echo json_encode ( $outputArray );
	
	} elseif ($_POST ['task'] == 'checkSessionEditable') {
        $id = htmlentities ( trim ( $_POST ['classID'] ) );
        $details = $section->getTableIdDetails($id);
        if($session->isSessionEditable($details['session_id'])){
            $outputArray[0] = 1;
        }else{
            $outputArray[0] = 0;
        }
        echo json_encode ( $outputArray );
    } elseif ($_POST ['task'] == 'getClassSessionDetails') {
        $classId = htmlentities ( trim ( $_POST ['classId'] ) );
        $details = $session->getTableIdDetails($classId);
        $classDetails = $session->getTableIdDetails($details['class_id']);
        $sessionDetails = $session->getTableIdDetails($details['session_id']);

        $outputArray[0] = $sessionDetails['session_name'];
        $outputArray[] = $classDetails['class_name'];
        $outputArray[] = $details['session_id'];

        echo json_encode ( $outputArray );
    }elseif ($_POST ['task'] == 'getSectionSessionDetails') {
        $sectionId = htmlentities ( trim ( $_POST ['sectionId'] ) );
        $sectionDetails = $session->getTableIdDetails($sectionId);        
        $classId = $sectionDetails['class_id'];
        $details = $session->getTableIdDetails($classId);
        $classDetails = $session->getTableIdDetails($details['class_id']);
        $sessionDetails = $session->getTableIdDetails($details['session_id']);

        $outputArray[0] = $sessionDetails['session_name'];
        $outputArray[] = $classDetails['class_name'];
        $outputArray[] = $details['session_id'];
        $outputArray[] = $sectionDetails['section_name'];

        echo json_encode ( $outputArray );
    }elseif ($_POST ['task'] == 'dropRecord') {
        $id = htmlentities ( trim ( $_POST ['id'] ) );
        $options->dropOptionID($id);
        $outputArray [0] = $id;
        echo json_encode ( $outputArray );
    } elseif ($_POST ['task'] == 'activateRecord') {
		$id = htmlentities ( trim ( $_POST ['id'] ) );
		$options->activateOptionID($id);
		$outputArray [0] = $id;
		echo json_encode ( $outputArray );
	} else {
		$outputArray [0] = 0;
		echo json_encode ( $outputArray );
	}
}
?>