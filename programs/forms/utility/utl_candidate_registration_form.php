<?php
require_once 'config.php';

require_once BASE_PATH . 'include/utility/class.personalInfo.php';
require_once BASE_PATH . 'include/utility/class.registration.php';
require_once BASE_PATH . 'include/utility/class.sections.php';
require_once BASE_PATH . 'include/utility/class.candidate.php';

$personalInfo = new personalInfo();
$registration = new registrationInfo();
$sections = new sections();
$candidate = new Candidate();

$sections->isRequestAuthorised4Form('LMENUL25');

if (isset ( $_POST ['task'] )) {
	if ($_POST ['task'] == 'insertRecord') {
		//create registration details		
		$error = 0;
		if($_POST['classAdmitted_val'] == "" || $_POST['registrationNumber'] == "" || $_POST['registrationDate'] == "" || $_POST['firstName'] == "" || $_POST['lastName'] == "" || $_POST['bday'] == "" || $_POST['gender'] == ""){
			++$error;
			$outputArray[0] = 0;
		}
		if($error == 0){
			$nationalityId = $_POST['nationality'] != "" ? $_POST['nationality_val'] : "";
			$religionId = $_POST['religion'] != "" ? $_POST['religion_val'] : "";
			
			$candidateId = $personalInfo->setUserDetails($_POST['salutation'], ucwords(strtolower(trim($_POST['firstName']))), ucwords(strtolower(trim($_POST['middleName']))), ucwords(strtolower(trim($_POST['lastName']))), $_POST['bday'], $_POST['gender'], '', '', $religionId, '', $nationalityId);
			
			//crate personal details
			
			$entranceId = $_POST['entranceId'];
			$sectionId = $_POST['sectionAdmitted'] != "" ? $_POST['sectionAdmitted_val'] : "";
			$houseId = $_POST['allottedHouse'] != "" ? $_POST['allottedHouse_val'] : "";
			
			$record1 = $_POST['recordShelve1'] != "" ? $_POST['recordShelve1_val'] : "";
			$record2 = $_POST['recordShelve2'] != "" ? $_POST['recordShelve2_val'] : "";
			$record3 = $_POST['recordShelve3'] != "" ? $_POST['recordShelve3_val'] : "";			
			
			$registration->setCandidateRegistrationDetails($candidateId, $entranceId, $_POST['registrationNumber'], $_POST['registrationDate'], $_POST['classAdmitted_val'], $sectionId, $houseId, $record1, $record2, $record3);
            $candidate->setCandidateClassDetails($candidateId, $_SESSION['currentClassSessionId'], $_POST['classAdmitted_val'], $sectionId, $houseId, $_POST['registrationDate'], '');
			$outputArray[0] = 1;
			$outputArray[1] = $candidateId;			
		}		
		//echo output
		echo json_encode ( $outputArray );
	} else {
		$outputArray [0] = 0;
		echo json_encode ( $outputArray );
	}
}
?>