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
require_once BASE_PATH . 'include/exam/class.resultMarking.php';
require_once BASE_PATH . 'include/utility/class.personalInfo.php';
require_once BASE_PATH . 'include/utility/class.registration.php';
require_once BASE_PATH . 'include/exam/class.grading.php';


$body = new body ();
$marks = new ResultMarking();
$menuTask = new MenuTask();
$personalInfo = new personalInfo();
$registration = new registrationInfo();
$grading = new Grading();

$body->startBody ( 'exam', 'LMENUL85', 'Exam Mark Progress' );
$activityId = $_GET['activityId'];
$activityDetails = $menuTask->getTableIdDetails($activityId);
if($activityDetails['marking_type'] == "")
	$status  = true;
else
	$status = false;

if($activityId == "")
	exit(0);
$candidateIds = $marks->getCandidate4Activity($activityId);


?>
<input type="hidden" name="activityId" id="activityId" value="<?php echo $activityId; ?>" />
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
                    <label for="assessmentName">Assessment Name :</label>
                </dt>
                <dd style="width: 30%">
                    <span id="assessmentName"></span>
                </dd>
                <dt style="width: 15%;">
                    <label for="activityName">Activity Name :</label>
                </dt>
                <dd style="width: 30%">
                    <span id="activityName"></span>
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
                <dt style="width: 15%;">
                    <label for="endDate">End Date :</label>
                </dt>
                <dd style="width: 30%">
                    <span id="endDate"></span>
                </dd>
            </dl>
        </fieldset>
    </div>
</div>

<div id="displayTable" class="display">
    <form id="entryForm" class="entryForm">
        <fieldset>
        	<div class="legend">
            <span id="legendDisplayDetail">Listing Of All Candidates For This Subject</span>
        </div>
        <dl>
        	
        	<table width="100%" align="left" border = "0" class="buttons">
                <tr class="even">
                	<th>SN</th>
                    <th>Reg No</th>
                    <th>Candidate Name</th>
                    <th>Mark</th>
                    <th>Submission Date</th>
                    <th>Subm. Officer</th>
                    <th>Verif. Date</th>
                    <th>Verif. Officer</th>
                    
                </tr>
                <tr>
                	<td colspan="8"><hr /></td>
                </tr>
                <?php
                	$i = 1;
                	foreach ($candidateIds as $candidateId){
                		$candidateDetails = $personalInfo->getUserIdDetails($candidateId);
                		$score = $marks->getMark4Candidate($activityId, $candidateId);
                		$scoreDetails = $marks->getTableIdDetails($score[1]);
                		if($status){
                			$score = $score[0];
                		}
                		else 
                			$score = $grading->getGradingOptionName($score[0]);
                		
                		
                		echo "
                			<tr class=\"odd\">
                				<th>$i</th>
			                    <th>".$registration->getCandidateRegistrationNumber($candidateId)."</th>
			                    <th align=\"left\">".$personalInfo->getUserName()."</th>
			                    <th>".$score[0]."</th>
			                    <th>".$scoreDetails['submission_date']."</th>
			                    <th>".$marks->getOfficerName($scoreDetails['submission_officer_id'])."</th>
			                    <th>".$scoreDetails['verification_date']."</th>
			                    <th>".$marks->getOfficerName($scoreDetails['verification_officer_id'])."</th>
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
<br />
<br />

