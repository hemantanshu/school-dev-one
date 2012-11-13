<?php
require_once 'config.php';
require_once BASE_PATH . 'include/exam/class.resultType.php';
require_once BASE_PATH . 'include/global/class.menu.php';

$resultType = new ResultType ();
$menu = new menu();

$resultType->isRequestAuthorised4Form ( 'LMENUL150' );

if (isset ( $_POST ['task'] )) {
	if ($_POST ['task'] == 'insertRecord') {
		$resultTypeId = $resultType->setResultTypeUrls ( $_POST ['resultTypeId'], $_POST ['displayName'], $_POST ['url_val'], $_POST ['urlType'], $_POST ['displayOrder'] );
		if ($resultTypeId) {
			$outputArray [0] = $resultTypeId;
			$outputArray [] = $_POST ['displayName'];
			$outputArray [] = $menu->getOptionIdValue ( $_POST ['urlType'] );
		} else
			$outputArray [0] = 0;
		echo json_encode ( $outputArray );
	} else if ($_POST ['task'] == 'search') {
		$search_type = $_POST ['search_type'];
		$hint = $_POST ['hint'];
		$resultTypeId = $_POST ['resultTypeId'];
		$resultTypeIds = $resultType->getResultTypeUrls ( $resultTypeId, $hint, $search_type );
		$outputArray [0] [0] = 1;
		$i = 0;
		foreach ( $resultTypeIds as $resultTypeId ) {
			$details = $resultType->getTableIdDetails ( $resultTypeId );
			$outputArray [$i] [0] = $resultTypeId;
			$outputArray [$i] [] = $details ['display_name'];
			$outputArray [$i] [] = $menu->getOptionIdValue ( $details ['url_type'] );
			++ $i;
		}
		echo json_encode ( $outputArray );
	} else if ($_POST ['task'] == 'getRecordIdDetails') {
		$id = $_POST ['id'];
		$details = $resultType->getTableIdDetails ( $id );
		$menuDetails = $resultType->getTableIdDetails($details['url']);
		$outputArray [0] = $details ['id'];
		$outputArray [] = $details ['display_name'];
		$outputArray [] = $details ['url'];
		$outputArray [] = $details ['url_type'];
		
		$outputArray [] = $details ['last_update_date'];
		$outputArray [] = $resultType->getOfficerName ( $details ['last_updated_by'] );
		$outputArray [] = $details ['creation_date'];
		$outputArray [] = $resultType->getOfficerName ( $details ['created_by'] ); // 10
		
		$outputArray [] = $details ['active'];
		
		$outputArray [] = $menu->getOptionIdValue ( $details ['url_type'] );
		$outputArray [] = $details ['display_order'];
		$outputArray [] = $menuDetails['menu_name'];
		
		echo json_encode ( $outputArray );
	} elseif ($_POST ['task'] == 'updateRecord') {
		$id = $_POST ['valueId_u'];
		$details = $resultType->getTableIdDetails ( $id );
		
		if ($details ['display_name'] != $_POST ['displayName_u']) {
			$resultType->setUpdateLog ( 'Name from ' . $details ['display_name'] . ' to ' . $_POST ['displayName_u'] );
			$resultType->updateTableParameter ( 'display_name', $_POST ['displayName_u'] );
		}
		if ($details ['url'] != $_POST ['url_uval']) {
			$resultType->setUpdateLog ( 'Order from ' . $details ['url'] . ' to ' . $_POST ['url_uval'] );
			$resultType->updateTableParameter ( 'url', $_POST ['url_uval'] );
		}
		if ($details ['url_type'] != $_POST ['urlType_u']) {
			$resultType->setUpdateLog ( 'Type from ' . $details ['url_type'] . ' to ' . $_POST ['urlType_u'] );
			$resultType->updateTableParameter ( 'url_type', $_POST ['urlType_u'] );
		}
		if ($details ['display_order'] != $_POST ['displayOrder_u']) {
			$resultType->setUpdateLog ( 'Order from ' . $details ['display_order'] . ' to ' . $_POST ['displayOrder_u'] );
			$resultType->updateTableParameter ( 'display_order', $_POST ['displayOrder_u'] );
		}
		
		if ($resultType->commitResultTypeUrlsUpdate ( $id )) {
			$outputArray [0] = $id;
			$outputArray [] = $_POST ['displayName_u'];
			$outputArray [] = $menu->getOptionIdValue ( $_POST ['urlType_u'] );
		} else {
			$outputArray [] = 0;
		}
		echo json_encode ( $outputArray );
	} elseif ($_POST ['task'] == 'fetchResultTypeUrl') {
		$resultTypeId = $_POST ['resultTypeId'];
		$resultUrlIds = $resultType->getResultTypeUrlIds4Code ( $resultTypeId, 'LRESER24', '', 1 );
		$i = 0;
		foreach ( $resultUrlIds as $resultUrlId ) {
			$details = $resultType->getTableIdDetails ( $resultUrlId );
			$url = $menu->generateMenuUrl($details['url']);
			$outputArray [$i] [0] = $details ['display_name'];
			$outputArray [$i] [] = $url[0];
			++ $i;
		}
		echo json_encode ( $outputArray );
	} elseif ($_POST ['task'] == 'dropRecord') {
		$id = htmlentities ( trim ( $_POST ['id'] ) );
		$resultType->dropResultTypeUrl ( $id );
		$outputArray [0] = $id;
		echo json_encode ( $outputArray );
	} elseif ($_POST ['task'] == 'activateRecord') {
		$id = htmlentities ( trim ( $_POST ['id'] ) );
		$resultType->activateResultTypeUrl ( $id );
		$outputArray [0] = $id;
		echo json_encode ( $outputArray );
	} else {
		$outputArray [0] = 0;
		echo json_encode ( $outputArray );
	}
}
?>