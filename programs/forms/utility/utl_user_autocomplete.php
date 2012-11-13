<?php
require_once 'config.php';

require_once BASE_PATH . 'include/utility/class.personalInfo.php';
require_once BASE_PATH . 'include/utility/class.employeeRegistration.php';

$personalInfo = new personalInfo();
$registration = new employeeRegistration();

$personalInfo->isRequestAuthorised4Form('LMENUL56');

if (isset ( $_GET )) {
    if ($_GET ['option'] == "employeeTeacher") {
        $input = $_GET ['q'];
        $outputData = $registration->searchEmployeeData($input, 1);
        foreach ( $outputData as $employeeData) {
            $personalInfo->getUserIdDetails($employeeData[1]);
            $displayName = $personalInfo->getUserName()." >> ".$employeeData[2];
            echo $displayName . "|" . $employeeData[1] . "\n";
        }
    }

}
?><p>
    <font color="#000000">Your Input</font>
</p>