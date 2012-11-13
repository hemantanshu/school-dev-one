<?php
require_once 'config.php';

require_once BASE_PATH . 'include/global/class.options.php';
require_once BASE_PATH . 'include/utility/class.address.php';
require_once BASE_PATH . 'include/utility/class.institute.php';

$options = new options ();
$address = new address ();
$institute = new institute ();

$options->isRequestAuthorised4Form('LMENUL32');

if (isset ( $_POST ['task'] )) {
	if ($_POST ['task'] == 'insertRecord') {
		$universityId = $_POST ['university_val'] == '' ? $options->setNewOptionValue ( $_POST ['university'], 'UVSTY' ) : $_POST ['university_val'];
		$id = $institute->setInstituteDetails ( $_POST ['collegeName'], $universityId, $_POST ['contactno'] );
		
		$city = $_POST ['city_val'] == '' ? $options->setNewOptionValue ( $_POST ['city'], 'CITYS' ) : $_POST ['city_val'];
		$state = $_POST ['state_val'] == '' ? $options->setNewOptionValue ( $_POST ['state'], 'STATE' ) : $_POST ['state_val'];
		$country = $_POST ['country_val'] == '' ? $options->setNewOptionValue ( $_POST ['country'], 'CNTRY' ) : $_POST ['country_val'];
		
		$addressId = $address->setAddressDetails ( $_POST ['streetAddress1'], $_POST ['streetAddress2'], $city, $state, $country, $_POST ['pincode'] );
		
		$institute->updateTableParameter ( 'address_id', $addressId );
		$institute->commitInstituteDetailsUpdate ( $id );
		
		$outputArray = array ();
		$outputArray [0] = 0;
		if ($id) {
			$details = $institute->getInstituteIdDetails ( $id );
			$outputArray [0] = $details ['id'];
			$outputArray [1] = $details ['institute_name'];
			$outputArray [2] = $options->getOptionIdValue ( $details ['university_id'] );
		}
		echo json_encode ( $outputArray );
	} else if ($_POST ['task'] == 'search') {
		$hint = htmlentities ( trim ( $_POST ['hint'] ) );
		$search_type = htmlentities ( trim ( $_POST ['search_type'] ) );
		$data = $institute->getInstituteSearchIds ( $hint, $search_type );
		$i = 0;
		$outputArray [0] [0] = 1;
		foreach ( $data as $id ) {
			$details = $institute->getInstituteIdDetails ( $id );
			$outputArray [$i] [0] = $details ['id'];
			$outputArray [$i] [1] = $details ['institute_name'];
			$outputArray [$i] [2] = $options->getOptionIdValue ( $details ['university_id'] );
			++ $i;
		}
		echo json_encode ( $outputArray );
	} else if ($_POST ['task'] == 'getRecordIdDetails') {
		$id = htmlentities ( trim ( $_POST ['id'] ) );
		$details = $institute->getInstituteIdDetails ( $id );
		
		$outputArray [0] = $details ['id'];
		$outputArray [1] = $details ['institute_name'];
		$outputArray [2] = $details ['university_id'];
		$outputArray [3] = $options->getOptionIdValue ( $details ['university_id'] );
		$outputArray [4] = $details ['contact_number'];
		$outputArray [5] = $details ['last_update_date'];
		$outputArray [6] = $institute->getOfficerName ( $details ['last_updated_by'] );
		$outputArray [7] = $details ['creation_date'];
		$outputArray [8] = $institute->getOfficerName ( $details ['created_by'] );
		$outputArray [9] = $details ['active'];
		$outputArray [10] = $details ['address_id'];
		
		$details = $address->getAddressIdDetails ( $details ['address_id'] );
		$outputArray [11] = $details ['street_address'];
		$outputArray [12] = $details ['street_address1'];
		$outputArray [13] = $options->getOptionIdValue ( $details ['city'] );
		$outputArray [14] = $details ['city'];
		$outputArray [15] = $options->getOptionIdValue ( $details ['state'] );
		$outputArray [16] = $details ['state'];
		$outputArray [17] = $details ['pincode'];
		$outputArray [18] = $options->getOptionIdValue ( $details ['country'] );
		$outputArray [19] = $details ['country'];
		
		$outputArray [20] = $details ['street_address'] . ',' . $details ['street_address1'] . "<br />" . $outputArray [13] . "," . $outputArray [15] . " " . $outputArray [18] . "-" . $outputArray [17];
		
		echo json_encode ( $outputArray );
	} elseif ($_POST ['task'] == 'updateRecord') {
		$instituteId = $_POST ['recordId_u'];
		$details = $institute->getInstituteIdDetails ( $instituteId );
		$universityId = $_POST ['university_uval'] == '' ? $options->setNewOptionValue ( $_POST ['university_u'], 'UVSTY' ) : $_POST ['university_uval'];
		
		if($details['institute_name'] != $_POST['collegeName_u']){
			$institute->setUpdateLog('Name from '.$details['institute_name'].' to '.$_POST['collegeName_u']);
			$institute->updateTableParameter ( 'institute_name', $_POST ['collegeName_u'] );
		}
		if($details['university_id'] != $universityId){
			$institute->setUpdateLog('University from '.$details['university_id'].' to '.$universityId);
			$institute->updateTableParameter ( 'university_id', $universityId );
		}
		if($details['contact_number'] != $_POST['contactno_u']){
			$institute->setUpdateLog('Contact no from '.$details['contact_number'].' to '.$_POST['contactno_u']);
			$institute->updateTableParameter ( 'contact_number', $_POST ['contactno_u'] );
		}
		$institute->commitInstituteDetailsUpdate ( $instituteId );
		
		$city = $_POST ['city_uval'] == '' ? $options->setNewOptionValue ( $_POST ['city_u'], 'CITYS' ) : $_POST ['city_uval'];
		$state = $_POST ['state_uval'] == '' ? $options->setNewOptionValue ( $_POST ['state_u'], 'STATE' ) : $_POST ['state_uval'];
		$country = $_POST ['country_uval'] == '' ? $options->setNewOptionValue ( $_POST ['country_u'], 'CNTRY' ) : $_POST ['country_uval'];
		
		$details = $address->getTableIdDetails($details['address_id']);
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
		$address->commitAddressUpdate($details['address_id']);
		
		$details = $institute->getInstituteIdDetails ( $instituteId );
		$outputArray [0] = $details ['id'];
		$outputArray [1] = $details ['institute_name'];
		$outputArray [2] = $options->getOptionIdValue ( $details ['university_id'] );
		
		echo json_encode ( $outputArray );
	
	} elseif ($_POST ['task'] == 'dropRecord') {
		$id = htmlentities ( trim ( $_POST ['id'] ) );
		$institute->dropInstituteDetails($id);
		$outputArray [0] = $id;
		echo json_encode ( $outputArray );
	} elseif ($_POST ['task'] == 'activateRecord') {
		$id = htmlentities ( trim ( $_POST ['id'] ) );
		$institute->activateInstituteDetails($id);
		$outputArray [0] = $id;
		echo json_encode ( $outputArray );
	} else {
		$outputArray [0] = 0;
		echo json_encode ( $outputArray );
	}
}
?>