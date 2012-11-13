<?php
require_once 'config.php';

require_once BASE_PATH . 'include/utility/class.candidate.php';
require_once BASE_PATH . 'include/utility/class.subject.php';
require_once BASE_PATH . 'include/global/class.session.php';
require_once BASE_PATH . 'include/utility/class.personalInfo.php';
require_once BASE_PATH . 'include/utility/class.registration.php';
require_once BASE_PATH . 'include/utility/class.sections.php';

$candidate = new Candidate();
$session = new Session ();
$subject = new subjects();
$personalInfo = new personalInfo();
$registration = new registrationInfo();
$section = new sections();

$session->isRequestAuthorised4Form ( 'LMENUL60' );

if (isset ( $_POST ['task'] )) {
	if ($_POST ['task'] == 'search') {
		$candidateId = $_POST['candidateId'];
		$mappingId = $_POST['mappingId'];
		$mappingDetails = $session->getTableIdDetails($mappingId);				
		if ($session->isSessionEditable ( $mappingDetails ['session_id'] ))
			$editEnabled = 1;
		else
			$editEnabled = 0;				
		$i = 0;
		$outputArray[0][0] = 1;
		$subjectTypeIds = $subject->getClassSubjectTypeIds($mappingDetails['class_id'], '', 1);
		foreach ($subjectTypeIds as $subjectTypeId ) {
			$subjectTypeDetails = $subject->getTableIdDetails($subjectTypeId);
			$subjectAssociationId = $candidate->getCandidateSubject($candidateId, $subjectTypeId);
			$subjectDetails = $candidate->getTableIdDetails($subjectAssociationId[1]);
			
			$outputArray[$i][0] = $subjectTypeId;
			$outputArray[$i][] = $subjectTypeDetails['subject_name'];
			$outputArray[$i][] = $subjectTypeDetails['subject_type'] == 'c' ? 'Compulsory' : 'Optional';
			$outputArray[$i][] = $subjectDetails['subject_code'];
			$outputArray[$i][] = $subjectDetails['subject_name'];
			$outputArray[$i][] = $editEnabled;
				
			++ $i;
		}
		echo json_encode ( $outputArray );
	} else if ($_POST ['task'] == 'getRecordIdDetails') {
		$candidateId = $_POST['candidateId'];
		$mappingId = $_POST['mappingId']; //contains the information regarding the candidate and class entry
		$subjectTypeId = $_POST['id']; //the subjectype which should be associated with teh candidate
		
		$mappingDetails = $session->getTableIdDetails($mappingId);
		if ($session->isSessionEditable ( $mappingDetails ['session_id'] ))
			$editEnabled = 1;
		else
			$editEnabled = 0;
		
		$subjectTypeDetails = $session->getTableIdDetails($subjectTypeId);
		$associatedId = $candidate->getCandidateSubject($candidateId, $subjectTypeId); //the subject candidate association
		if($associatedId[0] != ''){
			$subjectDetails = $subject->getTableIdDetails($associatedId[1]);
			$details = $session->getTableIdDetails($associatedId[0]);
		}else{
			$subjectDetails = '';
			$details = '';
		}		
		$outputArray[0] = $subjectTypeId;
		$outputArray[] = $subjectTypeDetails['subject_name'];
		$outputArray[] = $subjectTypeDetails['subject_type'] == 'c' ? 'Compulsory' : 'Optional';
		$outputArray[] = $subjectDetails['subject_name'];
		$outputArray[] = $subjectDetails['subject_code'];		
		$outputArray[] = $details['last_update_date'];
		$outputArray[] = $session->getOfficerName($details['last_updated_by']);
		$outputArray[] = $details['creation_date'];
		$outputArray[] = $session->getOfficerName($details['created_by']);
		$outputArray[] = $mappingDetails['active'];
		$outputArray[] = $editEnabled;	
		
		echo json_encode ( $outputArray );
	}  elseif($_POST['task'] == 'getCandidateClassDetails'){
		$candidateId = $_POST['candidateId'];
		$mappingId = $_POST['mappingId'];		
		
		$personalInfo->getUserIdDetails($candidateId);
		$registrationDetails = $registration->getTableIdDetails($registration->getCandidateRegistrationId($candidateId));
		$mappingDetails = $session->getTableIdDetails($mappingId);
		$sessionDetails = $session->getTableIdDetails($mappingDetails['session_id']);
		
		$outputArray[0] = $personalInfo->getUserName();
		$outputArray[] = $registrationDetails['registration_number'];
		$outputArray[] = $section->getClassName4Section($mappingDetails['section_id']);
		$outputArray[] = $section->getSectionName($mappingDetails['section_id']);
		$outputArray[] = $sessionDetails['session_name'];
		
		echo json_encode($outputArray);		
	}elseif($_POST['task'] == 'getRecordEditDetails'){
		$candidateId = $_POST['candidateId'];
		$mappingId = $_POST['mappingId']; //contains the information regarding the candidate and class entry
		$subjectTypeId = $_POST['id']; //the subjectype which should be associated with teh candidate
		
		$subjectTypeDetails = $session->getTableIdDetails($subjectTypeId);
		$assocationId = $candidate->getCandidateSubject($candidateId, $subjectTypeId);
		
		$outputArray[0] = $subjectTypeDetails['subject_name'];
		$outputArray[] = $subjectTypeDetails['subject_type'] == 'c' ? 'Compulsory' : 'Optional';
		$outputArray[] = $assocationId[1];		
		$subjectMapIds = $subject->getClassSubjectMappingIds($subjectTypeId, 1);
		$value = '';
		foreach ($subjectMapIds as $id){
			$mappingDetails = $subject->getTableIdDetails($id);
			$details = $subject->getTableIdDetails($mappingDetails['subject_id']);
			if($details['id'] == $assocationId[1])
				$value .= "<option value=\"".$details['id']."\" selected=\"selected\">".$details['subject_code']." ".$details['subject_name']."</option>";
			else
				$value .= "<option value=\"".$details['id']."\">".$details['subject_code']." ".$details['subject_name']."</option>";
		}		
		$outputArray[] = $value;
		
		echo json_encode($outputArray);		
	}elseif($_POST['task'] == 'updateRecord'){
		$candidateId = $_POST['candidateId'];
		$mappingId = $_POST['mappingId'];
		$details = $session->getTableIdDetails($mappingId);
		$subjectTypeId = $_POST['valueId_u'];
				
		$associationId = $candidate->setCandidateSubject($candidateId, $details['session_id'], $subjectTypeId, $_POST['subject']);		
		$subjectTypeDetails = $subject->getTableIdDetails($subjectTypeId);		
		$subjectDetails = $candidate->getTableIdDetails($_POST['subject']);
			
		$outputArray[0] = $subjectTypeId;
		$outputArray[] = $subjectTypeDetails['subject_name'];
		$outputArray[] = $subjectTypeDetails['subject_type'] == 'c' ? 'Compulsory' : 'Optional';
		$outputArray[] = $subjectDetails['subject_code'];
		$outputArray[] = $subjectDetails['subject_name'];
		$outputArray[] = 1;
		
		echo json_encode($outputArray);		
	}else {
		$outputArray [0] = 0;
		echo json_encode ( $outputArray );
	}
}
?>