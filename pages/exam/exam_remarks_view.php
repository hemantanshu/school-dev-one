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
require_once BASE_PATH . 'include/global/class.menuTask.php';
require_once BASE_PATH . 'include/utility/class.personalInfo.php';
require_once BASE_PATH . 'include/utility/class.candidate.php';
require_once BASE_PATH . 'include/utility/class.registration.php';
require_once BASE_PATH . 'include/exam/class.resultSections.php';
require_once BASE_PATH . 'include/global/class.options.php';


$body = new body ();
$menuTask = new MenuTask();
$personalInfo = new personalInfo();
$registration = new registrationInfo();
$candidate = new Candidate();
$result = new ResultSections();
$options = new options();


$body->startBody ( 'exam', 'LMENUL90', 'Result Remarks View' );
$resultId = $_GET['resultId'];
$sectionId = $_GET['sectionId'];
$sessionId = $_GET['sessionId'];

$resultSectionId = $result->getResultSectionId($resultId, $sectionId);
$resultDetails = $result->getTableIdDetails($resultId);
$candidateIds = $candidate->getCandidate4Section($sectionId, 1);

?>
<input type="hidden" name="resultSectionId" id="resultSectionId" value="<?php echo $resultSectionId;  ?>" />
<input type="hidden" name="resultId" id="resultId" value="<?php echo $resultId;  ?>" />
<input type="hidden" name="sessionId" id="sessionId" value="<?php echo $sessionId;  ?>" />
<input type="hidden" name="sectionId" id="sectionId" value="<?php echo $sectionId;  ?>" />

<div id="content_header">
    <div id="pageButton" class="buttons">      
    </div>
    <div id="contentHeader">Remarks View For The Section</div>
</div>
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

<div id="displayTable" class="display">
    <form id="entryForm" class="entryForm">
        <fieldset>
        	<div class="legend">
            <span id="legendDisplayDetail">Listing Of All Candidates</span>
        </div>
        <dl>
        	
        	<table width="100%" align="left" border = "0" class="buttons" id="mainTable">
                <tr class="even">
                	<th>SN</th>
                    <th>Reg No</th>
                    <th>Candidate Name</th>
                    <th>Remarks</th>
                    <th>Date</th>
                    <th>Officer</th>
                </tr>
                <tr>
                	<td colspan="7"><hr /></td>
                </tr>
                <?php 
                	$i = 1;
                	foreach ($candidateIds as $candidateId){
                		$candidateDetails = $personalInfo->getUserIdDetails($candidateId);
                		
                		$remarks = $result->getCandidateData($resultSectionId, $candidateId, 'REMKS');
                		$details = $result->getTableIdDetails($remarks[0]);
                		echo "
                			<tr class=\"odd\">
                				<th>".$i."</th>
			                    <th>".$registration->getCandidateRegistrationNumber($candidateId)."</th>
			                    <th>".$personalInfo->getUserName()."</th>
			                    <th>".$options->getOptionIdValue($remarks[1])."</th>
			                    <th>".$details['last_update_date']."</th>
			                    <th>".$result->getOfficerName($details['last_updated_by'])."</th>
			                    
			                </tr>";
                		++$i;
                	}
                ?>
                
        	</table>
        </dl>
        </fieldset>
    </form>
</div>
<div class="clear"></div>

<div class="clear"></div>
<div id="extraMenuListingPage" style="display:none">
	<?php 
		$baseServer = $body->getBaseServer();
		if($resultDetails['grading_id'] != ""){
			if($resultDetails['grading_id'] == 'KIDSR'){
				$setupUrl = $baseServer."pages/exam/exam_result_junior.php?sessionId=".$sessionId."&resultId=".$resultId."&sectionId=".$sectionId;
				$resultUrl = $baseServer."pages/exam/exam_print_result_junior.php?sessionId=".$sessionId."&resultId=".$resultId."&sectionId=".$sectionId;
				$datasheetUrl = $baseServer."pages/exam/exam_print_datasheet_junior.php?sessionId=".$sessionId."&resultId=".$resultId."&sectionId=".$sectionId;
			}else{
				$setupUrl = $baseServer."pages/exam/exam_result_section.php?sessionId=".$sessionId."&resultId=".$resultId."&sectionId=".$sectionId;
				$resultUrl = $baseServer."pages/exam/exam_print_result_grading.php?sessionId=".$sessionId."&resultId=".$resultId."&sectionId=".$sectionId;
				$datasheetUrl = $baseServer."pages/exam/exam_print_datasheet_grading.php?sessionId=".$sessionId."&resultId=".$resultId."&sectionId=".$sectionId;
			}			
		}else{
			$setupUrl = $baseServer."pages/exam/exam_result_section.php?sessionId=".$sessionId."&resultId=".$resultId."&sectionId=".$sectionId;
			$resultUrl = $baseServer."pages/exam/exam_print_result_absolute.php?sessionId=".$sessionId."&resultId=".$resultId."&sectionId=".$sectionId;
			$datasheetUrl = $baseServer."pages/exam/exam_print_datasheet_absolute.php?sessionId=".$sessionId."&resultId=".$resultId."&sectionId=".$sectionId;
		}		
	?>
	<li><a href="#" class="bookmarkedMenuListing" onclick="loadPageIntoDisplay('<?php echo $setupUrl; ?>')"><img src="<?php echo $baseServer; ?>images/global/b_usredit.png" alt="" />Set Section Details</a></li>
	<?php 
		if($resultDetails['grading_id'] == 'KIDSR'){
			$weightUrl = $baseServer."pages/exam/exam_weight_view.php?sessionId=".$sessionId."&resultId=".$resultId."&sectionId=".$sectionId;
			$heightUrl = $baseServer."pages/exam/exam_height_view.php?sessionId=".$sessionId."&resultId=".$resultId."&sectionId=".$sectionId;
			$achievementUrl = $baseServer."pages/exam/exam_achievement_view.php?sessionId=".$sessionId."&resultId=".$resultId."&sectionId=".$sectionId;
			
			echo "<li><a href=\"#\" class=\"bookmarkedMenuListing\" onclick=\"loadPageIntoDisplay('".$weightUrl."')\"><img src=\"".$baseServer."images/global/b_usredit.png\" alt=\"\" />View Candidate Weight</a></li>";
			echo "<li><a href=\"#\" class=\"bookmarkedMenuListing\" onclick=\"loadPageIntoDisplay('".$heightUrl."')\"><img src=\"".$baseServer."images/global/b_usredit.png\" alt=\"\" />View Candidate Height</a></li>";
			echo "<li><a href=\"#\" class=\"bookmarkedMenuListing\" onclick=\"loadPageIntoDisplay('".$achievementUrl."')\"><img src=\"".$baseServer."images/global/b_usredit.png\" alt=\"\" />View Candidate Achievement</a></li>";
		}else{
			$processUrl = $baseServer."pages/exam/exam_result_process.php?sessionId=".$sessionId."&resultId=".$resultId."&sectionId=".$sectionId;
			echo "<li><a href=\"#\" class=\"bookmarkedMenuListing\" onclick=\"loadPageIntoDisplay('".$processUrl."')\"><img src=\"".$baseServer."images/global/b_usredit.png\" alt=\"\" />Process Result Now</a></li>";		
		}
	?>
	<li><a href="#" class="bookmarkedMenuListing" onclick="loadPageIntoDisplay('<?php echo $baseServer."pages/exam/exam_attendance_view.php?sessionId=".$sessionId."&resultId=".$resultId."&sectionId=".$sectionId; ?>')"><img src="<?php echo $baseServer; ?>images/global/b_usredit.png" alt="" />View Candidate Attendance</a></li>
	<li><a href="#" class="bookmarkedMenuListing" onclick="loadPageIntoDisplay('<?php echo $baseServer."pages/exam/exam_remarks_view.php?sessionId=".$sessionId."&resultId=".$resultId."&sectionId=".$sectionId; ?>')"><img src="<?php echo $baseServer; ?>images/global/b_usredit.png" alt="" />View Candidate Remarks</a></li>	
	<li><a href="#" class="bookmarkedMenuListing" onclick="loadPageIntoDisplay('<?php echo  $datasheetUrl; ?>')"><img src="<?php echo $baseServer; ?>images/global/b_usredit.png" alt="" />Print Section Datasheet</a></li>
	<li><a href="#" class="bookmarkedMenuListing" onclick="loadPageIntoDisplay('<?php echo  $resultUrl; ?>')"><img src="<?php echo $baseServer; ?>images/global/b_usredit.png" alt="" />Print Candidate Result</a></li>
</div>
