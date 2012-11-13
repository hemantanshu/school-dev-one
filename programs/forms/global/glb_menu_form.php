<?php
	require_once 'config.php';
    require_once BASE_PATH.'include/global/class.menu.php';    
    $menu = new menu();
    
    $menu->isRequestAuthorised4Form('LMENUL52');
    
    if(isset($_GET['q']) && isset($_GET['type'])){
        if($_GET['type'] == 'menu_url'){
            $urlIds = $menu->getMenuUrlSearchIds($_GET['q'], 1);
            foreach ($urlIds as $urlId){
                $details = $menu->getMenuUrlIdDetails($urlId);
                echo $details[1]."|".$urlId."\n";
            }
        }
        if($_GET['type'] =='menu_submenu'){
        	$submenuIds = $menu->getMenuSubmenuSearchIds($_GET['q'], 1);
        	foreach ($submenuIds as $id){
        		if($_GET['submenuIds'] == $id)
        			continue;
        		$details = $menu->getMenuSubmenuIdDetails($id);
        		echo $details[1]."|".$id."\n";
        	}
        }
        if($_GET['type'] =='menu_top'){
        	$topMenuIds = $menu->getMenuTopSearchIds($_GET['q'], 1);
        	foreach ($topMenuIds as $id){        		
        		$details = $menu->getMenuTopIdDetails($id);
        		echo $details[1]."|".$id."\n";
        	}
        }
    } 
?><p><font color="#000000">Your Input</font></p>