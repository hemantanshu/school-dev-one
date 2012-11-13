<?php
require_once 'config.php';

require_once BASE_PATH . 'include/library/class.libraryVendor.php';

$vendor = new Vendor ();
$vendor->isRequestAuthorised4Form ( 'LMENUL143' );

if (isset ( $_POST ['task'] )) {
	if ($_POST ['task'] == 'search') {
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
			$outputArray [$i] [] = $details ['email_id'];
			++ $i;
		}
		echo json_encode ( $outputArray );
	}else {
		$outputArray [0] = 0;
		echo json_encode ( $outputArray );
	}
}
?>