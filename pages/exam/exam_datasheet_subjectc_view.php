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
require_once BASE_PATH . 'include/exam/class.grading.php';
require_once BASE_PATH . 'include/exam/class.result.php';
require_once BASE_PATH . 'include/exam/class.resultGrade.php';

$body = new body ();
$body->startBody ( 'exam', 'LMENUL154', 'Exam Datasheet Subject Component Grade View' );

$result = new Result();
$resultGrade = new ResultGrade();
$grading = new Grading();

$resultId = $_GET['resultId'];
$sectionId = $_GET['sectionId'];

$subjectComponents = $result->getResultSubjectComponentIds($resultId, $sectionId);

?>
<input type="hidden" name="resultIdGlobal" id="resultIdGlobal" value="<?php echo $resultId; ?>" />
<input type="hidden" name="sectionIdGlobal" id="sectionIdGlobal" value="<?php echo $sectionId; ?>" />
<div id="content_header">
    <div id="pageButton" class="buttons">
    </div>
    <div id="contentHeader">Exam Datasheet Subject Component Grade View</div>
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
                    <label for="className">Class :</label>
                </dt>
                <dd style="width: 30%">
                    <span id="className"></span>
                </dd>
            </dl>
            <dl>
                <dt style="width: 15%;">
                    <label for="resultType">Result Type :</label>
                </dt>
                <dd style="width: 30%">
                    <span id="resultType"></span>
                </dd>
                <dt style="width: 15%">
                    <label for="resultDescription">Result Description :</label>
                </dt>
                <dd style="width: 30%">
                    <span id="resultDescription"></span>
                </dd>
            </dl>
        </fieldset>
    </div>
</div>
<div class="clear"></div>
<div id="displayTable" class="display">
    <form id="confirmForm" class="confirmForm">
        <fieldset>
        	<div class="legend">
            <span id="legendDisplayDetail">Current Submitted Subject Component Details</span>
        </div>
        <dl>        	
        	<table width="100%" align="left" border = "0" class="buttons" id="finalTable">
                <tr class="even">
                	<th>SN</th>
                	<th>Subject Code</th>
                    <th>Subject Name</th>
                    <th>Component Name</th>
                    <th>Grade Type</th>
                </tr>
                <tr>
                	<td colspan="5"><hr /></td>
                </tr>  
                <?php 
                	$i = 1;
                	foreach ($subjectComponents as $subjectComponentDetails){
						$subjectDetails = $result->getTableIdDetails($subjectComponentDetails[0]);
						$componentDetails = $result->getTableIdDetails($subjectComponentDetails[1]);
						$assignedgGrade = $resultGrade->getResultSubjectComponentGradeData($resultId, $sectionId, 'LRESER26', $subjectComponentDetails[0], $subjectComponentDetails[1]);
						$assignedgGrade = $assignedgGrade == false ? 'Not Assigned' : ($assignedgGrade == '' ? 'Absolute Marking' : $grading->getGradingName($assignedgGrade));
						echo "
                			<tr class=\"odd\">
                				<th>".$i."</th>
			                    <th>".$subjectDetails['subject_code']."</th>
			                    <td>".$subjectDetails['subject_name']."</td>			                    
			                    <td>".$componentDetails['subject_component_name']."</td>
			                    <th>".$assignedgGrade."</th>			                    
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
<br /><br />
