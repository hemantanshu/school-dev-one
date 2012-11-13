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
require_once BASE_PATH . 'include/accounts/class.loan.php';

$body = new body ();
$loan = new Loan ();

$body->startBody ( 'accounts', 'LMENUL145', 'Loan Record Listing' );

$loanAccountId = $_GET ['loanAccountId'];
$details = $loan->getTableIdDetails($loanAccountId);
if($details['employee_id'] == '')
	exit(0);
$loanDetails = $loan->getTableIdDetails($details['loan_type_id']);
?>

<div id="content_header">
	<div id="pageButton" class="buttons">
	</div>
	<div id="contentHeader">Employee Loan Statement</div>
</div>
<div class="clear"></div>
<div class="display">
    <div id="displaySubjectRecord">
        <fieldset class="displayElements">
			<dl>
                <dt style="width: 15%;">
                    <label for="employeeName">Employee Name :</label>
                </dt>
                <dd style="width: 30%">
                    <span id="employeeName"><?php echo $loan->getOfficerName($details['employee_id']); ?></span>
                </dd>
                <dt style="width: 15%">
                    <label for="employeeCode">Emp Code :</label>
                </dt>
                <dd style="width: 30%">
                    <span id="employeeCode"><?php echo $loan->getEmployeeCode($details['employee_id']); ?></span>
                </dd>
            </dl>
            <dl>
                <dt style="width: 15%;">
                    <label for="laonType">Loan Type :</label>
                </dt>
                <dd style="width: 30%">
                    <span id="loanType"><?php echo $loanDetails['loan_name']; ?></span>
                </dd>
                <dt style="width: 15%">
                    <label for="totalInstallment">Installment Amt. :</label>
                </dt>
                <dd style="width: 30%">
                    <span id="totalInstallment"><?php echo number_format($details['installment_amount'], 2, '.', ','); ?></span>
                </dd>
            </dl>
        </fieldset>
    </div>
</div>
<div class="clear"></div>
<div class="datatable buttons" id="displayDatatable"
	style="display: nones">
	<fieldset class="formelements">
		<div class="legend">
			<span>Loan Statement Listing</span>
		</div>
		<table class="display" id="groupRecordsDirect">
			<thead>
				<tr>
					<th>Comments</th>
					<th>Date</th>					
					<th>Debit</th>
					<th>Credit</th>
					<th>Balance</th>
				</tr>
			</thead>
			<tbody>
			<?php 
				$balance = 0;
				$installmentIds = $loan->getLoanAccountInstallmentIds($loanAccountId);
				foreach ($installmentIds as $installmentId){
					$details = $loan->getTableIdDetails($installmentId);
					$amount = $details['amount'];
					if ($amount == 0)
						continue;
					$debitAmount = $amount > 0 ? number_format($amount, 2, '.', ',') : '';
					$creditAmount = $amount < 0 ? number_format(abs($amount), 2, '.', ',') : '';
					
					$balance += $amount;
					echo "<tr>";
					echo "<td>".$body->getOptionIdValue($details['payment_type'])."</td>";
					echo "<td>".$body->getDisplayDate($details['payment_date'])."</td>";
					
					echo "<td>".$creditAmount."</td>";
					echo "<td>".$debitAmount."</td>";
					echo "<td>".number_format($balance, 2, '.', ',')."</td>";
					echo "</tr>";
				}
			?>
			</tbody>
		</table>
	</fieldset>
</div>
<br />
<br />