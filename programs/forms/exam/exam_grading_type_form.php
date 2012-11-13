<?php
require_once 'config.php';
require_once BASE_PATH . 'include/exam/class.grading.php';

$grading = new Grading();
$grading->isRequestAuthorised4Form ( 'LMENUL66' );

if (isset ( $_POST ['task'] )) {
	if ($_POST ['task'] == 'insertRecord') {
		$gradingId = $grading->setGradingType($_POST['gradingType']);		
		if($gradingId){
			$outputArray[0] = $gradingId;
			$outputArray[] = $_POST['gradingType'];
			$outputArray[] = 0;
		}else
			$outputArray[0] = 0;		 
		echo json_encode ( $outputArray );
	} else if ($_POST ['task'] == 'search') {
		$search_type = $_POST['search_type'];
		$hint = $_POST['hint'];
		$gradingIds = $grading->getGradingType($hint, $search_type);
		$outputArray[0][0] = 1;
		$i = 0;
		foreach ($gradingIds as $gradingId){
			$details = $grading->getTableIdDetails($gradingId);
			$outputArray[$i][0] = $gradingId;
			$outputArray[$i][] = $details['grading_name'];
			$outputArray[$i][] = count($grading->getGradingOptionIds($gradingId, 1));
			++$i;
		}
		echo json_encode ( $outputArray );
	} else if ($_POST ['task'] == 'getRecordIdDetails') {
		$id = $_POST ['id'];				
		$details = $grading->getTableIdDetails ( $id );	
		
			
		$outputArray [0] = $details ['id'];
		$outputArray [] = $details['grading_name'];
		$outputArray [] = count($grading->getGradingOptionIds($id, 1));
		$outputArray [] = $details ['last_update_date'];
		$outputArray [] = $grading->getOfficerName ( $details ['last_updated_by'] );
		$outputArray [] = $details ['creation_date'];
		$outputArray [] = $grading->getOfficerName ( $details ['created_by'] ); //10
		$outputArray [] = $details ['active'];
		
		echo json_encode ( $outputArray );
	} elseif ($_POST ['task'] == 'updateRecord') {		
		$id = $_POST['valueId_u'];

		$grading->updateTableParameter('grading_name', $_POST['gradingType_u']);
		if($grading->commitGradingTypeUpdate($id)){
			$outputArray[0] = $id;
			$outputArray[] = $_POST['gradingType_u'];
			$outputArray[] = count($grading->getGradingOptionIds($id, 1));				
		}else{
			$outputArray[] = 0;
		}		
		echo json_encode ( $outputArray );
	
	} elseif ($_POST ['task'] == 'dropRecord') {
		$id = htmlentities ( trim ( $_POST ['id'] ) );
		$grading->dropGradingType($id);
		$outputArray [0] = $id;
		echo json_encode ( $outputArray );
	} elseif ($_POST ['task'] == 'activateRecord') {
		$id = htmlentities ( trim ( $_POST ['id'] ) );
		$grading->activateGradingType($id);
		$outputArray [0] = $id;
		echo json_encode ( $outputArray );
	} else {
		$outputArray [0] = 0;
		echo json_encode ( $outputArray );
	}
}
?>