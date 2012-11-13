<?php
require_once 'config.php';
require_once BASE_PATH . 'include/global/class.body.php';
$body = new body ();
$body->startBody ( 'utility', 'LMENUL48', 'Employee Employment Details Quick View Page', '', false );
$employmentId = $_GET['employmentId'];
?>
<input type="hidden" id="employmentId" class="employmentId" value="<?php echo $employmentId; ?>"/>
<div class="display">
    <div id="displayPortion">
        <fieldset class="displayElements">
            <div class="legend">
                <span id="legendDisplayDetail">Seminar Details</span>
            </div>
            <dl>
                <dt style="width: 18%;">
                    <label for="organizationId">Organization Name :</label>
                </dt>
                <dd style="width: 30%">
                    <span id="organization_d"></span>
                </dd>
                <dt style="width: 18%">
                    <label for="position">Position Held :</label>
                </dt>
                <dd style="width: 30%">
                    <span id="position_d"></span>
                </dd>
            </dl>
            <dl>
                <dt style="width: 18%;">
                    <label for="startDate">Joining Date :</label>
                </dt>
                <dd style="width: 30%">
                    <span id="startDate_d"></span>
                </dd>
                <dt style="width: 18%">
                    <label for="endDate">Leaving Date :</label>
                </dt>
                <dd style="width: 30%">
                    <span id="endDate_d"></span>
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
