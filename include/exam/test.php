<?php 
error_reporting(1);
require_once 'config.php';
require_once BASE_PATH . 'include/exam/class.resultType.php';

$test = new ResultType();

$data = $test->getResultTypeUrlIds4Code('LEXRET2', 'LRESER24', '', 1);

print_r($data);
?>