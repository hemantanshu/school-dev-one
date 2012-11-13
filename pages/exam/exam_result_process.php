<?php
/**
 *
 * @author hemantanshu@supportgurukul.com(html)
 * @category Utility
 * @license Support-Gurukul
 * @version 1.0.0
 */
require_once 'config.php';
require_once BASE_PATH . 'include/global/class.body.php';
require_once BASE_PATH . 'include/utility/class.candidate.php';
require_once BASE_PATH . 'include/utility/class.personalInfo.php';
require_once BASE_PATH . 'include/utility/class.registration.php';


$body = new body ();
$candidate = new Candidate();
$registration = new registrationInfo();
$personalInfo = new personalInfo();

$body->startBody('exam', 'LMENUL93', 'Exam Result Processing');

$sectionId = $_GET['sectionId'];
$resultId = $_GET['resultId'];
$sessionId = $_GET['sessionId'];

$details = $body->getTableIdDetails($resultId);
$flag = $details['grading_id'] == "" ? 0 : 1;


$candidateIds = $candidate->getCandidate4Section($sectionId, 1);

?>

<input type="hidden" name="resultId" id="resultId" value="<?php echo $resultId; ?>"/>
<input type="hidden" name="printType" id="printType" value="<?php echo $flag; ?>"/>
<input type="hidden" name="sectionId" id="sectionId" value="<?php echo $sectionId; ?>"/>
<input type="hidden" name="sessionId" id="sessionId" value="<?php echo $sessionId; ?>"/>

<div class="clear"></div>
<div id="content_header">
    <div id="contentHeader">Result Processing Page</div>
</div>
<div class="clear"></div>
<div class="clear"></div>
<div class="display">
    <div id="displaySubjectRecord">
        <fieldset class="displayElements">
            <dl>
                <dt style="width: 15%;">
                    <label for="resultName">Result Name :</label>
                </dt>
                <dd style="width: 30%">
                    <span id="resultName"></span>
                </dd>
                <dt style="width: 15%">
                    <label for="sessionName">Session Name :</label>
                </dt>
                <dd style="width: 30%">
                    <span id="sessionName"></span>
                </dd>
            </dl>
            <dl>
                <dt style="width: 15%;">
                    <label for="className">Class Name :</label>
                </dt>
                <dd style="width: 30%">
                    <span id="className"></span>
                </dd>
                <dt style="width: 15%;">
                    <label for="sectionName">Section Name :</label>
                </dt>
                <dd style="width: 30%">
                    <span id="sectionName"></span>
                </dd>

            </dl>
        </fieldset>
    </div>
</div>

<div class="inputs">
    <div id="displayRecord" class="buttons" id="totalResultProcess">
        <fieldset>
            <div class="legend">
                <span>Result Processing Details </span>
            </div>
            <dl class="element">
                <dt style="width: 15%;">
                    <label for="processResult">Process Result </label>
                </dt>
                <dd style="width: 30%">
                    <span id="processResult"><button type="button" class="regular insert"
                                                     onclick="startResultProcessing()">Process Result For The Entire Class
                    </button></span>
                </dd>
            </dl>
                    </fieldset>
    </div>
</div>
<div class="clear"></div>
<div id="displayTable" class="display">
    <form id="entryForm" class="entryForm">
        <fieldset>
            <div class="legend">
                <span id="legendDisplayDetail">Process Result For The Candidates</span>
            </div>
            <dl>
                <table width="100%" align="left" border = "0" class="buttons">
                    <tr class="even">
                        <th>SN</th>
                        <th>Reg No</th>
                        <th>Candidate Name</th>
                        <th>Gender</th>
                        <th style="width: 190px">Process Result</th>
                        <th style="width: 250px">Result History</th>
                    </tr>
                    <tr>
                        <td colspan="7"><hr /></td>
                    </tr>
                    <?php
                    $i = 1;
                    foreach ($candidateIds as $candidateId){
                        $candidateDetails = $personalInfo->getUserIdDetails($candidateId);
                        $buttonId = "button".$candidateId;
                        $gender = strtoupper($candidateDetails['gender']) == 'M' ? 'Male' : 'Female';
                        echo "
                			<tr class=\"odd\">
                				<th>$i</th>
			                    <th>".$registration->getCandidateRegistrationNumber($candidateId)."</th>
			                    <th>".$personalInfo->getUserName()."</th>
			                    <th>".$gender."</th>
			                    <th><button type=\"button\" class=\"negative insert\" id=\"$buttonId\" onclick=\"processResult4Candidate('".$candidateId."')\">Process Result Now</th>
			                    <th><button type=\"button\" class=\"regular browse\" onclick=\"showResultHistory4Candidate('".$candidateId."')\">Show Processing History</th>
			                </tr>";
                        ++$i;
                    }
                    ?>

                </table>
            </dl>
        </fieldset>
    </form>
</div>
<br /><br />


