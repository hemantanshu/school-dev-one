<?php
require_once 'config.php';

require_once BASE_PATH . 'include/utility/class.personalInfo.php';
require_once BASE_PATH . 'include/global/class.options.php';
require_once BASE_PATH . 'include/utility/class.employeeRegistration.php';
require_once BASE_PATH . 'include/utility/class.remarks.php';
require_once BASE_PATH . 'include/hrms/class.designation.php';
require_once BASE_PATH . 'include/hrms/class.employment.php';
require_once BASE_PATH . 'include/global/class.loginInfo.php';
require_once BASE_PATH . 'include/utility/class.institute.php';

$personalInfo = new personalInfo ();
$options = new options ();
$registration = new employeeRegistration ();
$remarks = new remarks ();
$ranks = new Designation ();
$loginInfo = new loginInfo ();
$employment = new Employment ();
$institute = new institute ();

$options->isRequestAuthorised4Form('LMENUL42');

if (isset ( $_POST ['task'] )) {
	if ($_POST ['task'] == 'insertRecord') {
		$userId = $_POST ['userId'];
		$outputArray [0] = 0;
		
		$positionId = $_POST ['position_val'] == "" ? $options->setNewOptionValue ( $_POST ['position'], 'RANKS' ) : $_POST ['position_val'];
		$employmentId = $employment->setUserEmploymentDetails ( $userId, $_POST ['organization_val'], $_POST ['startDate'], $_POST ['endDate'], $positionId );
		if ($employmentId) {
			if ($_POST ['comments'] != "")
				$remarks->setRemark ( $employmentId, $_POST ['comments'], false );
			$details = $employment->getEmploymentIdDetails ( $employmentId );
			$instituteDetails = $institute->getInstituteIdDetails ( $details ['organization_id'] );
			$outputArray [0] = $employmentId;
			$outputArray [1] = $instituteDetails ['institute_name'];
			$outputArray [2] = $options->getOptionIdValue ( $details ['position_id'] );
			$outputArray [3] = $options->getDisplayDate ( $details ['start_date'] );
			$outputArray [4] = $options->getDisplayDate ( $details ['end_date'] );
		}
		echo json_encode ( $outputArray );
	} elseif ($_POST ['task'] == 'searchDetails') {
		$userId = trim ( $_POST ['userId'] );
		$searchType = $_POST ['search_type'];
		$ids = $employment->getUserEmploymentHistoryId ( $userId, $searchType );
		$outputArray [0] [0] = 1;
		$i = 0;
		foreach ( $ids as $id ) {
			$details = $employment->getEmploymentIdDetails ( $id );
			$instituteDetails = $institute->getInstituteIdDetails ( $details ['organization_id'] );
			$outputArray [$i] [0] = $id;
			$outputArray [$i] [1] = $instituteDetails ['institute_name'];
			$outputArray [$i] [2] = $options->getOptionIdValue ( $details ['position_id'] );
			$outputArray [$i] [3] = $options->getDisplayDate ( $details ['start_date'] );
			$outputArray [$i] [4] = $options->getDisplayDate ( $details ['end_date'] );
			++ $i;
		}
		echo json_encode ( $outputArray );
	} elseif ($_POST ['task'] == 'getRecordIdDetails') {
		$employmentId = $_POST ['id'];
		$details = $employment->getEmploymentIdDetails ( $employmentId );
		$instituteDetails = $institute->getInstituteIdDetails ( $details ['organization_id'] );
		
		$outputArray [0] = $details ['id'];
		$outputArray [] = $instituteDetails ['institute_name'];
		$outputArray [] = $options->getOptionIdValue ( $details ['position_id'] );
		$outputArray [] = $options->getDisplayDate ( $details ['start_date'] );
		$outputArray [] = $options->getDisplayDate ( $details ['end_date'] );
		$outputArray [] = $details ['creation_date'];
		$outputArray [] = $loginInfo->getOfficerName ( $details ['last_updated_by'] );
		$outputArray [] = $details ['creation_date'];
		$outputArray [] = $loginInfo->getOfficerName ( $details ['created_by'] );
		$outputArray [] = $details ['active'];
		$outputArray [] = $remarks->getGenericRemarkValue ( $employmentId );
		$outputArray [] = $details ['organization_id'];
		$outputArray [] = $details ['position_id'];
		$outputArray [] = $details ['start_date'];
		$outputArray [] = $details ['end_date'];
		
		echo json_encode ( $outputArray );
	} elseif ($_POST ['task'] == 'updateRecord') {
		$userId = trim ( $_POST ['userId'] );
		$employmentId = trim ( $_POST ['recordId_u'] );		
		$details = $employment->getEmploymentIdDetails ( $employmentId );
		
		if($details['organization_id'] != $_POST['organization_id']){
			$employment->setUpdateLog('from '.$details['organization_id'].' to '.$_POST['organization_id']);
			$employment->updateTableParameter ( 'organization_id', $_POST ['organization_uval'] );
		}
		if($details['organization_id'] != $_POST['position_uval']){
			$employment->setUpdateLog('from '.$details['organization_id'].' to '.$_POST['position_uval']);			
			$employment->updateTableParameter ( 'position_id', $_POST ['position_uval'] );
		}
		if($details['start_date'] != $_POST['startDate_u']){
			$employment->setUpdateLog('from '.$details['start_date'].' to '.$_POST['startDate_u']);
			$employment->updateTableParameter ( 'start_date', $_POST ['startDate_u'] );			
		}
		if($details['end_date'] != $_POST['endDate_u']){
			$employment->setUpdateLog('from '.$details['end_date'].' to '.$_POST['endDate_u']);
			$employment->updateTableParameter ( 'end_date', $_POST ['endDate_u'] );
		}
		
		$outputArray [0] = 0;
		$operation = $employment->commitEmploymentDetailsUpdate ( $employmentId );
		$remarkOperation = $remarks->getGenericRemarkValue ( $employmentId ) != $_POST ['comments_u'] ? $remarks->setRemark ( $employmentId, $_POST ['comments_u'], false ) : false;
		
		if ($operation === $employmentId){
			$outputArray [0] = 1;
		}elseif ($operation) {
			$details = $employment->getEmploymentIdDetails ( $employmentId );
			$instituteDetails = $institute->getInstituteIdDetails ( $details ['organization_id'] );
			$outputArray [0] = $employmentId;
			$outputArray [1] = $instituteDetails ['institute_name'];
			$outputArray [2] = $options->getOptionIdValue ( $details ['position_id'] );
			$outputArray [3] = $options->getDisplayDate ( $details ['start_date'] );
			$outputArray [4] = $options->getDisplayDate ( $details ['end_date'] );
		}
		echo json_encode ( $outputArray );
	} elseif ($_POST ['task'] == "fetchRecord") {
		$details = $personalInfo->getUserIdDetails ( $_POST ['userId'] );
		$details1 = $registration->getRegistrationIdDetails ( $registration->getEmployeeRegistrationId ( $details ['id'] ) );
		$rankIds = $ranks->getUserRanks ( $details ['id'], 1 );
		
		if ($details ['id'] == "")
			$outputArray [0] = 0;
		else {
			$designation = "";
			$outputArray [0] = $details ['id'];
			$outputArray [1] = $options->getOptionIdValue ( $details ['salutation_id'] ) . " " . $details ['last_name'] . ", " . $details ['first_name'] . " " . $details ['middle_name'];
			$outputArray [2] = $details1 ['employee_code'];
			$outputArray [3] = $details ['official_email_id'];
			foreach ( $rankIds as $rankId ) {
				$details = $ranks->getRankIdDetails ( $rankId );
				$designation .= $options->getOptionIdValue ( $details ['rank_id'] ) . "<br />";
			}
			$outputArray [4] = $designation;
		}
		echo json_encode ( $outputArray );
	} elseif ($_POST ['task'] == 'dropRecord') {
		$id = htmlentities ( trim ( $_POST ['id'] ) );
		$employment->dropEmploymentDetails($id);
		$outputArray [0] = $id;
		echo json_encode ( $outputArray );
	} elseif ($_POST ['task'] == 'activateRecord') {
		$id = htmlentities ( trim ( $_POST ['id'] ) );
		$employment->activateEmploymentDetails($id);
		$outputArray [0] = $id;
		echo json_encode ( $outputArray );
	} else {
		$outputArray [0] = 0;
		echo json_encode ( $outputArray );
	}
}
?>