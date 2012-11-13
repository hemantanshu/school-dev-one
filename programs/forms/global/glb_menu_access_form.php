<?php
require_once 'config.php';
require_once BASE_PATH.'include/global/class.editMenu.php';
require_once BASE_PATH.'include/global/class.options.php';

$menu = new editMenu ();
$options = new options ();

$options->isRequestAuthorised4Form('LMENUL51');


if (isset ( $_POST ['task'] )) {
    if ($_POST ['task'] == 'insertRecord') {
        $associationId = $menu->setMenuAccessDetails($_POST['childMenu'], $_POST['parentMenu']);
        $outputArray[0] = 0;
        if($associationId){
            $details = $menu->getMenuAccessIdDetails($associationId);
            $menuDetails = $menu->getMenuUrlIdDetails($details['access_menu_id']);
            $outputArray[0] = $associationId;
            $outputArray[1] = $menuDetails['menu_name'];
            $outputArray[2] = $details['access_menu_id'];
            $outputArray[3] = $menuDetails['menu_url'];
        }
        echo json_encode($outputArray);
    } else if ($_POST['task'] == 'search') {
        $menuId = $_POST['childMenu'];
        $search_type = htmlentities(trim($_POST['search_type']));
        $data = $menu->getMenuAccessIds($menuId, $search_type, true);
        $i = 0;
        $outputArray[0][0] = 1;
        foreach ($data as $id) {
            $details = $menu->getMenuAccessIdDetails($id);
            $menuDetails = $menu->getMenuUrlIdDetails($details['access_menu_id']);
            $outputArray[$i][0] = $id;
            $outputArray[$i][1] = $menuDetails['menu_name'];
            $outputArray[$i][2] = $details['access_menu_id'];
            $outputArray[$i][3] = $menuDetails['menu_url'];
            $i++;
        }
        echo json_encode($outputArray);
    }else if ($_POST['task'] == 'recordDetails') {
        $id = htmlentities(trim($_POST['id']));
        $details = $menu->getMenuAccessIdDetails($id);

        $outputArray[0] = $details['last_update_date'];
        $outputArray[] = $options->getOfficerName($details['last_updated_by']);
        $outputArray[] = $details['creation_date'];
        $outputArray[] = $options->getOfficerName($details['created_by']);
        $outputArray[] = $details['active'];

        echo json_encode($outputArray);
    } elseif ($_POST['task'] == 'dropId') {
        $id = htmlentities(trim($_POST['id']));
        $menu->dropMenuAccessDetails($id);
        $outputArray[0] = $id;
        echo json_encode($outputArray);
    }elseif ($_POST['task'] == 'activateId') {
        $id = htmlentities(trim($_POST['id']));
        $menu->activateMenuAccessUpdate($id);
        $outputArray[0] = $id;
        echo json_encode($outputArray);
    }elseif($_POST['task'] == 'authenticate'){

        if ($menu->checkMenuAccessAvailability($_POST['childMenu'], $_POST['parentMenu']))
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