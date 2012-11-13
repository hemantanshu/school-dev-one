<?php
/**
 *
 * @author shubhamkesarwani@supportgurukul.com(html)
 * @category Utility
 * @license Support-Gurukul
 * @version 1.0.0
 */
require_once 'config.php';
require_once BASE_PATH . 'include/global/class.body.php';

$body = new body ();
$body->startBody ( 'utility', 'LMENUL60', 'Candidate Subject Assignment' );

$mappingId = $_GET ['mappingId'];
$candidateId = $_GET ['candidateId'];
$details = $body->getTableIdDetails($mappingId);
if ($details['class_id'] == "")
    exit(0);


?>
<div id="content_header">
    <div id="pageButton" class="buttons">
        <button type="button" class="regular toggle" onclick="showHideDatatable()"><span class="underline">T</span>oggle Tabulated Data</button>
    </div>
    <div id="contentHeader">Subject Record Details </div>
</div>
<input type="hidden" name="mapping_global" id="mapping_global" value="<?php echo $mappingId; ?>" />
<input type="hidden" name="candidate_global" id="candidate_global" value="<?php echo $candidateId; ?>" />
<div class="clear"></div>
<div class="display">
    <div id="sessionRecord" style="display: none">
        <div class="legend">
            <span id="legend_editForm">Candidate Class Details</span>
        </div>
        <dl>
            <dt style="width: 15%;">
                <label for="candidateName">Candidate Name :</label>
            </dt>
            <dd style="width: 30%">
                <span id="candidateName"></span>
            </dd>
            <dt style="width: 15%">
                <label for="registrationNumber">Reg. Number :</label>
            </dt>
            <dd style="width: 30%">
                <span id="registrationNumber"></span>
            </dd>
        </dl>
        <dl>
            <dt style="width: 15%;">
                <label for="class">Class :</label>
            </dt>
            <dd style="width: 30%">
                <span id="class"></span>
            </dd>
            <dt style="width: 15%">
                <label for="section">Section :</label>
            </dt>
            <dd style="width: 30%">
                <span id="section"></span>
            </dd>
        </dl>
        <dl>
            <dt style="width: 15%;">
                <label for="session">Session :</label>
            </dt>
            <dd style="width: 80%">
                <span id="session"></span>
            </dd>
        </dl>
        </div>
    </div>
</div>
<div class="clear"></div>
<div class="inputs">
    <form id="updateForm" class="updateForm" onsubmit="return valid.validateForm(this) ? processUpdateForm() : false;" style="display: none">
        <fieldset class="formelements">
            <div class="legend">
                <span id="legend_editForm">Update Subject Record Details</span>
            </div>
            <div class="display">
                <dl>
                    <dt style="width: 15%;">
                        <label for="subject_u">Subject :</label>
                    </dt>
                    <dd style="width: 30%">
                        <span id="subject_u"></span>
                    </dd>
                    <dt style="width: 15%">
                        <label for="type_u">Type :</label>
                    </dt>
                    <dd style="width: 30%">
                        <span id="type_u"></span>
                    </dd>
                </dl>
            </div>
            <dl class="element">
                <dt style="width: 15%"><label for="subject">Subject Name :</label>	</dt>
                <dd style="width: 80%">
                    <select name="subject" id="subject" style="width: 300px" class="required">

                    </select>
                    <div id="subjectError" class="validationError" style="display: none"></div></dd>

            </dl>
        </fieldset>
        <fieldset class="action buttons">
            <input type="hidden" name="valueId_u" id="valueId_u" value="" /> <input
            type="hidden" name="rowPosition_u" id="rowPosition_u" value="" />
            <input
                type="hidden" name="associationId_u" id="associationId_u" value="" />
            <button type="button" class="regular hide"
                    onclick="hideUpdateForm()">Hide Update Portion</button>
            <button type="submit" class="positive update">Update Record</button>
        </fieldset>

    </form>
</div>

<div class="clear"></div>
<div class="display">
    <div id="displayRecord" style="display: none">
        <fieldset class="displayElements">
            <div class="legend">
                <span id="legendDisplayDetail">Showing Details Of The Subject </span>
            </div>
            <dl>
                <dt style="width: 15%;">
                    <label for="subjectType_d">Subject Type :</label>
                </dt>
                <dd style="width: 30%">
                    <span id="subjectType_d"></span>
                </dd>
                <dt style="width: 15%">
                    <label for="type">Type :</label>
                </dt>
                <dd style="width: 30%">
                    <span id="type"></span>
                </dd>
            </dl>
            <dl>
                <dt style="width: 15%;">
                    <label for="subjectName">Subject Name :</label>
                </dt>
                <dd style="width: 30%">
                    <span id="subjectName"></span>
                </dd>
                <dt style="width: 15%">
                    <label for="subjectCode">Subject Code :</label>
                </dt>
                <dd style="width: 30%">
                    <span id="subjectCode"></span>
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
        <fieldset class="action buttons">
            <input type="hidden" name="valueId_d" id="valueId_d" value="" /> <input
            type="hidden" name="rowPosition_d" id="rowPosition_d" value="" />
            <button type="button" name="submit" class="regular hide" id="submit"
                    onclick="hideDisplayPortion()">Hide Display Details Portion</button>
            <button type="button" class="negative edit" id="editRecordButton"
                    class="editRecordButton">Edit Record</button>
        </fieldset>
    </div>
</div>
<div class="clear"></div>


<div class="datatable buttons" id="displayDatatable" style="display: none">
    <fieldset class="formelements">
        <div class="legend">
            <span>Subjects Assigned To The Candidate</span>
        </div>
        <table  class="display"
               id="groupSubjects">
            <thead>
            <tr>
                <th>Subject</th>
                <th>Type</th>
                <th>Subject Code</th>
                <th>Subject Name</th>
                <th style="width: 150px">View Details</th>
                <th style="width: 150px">Edit Details</th>
            </tr>
            </thead>
            <tbody>

            </tbody>
        </table>
    </fieldset>
</div>
