 <?php
// require_once 'config.php';

// require_once BASE_PATH . 'include/utility/class.employeeRegistration.php';
// require_once BASE_PATH . 'include/hrms/class.designation.php';
// require_once BASE_PATH . 'include/global/class.options.php';

// $registration = new employeeRegistration();
// $designation = new Designation();
// $options = new options();

// echo "<table border=\"1\" cellpadding=\"0\" cellspacing=\"0\" align=\"center\">
// 		<tr>
// 			<th width=\"200px\">Employee Id</th>
// 			<th width=\"200px\">Employee Code</th>
// 			<th width=\"400px\">Employee Name</th>
// 			<th width=\"400px\">Designation</th>
// 		</tr>";

// $employeeIds = $registration->getEmployeeIds(1);
// foreach ($employeeIds as $employeeId){
// 	$rankIds = $designation->getUserRanks($employeeId, 1);
// 	foreach($rankIds as $rankId){
// 		$details = $designation->getTableIdDetails($rankId);
// 		echo "<tr>
// 				<td style=\"padding-left:10px\">".$employeeId."</td>
// 				<td style=\"padding-left:10px\">".$designation->getEmployeeCode($employeeId)."</td>
// 				<td style=\"padding-left:10px\">".$designation->getOfficerName($employeeId)."</td>
// 				<td style=\"padding-left:10px\">".$options->getOptionIdValue($details['rank_id'])." - ".$details['rank_id']."</td>
// 			</tr>";
// 		break;
// 	}	
// }
// echo "</table>";
// ?>

// <?php 
// 	require_once 'class.test.php';
// 	$test = new testing();
	
// 	$test->dumpSms();
// ?>

// <?php 
// 	require_once 'config.php';

// 	require_once BASE_PATH . 'include/utility/class.employeeRegistration.php';
// 	require_once BASE_PATH . 'include/hrms/class.designation.php';
// 	require_once BASE_PATH . 'include/global/class.options.php';
	
// 	$registration = new employeeRegistration();
// 	$designation = new Designation();
// 	$options = new options();
	
// 	//adding new roles
// 	$userId = 'LUSERS837';
// 	$rankDesignation = 'LOPTON426';		
// 	$designation->setUserRank($userId, $rankDesignation, '2012-03-01', '0000-00-00', '');
	
	
	//updating 
// 	$rankIds = $designation->getUserRanks($userId, 1);
// 	foreach ($rankIds as $rankId){
// 		$designation->updateTableParameter('rank_id', $rankDesignation);
// 		$designation->commitRankUpdate($rankId);
// 		break;
// 	}
?>

<?php 
session_start();
//$_SESSION['time'] = time();

echo time() - $_SESSION['time'];

?>