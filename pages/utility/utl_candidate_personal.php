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
$body->startBody ( 'utility', 'LMENUL30', 'Candidate Personal Information' );

$houseIds = $options->getOptionSearchValueIds('', 'HOUSE', 1);
$candidateId = $_GET['candidateId'];
?>
<input type="hidden" id="candidateId" name="candidateId" value="<?php echo $candidateId; ?>" />
<div id="content_header">
    <div id="contentHeader">New Employee Entry Form</div>
</div>
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
<div class="inputs">
<form id="insertForm" class="insertForm" action="#"
          onsubmit="return valid.validateForm(this) ? processUpdateForm() : false;">
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
                                 title="Enter Employee First Name" value="" size="40"
                                 onchange="javascript: valid.validateInput(this);" /> <input
                type="text" name="middleName" id="middleName" class=""
                tabindex="7" title="Enter Employee Middle Name" value=""
                size="40" onchange="javascript: valid.validateInput(this);" /> <input
                type="text" name="lastName" id="lastName" class="required"
                tabindex="8" title="Enter Employee Last Name" value=""
                size="40" onchange="javascript: valid.validateInput(this);" />
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
                       title="Enter The Date Of Birth in yy/mm/dd Format" value=""
                       maxlength="10" size="20"
                       onchange="javascript: valid.validateInput(this);" />
                <div id="bdayError" class="validationError" style="display: none;"></div></dd>
        </dl>
        <dl class="element">
            <dt style="width: 15%">

                <label for="religion">Religion :</label>
            </dt>
            <dd style="width: 30%">
                <input type="hidden" name="religion_val" id="religion_val" value=""
                       onchange="javascript: valid.validateInput(this);" /> <input
                type="text" tabindex="12" name="religion" id="religion" class="autocomplete"
                title="Enter The Religion" value="" size="40" />
                <div id="religionError" class="validationError"
                     style="display: none;"></div>

            </dd>
            <dt style="width: 15%">

                <label for="nationality">Nationality :</label>
            </dt>
            <dd style="width: 30%">
                <input type="hidden" name="nationality_val" id="nationality_val"
                       value="" /> <input tabindex="13" type="text" name="nationality"
                                          id="nationality" class="autocomplete" title="Enter The Nationality"
                                          value="" size="40"
                                          onchange="javascript: valid.validateInput(this);" />
                <div id="nationalityError" class="validationError"
                     style="display: none;"></div>
            </dd>
        </dl>
        <dl class="element">
            <dt style="width: 15%">

                <label for="gender">Gender :</label>
            </dt>
            <dd style="width: 30%">
                <select name="gender" id="gender" class="required" style="width: 150px"
                        tabindex="10" title="Select The Gender" onchange="javascript:valid.validateInput(this);" >
                    <option value="M">Male</option>
                    <option value="F">Female</option>
                </select>
                <div id="genderError" class="validationError"
                     style="display: none;"></div>
            </dd>
            <dt style="width: 15%"><label for="personalEmail">Personal Email :</label>	</dt>
            <dd style="width: 30%">
                <input type="text" name="personalEmail" id="personalEmail" class="" tabindex="14" size="40" onchange="javascript: valid.validateInput(this);" title="" />
                <div id="personalEmailError" class="validationError" style="display: none"></div></dd>
        </dl>
        <dl class="element">
            <dt style="width: 15%"><label for="contactNo">Contact No :</label>	</dt>
            <dd style="width: 30%">
                <input type="text" name="contactNo" id="contactNo" class="phone" tabindex="16" size="40" onchange="javascript: valid.validateInput(this);" title="" />
                <div id="contactNoError" class="validationError" style="display: none"></div></dd>
            <dt style="width: 15%"><label for="aContactNo">Alt. Contact No :</label>	</dt>
            <dd style="width: 30%">
                <input type="text" name="aContactNo" id="aContactNo" class="phone" tabindex="17" size="40" onchange="javascript: valid.validateInput(this);" title="" />
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
            <input type="text" name="streetAddress1" size="40" id="streetAddress1" class="required" tabindex="18" onchange="javascript: valid.validateInput(this);" title="Enter the House Details" value="" />
            <div id="streetAddress1Error" class="validationError" style="display: none"></div></dd>
        <dt style="width: 15%"><label for="streetAddress2">Street Address :</label>	</dt>
        <dd style="width: 30%">
            <input type="text" size="40" name="streetAddress2" id="streetAddress2" class="required" tabindex="19" onchange="javascript: valid.validateInput(this);" title="Enter the street address" value=""/>
            <div id="streetAddress2Error" class="validationError" style="display: none"></div></dd>
    </dl>

    <dl class="element">
        <dt style="width: 15%"><label for="city">City :</label>	</dt>
        <dd style="width: 30%">
            <input type="hidden" name="city_val" id="city_val" value="" />
            <input type="text" name="city" id="city" class="required autocomplete" tabindex="20" size="40" onchange="javascript: valid.validateInput(this);" title="Enter the city" value="" />
            <div id="cityError" class="validationError" style="display: none"></div></dd>
        <dt style="width: 15%"><label for="state">State :</label>	</dt>
        <dd style="width: 30%">
            <input type="hidden" name="state_val" id="state_val" value=""/>
            <input type="text" name="state" id="state" class="required autocomplete" tabindex="21" size="40" onchange="javascript: valid.validateInput(this);" title="Enter the state" value=""/>
            <div id="stateError" class="validationError" style="display: none"></div></dd>
    </dl>
    <dl class="element">
        <dt style="width: 15%"><label for="pincode">Pincode :</label>	</dt>
        <dd style="width: 30%">
            <input type="text" name="pincode" id="pincode" class="required numeric" tabindex="22" size="40" onchange="javascript: valid.validateInput(this);" title="Enter the pincode of the address" value=""/>
            <div id="pincodeError" class="validationError" style="display: none"></div></dd>
        <dt style="width: 15%"><label for="country">Country :</label>	</dt>
        <dd style="width: 30%">
            <input type="hidden" name="country_val" id="country_val" value=""/>
            <input type="text" name="country" id="country" class="required autocomplete" tabindex="23" size="40" onchange="javascript: valid.validateInput(this);" title="Enter the country name" value=""/>
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
                                                             name="recordShelve1" id="recordShelve1" class="autocomplete"
                                                             title="Enter The Record Shelve1" value="" size="40"
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
                                                             name="recordShelve2" id="recordShelve2" class="autocomplete"
                                                             title="Enter The Record Shelve2" value="" size="40"
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
                                                             name="recordShelve3" id="recordShelve3" class="autocomplete" tabindex="26"
                                                             title="Enter The Record Shelve3" value="" size="40"
                                                             onchange="javascript: valid.validateInput(this);" />
            <div id="recordShelve3Error" class="validationError"
                 style="display: none;"></div>
        </dd>
    </dl>
    <dt style="width: 15%">

            <label for="houseDetails">House Details :</label>
        </dt>
        <dd style="width: 30%"><select name="houseDetails" id="houseDetails" class="required" tabindex="26"
                                                             onchange="javascript: valid.validateInput(this);">
                                	<?php 
                                		foreach($houseIds as $houseId){
                                			echo "<option value=\"$houseId\">".$options->getOptionIdValue($houseId)."</option>";
                                		}
                                	?>                             	
                                                             
                               </select>                              
            <div id="houseDetails" class="validationError"
                 style="display: none;"></div>
        </dd>
    </dl>
    
</fieldset>

<fieldset class="action buttons">
    <div id="up2" style="float: left; margin-right: 20px"></div>
    <button type="button" name="insertReset" id="insertReset" onclick="resetFormFields()"
            accesskey="R" class="negative reset">
        <span class="underline">R</span>eset Form Fields
    </button>

    <button type="submit" name="submit" id="submit" accesskey="I" class="positive update">
        <span class="underline">U</span>pdate Record
    </button>
</fieldset>
</form>
</div>
<?php
$body->endBody ( 'utility', 'MENUL38' );
?>
