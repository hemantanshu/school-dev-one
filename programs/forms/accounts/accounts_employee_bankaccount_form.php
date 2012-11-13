<?php
require_once 'config.php';

require_once BASE_PATH . 'include/global/class.options.php';
require_once BASE_PATH . 'include/accounts/class.bank.php';

$options = new options ();
$bank = new Bank ();

$options->isRequestAuthorised4Form ( 'LMENUL125' );

if (isset ( $_POST ['task'] )) {
	if ($_POST ['task'] == 'populateBankName') {
		$data = $bank->getBankNameSearchIds ( '', 1 );
		$i = 0;
		$outputArray [0] [0] = 1;
		foreach ( $data as $id ) {
			$details = $bank->getTableIdDetails ( $id );
			$outputArray [$i] [0] = $details ['id'];
			$outputArray [$i] [] = $details ['bank_name'] . " , " . $details ['branch_name'];
			++ $i;
		}
		echo json_encode ( $outputArray );
	} else if ($_POST ['task'] == 'search') {
		$accountTypeIds = $options->getOptionValueIds ( 'ACCTY', 1 );
		$employeeId = $_POST ['employeeId'];
		$i = 0;
		foreach ( $accountTypeIds as $accountTypeId ) {
			$bankAccountId = $bank->getEmployeeBankAccountId ( $employeeId, $accountTypeId );
			if (! $bankAccountId)
				$bankAccountId = $bank->setBankAccountDetails ( $employeeId, '', '', $accountTypeId );
			
			$details = $bank->getTableIdDetails ( $bankAccountId );
			if ($details ['bank_id'] != '')
				$bankDetails = $bank->getTableIdDetails ( $details ['bank_id'] );
			else
				$bankDetails = '';
			
			$outputArray [$i] [0] = $details ['id'];
			$outputArray [$i] [] = $options->getOptionIdValue ( $accountTypeId );
			$outputArray [$i] [] = $details ['account_number'];
			$outputArray [$i] [] = $bankDetails ['bank_name'] . " , " . $bankDetails ['branch_name'];
			
			++ $i;
		}
		
		echo json_encode ( $outputArray );
	} else if ($_POST ['task'] == 'getRecordIdDetails') {
		$id = htmlentities ( trim ( $_POST ['id'] ) );
		$details = $bank->getTableIdDetails ( $id );
		if ($details ['bank_id'] != '')
			$bankDetails = $bank->getTableIdDetails ( $details ['bank_id'] );
		else
			$bankDetails = '';
		
		$outputArray [0] = $details ['id'];
		
		$outputArray [] = $details ['account_number'];
		$outputArray [] = $options->getOptionIdValue ( $details ['account_type'] );
		
		$outputArray [] = $bankDetails ['bank_name'];
		$outputArray [] = $bankDetails ['branch_name'];
		
		$outputArray [] = $bankDetails ['ifsc_code'];
		$outputArray [] = $bankDetails ['micr_code'];
		
		$outputArray [] = $details ['last_update_date'];
		$outputArray [] = $bank->getOfficerName ( $details ['last_updated_by'] );
		$outputArray [] = $details ['creation_date'];
		$outputArray [] = $bank->getOfficerName ( $details ['created_by'] );
		$outputArray [] = $details ['active'];
		
		$outputArray [] = $details ['bank_id'];
		$outputArray [] = $details ['account_type'];
		
		echo json_encode ( $outputArray );
	} elseif ($_POST ['task'] == 'updateRecord') {
		$bankAccountId = $_POST ['valueId_u'];
		$details = $bank->getTableIdDetails ( $bankAccountId );
		
		if($details['account_number'] != $_POST['bankAccountNumber']){
			$bank->setUpdateLog('Account No from '.$details['account_number'].' to '.$_POST['bankAccountNumber']);
			$bank->updateTableParameter ( 'account_number', $_POST ['bankAccountNumber'] );
		}
		if($details['bank_id'] != $_POST['bankName']){
			$bank->setUpdateLog('Bank from '.$details['bank_id'].' to '.$_POST['bankName']);
			$bank->updateTableParameter ( 'bank_id', $_POST ['bankName'] );
		}			
		$bank->commitBankAccountDetailsUpdate ( $bankAccountId );
		
		$bankDetails = $bank->getTableIdDetails ( $_POST ['bankName'] );
		$outputArray [0] = $bankAccountId;
		$outputArray [] = $options->getOptionIdValue ( $details ['account_type'] );
		$outputArray [] = $details ['account_number'];
		$outputArray [] = $bankDetails ['bank_name'] . " , " . $bankDetails ['branch_name'];
		
		echo json_encode ( $outputArray );
	} else {
		$outputArray [0] = 0;
		echo json_encode ( $outputArray );
	}
}
?>