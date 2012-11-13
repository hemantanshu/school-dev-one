<?php
require_once 'config.php';
require_once BASE_PATH . 'include/exam/class.result.php';

$result = new Result();
$result->isRequestAuthorised4Form ( 'LMENUL94' );

if (isset ( $_POST ['task'] )) {
	if ($_POST ['task'] == 'getResultProcessingRecords') {
		$candidateId = $_POST['candidateId'];
		$resultId = $_POST['resultId'];		
		$outputArray[0][0] = 1;
		$processingIds = $result->getResultProcessingIds($resultId, $candidateId);
		$i = 0;
		foreach($processingIds as $id){
			$details = $result->getTableIdDetails($id);
			$outputArray[$i][0] = $result->getOfficerName($details['processing_officer']);
			$outputArray[$i][] = $details['creation_date'];
			++$i;				
		}
		echo json_encode ( $outputArray );
	} else {
		$outputArray [0] = 0;
		echo json_encode ( $outputArray );
	}
}
?>