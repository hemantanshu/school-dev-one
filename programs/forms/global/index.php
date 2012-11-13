<?php
require_once 'config.php';
require_once BASE_PATH.'include/global/class.body.php';

$body = new body();
$body->redirectUrl($body->getBaseServer());
?>
