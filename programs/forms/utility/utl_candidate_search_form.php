<?php
require_once 'config.php';

require_once BASE_PATH . 'include/utility/class.personalInfo.php';
$personalInfo = new personalInfo ();

$personalInfo->isRequestAuthorised4Form ( 'LMENUL72' );

if (isset ( $_POST ['task'] )) {
	if ($_POST ['task'] == 'search') {
		$hint = htmlentities ( trim ( $_POST ['searchString'] ) );
		
		$data = $personalInfo->searchCandidateDetails ( $hint, 1 );
		$i = 0;
		$outputArray [0] [0] = 1;
		foreach ( $data as $details ) {
			$photographUrl = $personalInfo->getBaseServer () . "/programs/uploads/user/" . $details [8];
			$gender = $details [7] == 'M' ? 'Male' : 'Female';
			$outputArray [$i] [0] = $details [0];
			$outputArray [$i] [] = "<img src=\"$photographUrl\" width=\"50px\" height=\"50px\" />";
			$outputArray [$i] [] = $details [5];
			$outputArray [$i] [] = $details [1] . " " . $details [4] . ", " . $details [2] . " " . $details [3];
			$outputArray [$i] [] = $personalInfo->getDisplayDate ( $details [6] );
			$outputArray [$i] [] = $gender . "<br />" . $details [9];
			$i ++;
		}
		echo json_encode ( $outputArray );
	}
}
?>