<?php
require_once 'config.php';
require_once BASE_PATH . 'include/utility/class.subject.php';
require_once BASE_PATH . 'include/global/class.session.php';

$subject = new subjects ();
$session = new Session ();

$subject->isRequestAuthorised4Form ( 'LMENUL58' );

if (isset ( $_POST ['task'] )) {
	if ($_POST ['task'] == 'insertRecord') {
		$classId = $_POST ['classId'];
		$classSubjectId = $_POST['classSubjectId'];
		$details = $subject->getTableIdDetails ( $classId );
		if ($session->isSessionEditable ( $details ['session_id'] )) {
			$mappingId = $subject->setClassSubjectMapping($classSubjectId, $_POST['subjectName_val']);
			$subjectDetails = $subject->getTableIdDetails($_POST['subjectName_val']);
			$outputArray [0] = $mappingId; // id created
			$outputArray [] = $subjectDetails['subject_code'];
			$outputArray [] = $subjectDetails['subject_name'];
			$outputArray [] = 1; // whether the id is editable
		} else {
			$outputArray = 406;
		}
		echo json_encode ( $outputArray );
	} else if ($_POST ['task'] == 'search') {
		$search_type = htmlentities ( trim ( $_POST ['search_type'] ) );
		$classSubjectId = $_POST['classSubjectId'];		
		$details = $session->getTableIdDetails ( $classId );
		
		if ($session->isSessionEditable ( $details ['session_id'] ))
			$editEnabled = 1;
		else
			$editEnabled = 0;		
		$subjectIds = $subject->getClassSubjectMappingIds($classSubjectId, $search_type);
		$outputArray [0] [0] = 1;
		$i = 0;
		foreach ( $subjectIds as $subjectId ) {
			$details = $subject->getTableIdDetails ( $subjectId );			
			$subjectDetails = $subject->getTableIdDetails($details['subject_id']);
			$outputArray [$i][0] = $subjectId; // id created
			$outputArray [$i][] = $subjectDetails['subject_code'];
			$outputArray [$i][] = $subjectDetails['subject_name'];
			$outputArray [$i][] = $editEnabled; // whether the id is editable
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
		$subjectDetails = $subject->getTableIdDetails($details['subject_id']);
		// getting any one of the subject id associated
		
		$outputArray [0] = $details ['id'];
		$outputArray [] = $subjectDetails['subject_id'];
		$outputArray [] = $subjectDetails['subject_code'];
		$outputArray [] = $subjectDetails['subject_name'];
		$outputArray [] = $details ['last_update_date'];
		$outputArray [] = $subject->getOfficerName ( $details ['last_updated_by'] );
		$outputArray [] = $details ['creation_date'];
		$outputArray [] = $subject->getOfficerName ( $details ['created_by'] ); //10
		$outputArray [] = $details ['active'];
		$outputArray [] = $editEnabled;		
		echo json_encode ( $outputArray );
	} elseif ($_POST ['task'] == 'updateRecord') {		
		$id = $_POST['valueId_u'];
		$classId = $_POST['clasId'];		
		$details = $session->getTableIdDetails ( $classId );		
		if ($session->isSessionEditable ( $details ['session_id'] )){
			$details = $subject->getTableIdDetails($id);
			if($details['subject_id'] != $_POST['subjectName_uval']){
				$subject->setUpdateLog('Subject from '.$details['subject_id'].' to '.$_POST['subjectName_uval']);
				$subject->updateTableParameter('subject_id', $_POST['subjectName_uval']);
			}		
			$subject->commitClassSubjectMapping($id);
			
			$details = $subject->getTableIdDetails ( $id );
			$subjectDetails = $subject->getTableIdDetails($details['subject_id']);
			$outputArray [0] = $id; // id created
			$outputArray [] = $subjectDetails['subject_code'];
			$outputArray [] = $subjectDetails['subject_name'];
			$outputArray [] = 1; // whether the id is editable				
		}else{
			$outputArray = 406;
		}		
		echo json_encode ( $outputArray );
	
	} elseif ($_POST ['task'] == 'dropRecord') {
		$id = htmlentities ( trim ( $_POST ['id'] ) );
		$subject->dropClassSubjectMapping($id);
		$outputArray [0] = $id;
		echo json_encode ( $outputArray );
	} elseif ($_POST ['task'] == 'activateRecord') {
		$id = htmlentities ( trim ( $_POST ['id'] ) );
		$subject->activateClassSubjectMapping($id);
		$outputArray [0] = $id;
		echo json_encode ( $outputArray );
	} else {
		$outputArray [0] = 0;
		echo json_encode ( $outputArray );
	}
}
?>