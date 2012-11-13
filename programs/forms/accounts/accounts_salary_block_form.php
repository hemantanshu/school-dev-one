<?php
require_once 'config.php';
require_once BASE_PATH . 'include/accounts/class.blockSalary.php';

$blockSalary = new BlockSalary ();
$blockSalary->isRequestAuthorised4Form ( 'LMENUL159' );

if (isset ( $_POST ['task'] )) {
	if ($_POST ['task'] == 'insertRecord') {
		
		$blockSalaryId = $blockSalary->setBlockSalaryDetails ( $_POST ['employeeName_val'], $_POST ['startDate_i'], $_POST ['endDate_i'], $_POST ['type'], $_POST ['comment_i'] );
		
		if ($blockSalaryId) {
			$outputArray [0] = $blockSalaryId;
			$outputArray [] = $blockSalary->getOfficerName ( $_POST ['employeeName_val'] );
			$outputArray [] = $blockSalary->getEmployeeCode ( $_POST ['employeeName_val'] );
			$outputArray [] = $blockSalary->getDisplayDate ( $_POST ['startDate_i'] );
		}
		echo json_encode ( $outputArray );
	} else if ($_POST ['task'] == 'search') {
		$hint = htmlentities ( trim ( $_POST ['searchKey'] ) );
		$search_type = htmlentities ( trim ( $_POST ['search_type'] ) );
		$i = 0;
		$outputArray [0] [0] = 1;
		$blockSalaryIds = $blockSalary->getBlockSalaryIds ( $search_type );
		foreach ( $blockSalaryIds as $blockSalaryId ) {
			$details = $blockSalary->getTableIdDetails ( $blockSalaryId );
			$outputArray [$i] [0] = $blockSalaryId;
			$outputArray [$i] [] = $blockSalary->getEmployeeCode ( $details ['employee_name'] );
			$outputArray [$i] [] = $blockSalary->getOfficerName ( $details ['employee_name'] );
			$outputArray [$i] [] = $blockSalary->getDisplayDate ( $details ['start_date'] );
			++ $i;
		}
		echo json_encode ( $outputArray );
	} else if ($_POST ['task'] == 'getRecordIdDetails') {
		$blockSalaryId = $_POST['id'];
		$details = $blockSalary->getTableIdDetails($blockSalaryId);
		
		$outputArray[0] = $details['id'];
		$outputArray[] = $blockSalary->getOfficerName($details['employee_id']);
		$outputArray[] = $blockSalary->getEmployeeCode($details['employee_id']);
		$outputArray[] = $blockSalary->getDisplayDate($details['start_date']);
		$outputArray[] = $details['start_date'];
		$outputArray[] = $blockSalary->getDisplayDate($details['end_date']);
		$outputArray[] = $details['end_date'];
		$outputArray[] = $details['comments'];
		
		$outputArray[0] = $details['last_update_date'];
        $outputArray[] = $blockSalary->getOfficerName($details['last_updated_by']);
        $outputArray[] = $details['creation_date'];
        $outputArray[] = $blockSalary->getOfficerName($details['created_by']);
        $outputArray[] = $details['active'];
		
		
		echo json_encode ( $outputArray );
	} elseif ($_POST ['task'] == 'updateMenuUrl') {
		$blockSalaryId = $_POST['valueId_u'];
		$details = $blockSalary->getTableIdDetails($blockSalaryId);
		
		if($details['end_date'] != $_POST['endDate_e']){
			$blockSalary->setUpdateLog('End Date from '.$details['end_date'].' to '.$_POST['endDate_e']);
			$blockSalary->updateTableParameter('end_date', $_POST['endDate_e']);
		}
		
		if($details['comments'] != $_POST['comments_e']){
			$blockSalary->setUpdateLog('Comments from '.$details['comments'].' to '.$_POST['comments_e']);
			$blockSalary->updateTableParameter('comments', $_POST['comments_e']);
		}		
		$outputArray [0] = $blockSalaryId;
		
		echo json_encode ( $outputArray );
	} elseif ($_POST ['task'] == 'dropId') {
		$id = htmlentities ( trim ( $_POST ['id'] ) );
		$blockSalary->dropBlockSalaryRecord($id);
		$outputArray [0] = $id;
		echo json_encode ( $outputArray );
	} elseif ($_POST ['task'] == 'activateId') {
		$id = htmlentities ( trim ( $_POST ['id'] ) );
		$blockSalary->activateAccountHeadDetails($id);
		$outputArray [0] = $id;
		echo json_encode ( $outputArray );
	} else {
		$outputArray [0] = 0;
		echo json_encode ( $outputArray );
	}
}
?>