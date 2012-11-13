<?php
require_once 'config.php';

if (isset($_POST['task'])) {
    if ($_POST['task'] == 'insertRecord') {
        $outputArray = array();
        array_push($outputArray, 'column1');
        array_push($outputArray, 'column2');
        array_push($outputArray, 'column3');
        array_push($outputArray, 'column4');
        array_push($outputArray, 'column5');
        array_push($outputArray, 'column6');
        array_push($outputArray, 'column7');
        
        echo json_encode($outputArray);
    } else if ($_POST['task'] == 'search') {
        $hint = htmlentities(trim($_POST['searchKey']));
        $search_type = htmlentities(trim($_POST['search_type']));
        $i = 0;        
        $outputArray[0][0] = 1;
        while($i < 5) {
        	$outputArray[$i] = array();
            $outputArray[$i][0] = 'id'.$i;
            $outputArray[$i][] = 'column1'.$i;
            $outputArray[$i][] = 'column2'.$i;
            $outputArray[$i][] = 'column3'.$i;
            $outputArray[$i][] = 'column4'.$i;
            $outputArray[$i][] = 'column5'.$i;
            $outputArray[$i][] = 'column6'.$i;
            $outputArray[$i][] = 'column7'.$i;
            $outputArray[$i][] = 'column8'.$i;            
            $i++;            
        }
        echo json_encode($outputArray);
    }else if ($_POST['task'] == 'getRecordIdDetails') {
    	$outputArray[0] = 1;
    	foreach ($_POST as $value)
    		$outputArray[] = $value;
    	$i = 0;
    	while($i < 15) {
    		$outputArray[] = 'column'.$i;
    		$i++;
    	}    	
    	echo json_encode($outputArray);
    }elseif($_POST['task'] == 'updateMenuUrl'){        
    	array_push($outputArray, 'column1');
    	array_push($outputArray, 'column2');
    	array_push($outputArray, 'column3');
    	array_push($outputArray, 'column4');
    	array_push($outputArray, 'column5');
    	array_push($outputArray, 'column6');
    	array_push($outputArray, 'column7');
    	
        echo json_encode($outputArray);
    } elseif ($_POST['task'] == 'dropId') {
        $id = htmlentities(trim($_POST['id']));
        $outputArray[0] = $id; 
        echo json_encode($outputArray);       
     }elseif ($_POST['task'] == 'activateId') {
        $id = htmlentities(trim($_POST['id']));
        $outputArray[0] = $id; 
        echo json_encode($outputArray);       
     }else{
     	$outputArray[0] = 0;
        echo json_encode($outputArray);
    }
}
?>