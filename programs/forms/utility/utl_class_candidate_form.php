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

$session->isRequestAuthorised4Form ( 'LMENUL59' );

if (isset ( $_POST ['task'] )) {
	if ($_POST ['task'] == 'search') {
		$search_type = htmlentities ( trim ( $_POST ['search_type'] ) );
		$classId = $_POST['classId'];		
		$details = $session->getTableIdDetails ( $classId );		
		if ($session->isSessionEditable ( $details ['session_id'] ))
			$editEnabled = 1;
		else
			$editEnabled = 0;		
		$i = 0;
		$mappingIds = $candidate->getClassCandidateAssociationIds($classId, $search_type);
		foreach ( $mappingIds as $mappingId ) {
			$details = $session->getTableIdDetails($mappingId);
			$candidateId = $details['candidate_id'];			
			$candidateDetails = $personalInfo->getUserIdDetails($candidateId);			
			
			$outputArray [$i][] = $mappingId;
			$outputArray [$i][] = $candidateId;
			$outputArray [$i][] = $registration->getCandidateRegistrationNumber($candidateId);
			$outputArray [$i][] = $personalInfo->getUserName();
			$outputArray [$i][] = $candidateDetails['gender'] == 'M' ? 'Male' : 'Female';
			$outputArray [$i][] = $section->getSectionName($details['section_id']);	
			$outputArray [$i][] = $editEnabled; // whether the id is editable
			++ $i;
		}
		echo json_encode ( $outputArray );
	} elseif ($_POST ['task'] == 'searchSection') {
        $search_type = htmlentities ( trim ( $_POST ['search_type'] ) );
        $sectionId = $_POST['sectionId'];
        $details = $session->getTableIdDetails ( $sectionId );
        if ($session->isSessionEditable ( $details ['session_id'] ))
            $editEnabled = 1;
        else
            $editEnabled = 0;
        $i = 0;
        $mappingIds = $candidate->getSectionCandidateAssociationIds($sectionId, $search_type);
        foreach ( $mappingIds as $mappingId ) {
            $details = $session->getTableIdDetails($mappingId);
            $candidateId = $details['candidate_id'];
            $candidateDetails = $personalInfo->getUserIdDetails($candidateId);

            $outputArray [$i][] = $mappingId;
            $outputArray [$i][] = $candidateId;
            $outputArray [$i][] = $registration->getCandidateRegistrationNumber($candidateId);
            $outputArray [$i][] = $personalInfo->getUserName();
            $outputArray [$i][] = $candidateDetails['gender'] == 'M' ? 'Male' : 'Female';
            $outputArray [$i][] = $section->getSectionName($details['section_id']);
            $outputArray [$i][] = $editEnabled; // whether the id is editable
            ++ $i;
        }
        echo json_encode ( $outputArray );
    }else if ($_POST ['task'] == 'getRecordIdDetails') {
		$id = $_POST ['candidateId'];
		$classId = $_POST ['classId'];		
		$type = $_POST['type'];
		$details = $session->getTableIdDetails ( $classId );		
		if ($session->isSessionEditable ( $details ['session_id'] ))
			$editEnabled = 1;
		else
			$editEnabled = 0;
		$subjectTypeIds = $subject->getClassSubjectTypeIds($classId, $type, 1);
		foreach ($subjectTypeIds as $subjectTypeId){
			$subjectId = $candidate->getCandidateSubject($id, $subjectTypeId);
			if($subjectId[0] != ''){
				$details = $subject->getTableIdDetails($subjectId[1]);
				$outputArray[0] .= $details['subject_name']." (".$details['subject_code'].") | ";
			}			
		}		
		$outputArray[1] = $editEnabled;
		echo json_encode ( $outputArray );
	}  else {
		$outputArray [0] = 0;
		echo json_encode ( $outputArray );
	}
}
?>