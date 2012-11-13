<?php
require_once 'config.php';

require_once BASE_PATH . 'include/utility/class.personalInfo.php';
require_once BASE_PATH . 'include/global/class.options.php';
require_once BASE_PATH . 'include/utility/class.registration.php';
require_once BASE_PATH . 'include/utility/class.address.php';
require_once BASE_PATH . 'include/utility/class.remarks.php';
require_once BASE_PATH . 'include/utility/class.educationHistory.php';
require_once BASE_PATH . 'include/utility/class.institute.php';

$personalInfo = new personalInfo ();
$options = new options ();
$registration = new registrationInfo ();
$remarks = new remarks ();
$address = new address ();
$education = new educationHistory ();
$institute = new institute ();

$options->isRequestAuthorised4Form('LMENUL34');

if (isset ( $_POST ['task'] )) {
	if ($_POST ['task'] == 'insertRecord') {
		$candidateId = $_POST ['candidateId'];
		$outputArray [0] = 0;
		$educationId = $education->setUserEducationDetails ( $candidateId, $_POST ['institute_val'], $_POST ['year'], $_POST ['level'], $_POST ['score'], $_POST ['markType'] );
		if ($educationId) {
			$remarks->setRemark ( $educationId, $_POST ['comments'], false );
			
			$details = $education->getEducationHistoryIdDetails ( $educationId );
			$instituteDetails = $institute->getInstituteIdDetails ( $details ['institute_id'] );
			$outputArray [0] = $educationId;
			$outputArray [1] = $details ['level'];
			$outputArray [2] = $instituteDetails ['institute_name'];
			$outputArray [3] = $details ['year'];
			$outputArray [4] = $details ['score'];
		}
		echo json_encode ( $outputArray );
	} elseif ($_POST ['task'] == 'searchDetails') {
		$candidateId = trim ( $_POST ['candidateId'] );
		$searchType = $_POST ['search_type'];
		$ids = $education->getUserEducationIds ( $candidateId, $searchType );
		$outputArray [0] [0] = 1;
		$i = 0;
		foreach ( $ids as $id ) {
			$details = $education->getEducationHistoryIdDetails ( $id );
			$instituteDetails = $institute->getInstituteIdDetails ( $details ['institute_id'] );
			$outputArray [$i] [0] = $id;
			$outputArray [$i] [1] = $details ['level'];
			$outputArray [$i] [2] = $instituteDetails ['institute_name'];
			$outputArray [$i] [3] = $details ['year'];
			$outputArray [$i] [4] = $details ['score'];
			++ $i;
		}
		echo json_encode ( $outputArray );
	} elseif ($_POST ['task'] == 'getRecordIdDetails') {
		$educationId = $_POST ['id'];
		$details = $education->getEducationHistoryIdDetails ( $educationId );
		$instituteDetails = $institute->getInstituteIdDetails ( $details ['institute_id'] );
		
		$outputArray [0] = $details ['id'];
		$outputArray [1] = $instituteDetails ['institute_name'];
		$outputArray [2] = $details ['institute_id'];
		$outputArray [3] = $options->getOptionIdValue ( $instituteDetails ['university_id'] );
		$outputArray [4] = $details ['level'];
		$outputArray [5] = $details ['year'];
		$outputArray [6] = $details ['score'];
		$outputArray [7] = $options->getOptionIdValue ( $details ['scoring_type'] );
		$outputArray [8] = $details ['last_update_date'];
		$outputArray [9] = $institute->getOfficerName ( $details ['last_updated_by'] );
		$outputArray [10] = $details ['creation_date'];
		$outputArray [11] = $institute->getOfficerName ( $details ['created_by'] );
		$outputArray [12] = $details ['active'];
		$outputArray [13] = $details ['scoring_type'];
		$outputArray [14] = $remarks->getGenericRemarkValue($educationId);
		
		echo json_encode ( $outputArray );
	} elseif ($_POST ['task'] == 'updateRecord') {
		$candidateId = trim ( $_POST ['candidateId'] );
		$educationId = trim ( $_POST ['recordId_u'] );
		
		$details = $education->getEducationHistoryIdDetails ( $educationId );
		
		if($details['institute_id'] != $_POST['institute_uval']){
			$education->setUpdateLog('Institute from '.$details['institute_id'].' to '.$_POST['institute_uval']);
			$education->updateTableParameter ( 'institute_id', $_POST ['institute_uval'] );
		}
		if($details['level'] != $_POST['level_u']){
			$education->setUpdateLog('Level from '.$details['level'].' to '.$_POST['level_u']);
			$education->updateTableParameter ( 'level', $_POST ['level_u'] );
		}
		if($details['year'] != $_POST['year_u']){
			$education->setUpdateLog('Year from '.$details['year'].' to '.$_POST['year_u']);
			$education->updateTableParameter ( 'year', $_POST ['year_u'] );
		}
		if($details['score'] != $_POST['score_u']){
			$education->setUpdateLog('Score from '.$details['score'].' to '.$_POST['score_u']);
			$education->updateTableParameter ( 'score', $_POST ['score_u'] );
		}
		if($details['scoring_type'] != $_POST['markType_u']){
			$education->setUpdateLog('Marking Type from '.$details['scoring_type'].' to '.$_POST['markType_u']);
			$education->updateTableParameter ( 'scoring_type', $_POST ['markType_u'] );
		}	
		
		$outputArray [0] = 0;
		if ($education->commitEducationDetailsUpdate ( $educationId )) {
			$remarks->setRemark ( $educationId, $_POST ['comments_u'], false );
			$details = $education->getEducationHistoryIdDetails ( $educationId );
			$instituteDetails = $institute->getInstituteIdDetails ( $details ['institute_id'] );
			$outputArray [0] = $educationId;
			$outputArray [1] = $details ['level'];
			$outputArray [2] = $instituteDetails ['institute_name'];
			$outputArray [3] = $details ['year'];
			$outputArray [4] = $details ['score'];
		}
		echo json_encode ( $outputArray );
	} elseif ($_POST ['task'] == "fetchRecord") {
		$details = $personalInfo->getUserIdDetails ( $_POST ['candidateId'] );
		$details1 = $registration->getRegistrationIdDetails ( $registration->getCandidateRegistrationId ( $_POST ['candidateId'] ) );
		
		if ($details ['id'] == "")
			$outputArray [0] = 0;
		else {
			$outputArray [0] = $details ['id'];
			$outputArray [1] = $options->getOptionIdValue ( $details ['salutation_id'] ) . " " . $details ['last_name'] . ", " . $details ['first_name'] . " " . $details ['middle_name'];
			$outputArray [2] = $details1 ['registration_number'];
			$outputArray [3] = $details ['official_email_id'];
			$outputArray [4] = $options->getDisplayDate($details ['dob']);
		}
		echo json_encode ( $outputArray );
	} elseif ($_POST ['task'] == 'dropRecord') {
		$id = htmlentities ( trim ( $_POST ['id'] ) );
		$education->dropEducationHistory ( $id );
		$outputArray [0] = $id;
		echo json_encode ( $outputArray );
	} elseif ($_POST ['task'] == 'activateRecord') {
		$id = htmlentities ( trim ( $_POST ['id'] ) );
		$education->activateEducationHistory ( $id );
		$outputArray [0] = $id;
		echo json_encode ( $outputArray );
	} else {
		$outputArray [0] = 0;
		echo json_encode ( $outputArray );
	}
}
?>