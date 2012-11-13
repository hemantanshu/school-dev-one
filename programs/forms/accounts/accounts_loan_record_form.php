<?php
require_once 'config.php';

require_once BASE_PATH . 'include/accounts/class.loan.php';
require_once BASE_PATH . 'include/accounts/class.allowance.php';
require_once BASE_PATH . 'include/global/class.options.php';

$allowance = new Allowance ();
$loan = new Loan ();
$options = new options();

$loan->isRequestAuthorised4Form ( 'LMENUL144' );

if (isset ( $_POST ['task'] )) {
	if ($_POST ['task'] == 'searchRecord') {
		$loanTypeId = $_POST['loanTypeHint'];
		$searchType = $_POST['search_type'];
		
		if($loanTypeId == '')
			$loanAccountIds = $loan->getLoanAccountIds($searchType);
		else
			$loanAccountIds = $loan->getLoanTypeLoanAccountIds($loanTypeId, $searchType);
		$i = 0;
		$outputArray[0][0] = 1;
		foreach ($loanAccountIds as $loanAccountId){
			$details = $loan->getTableIdDetails($loanAccountId);
			
			$outputArray[$i][0] = $loanAccountId;
			$outputArray[$i][] = $loan->getLoanName($details['loan_type_id']);
			$outputArray[$i][] = $loan->getOfficerName($details['employee_id']);
			$outputArray[$i][] = $details['amount'];
			$outputArray[$i][] = $loan->getDisplayDate($details['loan_date']);
			$outputArray[$i][] = $details['active'];
			++$i;	
		}
		echo json_encode ( $outputArray );
	} elseif ($_POST ['task'] == 'getRecordIdDetails') {
		$loanAccountId = $_POST['id'];
		$details = $loan->getTableIdDetails($loanAccountId);
		
		$outputArray[0] = $loanAccountId;
		$outputArray[] = $loan->getOfficerName($details['employee_id']);
		$outputArray[] = $loan->getEmployeeCode($details['employee_id']);
		$outputArray[] = $loan->getLoanName($details['loan_type_id']);
		$outputArray[] = $loan->getDisplayDate($details['loan_date']);
		$outputArray[] = number_format($details['amount'], 2, '.', ',');
		$outputArray[] = number_format($details['installment_amount'], 2, '.', ',');
		$outputArray[] = $options->getOptionIdValue($details['interest_type']);
		$outputArray[] = $options->getOptionIdValue($details['payment_mode']);
		$outputArray[] = $details['flexible_installment'] == 'y' ? 'Yes' : 'No';
		$outputArray[] = $details['interest_rate'];
		
		$outputArray [] = $loan->getDisplayDate($details ['last_update_date']);
		$outputArray [] = $allowance->getOfficerName ( $details ['last_updated_by'] );
		$outputArray [] = $loan->getDisplayDate($details ['creation_date']);
		$outputArray [] = $allowance->getOfficerName ( $details ['created_by'] );
		$outputArray [] = $details ['active'];
			
		echo json_encode ( $outputArray );
	} else {
		$outputArray [0] = 0;
		echo json_encode ( $outputArray );
	}
}
?>