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

$body->startBody ( 'utility', 'LMENUL36', 'Employee Profile Information' );
$userId = $_GET ['userId'];
$details = $personalInfo->getUserIdDetails ( $userId );
?>
<div id="content_header">
    <div id="pageButton" class="buttons">
        <button type="button" class="regular" onclick="getPhotographDetails()"><img src="<?php echo $body->getBaseServer(); ?>images/global/icons/Boss.png" />Photograph</button>
        <button type="button" class="regular" onclick="getRegistrationDetails()"><img src="<?php echo $body->getBaseServer(); ?>images/global/icons/Boss.png" />Registration</button>
        <button type="button" class="positive" onclick="getGuardianDetails()"><img src="<?php echo $body->getBaseServer(); ?>images/global/icons/Female.png" />Guardian</button>
        <button type="button" class="regular" onclick="getDesignationDetails()"><img src="<?php echo $body->getBaseServer(); ?>images/global/icons/Briefcase.png" />Designation</button>
        <button type="button" class="positive" onclick="getEducationDetails()"><img src="<?php echo $body->getBaseServer(); ?>images/global/icons/Book.png" />Education</button>
        <button type="button" class="negative" onclick="getSeminarDetails()"><img src="<?php echo $body->getBaseServer(); ?>images/global/icons/computer.png" />Seminar</button>
        <button type="button" class="" onclick="getEmploymentDetails()"><img src="<?php echo $body->getBaseServer(); ?>images/global/icons/Accounting.png" />Employment</button>
    </div>
    <div id="contentHeader">Employee</div>
</div>
<input type="hidden" name="userId"
	value="<?php echo $userId; ?>" id="userId" />
<div class="clear"></div>


<div id="personalInfo">
	<div class="display">
        <fieldset class="displayElements">
            <div class="legend">
                <span>Candidate Personal Information Details</span>
            </div>
            <dl>
                <dt style="width: 15%;">
                    <label for="name">Employee Name :</label>
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
                    <label for="maritalStatus">Marital Status :</label>
                </dt>
                <dd style="width: 30%">
                    <span id="maritalStatus"><?php echo $options->getOptionIdValue($details['marital_status_id']); ?></span>
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
<div id="registration">
	<div class="display">
		<fieldset class="displayElements">
            <div class="legend">
                <span>Employee Registration Details</span>
            </div>
            <dl>
                <dt style="width: 15%;">
                    <label for="employeeCode">Employee Code :</label>
                </dt>
                <dd style="width: 30%">
                    <span id="employeeCode"></span>
                </dd>
                <dt style="width: 15%">
                    <label for="applicationId">Application ID :</label>
                </dt>
                <dd style="width: 30%">
                    <span id="applicationId"></span>
                </dd>
            </dl>
            <dl>
                <dt style="width: 15%;">
                    <label for="joiningDate">Joining Date :</label>
                </dt>
                <dd style="width: 30%">
                    <span id="joiningDate"></span>
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
            <dl>
                <dt style="width: 15%;">
                    <label for="department">Department Name :</label>
                </dt>
                <dd style="width: 30%">
                    <span id="department"></span>
                </dd>
                <dt style="width: 15%">
                    <label for="employeeType">Employee Type :</label>
                </dt>
                <dd style="width: 30%">
                    <span id="employeeType"></span>
                </dd>
            </dl>
		</fieldset>
        <fieldset class="action buttons">
            <button accesskey="H" type="button" name="submit" tabindex="29" class="negative hide"
                    id="submit" onclick="hideRegistrationDetails()">
                Hide Registration Details
            </button>
            <button accesskey="U" type="submit" name="editRecordButton" class="positive edit"
                    tabindex="28" id="editRecordButton" onclick="editRegistrationDetails()">
                <span class="underline">U</span>pdate Record
            </button>
        </fieldset>
	</div>
</div>
<div class="clear"></div>

<div id="guardian" style="display: none">
	<div class="display">
		<fieldset class="displayElements">
            <div class="legend">
                <span  style="padding-right: 200px;">Employee Guardian Record Details</span>
                <select name="gaurdianType"
                        id="gaurdianType" onchange="getGuardianDetails()"
                        style="width: 150px;">
                    <?php
                    $gaurdianIds = $options->getOptionSearchValueIds ( '', 'GAURS', 1 );
                    foreach ( $gaurdianIds as $gaurdianId ) {
                        echo "<option value=\"" . $gaurdianId . "\">" . $options->getOptionIdValue ( $gaurdianId ) . "</option>";
                    }
                    ?>
                </select>
            </div>
            <div id="guardianInsideData">
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
                    id="submit" onclick="hideGuardianDetails()">
                Hide Guardian Details
            </button>
            <button accesskey="U" type="submit" name="editRecordButton" class="positive edit"
                    tabindex="28" id="editRecordButton" onclick="editGuardianDetails()">
                <span class="underline">U</span>pdate Record
            </button>
        </fieldset>
	</div>
</div>
<div class="clear"></div>
<div id="designation" style="display: none">
	<div id="display" class="buttons">
		<fieldset class="tableElements">
            <div class="legend">
                <span>Employee Designation Details</span>
            </div>
            <table  class="display"
                   id="designationTable">
                <thead>
                    <tr>
                        <th>Rank</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                        <th style="width: 150px;">View Details</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
		</fieldset>
        <fieldset class="action buttons">
            <button accesskey="H" type="button" name="submit" tabindex="29" class="negative hide"
                    id="submit" onclick="hideDesignationDetails()">
                Hide Designation Details
            </button>
            <button accesskey="U" type="submit" name="editRecordButton" class="positive edit"
                    tabindex="28" id="editRecordButton" onclick="editDesignationDetails()">
                <span class="underline">U</span>pdate Record
            </button>
        </fieldset>
	</div>
</div>
<div class="clear"></div>

<div id="education" style="display: none">
	<div id="display" class="buttons">
		<fieldset class="tableElements">
            <div class="legend">
                <span>Employee Education History Details</span>
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
                    <th style="width: 150px;">View Details</th>
                </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
		</fieldset>
        <fieldset class="action buttons">
            <button accesskey="H" type="button" name="submit" tabindex="29" class="negative hide"
                    id="submit" onclick="hideEducationDetails()">
                Hide Education Details
            </button>
            <button accesskey="U" type="submit" name="editRecordButton" class="positive edit"
                    tabindex="28" id="editRecordButton" onclick="editEducationDetails()">
                <span class="underline">U</span>pdate Record
            </button>
        </fieldset>
	</div>
</div>
<div class="clear"></div>
<div id="seminar" style="display: none">
	<div id="display"  class="buttons">
		<fieldset class="tableElements">
            <div class="legend">
                <span>Employee Seminar Details</span>
            </div>
            <table  class="display"
                   id="seminarTable">
                <thead>
                <tr>
                    <th>Seminar Title</th>
                    <th>Organized By</th>
                    <th>Date</th>
                    <th>Duration</th>
                    <th style="width: 150px">View</th>
                </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
		</fieldset>
        <fieldset class="action buttons">
            <button accesskey="H" type="button" name="submit" tabindex="29" class="negative hide"
                    id="submit" onclick="hideSeminarDetails()">
                Hide Seminar Details
            </button>
            <button accesskey="U" type="submit" name="editRecordButton" class="positive edit"
                    tabindex="28" id="editRecordButton" onclick="editSeminarDetails()">
                <span class="underline">U</span>pdate Record
            </button>
        </fieldset>
	</div>
</div>
<div class="clear"></div>
<div id="employment" style="display: none">
	<div id="display" class="buttons">
		<fieldset class="tableElements">
            <div class="legend">
                <span>Employee Employment History</span>
            </div>
            <table  class="display"
                   id="employmentTable">
                <thead>
                <tr>
                    <th>Joining Date</th>
                    <th>Leaving Date</th>
                    <th>Institute</th>
                    <th>Position</th>
                    <th style="width: 150px;">View</th>
                </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
		</fieldset>
        <fieldset class="action buttons">
            <button accesskey="H" type="button" name="submit" tabindex="29" class="negative hide"
                    id="submit" onclick="hideEmploymentDetails()">
                Hide Employment History
            </button>
            <button accesskey="U" type="submit" name="editRecordButton" class="positive edit"
                    tabindex="28" id="editRecordButton" onclick="editEmploymentDetails()">
                <span class="underline">U</span>pdate Record
            </button>
        </fieldset>
	</div>
</div>
<div class="clear"></div>
<div id="extraMenuListingPage" style="display:none">
	<?php 
		$baseServer = $body->getBaseServer();		
	?>
	<li><a href="#" class="bookmarkedMenuListing" target="_parent" onclick="handleBookmarkMenuClick('http://localhost/school/pages/utility/utl_user_personal.php?userId=USERS1', '_parent')"><img src="<?php echo $baseServer; ?>images/global/b_usredit.png" alt="" />Edit Personal Information</a></li>
	<li><a href="#" class="bookmarkedMenuListing" onclick="loadPageIntoDisplay('<?php echo $baseServer."pages/utility/utl_user_education.php?userId=".$userId; ?>')"><img src="<?php echo $baseServer; ?>images/global/b_usredit.png" alt="" />Edit Education History</a></li>
	<li><a href="#" class="bookmarkedMenuListing" onclick="loadPageIntoDisplay('<?php echo $baseServer."pages/utility/utl_user_seminar.php?userId=".$userId; ?>')"><img src="<?php echo $baseServer; ?>images/global/b_usredit.png" alt="" />Edit Seminar Details</a></li>
	<li><a href="#" class="bookmarkedMenuListing" onclick="loadPageIntoDisplay('<?php echo $baseServer."pages/utility/utl_user_employment.php?userId=".$userId; ?>')"><img src="<?php echo $baseServer; ?>images/global/b_usredit.png" alt="" />Edit Employment History</a></li>
	<li><a href="#" class="bookmarkedMenuListing" onclick="loadPageIntoDisplay('<?php echo $baseServer."pages/utility/utl_user_password.php?userId=".$userId; ?>')"><img src="<?php echo $baseServer; ?>images/global/b_usredit.png" alt="" />Reset User password</a></li>
</div>
