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
require_once BASE_PATH . 'include/exam/class.markHandling.php';
require_once BASE_PATH . 'include/utility/class.personalInfo.php';
require_once BASE_PATH . 'include/utility/class.registration.php';

$body = new body ();
$marks = new MarkHandling();
$personalInfo = new personalInfo();
$registration = new registrationInfo();

$body->startBody ( 'exam', 'LMENUL134', 'Exam Mark Verification' );
$examDateId = $_GET['examDateId'];


$details = $marks->getTableIdDetails($examDateId);
$candidateIds = $marks->getCandidate4ExaminationDate($examDateId);
?>

<div id="content_header">
    <div id="pageButton" class="buttons">        
    </div>
    <div id="contentHeader">Admin Mark Update Form </div>
</div>
<input type="hidden" name="examinationId" id="examinationId" value="<?php echo $examDateId; ?>" />
<div class="clear"></div>
<div class="display">
    <div id="displaySubjectRecord">
        <fieldset class="displayElements">
            <dl>
                <dt style="width: 15%;">
                    <label for="examinationName">Examination Name :</label>
                </dt>
                <dd style="width: 30%">
                    <span id="examinationName"></span>
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
            <dl>
                <dt style="width: 15%;">
                    <label for="subjectName">Subject Name :</label>
                </dt>
                <dd style="width: 30%">
                    <span id="subjectName"></span>
                </dd>
                <dt style="width: 15%">
                    <label for="subjectCombination">SubType Name :</label>
                </dt>
                <dd style="width: 30%">
                    <span id="subjectCombination"></span>
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
        	
        	<table width="100%" align="left" border = "0" class="buttons">
                <tr class="even">
                	<th>SN</th>
                    <th>Reg No</th>
                    <th>Candidate Name</th>
                    <th>Gender</th>
                    <th>Mark</th>
                    <th style="width: 220px">Mark Update</th>
                </tr>
                <tr>
                	<td colspan="7"><hr /></td>
                </tr>
                <?php 
                	$i = 0;
                	$j = 1;
                	foreach ($candidateIds as $candidateId){
                		++$i;                		
                		$candidateDetails = $personalInfo->getUserIdDetails($candidateId);
                		$tableId = "table".$candidateId;
                		$markId = "mark".$candidateId;                		
                		$nextFocusElement = "mark".$candidateIds[$i];
                		
                		$gender = $candidateDetails['gender'] == 'M' ? 'Male' : 'Female';
                		$score = $marks->getMark4Candidate($examDateId, $candidateId);
                		
                		echo "
                			<tr class=\"odd\" id=\"$tableId\">
                				<th>$j</th>
			                    <th>".$registration->getCandidateRegistrationNumber($candidateId)."</th>
			                    <th>".$personalInfo->getUserName()."</th>			                    
			                    <th>".$gender."</th>
			                    <th><input type=\"text\" name=\"$markId\" id=\"$markId\" size=\"15\" class=\"numeric required\" value=\"".$score[0]."\" onchange=\"javascript: valid.validateInput(this);\" /></th>		                    
			                    <th><button type=\"button\" class=\"negative insert\" onclick=\"processConfirmAction('".$candidateId."', '".$nextFocusElement."')\">Update Candidate Mark</th>			                    
			                </tr>";
                		
                		++$j;
                	}                	
                ?>
                
        	</table>
        </dl>
        </fieldset>
    </form>
</div>
<div class="clear"></div>
<br />
<div class="clear"></div>
<div id="extraMenuListingPage" style="display:none">	
	<?php 
		$baseServer = $registration->getBaseServer();
	?>
	<li><a href="#" class="bookmarkedMenuListing" onclick="loadPageIntoDisplay('<?php echo $baseServer."pages/exam/exam_mark_status.php?examinationId=".$examDateId; ?>')"><img src="<?php echo $baseServer; ?>images/global/b_usredit.png" alt="" />View Candidate Marks</a></li>
</div>