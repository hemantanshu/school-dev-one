<?php
require_once 'config.php';
require_once BASE_PATH . 'include/global/class.options.php';

$options = new options ();

$options->isRequestAuthorised4Form('LMENUL53');

if (isset ( $_GET )) {
	if (isset ( $_GET ['option'] )) {
		$input = $_GET ['q'];
		$type = $_GET ['type'];
		
		$optionIds = $options->getOptionSearchValueIds ( $input, $type, 1 );
		foreach ( $optionIds as $optionId ) {
			echo $options->getOptionIdValue ( $optionId ) . "|" . $optionId . "\n";
		}
	}
}
?><p>
	<font color="#000000">Your Input</font>
</p>