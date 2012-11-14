<?php
/**
 * 
 * @author shubhamkesarwani@supportgurukul.com (html)
 * @category Utility
 * @license Support-Gurukul
 * @version 1.0.0
 */

require_once 'config.php';
require_once BASE_PATH . 'include/global/class.body.php';
require_once BASE_PATH . 'include/global/class.options.php';

$body = new body ();
$options = new options ();

$body->startBody ( 'utility', 'LMENUL25', 'New Candidate Registration' );
?>
<div id="content_header">
    <div id="pageButton" class="buttons"></div>
    <div id="contentHeader">New Candidate Record Entry Form</div>
</div>
<div class="inputs">
	<form id="insertForm" class="insertForm" action="#"
		onsubmit="return valid.validateForm(this) ? (processInsertForm() ? false: false) : false;">
		<fieldset class="formelements">
			<div class="legend">
				<span>Candidate Registration Details</span>
			</div>
			<dl align="center">
				<span class="inlineFormDisplay">If the candidate has given entrance, please fill up the entrance
					form id and it would populate all the forms</span>
			</dl>
			<dl class="element">
				<dt style="width: 15%">

					<label for="entranceId">Entrance ID :</label>
				</dt>
				<dd style="width: 30%">
					<input type="text" name="entranceId" id="entranceId"
						tabindex="1"
						class="" title="Enter The Entrance ID " value="" size="30"
						onblur="populateFormElements()" />
					<div id="entranceIdError" class="validationError"
						style="display: none;"></div>
				</dd>
			</dl>
			<dl class="element">
				<dt style="width: 15%">

					<label for="registrationNumber">Registration Number :</label>
				</dt>
				<dd style="width: 30%">

					<input type="text" name="registrationNumber"
						id="registrationNumber" class="required"
						title="Enter The Regi stration Number" value="" size="30"
						tabindex="2"
						onchange="javascript: valid.validateInput(this);" />
					<div id="registrationNumberError" class="validationError"
						style="display: none;"></div>
				</dd>
				<dt style="width: 15%">

					<label for="registrationDate">Registration Date :</label>
				</dt>
				<dd style="width: 30%">
					<input type="text" name="registrationDate" id="registrationDate"
						class="required date" value="<?php echo date("Y-m-d"); ?>"
						title="Enter The Registration Date" value="" size="30"
						tabindex="3"
						onblur="javascript: valid.validateInput(this);" />
					<div id="registrationDateError" class="validationError"
						style="display: none;"></div>
				</dd>
			</dl>
		</fieldset>
		<br />
		<fieldset class="formelements">
			<div class="legend">
				<span>Candidate Personal Details</span>
			</div>
			<dl align="center">
				<span class="inlineFormDisplay">Fill In The Pesonal Details Of The Candidate</span>
			</dl>
			<dl class="element">
				<dt style="width: 15%">

					<label for="candidateName">Candidate Name :</label>
				</dt>
				<dd style="width: *">
					<select id="salutation" name="salutation" style="width: 80px" tabindex="4"
						onchange="javascript: valid.validateInput(this);">
						<?php
						$salutationIds = $options->getOptionSearchValueIds ( '', 'SALUT', 1 );
						foreach ( $salutationIds as $salutationId )
							echo "<option value=\"" . $salutationId . "\">" . $options->getOptionIdValue ( $salutationId ) . "</option>";
						?>
						
					</select> <input type="text" name="firstName" id="firstName"
						tabindex="5"
						class="required" title="Enter Candidate First Name" value=""
						size="30" onchange="javascript: valid.validateInput(this);" /> <input
						type="text" name="middleName" id="middleName" class=""
						tabindex="6"
						title="Enter Candidate Middle Name" value="" size="30"
						onchange="javascript: valid.validateInput(this);" /> <input
						type="text" name="lastName" id="lastName" class="required" tabindex="7"
						title="Enter Candidate Last Name" value="" size="30"
						onchange="javascript: valid.validateInput(this);" />
					<div id="salutationError" class="validationError"
						style="display: none;"></div>
					<div id="firstNameError" class="validationError"
						style="display: none;"></div>
					<div id="lastNameError" class="validationError"
						style="display: none;"></div>
				</dd>
			</dl>
			<dl class="element">
				<dt style="width: 15%">

					<label for="bday">Date Of Birth :</label>
				</dt>
				<dd style="width: 30%">
					<input type="text" name="bday" id="bday" class="date required"
						tabindex="8"
						title="Enter The Date Of Birth in yy/mm/dd Format" value=""
						maxlength="10" size="30"
						onchange="javascript: valid.validateInput(this);" />
					<div id="bdayError" class="validationError" style="display: none;"></div>
				</dd>
				<dt style="width: 15%">

					<label for="gender">Gender :</label>
				</dt>
				<dd style="width: 30%">				
					<select size="1" name="gender" id="gender" class="required"
						tabindex="9"
						title="Select The Gender onchange=" javascript:valid.validateInput(this);" >
						<option value="Male">Male</option>
						<option value="Female">Female</option>
					</select>
					<div id="genderError" class="validationError"
						style="display: none;"></div>
				</dd>
			</dl>
			<dl class="element">
				<dt style="width: 15%">

					<label for="religion">Religion :</label>
				</dt>
				<dd style="width: 30%">
					<input type="hidden" name="religion_val" id="religion_val" value=""
						onchange="javascript: valid.validateInput(this);" /> <input
						type="text" tabindex="10" name="religion" id="religion" class="autocomplete"
						title="Enter The Religion" value="" size="30" />
					<div id="religionError" class="validationError"
						style="display: none;"></div>

				</dd>
				<dt style="width: 15%">

					<label for="nationality">Nationality :</label>
				</dt>
				<dd style="width: 30%">
					<input type="hidden" name="nationality_val" id="nationality_val"
						value="" /> <input tabindex="11" type="text" name="nationality" id="nationality"
						class="autocomplete" title="Enter The Nationality" value="" size="30"
						onchange="javascript: valid.validateInput(this);" />
					<div id="nationalityError" class="validationError"
						style="display: none;"></div>

				</dd>
			</dl>
		</fieldset>
		<br />
		<fieldset class="formelements">
			<div class="legend">
				<span>Class & Section Allotment Details</span>
			</div>
			<dl align="center">
				<span class="inlineFormDisplay">Depending on the admitted class, system will suggest on the
					section & the house, yet you can modify as per your wish</span>
			</dl>
			<dl class="element">
				<dt style="width: 15%">

					<label for="classAdmitted">Class Admitted :</label>
				</dt>
				<dd style="width: 30%">
					<input type="hidden" name="classAdmitted_val" 
						id="classAdmitted_val" value=""/> <input tabindex="12" type="text"
						name="classAdmitted" id="classAdmitted" class="required autocomplete"
						title="Enter  Class you Admitted" value="" size="30"
						onblur="populateSectionDetails()" />
					<div id="classAdmittedError" class="validationError"
						style="display: none;"></div>

				</dd>
				<dt style="width: 15%">

					<label for="sectionAdmitted">Section Admitted :</label>
				</dt>
				<dd style="width: 30%">
					<input type="hidden" name="sectionAdmitted_val"
						id="sectionAdmitted_val" value="" /> <input type="text" tabindex="13"
						name="sectionAdmitted" id="sectionAdmitted" readonly="readonly"
						value="Select Class First" class="required autocomplete"
						title="Enter The Section You Admitted" value="" size="30"
						onchange="javascript: valid.validateInput(this);" />
					<div id="sectionAdmittedError" class="validationError"
						style="display: none;"></div>

				</dd>
			</dl>
			<dl class="element">
				<dt style="width: 15%">

					<label for="allottedHouse">Allotted House :</label>
				</dt>
				<dd style="width: 30%">
					<input type="hidden" name="allottedHouse_val"
						id="allottedHouse_val" value="" /> <input type="text"
						name="allottedHouse" id="allottedHouse" class="required autocomplete" tabindex="14"
						readonly="readonly"
						title="Enter The Allotted House" value="Select Class First" size="30"
						onchange="javascript: valid.validateInput(this);" />
					<div id="allottedHouseError" class="validationError"
						style="display: none;"></div>
				</dd>
			</dl>
		</fieldset>
		<br />
		<fieldset class="formelements">
			<div class="legend">
				<span>Book Keeping Details</span>
			</div>
			<dl class="element">
				<dt style="width: 15%">

					<label for="recordShelve1">Record Shelve1 :</label>
				</dt>
				<dd style="width: 30%">
					<input type="hidden" name="recordShelve1_val"
						id="recordShelve1_val" value="" /> <input type="text"
						name="recordShelve1" id="recordShelve1" class="autocomplete"
						title="Enter The Record Shelve1" value="" size="30"
						tabindex="15"
						onchange="javascript: valid.validateInput(this);" />
					<div id="recordShelve1Error" class="validationError"
						style="display: none;"></div>
				</dd>
				<dt style="width: 15%">

					<label for="recordShelve2">Record Shelve2 :</label>
				</dt>
				<dd style="width: 30%">
					<input type="hidden" name="recordShelve2_val"
						id="recordShelve2_val" value="" /> <input type="text"
						name="recordShelve2" id="recordShelve2" class="autocomplete"
						title="Enter The Record Shelve2" value="" size="30"
						tabindex="16"
						onchange="javascript: valid.validateInput(this);" />
					<div id="recordShelve2Error" class="validationError"
						style="display: none;"></div>
				</dd>
			</dl>

			<dl class="element">
				<dt style="width: 15%">

					<label for="recordShelve3">Record Shelve3 :</label>
				</dt>
				<dd style="width: 30%">
					<input type="hidden" name="recordShelve3_val"
						id="recordShelve3_val" value="" /> <input type="text"
						name="recordShelve3" id="recordShelve3" class="autocomplete"
						tabindex="17"
						title="Enter The Record Shelve3" value="" size="30"
						onchange="javascript: valid.validateInput(this);" />
					<div id="recordShelve3Error" class="validationError"
						style="display: none;"></div>
				</dd>
			</dl>
		</fieldset>

		<fieldset class="action buttons">
			<div id="up2" style="float: left; margin-right: 20px"></div>
			<button type="reset" name="insertReset" id="insertReset" class="negative reset"
				accesskey="R">
				<span class="underline">R</span>eset Form Fields
			</button>

			<button type="submit" name="submit" id="submit" accesskey="I" class="positive insert">
				<span class="underline">I</span>nsert New Record
			</button>
		</fieldset>


	</form>
</div>
<?php
$body->endBody ( 'utility', 'MENUL25' );
?>
