<?php
error_reporting(1);
require_once 'config.php';

require_once BASE_PATH . 'include/utility/class.personalInfo.php';
require_once BASE_PATH . 'include/global/class.options.php';
require_once BASE_PATH . 'include/utility/class.registration.php';
require_once BASE_PATH . 'include/utility/class.address.php';
require_once BASE_PATH . 'include/utility/class.remarks.php';
require_once BASE_PATH . 'include/utility/class.sections.php';
require_once BASE_PATH . 'include/utility/class.educationHistory.php';
require_once BASE_PATH . 'include/utility/class.institute.php';

$personalInfo = new personalInfo ();
$registration = new registrationInfo ();
$address = new address ();
$remarks = new remarks ();
$sections = new sections();
$options = new options();
$education = new educationHistory();
$institute = new institute();

$options->isRequestAuthorised4Form('LMENUL35');

if (isset ( $_POST ['task'] )) {
	if ($_POST ['task'] == 'registrationDetails') {
		$candidateId = $_POST['candidateId'];		
		$details = $registration->getRegistrationIdDetails($registration->getCandidateRegistrationId($candidateId));
		
		$outputArray[0] = $details['id'];
		$outputArray[] = $details['registration_number'];
		$outputArray[] = $details['entrance_id'];
		$outputArray[] = $sections->getClassName4Section($details['section_id']) ." - ". $sections->getSectionName($details['section_id']);
		$outputArray[] = $personalInfo->getDisplayDate($details['registration_date']);
		$outputArray[] = $options->getOptionIdValue($details['house_id']);
		$outputArray[] = $options->getOptionIdValue($details['record1_id']);
		$outputArray[] = $options->getOptionIdValue($details['record2_id']);
		$outputArray[] = $options->getOptionIdValue($details['record3_id']);

		echo json_encode($outputArray);
	} elseif($_POST['task'] == "gaurdianDetails"){
		$candidateId = $_POST['candidateId'];
		$gaurdianType = $_POST['gaurdianType'];
		
		$gaurdianId = $personalInfo->getUserGuardianId($candidateId, $gaurdianType);
		if($gaurdianId == ""){
			$outputArray[0] = 1;			
		}else{
			$details = $personalInfo->getUserGuardianIdDetails($gaurdianId);
			$outputArray[0] = $options->getOptionIdValue($details['salutation_id']).". ".$details['last_name'].", ".$details['first_name']." ".$details['last_name'];
			$outputArray[] = $options->getOptionIdValue($gaurdianType);
			$outputArray[] = $details['email_id'];
			$outputArray[] = $options->getOptionIdValue($details['occupation_id']);
			$outputArray[] = $details['mobile_no'];
			$outputArray[] = $details['landline_no'];
			$outputArray[] = $address->getAddressDisplay($details['address_id']);			
		}
		echo json_encode($outputArray);
	}elseif($_POST['task'] == "educationDetails"){
		$candidateId = $_POST['candidateId'];
		$ids = $education->getUserEducationIds($candidateId, 1);
		$outputArray[0][0] = 1;
		$i = 0;
		foreach ($ids as $id){
			$details = $education->getEducationHistoryIdDetails($id);
			$outputArray[$i][0] = $details['level'];
			$outputArray[$i][] = $details['year'];
			$outputArray[$i][] = $institute->getInstituteName($details['institute_id']);
			$outputArray[$i][] = $details['score'];
			$outputArray[$i][] = $options->getOptionIdValue($details['scoring_type']);
			++$i;				
		}
		echo json_encode($outputArray);
	}elseif($_POST['task'] == "getCandidateDetails"){
		$candidateId = $_POST['candidateId'];
		$details = $personalInfo->getUserIdDetails($candidateId);
		$registrationDetails = $registration->getRegistrationIdDetails($registration->getCandidateRegistrationId($candidateId));
		
		$outputArray[] = $personalInfo->getUserName();
		$outputArray[] = $registrationDetails['registration_number'];		
		$outputArray[] = $options->getDisplayDate($details['dob']);
		$outputArray[] = $details['gender'] == 'M' ? 'Male' : 'Female';
		$outputArray[] = $details['personal_email_id'];
		$outputArray[] = $details['official_email_id'];
		$outputArray[] = $details['mobile_no'];
		$outputArray[] = $details['landline_no'];		
		$outputArray[] = $address->getAddressDisplay($details['address_id']);
		
		echo json_encode($outputArray);
	}else {
		$outputArray [0] = 0;
		echo json_encode ( $outputArray );
	}
}
?>