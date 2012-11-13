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
require_once BASE_PATH . 'include/utility/class.address.php';

$body = new body ();
$options = new options ();
$personalInfo = new personalInfo ();
$address = new address ();

$body->startBody ( 'utility', 'LMENUL35', 'Candidate Profile Information' );

$candidateId = $_GET ['candidateId'];
$details = $personalInfo->getUserIdDetails ( $candidateId );
?>
<div id="content_header">
    <div id="pageButton" class="buttons">
        <button type="button" class="regular" onclick="getUserPhotograph()"><img src="<?php echo $body->getBaseServer(); ?>images/global/icons/Boss.png" />Candidate Photograph</button>
        <button type="button" class="regular" onclick="getRegistrationData()"><img src="<?php echo $body->getBaseServer(); ?>images/global/icons/Boss.png" />Registration Data</button>
        <button type="button" class="positive" onclick="getGuardianData()"><img src="<?php echo $body->getBaseServer(); ?>images/global/icons/Female.png" />Guardian Data</button>
        <button type="button" class="negative" onclick="getEducationData()"><img src="<?php echo $body->getBaseServer(); ?>images/global/icons/book.png" />Education History</button>
    </div>
    <div id="contentHeader">Candidate Profile View</div>
</div>
<input type="hidden" name="candidateId"	value="<?php echo $candidateId; ?>" id="candidateId" />
<div class="clear"></div>
<div id="personalInfo">
	<div class="display">
		<fieldset class="displayElements">
            <div class="legend">
                <span>Candidate Personal Information Details</span>
            </div>
            <dl></dl>
            <dl>
            <dt style="width: 15%;">
                <label for="name">Candidate Name :</label>
            </dt>
            <dd style="width: 80%">
                <span id="candidateName"><?php echo $personalInfo->getUserName(); ?></span>
            </dd>
        </dl>
            <dl>
                <dt style="width: 15%;">
                    <label for="dob">Date Of Birth :</label>
                </dt>
                <dd style="width: 30%">
                    <span id="dob"><?php echo $personalInfo->getDisplayDate($details['dob']); ?></span>
                </dd>
                <dt style="width: 15%">
                    <label for="gender">Gender :</label>
                </dt>
                <dd style="width: 30%">
                    <span id="gender"><?php echo strtoupper($details['gender']) == 'F' ? 'Female' : 'Male'; ?></span>
                </dd>
            </dl>
            <dl>
                <dt style="width: 15%;">
                    <label for="religion">Religion :</label>
                </dt>
                <dd style="width: 30%">
                    <span id="religion"><?php echo $options->getOptionIdValue($details['religion']); ?></span>
                </dd>
                <dt style="width: 15%">
                    <label for="nationality">Nationality :</label>
                </dt>
                <dd style="width: 30%">
                    <span id="nationality"><?php echo $options->getOptionIdValue($details['nationality']); ?></span>
                </dd>
            </dl>
            <dl>
                <dt style="width: 15%;">
                    <label for="pEmail">Personal Email :</label>
                </dt>
                <dd style="width: 30%">
                    <span id="pEmail"><?php echo $details['personal_email_id']; ?></span>

                </dd>
                <dt style="width: 15%">
                    <label for="oEmail">Official Email :</label>
                </dt>
                <dd style="width: 30%">
                    <span id="oEmail"><?php echo $details['official_email_id']; ?></span>
                </dd>
            </dl>
            <dl>
                <dt style="width: 15%;">
                    <label for="mobileNo">Contact No :</label>
                </dt>
                <dd style="width: 30%">
                    <span id="mobileNo"><?php echo $details['mobile_no']; ?></span>
                </dd>
                <dt style="width: 15%">
                    <label for="contactNo">Alt. Contact No :</label>
                </dt>
                <dd style="width: 30%">
                    <span id="contactNo"><?php echo $details['landline_no']; ?></span>
                </dd>
            </dl>

            <dl>
                <dt style="width: 15%;">
                    <label for="address">Address :</label>
                </dt>
                <dd style="width: 80%">
                    <span id="address"><?php echo $address->getAddressDisplay($details['address_id']); ?></span>
                </dd>
            </dl>
		</fieldset>
	</div>
</div>
<div class="clear"></div>
<div id="registration" style="display: none">
	<div class="display">
		<fieldset class="displayElements">
            <div class="legend">
                <span>Candidate Registration Information Details</span>
            </div>
            <dl></dl>
            <dl>
                <dt style="width: 15%;">
                    <label for="registrationId">Registration No :</label>
                </dt>
                <dd style="width: 30%">
                    <span id="registrationId"></span>
                </dd>
                <dt style="width: 15%">
                    <label for="entranceId">Entrance Id :</label>
                </dt>
                <dd style="width: 30%">
                    <span id="entranceId"></span>
                </dd>
            </dl>
            <dl>
                <dt style="width: 15%;">
                    <label for="admittedClass">Admitted Class :</label>
                </dt>
                <dd style="width: 30%">
                    <span id="admittedClass"></span>
                </dd>
                <dt style="width: 15%">
                    <label for="registrationDate">Registration Date :</label>
                </dt>
                <dd style="width: 30%">
                    <span id="registrationDate"></span>
                </dd>
            </dl>
            <dl>
                <dt style="width: 15%;">
                    <label for="house">House :</label>
                </dt>
                <dd style="width: 30%">
                    <span id="house"></span>
                </dd>
                <dt style="width: 15%">
                    <label for="record1">Record Shelve :</label>
                </dt>
                <dd style="width: 30%">
                    <span id="record1"></span>
                </dd>
            </dl>
            <dl>
                <dt style="width: 15%;">
                    <label for="record2">Record Shelve2 :</label>
                </dt>
                <dd style="width: 30%">
                    <span id="record2"></span>
                </dd>
                <dt style="width: 15%">
                    <label for="record3">Record Shelve3 :</label>
                </dt>
                <dd style="width: 30%">
                    <span id="record3"></span>
                </dd>
            </dl>
		</fieldset>
        <fieldset class="action buttons">
            <button accesskey="H" type="button" name="submit" tabindex="29" class="negative hide"
                    id="submit" onclick="hideRegistrationData()">
                <span class="underline">H</span>ide Registration Data
            </button>
            <button accesskey="U" type="submit" name="editRecordButton" class="positive edit"
                    tabindex="28" id="editRecordButton" onclick="editRegistrationData()">
            <span class="underline">U</span>pdate Record
            </button>
        </fieldset>
	</div>
</div>
<div class="clear"></div>
<div id="gaurdian" style="display: none">
	<div class="display">
        <fieldset class="formelements">
            <div class="legend">
                <span  style="padding-right: 200px;">Candidate Guardian Record Details</span>
                <select name="gaurdianType"
                                               id="gaurdianType" onchange="getGuardianData()"
                                               style="width: 150px;">
                <?php
                $gaurdianIds = $options->getOptionSearchValueIds ( '', 'GAURC', 1 );
                foreach ( $gaurdianIds as $gaurdianId ) {
                    echo "<option value=\"" . $gaurdianId . "\">" . $options->getOptionIdValue ( $gaurdianId ) . "</option>";
                }
                ?>
            </select>
            </div>
            <div id="guardianInsideData">
                <dl></dl>
                <dl>
                    <dt style="width: 15%;">
                        <label for="gaurdianName">Gaurdian Name :</label>
                    </dt>
                    <dd style="width: 30%">
                        <span id="gaurdianName"></span>
                    </dd>
                    <dt style="width: 15%">
                        <label for="gaurdianTypeVal">Gaurdian Type :</label>
                    </dt>
                    <dd style="width: 30%">
                        <span id="gaurdianTypeVal"></span>
                    </dd>
                </dl>
                <dl>
                    <dt style="width: 15%;">
                        <label for="emailId">Email Id :</label>
                    </dt>
                    <dd style="width: 30%">
                        <span id="emailId"></span>
                    </dd>
                    <dt style="width: 15%">
                        <label for="occupation">Occupation :</label>
                    </dt>
                    <dd style="width: 30%">
                        <span id="occupation"></span>
                    </dd>
                </dl>
                <dl>
                    <dt style="width: 15%;">
                        <label for="gMobileNo">Contact No :</label>
                    </dt>
                    <dd style="width: 30%">
                        <span id="gMobileNo"></span>
                    </dd>
                    <dt style="width: 15%">
                        <label for="gContactNo">Alt Contact No :</label>
                    </dt>
                    <dd style="width: 30%">
                        <span id="gContactNo"></span>
                    </dd>
                </dl>
                <dl>
                    <dt style="width: 15%;">
                        <label for="gAddress">Address :</label>
                    </dt>
                    <dd style="width: 80%">
                        <span id="gAddress"></span>
                    </dd>
                </dl>
            </div>
        </fieldset>
        <fieldset class="action buttons">
            <button accesskey="H" type="button" name="submit" tabindex="29" class="negative hide"
                    id="submit" onclick="hideGuardianData()">
                <span class="underline">H</span>ide Guardian Data
            </button>
            <button accesskey="U" type="submit" name="editRecordButton" class="positive edit"
                    tabindex="28" id="editRecordButton" onclick="editGuardianData()" >
            <span class="underline">U</span>pdate Record
            </button>
        </fieldset>
	</div>
</div>
<div class="clear"></div>
<div id="educationHistory" style="display: none">
	<div class="display">
		<fieldset class="tableElements">
            <div class="legend">
                <span>Candidate Education Background Details</span>
            </div>
            <table  class="display"
                   id="groupHistory">
                <thead>
                <tr>
                    <th>Level</th>
                    <th>Year</th>
                    <th>Institute</th>
                    <th>Score</th>
                    <th>Score Type</th>
                </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
		</fieldset>
        <fieldset class="action buttons">
            <button accesskey="H" type="button" name="submit" tabindex="29" class="negative hide"
                    id="submit" onclick="hideEducationHistory()">
                <span class="underline">H</span>ide Education Portion
            </button>
            <button accesskey="U" type="submit" name="editRecordButton" class="positive edit"
                    tabindex="28" id="editRecordButton" onclick="editEducationHistory()" >
            <span class="underline">U</span>pdate Record
            </button>
        </fieldset>
	</div>
</div>

<div style='display:none'>
    <div id='inlineDisplayMenus' style='padding:10px;'  class="buttons">
        <fieldset>
            <div class="legend">
                <span>Showing Extended Menu For The page</span>
            </div>
            <dl></dl>
            <dl>
                <dd>
                    <button class="negative" onclick="loadPageIntoDisplay('<?php echo $body->getBaseServer()."pages/utility/utl_candidate_personal.php?candidateId=".$candidateId;?>')">
                        <img src="<?php echo $body->getBaseServer(); ?>images/global/icons/lock_big.png" />Edit Candidate Personal Details  <br />
                        <label>Edit Personal, Address & Record Keeping Details </label></button>
                </dd>
                <dd>
                    <button class="negative" onclick="loadPageIntoDisplay('<?php echo $body->getBaseServer()."pages/utility/utl_candidate_education.php?candidateId=".$candidateId;?>')">
                        <img src="<?php echo $body->getBaseServer(); ?>images/global/icons/lock_big.png" />Edit Candidate Education History  <br />
                        <label>Edit Candidate Previous Education History Details </label></button>
                </dd>
            </dl>

        </fieldset>

    </div>
</div>