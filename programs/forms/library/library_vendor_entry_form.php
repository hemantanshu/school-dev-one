<?php
require_once 'config.php';

require_once BASE_PATH . 'include/global/class.options.php';
require_once BASE_PATH . 'include/utility/class.address.php';
require_once BASE_PATH . 'include/library/class.libraryVendor.php';

$address = new address ();
$vendor = new Vendor ();
$options = new options ();

$vendor->isRequestAuthorised4Form ( 'LMENUL139' );

if (isset ( $_POST ['task'] )) {
	if ($_POST ['task'] == 'insertRecord') {
		
		$city = $_POST ['city_val'] == '' ? $options->setNewOptionValue ( $_POST ['city'], 'CITYS' ) : $_POST ['city_val'];
		$state = $_POST ['state_val'] == '' ? $options->setNewOptionValue ( $_POST ['state'], 'STATE' ) : $_POST ['state_val'];
		$country = $_POST ['country_val'] == '' ? $options->setNewOptionValue ( $_POST ['country'], 'CNTRY' ) : $_POST ['country_val'];
		
		$addressId = $address->setAddressDetails ( $_POST ['streetAddress1'], $_POST ['streetAddress2'], $city, $state, $country, $_POST ['pincode'] );
		
		$vendorId = $vendor->setVendorRecord ( $_POST ['vendorName'], $addressId, $_POST ['weightage'], $_POST ['contactNo'], $_POST ['emailId'] );
		
		$outputArray = array ();
		$outputArray [0] = 0;
		if ($vendorId) {
			$outputArray [0] = $vendorId;
			$outputArray [] = $_POST ['vendorName'];
			$outputArray [] = $_POST ['weightage'];
			$outputArray [] = $_POST ['contactNo'];
		}
		echo json_encode ( $outputArray );
	} else if ($_POST ['task'] == 'search') {
		$hint = htmlentities ( trim ( $_POST ['hint'] ) );
		$search_type = htmlentities ( trim ( $_POST ['search_type'] ) );
		$data = $vendor->getVendorIds ( $hint, $search_type );
		$i = 0;
		$outputArray [0] [0] = 1;
		foreach ( $data as $id ) {
			$details = $vendor->getTableIdDetails ( $id );
			$outputArray [$i] [0] = $details ['id'];
			$outputArray [$i] [] = $details ['vendor_name'];
			$outputArray [$i] [] = $details ['weightage'];
			$outputArray [$i] [] = $details ['contact_number'];
			++ $i;
		}
		echo json_encode ( $outputArray );
	} else if ($_POST ['task'] == 'getRecordIdDetails') {
		$id = htmlentities ( trim ( $_POST ['id'] ) );
		$details = $vendor->getTableIdDetails ( $id );
		
		$outputArray [0] = $details ['id'];
		$outputArray [] = $details ['vendor_name'];
		$outputArray [] = $details ['weightage'];
		$outputArray [] = $details ['contact_number'];
		$outputArray [] = $details ['email_id'];
		$outputArray [] = $address->getAddressDisplay ( $details ['vendor_address'] );
		
		$categoryIds = $vendor->getVendorCategories ( $id );
		foreach ( $categoryIds as $categoryId ) {
			$category .= $options->getOptionIdValue ( $categoryId ) . " ";
		}
		$outputArray [] = $category;
		
		$tagIds = $vendor->getVendorTags ( $id );
		foreach ( $tagIds as $tagId )
			$tags .= $options->getOptionIdValue ( $tagId ) . ' ';
		$outputArray [] = $tags;
		
		$outputArray [] = $details ['last_update_date'];
		$outputArray [] = $vendor->getOfficerName ( $details ['last_updated_by'] );
		$outputArray [] = $details ['creation_date'];
		$outputArray [] = $vendor->getOfficerName ( $details ['created_by'] );
		$outputArray [] = $details ['active'];
		
		$details = $address->getTableIdDetails($details['vendor_address']);
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
		$vendorId = $_POST ['valueId_u'];
		
		$details = $vendor->getTableIdDetails ( $vendorId );
		
		if ($details ['vendor_name'] != $_POST ['vendorName_u']) {
			$vendor->setUpdateLog ( 'Name from ' . $details ['vendor_name'] . ' to ' . $_POST ['vendorName_u'] );
			$vendor->updateTableParameter ( 'vendor_name', $_POST ['vendorName_u'] );
		}
		if ($details ['weightage'] != $_POST ['weightage_u']) {
			$vendor->setUpdateLog ( 'Win Rate from ' . $details ['weightage'] . ' to ' . $_POST ['weightage_u'] );
			$vendor->updateTableParameter ( 'weightage', $_POST ['weightage_u'] );
		}
		if ($details ['contact_number'] != $_POST ['contactNo_u']) {
			$vendor->setUpdateLog ( 'Contact No from ' . $details ['contact_number'] . ' to ' . $_POST ['contactNo_u'] );
			$vendor->updateTableParameter ( 'contact_number', $_POST ['contactNo_u'] );
		}
		if ($details ['email_id'] != $_POST ['emailId_u']) {
			$vendor->setUpdateLog ( 'Email from ' . $details ['email_id'] . ' to ' . $_POST ['emailId_u'] );
			$vendor->updateTableParameter ( 'email_id', $_POST ['emailId_u'] );
		}
		$vendor->commitVendorRecordUpdate ( $vendorId );
		
		$details = $address->getTableIdDetails ( $details ['vendor_address'] );
		$city = $_POST ['city_uval'] == '' ? $options->setNewOptionValue ( $_POST ['city_u'], 'CITYS' ) : $_POST ['city_uval'];
		$state = $_POST ['state_uval'] == '' ? $options->setNewOptionValue ( $_POST ['state_u'], 'STATE' ) : $_POST ['state_uval'];
		$country = $_POST ['country_uval'] == '' ? $options->setNewOptionValue ( $_POST ['country_u'], 'CNTRY' ) : $_POST ['country_uval'];
		
		if ($details ['street_address'] != $_POST ['streetAddress1_u']) {
			$address->setUpdateLog ( 'House No from ' . $details ['street_address'] . ' to ' . $_POST ['streetAddress1_u'] );
			$address->updateTableParameter ( 'street_address', $_POST ['streetAddress1_u'] );
		}
		if ($details ['street_address1'] != $_POST ['streetAddress2_u']) {
			$address->setUpdateLog ( 'Street Address from ' . $details ['street_address1'] . ' to ' . $_POST ['streetAddress2_u'] );
			$address->updateTableParameter ( 'street_address1', $_POST ['streetAddress2_u'] );
		}
		if ($details ['city'] != $city) {
			$address->setUpdateLog ( 'City from ' . $details ['city'] . ' to ' . $city );
			$address->updateTableParameter ( 'city', $city );
		}
		if ($details ['state'] != $state) {
			$address->setUpdateLog ( 'State from ' . $details ['state'] . ' to ' . $state );
			$address->updateTableParameter ( 'state', $state );
		}
		if ($details ['country'] != $country) {
			$address->setUpdateLog ( 'Country from ' . $details ['country'] . ' to ' . $country );
			$address->updateTableParameter ( 'country', $country );
		}
		if ($details ['pincode'] != $_POST ['pincode_u']) {
			$address->setUpdateLog ( 'Pincode from ' . $details ['pincode'] . ' to ' . $_POST ['pincode_u'] );
			$address->updateTableParameter ( 'pincode', $_POST ['pincode_u'] );
		}
		$address->commitAddressUpdate($addressId);
		
		$outputArray [0] = $vendorId;
		$outputArray [] = $_POST ['vendorName_u'];
		$outputArray [] = $_POST ['weightage_u'];
		$outputArray [] = $_POST ['contactNo_u'];
		
		echo json_encode ( $outputArray );
	} elseif ($_POST ['task'] == 'dropRecord') {
		$id = htmlentities ( trim ( $_POST ['id'] ) );
		$vendor->dropVendorRecord ( $id );
		$outputArray [0] = $id;
		echo json_encode ( $outputArray );
	} elseif ($_POST ['task'] == 'activateRecord') {
		$id = htmlentities ( trim ( $_POST ['id'] ) );
		$vendor->activateVendorRecord ( $id );
		$outputArray [0] = $id;
		echo json_encode ( $outputArray );
	} else {
		$outputArray [0] = 0;
		echo json_encode ( $outputArray );
	}
}
?>