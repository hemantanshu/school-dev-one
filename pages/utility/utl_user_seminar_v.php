<?php
require_once 'config.php';
require_once BASE_PATH . 'include/global/class.body.php';
$body = new body ();
$body->startBody ( 'utility', 'LMENUL47', 'Employee Seminar Details Quick View Page', '', false );
$seminarId = $_GET['seminarId'];
?>
<input type="hidden" id="seminarId" class="seminarId" value="<?php echo $seminarId; ?>"/>
<div class="display">
    <div id="displayPortion">
        <fieldset class="displayElements">
            <div class="legend">
                <span id="legendDisplayDetail">Seminar Details</span>
            </div>
            <dl>
                <dt style="width: 15%;">
                    <label for="seminarTitle">Seminar Title :</label>
                </dt>
                <dd style="width: 80%">
                    <span id="seminarTitle_d"></span>
                </dd>
            </dl>
            <dl>
                <dt style="width: 15%;">
                    <label for="organizedBy">Organized By :</label>
                </dt>
                <dd style="width: 80%">
                    <span id="organizedBy_d"></span>
                </dd>
            </dl>
            <dl>
                <dt style="width: 15%;">
                    <label for="startDate">Date :</label>
                </dt>
                <dd style="width: 30%">
                    <span id="startDate_d"></span>
                </dd>
                <dt style="width: 15%">
                    <label for="duration">Duration :</label>
                </dt>
                <dd style="width: 30%">
                    <span id="duration_d"></span>
                </dd>
            </dl>
            <dl>
                <dt style="width: 15%;">
                    <label for="comments">Comments :</label>
                </dt>
                <dd style="width: 80%">
                    <span id="comments_d"></span>
                </dd>
            </dl>
            <dl>
                <dt style="width: 15%;">
                    <label for="updateDate">Last Update Date : </label>
                </dt>
                <dd style="width: 30%">
                    <span id="lastUpdateDateDisplay"></span>
                </dd>
                <dt style="width: 15%">
                    <label for="updatedBy">Updated By :</label>
                </dt>
                <dd style="width: 30%">
                    <span id="lastUpdatedByDisplay"></span>
                </dd>
            </dl>
            <dl>
                <dt style="width: 15%;">
                    <label for="creationDate">Creation Date : </label>
                </dt>
                <dd style="width: 30%">
                    <span id="creationDateDisplay"></span>
                </dd>
                <dt style="width: 15%">
                    <label for="createdBy">Created By :</label>
                </dt>
                <dd style="width: 30%">
                    <span id="createdByDisplay"></span>
                </dd>
            </dl>
            <dl>
                <dt style="width: 15%;">
                    <label for="active">Active/Inactive : </label>
                </dt>
                <dd style="width: 30%">
                    <span id="activeDisplay"></span>
                </dd>
            </dl>
        </fieldset>
    </div>
</div>

