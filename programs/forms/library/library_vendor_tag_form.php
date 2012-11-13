<?php
require_once 'config.php';

require_once BASE_PATH . 'include/global/class.options.php';
require_once BASE_PATH . 'include/library/class.libraryVendor.php';

$vendor = new Vendor ();
$options = new options ();

$vendor->isRequestAuthorised4Form ( 'LMENUL142' );

if (isset ( $_POST ['task'] )) {
	if ($_POST ['task'] == 'insertRecord') {
		$vendorId = $_POST['vendorId'];
		$tagId = $_POST ['tag_val'] == '' ? $options->setNewOptionValue ( $_POST ['tag'], 'LITAG' ) : $_POST ['tag_val'];	
		$vendorTagId = $vendor->setVendorTag($vendorId, $tagId);
		
		$outputArray = array ();
		$outputArray [0] = 0;
		if ($vendorTagId) {
			$outputArray [0] = $vendorTagId;
			$outputArray [] = $_POST ['tag'];
		}
		echo json_encode ( $outputArray );
	} else if ($_POST ['task'] == 'search') {
		$vendorId = $_POST['vendorId'];
		$search_type = htmlentities ( trim ( $_POST ['search_type'] ) );
		$data = $vendor->getVendorTagIds($vendorId, $search_type);
		$i = 0;
		$outputArray [0] [0] = 1;
		foreach ( $data as $id ) {
			$details = $vendor->getTableIdDetails ( $id );
			$outputArray [$i] [0] = $details ['id'];
			$outputArray [$i] [] = $options->getOptionIdValue($details ['tag_id']);			
			++ $i;
		}
		echo json_encode ( $outputArray );
	} else if ($_POST ['task'] == 'getRecordIdDetails') {
		$id = htmlentities ( trim ( $_POST ['id'] ) );
		$details = $vendor->getTableIdDetails ( $id );
		
		$outputArray [0] = $details ['id'];
		$outputArray [] = $options->getOptionIdValue($details ['tag_id']);
		
		$outputArray [] = $details ['last_update_date'];
		$outputArray [] = $vendor->getOfficerName ( $details ['last_updated_by'] );
		$outputArray [] = $details ['creation_date'];
		$outputArray [] = $vendor->getOfficerName ( $details ['created_by'] );
		$outputArray [] = $details ['active'];	
		
		echo json_encode ( $outputArray );
	}elseif ($_POST ['task'] == 'dropRecord') {
		$id = htmlentities ( trim ( $_POST ['id'] ) );
		$vendor->dropVendorTag($id);
		$outputArray [0] = $id;
		echo json_encode ( $outputArray );
	} elseif ($_POST ['task'] == 'activateRecord') {
		$id = htmlentities ( trim ( $_POST ['id'] ) );
		$vendor->activateVendorTag($id);
		$outputArray [0] = $id;
		echo json_encode ( $outputArray );
	} else {
		$outputArray [0] = 0;
		echo json_encode ( $outputArray );
	}
}
?>