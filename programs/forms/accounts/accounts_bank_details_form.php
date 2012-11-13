<?php
require_once 'config.php';

require_once BASE_PATH . 'include/global/class.options.php';
require_once BASE_PATH . 'include/utility/class.address.php';
require_once BASE_PATH . 'include/accounts/class.bank.php';

$options = new options ();
$address = new address ();
$bank = new Bank ();

$options->isRequestAuthorised4Form ( 'LMENUL108' );

if (isset ( $_POST ['task'] )) {
	if ($_POST ['task'] == 'insertRecord') {
		$city = $_POST ['city_val'] == '' ? $options->setNewOptionValue ( $_POST ['city'], 'CITYS' ) : $_POST ['city_val'];
		$state = $_POST ['state_val'] == '' ? $options->setNewOptionValue ( $_POST ['state'], 'STATE' ) : $_POST ['state_val'];
		$country = $_POST ['country_val'] == '' ? $options->setNewOptionValue ( $_POST ['country'], 'CNTRY' ) : $_POST ['country_val'];
		$addressId = $address->setAddressDetails ( $_POST ['streetAddress1'], $_POST ['streetAddress2'], $city, $state, $country, $_POST ['pincode'] );
		
		$bankId = $bank->setBankDetails ( $_POST ['bankName'], $_POST ['branchName'], $_POST ['ifscCode'], $_POST ['micrCode'], $addressId );
		
		$outputArray = array ();
		$outputArray [0] = 0;
		if ($bankId) {
			$outputArray [0] = $bankId;
			$outputArray [] = $_POST ['bankName'];
			$outputArray [] = $_POST ['branchName'];
			$outputArray [] = $_POST ['ifscCode'];
			$outputArray [] = $_POST ['micrCode'];
		}
		echo json_encode ( $outputArray );
	} else if ($_POST ['task'] == 'search') {
		$hint = htmlentities ( trim ( $_POST ['hint'] ) );
		$search_type = htmlentities ( trim ( $_POST ['search_type'] ) );
		$data = $bank->getBankNameSearchIds ( $hint, $search_type );
		
		
		$i = 0;
		$outputArray [0] [0] = 1;
		foreach ( $data as $id ) {
			$details = $bank->getTableIdDetails($id);
			$outputArray [$i] [0] = $details ['id'];
			$outputArray [$i] [] = $details ['bank_name'];
			$outputArray [$i] [] = $details ['branch_name'];
			$outputArray [$i] [] = $details ['ifsc_code'];
			$outputArray [$i] [] = $details ['micr_code'];
			++ $i;
		}
		echo json_encode ( $outputArray );
	} else if ($_POST ['task'] == 'getRecordIdDetails') {
		$id = htmlentities ( trim ( $_POST ['id'] ) );
		$details = $bank->getTableIdDetails ( $id );
		$addressId = $details ['address_id'];
		
		$outputArray [0] = $details ['id'];
		$outputArray [] = $details ['bank_name'];
		$outputArray [] = $details ['branch_name'];
		$outputArray [] = $details ['ifsc_code'];
		$outputArray [] = $details ['micr_code'];
		
		$outputArray [] = $address->getAddressDisplay ( $addressId );
		$outputArray [] = $details ['last_update_date'];
		$outputArray [] = $bank->getOfficerName ( $details ['last_updated_by'] );
		$outputArray [] = $details ['creation_date'];
		$outputArray [] = $bank->getOfficerName ( $details ['created_by'] );
		$outputArray [] = $details ['active'];
		
		$details = $address->getAddressIdDetails ( $addressId );
		$outputArray [] = $details ['street_address'];
		$outputArray [] = $details ['street_address1'];
		$outputArray [] = $options->getOptionIdValue ( $details ['city'] );
		$outputArray [] = $details ['city'];
		$outputArray [] = $options->getOptionIdValue ( $details ['state'] );
		$outputArray [] = $details ['state'];
		$outputArray [] = $details ['pincode'];
		$outputArray [] = $options->getOptionIdValue ( $details ['country'] );
		$outputArray [] = $details ['country'];
		
		echo json_encode ( $outputArray );
	} elseif ($_POST ['task'] == 'updateRecord') {
		$bankId = $_POST ['valueId_u'];
		$details = $bank->getTableIdDetails ( $bankId );
		
		if($details['bank_name'] != $_POST['bankName_u']){
			$bank->setUpdateLog('Name from '.$details['bank_name'].' to '.$_POST['bankName_u']);
			$bank->updateTableParameter ( 'bank_name', $_POST ['bankName_u'] );
		}
		if($details['branch_name'] != $_POST['branchName_u']){
			$bank->setUpdateLog('Branch from '.$details['branch_name'].' to '.$_POST['branchName_u']);
			$bank->updateTableParameter ( 'branch_name', $_POST ['branchName_u'] );
		}
		if($details['ifsc_code'] != $_POST['ifscCode_u']){
			$bank->setUpdateLog('IFSC from '.$details['ifsc_code'].' to '.$_POST['ifscCode_u']);
			$bank->updateTableParameter ( 'ifsc_code', $_POST ['ifscCode_u'] );
		}
		if($details['micr_code'] != $_POST['micrCode_u']){
			$bank->setUpdateLog('MICR from '.$details['micr_code'].' to '.$_POST['micrCode_u']);
			$bank->updateTableParameter ( 'micr_code', $_POST ['micrCode_u'] );
		}
		$bank->commitBankDetailsUpdate ( $bankId );
		
		$details = $address->getTableIdDetails ( $details ['address_id'] );
		$city = $_POST ['city_uval'] == '' ? $options->setNewOptionValue ( $_POST ['city_u'], 'CITYS' ) : $_POST ['city_uval'];
		$state = $_POST ['state_uval'] == '' ? $options->setNewOptionValue ( $_POST ['state_u'], 'STATE' ) : $_POST ['state_uval'];
		$country = $_POST ['country_uval'] == '' ? $options->setNewOptionValue ( $_POST ['country_u'], 'CNTRY' ) : $_POST ['country_uval'];
		
		if($details['street_address'] != $_POST['streetAddress1_u']){
			$address->setUpdateLog('House No from '.$details['street_address'].' to '.$_POST['streetAddress1_u']);
			$address->updateTableParameter ( 'street_address', $_POST ['streetAddress1_u'] );
		}
		if($details['street_address1'] != $_POST['streetAddress2_u']){
			$address->setUpdateLog('Street Address from '.$details['street_address1'].' to '.$_POST['streetAddress2_u']);
			$address->updateTableParameter ( 'street_address1', $_POST ['streetAddress2_u'] );
		}
		if($details['city'] != $city){
			$address->setUpdateLog('City from '.$details['city'].' to '.$city);
			$address->updateTableParameter ( 'city', $city );
		}
		if($details['state'] != $state){
			$address->setUpdateLog('State from '.$details['state'].' to '.$state);
			$address->updateTableParameter ( 'state', $state );
		}
		if($details['country'] != $country){
			$address->setUpdateLog('Country from '.$details['country'].' to '.$country);
			$address->updateTableParameter ( 'country', $country );
		}
		if($details['pincode'] != $_POST['pincode_u']){
			$address->setUpdateLog('Pincode from '.$details['pincode'].' to '.$_POST['pincode_u']);
			$address->updateTableParameter ( 'pincode', $_POST ['pincode_u'] );
		}	
		$address->commitAddressUpdate ( $details ['id'] );
		
		$outputArray [0] = $bankId;
		$outputArray [] = $_POST ['bankName_u'];
		$outputArray [] = $_POST ['branchName_u'];
		$outputArray [] = $_POST ['ifscCode_u'];
		$outputArray [] = $_POST ['micrCode_u'];
				
		echo json_encode ( $outputArray );	
	} elseif ($_POST ['task'] == 'dropRecord') {
		$id = htmlentities ( trim ( $_POST ['id'] ) );
		$bank->dropBankDetails($id);
		$outputArray [0] = $id;
		echo json_encode ( $outputArray );
	} elseif ($_POST ['task'] == 'activateRecord') {
		$id = htmlentities ( trim ( $_POST ['id'] ) );
		$bank->activateBankDetails($id);
		$outputArray [0] = $id;
		echo json_encode ( $outputArray );
	} else {
		$outputArray [0] = 0;
		echo json_encode ( $outputArray );
	}
}
?>