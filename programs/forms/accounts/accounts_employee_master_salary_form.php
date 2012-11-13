<?php
require_once 'config.php';

require_once BASE_PATH . 'include/utility/class.employeeRegistration.php';
require_once BASE_PATH . 'include/accounts/class.accounts.php';

$registration = new employeeRegistration ();
$accounts = new Accounts ();

$registration->isRequestAuthorised4Form ( 'LMENUL112' );

if (isset ( $_POST ['task'] )) {
	if ($_POST ['task'] == 'fetchEmployeeDetails') {
		
		$employeeId = $_POST ['employeeId'];
		
		$outputArray [0] = $registration->getCandidateName ( $employeeId );
		$outputArray [] = $registration->getEmployeeRegistrationNumber ( $employeeId );
		
		echo json_encode ( $outputArray );
	} elseif ($_POST ['task'] == 'getAllowanceAmount') {
		$employeeId = $_POST ['employeeId'];
		$allowanceId = $_POST ['allowanceId'];
		$amount = $accounts->getEmployeeMasterSalaryAllowanceAmount ( $employeeId, $allowanceId );
		$calculatedAmount = $accounts->getAccountSum ( $employeeId, $allowanceId );
		$outputArray [0] = $allowanceId;
		$outputArray [] = $amount ? $amount : $calculatedAmount;
		$outputArray [] = $calculatedAmount;
		
		echo json_encode ( $outputArray );
	} elseif ($_POST ['task'] == 'insertRecord') {
		$employeeId = $_POST ['employeeId'];
		$allowanceId = $_POST ['allowance_val'];
		$amount = $_POST ['amount'];
		$calculatedAmount = $_POST ['calculatedAmount_i'];
		
		$overRidden = 'n';
		if ($amount != $calculatedAmount)
			$overRidden = 'y';
		
		$masterSalaryId = $accounts->setEmployeeMasterSalaryInfo ( $employeeId, $allowanceId, $amount, $overRidden );
		if (masterSalaryId) {
			$outputArray [0] = $masterSalaryId;
			$outputArray [] = $accounts->getAllowanceName ( $allowanceId );
			$outputArray [] = $amount;
			$outputArray [] = $calculatedAmount;
			$outputArray [] = $overRidden;
		} else
			$outputArray [0] = 0;
		echo json_encode ( $outputArray );
	} elseif ($_POST ['task'] == 'updateRecord') {
		$employeeId = $_POST ['employeeId'];
		$allowanceId = $_POST ['allowance_uval'];
		$amount = $_POST ['amount_u'];
		$calculatedAmount = $_POST ['calculatedAmount_ui'];
		
		$overRidden = 'n';
		if ($amount != $calculatedAmount)
			$overRidden = 'y';
		
		$masterSalaryId = $accounts->setEmployeeMasterSalaryInfo ( $employeeId, $allowanceId, $amount, $overRidden );
		if (masterSalaryId) {
			$outputArray [0] = $masterSalaryId;
			$outputArray [] = $accounts->getAllowanceName ( $allowanceId );
			$outputArray [] = $amount;
			$outputArray [] = $calculatedAmount;
			$outputArray [] = $overRidden;
		} else
			$outputArray [0] = 0;
		echo json_encode ( $outputArray );
	} elseif ($_POST ['task'] == 'search') {
		$employeeId = $_POST ['employeeId'];
		$search_type = $_POST ['search_type'];
		
		$masterSalaryIds = $accounts->getEmployeeMasterSalaryIds ( $employeeId, $search_type );
		$outputArray [0] [0] = 1;
		$i = 0;
		foreach ( $masterSalaryIds as $masterSalaryId ) {
			$details = $accounts->getTableIdDetails ( $masterSalaryId );
			$outputArray [$i] [0] = $masterSalaryId;
			$outputArray [$i] [] = $accounts->getAllowanceName ( $details ['allowance_id'] );
			$outputArray [$i] [] = $details ['amount'];
			$outputArray [$i] [] = $accounts->getAccountSum ( $employeeId, $details ['allowance_id'] );
			$outputArray [$i] [] = $details ['over_ridden'];
			++ $i;
		}
		
		echo json_encode ( $outputArray );
	} else if ($_POST ['task'] == 'getRecordIdDetails') {
		$id = htmlentities ( trim ( $_POST ['id'] ) );
		$employeeId = $_POST ['employeeId'];
		$details = $accounts->getTableIdDetails ( $id );
		
		$outputArray [0] = $details ['id'];
		$outputArray [] = $accounts->getAllowanceName ( $details ['allowance_id'] );
		$outputArray [] = $details ['over_ridden'] == 'y' ? "<font class='red'>Over Ridden</font>" : "<font class='green'>Calculated</font>";
		$outputArray [] = $details ['amount'];
		$outputArray [] = $accounts->getAccountSum ( $employeeId, $details ['allowance_id'] );
		
		$outputArray [] = $details ['last_update_date'];
		$outputArray [] = $accounts->getOfficerName ( $details ['last_updated_by'] );
		$outputArray [] = $details ['creation_date'];
		$outputArray [] = $accounts->getOfficerName ( $details ['created_by'] );
		$outputArray [] = $details ['active'];
		
		$outputArray [] = $details ['allowance_id'];
		
		echo json_encode ( $outputArray );
	} elseif ($_POST ['task'] == 'dropRecord') {
		$id = htmlentities ( trim ( $_POST ['id'] ) );
		$accounts->dropMasterSalaryDetails($id);
		$outputArray [0] = $id;
		echo json_encode ( $outputArray );
	} elseif ($_POST ['task'] == 'activateRecord') {
		$id = htmlentities ( trim ( $_POST ['id'] ) );
		$accounts->activateMasterSalaryDetails($id);
		$outputArray [0] = $id;
		echo json_encode ( $outputArray );
	} else {
		$outputArray [0] = 0;
		echo json_encode ( $outputArray );
	}
}
?>