<?php
require_once 'config.php';
require_once BASE_PATH . 'include/exam/class.resultTypeEntry.php';
require_once BASE_PATH . 'include/global/class.menuTask.php';
require_once BASE_PATH . 'include/global/class.notification.php';

$result = new ResultTypeEntry ();
$menuTask = new MenuTask ();
$notification = new Notification ();

$result->isRequestAuthorised4Form ( 'LMENUL156' );

if (isset ( $_POST ['task'] )) {
	if ($_POST ['task'] == 'search') {
		$sectionId = $_POST ['sectionId'];
		$resultId = $_POST ['resultId'];
		
		$resultDetails = $result->getTableIdDetails ( $resultId );
		$fieldIds = $result->getResultTypeFields ( $resultDetails ['result_type'], '', 1 );
		
		$i = 0;
		$outputArray[0][0] = 1;
		foreach ( $fieldIds as $fieldId ) {
			$details = $result->getTableIdDetails ( $fieldId );
			if ($details ['submission_url'] == '') {				
				$dataId = $result->getResultTypeFieldDataId ( $resultId, $sectionId, $fieldId );
				if ($dataId) {
					$outputArray [$i] [0] = $dataId;
					$outputArray [$i] [] = $details ['display_name'];
					$outputArray [$i] [] = 0;
				} else {
					$dataId = $result->setResultTypeDataEntry ( $resultId, $sectionId, $fieldId, 0 );
					$outputArray [$i] [0] = $dataId;
					$outputArray [$i] [] = $details ['display_name'];
					$outputArray [$i] [] = 0;
				}				
			} else {
				$submissionId = $result->getResultTypeFieldSubmissionId ( $resultId, $sectionId, $fieldId );
				if ($submissionId) {
					$outputArray [$i] [0] = $submissionId;
					$outputArray [$i] [] = $details ['display_name'];
					$outputArray [$i] [] = 1;
				} else {
					$userId = $result->getLoggedUserId ();
					$submissionId = $result->setResultTypeSubmissionEntry ( $resultId, $sectionId, $fieldId, $result->getCurrentDate (), $userId );
					// creating a entry for menu task assignment
					$displayName = $details ['display_name'] . " Data Entry";
					$menuTaskId = $menuTask->setMenuTaskAssignment ( $userId, $displayName, $details ['submission_url'], $submissionId, $displayName, $result->getCurrentDate (), $result->getCurrentDate () );
					$notification->setNewNotification ( $userId, $displayName, $displayName, $result->getCurrentDate (), $result->getCurrentDate (), 5, $submissionId );
					
					$attributeArray = array();
					array_push($attributeArray, $submissionId);
					$menuTask->setMenuTaskAttributes($menuTaskId, 1, $attributeArray);
					
					$outputArray [$i] [0] = $submissionId;
					$outputArray [$i] [] = $details ['display_name'];
					$outputArray [$i] [] = 1;
				}
			}
			++$i;
		}
		echo json_encode($outputArray);
		
	} else if ($_POST ['task'] == 'getStaticRecordIdDetails') {
		$id = $_POST ['id'];
		$details = $result->getTableIdDetails ( $id );
		$fieldDetails = $result->getTableIdDetails ( $details ['field_id'] );
		
		$outputArray [0] = $details ['id'];
		$outputArray [] = $fieldDetails ['display_name'];
		$outputArray [] = $details ['field_data'];
		
		$outputArray [] = $details ['last_update_date'];
		$outputArray [] = $result->getOfficerName ( $details ['last_updated_by'] );
		$outputArray [] = $details ['creation_date'];
		$outputArray [] = $result->getOfficerName ( $details ['created_by'] );
		$outputArray [] = $details ['active'];
		
		echo json_encode ( $outputArray );
	}else if ($_POST ['task'] == 'getSubmissionRecordIdDetails') {
		$id = $_POST ['id'];
		$details = $result->getTableIdDetails ( $id );
		$fieldDetails = $result->getTableIdDetails ( $details ['field_id'] );
		
		$outputArray [0] = $details ['id'];
		$outputArray [] = $fieldDetails ['display_name'];
		$outputArray [] = $details ['submission_date'];
		$outputArray [] = $result->getDisplayDate($details ['submission_date']);
		$outputArray [] = $details ['submission_officer'];
		$outputArray [] = $result->getOfficerName($details ['submission_officer']);		
		
		$outputArray [] = $details ['last_update_date'];
		$outputArray [] = $result->getOfficerName ( $details ['last_updated_by'] );
		$outputArray [] = $details ['creation_date'];
		$outputArray [] = $result->getOfficerName ( $details ['created_by'] );
		$outputArray [] = $details ['active'];
		
		echo json_encode ( $outputArray );
	} elseif ($_POST ['task'] == 'updateStaticRecord') {
		$id = $_POST ['valueId_su'];
		$details = $result->getTableIdDetails($id);
		
		if($details['field_data'] != $_POST['staticData']){
			$result->setUpdateLog('Data from '.$details['field_data'].' to '.$_POST['staticData']);
			$result->updateTableParameter('field_data', $_POST['staticData']);
		}
		$result->commitResultTypeFieldDataUpdate($id);		
		$outputArray[0] = $id;	
		
		echo json_encode ( $outputArray );
	}elseif ($_POST ['task'] == 'updateSubmissionRecord') {
		$id = $_POST ['valueId_u'];
		$details = $result->getTableIdDetails($id);

		if($details['submission_date'] != $_POST['submissionDate']){
			$result->setUpdateLog('Date from '.$details['submission_date'].' to '.$_POST['submissionDate']);
			$result->updateTableParameter('submission_date', $_POST['submissionDate']);
		}
		if($details['submission_officer'] != $_POST['submissionOfficer_val']){
			$result->setUpdateLog('Officer from '.$details['submission_officer'].' to '.$_POST['submissionOfficer_val']);
			$result->updateTableParameter('submission_officer', $_POST['submissionOfficer_val']);
		}
		$result->commitResultTypeFieldSubmissionUpdate($id);
		
		//updating the menu task & notification records
		if($details['submission_date'] != $_POST['submissionDate'] || $details['submission_officer'] != $_POST['submissionOfficer_val']){
			$menuTaskId = $menuTask->getMenuTaskId4SourceId($id);
			$notificationId = $notification->getNotificationId4Source($id);
			
			$menuDetails = $result->getTableIdDetails($menuTaskId);
			$notificationDetails = $result->getTableIdDetails($notificationId);
			
			if($details['submission_date'] != $_POST['submissionDate']){
				$menuTask->setUpdateLog('End Date from '.$details['submission_date'].' to '.$_POST['submissionDate']);
				$menuTask->updateTableParameter('end_date', $_POST['submissionDate']);
				
				$notification->setUpdateLog('End Date from '.$details['submission_date'].' to '.$_POST['submissionDate']);
				$notification->updateTableParameter('end_date', $_POST['submissionDate']);
			}
			
			if($details['submission_officer'] != $_POST['submissionOfficer_val']){
				$menuTask->setUpdateLog('User from '.$details['submission_officer'].' to '.$_POST['submissionOfficer_val']);
				$menuTask->updateTableParameter('user_id', $_POST['submissionOfficer_val']);
				
				$notification->setUpdateLog('User from '.$details['submission_officer'].' to '.$_POST['submissionOfficer_val']);
				$notification->updateTableParameter('user_id', $_POST['submissionOfficer_val']);
			}
			$menuTask->commitMenuTaskAssignmentUpdate($menuTaskId);
			$notification->commitNotificationUpdate($notificationId);
		}
		$outputArray[0] = $id;		
		echo json_encode ( $outputArray );
	}else {
		$outputArray [0] = 0;
		echo json_encode ( $outputArray );
	}
}
?>