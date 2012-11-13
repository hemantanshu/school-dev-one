<?php
require_once 'config.php';

require_once BASE_PATH . 'include/utility/class.personalInfo.php';
require_once BASE_PATH . 'include/global/class.options.php';
require_once BASE_PATH . 'include/utility/class.employeeRegistration.php';
require_once BASE_PATH . 'include/utility/class.address.php';
require_once BASE_PATH . 'include/utility/class.remarks.php';
require_once BASE_PATH . 'include/hrms/class.designation.php';

$personalInfo = new personalInfo ();
$options = new options ();
$registration = new employeeRegistration();
$address = new address ();
$remarks = new remarks();
$ranks = new Designation();

$options->isRequestAuthorised4Form('LMENUL39');

if (isset ( $_POST ['task'] )) {
	if ($_POST ['task'] == 'updateRecord') {
		// create registration details
		$userId = $_POST ['userId'];
		$guardianType = $_POST ['guardianType'];
		$outputArray [0] = 0;
		
		$guardianId = $personalInfo->getUserGuardianId ( $userId, $guardianType );
		
		$city = $_POST ['city_val'] == '' ? $options->setNewOptionValue ( $_POST ['city'], 'CITYS' ) : $_POST ['city_val'];
		$state = $_POST ['state_val'] == '' ? $options->setNewOptionValue ( $_POST ['state'], 'STATE' ) : $_POST ['state_val'];
		$country = $_POST ['country_val'] == '' ? $options->setNewOptionValue ( $_POST ['country'], 'CNTRY' ) : $_POST ['country_val'];
		$occupationId = $_POST ['occupation_val'] == '' ? $options->setNewOptionValue ( $_POST ['occupation'], 'PROFS' ) : $_POST ['occupation_val'];
		
		if ($guardianId == "") {
			// insert into the table
			$guardianId = $personalInfo->setUserGuardianDetails ( $guardianType, $userId, $_POST ['salutation'], $_POST ['firstName'], $_POST ['middleName'], $_POST ['lastName'], $_POST ['mobileNo'], $_POST ['landlineNo'], $_POST ['emailId'], $occupationId );
			$addressId = $address->setAddressDetails ( $_POST ['streetAddress1'], $_POST ['streetAddress2'], $city, $state, $country, $_POST ['pincode'] );
			$personalInfo->updateTableParameter ( 'address_id', $addressId );
			$personalInfo->commitUserGuardianUpdate ( $guardianId );
			$outputArray [0] = 1;
		} else {
			$details = $personalInfo->getTableIdDetails($guardianId);
			if($details['salutation_id'] != $_POST['salutation']){
				$personalInfo->setUpdateLog('Salutation from '.$details['salutation_id'].' to '.$_POST['salutation']);
				$personalInfo->updateTableParameter ( 'salutation_id', $_POST ['salutation'] );
			}
			if($details['first_name'] != $_POST['firstName']){
				$personalInfo->setUpdateLog('First Name from '.$details['first_name'].' to '.$_POST['firstName']);
				$personalInfo->updateTableParameter ( 'first_name', $_POST ['firstName'] );
			}
			if($details['middle_name'] != $_POST['middleName']){
				$personalInfo->setUpdateLog('Middle Name from '.$details['middle_name'].' to '.$_POST['middleName']);
				$personalInfo->updateTableParameter ( 'middle_name', $_POST ['middleName'] );				
			}
			if($details['last_name'] != $_POST['lastName']){
				$personalInfo->setUpdateLog('Last Name from '.$details['last_name'].' to '.$_POST['lastName']);
				$personalInfo->updateTableParameter ( 'last_name', $_POST ['lastName'] );
			}
			if($details['mobile_no'] != $_POST['mobileNo']){
				$personalInfo->setUpdateLog('Mobile No from '.$details['mobile_no'].' to '.$_POST['mobileNo']);
				$personalInfo->updateTableParameter ( 'mobile_no', $_POST ['mobileNo'] );
			}
			if($details['landline_no'] != $_POST['landlineNo']){
				$personalInfo->setUpdateLog('Landline from '.$details['landline_no'].' to '.$_POST['landlineNo']);
				$personalInfo->updateTableParameter ( 'landline_no', $_POST ['landlineNo'] );
			}
			if($details['email_id'] != $_POST['emailId']){
				$personalInfo->setUpdateLog('Email from '.$details['email_id'].' to '.$_POST['emailId']);
				$personalInfo->updateTableParameter ( 'email_id', $_POST ['emailId'] );
			}
			if($details['occupation_id'] != $occupationId){
				$personalInfo->setUpdateLog('Occupation from '.$details['occupation_id'].' to '.$occupationId);
				$personalInfo->updateTableParameter ( 'occupation_id', $occupationId );
			}			
			$personalInfo->commitUserGuardianUpdate ( $guardianId );
			
			$addressId = $personalInfo->getUserAddressIds ( $userId, false, $guardianType );			
			$details = $address->getTableIdDetails($addressId);
			
			if($details['street_address'] != $_POST['streetAddress1']){
				$address->setUpdateLog('House No from '.$details['street_address'].' to '.$_POST['streetAddress1']);
				$address->updateTableParameter ( 'street_address', $_POST ['streetAddress1'] );
			}
			if($details['street_address1'] != $_POST['streetAddress2']){
				$address->setUpdateLog('Street Address from '.$details['street_address1'].' to '.$_POST['streetAddress2']);
				$address->updateTableParameter ( 'street_address1', $_POST ['streetAddress2'] );
			}
			if($details['city'] != $city){
				$address->setUpdateLog('City from '.$details['city'].' to '.$city);
				$address->updateTableParameter ( 'city', $city );
			}
			if($details['state'] != $state){
				$address->setUpdateLog('State from '.$details['state'].' to '.$state);				
				$address->updateTableParameter ( 'state', $state );
			}
			if($details['country'] != $country){
				$address->setUpdateLog('Country from '.$details['country'].' to '.$country);
				$address->updateTableParameter ( 'country', $country );
			}
			if($details['pincode'] != $_POST['pincode']){
				$address->setUpdateLog('Pincode from '.$details['pincode'].' to '.$_POST['pincode']);
				$address->updateTableParameter ( 'pincode', $_POST ['pincode'] );
			}
			$address->commitAddressUpdate ( $addressId );
			$outputArray [0] = 1;
		}
		$remarks->setRemark($guardianId, $_POST['remarks'], false);
		// echo output
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
	} elseif ($_POST ['task'] == "fetchGuardianRecord") {
		$userId = $_POST ['userId'];
		$guardianType = $_POST ['guardianType'];		
		$guardianId = $personalInfo->getUserGuardianId ( $userId, $guardianType );
		$outputArray [0] = 0;
		if ($guardianId != "") {
			$details = $personalInfo->getUserGuardianIdDetails ( $guardianId );
			$outputArray [0] = $details ['id'];
			$outputArray [1] = $details ['salutation_id'];
			$outputArray [2] = $details ['first_name'];
			$outputArray [3] = $details ['middle_name'];
			$outputArray [4] = $details ['last_name'];
			$outputArray [5] = $details ['mobile_no'];
			$outputArray [6] = $details ['landline_no'];
			$outputArray [7] = $details ['email_id'];
			$outputArray [8] = $options->getOptionIdValue ( $details ['occupation_id'] );
			$outputArray [9] = $details ['occupation_id'];
			
			$remarkId = $remarks->getRemarkId($guardianId, 1);
			if(sizeof($remarkId, 0))
				$outputArray[10] = $remarks->getRemarkIdValue($remarkId[0]); 
		}
		echo json_encode ( $outputArray );
	} elseif ($_POST ['task'] == "fetchAddress") {
		$userId = $_POST ['userId'];
		$guardianType = $_POST ['guardianType'];
		
		$outputArray [0] = 0;
		$guardianId = $personalInfo->getUserGuardianId($userId, $guardianType);
		$personalInfo->getUserGuardianIdDetails ( $guardianId );
		$addressId = $personalInfo->getUserGuardianAttributeDetails ( 'address_id' );		
		if ($addressId != "") {
			$details = $address->getAddressIdDetails ( $addressId );
			
			$outputArray [0] = $details ['id'];
			$outputArray [1] = $details ['street_address'];
			$outputArray [2] = $details ['street_address1'];
			$outputArray [3] = $options->getOptionIdValue ( $details ['city'] );
			$outputArray [4] = $details ['city'];
			$outputArray [5] = $options->getOptionIdValue ( $details ['state'] );
			$outputArray [6] = $details ['state'];
			$outputArray [7] = $details ['pincode'];
			$outputArray [8] = $options->getOptionIdValue ( $details ['country'] );
			$outputArray [9] = $details ['country'];
		}
		echo json_encode ( $outputArray );
	
	} else {
		$outputArray [0] = 0;
		echo json_encode ( $outputArray );
	}
}
?>