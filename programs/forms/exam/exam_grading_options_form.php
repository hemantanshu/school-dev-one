<?php
require_once 'config.php';
require_once BASE_PATH . 'include/exam/class.grading.php';

$grading = new Grading();
$grading->isRequestAuthorised4Form ( 'LMENUL67' );

if (isset ( $_POST ['task'] )) {
	if ($_POST ['task'] == 'insertRecord') {
		$gradingId = $grading->setGradingOptions($_POST['gradeId'], $_POST['gradeOption'], $_POST['gradeWeight']);		
		if($gradingId){
			$outputArray[0] = $gradingId;
			$outputArray[] = $_POST['gradeOption'];
			$outputArray[] = $_POST['gradeWeight'];
		}else
			$outputArray[0] = 0;		 
		echo json_encode ( $outputArray );
	} else if ($_POST ['task'] == 'search') {
		$search_type = $_POST['search_type'];
		$gradingIds = $grading->getGradingOptionIds($_POST['gradeId'], $search_type);
		$outputArray[0][0] = 1;
		$i = 0;
		foreach ($gradingIds as $gradingId){
			$details = $grading->getTableIdDetails($gradingId);
			$outputArray[$i][0] = $gradingId;
			$outputArray[$i][] = $details['grade_name'];
			$outputArray[$i][] = $details['weight'];
			++$i;
		}
		echo json_encode ( $outputArray );
	} else if ($_POST ['task'] == 'getRecordIdDetails') {
		$id = $_POST ['id'];				
		$details = $grading->getTableIdDetails ( $id );		
			
		$outputArray [0] = $details ['id'];
		$outputArray [] = $details['grade_name'];
		$outputArray [] = $details['weight'];
		$outputArray [] = $details ['last_update_date'];
		$outputArray [] = $grading->getOfficerName ( $details ['last_updated_by'] );
		$outputArray [] = $details ['creation_date'];
		$outputArray [] = $grading->getOfficerName ( $details ['created_by'] ); //10
		$outputArray [] = $details ['active'];
		
		echo json_encode ( $outputArray );
	} elseif ($_POST ['task'] == 'updateRecord') {		
		$id = $_POST['valueId_u'];
		$details = $grading->getTableIdDetails($id);
		if($details['grade_name'] != $_POST['gradeOption_u']){
			$grading->setUpdateLog('Name from '.$details['grade_name'].' to '.$_POST['gradeOption_u']);
			$grading->updateTableParameter('grade_name', $_POST['gradeOption_u']);
		}
		if($details['weight'] != $_POST['gradeWeight_u']){
			$grading->setUpdateLog('Weight from '.$details['weight'].' to '.$_POST['gradeWeight_u']);
			$grading->updateTableParameter('weight', $_POST['gradeWeight_u']);
		}	
		if($grading->commitGradingOptionUpdate($id)){
			$outputArray[0] = $id;
			$outputArray[] = $_POST['gradeOption_u'];
			$outputArray[] = $_POST['gradeWeight_u'];				
		}else{
			$outputArray[] = 0;
		}		
		echo json_encode ( $outputArray );
	
	} elseif ($_POST ['task'] == 'dropRecord') {
		$id = htmlentities ( trim ( $_POST ['id'] ) );
		$grading->dropGradingOption($id);
		$outputArray [0] = $id;
		echo json_encode ( $outputArray );
	} elseif ($_POST ['task'] == 'activateRecord') {
		$id = htmlentities ( trim ( $_POST ['id'] ) );
		$grading->activateGradingOption($id);
		$outputArray [0] = $id;
		echo json_encode ( $outputArray );
	} else {
		$outputArray [0] = 0;
		echo json_encode ( $outputArray );
	}
}
?>