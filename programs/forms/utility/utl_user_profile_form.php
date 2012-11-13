<?php
require_once 'config.php';

require_once BASE_PATH . 'include/utility/class.personalInfo.php';
require_once BASE_PATH . 'include/global/class.options.php';
require_once BASE_PATH . 'include/utility/class.employeeRegistration.php';
require_once BASE_PATH . 'include/utility/class.address.php';
require_once BASE_PATH . 'include/utility/class.remarks.php';
require_once BASE_PATH . 'include/utility/class.educationHistory.php';
require_once BASE_PATH . 'include/utility/class.institute.php';
require_once BASE_PATH . 'include/hrms/class.designation.php';
require_once BASE_PATH . 'include/hrms/class.employment.php';
require_once BASE_PATH . 'include/hrms/class.seminar.php';

$personalInfo = new personalInfo ();
$registration = new employeeRegistration ();
$address = new address ();
$remarks = new remarks ();
$options = new options ();
$education = new educationHistory ();
$institute = new institute ();
$seminar = new Seminar ();
$designation = new Designation ();
$employment = new Employment ();

$options->isRequestAuthorised4Form('LMENUL36');

if (isset ( $_POST ['task'] )) {
	if ($_POST ['task'] == 'registrationDetails') {
		$userId = $_POST ['userId'];
		$details = $registration->getRegistrationIdDetails ( $registration->getEmployeeRegistrationId ( $userId ) );
		$outputArray [0] = $details ['id'];
		$outputArray [] = $details ['employee_code'];
		$outputArray [] = $details ['application_id'];
		$outputArray [] = $options->getDisplayDate ( $details ['joining_date'] );
		$outputArray [] = $options->getOptionIdValue ( $details ['record1_id'] );
		$outputArray [] = $options->getOptionIdValue ( $details ['record2_id'] );
		$outputArray [] = $options->getOptionIdValue ( $details ['record3_id'] );
		$outputArray [] = $options->getOptionIdValue ( $details ['department_id'] );
		$outputArray [] = $options->getOptionIdValue ( $details ['employee_type'] );
		
		echo json_encode ( $outputArray );
	} elseif ($_POST ['task'] == "gaurdianDetails") {
		$userId = $_POST ['userId'];
		$gaurdianType = $_POST ['gaurdianType'];
		$gaurdianId = $personalInfo->getUserGuardianId ( $userId, $gaurdianType );
		if ($gaurdianId == "") {
			$outputArray [0] = 1;
		} else {
			$details = $personalInfo->getUserGuardianIdDetails ( $gaurdianId );
			$outputArray [0] = $options->getOptionIdValue ( $details ['salutation_id'] ) . ". " . $details ['last_name'] . ", " . $details ['first_name'] . " " . $details ['last_name'];
			$outputArray [] = $options->getOptionIdValue ( $gaurdianType );
			$outputArray [] = $details ['email_id'];
			$outputArray [] = $options->getOptionIdValue ( $details ['occupation_id'] );
			$outputArray [] = $details ['mobile_no'];
			$outputArray [] = $details ['landline_no'];
			$outputArray [] = $address->getAddressDisplay ( $details ['address_id'] );
		}
		echo json_encode ( $outputArray );
	} elseif ($_POST ['task'] == "educationDetails") {
		$userId = $_POST ['userId'];
		$ids = $education->getUserEducationIds ( $userId, 1 );
		$outputArray [0] [0] = 1;
		$i = 0;
		foreach ( $ids as $id ) {
			$details = $education->getEducationHistoryIdDetails ( $id );
			$outputArray [$i] [0] = $details ['level'];
			$outputArray [$i] [] = $details ['year'];
			$outputArray [$i] [] = $institute->getInstituteName ( $details ['institute_id'] );
			$outputArray [$i] [] = $details ['score'];
			$outputArray [$i] [] = $options->getOptionIdValue ( $details ['scoring_type'] );
            $outputArray [$i] [] = $details['id'];
			++ $i;
		}
		echo json_encode ( $outputArray );
	} elseif ($_POST ['task'] == "seminarDetails") {
		$userId = $_POST ['userId'];
		$seminarIds = $seminar->getUserSeminarIds ( $userId, 1 );
		$i = 0;
		$outputArray [0] [0] = 1;
		foreach ( $seminarIds as $seminarId ) {
			$details = $seminar->getSeminarIdDetails ( $seminarId );
			$outputArray [$i] [0] = $details ['id'];
			$outputArray [$i] [] = $details ['seminar_title'];
			$outputArray [$i] [] = $details ['organized_by'];
			$outputArray [$i] [] = $options->getDisplayDate ( $details ['seminar_date'] );
			$outputArray [$i] [] = $details ['duration'];
			++ $i;
		}
		echo json_encode ( $outputArray );
	} elseif ($_POST ['task'] == "employmentDetails") {
		$userId = $_POST ['userId'];
		$employmentIds = $employment->getUserEmploymentHistoryId($userId, 1);
		$i = 0;
		$outputArray [0] [0] = 1;
		foreach ( $employmentIds as $employmentId) {
			$details = $employment->getEmploymentIdDetails($employmentId);			
			$outputArray [$i] [0] = $details ['id'];
			$outputArray [$i] [] = $institute->getInstituteName($details['organization_id']);			
			$outputArray [$i] [] = $options->getDisplayDate ( $details ['start_date'] );
			$outputArray [$i] [] = $options->getDisplayDate ( $details ['end_date'] );
			$outputArray [$i] [] = $options->getOptionIdValue($details['position_id']);
			$outputArray [$i] [] = $details['organization_id'];				
			++ $i;
		}
		echo json_encode ( $outputArray );
	}elseif ($_POST ['task'] == "designationDetails") {
		$userId = $_POST ['userId'];
		$designationIds = $designation->getUserRanks($userId, 1);	
		$i = 0;
		$outputArray [0] [0] = 1;
		foreach ( $designationIds as $designationId) {
			$details = $designation->getRankIdDetails($designationId);			
			$outputArray [$i] [0] = $details ['id'];
			$outputArray [$i] [] = $options->getOptionIdValue($details['rank_id']);			
			$outputArray [$i] [] = $options->getDisplayDate ( $details ['start_date'] );
			$outputArray [$i] [] = $options->getDisplayDate ( $details ['end_date'] );
			++ $i;
		}
		echo json_encode ( $outputArray );
	} else {
		$outputArray [0] = 0;
		echo json_encode ( $outputArray );
	}
}
?>