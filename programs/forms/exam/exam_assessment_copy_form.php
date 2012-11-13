<?php
require_once 'config.php';

require_once BASE_PATH . 'include/global/class.menuTask.php';
require_once BASE_PATH . 'include/exam/class.result.php';
require_once BASE_PATH . 'include/utility/class.subject.php';
require_once BASE_PATH . 'include/hrms/class.designation.php';
require_once BASE_PATH . 'include/global/class.notification.php';
require_once BASE_PATH . 'include/global/class.session.php';
require_once BASE_PATH . 'include/utility/class.sections.php';

$result = new Result ();
$subject = new subjects ();
$designation = new Designation ();
$menuTask = new MenuTask ();
$notification = new Notification ();
$section = new sections();
$session = new Session ();

$result->isRequestAuthorised4Form ( 'LMENUL107' );

if (isset ( $_POST ['task'] )) {	
	if ($_POST ['task'] == 'copyResultAssessment') {
		$resultId = $_POST['resultId'];
		$copyToResultId = $_POST['copyResultId'];
		$sectionId = $_POST['sectionId'];
		
		$assessmentIds = $result->getResultAssessment($resultId, $sectionId, 1);
		$outputArray[0][0] = 0;
		if(count($assessmentIds) == 0){
			$toAssignAssessmentIds = $result->getResultAssessment($copyToResultId, $sectionId, 1);		
			$outputArray[0][0] = 1;
			$i = 0;
			foreach ($toAssignAssessmentIds as $assessmentId){
				$details = $result->getTableIdDetails($assessmentId);
				$outputArray[$i][0] = $assessmentId;
				$outputArray[$i][] = $details['assessment_name'];
				++$i;
			}	
		}		
		echo json_encode ( $outputArray );
	
	} elseif($_POST['task'] == "insertRecord"){
		$toCopyResultId = $_POST['toCopyResultId'];
		$resultId = $_POST['resultId'];
		$sectionId = $_POST['sectionId'];
		
		$startDate = $_POST['startDate'];
		$submissionDate = $_POST ['markSubmissionDate_i'];
		$submissionOfficer = $_POST ['markSubmissionOfficer_ival'];
		$verificationDate = $_POST ['markVerificationDate_i'];
		$verificationOfficer = $_POST ['markVerificationOfficer_ival'];
		
		$assessmentIds = $result->getResultAssessment($toCopyResultId, $sectionId, 1);
		$outputArray[0] = 0;
		foreach($assessmentIds as $assessmentId){
			$activityIds = $result->getActivityIds($assessmentId, 1);
			//create new assessment
			$assessmentDetails = $result->getTableIdDetails($assessmentId);
			$newAssessmentId = $result->setResultAssessment($assessmentDetails['session_id'], $resultId, $assessmentDetails['class_id'], $sectionId, $assessmentDetails['assessment_name'], $assessmentDetails['assessment_order'], $assessmentDetails['marking_scheme']);
			foreach ($activityIds as $activityId){
				$activityDetails = $result->getTableIdDetails($activityId);
				$activitySubjectId = $result->setAssessmentSubject($newAssessmentId, $activityDetails['activity_name'], $activityDetails['activity_order'], $activityDetails['subject_id'], $submissionDate, $submissionOfficer, $verificationDate, $verificationOfficer);
				
				$details = $result->getTableIdDetails ( $newAssessmentId );
				$subjectDetails = $result->getTableIdDetails ( $activityDetails['subject_id'] );
									
				$url = "pages/exam/exam_agrade_submission.php";
				$subjectCode = $subjectDetails ['subject_code'];
				$subjectName = $subjectDetails ['subject_name'];
					
				$comments = "Grade Submission Role For: " .$section->getClassName4Section($sectionId). " " . $section->getSectionName($sectionId) . " " . "$subjectCode  $subjectName ";
				$urlName = "Submission : " . $section->getClassName4Section($sectionId) . " " . $section->getSectionName($sectionId) . " " . "$subjectCode  $subjectName ";
				$taskId = $menuTask->setMenuTaskAssignment ( $submissionOfficer, $urlName, $url, $activitySubjectId . '-S', $comments, $startDate, $submissionDate );
				$attribute = array ();
				$attribute [0] = $activitySubjectId;
				$menuTask->setMenuTaskAttributes ( $taskId, 1, $attribute );
				$notification->setNewNotification ( $submissionOfficer, "Mark Submission Role Assigned", $comments, $startDate, $submissionDate, 1, $activitySubjectId . '-S' );
					
				// setting up the notification for the mark verification officer
				$url = "pages/exam/exam_agrade_verification.php";
				$comments = "Mark Verification Role For: " . $section->getClassName4Section($sectionId) . " " . $section->getSectionName($sectionId) . " " . "$subjectCode  $subjectName ";
				$urlName = "Verification : " . $section->getClassName4Section($sectionId) . " " . $section->getSectionName($sectionId) . " " . "$subjectCode  $subjectName ";
					
				$taskId = $menuTask->setMenuTaskAssignment ( $verificationOfficer, $urlName, $url, $activitySubjectId . '-V', $comments, $submissionDate, $verificationDate );
				$attribute = array ();
				$attribute [0] = $activitySubjectId;
				$menuTask->setMenuTaskAttributes ( $taskId, 1, $attribute );
				$notification->setNewNotification ( $verificationOfficer, "Mark Verification Role Assigned", $comments, $submissionDate, $verificationDate, 1, $activitySubjectId . '-V' );
			}
			$outputArray[0] = 1;
		}
		
		echo json_encode($outputArray);
		
	}else {
		$outputArray [0] = 0;
		echo json_encode ( $outputArray );
	}
}
?>