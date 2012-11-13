<?php
require_once 'config.php';
require_once BASE_PATH . 'include/accounts/class.directInsertion.php';

$accounts = new DirectInsertion();

$accounts->isRequestAuthorised4Form ( 'LMENUL124' );

if (isset ( $_POST ['task'] )) {
	if ($_POST ['task'] == 'getAllowanceAmount') {
		$employeeId = $_POST ['employeeId'];
		$allowanceId = $_POST ['allowanceId'];
		$month = $_POST['month'];
		$directSalaryId = $accounts->getDirectSalaryId4EmployeeAllowance($employeeId, $allowanceId, $month, 1);
		if($directSalaryId){
			$details = $accounts->getTableIdDetails($directSalaryId);
			$outputArray [0] = $directSalaryId;
			$outputArray [] = $details['amount'] == '' ? 0 : $details['amount'];
			$outputArray [] = $details['comments'];
		}else{
			$outputArray[0] = 1;
		}		
		echo json_encode ( $outputArray );
	} elseif ($_POST ['task'] == 'insertRecord') {
		$employeeId = $_POST ['employeeId'];
		$allowanceId = $_POST ['allowanceId'];
		$amount = $_POST ['amount'];
		$month = $_POST ['month'];
		$remarks = $_POST['remarks'];
		
		$directSalaryId = $accounts->setDirectSalaryAdditionDetails($employeeId, $allowanceId, $month, $amount, $remarks);
		$accounts->setDirectAllowanceComponentsInsertion($directSalaryId, $employeeId, $allowanceId, $remarks, $month, $amount);
		if ($directSalaryId) {
			$outputArray [0] = $directSalaryId;
		} else
			$outputArray [0] = 0;
		
		echo json_encode ( $outputArray );
	} elseif ($_POST ['task'] == 'dropRecord') {
		$allowanceId = $_POST['allowanceId'];
		$employeeId = $_POST['employeeId'];
		$month = $_POST['month'];
		
		$id = $accounts->getDirectSalaryId4EmployeeAllowance($employeeId, $allowanceId, $month, 'all');
		$accounts->dropDirectSalaryAdditionDetails($id);
		$outputArray [0] = $id;
		echo json_encode ( $outputArray );
	} elseif ($_POST ['task'] == 'activateRecord') {
		$allowanceId = $_POST['allowanceId'];
		$employeeId = $_POST['employeeId'];
		$month = $_POST['month'];
		
		$id = $accounts->getDirectSalaryId4EmployeeAllowance($employeeId, $allowanceId, $month, 'all');
		$accounts->activateDirectSalaryAdditionDetails($id);
		$outputArray [0] = $id;
		echo json_encode ( $outputArray );
	}else {
		$outputArray [0] = 0;
		echo json_encode ( $outputArray );
	}
}
?>