<?php
require_once 'config.php';
require_once BASE_PATH . 'include/global/class.body.php';
$body = new body ();
$body->startBody ( 'utility', 'LMENUL49', 'Employee Education Details Quick View Page', '', false );
$educationId = $_GET['educationId'];
?>
<input type="hidden" id="educationId" class="educationId" value="<?php echo $educationId; ?>"/>
<div class="display">
    <div id="displayPortion">
        <fieldset class="displayElements">
            <div class="legend">
                <span id="legendDisplayDetail">Education History Details</span>
            </div>
            <dl>
                <dt style="width: 18%;">
                    <label for="institute">Institute Name :</label>
                </dt>
                <dd style="width: 30%">
                    <span id="institute_d"></span>
                </dd>
                <dt style="width: 18%">
                    <label for="university">University / Board :</label>
                </dt>
                <dd style="width: 30%">
                    <span id="university_d"></span>
                </dd>
            </dl>
            <dl>
                <dt style="width: 18%;">
                    <label for="level">Class / Level :</label>
                </dt>
                <dd style="width: 30%">
                    <span id="level_d"></span>
                </dd>
                <dt style="width: 18%">
                    <label for="year">Passing Year :</label>
                </dt>
                <dd style="width: 30%">
                    <span id="year_d"></span>
                </dd>
            </dl>
            <dl>
                <dt style="width: 18%;">
                    <label for="score">Score :</label>
                </dt>
                <dd style="width: 30%">
                    <span id="score_d"></span>
                </dd>
                <dt style="width: 18%">
                    <label for="markingType">Scoring Type :</label>
                </dt>
                <dd style="width: 30%">
                    <span id="markType_d"></span>
                </dd>
            </dl>
            <dl>
                <dt style="width: 18%;">
                    <label for="comments">Comments :</label>
                </dt>
                <dd style="width: 80%">
                    <span id="comments_d"></span>
                </dd>
            </dl>
            <dl>
                <dt style="width: 18%;">
                    <label for="updateDate">Last Update Date : </label>
                </dt>
                <dd style="width: 30%">
                    <span id="lastUpdateDateDisplay"></span>
                </dd>
                <dt style="width: 18%">
                    <label for="updatedBy">Updated By :</label>
                </dt>
                <dd style="width: 30%">
                    <span id="lastUpdatedByDisplay"></span>
                </dd>
            </dl>
            <dl>
                <dt style="width: 18%;">
                    <label for="creationDate">Creation Date : </label>
                </dt>
                <dd style="width: 30%">
                    <span id="creationDateDisplay"></span>
                </dd>
                <dt style="width: 18%">
                    <label for="createdBy">Created By :</label>
                </dt>
                <dd style="width: 30%">
                    <span id="createdByDisplay"></span>
                </dd>
            </dl>
            <dl>
                <dt style="width: 18%;">
                    <label for="active">Active/Inactive : </label>
                </dt>
                <dd style="width: 30%">
                    <span id="activeDisplay"></span>
                </dd>

            </dl>
        </fieldset>
    </div>
</div>
