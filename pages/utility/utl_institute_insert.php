<?php
require_once 'config.php';
require_once BASE_PATH . 'include/global/class.body.php';


$body = new body ();
$body->startBody ( 'utility', 'LMENUL62', 'Institute Entry Page', '', false );
?>
<div class="inputs">
    <form id="insertForm" class="insertForm" onsubmit="return valid.validateForm(this) ? processInsertForm() : false;">
        <fieldset class="formelements">
            <div class="legend">
                <span>New Institute / College Record Entry</span>
            </div>
            <dl class="element">
                <dt style="width: 15%"><label for="collegeName">Institute Name :</label>	</dt>
                <dd style="width: 80%">
                    <input type="text" size="80" name="collegeName" id="collegeName" class="required" tabindex="101" onchange="javascript: valid.validateInput(this);" title="Insert The College Name" />
                    <div id="collegeNameError" class="validationError" style="display: none"></div></dd>
            </dl>
            <dl class="element">
                <dt style="width: 15%"><label for="university">University / Board :</label>	</dt>
                <dd style="width: 30%">
                    <input type="hidden" name="university_val" id="university_val" value="" />
                    <input type="text" name="university" id="university" class="required autocomplete" tabindex="102" size="30" onchange="javascript: valid.validateInput(this);" title="Select the univerysity name" />
                    <div id="universityError" class="validationError" style="display: none"></div></dd>
                <dt style="width: 15%"><label for="contactno">Contact No :</label>	</dt>
                <dd style="width: 30%">
                    <input type="text" name="contactno" id="contactno" class="required numeric" tabindex="103" size="30" onchange="javascript: valid.validateInput(this);" title="Insert the contact no" />
                    <div id="contactnoError" class="validationError" style="display: none"></div></dd>
            </dl>
        </fieldset>
        <fieldset class="formelements">
            <div class="legend">
                <span>Address Details of the Institute</span>
            </div>
            <dl class="element">
                <dt style="width: 15%"><label for="streetAddress1">Flat / House No :</label>	</dt>
                <dd style="width: 80%">
                    <input type="text" name="streetAddress1" size="50" id="streetAddress1" class="required" tabindex="104" onchange="javascript: valid.validateInput(this);" title="Enter the House Details" />
                    <div id="streetAddress1Error" class="validationError" style="display: none"></div></dd>
            </dl>
            <dl class="element">
                <dt style="width: 15%"><label for="streetAddress2">Street Address :</label>	</dt>
                <dd style="width: 80%">
                    <input type="text" size="50" name="streetAddress2" id="streetAddress2" class="required" tabindex="105" onchange="javascript: valid.validateInput(this);" title="Enter the street address" />
                    <div id="streetAddress2Error" class="validationError" style="display: none"></div></dd>
            </dl>
            <dl class="element">
                <dt style="width: 15%"><label for="city">City :</label>	</dt>
                <dd style="width: 30%">
                    <input type="hidden" name="city_val" id="city_val" />
                    <input type="text" name="city" id="city" class="required autocomplete" tabindex="106" size="30" onchange="javascript: valid.validateInput(this);" title="Enter the city" />
                    <div id="cityError" class="validationError" style="display: none"></div></dd>
                <dt style="width: 15%"><label for="state">State :</label>	</dt>
                <dd style="width: 30%">
                    <input type="hidden" name="state_val" id="state_val" />
                    <input type="text" name="state" id="state" class="required autocomplete" tabindex="107" size="30" onchange="javascript: valid.validateInput(this);" title="Enter the state" />
                    <div id="stateError" class="validationError" style="display: none"></div></dd>
            </dl>
            <dl class="element">
                <dt style="width: 15%"><label for="pincode">Pincode :</label>	</dt>
                <dd style="width: 30%">
                    <input type="text" name="pincode" id="pincode" class="required numeric" tabindex="108" size="30" onchange="javascript: valid.validateInput(this);" title="Enter the pincode of the address" />
                    <div id="pincodeError" class="validationError" style="display: none"></div></dd>
                <dt style="width: 15%"><label for="country">Country :</label>	</dt>
                <dd style="width: 30%">
                    <input type="hidden" name="country_val" id="country_val" />
                    <input type="text" name="country" id="country" class="required autocomplete" tabindex="109" size="30" onchange="javascript: valid.validateInput(this);" title="Enter the country name" />
                    <div id="countryError" class="validationError" style="display: none"></div></dd>
            </dl>
        </fieldset>
        <fieldset class="action buttons">
            <button type="reset" name="insertReset" class="negative reset" id="insertReset"
                    accesskey="R"><img src="<?php echo $body->getBaseServer();?>images/global/icons/Redo.png" />
                <span class="underline">R</span>eset Form Fields
            </button>

            <button type="submit" name="submit" id="submit" accesskey="P" class="positive insert">
                <img src="<?php echo $body->getBaseServer();?>images/global/icons/Create.png" />
                Insert New Record (<u>P</u>)
            </button>
        </fieldset>
    </form>
</div>
<div class="clear"></div>
