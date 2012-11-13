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
require_once BASE_PATH . 'include/global/class.options.php';
require_once BASE_PATH . 'include/exam/class.examination.php';
require_once BASE_PATH . 'include/utility/class.sections.php';

$body = new body ();
$examination = new Examination();
$options = new options();
$section = new sections();

$body->startBody ( 'exam', 'LMENUL101', 'Examination Section Subject Process View' );
$examinationId = $_GET['examinationId'];

$examinationDetails = $body->getTableIdDetails($examinationId);
$sesionDetails = $body->getTableIdDetails($examinationDetails['session_id']);
$assignmentIds = $options->getAssignmentIds($examinationId, 'CLSSA');
$currentDate = $options->getCurrentDate();
?>
<input type="hidden" name="examinationId" id="examinationId" value="<?php echo $examinationId;  ?>" />

<div id="content_header">
    <div id="pageButton" class="buttons">
    </div>
    <div id="contentHeader">Examination Process View </div>
</div>
<div class="clear"></div>
<div class="display">
    <div id="displaySubjectRecord">
        <fieldset class="displayElements">
            <dl>
                <dt style="width: 15%;">
                    <label for="examinationName">Examination Name :</label>
                </dt>
                <dd style="width: 30%">
                    <span id="examinationName"><?php echo $examinationDetails['examination_name']; ?></span>
                </dd>
                <dt style="width: 15%">
                    <label for="sessionName">Session Name :</label>
                </dt>
                <dd style="width: 30%">
                    <span id="sessionName"><?php echo $sesionDetails['session_name']; ?></span>
                </dd>
            </dl>
        </fieldset>
    </div>
</div>
<div class="clear"></div>
<?php 
	
	foreach ($assignmentIds as $assignmentId){
		$details = $options->getTableIdDetails($assignmentId);
		$sectionId = $details['value_set'];
		echo "
			<div class=\"display\">
		        <fieldset>
		            <div class=\"legend\">
		                <span id=\"legendDisplayDetail\">Class Name : ".$section->getClassName4Section($sectionId)." ".$section->getSectionName($sectionId)."</span>
		            </div>
		            <table cellpadding=\"0\" cellspacing=\"0\" border=\"0\" width=\"100%\">
		                <tr class=\"even\">
		                    <th>Subject Name</th>
		                    <th style=\"width: 15%\">Sub Date</th>
		                    <th style=\"width: 15%\">Sub Off.</th>
		                    <th style=\"width: 10%\">Status</th>
		                    <th style=\"width: 15%\">Veri. Date</th>
		                    <th style=\"width: 15%\">Veri. Off.</th>
		                    <th style=\"width: 10%\">Status</th>
		                </tr>";
		$examinationDateIds = $examination->getExaminationSubjectDateIds($sesionDetails['id'], $examinationId, $sectionId, '1');
		$i = 0;
		foreach ($examinationDateIds as $examinationDateId){
			$details = $examination->getTableIdDetails($examinationDateId);
			$rowClass = $i % 2 == 0 ? "odd" : "even";
			
			$submissionType = $details['actual_mark_submission_date'] != "0000-00-00 00:00:00" ? "<font class=\"green\">Done</font>" : (strtotime($details['mark_submission_date']) <  strtotime($currentDate) ? "<font class=\"red\">Late</font>" : "<font class=\"\">Pending</font>");
			$verificationType = $details['actual_mark_verification_date'] != "0000-00-00 00:00:00" ? "<font class=\"green\">Done</font>" : (strtotime($details['mark_verification_date']) <  strtotime($currentDate) ? "<font class=\"red\">Late</font>" : "<font class=\"\">Pending</font>");
				
			
			echo "
				<tr class=\"$rowClass\">
					<td><a href=\"#\" onclick=\"showSubjectMark('$examinationDateId')\">".$details['examination_name']."</a></td>
					<th>".$options->getDisplayDate($details['mark_submission_date'])."</th>
					<th>".$options->getOfficerName($details['mark_submission_officer'])."</th>
					<th>".$submissionType."</th>
					<th>".$options->getDisplayDate($details['mark_verification_date'])."</th>
					<th>".$options->getOfficerName($details['mark_verification_officer'])."</th>
					<th>".$verificationType."</th>					
				</tr>
				<tr>
							<td height=\"5px\"></td>
						</tr>";
		}
		
		echo "
		            </table>
		        </fieldset>    
		    </div>
			<div class=\"clear\"></div><br />";
	}
?>

