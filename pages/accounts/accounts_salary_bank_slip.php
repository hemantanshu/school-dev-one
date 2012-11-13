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

$body = new body ();
$options = new options();

$body->startBody('accounts', 'LMENUL133', 'Bank Slip Printing');

?>

<div id="content_header" class="noPrint">
    <div id="pageButton" class="buttons">
    	<button type="button" class="negative toggle" onclick="changeDate()">Change Date </button>
    </div>
    <div id="contentHeader">Payment Slip Printing</div>
</div>
<div class="clear"></div>
<div id="choiceListing">
    <div class="inputs">
    <form id="allowanceForm" class="allowanceForm" onsubmit="return valid.validateForm(this) ? processAllowanceForm() : false;">    
        <fieldset class="formelements buttons">
            <div class="legend">
                <span>Select Month For Payment Slip Printing</span>
            </div>
            <dl class="element">
                <dt style="width: 15%"><label for="month">Payment Month :</label>	</dt>                
                <dd style="width: 20%">
              		<select name="month" id="month" onchange="javascript: valid.validateInput(this);">
              		<?php 
              			$i = 0;
              			$monthOptions = '';
              			while(true){              				
              				$month = date('Ym', mktime(0, 0, 0, date('m') - $i, 15, date('Y')));
              				if($month < 201204)
              					break;
              				$monthName = date('F, Y', mktime(0, 0, 0, date('m') - $i, 15, date('Y')));
              				$monthOptions .= "<option value=\"$month\">".$monthName."</option>";
              				++$i;
              			}
              			echo $monthOptions;
              		?>
                    </select>                    
              	</dd>
              	<dt style="width: 20%"></dt>
                <dd style="width: 25%">
                	<button type="button" style="width: 250px; text-align: left" class="positive insert" onclick="printPaymentPaySlip()">Print Salary Payment Slip</button></dd>                                 
            </dl>
        </fieldset>
   	</form>     
    </div>
</div>
<div class="clear"></div>
<div id="completePageDisplay" style="margin: auto; display: none">
      
</div>