<?php
require_once 'config.php';

require_once BASE_PATH . 'include/utility/class.subject.php';
require_once BASE_PATH . 'include/global/class.options.php';
require_once BASE_PATH . 'include/utility/class.books.php';

$subject = new subjects ();
$options = new options ();
$book = new books ();

$options->isRequestAuthorised4Form('LMENUL19');

if (isset ( $_POST ['task'] )) {
	if ($_POST ['task'] == 'insertRecord') {
		
		$id = $subject->setSujectBookDetails ( $_POST ['subjectId'], $_POST ['bookName_val'], $_POST ['bookType'], $_POST ['priority'] );
		$outputArray = array ();
		$outputArray [0] = 0;
		if ($id) {
			$details = $subject->getSubjectBookIdDetails ( $id );
			$bookDetails = $book->getBookIdDetails ( $details ['book_id'] );
			$outputArray [0] = $details ['id'];
			$outputArray [1] = $bookDetails ['book_name'];
			$outputArray [2] = $options->getOptionIdValue ( $bookDetails ['author_id'] );
			$outputArray [3] = $options->getOptionIdValue ( $bookDetails ['publication_id'] );
			$outputArray [4] = $details ['priority'];
		}
		echo json_encode ( $outputArray );
	} else if ($_POST ['task'] == 'search') {
		$hint = htmlentities ( trim ( $_POST ['book_hint'] ) );
		$search_type = htmlentities ( trim ( $_POST ['search_type'] ) );
		$subjectId = $_POST ['subjectId'];
		$data = $subject->getSubjectBookSearchIds ( $subjectId, $hint, $search_type );
		$i = 0;
		$outputArray [0] [0] = 1;
		foreach ( $data as $id ) {
			$details = $subject->getSubjectBookIdDetails ( $id );
			$bookDetails = $book->getBookIdDetails ( $details ['book_id'] );
			$outputArray [$i] [0] = $details ['id'];
			$outputArray [$i] [1] = $bookDetails ['book_name'];
			$outputArray [$i] [2] = $options->getOptionIdValue ( $bookDetails ['author_id'] );
			$outputArray [$i] [3] = $options->getOptionIdValue ( $bookDetails ['publication_id'] );
			$outputArray [$i] [4] = $details ['priority'];
			$i ++;
		}
		echo json_encode ( $outputArray );
	} else if ($_POST ['task'] == 'getRecordIdDetails') {
		$id = htmlentities ( trim ( $_POST ['id'] ) );
		$details = $subject->getSubjectBookIdDetails ( $id );
		$subjectDetails = $subject->getSubjectIdDetails ( $details ['subject_id'] );
		$bookDetails = $book->getBookIdDetails ( $details ['book_id'] );
		$outputArray [0] = $details ['id'];
		$outputArray [1] = $subjectDetails ['subject_name'];
		$outputArray [2] = $bookDetails ['book_name'];
		$outputArray [3] = $details ['book_type'] == "y" ? "Core Book" : "Reference Book";
		$outputArray [4] = $options->getOptionIdValue ( $bookDetails ['author_id'] );
		$outputArray [5] = $options->getOptionIdValue ( $bookDetails ['publication_id'] );
		$outputArray [6] = $details ['priority'];
		$outputArray [7] = $details ['last_update_date'];
		$outputArray [8] = $subject->getOfficerName ( $details ['last_updated_by'] );
		$outputArray [9] = $details ['creation_date'];
		$outputArray [10] = $subject->getOfficerName ( $details ['created_by'] );
		$outputArray [11] = $details ['active'];
		
		$outputArray [12] = $details ['book_id'];
		$outputArray [13] = $details ['priority'];
		
		echo json_encode ( $outputArray );
	} elseif ($_POST ['task'] == 'updateRecord') {
		$associationId = $_POST ['valueId_u'];
			
		if($details['book_id'] != $_POST['bookName_eval']){
			$subject->setUpdateLog('from '.$details['book_id'].' to '.$_POST['bookName_eval']);
			$subject->updateTableParameter ( 'book_id', $_POST ['bookName_eval'] );
		}
		if($details['core'] != $_POST['bookType_e']){
			$subject->setUpdateLog('from '.$details['core'].' to '.$_POST['bookType_e']);
			$subject->updateTableParameter ( 'core', $_POST ['bookType_e'] );
		}
		if($details['priority'] != $_POST['priority_e']){
			$subject->setUpdateLog('from '.$details['priority'].' to '.$_POST['priority_e']);
			$subject->updateTableParameter ( 'priority', $_POST ['priority_e'] );
		}
		
		$subject->commitSubjectBookDetailsUpdate($associationId);
		
		$details = $subject->getSubjectBookIdDetails ( $associationId );		
		$bookDetails = $book->getBookIdDetails ( $details ['book_id'] );
		
		$outputArray [0] = $details ['id'];
		$outputArray [1] = $bookDetails ['book_name'];
		$outputArray [2] = $options->getOptionIdValue ( $bookDetails ['author_id'] );
		$outputArray [3] = $options->getOptionIdValue ( $bookDetails ['publication_id'] );
		$outputArray [4] = $details ['priority'];
		echo json_encode ( $outputArray );
	
	} elseif ($_POST ['task'] == 'dropRecord') {
		$id = htmlentities ( trim ( $_POST ['id'] ) );
		$subject->dropSubjectBookDetails ( $id );
		$outputArray [0] = $id;
		echo json_encode ( $outputArray );
	} elseif ($_POST ['task'] == 'activateRecord') {
		$id = htmlentities ( trim ( $_POST ['id'] ) );
		$subject->activateSubjectBookDetails ( $id );
		$outputArray [0] = $id;
		echo json_encode ( $outputArray );
	} else {
		$outputArray [0] = 0;
		echo json_encode ( $outputArray );
	}
}
?>