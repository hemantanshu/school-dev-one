<?php
require_once 'config.php';
require_once BASE_PATH . 'include/global/class.body.php';
require_once BASE_PATH . 'include/utility/class.sections.php';
require_once BASE_PATH . 'include/global/class.options.php';

$body = new body ();
$sections = new sections();
$options = new options();

$body->startBody ( 'exam', 'LMENUL71', 'Quick Class Selection For Examination', '', false );
$examinationId = $_GET['examinationId'];
$sessionId = $_GET['sessionId'];
$flag = $_GET['flag'] == "" ? 1 : 0;

$assingmentIds = $options->getAssignmentIds($examinationId, 'CLSSA', '1');
$sectionIds = array();

foreach ($assingmentIds as $assingmentId){
	$details = $options->getTableIdDetails($assingmentId);
	$sectionIds[$details['value_set']] = $sections->getClassName4Section($details['value_set'])." ".$sections->getSectionName($details['value_set']);
}
asort($sectionIds);
?>
<div style="height: 20px">

</div>
<input type="hidden" name="examinationId_direct" id="examinationId_direct" value="<?php echo $examinationId; ?>" />
<input type="hidden" name="flag_direct" id="flag_direct" value="<?php echo $flag; ?>" />


<div class="inputs">
    <form id="insertForm" class="insertForm"
          onsubmit="return valid.validateForm(this) ? processClassSelection() : false;">
        <fieldset class="formelements">
            <div class="legend">
                <span>Select The Class Name For New Examination Date</span>
            </div>
            <dl class="element">
                <dt style="width: 30%"><label for="classId">Class Name :</label>	</dt>
                <dd style="width: 60%">
                    <select name="classId" id="sectionId" class="sectionId" style="width: 200px" onchange="processClassSelection()">
                    	<?php
                    		foreach ($sectionIds as $key => $className){
                    			echo "<option value=\"$key\">".$className."</option>";
                    		} 
                    	?>
                        
                    </select>
                    <div id="classNameError" class="validationError" style="display: none"></div></dd>
            </dl>
        </fieldset>
        <fieldset class="action buttons">
            <button type="submit" name="submit" id="submit" accesskey="I" class="positive insert">
                <img src="<?php echo $body->getBaseServer(); ?>images/global/icons/Create.png" alt="" />
                Select Class
            </button>
        </fieldset>
    </form>
</div>

