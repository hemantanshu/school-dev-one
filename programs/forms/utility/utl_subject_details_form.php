<?php
require_once 'config.php';

require_once BASE_PATH . 'include/utility/class.subject.php';
require_once BASE_PATH . 'include/global/class.options.php';

$subject = new subjects ();
$options = new options ();

$options->isRequestAuthorised4Form ( 'LMENUL18' );

if (isset ( $_POST ['task'] )) {
	if ($_POST ['task'] == 'insertRecord') {
		$id = $subject->setSubjectDetails ( $_POST ['subCode'], $_POST ['subName'], $_POST['subjectOrder'] );
		$outputArray = array ();
		$outputArray [0] = 0;
		if ($id) {
			$details = $subject->getSubjectIdDetails ( $id );
			$outputArray [0] = $details ['id'];
			$outputArray [1] = $details ['subject_code'];
			$outputArray [2] = $details ['subject_name'];
			$outputArray [3] = $details ['subject_order'];
		}
		echo json_encode ( $outputArray );
	} else if ($_POST ['task'] == 'search') {
		$hint = htmlentities ( trim ( $_POST ['room_hint'] ) );
		$search_type = htmlentities ( trim ( $_POST ['search_type'] ) );
		$data = $subject->getSubjectSearchIds ( $hint, $search_type );
		$i = 0;
		$outputArray [0] [0] = 1;
		foreach ( $data as $id ) {
			$details = $subject->getSubjectIdDetails ( $id );
			$outputArray [$i] [0] = $details ['id'];
			
			$outputArray [$i] [1] = $details ['subject_code'];
			$outputArray [$i] [2] = $details ['subject_name'];
			$outputArray [$i] [3] = $details ['subject_order'];
			$i ++;
		}
		echo json_encode ( $outputArray );
	} else if ($_POST ['task'] == 'getRecordIdDetails') {
		$id = htmlentities ( trim ( $_POST ['id'] ) );
		$details = $subject->getSubjectIdDetails ( $id );
		$outputArray [0] = $details ['id'];
		$outputArray [1] = $details ['subject_code'];		
		$outputArray [2] = $details ['subject_name'];
		$outputArray [3] = $details ['last_update_date'];
		$outputArray [4] = $subject->getOfficerName ( $details ['last_updated_by'] );
		$outputArray [5] = $details ['creation_date'];
		$outputArray [6] = $subject->getOfficerName ( $details ['created_by'] );
		$outputArray [7] = $details ['active'];
		$outputArray [8] = $details ['subject_order'];
		
		
		echo json_encode ( $outputArray );
	} elseif ($_POST ['task'] == 'updateRecord') {
		$subjectId = $_POST['valueId_u'];
		$details = $subject->getTableIdDetails($_POST['valueId_u']);

		if($details['subject_code'] != $_POST['subCode_u']){
			$subject->setUpdateLog('Code from '.$details['subject_code'].' to '.$_POST['subCode_u']);
			$subject->updateTableParameter ( 'subject_code', $_POST ['subCode_u'] );
		}
		if($details['subject_name'] != $_POST['subName_u']){
			$subject->setUpdateLog('Name from '.$details['subject_name'].' to '.$_POST['subName_u']);
			$subject->updateTableParameter ( 'subject_name', $_POST ['subName_u'] );
		}
		if($details['subject_order'] != $_POST['subjectOrder_u']){
			$subject->setUpdateLog('from '.$details['subject_order'].' to '.$_POST['subjectOrder_u']);
			$subject->updateTableParameter ( 'subject_order', $_POST ['subjectOrder_u'] );
		}				
		
		$subject->commitSubjectDetailsUpdate ( $subjectId );
		
		$details = $subject->getSubjectIdDetails ( $subjectId );
		$outputArray [0] = $details ['id'];
		$outputArray [1] = $details ['subject_code'];
		$outputArray [2] = $details ['subject_name'];
		$outputArray [3] = $details ['subject_order'];
		
		echo json_encode ( $outputArray );
	
	} elseif ($_POST ['task'] == 'dropRecord') {
		$id = htmlentities ( trim ( $_POST ['id'] ) );
		$subject->dropSubjectDetails ( $id );
		$outputArray [0] = $id;
		echo json_encode ( $outputArray );
	} elseif ($_POST ['task'] == 'activateRecord') {
		$id = htmlentities ( trim ( $_POST ['id'] ) );
		$subject->activateSubjectDetails ( $id );
		$outputArray [0] = $id;
		echo json_encode ( $outputArray );
	} else {
		$outputArray [0] = 0;
		echo json_encode ( $outputArray );
	}
} elseif (isset ( $_GET ['q'] )) {
	if ($_GET ['option'] == 'subject') {
		$string = $_GET ['q'];
		$subjectIds = $subject->getSubjectSearchIds ( $string, 1 );
		foreach ( $subjectIds as $subjectId ) {
			$details = $subject->getTableIdDetails ( $subjectId );
			echo $details ['subject_name']." ".$details ['subject_code'] . "|" . $subjectId . "\n";
		}
	}
	echo "<p>
		    <font color=\"#000000\">Your Input</font>
		</p>";
}
?>