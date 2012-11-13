<?php

require_once 'config.php';

require_once BASE_PATH . 'include/utility/class.personalInfo.php';
require_once BASE_PATH . 'include/global/class.options.php';
require_once BASE_PATH . 'include/utility/class.employeeRegistration.php';
require_once BASE_PATH . 'include/utility/class.address.php';

$personalInfo = new personalInfo ();
$registration = new employeeRegistration ();
$address = new address ();
$options = new options ();

$options->isRequestAuthorised4Form('LMENUL38');

if (isset ( $_POST ['task'] )) {
    if ($_POST ['task'] == 'getPersonalInformation') {
        $userId = $_POST ['userId'];
        $details = $personalInfo->getUserIdDetails($userId);

        $outputArray[0] = $details['id'];
        $outputArray[] = $details['salutation_id'];
        $outputArray[] = $details['first_name'];
        $outputArray[] = $details['middle_name'];
        $outputArray[] = $details['last_name'];
        $outputArray[] = $details['dob'];
        $outputArray[] = $details['gender'];
        $outputArray[] = $details['marital_status_id'];
        $outputArray[] = $details['religion'];
        $outputArray[] = $options->getOptionIdValue($details['religion']);
        $outputArray[] = $details['nationality'];
        $outputArray[] = $options->getOptionIdValue($details['nationality']);
        $outputArray[] = $details['personal_email_id'];
        $outputArray[] = $details['official_email_id'];
        $outputArray[] = $details['mobile_no'];
        $outputArray[] = $details['landline_no'];

        echo json_encode ( $outputArray );
    } elseif($_POST['task'] == "getAddressDetails"){
        $userId = $_POST ['userId'];
        $personalDetails = $personalInfo->getUserIdDetails($userId);
        $details = $address->getAddressIdDetails($personalDetails['address_id']);

        $outputArray[0] = $details['id'];
        $outputArray[] = $details['street_address'];
        $outputArray[] = $details['street_address1'];
        $outputArray[] = $details['city'];
        $outputArray[] = $options->getOptionIdValue($details['city']);
        $outputArray[] = $details['state'];
        $outputArray[] = $options->getOptionIdValue($details['state']);
        $outputArray[] = $details['pincode'];
        $outputArray[] = $details['country'];
        $outputArray[] = $options->getOptionIdValue($details['country']);

        echo json_encode($outputArray);
    }elseif($_POST['task'] == "getRegistrationDetails"){
        $userId = $_POST ['userId'];
        $details = $registration->getRegistrationIdDetails($registration->getEmployeeRegistrationId($userId));

        $outputArray[0] = $details['id'];
        $outputArray[] = $details['record1_id'];
        $outputArray[] = $options->getOptionIdValue($details['record1_id']);
        $outputArray[] = $details['record2_id'];
        $outputArray[] = $options->getOptionIdValue($details['record2_id']);
        $outputArray[] = $details['record3_id'];
        $outputArray[] = $options->getOptionIdValue($details['record3_id']);
        $outputArray[] = $details['department_id'];
        $outputArray[] = $options->getOptionIdValue($details['department_id']);
        $outputArray[] = $details['employee_type'];
        $outputArray[] = $options->getOptionIdValue($details['employee_type']);
        
        echo json_encode($outputArray);
    }elseif($_POST['task'] == "updateEmployeeRecord"){
        $userId = $_POST ['userId'];
        $details = $personalInfo->getUserIdDetails($userId);
        $addressDetails = $address->getAddressIdDetails($details['address_id']);
        $registrationDetails = $registration->getRegistrationIdDetails($registration->getEmployeeRegistrationId($userId));

        //processing update of the personal details form
        if($details['salutation_id'] != $_POST['salutation']){
        	$personalInfo->setUpdateLog('Salutation from '.$details['salutation_id'].' to '.$_POST['salutation']);
        	$personalInfo->updateTableParameter('salutation_id', $_POST['salutation']);
        }
        if($details['first_name'] != $_POST['firstName']){
        	$personalInfo->setUpdateLog('First Name from '.$details['first_name'].' to '.$_POST['firstName']);
        	$personalInfo->updateTableParameter('first_name', $_POST['firstName']);
        }
        if($details['middle_name'] != $_POST['middleName']){
        	$personalInfo->setUpdateLog('Middle Name from '.$details['middle_name'].' to '.$_POST['middleName']);
        	$personalInfo->updateTableParameter('middle_name', $_POST['middleName']);
        }
        if($details['last_name'] != $_POST['lastName']){
        	$personalInfo->setUpdateLog('Last Name from '.$details['last_name'].' to '.$_POST['lastName']);
        	$personalInfo->updateTableParameter('last_name', $_POST['lastName']);
        }
        if($details['dob'] != $_POST['bday']){
        	$personalInfo->setUpdateLog('DOB from '.$details['dob'].' to '.$_POST['bday']);
        	$personalInfo->updateTableParameter('dob', $_POST['bday']);
        }
        if($details['gender'] != $_POST['gender']){
        	$personalInfo->setUpdateLog('Gender from '.$details['gender'].' to '.$_POST['gender']);
        	$personalInfo->updateTableParameter('gender', $_POST['gender']);
        }
        if($details['marital_status_id'] != $_POST['marital']){
        	$personalInfo->setUpdateLog('Marital Status from '.$details['marital_status_id'].' to '.$_POST['marital']);
        	$personalInfo->updateTableParameter('marital_status_id', $_POST['marital']);
        }
        if($details['religion'] != $_POST['religion']){
        	$personalInfo->setUpdateLog('Religion from '.$details['religion'].' to '.$_POST['religion']);
        	$personalInfo->updateTableParameter('religion', $options->setNewOptionValue($_POST['religion'], 'RELIG'));
        }
        if($details['nationality'] != $_POST['nationality']){
        	$personalInfo->setUpdateLog('Nationality from '.$details['nationality'].' to '.$_POST['nationality']);
        	$personalInfo->updateTableParameter('nationality', $options->setNewOptionValue($_POST['nationality'], 'NATON'));
        }
        if($details['personal_email_id'] != $_POST['personalEmail']){
        	$personalInfo->setUpdateLog('Personal email from '.$details['personal_email_id'].' to '.$_POST['personalEmail']);
        	$personalInfo->updateTableParameter('personal_email_id', $_POST['personalEmail']);
        }
        if($details['official_email_id'] != $_POST['officialEmail']){
        	$personalInfo->setUpdateLog('Official Email from '.$details['official_email_id'].' to '.$_POST['officialEmail']);
        	$personalInfo->updateTableParameter('official_email_id', $_POST['officialEmail']);
        }
        if($details['mobile_no'] != $_POST['contactNo']){
        	$personalInfo->setUpdateLog('Mobile No from '.$details['mobile_no'].' to '.$_POST['contactNo']);
        	$personalInfo->updateTableParameter('mobile_no', $_POST['contactNo']);
        }
        if($details['landline_no'] != $_POST['aContactNo']){
        	$personalInfo->setUpdateLog('Alternate No from '.$details['landline_no'].' to '.$_POST['aContactNo']);
        	$personalInfo->updateTableParameter('landline_no', $_POST['aContactNo']);
        }
        $personalInfo->commitUserDetailsUpdate($userId);

        //processing update of the address form
        if($details['street_address'] != $_POST['streetAddress1']){
        	$address->setUpdateLog('House Address from '.$details['street_address'].' to '.$_POST['streetAddress1']);
        	$address->updateTableParameter('street_address', $_POST['streetAddress1']);
        }
        if($details['street_address1'] != $_POST['streetAddress2']){
        	$address->setUpdateLog('Street Address from '.$details['street_address1'].' to '.$_POST['streetAddress2']);
        	$address->updateTableParameter('street_address1', $_POST['streetAddress2']);
        }
        if($details['city'] != $_POST['city']){
        	$address->setUpdateLog('City from '.$details['city'].' to '.$_POST['city']);
        	($address->updateTableParameter('city', $options->setNewOptionValue($_POST['city'], 'CITYS')));
        }
        if($details['state'] != $_POST['state']){
        	$address->setUpdateLog('State from '.$details['state'].' to '.$_POST['state']);
        	($address->updateTableParameter('state', $options->setNewOptionValue($_POST['state'], 'STATE')));
        }
        if($details['country'] != $_POST['country']){
        	$address->setUpdateLog('Country from '.$details['country'].' to '.$_POST['country']);
        	($address->updateTableParameter('country', $options->setNewOptionValue($_POST['country'], 'CNTRY')));
        }
        if($details['pincode'] != $_POST['pincode']){
        	$address->setUpdateLog('Pincode from '.$details['pincode'].' to '.$_POST['pincode']);
        	$address->updateTableParameter('pincode', $_POST['pincode']);
        }
        $address->commitAddressUpdate($details['address_id']);

        //processing update of the registration details
        if($details['record1_id'] != $_POST['recordShelve1']){
        	$registration->setUpdateLog('Record1 from '.$details['record1_id'].' to '.$_POST['recordShelve1']);
        	($registration->updateTableParameter('record1_id', $options->setNewOptionValue($_POST['recordShelve1'], 'SHLVE')));        	
        }
        if($details['record2_id'] != $_POST['recordShelve2']){
        	$registration->setUpdateLog('Record2 from '.$details['record2_id'].' to '.$_POST['recordShelve2']);        	
        	($registration->updateTableParameter('record2_id', $options->setNewOptionValue($_POST['recordShelve2'], 'SHLVE')));
        }
        if($details['record3_id'] != $_POST['recordShelve3']){
        	$registration->setUpdateLog('Record3 from '.$details['record3_id'].' to '.$_POST['recordShelve3']);
        	($registration->updateTableParameter('record3_id', $options->setNewOptionValue($_POST['recordShelve3'], 'SHLVE')));
        }
        if($details['department_id'] != $_POST['department_val']){
        	$registration->setUpdateLog('Department from '.$details['department_id'].' to '.$_POST['department_val']);
        	$registration->updateTableParameter('department_id', $_POST['department_val']);
        }
        if($details['employee_type'] != $_POST['employeeType_val']){
        	$registration->setUpdateLog('Emp Type from '.$details['employee_type'].' to '.$_POST['employeeType_val']);
        	$registration->updateTableParameter('employee_type', $_POST['employeeType_val']);
        }
        if($details['employee_code'] != $_POST['employeeCode1']){
        	$registration->setUpdateLog('Emp Code from '.$details['employee_code'].' to '.$_POST['employeeCode1']);
        	$registration->updateTableParameter('employee_code', $_POST['employeeCode1']);
        }
        $registration->commitRegistrationDetailsUpdate($registrationDetails['id']);

        $outputArray[0] = 1;
        echo json_encode($outputArray);
    }else {
        $outputArray [0] = 0;
        echo json_encode ( $outputArray );
    }
}
?>