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
require_once BASE_PATH . 'include/utility/class.personalInfo.php';

$body = new body ();
$options = new options ();
$personalInfo = new personalInfo();

$body->startBody ( 'utility', 'LMENUL31', 'Candidate Guardian Information' );

$candidateId = $_GET ['candidateId'];
$gaurdianId = $_GET ['guardianId'];

$gaurdianName = $options->getOptionIdValue($gaurdianId);
$totalGaurdianTypes = $options->getOptionSearchValueIds('', 'GAURC', 1);

if(!in_array($gaurdianId, $totalGaurdianTypes))
	exit(0);
?>
<div id="content_header">
    <div id="pageButton" class="buttons">
    </div>
    <div id="contentHeader">Candidate <?php echo $gaurdianName; ?> Details Record Entry/Edit Form</div>
</div>
<input type="hidden" name="candidateId"	value="<?php echo $candidateId; ?>" id="candidateId" />
<input type="hidden" name="guardianType" value="<?php echo $gaurdianId; ?>" id="guardianType" />

<div class="display">
	<div id="candidateInfo">
		<fieldset class="formelements">
			<div class="legend">
				<span id="legend_mailForm">Details Of The Candidate</span>
			</div>
			<dl>
				<dt style="width: 20%">
					<label for="candidateName">Candidate Name :</label>
				</dt>
				<dd style="width: 28%">
					<span id="candidateName"></span>
				</dd>
				<dt style="width: 20%">
					<label for="registrationNumber">Registration Number :</label>
				</dt>
				<dd style="width: 28%">
					<span id="registrationNumber"></span>
				</dd>
			</dl>
			<dl>
				<dt style="width: 20%">
					<label for="registeredEmail">Registered Email :</label>
				</dt>
				<dd style="width: 28%">
					<span id="registeredEmail"></span>
				</dd>
				<dt style="width: 20%">
					<label for="birthDate">Date Of Birth :</label>
				</dt>
				<dd style="width: 28%">
					<span id="birthDate"></span>
				</dd>
			</dl>
		</fieldset>
	</div>
</div>
<div class="clear"></div>
<br />
<div class="inputs">
	<form id="updateForm" class="updateForm"
		onsubmit="return valid.validateForm(this) ? processUpdateForm() : false;">
		<fieldset>
			<div class="legend">
				<span>Candidate's <?php echo $gaurdianName; ?> Personal Details</span>
			</div>
			
			<dl class="element">
				<dt style="width: 15%">

					<label for="candidateName">Candidate Name :</label>
				</dt>
				<dd style="width: *">
					<select id="salutation" name="salutation" style="width: 80px"
						tabindex="1" onchange="javascript: valid.validateInput(this);">
						<?php
						$salutationIds = $options->getOptionSearchValueIds ( '', 'SALUT', 1 );
						foreach ( $salutationIds as $salutationId )
							echo "<option value=\"" . $salutationId . "\">" . $options->getOptionIdValue ( $salutationId ) . "</option>";
						?>
						
					</select> <input type="text" name="firstName" id="firstName"
						tabindex="2" class="required"
						title="Enter Name" value="" size="30"
						onchange="javascript: valid.validateInput(this);" /> <input
						type="text" name="middleName" id="middleName" class=""
						tabindex="3" title="Enter Middle Name" value=""
						size="30" onchange="javascript: valid.validateInput(this);" /> <input
						type="text" name="lastName" id="lastName" class="required"
						tabindex="4" title="Enter  Last Name" value=""
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
				<dt style="width: 15%"><label for="MobileNo">Mobile Number :</label>	</dt>
				<dd style="width: 30%">
						<input type="text" size="30" name="mobileNo" id="mobileNo" class="required numeric" tabindex="4" onchange="javascript: valid.validateInput(this);" title="Enter the mobile number" />
						<div id="mobileNoError" class="validationError" style="display: none"></div></dd>
				<dt style="width: 15%"><label for="landlineNumber">Landline Number :</label>	</dt>
				<dd style="width: 30%">
						<input type="text" size="30" name="landlineNo" id="landlineNo" class="phone" tabindex="5" onchange="javascript: valid.validateInput(this);" title="Enter the landline number" />
						<div id="landlineNoError" class="validationError" style="display: none"></div></dd>
			</dl>
			<dl class="element">
				<dt style="width: 15%"><label for="emailId">Email ID</label>	</dt>
				<dd style="width: 30%">						
						<input type="text" size="30" name="emailId" id="emailId" class="required email" tabindex="6" onchange="javascript: valid.validateInput(this);" title="Enter the email id" />
						<div id="emailIdError" class="validationError" style="display: none"></div></dd>
				<dt style="width: 15%"><label for="occupation">Occupation</label>	</dt>
				<dd style="width: 30%">
						<input type="hidden" name="occupation_val" id="occupation_val" />
						<input type="text" size="30" name="occupation" id="occupation" class="required" tabindex="7" onblur="resetFieldValue('occupation_val');" onchange="javascript: valid.validateInput(this);" title="Enter/Select the occupation" />
						<div id="occupationError" class="validationError" style="display: none"></div></dd>
			</dl>
			<dl class="element">
				<dt style="width: 15%"><label for="remarks">Remarks / Comments :</label>	</dt>
				<dd style="width: 80%">
						<textarea name="remarks" id="remarks" tabindex="8" rows="2" cols="80"></textarea></dd>
			</dl>

		</fieldset>
		<div class="clear"></div>
		<br />

		<fieldset>
			<div class="legend"><span id="lengendAddress">Address Detail Filling Portion</span></div>
			<dl></dl>
			<div style="float: right" class="buttons">
			<?php 
				foreach ($totalGaurdianTypes as $gaurdianType){
					if($gaurdianId == $gaurdianType)
						continue;
					$addressId = $personalInfo->getUserAddressIds($candidateId, false, $gaurdianType);
					if($addressId != "")
						echo "<button type=\"button\" name=\"\" id=\"\" onclick=\"copyAddressDetails('".$gaurdianType."')\" class=\"positive\"><img src=\"".$body->getBaseServer()."images/global/icons/Copy.png\" /> Copy ".$options->getOptionIdValue($gaurdianType)." Address</button>&nbsp;&nbsp;";
				}
				$addressId = $personalInfo->getUserAddressIds($candidateId, true, false);
				if($addressId != "")
					echo "<button type=\"button\" class=\"positive\" onclick=\"copyCorrespondenceAddressDetails('".$addressId."')\"><img src=\"".$body->getBaseServer()."images/global/icons/Copy.png\" /> Copy Candidate Address</button>&nbsp;&nbsp;";
			?>		
			</div>
			<dl class="element">
				<dt style="width: 15%"><label for="streetAddress1">Flat / House No :</label>	</dt>
				<dd style="width: 80%">
						<input type="text" name="streetAddress1" size="50" id="streetAddress1" class="required" tabindex="9" onchange="javascript: valid.validateInput(this);" title="Enter the House Details" />
						<div id="streetAddress1Error" class="validationError" style="display: none"></div></dd>				
			</dl>
			<dl class="element">
				<dt style="width: 15%"><label for="streetAddress2">Street Address :</label>	</dt>
				<dd style="width: 80%">
						<input type="text" size="50" name="streetAddress2" id="streetAddress2" class="required" tabindex="10" onchange="javascript: valid.validateInput(this);" title="Enter the street address" />
						<div id="streetAddress2Error" class="validationError" style="display: none"></div></dd>				
			</dl>
			<dl class="element">
				<dt style="width: 15%"><label for="city">City :</label>	</dt>
				<dd style="width: 30%">
						<input type="hidden" name="city_val" id="city_val" />
						<input type="text" name="city" id="city" class="required" tabindex="11" size="30"onblur="resetFieldValue('city_val');" onchange="javascript: valid.validateInput(this);" title="Enter the city" />
						<div id="cityError" class="validationError" style="display: none"></div></dd>
				<dt style="width: 15%"><label for="state">State :</label>	</dt>
				<dd style="width: 30%">
						<input type="hidden" name="state_val" id="state_val" />
						<input type="text" name="state" id="state" class="required" tabindex="12" size="30" onblur="resetFieldValue('state_val');" onchange="javascript: valid.validateInput(this);" title="Enter the state" />
						<div id="stateError" class="validationError" style="display: none"></div></dd>
			</dl>			
			<dl class="element">
				<dt style="width: 15%"><label for="pincode">Pincode :</label>	</dt>
				<dd style="width: 30%">
						<input type="text" name="pincode" id="pincode" class="required numeric" tabindex="13" size="30" onchange="javascript: valid.validateInput(this);" title="Enter the pincode of the address" />
						<div id="pincodeError" class="validationError" style="display: none"></div></dd>
				<dt style="width: 15%"><label for="country">Country :</label>	</dt>
				<dd style="width: 30%">
						<input type="hidden" name="country_val" id="country_val" />
						<input type="text" name="country" id="country" class="required" tabindex="14" size="30" onblur="resetFieldValue('country_val');" onchange="javascript: valid.validateInput(this);" title="Enter the country name" />
						<div id="countryError" class="validationError" style="display: none"></div></dd>
			</dl>
		</fieldset>
		<fieldset class="action buttons">			
			<button type="button" name="insertReset" id="insertReset" class="negative reset" onclick="resetFormFields()"
				accesskey="R">
				<span class="underline">R</span>eset Form Fields
			</button>

			<button type="submit" name="submit" id="submit" accesskey="I" class="positive insert">
				<span class="underline">I</span>nsert / Update The Record
			</button>
		</fieldset>
		
	</form>
</div>
<div class="clear"></div>

