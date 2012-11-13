<?php
require_once 'config.php';

require_once BASE_PATH . 'include/utility/class.books.php';
require_once BASE_PATH . 'include/utility/class.rooms.php';
require_once BASE_PATH . 'include/global/class.options.php';
require_once BASE_PATH . 'include/utility/class.sections.php';
require_once BASE_PATH . 'include/utility/class.institute.php';

$books = new books();
$rooms = new rooms();
$options = new options();
$sections = new sections();
$institute = new institute();

$options->isRequestAuthorised4Form('LMENUL54');

if (isset ( $_GET )) {
	if ($_GET ['option'] == "book") {
		$input = $_GET ['q'];
		$bookIds = $books->getBookSearchIds($input, 1);
		foreach ( $bookIds as $bookId) {
			$details = $books->getBookIdDetails($bookId);
			echo $details['book_name'] . "|" . $bookId . "\n";
		}
	}
	
	if ($_GET ['option'] == "room") {
		$input = $_GET ['q'];
		$roomIds = $rooms->getRoomSearchIds($input, 1);
		foreach ( $roomIds as $roomId) {
			$details = $rooms->getRoomIdDetails($roomId);
			echo $details['room_name'] . "|" . $roomId . "\n";
		}
	}
	if($_GET['option'] == "section"){
		$input = $_GET ['q'];
		$classId = $_COOKIE['classId_globalVars'];
		$sectionIds = $sections->getClassSectionSearchIds($classId, $input, 1);
		foreach($sectionIds as $sectionId){
			$details = $sections->getSectionIdDetails($sectionId);
			echo $details['section_name'] . "|" . $sectionId . "\n";
		}
	}
	if($_GET['option'] == "class"){
		$input = $_GET ['q'];
		$classIds = $sections->getCurrentSessionClassNameIds($input, 1);        
		foreach($classIds as $classId){			
			echo $classId[1] . "|" . $classId[0] . "\n";
		}
	}
	if($_GET['option'] == "institute"){
		$input = $_GET ['q'];		
		$instituteIds = $institute->getInstituteSearchIds($input, 1);
		foreach($instituteIds as $instituteId){
			$details = $institute->getInstituteIdDetails($instituteId);
			echo $details['institute_name'] . "|" . $instituteId . "\n";
		}
	}

}
?><p>
	<font color="#000000">Your Input</font>
</p>