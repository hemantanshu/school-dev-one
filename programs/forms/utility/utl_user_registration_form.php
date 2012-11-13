<?php
require_once 'config.php';

require_once BASE_PATH . 'include/global/class.options.php';
require_once BASE_PATH . 'include/utility/class.personalInfo.php';
require_once BASE_PATH . 'include/utility/class.address.php';
require_once BASE_PATH . 'include/utility/class.employeeRegistration.php';
require_once BASE_PATH . 'include/hrms/class.designation.php';


$options = new options ();
$personalInfo =  new personalInfo();
$address = new address();
$registration = new employeeRegistration();
$designation = new Designation();

$options->isRequestAuthorised4Form('LMENUL37');


if (isset ( $_POST ['task'] )) {
	if ($_POST ['task'] == 'insertRecord') {
		
		$nationalityId = $_POST['nationality_val'] == "" ? $options->setNewOptionValue($_POST['nationality'], 'NATON') : $_POST['nationality_val'];
		$religionId = $_POST['religion_val'] == "" ? $options->setNewOptionValue($_POST['religion'], 'RELIG') : $_POST['religion_val'];
		$userId = $personalInfo->setUserDetails($_POST['salutation'], $_POST['firstName'], $_POST['middleName'], $_POST['lastName'], $_POST['bday'], $_POST['gender'], $_POST['officialEmail'], $_POST['contactNo'], $religionId, $_POST['aContactNo'], $nationalityId, $_POST['marital'], $_POST['personalEmail']);
				
		$recordShelve1 = $_POST['recordShelve1_val'] == "" ? $options->setNewOptionValue($_POST['recordShelve1'], 'SHLVE') : $_POST['recordShelve1_val'];
		$recordShelve2 = $_POST['recordShelve2_val'] == "" ? $options->setNewOptionValue($_POST['recordShelve2'], 'SHLVE') : $_POST['recordShelve2_val'];
		$recordShelve3 = $_POST['recordShelve3_val'] == "" ? $options->setNewOptionValue($_POST['recordShelve3'], 'SHLVE') : $_POST['recordShelve3_val'];		
		$registrationId = $registration->setEmployeeRegistrationDetails($userId, $_POST['employeeCode'], $_POST['applicationId'], $_POST['joiningDate'], $_POST['department_val'], $_POST['employeeType_val'], $recordShelve1, $recordShelve2, $recordShelve3);
		
		$cityId = $_POST['city_val'] == "" ? $options->setNewOptionValue($_POST['city'], 'CITYS') : $_POST['city_val'];
		$stateId = $_POST['state_val'] == "" ? $options->setNewOptionValue($_POST['state'], 'STATE') : $_POST['state_val'];
		$countryId = $_POST['country_val'] == "" ? $options->setNewOptionValue($_POST['country'], 'CNTRY') : $_POST['country_val'];
		$addressId = $address->setAddressDetails($_POST['streetAddress1'], $_POST['streetAddress2'], $cityId, $stateId, $countryId, $_POST['pincode']);
		
		$personalInfo->updateTableParameter('address_id', $addressId);
		$personalInfo->commitUserDetailsUpdate($userId);
				
		$designation->setUserRank($userId, $_POST['rank_val'], $_POST['joiningDate'], '');
		
		$outputArray[0] = $userId;
		echo json_encode($outputArray);
		
	} elseif($_POST ['task'] == 'checkEmployeeCode'){
		$employeeCode = $_POST['employeeCode'];
		$status = $registration->getEmployeeId4EmployeeCode($employeeCode);
		if(!$status)
			$outputArray[0] = 1;
		else
			$outputArray[0] = 0;
		echo json_encode($outputArray);
	}else {
		$outputArray [0] = 0;
		echo json_encode ( $outputArray );
	}
}
?>