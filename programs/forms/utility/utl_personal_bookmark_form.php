<?php
require_once 'config.php';

require_once BASE_PATH . 'include/utility/class.bookmark.php';
require_once BASE_PATH . 'include/global/class.menuTask.php';

$bookmark = new Bookmark();
$menuTask = new MenuTask();

if(!$bookmark->isUserLogged(true)){
	echo json_encode(401);
	exit(0);
}

if (isset ( $_POST ['task'] )) {
	if ($_POST ['task'] == 'insertRecord') {
		$id = $bookmark->setUserBookmarkDetails($bookmark->getLoggedUserId(), $_POST['pageName'], $_POST['newurl'], $_POST['priority'], $_POST['redirect']);
		$outputArray = array ();
		$outputArray [0] = 0;
		if ($id) {
			$details = $bookmark->getBookmarkIdDetails($id);
			$outputArray [0] = $details ['id'];
			$outputArray [] = $details ['page_name'];
			$outputArray [] = $details ['url'];
			$outputArray [] = $details ['priority'];
		}
		echo json_encode ( $outputArray );
	} else if ($_POST ['task'] == 'search') {
		$hint = htmlentities ( trim ( $_POST ['hint'] ) );
		$search_type = htmlentities ( trim ( $_POST ['search_type'] ) );
		$data = $bookmark->getUserBookmarkIds($bookmark->getLoggedUserId(), $search_type, $hint);
		$i = 0;
		$outputArray [0] [0] = 1;
		foreach ( $data as $id ) {
			$details = $bookmark->getBookmarkIdDetails($id);
			$outputArray [$i][0] = $details ['id'];
			$outputArray [$i][] = $details ['page_name'];
			$outputArray [$i][] = $details ['url'];
			$outputArray [$i][] = $details ['priority'];
			$i ++;
		}
		echo json_encode ( $outputArray );
	} else if ($_POST ['task'] == 'getRecordIdDetails') {
		$id = htmlentities ( trim ( $_POST ['id'] ) );
		$details = $bookmark->getBookmarkIdDetails($id);
		$outputArray [0] = $details ['id'];
		$outputArray [] = $details ['page_name'];
		$outputArray [] = $details['url'];
		$outputArray [] = $details['priority'];
		$outputArray [] = $details['redirect'] == 'n' ? '<font class="green">Parent</font>': '<font class="red">Blank</font>';
		$outputArray [] = $details ['last_update_date'];
		$outputArray [] = $bookmark->getOfficerName ( $details ['last_updated_by'] );
		$outputArray [] = $details ['creation_date'];
		$outputArray [] = $bookmark->getOfficerName ( $details ['created_by'] );
		$outputArray [] = $details ['active'];
		
		// extra details for the edit form
		$outputArray [] = $details ['redirect'];
		
		echo json_encode ( $outputArray );
	} elseif ($_POST ['task'] == 'updateRecord') {
		$bookmarkId = $_POST ['valueId_u'];
		$details = $bookmark->getBookmarkIdDetails($bookmarkId);

		if($details['page_name'] != $_POST['pageName_u']){
			$bookmark->setUpdateLog('Name from '.$details['page_name'].' to '.$_POST['pageName_u']);
			$bookmark->updateTableParameter('page_name', $_POST['pageName_u']);
		}
		if($details['url'] != $_POST['newurl']){
			$bookmark->setUpdateLog('URL from '.$details['url'].' to '.$_POST['newurl']);
			$bookmark->updateTableParameter('url', $_POST['newurl']);
		}
		if($details['redirect'] != $_POST['redirect_u']){
			$bookmark->setUpdateLog('Redirect from '.$details['redirect'].' to '.$_POST['redirect_u']);
			$bookmark->updateTableParameter('redirect', $_POST['redirect_u']);
		}
		if($details['priority'] != $_POST['priority_u']){
			$bookmark->setUpdateLog('Priority from '.$details['priority'].' to '.$_POST['priority_u']);
			$bookmark->updateTableParameter('priority', $_POST['priority_u']);
		}     
		
		$bookmark->commitBookmarkDetailsUpdate($bookmarkId);
		
		$details = $bookmark->getBookmarkIdDetails($bookmarkId);
        $outputArray [0] = $details ['id'];
        $outputArray [] = $details ['page_name'];
        $outputArray [] = $details ['url'];
        $outputArray [] = $details ['priority'];
		echo json_encode ( $outputArray );
	
	} elseif($_POST['task'] == 'getBookmarkMenus'){
        $data = $bookmark->getUserBookmarkIds($bookmark->getLoggedUserId(), 1 , '');
        $i = 0;
        $outputArray [0] [0] = 0;
        foreach ( $data as $id ) {
            $details = $bookmark->getBookmarkIdDetails($id);
            $outputArray [$i][0] = $details ['page_name'];
            $outputArray [$i][1] = $details ['url'];
            $outputArray [$i][2] = $details ['redirect'] != 'y' ? '_parent' : '_blank';
            $i ++;
        }
        echo json_encode ( $outputArray );
    }elseif($_POST['task'] == 'getTaskMenus'){
    	$ids = $menuTask->getUserMenuTaskRecords($bookmark->getLoggedUserId(), 1);
    	$i = 0;
    	$outputArray[0][0] = 0;
    	foreach($ids as $id){
    		$details = $menuTask->getTableIdDetails($id);
    		$url = $menuTask->getBaseServer().$details['menu_url']."?referenceId=".$id;    		
    		$outputArray[$i][0] = ucwords($details['menu_display_name']);
    		$outputArray[$i][] = $url;
    		$outputArray[$i][] = '_parent';
    		$outputArray[$i][] = $id;
    		++$i;
    	}
    	echo json_encode($outputArray);
    }
    elseif ($_POST ['task'] == 'dropRecord') {
		$id = htmlentities ( trim ( $_POST ['id'] ) );
		$bookmark->dropBookmarkDetails($id);
		$outputArray [0] = $id;
		echo json_encode ( $outputArray );
	} elseif ($_POST ['task'] == 'activateRecord') {
		$id = htmlentities ( trim ( $_POST ['id'] ) );
		$bookmark->activateBookmarkDetails($id);
		$outputArray [0] = $id;
		echo json_encode ( $outputArray );
	} else {
		$outputArray [0] = 0;
		echo json_encode ( $outputArray );
	}
}
?>