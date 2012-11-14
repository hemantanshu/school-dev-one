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

$body->startBody ( 'utility', 'LMENUL37', 'New Employee Registration' );
?>
<div id="content_header">
    <div id="contentHeader">New Employee Entry Form</div>
</div>
<div class="inputs">
	<form id="insertForm" class="insertForm" action="#"
		onsubmit="return valid.validateForm(this) ? processInsertForm() : false;">
		<fieldset>
			<div class="legend">
				<span>Employee Registration Details</span>
			</div>
			<dl align="center">
				<span class="inlineFormDisplay"> If the employee has filled up pre-employment application form,
					please fill up the application form id and it would populate all
					the forms</span>
			</dl>
			<dl class="element">
				<dt style="width: 15%"><label for="employeeCode">Employee Code :</label>	</dt>
				<dd style="width: 30%">
						<input type="text" name="employeeCode" id="employeeCode" class="required" tabindex="1" size="30" onblur="checkEmployeeCode()" onchange="javascript: valid.validateInput(this);" title="Enter the employee code" />
						<div id="employeeCodeError" class="validationError" style="display: none"></div></dd>
				<dt style="width: 15%"><label for="applicationId">Applicatoin ID :</label>	</dt>
				<dd style="width: 30%">
						<input type="text" name="applicationId" id="applicationId" class="" tabindex="2" size="30" onchange="javascript: valid.validateInput(this);" title="Application ID againt pre-employment form" />
						<div id="applicationIdError" class="validationError" style="display: none"></div></dd>
			</dl>
			<dl class="element">
				<dt style="width: 15%"><label for="joiningDate">Joining Date :</label>	</dt>
				<dd style="width: 30%">
						<input type="text" name="joiningDate" id="joiningDate" class="required date" tabindex="3" size="20" onchange="javascript: valid.validateInput(this);" title="" />
						<div id="joiningDateError" class="validationError" style="display: none"></div></dd>
				<dt style="width: 15%"><label for="rank">Joining As :</label>	</dt>
				<dd style="width: 30%">
						<input type="hidden" name="rank_val" id="rank_val" value=""/>
						<input type="text" name="rank" id="rank" class="required" tabindex="4" size="30" onchange="javascript: valid.validateInput(this);" title="Select the rank of the employee" />
						<div id="rankError" class="validationError" style="display: none"></div></dd>
			</dl>
		</fieldset>
		<br />
		<fieldset>
			<div class="legend">
				<span>Employee Personal Details</span>
			</div>
			<dl class="element">
				<dt style="width: 15%">

					<label for="EmployeeName">Name :</label>
				</dt>
				<dd style="width: *">
					<select id="salutation" name="salutation" style="width: 80px"
						tabindex="5" onchange="javascript: valid.validateInput(this);">
						<?php
						$salutationIds = $options->getOptionSearchValueIds ( '', 'SALUT', 1 );
						foreach ( $salutationIds as $salutationId )
							echo "<option value=\"" . $salutationId . "\">" . $options->getOptionIdValue ( $salutationId ) . "</option>";
						?>
						
					</select> <input type="text" name="firstName" id="firstName"
						tabindex="6" class="required"
						title="Enter Employee First Name" value="" size="30"
						onchange="javascript: valid.validateInput(this);" /> <input
						type="text" name="middleName" id="middleName" class=""
						tabindex="7" title="Enter Employee Middle Name" value=""
						size="30" onchange="javascript: valid.validateInput(this);" /> <input
						type="text" name="lastName" id="lastName" class="required"
						tabindex="8" title="Enter Employee Last Name" value=""
						size="30" onchange="javascript: valid.validateInput(this);" />
					<div id="salutationError" class="validationError"
						style="display: none;"></div>
					<div id="firstNameError" class="validationError"
						style="display: none;"></div>
					<div id="lastNameError" class="validationError"
						style="display: none;"></div>
				</dd>
			</dl>
			<dl class="element">
				<dt style="width: 15%"><label for="bday">Date Of Birth</label>	</dt>
				<dd style="width: 80%">
						<input type="text" name="bday" id="bday" class="required date"
						tabindex="9"
						title="Enter The Date Of Birth in yyyy/mm/dd Format" value=""
						maxlength="10" size="20"
						onchange="javascript: valid.validateInput(this);" />
					<div id="bdayError" class="validationError" style="display: none;"></div></dd>
			</dl>
			<dl class="element">				
				<dt style="width: 15%">

					<label for="gender">Gender :</label>
				</dt>
				<dd style="width: 30%">
					<select name="gender" id="gender" class="required" style="width: 150px"
						tabindex="10" title="Select The Gender" onchange="javascript:valid.validateInput(this);" >
						<option value="Male">Male</option>
						<option value="Female">Female</option>
					</select>
					<div id="genderError" class="validationError"
						style="display: none;"></div>
				</dd>
				<dt style="width: 15%">

					<label for="gender">Marital Status :</label>
				</dt>
				<dd style="width: 30%">
					<select name="marital" id="marital" class="required"
						tabindex="11" title="Select The Marital Status" onchange="javascript:valid.validateInput(this);" style="width: 150px" >
					<?php 
						$ids = $options->getOptionSearchValueIds('', 'MARST', 1);
						foreach($ids as $id)
							echo "<option value=\"".$id."\">".$options->getOptionIdValue($id)."</option>";						
					?>	
					</select>
					<div id="maritalError" class="validationError"
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
						type="text" tabindex="12" name="religion" id="religion" class=""
						title="Enter The Religion" value="" size="30" />
					<div id="religionError" class="validationError"
						style="display: none;"></div>

				</dd>
				<dt style="width: 15%">

					<label for="nationality">Nationality :</label>
				</dt>
				<dd style="width: 30%">
					<input type="hidden" name="nationality_val" id="nationality_val"
						value="" /> <input tabindex="13" type="text" name="nationality"
						id="nationality" class="required" title="Enter The Nationality"
						value="" size="30"
						onchange="javascript: valid.validateInput(this);" />
					<div id="nationalityError" class="validationError"
						style="display: none;"></div>
				</dd>
			</dl>
			<dl class="element">
				<dt style="width: 15%"><label for="personalEmail">Personal Email :</label>	</dt>
				<dd style="width: 30%">
						<input type="text" name="personalEmail" id="personalEmail" class="" tabindex="14" size="30" onchange="javascript: valid.validateInput(this);" title="" />
						<div id="personalEmailError" class="validationError" style="display: none"></div></dd>
				<dt style="width: 15%"><label for="officialEmail">Official Email :</label>	</dt>
				<dd style="width: 30%">
						<input type="text" name="officialEmail" id="officialEmail" class="" tabindex="15" size="30" onchange="javascript: valid.validateInput(this);" title="" />
						<div id="officialEmailError" class="validationError" style="display: none"></div></dd>
			</dl>
			<dl class="element">
				<dt style="width: 15%"><label for="contactNo">Mobile No :</label>	</dt>
				<dd style="width: 30%">
						<input type="text" name="contactNo" id="contactNo" class="phone" tabindex="16" size="30" onchange="javascript: valid.validateInput(this);" title="" />
						<div id="contactNoError" class="validationError" style="display: none"></div></dd>
				<dt style="width: 15%"><label for="aContactNo">Alt. Contact No :</label>	</dt>
				<dd style="width: 30%">
						<input type="text" name="aContactNo" id="aContactNo" class="phone" tabindex="17" size="30" onchange="javascript: valid.validateInput(this);" title="" />
						<div id="aContactNoError" class="validationError" style="display: none"></div></dd>
			</dl>
		</fieldset>
		<br />
		<fieldset>
			<div class="legend">
				<span>Correspondence Address Details </span>
			</div>
			<dl class="element">
				<dt style="width: 15%"><label for="streetAddress1">Flat / House No :</label>	</dt>
				<dd style="width: 30%">
						<input type="text" name="streetAddress1" size="40" id="streetAddress1" class="required" value="" tabindex="18" onchange="javascript: valid.validateInput(this);" title="Enter the House Details" />
						<div id="streetAddress1Error" class="validationError" style="display: none"></div></dd>				
				<dt style="width: 15%"><label for="streetAddress2">Street Address :</label>	</dt>
				<dd style="width: 30%">
						<input type="text" size="40" name="streetAddress2" id="streetAddress2" class="required" value="" tabindex="19" onchange="javascript: valid.validateInput(this);" title="Enter the street address" />
						<div id="streetAddress2Error" class="validationError" style="display: none"></div></dd>		
			</dl>
			
			<dl class="element">
				<dt style="width: 15%"><label for="city">City :</label>	</dt>
				<dd style="width: 30%">
						<input type="hidden" name="city_val" id="city_val" value=""/>
						<input type="text" name="city" id="city" class="required" tabindex="20" value="" size="30" onchange="javascript: valid.validateInput(this);" title="Enter the city" />
						<div id="cityError" class="validationError" style="display: none"></div></dd>
				<dt style="width: 15%"><label for="state">State :</label>	</dt>
				<dd style="width: 30%">
						<input type="hidden" name="state_val" id="state_val" value=""/>
						<input type="text" name="state" id="state" class="required" tabindex="21" value="" size="30" onchange="javascript: valid.validateInput(this);" title="Enter the state" />
						<div id="stateError" class="validationError" style="display: none"></div></dd>
			</dl>			
			<dl class="element">
				<dt style="width: 15%"><label for="pincode">Pincode :</label>	</dt>
				<dd style="width: 30%">
						<input type="text" name="pincode" id="pincode" class="required numeric" tabindex="22" value="" size="30" onchange="javascript: valid.validateInput(this);" title="Enter the pincode of the address" />
						<div id="pincodeError" class="validationError" style="display: none"></div></dd>
				<dt style="width: 15%"><label for="country">Country :</label>	</dt>
				<dd style="width: 30%">
						<input type="hidden" name="country_val" id="country_val" value=""/>
						<input type="text" name="country" id="country" class="required" tabindex="23" value="" size="30" onchange="javascript: valid.validateInput(this);" title="Enter the country name" />
						<div id="countryError" class="validationError" style="display: none"></div></dd>
			</dl>			
		</fieldset>
		<br />
		<fieldset>
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
						name="recordShelve1" id="recordShelve1" class=""
						title="Enter The Record Shelve1" value="" size="30"
						tabindex="24" onchange="javascript: valid.validateInput(this);" />
					<div id="recordShelve1Error" class="validationError"
						style="display: none;"></div>
				</dd>
				<dt style="width: 15%">

					<label for="recordShelve2">Record Shelve2 :</label>
				</dt>
				<dd style="width: 30%">
					<input type="hidden" name="recordShelve2_val"
						id="recordShelve2_val" value="" /> <input type="text"
						name="recordShelve2" id="recordShelve2" class=""
						title="Enter The Record Shelve2" value="" size="30"
						tabindex="25" onchange="javascript: valid.validateInput(this);" />
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
						name="recordShelve3" id="recordShelve3" class="" tabindex="26"
						title="Enter The Record Shelve3" value="" size="30"
						onchange="javascript: valid.validateInput(this);" />
					<div id="recordShelve3Error" class="validationError"
						style="display: none;"></div>
				</dd>
			</dl>
			<dl class="element">
		        <dt style="width: 15%"><label for="department">Department Name :</label>	</dt>
		        <dd style="width: 30%">
		            <input type="hidden" name="department_val" id="department_val" class="required" />
		            <input type="text" name="department" id="department" class="required" tabindex="27" size="30" onchange="javascript: valid.validateInput(this);" title="Enter the department" />
		            <div id="department_valError" class="validationError" style="display: none"></div></dd>
		        <dt style="width: 15%"><label for="employeeType">Employee Type :</label>	</dt>
		        <dd style="width: 30%">
		            <input type="hidden" name="employeeType_val" id="employeeType_val" class="required" />
		            <input type="text" name="employeeType" id="employeeType" class="required" tabindex="28" size="30" onchange="javascript: valid.validateInput(this);" title="Enter the employeeType" />
		            <div id="employeeType_valError" class="validationError" style="display: none"></div></dd>
		    </dl>
		</fieldset>

		<fieldset class="action buttons">
			<div id="up2" style="float: left; margin-right: 20px"></div>
			<button type="reset" name="insertReset" id="insertReset"
				accesskey="R" class="negative reset">
				<span class="underline">R</span>eset Form Fields
			</button>

			<button type="submit" name="submit" id="submit" accesskey="I" class="positive insert">
				<span class="underline">I</span>nsert New Record
			</button>
		</fieldset>


	</form>
</div>
<?php
$body->endBody ( 'utility', 'MENUL37' );
?>
