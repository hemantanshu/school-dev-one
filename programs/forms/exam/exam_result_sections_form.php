<?php
require_once 'config.php';
require_once BASE_PATH . 'include/exam/class.resultSections.php';
require_once BASE_PATH . 'include/global/class.session.php';
require_once BASE_PATH . 'include/global/class.notification.php';
require_once BASE_PATH . 'include/global/class.menuTask.php';


$result = new ResultSections();
$session = new Session();
$menuTask = new MenuTask();
$notification = new Notification();

$result->isRequestAuthorised4Form ( 'LMENUL86' );

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
				$result->setUpdateLog('Remarks Officer from '.$details['remarks_officer'].' to '.$_POST['remarksOfficer_val']);
				$result->updateTableParameter('remarks_officer',$_POST['remarksOfficer_val']);
			}
			if($details['total_mark'] != $_POST['totalMarks']){
				$result->setUpdateLog('Marks from '.$details['total_mark'].' to '.$_POST['totalMarks']);
				$result->updateTableParameter('total_mark',$_POST['totalMarks']);
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
					$notification->setUpdateLog('User from '.$details['user_id'].' to '.$_POST['attendanceOfficer_val']);
					$notification->updateTableParameter('user_id',$_POST['attendanceOfficer_val']);
				}			
				$notification->commitNotificationUpdate($notification->getNotificationId4Source($attendanceId));				
			}
			
			if($details['remarks_date'] != $_POST['remarksDate'] || $details['remarks_officer'] != $_POST['remarksOfficer_val'] ){
				$remarksId = $resultSectionId."-V";
				if($details['end_date'] != $_POST['remarksDate']){
					$menuTask->setUpdateLog('from '.$details['end_date'].' to '.$_POST['remarksDate']);
					$menuTask->updateTableParameter('end_date',$_POST['remarksDate']);
				}
				if($details['user_id'] != $_POST['remarksOfficer_val']){
					$menuTask->setUpdateLog('from '.$details['user_id'].' to '.$_POST['remarksOfficer_val']);
					$menuTask->updateTableParameter('user_id',$_POST['remarksOfficer_val']);
				}				
				$menuTask->commitMenuTaskAssignmentUpdate($menuTask->getMenuTaskId4SourceId($remarksId));
				
				if($details['end_date'] != $_POST['remarksDate']){
					$notification->setUpdateLog('End Date from '.$details['end_date'].' to '.$_POST['remarksDate']);
					$notification->updateTableParameter('end_date',$_POST['remarksDate']);
				}
				if($details['user_id'] != $_POST['remarksOfficer_val']){
					$notification->setUpdateLog('User from '.$details['user_id'].' to '.$_POST['remarksOfficer_val']);
					$notification->updateTableParameter('user_id',$_POST['remarksOfficer_val']);
				}				
				$notification->commitNotificationUpdate($notification->getNotificationId4Source($remarksId));			
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
		$outputArray [] = $details ['active'];
			
		echo json_encode ( $outputArray );
	} else {
		$outputArray [0] = 0;
		echo json_encode ( $outputArray );
	}
}
?>