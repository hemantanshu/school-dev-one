<?php

require_once 'config.php';
require_once BASE_PATH . 'include/global/class.body.php';
require_once BASE_PATH . 'include/global/class.session.php';
require_once BASE_PATH . 'include/utility/class.subject.php';
require_once BASE_PATH . 'include/utility/class.candidate.php';
require_once BASE_PATH . 'include/utility/class.personalInfo.php';
require_once BASE_PATH . 'include/utility/class.registration.php';
require_once BASE_PATH . 'include/exam/class.result.php';

$body = new body ();
$session = new Session();
$result = new Result();

$subject = new subjects();
$candidate = new Candidate();
$personalInfo = new personalInfo();
$registration = new registrationInfo();



$sectionIds = $result->getSectionIds();
foreach($sectionIds as $sectionId){	
	$sectionDetails = $body->getTableIdDetails($sectionId);
	$classId = $sectionDetails['class_id'];
	$classDetails = $body->getTableIdDetails($sectionDetails['class_id']);
	$classDetails = $body->getTableIdDetails($classDetails['class_id']);
	
	$candidateAssociationIds = $candidate->getSectionCandidateAssociationIds($sectionId, 1);
	$subjectTypeIds = $subject->getClassSubjectTypeIds($classId, 'o', 1);
	$optionalSubjectCount = sizeof($subjectTypeIds);
	$i = 0;
	foreach($subjectTypeIds as $subjectTypeId){
		$details = $subject->getTableIdDetails($subjectTypeId);
		$subjectTypeDetails[$i] = $details['subject_name'];
		$subjectOptionIds = $subject->getClassSubjectMappingIds($subjectTypeId, 1);
		$j = 0;
		foreach($subjectOptionIds as $subjectOptionId){
			$details = $subject->getTableIdDetails($subjectOptionId);
			$subjectDetails = $subject->getTableIdDetails($details['subject_id']);
	
			$subjectOption[$i][$j][0] = $subjectDetails['subject_code'];
			$subjectOption[$i][$j][] = $subjectDetails['subject_name'];
			$subjectOption[$i][$j][] = $subjectDetails['id'];
	
			++$j;
		}
		++$i;
	}
	
	echo "
	<table border=\"1\" cellpadding=\"0\" cellspacing=\"0\">
    <tr>
        <th>SN</th>
        <th>Class</th>
        <th>Name</th>";
	
        foreach ($subjectTypeDetails as $subjectTypeName){
            echo "<th>".$subjectTypeName."</th>";
        }
    echo "    
    </tr>";
    
    $j = 0;
    foreach ($candidateAssociationIds as $candidateAssociationId){
    	++$j;
        $details = $personalInfo->getTableIdDetails($candidateAssociationId);
        $candidateId = $details['candidate_id'];
        $personalInfo->getUserIdDetails($candidateId);
        $registrationDetails = $registration->getTableIdDetails($registration->getCandidateRegistrationId($candidateId));

        echo "<tr>";
        echo "<td>".$j."</td>";
        echo "<td>".$classDetails['class_name']." ".$sectionDetails['section_name']."</td>";
        echo "<td>".$personalInfo->getUserName()."</td>";
        
        //creating the options for the subject

        for($i = 0; $i < $optionalSubjectCount; ++$i){
            $candidateSubjectId = $candidate->getCandidateSubject($candidateId, $subjectTypeIds[$i]);
            $subjectDetails = $registration->getTableIdDetails($candidateSubjectId[1]);
            echo "<td>".$subjectDetails['subject_code']." ".$subjectDetails['subject_name']."</td>";
        }
        echo "</tr>";
    }
    echo "</table><br /><hr /><br />";
		
	
	
}