<?php
require_once 'config.php';

require_once BASE_PATH . 'include/exam/class.examination.php';

$examination = new Examination();
$examination->isRequestAuthorised4Form ( 'LMENUL69' );

if (isset ( $_POST ['task'] )) {
	if ($_POST ['task'] == 'insertRecord') {
		$componentId = $examination->setSubjectCombination($_POST['subjectId'], $_POST['subjectComponentName']);
		if($componentId){
			$outputArray[0] = $componentId;
			$outputArray[] = $_POST['subjectComponentName'];
			$outputArray[] = $_POST['subjectComponentOrder'];
		}else 
			$outputArray[] = 0;
		echo json_encode($outputArray);
				
	} else if ($_POST ['task'] == 'search') {
		$search_type = $_POST['search_type'];
		$subjectId = $_POST['subjectId'];

		$subjectComponentIds = $examination->getSubjectCombinationIds($subjectId, $search_type);
		$i = 0;
		$outputArray[0][0] = 1;
		foreach ($subjectComponentIds as $subjectComponentId){
			$details = $examination->getTableIdDetails($subjectComponentId);
			$outputArray[$i][0] = $subjectComponentId;
			$outputArray[$i][] = $details['subject_component_name'];
			$outputArray[$i][] = $details['subject_component_order'];
			++$i;
		}
		echo json_encode($outputArray);
	} else if ($_POST ['task'] == 'getRecordIdDetails') {
		$componentId = $_POST['id'];
		$details = $examination->getTableIdDetails($componentId);
		
		$outputArray[0] = $componentId;
		$outputArray[] = $details['subject_component_name'];
		
		$outputArray[] = $details['last_update_date'];
		$outputArray[] = $examination->getOfficerName($details['last_updated_by']);
		$outputArray[] = $details['creation_date'];
		$outputArray[] = $examination->getOfficerName($details['created_by']);
		
		$outputArray[] = $details['active'];
		$outputArray[] = $details['subject_component_order'];
		
		echo json_encode($outputArray);		
	} elseif ($_POST ['task'] == 'updateRecord') {		
		$details = $examination->getTableIdDetails($_POST['valueId_u']);
		if($details['subject_component_name'] != $_POST['subjectComponentName_u']){
			$examination->setUpdateLog('Name from '.$details['subject_component_name'].' to '.$_POST['subjectComponentName_u']);
			$examination->updateTableParameter('subject_component_name', $_POST['subjectComponentName_u']);
		}
		if($details['subject_component_order'] != $_POST['subjectComponentOrder_u']){
			$examination->setUpdateLog('Order from '.$details['subject_component_order'].' to '.$_POST['subjectComponentOrder_u']);
			$examination->updateTableParameter('subject_component_order', $_POST['subjectComponentOrder_u']);
		}		
		if($examination->commitSubjectCombinationDetailsUpdate($_POST['valueId_u'])){
			$outputArray[0] = $_POST['valueId_u'];
			$outputArray[] = $_POST['subjectComponentName_u'];
			$outputArray[] = $_POST['subjectComponentOrder_u'];
		}else 
			$outputArray[0] = 0;
		echo json_encode($outputArray);			
	} else if ($_POST ['task'] == 'getSubjectDetails') {
		$subjectId = $_POST['subjectId'];
		$subjectDetails = $examination->getTableIdDetails($subjectId);

		$outputArray[0] = $subjectId;
		$outputArray[] = $subjectDetails['subject_code'];
		$outputArray[] = $subjectDetails['subject_name'];
		
		echo json_encode($outputArray);
	} elseif ($_POST ['task'] == 'dropRecord') {
		$id = htmlentities ( trim ( $_POST ['id'] ) );		
		$examination->dropSubjectCombinationDetails($id);
		$outputArray [0] = $id;
		echo json_encode ( $outputArray );
	} elseif ($_POST ['task'] == 'activateRecord') {
		$id = htmlentities ( trim ( $_POST ['id'] ) );
		$examination->activateSubjectCombinationDetails($id);
		$outputArray [0] = $id;
		echo json_encode ( $outputArray );
	} else {
		$outputArray [0] = 0;
		echo json_encode ( $outputArray );
	}
}
?>