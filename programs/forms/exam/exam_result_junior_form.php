<?php
require_once 'config.php';
require_once BASE_PATH . 'include/exam/class.resultSections.php';
require_once BASE_PATH . 'include/exam/class.resultJunior.php';
require_once BASE_PATH . 'include/global/class.session.php';
require_once BASE_PATH . 'include/global/class.notification.php';
require_once BASE_PATH . 'include/global/class.menuTask.php';


$result = new ResultSections();
$session = new Session();
$menuTask = new MenuTask();
$notification = new Notification();
$junior = new ResultJunior();

$result->isRequestAuthorised4Form ( 'LMENUL113' );

if (isset ( $_POST ['task'] )) {
	if ($_POST ['task'] == 'insertRecord') {
		$resultId = $_POST ['resultId'];
		$sectionId = $_POST['sectionId'];
		
		$resultSectionId = $result->getResultSectionId($resultId, $sectionId);
		if($resultSectionId){
			$details = $result->getTableIdDetails($resultSectionId);
			if($details['total_attendance'] != $_POST['totalAttendance']){
				$result->setUpdateLog('Total Attendance from '.$details['total_attendance'].' to '.$_POST['totalAttendance']);				
				$result->updateTableParameter('total_attendance',$_POST['totalAttendance']);
			}
			if($details['attendance_date'] != $_POST['attendanceDate']){
				$result->setUpdateLog('Attendance Date from '.$details['attendance_date'].' to '.$_POST['attendanceDate']);
				$result->updateTableParameter('attendance_date',$_POST['attendanceDate']);
			}
			if($details['attendance_officer'] != $_POST['attendanceOfficer_val']){
				$result->setUpdateLog('Attendance Officer from '.$details['attendance_officer'].' to '.$_POST['attendanceOfficer_val']);
				$result->updateTableParameter('attendance_officer',$_POST['attendanceOfficer_val']);
			}
			if($details['remarks_date'] != $_POST['remarksDate']){
				$result->setUpdateLog('Remarks Date from '.$details['remarks_date'].' to '.$_POST['remarksDate']);
				$result->updateTableParameter('remarks_date',$_POST['remarksDate']);
			}
			if($details['remarks_officer'] != $_POST['remarksOfficer_val']){
				$result->setUpdateLog('Remarks Date from '.$details['remarks_officer'].' to '.$_POST['remarksOfficer_val']);
				$result->updateTableParameter('remarks_officer',$_POST['remarksOfficer_val']);
			}						
			$result->commitSectionDetailsUpdate($resultSectionId);			
			
			if($details['attendance_date'] != $_POST['attendanceDate'] || $details['attendance_officer'] != $_POST['attendanceOfficer_val'] ){
				$attendanceId = $resultSectionId."-A";
				if($details['end_date'] != $_POST['attendanceDate']){
					$menuTask->setUpdateLog('End Date from '.$details['end_date'].' to '.$_POST['attendanceDate']);
					$menuTask->updateTableParameter('end_date',$_POST['attendanceDate']);					
				}
				if($details['user_id'] != $_POST['attendanceOfficer_val']){
					$menuTask->setUpdateLog('Officer from '.$details['user_id'].' to '.$_POST['attendanceOfficer_val']);
					$menuTask->updateTableParameter('user_id',$_POST['attendanceOfficer_val']);
				}				
				$menuTask->commitMenuTaskAssignmentUpdate($menuTask->getMenuTaskId4SourceId($attendanceId));
				
				if($details['end_date'] != $_POST['attendanceDate']){
					$notification->setUpdateLog('End Date from '.$details['end_date'].' to '.$_POST['attendanceDate']);
					$notification->updateTableParameter('end_date',$_POST['attendanceDate']);
				}
				if($details['user_id'] != $_POST['attendanceOfficer_val']){
					$notification->setUpdateLog('Officer from '.$details['user_id'].' to '.$_POST['attendanceOfficer_val']);
					$notification->updateTableParameter('user_id',$_POST['attendanceOfficer_val']);
				}			
				$notification->commitNotificationUpdate($notification->getNotificationId4Source($attendanceId));				
			}
			
			if($details['remarks_date'] != $_POST['remarksDate'] || $details['remarks_officer'] != $_POST['remarksOfficer_val'] ){
				$remarksId = $resultSectionId."-V";
				if($details['end_date'] != $_POST['remarksDate']){
					$menuTask->setUpdateLog('End Date from '.$details['end_date'].' to '.$_POST['remarksDate']);
					$menuTask->updateTableParameter('end_date',$_POST['remarksDate']);
				}
				if($details['user_id'] != $_POST['remarksOfficer_val']){
					$menuTask->setUpdateLog('Officer from '.$details['user_id'].' to '.$_POST['remarksOfficer_val']);
					$menuTask->updateTableParameter('user_id',$_POST['remarksOfficer_val']);					
				}				
				$menuTask->commitMenuTaskAssignmentUpdate($menuTask->getMenuTaskId4SourceId($remarksId));
				
				if($details['end_date'] != $_POST['remarksDate']){
					$notification->setUpdateLog('End Date from '.$details['end_date'].' to '.$_POST['remarksDate']);
					$notification->updateTableParameter('end_date',$_POST['remarksDate']);
				}
				if($details['user_id'] != $_POST['remarksOfficer_val']){
					$notification->setUpdateLog('Officer from '.$details['user_id'].' to '.$_POST['remarksOfficer_val']);
					$notification->updateTableParameter('user_id',$_POST['remarksOfficer_val']);
				}			
				$notification->commitNotificationUpdate($notification->getNotificationId4Source($remarksId));			
			}
			
			$resultJuniorId = $junior->getResultJuniorId($resultId, $sectionId);
			$details = $junior->getTableIdDetails($resultJuniorId);
			if($details['weight_date'] != $_POST['weightDate']){
				$junior->setUpdateLog('Weight Date from '.$details['weight_date'].' to '.$_POST['weightDate']);
				$junior->updateTableParameter('weight_date',$_POST['weightDate']);
			}
			if($details['weight_officer'] != $_POST['weightOfficer_val']){
				$junior->setUpdateLog('Weight Officer from '.$details['weight_officer'].' to '.$_POST['weightOfficer_val']);
				$junior->updateTableParameter('weight_officer',$_POST['weightOfficer_val']);
			}
			if($details['height_date'] != $_POST['heightDate']){
				$junior->setUpdateLog('Height Date from '.$details['height_date'].' to '.$_POST['heightDate']);					
				$junior->updateTableParameter('height_date',$_POST['heightDate']);
			}
			if($details['height_officer'] != $_POST['heightOfficer_val']){
				$junior->setUpdateLog('Height Officer from '.$details['height_officer'].' to '.$_POST['heightOfficer_val']);
				$junior->updateTableParameter('height_officer',$_POST['heightOfficer_val']);
			}
			if($details['achievement_date'] != $_POST['achievementDate']){
				$junior->setUpdateLog('Achievement Date from '.$details['achievement_date'].' to '.$_POST['achievementDate']);				
				$junior->updateTableParameter('achievement_date',$_POST['achievementDate']);
			}
			if($details['achievement_officer'] != $_POST['achievementOfficer_val']){
				$junior->setUpdateLog('Achivement Officer from '.$details['achievement_officer'].' to '.$_POST['achievementOfficer_val']);
				$junior->updateTableParameter('achievement_officer',$_POST['achievementOfficer_val']);
			}		
			$junior->commitSectionDetailsUpdate($resultJuniorId);	
			
			
			if($details['weight_date'] != $_POST['weightDate'] || $details['weight_officer'] != $_POST['weightOfficer_val'] ){
				$weightId = $resultJuniorId."-W";
				if($details['end_date'] != $_POST['weightDate']){
					$menuTask->setUpdateLog('End Date from '.$details['end_date'].' to '.$_POST['weightDate']);
					$menuTask->updateTableParameter('end_date',$_POST['weightDate']);
				}
				if($details['user_id'] != $_POST['weightOfficer_val']){
					$menuTask->setUpdateLog('Officer from '.$details['user_id'].' to '.$_POST['weightOfficer_val']);
					$menuTask->updateTableParameter('user_id',$_POST['weightOfficer_val']);
				}			
				$menuTask->commitMenuTaskAssignmentUpdate($menuTask->getMenuTaskId4SourceId($weightId));
			
				if($details['end_date'] != $_POST['weightDate']){
					$notification->setUpdateLog('from '.$details['end_date'].' to '.$_POST['weightDate']);
					$notification->updateTableParameter('end_date',$_POST['weightDate']);
				}
				if($details['user_id'] != $_POST['weightOfficer_val']){
					$notification->setUpdateLog('from '.$details['user_id'].' to '.$_POST['weightOfficer_val']);
					$notification->updateTableParameter('user_id',$_POST['weightOfficer_val']);
				}			
				$notification->commitNotificationUpdate($notification->getNotificationId4Source($weightId));
			}
				
			if($details['height_date'] != $_POST['heightDate'] || $details['height_officer'] != $_POST['heightOfficer_val'] ){
				$heightId = $resultJuniorId."-H";
				if($details['end_date'] != $_POST['heightDate']){
					$menuTask->setUpdateLog('End Date from '.$details['end_date'].' to '.$_POST['heightDate']);
					$menuTask->updateTableParameter('end_date',$_POST['heightDate']);
				}
				if($details['user_id'] != $_POST['heightOfficer_val']){
					$menuTask->setUpdateLog('Officer from '.$details['user_id'].' to '.$_POST['heightOfficer_val']);
					$menuTask->updateTableParameter('user_id',$_POST['heightOfficer_val']);
				}			
				$menuTask->commitMenuTaskAssignmentUpdate($menuTask->getMenuTaskId4SourceId($heightId));
			
				if($details['end_date'] != $_POST['heightDate']){
					$notification->setUpdateLog('End Date from '.$details['end_date'].' to '.$_POST['heightDate']);
					$notification->updateTableParameter('end_date',$_POST['heightDate']);
				}
				if($details['user_id'] != $_POST['heightOfficer_val']){
					$notification->setUpdateLog('Officer from '.$details['user_id'].' to '.$_POST['heightOfficer_val']);
					$notification->updateTableParameter('user_id',$_POST['heightOfficer_val']);
				}
				$notification->commitNotificationUpdate($notification->getNotificationId4Source($heightId));
			}
			
			if($details['achievement_date'] != $_POST['achievementDate'] || $details['achievement_officer'] != $_POST['achievementOfficer_val'] ){
				$achievementId = $resultJuniorId."-A";
				if($details['end_date'] != $_POST['achievementDate']){
					$menuTask->setUpdateLog('End Date from '.$details['end_date'].' to '.$_POST['achievementDate']);
					$menuTask->updateTableParameter('end_date',$_POST['achievementDate']);
				}
				if($details['user_id'] != $_POST['achievementOfficer_val']){
					$menuTask->setUpdateLog('Officer from '.$details['user_id'].' to '.$_POST['achievementOfficer_val']);
					$menuTask->updateTableParameter('user_id',$_POST['achievementOfficer_val']);
				}				
				$menuTask->commitMenuTaskAssignmentUpdate($menuTask->getMenuTaskId4SourceId($achievementId));
					
				if($details['end_date'] != $_POST['achievementDate']){
					$notification->setUpdateLog('End Date from '.$details['end_date'].' to '.$_POST['achievementDate']);
					$notification->updateTableParameter('end_date',$_POST['achievementDate']);
				}
				if($details['user_id'] != $_POST['achievementOfficer_val']){
					$notification->setUpdateLog('Officer from '.$details['user_id'].' to '.$_POST['achievementOfficer_val']);
					$notification->updateTableParameter('user_id',$_POST['achievementOfficer_val']);
				}				
				$notification->commitNotificationUpdate($notification->getNotificationId4Source($achievementId));
			}
				
			$outputArray[0] = $resultSectionId;			
				
		}else{
			$sectionId = $result->setResultSectionDetails($_POST['resultId'], $_POST['sectionId'], $_POST['totalAttendance'], $_POST['totalMarks'], $_POST['attendanceDate'], $_POST['attendanceOfficer_val'], $_POST['remarksDate'], $_POST['remarksOfficer_val']);
			
			$url = "pages/exam/exam_attendance_entry.php";
			$sectionDetails = $result->getTableIdDetails($_POST['sectionId']);
			$classDetails = $result->getTableIdDetails($sectionDetails['class_id']);
			$classDetails = $result->getTableIdDetails($classDetails['class_id']);
				
			$comments = "Attendance Submission Role For: " . $classDetails ['class_name'] . " " . $sectionDetails ['section_name'] ;
			$urlName = "Attendance Submission : " . $classDetails ['class_name'] . " " . $sectionDetails ['section_name'];
			$taskId = $menuTask->setMenuTaskAssignment ( $_POST ['attendanceOfficer_val'], $urlName, $url, $sectionId . '-A', $comments, $result->getCurrentDate (), $_POST ['attendanceDate'] );
			$attribute = array ();
			$attribute [0] = $sectionId;
			$menuTask->setMenuTaskAttributes ( $taskId, 1, $attribute );
			$notification->setNewNotification ( $_POST ['attendanceOfficer_val'], "Attendance Submission Role Assigned", $comments, $result->getCurrentDate (), $_POST ['attendanceDate'], 1, $sectionId . '-A' );
				
			// setting up the notification for the mark verification officer
			$url = "pages/exam/exam_remarks_entry.php";
			$comments = "Remarks Entry Role For: " . $classDetails ['class_name'] . " " . $sectionDetails ['section_name'] ;
			$urlName = "Remarks Submission : " . $classDetails ['class_name'] . " " . $sectionDetails ['section_name'] ;
				
			$taskId = $menuTask->setMenuTaskAssignment ( $_POST ['remarksOfficer_val'], $urlName, $url, $sectionId . '-V', $comments, $result->getCurrentDate(), $_POST ['remarksDate']);
			$attribute = array ();
			$attribute [0] = $sectionId;
			$menuTask->setMenuTaskAttributes ( $taskId, 1, $attribute );
			$notification->setNewNotification ( $_POST ['remarksOfficer_val'], "Remarks Submission Role Assigned", $comments, $result->getCurrentDate(), $_POST ['remarksDate'], 1, $sectionId . '-R' );
			
			
			/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
			$juniorId = $junior->setResultJuniorDetails($_POST['resultId'], $_POST['sectionId'], $_POST['weightDate'], $_POST['heightDate'], $_POST['achievementDate'], $_POST['weightOfficer_val'], $_POST['heightOfficer_val'], $_POST['achievementOfficer_val']);			
			
			// setting up the notification for the weight entry officer
			$url = "pages/exam/exam_weight_entry.php";
			$comments = "Weight Entry Role For: " . $classDetails ['class_name'] . " " . $sectionDetails ['section_name'] ;
			$urlName = "Weight Submission : " . $classDetails ['class_name'] . " " . $sectionDetails ['section_name'] ;
			
			$taskId = $menuTask->setMenuTaskAssignment ( $_POST ['weightOfficer_val'], $urlName, $url, $juniorId . '-W', $comments, $result->getCurrentDate(), $_POST ['weightDate']);
			$attribute = array ();
			$attribute [0] = $juniorId;
			$menuTask->setMenuTaskAttributes ( $taskId, 1, $attribute );
			$notification->setNewNotification ( $_POST ['weightOfficer_val'], "Weight Submission Role Assigned", $comments, $result->getCurrentDate(), $_POST ['weightDate'], 1, $juniorId . '-W' );
			
			// setting up the notification for the height entry officer
			$url = "pages/exam/exam_height_entry.php";
			$comments = "Height Entry Role For: " . $classDetails ['class_name'] . " " . $sectionDetails ['section_name'] ;
			$urlName = "Height Submission : " . $classDetails ['class_name'] . " " . $sectionDetails ['section_name'] ;
				
			$taskId = $menuTask->setMenuTaskAssignment ( $_POST ['heightOfficer_val'], $urlName, $url, $juniorId . '-H', $comments, $result->getCurrentDate(), $_POST ['heightDate']);
			$attribute = array ();
			$attribute [0] = $juniorId;
			$menuTask->setMenuTaskAttributes ( $taskId, 1, $attribute );
			$notification->setNewNotification ( $_POST ['heightOfficer_val'], "Height Submission Role Assigned", $comments, $result->getCurrentDate(), $_POST ['heightDate'], 1, $juniorId . '-H' );
			
			// setting up the notification for the achievement entry officer
			$url = "pages/exam/exam_achievement_entry.php";
			$comments = "Achievement Entry Role For: " . $classDetails ['class_name'] . " " . $sectionDetails ['section_name'] ;
			$urlName = "Achievement Submission : " . $classDetails ['class_name'] . " " . $sectionDetails ['section_name'] ;
			
			$taskId = $menuTask->setMenuTaskAssignment ( $_POST ['achievementOfficer_val'], $urlName, $url, $juniorId . '-A', $comments, $result->getCurrentDate(), $_POST ['achievementDate']);
			$attribute = array ();
			$attribute [0] = $juniorId;
			$menuTask->setMenuTaskAttributes ( $taskId, 1, $attribute );
			$notification->setNewNotification ( $_POST ['achievementOfficer_val'], "Achievement Submission Role Assigned", $comments, $result->getCurrentDate(), $_POST ['achievementDate'], 1, $juniorId . '-A' );
						
			$outputArray[0] = $sectionId;
		}		
		echo json_encode($outputArray);
		
	} else if ($_POST ['task'] == 'getRecordIdDetails') {
		$resultId = $_POST ['resultId'];
		$sectionId = $_POST['sectionId'];
		
		$id = $result->getResultSectionId($resultId, $sectionId);						
		$details = $result->getTableIdDetails ( $id );
			
		$outputArray [0] = $details ['id'];
		$outputArray [] = $details['total_attendance'];
		$outputArray [] = $details['total_mark'];
		$outputArray [] = $details['attendance_date'];
		$outputArray [] = $result->getDisplayDate($details['attendance_date']);
		$outputArray [] = $details['attendance_officer'];		
		$outputArray [] = $result->getOfficerName ( $details ['attendance_officer'] );		
		$outputArray [] = $details['remarks_date']; //7
		$outputArray [] = $result->getDisplayDate($details['remarks_date']);
		$outputArray [] = $details['remarks_officer'];
		$outputArray [] = $result->getOfficerName ( $details ['remarks_officer'] );
		$outputArray [] = $details ['last_update_date'];
		$outputArray [] = $result->getOfficerName ( $details ['last_updated_by'] );
		$outputArray [] = $details ['creation_date'];
		$outputArray [] = $result->getOfficerName ( $details ['created_by'] ); //10
				
		$id = $junior->getResultJuniorId($resultId, $sectionId);
		$details = $junior->getTableIdDetails($id);
		
		$outputArray [] = $details['weight_date'];
		$outputArray [] = $result->getDisplayDate($details['weight_date']);
		$outputArray [] = $details['weight_officer'];
		$outputArray [] = $result->getOfficerName ( $details ['weight_officer'] );
		
		$outputArray [] = $details['height_date'];
		$outputArray [] = $result->getDisplayDate($details['height_date']);
		$outputArray [] = $details['height_officer'];
		$outputArray [] = $result->getOfficerName ( $details ['height_officer'] );
		
		$outputArray [] = $details['achievement_date'];
		$outputArray [] = $result->getDisplayDate($details['achievement_date']);
		$outputArray [] = $details['achievement_officer'];
		$outputArray [] = $result->getOfficerName ( $details ['achievement_officer'] );
		
		$outputArray [] = $details ['active'];
		
		echo json_encode ( $outputArray );
	} else {
		$outputArray [0] = 0;
		echo json_encode ( $outputArray );
	}
}
?>