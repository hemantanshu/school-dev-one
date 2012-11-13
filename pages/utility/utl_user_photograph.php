<?php
require_once 'config.php';
require_once BASE_PATH . 'include/global/class.body.php';
require_once BASE_PATH . 'include/utility/class.personalInfo.php';
$body = new body ();
$personalInfo = new personalInfo();

$body->startBody ( 'utility', 'LMENUL65', 'User Photograph Uploading', '', false );

$candidateId = $_GET['candidateId'];
$personalInfo->getUserIdDetails($candidateId);

$formLink =  $body->getBaseServer()."javascript/global/jquery.form.js";
$_SESSION['photographUserId'] = $candidateId;
$imageName = $personalInfo->getUserAttributeDetails('photograph_name');
if($imageName != '')
    $imageName = $body->getBaseServer()."programs/uploads/user/".$imageName;
else
    $imageName = $body->getBaseServer()."programs/uploads/user/DummyFace.JPG";
?>

<script type="text/javascript" src="<?php echo $formLink; ?>"></script>

<div id="display" class="buttons">
    <form id="imageform" method="post" enctype="multipart/form-data" action="<?php echo $body->getBaseServer()."programs/forms/utility/utl_user_photograph_form.php"; ?>">
        <div class="legend">
            <span>User Photograph Details</span>
        </div>
        <dl>
           <dt style="width: 40%;">
               <img src="<?php echo $imageName; ?>" alt="alternate image" height="250px" width="250px" id="imageLink" name="imageLink" style="border: 1px solid #000000; margin: 5px" />
           </dt>
           <dd style="width: 55%; margin-top: 50px">
               The image has to be of the aspect ratio of 1:1 and the maximum size of 512 KB <br /><br />
               <input type="file" name="photoimg" id="photoimg" />
           </dd>
       </dl>
        <dl></dl>
    </form>
</div>
<div id="preview" style="height: 20px"></div>