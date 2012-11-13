<?php
require_once 'config.php';

require_once BASE_PATH . 'include/utility/class.books.php';
require_once BASE_PATH . 'include/global/class.options.php';

$books = new books ();
$options = new options ();

$options->isRequestAuthorised4Form('LMENUL17');

if (isset ( $_POST ['task'] )) {
	if ($_POST ['task'] == 'insertRecord') {
		$id = $books->setBookDetails ( $_POST ['bookName'], $_POST ['authorName_val'], $_POST ['publication_val'] );
		$outputArray = array ();
		$outputArray [0] = 0;
		if ($id) {
			$details = $books->getBookIdDetails ( $id );
			$outputArray [0] = $details ['id'];
			$outputArray [1] = $details ['book_name'];
			$outputArray [2] = $options->getOptionIdValue ( $details ['author_id'] );
			$outputArray [3] = $options->getOptionIdValue ( $details ['publication_id'] );
		}
		echo json_encode ( $outputArray );
	} else if ($_POST ['task'] == 'search') {
		$hint = htmlentities ( trim ( $_POST ['book_hint'] ) );
		$search_type = htmlentities ( trim ( $_POST ['search_type'] ) );
		$data = $books->getBookSearchIds ( $hint, $search_type );
		$i = 0;
		$outputArray [0] [0] = 1;
		foreach ( $data as $id ) {
			$details = $books->getBookIdDetails ( $id );
			$outputArray [$i] [0] = $details ['id'];
			$outputArray [$i] [1] = $details ['book_name'];
			$outputArray [$i] [2] = $options->getOptionIdValue ( $details ['author_id'] );
			$outputArray [$i] [3] = $options->getOptionIdValue ( $details ['publication_id'] );
			$i ++;
		}
		echo json_encode ( $outputArray );
	} else if ($_POST ['task'] == 'getRecordIdDetails') {
		$id = htmlentities ( trim ( $_POST ['id'] ) );
		$details = $books->getBookIdDetails ( $id );
		$outputArray [0] = $details ['id'];
		$outputArray [1] = $details ['book_name'];
		$outputArray [2] = $options->getOptionIdValue ( $details ['author_id'] );
		$outputArray [3] = $options->getOptionIdValue ( $details ['publication_id'] );
		$outputArray [4] = $details ['last_update_date'];
		$outputArray [5] = $books->getOfficerName ( $details ['last_updated_by'] );
		$outputArray [6] = $details ['creation_date'];
		$outputArray [7] = $books->getOfficerName ( $details ['created_by'] );
		$outputArray [8] = $details ['active'];
		
		// extra details for the edit form
		$outputArray [9] = $details ['author_id'];
		$outputArray [10] = $details ['publication_id'];
		
		echo json_encode ( $outputArray );
	} elseif ($_POST ['task'] == 'updateRecord') {
		$bookId = $_POST ['valueId_u'];
		$details = $books->getBookIdDetails ( $bookId );
		
		if($details['book_name'] != $_POST['bookName_u']){
			$books->setUpdateLog('Name from '.$details['book_name'].' to '.$_POST['bookName_u']);
			$books->updateTableParameter ( 'book_name', $_POST ['bookName_u'] );
		}
		if($details['author_id'] != $_POST['authorName_uval']){
			$books->setUpdateLog('Author from '.$details['author_id'].' to '.$_POST['authorName_uval']);
			$books->updateTableParameter ( 'author_id', $_POST ['authorName_uval'] );
		}
		if($details['publication_id'] != $_POST['publication_uval']){
			$books->setUpdateLog('Publication from '.$details['publication_id'].' to '.$_POST['publication_uval']);
			$books->updateTableParameter ( 'publication_id', $_POST ['publication_uval'] );
		}		
		$books->commitBookDetailsUpdate ( $bookId );
		
		$details = $books->getBookIdDetails ( $bookId );
		$outputArray [0] = $details ['id'];
		$outputArray [1] = $details ['book_name'];
		$outputArray [2] = $options->getOptionIdValue ( $details ['author_id'] );
		$outputArray [3] = $options->getOptionIdValue ( $details ['publication_id'] );
		echo json_encode ( $outputArray );
	
	} elseif ($_POST ['task'] == 'dropRecord') {
		$id = htmlentities ( trim ( $_POST ['id'] ) );
		$books->dropBookDetails($id);
		$outputArray [0] = $id;
		echo json_encode ( $outputArray );
	} elseif ($_POST ['task'] == 'activateRecord') {
		$id = htmlentities ( trim ( $_POST ['id'] ) );
		$books->activateBookDetails($id);
		$outputArray [0] = $id;
		echo json_encode ( $outputArray );
	} else {
		$outputArray [0] = 0;
		echo json_encode ( $outputArray );
	}
}
?>