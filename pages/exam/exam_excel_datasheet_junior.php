<?php
require_once 'config.php';

require_once BASE_PATH . 'include/exam/class.result.php';
require_once BASE_PATH . 'include/exam/class.resultSections.php';
require_once BASE_PATH . 'include/global/class.body.php';

$result = new Result ();
$resultSections = new ResultSections ();
$body = new body ();

$body->startBody ( "exam", "LMENUL147", "Section Datasheet" );

$resultId = $_GET ['resultId'];
$sectionId = $_GET ['sectionId'];

$resultDetails = $result->getTableIdDetails ( $resultId );
$sectionDetails = $result->getTableIdDetails ( $sectionId );
$classDetails = $result->getTableIdDetails ( $sectionDetails ['class_id'] );
$classDetails = $result->getTableIdDetails ( $classDetails ['class_id'] );
$sessionDetails = $result->getTableIdDetails ( $resultDetails ['session_id'] );

$resultSetupIds = $result->getResultSetupIds ( $resultId, 1 );
$subjectIds = $result->getResultSubjectComponentIds($resultId, $sectionId);

$assessmentIds = $result->getResultAssessment ( $resultId, $sectionId, 1 );
?>
<input type="hidden" name="resultIdGlobal" id="resultIdGlobal" value="<?php echo $resultId;  ?>" />
<input type="hidden" name="sectionIdGlobal" id="sectionIdGlobal" value="<?php echo $sectionId;  ?>" />
<table border="0" width="100%">
    <tr>
        <td width="15%" align="right"><font class="printMark">Result</font></td>
        <th width="3%">:</th>
        <td width="30%" align="left"><font class="printMark"><?php echo $resultDetails['display_name']; ?></font></td>
        <td width="15%" align="right"><font class="printMark">Session</font></td>
        <th width="3%">:</th>
        <td width="30%" align="left"><font class="printMark"><?php echo $sessionDetails ['session_name'];?></font></td>
    </tr>
    <tr>
        <td align="right"><font class="printMark">Class </font></td>
        <th>:</th>
        <td align="left"><font class="printMark"><?php echo $classDetails ['class_name'] . " " . $sectionDetails ['section_name']; ?></font></td>
    </tr>
</table>
<div class="clear"></div>

<div class="datatable buttons" id="displayDatatable">
    <fieldset class="formelements">
        <div class="legend">
            <span>Tabulation Listing Of All Subjects & Activities</span>
        </div>
        <table  class="display"  id="totalMarkListing">
            <thead>
            <tr>
                <th>Adm No</th>
                <th>Candidate</th>
                <?php 
                	foreach ($subjectIds as $subjectIdDetails){
						$subjectDetails = $result->getTableIdDetails ( $subjectIdDetails[0] );
						$subjectComponentDetails = $result->getTableIdDetails ( $subjectIdDetails[1] );
						foreach($resultSetupIds as $resultSetupId){
							$details = $result->getTableIdDetails($resultSetupId);
							echo "<th style=\"width: 50px\">".$details['display_name']." - ".$subjectDetails['subject_name']." ".$subjectComponentDetails['subject_component_name']."</th>";							
						}
					}
					foreach ($assessmentIds as $assessmentId){
						$activityIds = $result->getActivityIds ( $assessmentId, 1 );
						foreach ($activityIds as $activityId){
							$details = $result->getTableIdDetails ( $activityId );
							$details = $result->getTableIdDetails ( $details['subject_id'] ); 							
							echo "<th  style=\"width: 50px\">".$details['subject_name']."</th>";
						}
					}
					
                ?>              
            </tr>
            </thead>
            <tbody>            	
            </tbody>
        </table>
    </fieldset>
</div>