<?php
require_once 'config.php';
require_once BASE_PATH.'include/global/class.options.php';
require_once BASE_PATH.'include/global/class.loginInfo.php';


$options = new options ();
$loginInfo = new loginInfo();

$options->isRequestAuthorised4Form('LMENUL74');

if (isset ( $_POST ['task'] )) {
	if ($_POST ['task'] == 'insertRecord') {				
       $password = $_POST['password'];
       $newPassword = $_POST['newpassword'];
       $userId = $_POST['userId'];
       $outputArray[0] = 0;
       
       if($password != $newPassword)
       		$outputArray[0] = 1;
       else{
       		$loginInfo->setCandidatePassword($userId, $password);
       		$outputArray[0] = 2;
       }
       		
       echo json_encode($outputArray);
     }elseif ($_POST ['task'] == 'assignRecord') {
     	$userId = $_POST['userId'];
     	$userGroup = $_POST['userGroup_val'];

     	$assignmentId = $options->setNewAssignment($userGroup, $userId, 'USRGP');
     	$details = $options->getTableIdDetails($assignmentId);
     	
     	$outputArray[0] = $details['id'];
     	$outputArray[] = $options->getOptionIdValue($details['generic_id']);
     	$outputArray[] = $details['last_update_date'];
     	$outputArray[] = $options->getOfficerName($details['last_updated_by']);     	
       echo json_encode($outputArray);
     }elseif ($_POST ['task'] == 'search') {
     	$userId = $_POST['userId'];
     	$assignmentIds = $options->getAssignmentIds($userId, 'USRGP', '1', '1', '1000', true);
     	$outputArray[0][0] = 1;
     	$i = 0;
     	foreach($assignmentIds as $assignmentId){
     		$details = $options->getTableIdDetails($assignmentId);
     		
     		$outputArray[$i][0] = $details['id'];
     		$outputArray[$i][] = $options->getOptionIdValue($details['generic_id']);
     		$outputArray[$i][] = $details['last_update_date'];
     		$outputArray[$i][] = $options->getOfficerName($details['last_updated_by']);
     		
     		++$i;
     	}     	     	
       echo json_encode($outputArray);
     }elseif ($_POST ['task'] == 'userName') {
     	$userId = $_POST['userId'];
     	$outputArray[0] = 1;
     	$outputArray[] = $loginInfo->getUserLoginUsernameId($userId);  	     	
       echo json_encode($outputArray);
     }elseif($_POST['task'] == 'userRecord'){
     	$userId = $_POST['userId'];
     	$userName = $_POST['userName'];
     	
     	if($loginInfo->checkUsernameAvailability($userName)){
     		if($loginInfo->setCandidateUsername($userId, $userName))
     			$outputArray[0] = 2;
     		
     	}else{
     		$outputArray[0] = 1; 
     	}
     	echo json_encode($outputArray);
     }else {
		$outputArray [0] = 0;
		echo json_encode ( $outputArray );
	}
}
?>