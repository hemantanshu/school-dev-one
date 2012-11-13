<?php
require_once 'config.php';
require_once BASE_PATH . 'include/global/class.body.php';
require_once BASE_PATH . 'include/utility/class.sections.php';

$body = new body ();
$sections = new sections();

$body->startBody ( 'exam', 'LMENUL94', 'Quick Class Selection For Examination', '', false );
$candidateId = $_GET['candidateId'];
$resultId = $_GET['resultId'];
?>
<div style="height: 20px">

</div>
<input type="hidden" name="resultId_direct" id="resultId_direct" value="<?php echo $resultId; ?>" />
<input type="hidden" name="candidateId_direct" id="candidateId_direct" value="<?php echo $candidateId; ?>" />


<div class="clear"></div>

<div class="datatable buttons" id="displayDatatable" style="height: 350px">
    <fieldset class="formelements">
        <div class="legend">
            <span>Tabulated Listing Of Result Record Processing</span>
        </div>
        <table  class="display"
               id="groupRecords">
            <thead>
            <tr>
                <th>Officer Name</th>
                <th>Date</th>
            </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </fieldset>
</div>
