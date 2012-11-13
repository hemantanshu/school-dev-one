<?php
/**
 *
 * @author shubhamkesarwani@supportgurukul.com(html)
 * @category Utility
 * @license Support-Gurukul
 * @version 1.0.0
 */

require_once 'config.php';
require_once BASE_PATH . 'include/global/class.body.php';
require_once BASE_PATH . 'include/global/class.session.php';
require_once BASE_PATH . 'include/utility/class.subject.php';
require_once BASE_PATH . 'include/utility/class.candidate.php';
require_once BASE_PATH . 'include/utility/class.personalInfo.php';
require_once BASE_PATH . 'include/utility/class.registration.php';

$body = new body ();
$session = new Session();
$body->startBody ( 'utility', 'LMENUL61', 'Class Candidate Subject Bulk Assignment' );

$classId = $_GET ['classId'];
$details = $body->getTableIdDetails($classId);


if ($details['class_id'] == "" || !$session->isSessionEditable($details['session_id']))
    exit(0);


$subject = new subjects();
$candidate = new Candidate();
$personalInfo = new personalInfo();
$registration = new registrationInfo();

//getting the list of candidates
$candidateIds = $candidate->getClassCandidateIds($classId, 1);
$subjectTypeIds = $subject->getClassSubjectTypeIds($classId, 'o', 1);
$optionalSubjectCount = sizeof($subjectTypeIds);
$i = 0;
foreach($subjectTypeIds as $subjectTypeId){
	$details = $subject->getTableIdDetails($subjectTypeId);
	$subjectTypeDetails[$i] = $details['subject_name'];
	$subjectOptionIds = $subject->getClassSubjectMappingIds($subjectTypeId, 1);
	$j = 0;
	foreach($subjectOptionIds as $subjectOptionId){
		$details = $subject->getTableIdDetails($subjectOptionId);
		$subjectDetails = $subject->getTableIdDetails($details['subject_id']);

		$subjectOption[$i][$j][0] = $subjectDetails['subject_code'];
		$subjectOption[$i][$j][] = $subjectDetails['subject_name'];
		$subjectOption[$i][$j][] = $subjectDetails['id'];

		++$j;
	}
	++$i;
}

?>

<div id="content_header">
    <div id="contentHeader">Candidate Bulk Subject Assignment</div>
</div>
<input type="hidden" name="class_global" id="class_global" value="<?php echo $classId; ?>" />
<input type="hidden" name="optionalSubjects" id="optionalSubjects" value="<?php echo $optionalSubjectCount; ?>" />
<?php 
	for($i = 0; $i < $optionalSubjectCount; ++$i){
		$idName = "subjectGlobal".$i;
		echo "<input type=\"hidden\" name=\"$idName\" id=\"$idName\" value=\"".$subjectTypeIds[$i]."\" />";
	}
		
?>
<div class="clear"></div>
<div class="display">
    <div id="sessionRecord" style="display: nosne">
        <fieldset class="displayElements">
            <dl>
                <dt style="width: 15%">
                    <label for="session_d">Session :</label>
                </dt>
                <dd style="width: 30%">
                    <span id="session_d"></span>
                </dd>
                <dt style="width: 15%">
                    <label for="class_d">Class :</label>
                </dt>
                <dd style="width: 30%">
                    <span id="class_d"></span>
                </dd>
            </dl>
        </fieldset>
    </div>
</div>
<div class="clear"></div>
<div id="displayTable" class="display">
    <form id="updateForm" name="updateForm" onsubmit="return valid.validateForm(this) ? processMainForm() : false;">
        <fieldset>
        	<div class="legend">
            <span id="legendDisplayDetail">Form Listing Of All The Candidates</span>
        </div>
        <dl>
        	<dt style="width: 5px"></dt>
            <dt style="width: 10%;">
                <label for="registrationNumber">Reg. Number</label>
            </dt>
            <dt style="width: 25%">
                <label for="candidateName">Candidate Name</label>
            </dt>
            <?php 
            	$columnWidth = 45 / $optionalSubjectCount;
            	foreach ($subjectTypeDetails as $subjectTypeName){
            		echo "<dt style=\"width: ".$columnWidth."%\">
			                <label for=\"candidateName\">".$subjectTypeName."</label>
			            </dt>";
            	}
            ?>
            <dt style="width:10%">
                <label for="update">Update Operation</label>
            </dt>
        </dl>
        <?php 
        	foreach ($candidateIds as $candidateId){
        		$personalInfo->getUserIdDetails($candidateId);       		

        		echo "<dl id=\"".$candidateId."\">";
        		echo "<dt style=\"width: 5px\"></dt>";
        		echo "<dt style=\"width: 10%\">
        				<label for=\"registrationNumber\">".$registration->getCandidateRegistrationNumber($candidateId)."</label>	
        			</dt>
        			<dt style=\"width: 25%\">
        				<label for=\"candidateName\">".$personalInfo->getUserName()."</label>	
        			</dt>";		
        		//creating the options for the subject
        		
        		for($i = 0; $i < $optionalSubjectCount; ++$i){
        			$candidateSubjectId = $candidate->getCandidateSubject($candidateId, $subjectTypeIds[$i]);
        			$selectId = $candidateId."subject".$i;
        			$assignedId = $candidateId."assigned".$i;        			
        			echo "<dt style=\"width: ".$columnWidth."%\">
        					<select id=\"$selectId\" name=\"$selectId\" style=\"\">";
        			foreach($subjectOption[$i] as $subjectOptions){
        				if($subjectOptions[2] == $candidateSubjectId[1])
        					echo "<option value=\"$subjectOptions[2]\" selected=\"selected\">$subjectOptions[1]</option>";
        				else
        					echo "<option value=\"$subjectOptions[2]\">$subjectOptions[1]</option>";
        			}       			
        			echo "</select>
        				<input type=\"hidden\" name=\"$assignedId\" id=\"$assignedId\" value=\"$candidateSubjectId[1]\" />
        			</dt>";
        		}
        		      		
        		echo "
        			<dt style=\"width: 13%\">
        			<button type=\"button\" class=\"negative update\" onclick=\"updateCandidateSubjects('$candidateId')\" style=\"padding: 0 5px 1px 5px\"> Update & Remove </button>
        			</dt>
        		</dl>";
        	}
        ?>
        <dl></dl>
        </fieldset>
    </form>
</div>

<br />
<div class="clear"></div>
<div id="extraMenuListingPage" style="display:none">
	<?php 
		$baseServer = $body->getBaseServer();				
	?>
	<li><a href="#" class="bookmarkedMenuListing" onclick="loadPageIntoDisplay('<?php echo $baseServer."pages/utility/utl_class_candidate_subject_view.php?classId=".$classId; ?>')"><img src="<?php echo $baseServer; ?>images/global/b_usredit.png" alt="" />Candidate Optional Subject</a></li>
</div>