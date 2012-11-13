<?php
require_once 'config.php';
require_once BASE_PATH . 'include/utility/class.subject.php';
require_once BASE_PATH . 'include/global/class.session.php';
require_once BASE_PATH . 'include/utility/class.candidate.php';

$candidate = new candidate();
$subject = new subjects ();
$session = new Session ();

$subject->isRequestAuthorised4Form ( 'LMENUL57' );

if (isset ( $_POST ['task'] )) {
	if ($_POST ['task'] == 'insertRecord') {
		$classId = $_POST ['classId'];
		$details = $subject->getTableIdDetails ( $classId );
		if ($session->isSessionEditable ( $details ['session_id'] )) {
			$associationId = $subject->setClassSubjectTypeEntry ( $classId, $_POST ['subject'], $_POST ['type'] );
			$mappingId = $subject->setClassSubjectMapping ( $associationId, $_POST ['subjectName_val'] );
			
			$outputArray [0] = $associationId; // id created
			$outputArray [] = $_POST ['subject']; // subject display name
			$outputArray [] = $_POST ['type'] == 'c' ? 'Compulsory' : 'Optional'; // subject                                   // type
			$outputArray [] = 1; // no of subject associated in the lookup
			$outputArray [] = $_POST ['type'] == 'c' ? 0 : 1; // whether to allow lookup addition
			$outputArray [] = 1; // whether the id is editable
		} else {
			$outputArray = 406;
		}
		echo json_encode ( $outputArray );
	} else if ($_POST ['task'] == 'search') {
		$search_type = htmlentities ( trim ( $_POST ['search_type'] ) );
		$classId = $_POST ['classId'];
		
		$details = $session->getTableIdDetails ( $classId );
		if ($session->isSessionEditable ( $details ['session_id'] ))
			$editEnabled = 1;
		else
			$editEnabled = 0;
		
		$subjectIds = $subject->getClassSubjectTypeIds ( $classId, '', $search_type );
		$outputArray [0] [0] = 1;
		$i = 0;
		foreach ( $subjectIds as $subjectId ) {
			$details = $subject->getTableIdDetails ( $subjectId );
			$outputArray [$i] [0] = $details ['id'];
			$outputArray [$i] [] = $details ['subject_name'];
			$outputArray [$i] [] = $details ['subject_type'] == 'c' ? 'Compulsory' : 'Optional';
			$outputArray [$i] [] = sizeof ( $subject->getClassSubjectMappingIds ( $details ['id'], 1 ), 0 );
			$outputArray [$i] [] = $details ['subject_type'] == 'c' ? 0 : 1;
			$outputArray [$i] [] = $editEnabled;
			++ $i;
		}
		echo json_encode ( $outputArray );
	} else if ($_POST ['task'] == 'getRecordIdDetails') {
		$id = $_POST ['id'];
		$classId = $_POST ['classId'];
		
		$details = $session->getTableIdDetails ( $classId );
		if ($session->isSessionEditable ( $details ['session_id'] ))
			$editEnabled = 1;
		else
			$editEnabled = 0;
		
		$details = $subject->getTableIdDetails ( $id );
		
		// getting any one of the subject id associated
		$mappingIds = $subject->getClassSubjectMappingIds ( $id, 1 );
		$mappingId = $mappingIds [0];
		$subjectDetails = $subject->getTableIdDetails ( $mappingId);		
		$subjectDetails = $subject->getTableIdDetails ( $subjectDetails ['subject_id'] );
		
		$outputArray [0] = $details ['id'];
		$outputArray [] = $details ['subject_name'];
		$outputArray [] = $details ['subject_type'];
		$outputArray [] = $details ['subject_type'] == 'c' ? 'Compulsory' : 'Optional';
		$outputArray [] = $subjectDetails['subject_id'];
		$outputArray [] = $subjectDetails ['subject_code'] . ' - ' . $subjectDetails ['subject_name'];
		$outputArray [] = sizeof ( $subject->getClassSubjectMappingIds ( $details ['id'], 1 ), 0 );
		$outputArray [] = $details ['last_update_date'];
		$outputArray [] = $subject->getOfficerName ( $details ['last_updated_by'] );
		$outputArray [] = $details ['creation_date'];
		$outputArray [] = $subject->getOfficerName ( $details ['created_by'] ); //10
		$outputArray [] = $details ['active'];
		$outputArray [] = $details ['subject_type'] == 'c' ? 0 : 1;
		$outputArray [] = $editEnabled;		
		$outputArray [] = $mappingId;
		echo json_encode ( $outputArray );
	} elseif ($_POST ['task'] == 'updateRecord') {		
		$id = $_POST['valueId_u'];
		$associationId = $_POST['associationId'];
		$classId = $_POST['clasId'];
		
		$details = $session->getTableIdDetails ( $classId );
		$mappingDetails = $session->getTableIdDetails($associationId);
		if ($session->isSessionEditable ( $details ['session_id'] )){
			$details = $subject->getTableIdDetails($id);
			if($details['subject_name'] != $_POST['subject_u']){
				$subject->setUpdateLog('Name from '.$details['subject_name'].' to '.$_POST['subject_u']);
				$subject->updateTableParameter('subject_name', $_POST['subject_u']);
			}
			
			if($details['subject_type'] != $_POST['type_u']){
				$subject->setUpdateLog('Type from '.$details['subject_type'].' to '.$_POST['type_u']);
				$subject->updateTableParameter('subject_type', $_POST['type_u']);
			}	
			$subject->commitClassSubjectTypeUpdate($id);			
			if($mappingDetails['subject_id'] != $_POST['subjectName_uval']){
				$subject->setUpdateLog('Subject from '.$mappingDetails['subject_id'].' to '.$_POST['subjectName_uval']);
				$subject->updateTableParameter('subject_id', $_POST['subjectName_uval']);
			}
			
			$subject->commitClassSubjectMapping($associationId);
			
			$outputArray [0] = $id; // id created
			$outputArray [] = $_POST ['subject_u']; // subject display name
			$outputArray [] = $_POST ['type_u'] == 'c' ? 'Compulsory' : 'Optional'; // subject                                   // type
			$outputArray [] = sizeof ( $subject->getClassSubjectMappingIds ( $id, 1 ), 0 );
			$outputArray [] = $_POST ['type_u'] == 'c' ? 0 : 1; // whether to allow lookup addition
			$outputArray [] = 1; // whether the id is editable				
		}else{
			$outputArray = 406;
		}		
		echo json_encode ( $outputArray );
	
	} elseif ($_POST ['task'] == 'dropRecord') {
		$id = htmlentities ( trim ( $_POST ['id'] ) );
		$subject->dropClassSubjectTypeDetails($id);
		
		$mappingIds = $candidate->getSubjectAssociationIds4SubjectTypeId($id);
		foreach ($mappingIds as $mappingId)
			$candidate->dropCandidateSubjectId($mappingId);
		
		$outputArray [0] = $id;
		echo json_encode ( $outputArray );
	} elseif ($_POST ['task'] == 'activateRecord') {
		$id = htmlentities ( trim ( $_POST ['id'] ) );
		$subject->activateClassSubjectTypeDetails($id);
		$mappingIds = $candidate->getSubjectAssociationIds4SubjectTypeId($id);
		foreach ($mappingIds as $mappingId)
			$candidate->activateCandidateSubjectId($mappingId);
		$outputArray [0] = $id;
		echo json_encode ( $outputArray );
	} else {
		$outputArray [0] = 0;
		echo json_encode ( $outputArray );
	}
}
?>