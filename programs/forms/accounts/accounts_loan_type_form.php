<?php
require_once 'config.php';

require_once BASE_PATH . 'include/accounts/class.loan.php';
require_once BASE_PATH . 'include/accounts/class.allowance.php';

$allowance = new Allowance ();
$loan = new Loan ();

$loan->isRequestAuthorised4Form ( 'LMENUL126' );

if (isset ( $_POST ['task'] )) {
	if ($_POST ['task'] == 'insertRecord') {
		$loanTypeId = $loan->setLoanTypeDetails ( $_POST ['allowanceId'], $_POST ['loanName'], $_POST ['minAmount'], $_POST ['maxAmount'] );
		if ($loanTypeId) {
			$outputArray [0] = $loanTypeId;
			$outputArray [] = $_POST ['loanName'];
			$outputArray [] = $allowance->getAllowanceName ( $_POST ['allowanceId'] );
			$outputArray [] = $_POST ['minAmount'];
			$outputArray [] = $_POST ['maxAmount'];
		}
		echo json_encode ( $outputArray );
	} else if ($_POST ['task'] == 'search') {
		$search_type = htmlentities ( trim ( $_POST ['search_type'] ) );
		$data = $loan->getLoanIds ( $search_type );
		$i = 0;
		$outputArray [0] [0] = 1;
		foreach ( $data as $id ) {
			$details = $loan->getTableIdDetails ( $id );
			$outputArray [$i] [0] = $details ['id'];
			$outputArray [$i] [] = $details ['loan_name'];
			$outputArray [$i] [] = $allowance->getAllowanceName ( $details ['allowance_id'] );
			$outputArray [$i] [] = $details ['min_amount'];
			$outputArray [$i] [] = $details ['max_amount'];
			++ $i;
		}
		echo json_encode ( $outputArray );
	} else if ($_POST ['task'] == 'getRecordIdDetails') {
		$id = htmlentities ( trim ( $_POST ['id'] ) );
		$details = $loan->getTableIdDetails ( $id );
		
		$outputArray [0] = $details ['id'];
		$outputArray [] = $details ['loan_name'];
		$outputArray [] = $details ['allowance_id'];
		$outputArray [] = $allowance->getAllowanceName ( $details ['allowance_id'] );
		$outputArray [] = $details ['min_amount'];
		$outputArray [] = $details ['max_amount'];
		
		$outputArray [] = $details ['last_update_date'];
		$outputArray [] = $loan->getOfficerName ( $details ['last_updated_by'] );
		$outputArray [] = $details ['creation_date'];
		$outputArray [] = $loan->getOfficerName ( $details ['created_by'] );
		$outputArray [] = $details ['active'];
		
		echo json_encode ( $outputArray );
	} elseif ($_POST ['task'] == 'updateRecord') {
		$loanTypeId = $_POST ['valueId_u'];
		$details = $loan->getTableIdDetails ( $loanTypeId );
		
		if($details['loan_name'] != $_POST['loanName_u']){
			$loan->setUpdateLog('Name from '.$details['loan_name'].' to '.$_POST['loanName_u']);
			$loan->updateTableParameter ( 'loan_name', $_POST ['loanName_u'] );
		}
		if($details['allowance_id'] != $_POST['allowanceId_u']){
			$loan->setUpdateLog('Allowance from '.$details['allowance_id'].' to '.$_POST['allowanceId_u']);
			$loan->updateTableParameter ( 'allowance_id', $_POST ['allowanceId_u'] );
		}
		if($details['min_amount'] != $_POST['minAmount_u']){
			$loan->setUpdateLog('Min Amount from '.$details['min_amount'].' to '.$_POST['minAmount_u']);
			$loan->updateTableParameter ( 'min_amount', $_POST ['minAmount_u'] );
		}
		if($details['max_amount'] != $_POST['maxAmount_u']){
			$loan->setUpdateLog('Max Amount from '.$details['max_amount'].' to '.$_POST['maxAmount_u']);
			$loan->updateTableParameter ( 'max_amount', $_POST ['maxAmount_u'] );
		}
		$loan->commitLoanTypeDetailsUpdate ( $loanTypeId );
		
		$outputArray [0] = $loanTypeId;
		$outputArray [] = $_POST ['loanName_u'];
		$outputArray [] = $allowance->getAllowanceName ( $_POST ['allowanceId_u'] );
		$outputArray [] = $_POST ['minAmount_u'];
		$outputArray [] = $_POST ['maxAmount_u'];
		
		echo json_encode ( $outputArray );
	} elseif ($_POST ['task'] == 'dropRecord') {
		$id = htmlentities ( trim ( $_POST ['id'] ) );
		$loan->dropLoanTypeDetails($id);
		$outputArray [0] = $id;
		echo json_encode ( $outputArray );
	} elseif ($_POST ['task'] == 'activateRecord') {
		$id = htmlentities ( trim ( $_POST ['id'] ) );
		$loan->activateLoanTypeDetails($id);
		$outputArray [0] = $id;
		echo json_encode ( $outputArray );
	} else {
		$outputArray [0] = 0;
		echo json_encode ( $outputArray );
	}
}
?>