<?php
require_once 'config.php';

require_once BASE_PATH . 'include/global/class.menuTask.php';
require_once BASE_PATH . 'include/exam/class.result.php';
require_once BASE_PATH . 'include/global/class.session.php';

$result = new Result ();
$session = new Session ();

$result->isRequestAuthorised4Form ( 'LMENUL92' );
if (isset ( $_POST ['task'] )) {
	if ($_POST ['task'] == 'insertRecord') {
		$resultId = $result->setResultSetup ( $_POST ['sessionId'], $_POST ['resultId'], $_POST ['displayName'], $_POST ['examinationGroup'], $_POST ['weightAge'], $_POST['displayOrder'] );
		$details = $result->getTableIdDetails ( $_POST ['examinationGroup'] );
		$outputArray [0] = $resultId;
		$outputArray [] = $_POST ['displayName'];
		$outputArray [] = $details ['examination_name'];
		$outputArray [] = $_POST ['weightAge'];
		$outputArray [] = $_POST ['display_order'];
		
		echo json_encode ( $outputArray );
	
	} else if ($_POST ['task'] == 'search') {
		$search_type = $_POST ['search_type'];
		$resultId = $_POST ['resultId'];
		
		$resultDateIds = $result->getResultSetupIds ( $resultId, $search_type );
		$outputArray [0] [0] = 1;
		$i = 0;
		foreach ( $resultDateIds as $resultId ) {
			$details = $result->getTableIdDetails ( $resultId );
			$examDetails = $result->getTableIdDetails ( $details ['examination_id'] );
			$editable = 1;
			if (! $session->isSessionEditable ( $details ['session_id'] ))
				$editable = 0;
			
			$outputArray [$i] [0] = $resultId;
			$outputArray [$i] [] = $details ['display_name'];
			$outputArray [$i] [] = $examDetails ['examination_name'];
			$outputArray [$i] [] = $details ['weightage'];
			$outputArray [$i] [] = $details ['display_order'];
			$outputArray [$i] [] = $editable;
			++ $i;
		}
		echo json_encode ( $outputArray );
	
	} else if ($_POST ['task'] == 'getRecordIdDetails') {
		$resultId = $_POST ['id'];
		$details = $result->getTableIdDetails ( $resultId );
		$editable = 1;
		if (! $session->isSessionEditable ( $details ['session_id'] ))
			$editable = 0;
		
		$examinationDetails = $result->getTableIdDetails ( $details ['examination_id'] );
		
		$outputArray [0] = $details ['id'];
		$outputArray [] = $details ['display_name'];
		$outputArray [] = $details ['examination_id'];
		$outputArray [] = $examinationDetails ['examination_name'];
		$outputArray [] = $details ['weightage'];
		
		$outputArray [] = $details ['last_update_date'];
		$outputArray [] = $result->getOfficerName ( $details ['last_updated_by'] );
		$outputArray [] = $details ['creation_date']; // 10
		$outputArray [] = $result->getOfficerName ( $details ['created_by'] );
		$outputArray [] = $details ['active'];
		$outputArray [] = $editable;
		
		$outputArray [] = $details ['display_order'];
		
		echo json_encode ( $outputArray );
	} elseif ($_POST ['task'] == 'updateRecord') {
		$resultSetupId = $_POST ['valueId_u'];		
		$details = $result->getTableIdDetails($resultSetupId);
		if ($session->isSessionEditable ( $details ['session_id'] ))
		{
			if($details['display_name'] != $_POST['displayName_u']){
				$result->setUpdateLog('Name from '.$details['display_name'].' to '.$_POST['displayName_u']);
				$result->updateTableParameter('display_name', $_POST['displayName_u']);
			}
			if($details['examination_id'] != $_POST['examinationGroup_u']){
				$result->setUpdateLog('Examination from '.$details['examination_id'].' to '.$_POST['examinationGroup_u']);
				$result->updateTableParameter('examination_id', $_POST['examinationGroup_u']);
			}
			if($details['weightage'] != $_POST['weightAge_u']){
				$result->setUpdateLog('Weightage from '.$details['weightage'].' to '.$_POST['weightAge_u']);
				$result->updateTableParameter('weightage', $_POST['weightAge_u']);
			}	
			if($details['display_order'] != $_POST['displayOrder_u']){
				$result->setUpdateLog('Order from '.$details['display_order'].' to '.$_POST['displayOrder_u']);
				$result->updateTableParameter('display_order', $_POST['displayOrder_u']);
			}		
			$result->commitResultSetupUpdate($resultSetupId);
			
			$examinationDetails = $result->getTableIdDetails($_POST['examinationGroup_u']);
			$outputArray[0] = $resultSetupId;
			$outputArray[] = $_POST['displayName_u'];
			$outputArray[] = $examinationDetails['examination_name'];
			$outputArray[] = $_POST['weightAge_u'];
			$outputArray[] = $_POST['displayOrder_u'];
			$outputArray[] = 1;				
		}else
			$outputArray[] = 'ERR401';
			
		echo json_encode ( $outputArray );
	}elseif ($_POST ['task'] == 'dropRecord') {
		$id = $_POST['id'];
		$result->dropResultSetupDetails($id);				
		$outputArray[0] = 1;
		echo json_encode($outputArray);		
		
	} elseif ($_POST ['task'] == 'activateRecord') {
		$id = $_POST['id'];
		$result->activateResultSetupDetails($id);		
		$outputArray[0] = 1;
		echo json_encode($outputArray);
	}else{
		$outputArray[0] = 0;
		echo json_encode($outputArray);
	}
}
?>