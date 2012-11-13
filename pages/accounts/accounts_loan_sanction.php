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

$body = new body ();
$body->startBody ( 'accounts', 'LMENUL127', 'New Loan Sanction Form' );
?>
<div id="content_header">
    <div id="pageButton" class="buttons">
        <button type="button" class="regular toggle" onclick="showHideSearchForm()">Toggle Search Form</button>
        <button type="button" class="regular toggle" onclick="showHideDatatable()">Toggle Tabulated Data</button>
    </div>
    <div id="contentHeader">New Loan Sanction Form </div>
</div>
<div class="clear"></div>
<div class="inputs">
    <form id="insertForm" class="insertForm" onsubmit="return valid.validateForm(this) ? processInsertForm() : false;">
        <fieldset class="formelements">
            <div class="legend">
                <span>New Loan Sanction Form</span>
            </div>
            <dl class="element">
                <dt style="width: 15%">
                    <label for="employeeId">Employee Name :</label>
                </dt>
                <dd style="width: 30%">
                	<input type="hidden" name="employeeId_val" id="employeeId_val" />
                    <input type="text" name="employeeId" id="employeeId" class="required"  title="select employee for loan sanction"  tabindex="1" size="40" onchange="javascript: valid.validateInput(this);" />
                    <div id="employeeIdError" class="validationError"	style="display: none"></div>
                </dd>
                <dt style="width: 15%">
                    <label for="loanType">Loan Type :</label>
                </dt>
                <dd style="width: 30%">
                    <select name="loanType" id="loanType" class="required"  title="select the loan type"  tabindex="2" style="width: 200px" onchange="javascript: valid.validateInput(this);">
                    
                    </select>
                    <div id="loanTypeError" class="validationError"	style="display: none"></div>
                </dd>
            </dl>
            <dl class="element">
                <dt style="width: 15%">
                    <label for="amount">Loan Amount :</label>
                </dt>
                <dd style="width: 30%">
                    <input type="text" name="amount" id="amount" class="required numeric"  title="enter the loan sanction amount"  tabindex="4" value="" size="15" onchange="javascript: valid.validateInput(this);" />
                    <div id="amountError" class="validationError"	style="display: none"></div>
                </dd>
                <dt style="width: 15%">
                    <label for="installment">Total Installments :</label>
                </dt>
                <dd style="width: 30%">
                    <input type="text" name="installment" id="installment" class="required"  title="Total number of installments"  tabindex="5" value="" size="15" onchange="javascript: valid.validateInput(this);" />
                    <div id="installmentError" class="validationError"	style="display: none"></div>
                </dd>
            </dl>
            <dl class="element">
                
                <dt style="width: 15%">
                    <label for="loanDate">Sanction Date :</label>
                </dt>
                <dd style="width: 30%">
                    <input type="text" name="loanDate" id="loanDate" class="required"  title="select the loan sanction date" value="<?php echo $body->getCurrentDate(); ?>"  tabindex="6" size="20" onchange="javascript: valid.validateInput(this);" />
                    <div id="loanDateError" class="validationError"	style="display: none"></div>
                </dd>
                <dt style="width: 15%">
                    <label for="repaymentMonth">Repayment Month :</label>
                </dt>
                <dd style="width: 30%">
                    <select name="repaymentMonth" id="repaymentMonth" class="required"  title="start month of repayment of loan"  tabindex="8" style="width: 200px" onchange="javascript: valid.validateInput(this);">
                    	<?php 
                    	for ($i = 0; $i < 12; ++$i){
                    		$month = date('Ym', mktime(0, 0, 0, date('m')+$i, 15, date('Y')));
                    		$monthName = date('F, Y', mktime(0, 0, 0, date('m')+$i, 15, date('Y')));
                    		echo "<option value=\"$month\">".$monthName."</option>";
                    	}
                    ?>
                    </select>
                    <div id="repaymentMonthError" class="validationError"	style="display: none"></div>
                </dd>
            </dl>            
            <dl class="element">
                <dt style="width: 15%">
                    <label for="interest">Interest Applicable :</label>
                </dt>
                <dd style="width: 30%">
                    <input type="text" name="interest" id="interest" class=""  title="leave blank if flexi interest rate to be applied"  tabindex="10" size="15" onchange="javascript: valid.validateInput(this);" />
                    <div id="interestError" class="validationError"	style="display: none"></div>
                </dd>
                <dt style="width: 15%">
                    <label for="interestType">Interest Type :</label>
                </dt>
                <dd style="width: 30%">
                    <select name="interestType" id="interestType" class="required"  title="interestType"  tabindex="10" style="width: 200px" onchange="javascript: valid.validateInput(this);">
                    	
                    </select>
                    <div id="interestTypeError" class="validationError"	style="display: none"></div>
                </dd>
            </dl>
            <dl class="element">                
                <dt style="width: 15%">
                    <label for="paymentMode">Payment Mode :</label>
                </dt>
                <dd style="width: 30%">
                    <select name="paymentMode" id="paymentMode" class="required"  title="the mode of payment of loan amount"  tabindex="11" style="width: 200px" onblur="checkPaymentType()">
                    	
                    </select>
                    <div id="paymentModeError" class="validationError"	style="display: none"></div>
                </dd>
                <dt style="width: 15%">
                    <label for="flexiInstallment">Flexible Installment :</label>
                </dt>
                <dd style="width: 30%">
                    <select name="flexiInstallment" id="flexiInstallment" class="required"  title="if employee can change their installment amount"  tabindex="12" style="width: 200px" onchange="javascript: valid.validateInput(this);">
                    	<option value="n">Disabled</option>
                    	<option value="y">Enabled</option>
                    </select>
                    <div id="flexiInstallmentError" class="validationError"	style="display: none"></div>
                </dd>                
            </dl>
            <dl class="element" id="chequeDetails" style="display:none">
                <dt style="width: 15%">
                    <label for="bankName">Bank Name :</label>
                </dt>
                <dd style="width: 30%">
                    <select name="bankName" id="bankName" class="required"  title="Select the bank name"  tabindex="13" style="width: 200px" onchange="javascript: valid.validateInput(this);" >
                    </select>
                    <div id="bankNameError" class="validationError"	style="display: none"></div>
                </dd>
                <dt style="width: 15%">
                    <label for="chequeNumber">Cheque/Draft Number :</label>
                </dt>
                <dd style="width: 30%">
                    <input type="text" name="chequeNumber" id="chequeNumber" class=""  title="Enter The Cheque Number"  tabindex="14" size="30" onchange="javascript: valid.validateInput(this);" />
                    <div id="chequeNumberError" class="validationError"	style="display: none"></div>
                </dd>
            </dl>
        </fieldset>
        <fieldset class="action buttons">
            <button type="reset" name="insertReset" id="insertReset" class="negative reset">Reset Form</button>
            <button type="button" name="submit" class="regular hide" onclick="hideInsertForm()">Hide
                Insert Form</button>
            <button type="submit" name="submit" id="submit" class="positive insert" accesskey="I">Sanction New Loan</button>
        </fieldset>
    </form>
</div>
<div class="clear"></div>
<div class="display">
    <div id="displayRecord" style="display:none">
    <form id="updateForm" class="updateForm" onsubmit="return valid.validateForm(this) ? processUpdateForm() : false;">
        <fieldset class="displayElements">
            <div class="legend">
                <span>Loan Sanction Record Details Confirmation Display </span>
            </div>
            <dl>
                <dt style="width: 15%;">
                    <label for="employeeName_d">Employee Name :</label>
                </dt>
                <dd style="width: 30%">
                	<input type="hidden" name="employeeId_u" id="employeeId_u" />
                    <span id="employeeName_d"></span>
                </dd>
                <dt style="width: 15%;">
                    <label for="employeeCode_d">Employee Id :</label>
                </dt>
                <dd style="width: 30%">                	
                    <span id="employeeCode_d"></span>
                </dd>
            </dl>
            <dl>
                <dt style="width: 15%;">
                    <label for="loanType_d">Loan Name :</label>
                </dt>
                <dd style="width: 30%">
                	<input type="hidden" name="loanType_u" id="loanType_u" />
                    <span id="loanType_d"></span>
                </dd>
                <dt style="width: 15%;">                	
                    <label for="sanctionDate_d">Sanction Date :</label>
                </dt>
                <dd style="width: 30%">
                	<input type="hidden" name="loanDate_u" id="loanDate_u" />
                    <span id="sanctionDate_d"></span>
                </dd>
            </dl>
            <dl>
                <dt style="width: 15%;">
                    <label for="loanAmount_d">Loan Amount :</label>
                </dt>
                <dd style="width: 30%">
                	<input type="hidden" name="amount_u" id="amount_u" />
                    <span id="loanAmount_d"></span>
                </dd>                
                <dt style="width: 15%;">
                    <label for="repaymentMonth_d">Repayment Month :</label>
                </dt>
                <dd style="width: 30%">
                	<input type="hidden" name="repaymentMonth_u" id="repaymentMonth_u" />
                    <span id="repaymentMonth_d"></span>
                </dd>
            </dl>
            <dl>
                <dt style="width: 15%;">
                    <label for="interestApplicable_d">Interest Rate :</label>
                </dt>
                <dd style="width: 30%">
                	<input type="hidden" name="interest_u" id="interest_u" />
                    <span id="interestApplicable_d"></span>
                </dd>
                
                <dt style="width: 15%;">
                    <label for="interestType_d">Interest Type :</label>
                </dt>
                <dd style="width: 30%">
                	<input type="hidden" name="interestType_u" id="interestType_u" />
                    <span id="interestType_d"></span>
                </dd>
            </dl>
            <dl>
            	<dt style="width: 15%;">
                    <label for="totalInstallments_d">Total Installments :</label>
                </dt>
                <dd style="width: 30%">
                	<input type="hidden" name="installment_u" id="installment_u" />
                    <span id="totalInstallments_d"></span>
                </dd>
                <dt style="width: 15%;">
                    <label for="installmentAmount_d">Installment Amount :</label>
                </dt>
                <dd style="width: 30%">
                    <span id="installmentAmount_d"></span>
                </dd>
            </dl>
            <dl>
                <dt style="width: 15%;">
                    <label for="paymentMode_d">Payment Mode :</label>
                </dt>
                <dd style="width: 30%">
                	<input type="hidden" name="paymentMode_u" id="paymentMode_u" />
                    <span id="paymentMode_d"></span>
                </dd>
                <dt style="width: 15%;">
                    <label for="flexiInstallment_d">Flexi Installment :</label>
                </dt>
                <dd style="width: 30%">
                	<input type="hidden" name="flexiInstallment_u" id="flexiInstallment_u" />
                    <span id="flexiInstallment_d"></span>
                </dd>
            </dl>
            <dl id="chequeDetails_d">
                <dt style="width: 15%;">
                    <label for="bankName_d">Bank Name :</label>
                </dt>
                <dd style="width: 30%">
                	<input type="hidden" name="bankName_u" id="bankName_u" />
                    <span id="bankName_d"></span>
                </dd>
                <dt style="width: 15%;">
                    <label for="chequeNumber_d">Cheque Number :</label>
                </dt>
                <dd style="width: 30%">
                	<input type="hidden" name="chequeNumber_u" id="chequeNumber_u" />
                    <span id="chequeNumber_d"></span>
                </dd>
            </dl>
        </fieldset>
        <fieldset class="action buttons">            
            <button type="button" class="negative edit" id="editRecordButton"
                    class="editRecordButton" onclick="editRecord()">Edit Record</button>                   
            <button type="submit" name="submit" id="submit" class="positive insert" >Confirm Loan Sanction</button> 
        </fieldset>
    </form>
    </div>
</div>
<div class="clear"></div>
<div class="datatable buttons" id="displayDatatable" style="display:none">
    <fieldset class="formelements">
        <div class="legend">
            <span>Tabulated Listing Of All Loan Accounts Of The Employee</span>
        </div>
        <table  class="display"
               id="groupRecords">
            <thead>
            <tr>
            	<th>Employee Name</th>
            	<th>Loan Account</th>
            	<th>Balance Amount</th>
            	<th>Installment Amount</th>
            </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </fieldset>
</div>