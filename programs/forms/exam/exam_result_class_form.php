<?php
require_once 'config.php';

require_once BASE_PATH . 'include/global/class.options.php';
require_once BASE_PATH . 'include/utility/class.sections.php';

$section = new sections ();
$options = new options ();

$options->isRequestAuthorised4Form ( 'LMENUL100' );

if (isset ( $_POST ['task'] )) {
	if ($_POST ['task'] == 'insertRecord') {
		$resultId = $_POST ['resultId'];
		$sectionId = $_POST ['sectionId'];
		
		$assignmentId = $options->setNewAssignment ( $resultId, $sectionId, 'CLSSA' );
		
		$details = $options->getTableIdDetails ( $assignmentId );
		$outputArray[0] = $assignmentId;
		$outputArray [] = $section->getClassName4Section ( $sectionId );
		$outputArray [] = $section->getSectionName ( $sectionId );
		$outputArray [] = $details ['last_update_date'];
		$outputArray [] = $options->getOfficerName ( $details ['last_updated_by'] );
		
		echo json_encode ( $outputArray );
	
	} else if ($_POST ['task'] == 'search') {
		$resultId = htmlentities ( trim ( $_POST ['resultId'] ) );
		$search_type = htmlentities ( trim ( $_POST ['search_type'] ) );
		
		$assignmentIds = $options->getAssignmentIds ( $resultId, 'CLSSA', $search_type );
		$outputArray [0] [0] = 1;
		$i = 0;
		foreach ( $assignmentIds as $assignmentId ) {
			$details = $options->getTableIdDetails ( $assignmentId );
			$outputArray [$i] [0] = $details ['id'];
			$outputArray [$i] [] = $section->getClassName4Section ( $details ['value_set'] );
			$outputArray [$i] [] = $section->getSectionName ( $details ['value_set'] );
			$outputArray [$i] [] = $details ['last_update_date'];
			$outputArray [$i] [] = $options->getOfficerName ( $details ['last_updated_by'] );
			++ $i;
		}
		echo json_encode ( $outputArray );
	} else if ($_POST ['task'] == 'getRecordIdDetails') {
		$id = $_POST ['id'];
		
		$details = $options->getTableIdDetails ( $id );

		$outputArray [0] = $details ['id'];
		$outputArray [] = $section->getClassName4Section ( $details ['value_set'] );
		$outputArray [] = $section->getSectionName ( $details ['value_set'] );
		
		$outputArray [] = $details ['last_update_date'];
		$outputArray [] = $section->getOfficerName ( $details ['last_updated_by'] );
		$outputArray [] = $details ['creation_date'];
		$outputArray [] = $section->getOfficerName ( $details ['created_by'] );
		$outputArray [] = $details ['active'];
		
		echo json_encode ( $outputArray );
	} elseif ($_POST ['task'] == 'dropRecord') {
		$id = htmlentities ( trim ( $_POST ['id'] ) );
		$options->dropAssignment ( $id );
		$outputArray [0] = $id;
		echo json_encode ( $outputArray );
	} elseif ($_POST ['task'] == 'activateRecord') {
		$id = htmlentities ( trim ( $_POST ['id'] ) );
		$options->activateAssignment ( $id );
		$outputArray [0] = $id;
		echo json_encode ( $outputArray );
	}else {
		$outputArray [0] = 0;
		echo json_encode ( $outputArray );
	}
}
?>