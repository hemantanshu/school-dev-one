<?php
require_once 'config.php';
require_once BASE_PATH . 'include/exam/class.resultSections.php';
require_once BASE_PATH . 'include/global/class.notification.php';
require_once BASE_PATH . 'include/global/class.menuTask.php';
require_once BASE_PATH . 'include/global/class.options.php';

$result = new ResultSections();
$options = new options();

$result->isRequestAuthorised4Form ( 'LMENUL88' );

if (isset ( $_POST ['task'] )) {
	if ($_POST ['task'] == 'confirmRemarks') {
		$remarks = $_POST ['remarks'];
		$resultSectionId = $_POST ['resultSectionId'];
		$candidateId = $_POST ['candidateId'];
		
		$remarksId = $result->setResultSectionData($resultSectionId, $candidateId, $remarks, 'REMKS');		
		$outputArray[0] = $remarksId;
		echo json_encode ( $outputArray );
	} elseif($_POST['task'] = "getOptions"){
		$optionIds = $options->getOptionSearchValueIds('', 'REMKS', 1);
		$i = 0;
		foreach($optionIds as $optionId){
			$outputArray[$i][0] = $optionId;
			$outputArray[$i][] = $options->getOptionIdValue($optionId);
			++$i;
		}
		echo json_encode($outputArray);
		
	}else {
		$outputArray [0] = 0;
		echo json_encode ( $outputArray );
	}
}
?>