<?php 
require_once 'class.accounts.php';

$accounts = new Accounts();
echo $accounts->getAccountSum('USERS0', 'AALDT0');

?>
