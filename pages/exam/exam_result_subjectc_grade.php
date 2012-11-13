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
$body->startBody ( 'exam', 'LMENUL151', 'Exam Result Subject Component Grade Setup' );

$grading = new Grading();
$result = new Result();
$resultGrade = new ResultGrade();

$resultId = $_GET['resultId'];
$sectionId = $_GET['sectionId'];

$subjectComponents = $result->getResultSubjectComponentIds($resultId, $sectionId);

$gradingIds = $grading->getGradingType('', 1);
$gradingOptionDetails = array();
//setting for the absolute marking type
$i = 0;
$gradingOptionDetails[$i] = array();
array_push($gradingOptionDetails[$i], '');
array_push($gradingOptionDetails[$i], 'Absolute Marking');
++$i;
foreach($gradingIds as $gradingId){
	$gradingOptionDetails[$i] = array();
	array_push($gradingOptionDetails[$i], $gradingId);
	array_push($gradingOptionDetails[$i], $grading->getGradingName($gradingId));
	++$i;
}
?>
<input type="hidden" name="resultIdGlobal" id="resultIdGlobal" value="<?php echo $resultId; ?>" />
<input type="hidden" name="sectionIdGlobal" id="sectionIdGlobal" value="<?php echo $sectionId; ?>" />
<div id="content_header">
    <div id="pageButton" class="buttons">
    </div>
    <div id="contentHeader">Exam Result Subject Component Grade Setup </div>
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
    <form id="entryForm" class="entryForm">
        <fieldset>
        	<div class="legend">
            <span id="legendDisplayDetail">Listing Of All Subject Components</span>
        </div>
        <dl>        	
        	<table width="100%" align="left" border = "0" class="buttons">
                <tr class="even">
                	<th>Subject Code</th>
                    <th>Subject Name</th>
                    <th>Component Name</th>
                    <th>Grade Type</th>
                    <th style="width: 200px">Submit</th>
                </tr>
                <tr>
                	<td colspan="7"><hr /></td>
                </tr>  
                <?php 
                	$i = 0;
                	foreach ($subjectComponents as $subjectComponentDetails){
						$subjectDetails = $result->getTableIdDetails($subjectComponentDetails[0]);
						$componentDetails = $result->getTableIdDetails($subjectComponentDetails[1]);
						
						$rowId = "row".$i;
						$gradeId = "grade".$i;
						$subjectFormName = "subject".$i;
						$componentFormName = "component".$i;
						echo "
                			<tr class=\"odd\" id=\"$rowId\">
			                    <th>".$subjectDetails['subject_code']."</th>
			                    <td>".$subjectDetails['subject_name']."</td>			                    
			                    <td>".$componentDetails['subject_component_name']."</td>
			                    <th>
			                    	<select name=\"$gradeId\" id=\"$gradeId\" class=\"required\" style=\"width: 250px\">";
						$assignedgGrade = $resultGrade->getResultSubjectComponentGradeData($resultId, $sectionId, 'LRESER25', $subjectComponentDetails[0], $subjectComponentDetails[1]);
                		foreach ($gradingOptionDetails as $gradingOptions){							
							if($assignedgGrade == $gradingOptions[0])
								echo "<option value=\"".$gradingOptions[0]."\" selected=\"selected\">".$gradingOptions[1]."</option>";
							else
								echo "<option value=\"".$gradingOptions[0]."\">".$gradingOptions[1]."</option>";
						}			                    	
                		echo "
                					</select></th>
			                    <th align=\"center\">
									<input type=\"hidden\" name=\"$subjectFormName\" id=\"$subjectFormName\" value=\"".$subjectComponentDetails[0]."\" />
									<input type=\"hidden\" name=\"$componentFormName\" id=\"$componentFormName\" value=\"".$subjectComponentDetails[1]."\" />									
									<button type=\"button\" class=\"positive browse\" onclick=\"processSubmitAction('".$i."')\">Submit Result Grade</th>			                    
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
<div id="displayTable" class="display">
    <form id="confirmForm" class="confirmForm">
        <fieldset>
        	<div class="legend">
            <span id="legendDisplayDetail">Current Submitted Subject Component Details</span>
        </div>
        <dl>        	
        	<table width="100%" align="left" border = "0" class="buttons" id="finalTable">
                <tr class="even">
                	<th>Subject Code</th>
                    <th>Subject Name</th>
                    <th>Component Name</th>
                    <th>Grade Type</th>
                </tr>
                <tr>
                	<td colspan="4"><hr /></td>
                </tr>  
                
        	</table>
        </dl>
        </fieldset>
    </form>
</div>
<div class="clear"></div>
<br />
<br /><br />
