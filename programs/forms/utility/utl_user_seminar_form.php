<?php
require_once 'config.php';

require_once BASE_PATH . 'include/utility/class.personalInfo.php';
require_once BASE_PATH . 'include/global/class.options.php';
require_once BASE_PATH . 'include/utility/class.employeeRegistration.php';
require_once BASE_PATH . 'include/utility/class.remarks.php';
require_once BASE_PATH . 'include/hrms/class.designation.php';
require_once BASE_PATH . 'include/hrms/class.seminar.php';
require_once BASE_PATH . 'include/global/class.loginInfo.php';


$personalInfo = new personalInfo ();
$options = new options ();
$registration = new employeeRegistration();
$remarks = new remarks ();
$ranks = new Designation();
$seminar = new Seminar();
$loginInfo = new loginInfo();

$options->isRequestAuthorised4Form('LMENUL41');

if (isset ( $_POST ['task'] )) {
	if ($_POST ['task'] == 'insertRecord') {
		$userId = $_POST ['userId'];
		$outputArray [0] = 0;
		$seminarId = $seminar->setUserSeminarDetails($userId, $_POST['seminarTitle'], $_POST['organizedBy'], $_POST['startDate'], $_POST['duration']);
		if ($seminarId) {
			if($_POST['comments'] != "")
				$remarks->setRemark ( $seminarId, $_POST ['comments'], false );
			
			$details = $seminar->getSeminarIdDetails($seminarId);
			$outputArray [0] = $seminarId;
			$outputArray [1] = $details ['seminar_title'];
			$outputArray [2] = $details['organized_by'];
			$outputArray [3] = $options->getDisplayDate($details ['seminar_date']);
			$outputArray [4] = $details ['duration'];
		}
		echo json_encode ( $outputArray );
	} elseif ($_POST ['task'] == 'searchDetails') {
		$userId = trim ( $_POST ['userId'] );
		$searchType = $_POST ['search_type'];
		$ids = $seminar->getUserSeminarIds($userId, $searchType);
		$outputArray [0] [0] = 1;
		$i = 0;
		foreach ( $ids as $id ) {
			$details = $seminar->getSeminarIdDetails($id);
			$outputArray [$i] [0] = $id;
			$outputArray [$i] [1] = $details ['seminar_title'];
			$outputArray [$i] [2] = $details['organized_by'];
			$outputArray [$i] [3] = $options->getDisplayDate($details ['seminar_date']);
			$outputArray [$i] [4] = $details ['duration'];
			++ $i;
		}
		echo json_encode ( $outputArray );
	} elseif ($_POST ['task'] == 'getRecordIdDetails') {
		$seminarId = $_POST ['id'];
		$details = $seminar->getSeminarIdDetails($seminarId);
		$outputArray [0] = $details ['id'];
		$outputArray [] = $details['seminar_title'];
		$outputArray [] = $details['organized_by'];
		$outputArray [] = $options->getDisplayDate($details['seminar_date']);
		$outputArray [] = $details['duration'];		
		$outputArray [] = $details ['last_update_date'];		
		$outputArray [] = $loginInfo->getOfficerName ( $details ['last_updated_by'] );
		$outputArray [] = $details ['creation_date'];
		$outputArray [] = $loginInfo->getOfficerName ( $details ['created_by'] );
		$outputArray [] = $details ['active'];	
		$outputArray [] = $remarks->getGenericRemarkValue($seminarId);
		$outputArray [] = $details['seminar_date'];		
		
		echo json_encode ( $outputArray );
	} elseif ($_POST ['task'] == 'updateRecord') {		
		$userId = trim ( $_POST ['userId'] );
		$seminarId = trim ( $_POST ['recordId_u'] );
		
		$details = $seminar->getSeminarIdDetails($seminarId);
		if($details['seminar_title'] != $_POST['seminarTitle_u']){
			$seminar->setUpdateLog('Title from '.$details['seminar_title'].' to '.$_POST['seminarTitle_u']);
			$seminar->updateTableParameter('seminar_title', $_POST['seminarTitle_u']);
		}
		if($details['organized_by'] != $_POST['organizedBy_u']){
			$seminar->setUpdateLog('Organized By from '.$details['organized_by'].' to '.$_POST['organizedBy_u']);			
			$seminar->updateTableParameter('organized_by', $_POST['organizedBy_u']);
		}
		if($details['duration'] != $_POST['duration_u']){
			$seminar->setUpdateLog('Duration from '.$details['duration'].' to '.$_POST['duration_u']);
			$seminar->updateTableParameter('duration', $_POST['duration_u']);
		}
		if($details['seminar_date'] != $_POST['startDate_u']){
			$seminar->setUpdateLog('Date from '.$details['seminar_date'].' to '.$_POST['startDate_u']);
			$seminar->updateTableParameter('seminar_date', $_POST['startDate_u']);
		}		
		$outputArray [0] = 0;
		$operation = $seminar->commitSeminarUpdate($seminarId);
		$remarkOperation = $remarks->getGenericRemarkValue($seminarId) != $_POST['comments_u'] ? $remarks->setRemark($seminarId, $_POST['comments_u'], false) : false;
		
		if($operation === $seminarId && !$remarkOperation)			
			$outputArray[0] = 1;
		elseif ($operation) {			
			$details = $seminar->getSeminarIdDetails($seminarId);
			$outputArray [0] = $seminarId;
			$outputArray [1] = $details ['seminar_title'];
			$outputArray [2] = $details['organized_by'];
			$outputArray [3] = $options->getDisplayDate($details ['seminar_date']);
			$outputArray [4] = $details ['duration'];
		}
		echo json_encode ( $outputArray );
	} elseif ($_POST ['task'] == "fetchRecord") {
		$details = $personalInfo->getUserIdDetails ( $_POST ['userId'] );
		$details1 = $registration->getRegistrationIdDetails($registration->getEmployeeRegistrationId($details['id']));
		$rankIds = $ranks->getUserRanks($details['id'], 1);
		
		if ($details ['id'] == "")
			$outputArray [0] = 0;
		else {
			$designation = "";			
			$outputArray [0] = $details ['id'];
			$outputArray [1] = $options->getOptionIdValue ( $details ['salutation_id'] ) . " " . $details ['last_name'] . ", " . $details ['first_name'] . " " . $details ['middle_name'];
			$outputArray [2] = $details1 ['employee_code'];
			$outputArray [3] = $details ['official_email_id'];			
			foreach ($rankIds as $rankId){
				$details = $ranks->getRankIdDetails($rankId);
				$designation .= $options->getOptionIdValue($details['rank_id'])."<br />";
			}
			$outputArray [4] = $designation;
		}
		echo json_encode ( $outputArray );
	} elseif ($_POST ['task'] == 'dropRecord') {
		$id = htmlentities ( trim ( $_POST ['id'] ) );
		$seminar->dropSeminarDetails($id);
		$outputArray [0] = $id;
		echo json_encode ( $outputArray );
	} elseif ($_POST ['task'] == 'activateRecord') {
		$id = htmlentities ( trim ( $_POST ['id'] ) );
		$seminar->activateSeminarDetails($id);
		$outputArray [0] = $id;
		echo json_encode ( $outputArray );
	} else {
		$outputArray [0] = 0;
		echo json_encode ( $outputArray );
	}
}
?>