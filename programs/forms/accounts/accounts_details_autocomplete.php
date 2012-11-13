<?php
require_once 'config.php';

require_once BASE_PATH . 'include/accounts/class.allowance.php';

$allowance = new Allowance();
$allowance->isRequestAuthorised4Form('LMENUL122');

if (isset ( $_GET )) {
    if ($_GET ['option'] == "allowanceName") {
        $input = $_GET ['q'];
        $outputData = $allowance->getAllowanceNameSearchIds($input, 1);
        foreach ( $outputData as $allowanceId) {            
            echo $allowance->getAllowanceName($allowanceId) . "|" . $allowanceId . "\n";
        }
    }

}
?><p>
    <font color="#000000">Your Input</font>
</p>