<?php
require_once 'config.php';
require_once BASE_PATH . 'include/accounts/class.accounts.php';
require_once BASE_PATH . 'include/utility/class.employeeRegistration.php';

$accounts = new Accounts ();
$registration = new employeeRegistration();

$accounts->isRequestAuthorised4Form ( 'LMENUL123' );

if (isset ( $_POST ['task'] )) {
	if ($_POST ['task'] == 'getAllowanceAmount') {
		$employeeId = $_POST ['employeeId'];
		$allowanceId = $_POST ['allowanceId'];
		$amount = $accounts->getEmployeeMasterSalaryAllowanceAmount ( $employeeId, $allowanceId );
		$calculatedAmount = $accounts->getAccountSum ( $employeeId, $allowanceId );
		$outputArray [0] = $allowanceId;
		$outputArray [] = $amount ? $amount : 0;
		$outputArray [] = $calculatedAmount;	
		
		echo json_encode ( $outputArray );
	} elseif ($_POST ['task'] == 'insertRecord') {
		$employeeId = $_POST ['employeeId'];
		$allowanceId = $_POST ['allowanceId'];
		$amount = $_POST ['amount'];
		$calculatedAmount = $_POST ['calculatedAmount'];
		
		$overRidden = 'n';
		if ($amount != $calculatedAmount)
			$overRidden = 'y';
		
		$masterSalaryId = $accounts->setEmployeeMasterSalaryInfo ( $employeeId, $allowanceId, $amount, $overRidden );
		if (masterSalaryId) {
			$outputArray [0] = $masterSalaryId;
			$outputArray [] = $overRidden == 'y' ? "<font class='red'>Over Ridden</font>" : "<font class='green'>Calculated</font>";
		} else
			$outputArray [0] = 0;
		echo json_encode ( $outputArray );
	} elseif ($_POST ['task'] == 'dropRecord') {
		$allowanceId = $_POST['allowanceId'];
		$employeeId = $_POST['employeeId'];
		
		$id = $accounts->getEmployeeMasterSalaryId($employeeId, $allowanceId, false);
		$accounts->dropMasterSalaryDetails($id);
		$outputArray [0] = $id;
		echo json_encode ( $outputArray );
	} elseif ($_POST ['task'] == 'activateRecord') {
		$allowanceId = $_POST['allowanceId'];
		$employeeId = $_POST['employeeId'];
		
		$id = $accounts->getEmployeeMasterSalaryId($employeeId, $allowanceId, false);
		$accounts->activateMasterSalaryDetails($id);
		$outputArray [0] = $id;
		echo json_encode ( $outputArray );
	} elseif($_POST['task'] == 'fetchEmployeeIds'){
		$outputArray = $registration->getEmployeeIds(1);
		echo json_encode(array_reverse($outputArray));
	}else {
		$outputArray [0] = 0;
		echo json_encode ( $outputArray );
	}
}
?>