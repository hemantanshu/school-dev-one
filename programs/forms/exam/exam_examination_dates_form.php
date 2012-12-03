<?php
require_once 'config.php';

require_once BASE_PATH . 'include/global/class.menuTask.php';
require_once BASE_PATH . 'include/exam/class.examination.php';
require_once BASE_PATH . 'include/utility/class.subject.php';
require_once BASE_PATH . 'include/hrms/class.designation.php';
require_once BASE_PATH . 'include/global/class.notification.php';
require_once BASE_PATH . 'include/global/class.session.php';
require_once BASE_PATH . 'include/exam/class.markHandling.php';
require_once BASE_PATH . 'include/exam/class.grading.php';


$examination = new Examination ();
$subject = new subjects ();
$designation = new Designation ();
$menuTask = new MenuTask ();
$notification = new Notification ();
$session = new Session ();
$markHandling = new MarkHandling();
$grading = new Grading();

$examination->isRequestAuthorised4Form ( 'LMENUL70' );

if (isset ( $_POST ['task'] )) {
	if ($_POST ['task'] == 'insertRecord') {
		
		$maxMark = $_POST['markingType_i'] == '' ? $_POST['maxMark_i'] : $grading->getGradingTypeMaxScore($_POST['markingType_i']);  
		
		$examinationId = $examination->setExaminationSubjectDate ( $_POST ['sessionId'], $_POST ['examinationId'], $_POST ['classId'], $_POST['sectionId'], $_POST ['subjectName_i'], $_POST ['subjectComponent_i'], $_POST ['examinationName_i'], $_POST ['examinationDate_i'], $_POST ['examinationTime_i'], $_POST ['examinationDuration_i'], $_POST ['markingType_i'], $_POST ['credit_i'], $maxMark, $_POST ['passMark_i'], $_POST ['markSubmissionDate_i'], $_POST ['markSubmissionOfficer_ival'], $_POST ['markVerificationDate_i'], $_POST ['markVerificationOfficer_ival'] );
		$outputArray [0] = 0;
		if ($examinationId) {
			$subjectDetails = $examination->getTableIdDetails ( $_POST ['subjectName_i'] );
			$subjectComponent = $examination->getTableIdDetails ( $_POST ['subjectComponent_i'] );
			$sectionDetails = $examination->getTableIdDetails($_POST['sectionId']);
			$classDetails = $examination->getTableIdDetails($_POST['classId']);
			$classDetails = $examination->getTableIdDetails($classDetails['class_id']);
			
			$url = $_POST['markingType_i'] == "" ? "pages/exam/exam_mark_submission.php" : "pages/exam/exam_grade_submission.php";
			$subjectCode = $subjectDetails ['subject_code'];
			$subjectName = $subjectDetails ['subject_name'];
			
			$comments = "Mark Submission Role For: ".$classDetails['class_name']." ".$sectionDetails['section_name']." ". "$subjectCode  $subjectName ";
			$urlName = "Submission : ".$classDetails['class_name']." ".$sectionDetails['section_name']." ". "$subjectCode  $subjectName ";
			$taskId = $menuTask->setMenuTaskAssignment ( $_POST ['markSubmissionOfficer_ival'], $urlName, $url, $examinationId . '-S', $comments, $_POST ['examinationDate_i'], $_POST ['markSubmissionDate_i'] );
			$attribute = array ();
			$attribute [0] = $examinationId;
			$menuTask->setMenuTaskAttributes ( $taskId, 1, $attribute );
			$notification->setNewNotification ( $_POST ['markSubmissionOfficer_ival'], "Mark Submission Role Assigned", $comments, $_POST ['examinationDate_i'], $_POST ['markSubmissionDate_i'], 1, $examinationId . '-S' );
			
			// setting up the notification for the mark verification officer
			$url = $_POST['markingType_i'] == "" ? "pages/exam/exam_mark_verification.php" : "pages/exam/exam_grade_verification.php";
			$comments = "Mark Verification Role For: ".$classDetails['class_name']." ".$sectionDetails['section_name']." ". "$subjectCode  $subjectName ";
			$urlName = "Verification : ".$classDetails['class_name']." ".$sectionDetails['section_name']." ". "$subjectCode  $subjectName ";
			
			$verificationStartDate = $examination->getFutureDate($_POST ['markSubmissionDate_i'], 1);
			$taskId = $menuTask->setMenuTaskAssignment ( $_POST ['markVerificationOfficer_ival'], $urlName, $url, $examinationId . '-V', $comments, $verificationStartDate, $_POST ['markVerificationDate_i'] );
			$attribute = array ();
			$attribute [0] = $examinationId;
			$menuTask->setMenuTaskAttributes ( $taskId, 1, $attribute );
			$notification->setNewNotification ( $_POST ['markSubmissionOfficer_ival'], "Mark Submission Role Assigned", $comments, $verificationStartDate, $_POST ['markVerificationDate_i'], 1, $examinationId . '-V' );
			
			$outputArray [0] = $examinationId;
			$outputArray [] = $subjectDetails ['subject_code'];
			$outputArray [] = $subjectDetails ['subject_name'];
			$outputArray [] = $subjectComponent ['subject_component_name'];
			$outputArray [] = $examination->getDisplayDate ( $_POST ['examinationDate_i'] );
		}
		echo json_encode ( $outputArray );
	
	} else if ($_POST ['task'] == 'search') {
		$search_type = $_POST ['search_type'];
		$sessionId = $_POST ['sessionId'];
		$examinationId = $_POST ['examinationId'];
		$sectionId = $_POST ['sectionId'];
		$editable = 1;
		if (! $session->isSessionEditable ( $sessionId ))
			$editable = 0;
		
		$examinationDateIds = $examination->getExaminationSubjectDateIds ( $sessionId, $examinationId, $sectionId, $search_type );
		$outputArray [0] [0] = 1;
		$i = 0;
		foreach ( $examinationDateIds as $examinationId ) {
			$details = $examination->getTableIdDetails ( $examinationId );
			$subjectDetails = $examination->getTableIdDetails ( $details ['subject_id'] );
			$subjectComponentDetails = $examination->getTableIdDetails ( $details ['subject_component_id'] );
			
			$outputArray [$i] [0] = $examinationId;
			$outputArray [$i] [] = $subjectDetails ['subject_code'];
			$outputArray [$i] [] = $subjectDetails ['subject_name'];
			$outputArray [$i] [] = $subjectComponentDetails ['subject_component_name'];
			$outputArray [$i] [] = $examination->getDisplayDate ( $details ['examination_date'] );
			$outputArray [$i] [] = $editable;
			++ $i;
		}
		echo json_encode ( $outputArray );
	
	} else if ($_POST ['task'] == 'getRecordIdDetails') {
		$examinationId = $_POST ['id'];
		$details = $examination->getTableIdDetails ( $examinationId );
		$editable = 1;
		if (! $session->isSessionEditable ( $details ['session_id'] ))
			$editable = 0;
		
		$subjectDetails = $examination->getTableIdDetails ( $details ['subject_id'] );
		$subjectComponentDetails = $examination->getTableIdDetails ( $details ['subject_component_id'] );
		if ($details ['marking_type'] == '')
			$markingType = "Absolute Marking";
		else {
			$markingDetails = $examination->getTableIdDetails ( $details ['marking_type'] );
			$markingType = $markingDetails ['grading_name'];
		}
		
		$outputArray [0] = $examinationId;
		$outputArray [] = $details ['examination_name'];
		$outputArray [] = $examination->getDisplayDate ( $details ['examination_date'] );
		$outputArray [] = $subjectDetails ['subject_code'] . " " . $subjectDetails ['subject_name'];
		$outputArray [] = $subjectComponentDetails ['subject_component_name'];
		$outputArray [] = $details ['examination_start_time'];
		$outputArray [] = $details ['examination_duration'];
		$outputArray [] = $markingType;
		$outputArray [] = $details ['subject_credit'];
		$outputArray [] = $examination->getDisplayDate ( $details ['mark_submission_date'] );
		$outputArray [] = $examination->getOfficerName ( $details ['mark_submission_officer'] ); // 10
		$outputArray [] = $examination->getDisplayDate ( $details ['mark_verification_date'] );
		$outputArray [] = $examination->getOfficerName ( $details ['mark_verification_officer'] );
		
		$outputArray [] = $details ['last_update_date'];
		$outputArray [] = $examination->getOfficerName ( $details ['last_updated_by'] );
		$outputArray [] = $details ['creation_date'];
		$outputArray [] = $examination->getOfficerName ( $details ['created_by'] );
		$outputArray [] = $details ['active'];
		$outputArray [] = $editable;
		
		$outputArray [] = $details ['examination_date'];
		$outputArray [] = $details ['subject_id']; // 20
		$outputArray [] = $details ['subject_component_id'];
		$outputArray [] = $details ['marking_type'];
		$outputArray [] = $details ['mark_submission_date'];
		$outputArray [] = $details ['mark_submission_officer'];
		$outputArray [] = $details ['mark_verification_date'];
		$outputArray [] = $details ['mark_verification_officer'];
		
		$outputArray [] = $details ['max_mark'];
		$outputArray [] = $details ['pass_mark'];
		
		echo json_encode ( $outputArray );
	} elseif ($_POST ['task'] == 'updateRecord') {
		$examinationId = $_POST ['valueId_u'];
		$details = $examination->getTableIdDetails ( $examinationId );
		if ($session->isSessionEditable ( $details ['session_id'] )) {
			
			if($details['examination_name'] != $_POST['examinationName_u']){
				$examination->setUpdateLog('Name from '.$details['examination_name'].' to '.$_POST['examinationName_u']);
				$examination->updateTableParameter ( 'examination_name', $_POST ['examinationName_u'] );
			}
			if($details['examination_date'] != $_POST['examinationDate_u']){
				$examination->setUpdateLog('Date from '.$details['examination_date'].' to '.$_POST['examinationDate_u']);
				$examination->updateTableParameter ( 'examination_date', $_POST ['examinationDate_u'] );
			}
			if($details['examination_start_time'] != $_POST['examinationTime_u']){
				$examination->setUpdateLog('Time from '.$details['examination_start_time'].' to '.$_POST['examinationTime_u']);
				$examination->updateTableParameter ( 'examination_start_time', $_POST ['examinationTime_u'] );
			}
			if($details['examination_duration'] != $_POST['examinationDuration_u']){
				$examination->setUpdateLog('Duration from '.$details['examination_duration'].' to '.$_POST['examinationDuration_u']);
				$examination->updateTableParameter ( 'examination_duration', $_POST ['examinationDuration_u'] );
			}			
			if($details['subject_credit'] != $_POST['credit_u']){
				$examination->setUpdateLog('Credits from '.$details['subject_credit'].' to '.$_POST['credit_u']);
				$examination->updateTableParameter ( 'subject_credit', $_POST ['credit_u'] );
			}
			if($details['max_mark'] != $_POST['maxMark_u']){
				$examination->setUpdateLog('Max Mark from '.$details['max_mark'].' to '.$_POST['maxMark_u']);
				$examination->updateTableParameter ( 'max_mark', $_POST ['maxMark_u'] );
			}
			if($details['pass_mark'] != $_POST['passMark_u']){
				$examination->setUpdateLog('Pass Mark from '.$details['pass_mark'].' to '.$_POST['passMark_u']);
				$examination->updateTableParameter ( 'pass_mark', $_POST ['passMark_u'] );
			}
			if($details['mark_submission_date'] != $_POST['markSubmissionDate_u']){
				$examination->setUpdateLog('Submission Date from '.$details['mark_submission_date'].' to '.$_POST['markSubmissionDate_u']);
				$examination->updateTableParameter ( 'mark_submission_date', $_POST ['markSubmissionDate_u'] );
			}
			if($details['mark_verification_date'] != $_POST['markVerificationDate_u']){
				$examination->setUpdateLog('Verification Date from '.$details['mark_verification_date'].' to '.$_POST['markVerificationDate_u']);
				$examination->updateTableParameter ( 'mark_verification_date', $_POST ['markVerificationDate_u'] );
			}
			if($details['mark_submission_officer'] != $_POST['markSubmissionOfficer_uval']){
				$examination->setUpdateLog('Submission Officer from '.$details['mark_submission_officer'].' to '.$_POST['markSubmissionOfficer_uval']);
				$examination->updateTableParameter ( 'mark_submission_officer', $_POST ['markSubmissionOfficer_uval'] );
			}
			if($details['mark_verification_officer'] != $_POST['markVerificationOfficer_uval']){
				$examination->setUpdateLog('Verification Officer from '.$details['mark_verification_officer'].' to '.$_POST['markVerificationOfficer_uval']);
				$examination->updateTableParameter ( 'mark_verification_officer', $_POST ['markVerificationOfficer_uval'] );
			}
			if($details['marking_type'] != $_POST['markingType_u']){
				$examination->setUpdateLog('Marking Type from '.$details['marking_type'].' to '.$_POST['markingType_u']);
				$examination->updateTableParameter ( 'marking_type', $_POST ['markingType_u'] );
				if($_POST['markingType_u'] != '')
					$examination->updateTableParameter ( 'max_mark', $grading->getGradingTypeMaxScore($_POST ['markingType_u']) );
			}
			$examination->commitExaminationSubjectDateUpdate ( $examinationId );
			
			if (($details ['mark_submission_date'] != $_POST ['markSubmissionDate_u']) || ($details ['mark_submission_officer'] != $_POST ['markSubmissionOfficer_uval']) || ($details ['examination_date'] != $_POST ['examinationDate_u']) || $details ['marking_type'] != $_POST ['markingType_u']) {				
				$menuTaskId = $menuTask->getMenuTaskId4SourceId ( $examinationId . '-S' );				
				// updating the menu task
				if($details['marking_type'] != $_POST['markingType_u']){					
					$submissionUrl = $_POST['markingType_u'] == "" ? "pages/exam/exam_mark_submission.php" : "pages/exam/exam_grade_submission.php";
					$verificationUrl = $_POST['markingType_u'] == "" ? "pages/exam/exam_mark_verification.php" : "pages/exam/exam_grade_verification.php";					
				}
				
				if($details['start_date'] != $_POST['examinationDate_u']){
					$menuTask->setUpdateLog('Start Date from '.$details['start_date'].' to '.$_POST['examinationDate_u']);
					$menuTask->updateTableParameter ( 'start_date', $_POST ['examinationDate_u'] );
				}
				if($details['end_date'] != $_POST['markSubmissionDate_u']){
					$menuTask->setUpdateLog('End Date from '.$details['end_date'].' to '.$_POST['markSubmissionDate_u']);
					$menuTask->updateTableParameter ( 'end_date', $_POST ['markSubmissionDate_u'] );
				}
				if($details['user_id'] != $_POST['markSubmissionOfficer_uval']){
					$menuTask->setUpdateLog('Submission Officer from '.$details['user_id'].' to '.$_POST['markSubmissionOfficer_uval']);
					$menuTask->updateTableParameter ( 'user_id', $_POST ['markSubmissionOfficer_uval'] );
				}
				if($details['complete_flag'] != ''){
					$menuTask->setUpdateLog('Complete Flag to blank ');
					$menuTask->updateTableParameter ( 'complete_flag', '' );
				}
				if($details['menu_url'] != $submissionUrl){
					$menuTask->setUpdateLog('Url from '.$details['menu_url'].' to '.$submissionUrl);
					$menuTask->updateTableParameter ( 'menu_url', $submissionUrl );
				}				
				$menuTask->commitMenuTaskAssignmentUpdate ( $menuTaskId );
				
				$notificationId = $notification->getNotificationId4Source ( $examinationId . '-S' );
				if($details['start_date'] != $_POST['examinationDate_u']){
					$notification->setUpdateLog('Start Date from '.$details['start_date'].' to '.$_POST['examinationDate_u']);
					$notification->updateTableParameter ( 'start_date', $_POST ['examinationDate_u'] );
				}
				if($details['end_date'] != $_POST['markSubmissionDate_u']){
					$notification->setUpdateLog('End Date from '.$details['end_date'].' to '.$_POST['markSubmissionDate_u']);
					$notification->updateTableParameter ( 'end_date', $_POST ['markSubmissionDate_u'] );
				}
				if($details['user_id'] != $_POST['markSubmissionOfficer_uval']){
					$notification->setUpdateLog('Officer from '.$details['user_id'].' to '.$_POST['markSubmissionOfficer_uval']);
					$notification->updateTableParameter ( 'user_id', $_POST ['markSubmissionOfficer_uval'] );
				}
				if($details['hide_flag'] != ''){
					$notification->setUpdateLog('Hide flag to blank');
					$notification->updateTableParameter ( 'hide_flag', '' );
				}				
				$notification->commitNotificationUpdate ( $notificationId );
			}
			
			if ($details ['mark_verification_date'] != $_POST ['markVerificationDate_u'] || $details ['mark_verification_officer'] != $_POST ['markVerificationOfficer_uval'] || $details ['mark_submission_date'] != $_POST ['markSubmissionDate_u'] || $details ['marking_type'] != $_POST ['markingType_u']) {
				
				$menuTaskId = $menuTask->getMenuTaskId4SourceId ( $examinationId . '-V' );
				// updating the menu task
				if($details['start_date'] != $_POST['markSubmissionDate_u']){
					$menuTask->setUpdateLog('Start Date from '.$details['start_date'].' to '.$_POST['markSubmissionDate_u']);
					$menuTask->updateTableParameter ( 'start_date', $examination->getFutureDate($_POST ['markSubmissionDate_u'], 1) );
				}
				if($details['end_date'] != $_POST['markVerificationDate_u']){
					$menuTask->setUpdateLog('End Date from '.$details['end_date'].' to '.$_POST['markVerificationDate_u']);
					$menuTask->updateTableParameter ( 'end_date', $_POST ['markVerificationDate_u'] );
				}
				if($details['user_id'] != $_POST['markVerificationOfficer_uval']){
					$menuTask->setUpdateLog('Officer from '.$details['user_id'].' to '.$_POST['markVerificationOfficer_uval']);
					$menuTask->updateTableParameter ( 'user_id', $_POST ['markVerificationOfficer_uval'] );
				}
				if($details['menu_url'] != $verificationUrl){
					$menuTask->setUpdateLog('URL from '.$details['menu_url'].' to '.$verificationUrl);
					$menuTask->updateTableParameter ( 'menu_url', $verificationUrl );
				}
				if($details['complete_flag'] != ''){
					$menuTask->setUpdateLog('Complete Flag to blank');
					$menuTask->updateTableParameter ( 'complete_flag', '' );
				}			
				$menuTask->commitMenuTaskAssignmentUpdate ( $menuTaskId );
				
				$notificationId = $notification->getNotificationId4Source ( $examinationId . '-V' );
				if($details['start_date'] != $_POST['examinationDate_u']){
					$notification->setUpdateLog('Start Date from '.$details['start_date'].' to '.$_POST['markSubmissionDate_u']);
					$notification->updateTableParameter ( 'start_date', $_POST ['examinationDate_u'] );
				}
				if($details['end_date'] != $_POST['markVerificationDate_u']){
					$notification->setUpdateLog('End Date from '.$details['end_date'].' to '.$_POST['markVerificationDate_u']);
					$notification->updateTableParameter ( 'end_date', $_POST ['markVerificationDate_u'] );
				}
				if($details['user_id'] != $_POST['markVerificationOfficer_uval']){
					$notification->setUpdateLog('Officer from '.$details['user_id'].' to '.$_POST['markVerificationOfficer_uval']);
					$notification->updateTableParameter ( 'user_id', $_POST ['markVerificationOfficer_uval'] );
				}
				if($details[''] != $_POST['']){
					$notification->setUpdateLog('Hide flag to blank');
					$notification->updateTableParameter ( 'hide_flag', '' );
				}				
				$notification->commitNotificationUpdate ( $notificationId );
			}			
		}
		$subjectDetails = $examination->getTableIdDetails ( $details ['subject_id'] );
		$subjectComponentDetails = $examination->getTableIdDetails ( $details ['subject_component_id'] );
		$outputArray [0] = $examinationId;
		$outputArray [] = $subjectDetails ['subject_code'];
		$outputArray [] = $subjectDetails ['subject_name'];
		$outputArray [] = $subjectComponentDetails ['subject_component_name'];
		$outputArray [] = $examination->getDisplayDate ( $_POST ['examinationDate_u'] );
		$outputArray [] = 1;
			
		echo json_encode ( $outputArray );
	
	} else if ($_POST ['task'] == 'getClassExaminationSessionDetails') {
		$sectionId = $_POST ['sectionId'];
		$sessionId = $_POST ['sessionId'];
		$examinationId = $_POST ['examinationId'];
		
		$classDetails = $examination->getTableIdDetails ( $sectionId );
		$classNameDetails = $examination->getTableIdDetails ( $classDetails ['class_id'] );
		$classNameDetails = $examination->getTableIdDetails ( $classNameDetails ['class_id'] );
		$sessionDetails = $examination->getTableIdDetails ( $sessionId );
		$examinationDetails = $examination->getTableIdDetails ( $examinationId );
		if ($classDetails ['session_id'] == $sessionId && $examinationDetails ['session_id'] == $sessionId) {
			
			$outputArray [0] = $sessionDetails ['session_name'];
			$outputArray [] = $examinationDetails ['examination_name'];
			$outputArray [] = $classNameDetails ['class_name'];
			$outputArray[] = $classDetails['section_name'];
			$outputArray[] = $classDetails['class_id'];
		} else
			$outputArray [0] = 0;
		echo json_encode ( $outputArray );
	
	} elseif ($_POST ['task'] == 'getClassAssignedSubjects') {
		$classId = $_POST ['classId'];
		$subjectDetailsArray = $examination->getClassSubjectIdDetails ( $classId );
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
		$subjectComponentIds = $examination->getSubjectCombinationIds ( $subjectId, 1 );
		$i = 0;
		$outputArray [0] [0] = 1;
		foreach ( $subjectComponentIds as $componentId ) {
			$details = $examination->getTableIdDetails ( $componentId );
			$outputArray [$i] [0] = $details ['id'];
			$outputArray [$i] [] = $details ['subject_component_name'];
			++ $i;
		}
		echo json_encode ( $outputArray );
	} elseif ($_POST ['task'] == 'dropRecord') {
		$id = $_POST['id'];
		$examination->dropExaminationSubjectDate($id);		
		
		$taskId = $menuTask->getMenuTaskId4SourceId($id."-S");
		$menuTask->dropMenuTaskAssignment($taskId);
		
		$taskId = $menuTask->getMenuTaskId4SourceId($id."-V");
		$menuTask->dropMenuTaskAssignment($taskId);
		
		$notificationId = $notification->getNotificationId4Source($id."-S");
		$notification->dropNotification($notificationId);
		
		$notificationId = $notification->getNotificationId4Source($id."-V");
		$notification->dropNotification($notificationId);
		
		$outputArray[0] = 1;
		echo json_encode($outputArray);		
		
	} elseif ($_POST ['task'] == 'activateRecord') {
		$id = $_POST['id'];
		
		$examination->activateExaminationSubjectDate($id);
		
		$taskId = $menuTask->getMenuTaskId4SourceId($id."-S");
		$menuTask->activateMenuTaskAssignment($taskId);
		
		$taskId = $menuTask->getMenuTaskId4SourceId($id."-V");
		$menuTask->activateMenuTaskAssignment($taskId);
		
		$notificationId = $notification->getNotificationId4Source($id."-S");
		$notification->activateNotification($notificationId);
		
		$notificationId = $notification->getNotificationId4Source($id."-V");
		$notification->activateNotification($notificationId);
		
		$outputArray[0] = 1;
		echo json_encode($outputArray);
	} else {
		$outputArray [0] = 0;
		echo json_encode ( $outputArray );
	}
}
?>