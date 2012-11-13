<?php
require_once 'config.php';
require_once BASE_PATH."include/utility/class.personalInfo.php";

$personalInfo = new personalInfo();
$personalInfo->isRequestAuthorised4Form('LMENUL65');

$path = BASE_PATH."programs/uploads/user/";
$valid_formats = array("jpg", "png", "gif", "bmp");
$userId = $_SESSION['photographUserId'];

if(isset($_POST) and $_SERVER['REQUEST_METHOD'] == "POST")
{
    $name = $_FILES['photoimg']['name'];
    $size = $_FILES['photoimg']['size'];
	$maxSize = 512*512;
	
	$outputArray[0] = 0;
    if(strlen($name))
    {
        list($txt, $ext) = explode(".", $name);
        if(in_array(strtolower($ext), $valid_formats))
        {
            if($size<($maxSize))
            {
                $actual_image_name = time().substr(str_replace(" ", "_", $txt), 5).".".$ext;
                $tmp = $_FILES['photoimg']['tmp_name'];
                if(move_uploaded_file($tmp, $path.$actual_image_name))
                {                   
                    //updating the candidate image
                    $personalInfo->updateTableParameter('photograph_name', $actual_image_name);
                    if($personalInfo->commitUserDetailsUpdate($userId)){
                    	$outputArray[0] = 1;
                    	$outputArray[1] = $actual_image_name;
                    }else{
                    	$outputArray[1] =  "Server is facing some problems, please try after some time";
                    }                    
                }
                else
                    $outputArray[1] =  "Server is facing some problems, please try after some time";
            }
            else
                $outputArray[1] =  "The image file size exceeds the size limit of 512 KB";
        }
        else
            $outputArray[1] =  "Invalid file format.. The accepted formats are .jpg, .bmp, .png, .gif";
    }
    else
        $outputArray[1] =  "Please select image..!";

    echo json_encode($outputArray);
}
?>