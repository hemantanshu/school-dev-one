<?php
require_once 'config.php';

require_once BASE_PATH . 'include/global/class.menuTask.php';
require_once BASE_PATH . 'include/exam/class.result.php';
require_once BASE_PATH . 'include/utility/class.subject.php';
require_once BASE_PATH . 'include/hrms/class.designation.php';
require_once BASE_PATH . 'include/global/class.notification.php';
require_once BASE_PATH . 'include/global/class.session.php';

$result = new Result ();
$subject = new subjects ();
$designation = new Designation ();
$menuTask = new MenuTask ();
$notification = new Notification ();
$session = new Session ();

$result->isRequestAuthorised4Form ( 'LMENUL82' );

if (isset ( $_POST ['task'] )) {
	if ($_POST ['task'] == 'insertRecord') {
		$resultId = $result->setAssessmentSubject ( $_POST ['assessmentId'], $_POST ['activityName_i'], $_POST ['activityOrder_i'], $_POST ['subjectName_i'], $_POST ['markSubmissionDate_i'], $_POST ['markSubmissionOfficer_ival'], $_POST ['markVerificationDate_i'], $_POST ['markVerificationOfficer_ival'] );
		$outputArray [0] = 0;
		if ($resultId) {
			$details = $result->getTableIdDetails ( $_POST ['assessmentId'] );
			$subjectDetails = $result->getTableIdDetails ( $_POST ['subjectName_i'] );
			$sectionDetails = $result->getTableIdDetails ( $details ['section_id'] );
			$classDetails = $result->getTableIdDetails ( $details ['class_id'] );
			$classDetails = $result->getTableIdDetails ( $classDetails ['class_id'] );
			
			$url = "pages/exam/exam_agrade_submission.php";
			$subjectCode = $subjectDetails ['subject_code'];
			$subjectName = $subjectDetails ['subject_name'];
			
			$comments = "Grade Submission Role For: " . $classDetails ['class_name'] . " " . $sectionDetails ['section_name'] . " " . "$subjectCode  $subjectName ";
			$urlName = "Submission : " . $classDetails ['class_name'] . " " . $sectionDetails ['section_name'] . " " . "$subjectCode  $subjectName ";
			$taskId = $menuTask->setMenuTaskAssignment ( $_POST ['markSubmissionOfficer_ival'], $urlName, $url, $resultId . '-S', $comments, $result->getCurrentDate (), $_POST ['markSubmissionDate_i'] );
			$attribute = array ();
			$attribute [0] = $resultId;
			$menuTask->setMenuTaskAttributes ( $taskId, 1, $attribute );
			$notification->setNewNotification ( $_POST ['markSubmissionOfficer_ival'], "Mark Submission Role Assigned", $comments, $result->getCurrentDate (), $_POST ['markSubmissionDate_i'], 1, $resultId . '-S' );
			
			// setting up the notification for the mark verification officer
			$url = "pages/exam/exam_agrade_verification.php";
			$comments = "Mark Verification Role For: " . $classDetails ['class_name'] . " " . $sectionDetails ['section_name'] . " " . "$subjectCode  $subjectName ";
			$urlName = "Verification : " . $classDetails ['class_name'] . " " . $sectionDetails ['section_name'] . " " . "$subjectCode  $subjectName ";
			
			$taskId = $menuTask->setMenuTaskAssignment ( $_POST ['markVerificationOfficer_ival'], $urlName, $url, $resultId . '-V', $comments, $_POST ['markSubmissionDate_i'], $_POST ['markVerificationDate_i'] );
			$attribute = array ();
			$attribute [0] = $resultId;
			$menuTask->setMenuTaskAttributes ( $taskId, 1, $attribute );
			$notification->setNewNotification ( $_POST ['markVerificationOfficer_ival'], "Mark Verification Role Assigned", $comments, $_POST ['markSubmissionDate_i'], $_POST ['markVerificationDate_i'], 1, $resultId . '-V' );
			
			$outputArray [0] = $resultId;
			$outputArray [] = $subjectDetails ['subject_code'];
			$outputArray [] = $subjectDetails ['subject_name'];
		}
		echo json_encode ( $outputArray );
	
	} else if ($_POST ['task'] == 'search') {
		$search_type = $_POST ['search_type'];
		$assessmentId = $_POST ['assessmentId'];
		
		$resultDateIds = $result->getActivityIds ( $assessmentId, $search_type );
		$outputArray [0] [0] = 1;
		$i = 0;
		foreach ( $resultDateIds as $resultId ) {
			$details = $result->getTableIdDetails ( $resultId );
			$subjectDetails = $result->getTableIdDetails ( $details ['subject_id'] );
			
			$outputArray [$i] [0] = $resultId;
			$outputArray [$i] [] = $subjectDetails ['subject_code'];
			$outputArray [$i] [] = $subjectDetails ['subject_name'];
			$outputArray [$i] [] = 1;
			++ $i;
		}
		echo json_encode ( $outputArray );
	
	} else if ($_POST ['task'] == 'getRecordIdDetails') {
		$resultId = $_POST ['id'];
		$details = $result->getTableIdDetails ( $resultId );
		$editable = 1;
		if (! $session->isSessionEditable ( $details ['session_id'] ))
			$editable = 0;
		
		$subjectDetails = $result->getTableIdDetails ( $details ['subject_id'] );
		$subjectComponentDetails = $result->getTableIdDetails ( $details ['subject_component_id'] );
		if ($details ['marking_type'] == '')
			$markingType = "Absolute Marking";
		else {
			$markingDetails = $result->getTableIdDetails ( $details ['marking_type'] );
			$markingType = $markingDetails ['grading_name'];
		}
		
		$outputArray [0] = $resultId;
		$outputArray [] = $details ['activity_name'];
		$outputArray [] = $details ['activity_order'];
		$outputArray [] = $subjectDetails ['subject_code'] . " " . $subjectDetails ['subject_name'];
		$outputArray [] = $result->getDisplayDate ( $details ['mark_submission_date'] );
		$outputArray [] = $result->getOfficerName ( $details ['mark_submission_officer'] ); // 10
		$outputArray [] = $result->getDisplayDate ( $details ['mark_verification_date'] );
		$outputArray [] = $result->getOfficerName ( $details ['mark_verification_officer'] );
		
		$outputArray [] = $details ['last_update_date'];
		$outputArray [] = $result->getOfficerName ( $details ['last_updated_by'] );
		$outputArray [] = $details ['creation_date']; // 10
		$outputArray [] = $result->getOfficerName ( $details ['created_by'] );
		$outputArray [] = $details ['active'];
		$outputArray [] = $editable;
		
		$outputArray [] = $details ['subject_id'];
		$outputArray [] = $details ['mark_submission_date']; // 15
		$outputArray [] = $details ['mark_submission_officer'];
		$outputArray [] = $details ['mark_verification_date'];
		$outputArray [] = $details ['mark_verification_officer'];
		
		$outputArray [] = $details ['max_mark'];
		$outputArray [] = $details ['pass_mark'];
		
		echo json_encode ( $outputArray );
	} elseif ($_POST ['task'] == 'updateRecord') {
		$resultId = $_POST ['valueId_u'];
		$details = $result->getTableIdDetails ( $resultId );
		if ($session->isSessionEditable ( $details ['session_id'] )) {
			if($details['activity_name'] != $_POST['activityName_u']){
				$result->setUpdateLog('Name From '.$details['activity_name'].' to '.$_POST['activityName_u']);
				$result->updateTableParameter ( 'activity_name', $_POST ['activityName_u'] );
			}
			if($details['activity_order'] != $_POST['activityOrder_u']){
				$result->setUpdateLog('Order from '.$details['activity_order'].' to '.$_POST['activityOrder_u']);
				$result->updateTableParameter ( 'activity_order', $_POST ['activityOrder_u'] );
			}
			if($details['mark_submission_date'] != $_POST['markSubmissionDate_u']){
				$result->setUpdateLog('Submission Date from '.$details['mark_submission_date'].' to '.$_POST['markSubmissionDate_u']);
				$result->updateTableParameter ( 'mark_submission_date', $_POST ['markSubmissionDate_u'] );
			}
			if($details['mark_verification_date'] != $_POST['markVerificationDate_u']){
				$result->setUpdateLog('Verification Date from '.$details['mark_verification_date'].' to '.$_POST['markVerificationDate_u']);
				$result->updateTableParameter ( 'mark_verification_date', $_POST ['markVerificationDate_u'] );
			}
			if($details['mark_submission_officer'] != $_POST['markSubmissionOfficer_uval']){
				$result->setUpdateLog('Submission Officer from '.$details['mark_submission_officer'].' to '.$_POST['markSubmissionOfficer_uval']);
				$result->updateTableParameter ( 'mark_submission_officer', $_POST ['markSubmissionOfficer_uval'] );
			}
			if($details['mark_verification_officer'] != $_POST['markVerificationOfficer_uval']){
				$result->setUpdateLog('Verification Officer from '.$details['mark_verification_officer'].' to '.$_POST['markVerificationOfficer_uval']);
				$result->updateTableParameter ( 'mark_verification_officer', $_POST ['markVerificationOfficer_uval'] );
			}
			$result->commitAssessmentSubjectUpdate ( $resultId );
			
			if (($details ['mark_submission_date'] != $_POST ['markSubmissionDate_u']) || ($details ['mark_submission_officer'] != $_POST ['markSubmissionOfficer_uval'])) {
				$menuTaskId = $menuTask->getMenuTaskId4SourceId ( $resultId . '-S' );
				// updating the menu task
				if($details['end_date'] != $_POST['markSubmissionDate_u']){
					$menuTask->setUpdateLog('End Date from '.$details['end_date'].' to '.$_POST['markSubmissionDate_u']);
					$menuTask->updateTableParameter ( 'end_date', $_POST ['markSubmissionDate_u'] );
				}
				if($details['user_id'] != $_POST['markSubmissionOfficer_uval']){
					$menuTask->setUpdateLog('Officer from '.$details['user_id'].' to '.$_POST['markSubmissionOfficer_uval']);
					$menuTask->updateTableParameter ( 'user_id', $_POST ['markSubmissionOfficer_uval'] );
				}								
				$menuTask->commitMenuTaskAssignmentUpdate ( $menuTaskId );
				
				$notificationId = $notification->getNotificationId4Source ( $resultId . '-S' );
				if($details['end_date'] != $_POST['markSubmissionDate_u']){
					$notification->setUpdateLog('End Date from '.$details['end_date'].' to '.$_POST['markSubmissionDate_u']);
					$notification->updateTableParameter ( 'end_date', $_POST ['markSubmissionDate_u'] );
				}
				if($details['user_id'] != $_POST['markSubmissionOfficer_uval']){
					$notification->setUpdateLog('Officer from '.$details['user_id'].' to '.$_POST['markSubmissionOfficer_uval']);
					$notification->updateTableParameter ( 'user_id', $_POST ['markSubmissionOfficer_uval'] );
				}								
				$notification->commitNotificationUpdate ( $notificationId );
			}
			
			if ($details ['mark_verification_date'] != $_POST ['markVerificationDate_u'] || $details ['mark_verification_officer'] != $_POST ['markVerificationOfficer_uval'] || $details ['mark_submission_date'] != $_POST ['markSubmissionDate_u'] || $details ['marking_type'] != $_POST ['markingType_u']) {
				
				$menuTaskId = $menuTask->getMenuTaskId4SourceId ( $resultId . '-V' );
				// updating the menu task
				if($details['end_date'] != $_POST['markVerificationDate_u']){
					$menuTask->setUpdateLog('End Date from '.$details['end_date'].' to '.$_POST['markVerificationDate_u']);
					$menuTask->updateTableParameter ( 'end_date', $_POST ['markVerificationDate_u'] );
				}
				if($details['user_id'] != $_POST['markVerificationOfficer_uval']){
					$menuTask->setUpdateLog('Officer from '.$details['user_id'].' to '.$_POST['markVerificationOfficer_uval']);
					$menuTask->updateTableParameter ( 'user_id', $_POST ['markVerificationOfficer_uval'] );
				}				
				$menuTask->commitMenuTaskAssignmentUpdate ( $menuTaskId );
				
				$notificationId = $notification->getNotificationId4Source ( $resultId . '-V' );
				if($details['end_date'] != $_POST['markVerificationDate_u']){
					$notification->setUpdateLog('End Date from '.$details['end_date'].' to '.$_POST['markVerificationDate_u']);
					$notification->updateTableParameter ( 'end_date', $_POST ['markVerificationDate_u'] );
				}
				if($details['user_id'] != $_POST['markVerificationOfficer_uval']){
					$notification->setUpdateLog('Officer from '.$details['user_id'].' to '.$_POST['markVerificationOfficer_uval']);
					$notification->updateTableParameter ( 'user_id', $_POST ['markVerificationOfficer_uval'] );
				}				
				$notification->commitNotificationUpdate ( $notificationId );
			}
		}
		$subjectDetails = $result->getTableIdDetails ( $details ['subject_id'] );
		$subjectComponentDetails = $result->getTableIdDetails ( $details ['subject_component_id'] );
		$outputArray [0] = $resultId;
		$outputArray [] = $subjectDetails ['subject_code'];
		$outputArray [] = $subjectDetails ['subject_name'];
		$outputArray [] = 1;
		
		echo json_encode ( $outputArray );
	
	} else if ($_POST ['task'] == 'getAssessmentDetails') {
		$assessmentId = $_POST ['assessmentId'];
		$details = $result->getTableIdDetails ( $assessmentId );
		
		$classDetails = $result->getTableIdDetails ( $details ['section_id'] );
		$classNameDetails = $result->getTableIdDetails ( $classDetails ['class_id'] );
		$classNameDetails = $result->getTableIdDetails ( $classNameDetails ['class_id'] );
		$sessionDetails = $result->getTableIdDetails ( $details ['session_id'] );
		$resultDetails = $result->getTableIdDetails ( $details ['result_id'] );
		$gradeDetails = $result->getTableIdDetails ( $details ['marking_scheme'] );
		
		$outputArray [0] = $sessionDetails ['session_name'];
		$outputArray [] = $resultDetails ['result_name'];
		$outputArray [] = $details ['assessment_name'];
		$outputArray [] = $gradeDetails ['grading_name'];
		$outputArray [] = $classNameDetails ['class_name'];
		$outputArray [] = $classDetails ['section_name'];
		
		$outputArray [] = $details ['session_id'];
		$outputArray [] = $details ['result_id'];
		$outputArray [] = $details ['class_id'];
		$outputArray [] = $details ['section_id'];
		$outputArray [] = $details ['marking_scheme'];
		
		echo json_encode ( $outputArray );
	
	} elseif ($_POST ['task'] == 'getClassAssignedSubjects') {
		$classId = $_POST ['classId'];
		$subjectDetailsArray = $result->getClassSubjectIdDetails ( $classId );
		$i = 0;
		foreach ( $subjectDetailsArray as $subjectDetails ) {
			$outputArray [$i] [0] = $subjectDetails [0];
			$outputArray [$i] [] = $subjectDetails [1];
			$outputArray [$i] [] = $subjectDetails [2];
			++ $i;
		}
		if ($i == 0)
			$outputArray [0] [0] = 0;
		echo json_encode ( $outputArray );
	} elseif ($_POST ['task'] == 'getSubjectComponents') {
		$subjectId = $_POST ['subjectId'];
		$subjectComponentIds = $result->getSubjectCombinationIds ( $subjectId, 1 );
		$i = 0;
		$outputArray [0] [0] = 1;
		foreach ( $subjectComponentIds as $componentId ) {
			$details = $result->getTableIdDetails ( $componentId );
			$outputArray [$i] [0] = $details ['id'];
			$outputArray [$i] [] = $details ['subject_component_name'];
			++ $i;
		}
		echo json_encode ( $outputArray );
	} elseif ($_POST ['task'] == 'dropRecord') {
		$id = $_POST ['id'];
		$taskId = $menuTask->getMenuTaskId4SourceId ( $id . "-S" );
		$menuTask->dropMenuTaskAssignment ( $taskId );
		
		$taskId = $menuTask->getMenuTaskId4SourceId ( $id . "-V" );
		$menuTask->dropMenuTaskAssignment ( $taskId );
		
		$notificationId = $notification->getNotificationId4Source ( $id . "-S" );
		$notification->dropNotification ( $notificationId );
		
		$notificationId = $notification->getNotificationId4Source ( $id . "-V" );
		$notification->dropNotification ( $notificationId );
		
		$result->dropAssessmentActivity($id);
		
		$outputArray [0] = 1;
		echo json_encode ( $outputArray );
	
	} elseif ($_POST ['task'] == 'activateRecord') {
		$id = $_POST ['id'];
		
		$taskId = $menuTask->getMenuTaskId4SourceId ( $id . "-S" );
		$menuTask->activateMenuTaskAssignment ( $taskId );
		
		$taskId = $menuTask->getMenuTaskId4SourceId ( $id . "-V" );
		$menuTask->activateMenuTaskAssignment ( $taskId );
		
		$notificationId = $notification->getNotificationId4Source ( $id . "-S" );
		$notification->activateNotification ( $notificationId );
		
		$notificationId = $notification->getNotificationId4Source ( $id . "-V" );
		$notification->activateNotification ( $notificationId );
		
		$result->activateAssessmentActivity($id);
		
		$outputArray [0] = 1;
		echo json_encode ( $outputArray );
	} else {
		$outputArray [0] = 0;
		echo json_encode ( $outputArray );
	}
}
?>