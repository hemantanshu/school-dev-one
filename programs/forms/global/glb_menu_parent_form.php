<?php
require_once 'config.php';
require_once BASE_PATH.'include/global/class.editMenu.php';
require_once BASE_PATH.'include/global/class.options.php';

$menu = new editMenu ();
$options = new options ();

$options->isRequestAuthorised4Form('LMENUL50');
	

if (isset ( $_POST ['task'] )) {
	if ($_POST ['task'] == 'insertRecord') {				
		$associationId = $menu->setMenuChildParentDetails($_POST['parentMenu'], $_POST['childMenu']);
        $outputArray[0] = 0;
		if($associationId){
			$details = $menu->getMenuChildParentIdDetails($associationId);
			$menuDetails = $menu->getMenuUrlIdDetails($details['parent_menu_id']);
			$outputArray[0] = $associationId;
			$outputArray[1] = $menuDetails['menu_name'];
			$outputArray[2] = $details['parent_menu_id'];
		}
		echo json_encode($outputArray);
	} else if ($_POST['task'] == 'search') {
        $menuId = $_POST['childMenu'];
        $search_type = htmlentities(trim($_POST['search_type']));
        $data = $menu->getMenuChildParentId($menuId, $search_type, true);
        $i = 0;
        $outputArray[0][0] = 1;
        foreach ($data as $id) {
            $details = $menu->getMenuChildParentIdDetails($id);
			$menuDetails = $menu->getMenuUrlIdDetails($details['parent_menu_id']);
            $outputArray[$i][0] = $id;
            $outputArray[$i][1] = $menuDetails['menu_name'];
            $outputArray[$i][2] = $details['parent_menu_id'];
            $i++;
        }
        echo json_encode($outputArray);
    }else if ($_POST['task'] == 'recordDetails') {
        $id = htmlentities(trim($_POST['id']));
        $details = $menu->getMenuChildParentIdDetails($id);

        $outputArray[0] = $details['last_update_date'];
        $outputArray[] = $options->getOfficerName($details['last_updated_by']);
        $outputArray[] = $details['creation_date'];
        $outputArray[] = $options->getOfficerName($details['created_by']);
        $outputArray[] = $details['active'];

        echo json_encode($outputArray);
    } elseif ($_POST['task'] == 'dropId') {
        $id = htmlentities(trim($_POST['id']));
        $menu->dropMenuChildParentDetails($id);
        $outputArray[0] = $id;
        echo json_encode($outputArray);       
     }elseif ($_POST['task'] == 'activateId') {
        $id = htmlentities(trim($_POST['id']));
        $menu->activateMenuChildParentUpdate($id);
        $outputArray[0] = $id; 
        echo json_encode($outputArray);       
     }elseif($_POST['task'] == 'authenticate'){

        if ($menu->checkParentChildMenuAvailability($_POST['parentMenu'], $_POST['childMenu']))
            $outputArray[0] = 0;
        else
            $outputArray[0] = 1;
        echo json_encode($outputArray);
    }else {
		$outputArray [0] = 0;
		echo json_encode ( $outputArray );
	}
}
?>