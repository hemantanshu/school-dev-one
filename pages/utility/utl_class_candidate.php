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

$classId = $_GET ['classId'];
$details = $body->getTableIdDetails($classId);

$body->startBody ( 'utility', 'LMENUL59', 'Class Candidate Subject Assignment' );
if ($details['class_id'] == "")
    exit(0);

?>
<div id="content_header">
    <div id="pageButton" class="buttons">
        <button type="button" class="positive browse" onclick="bulkSubjectEntry()">Bulk Subject Entry</button>
        <button type="button" class="regular toggle" onclick="showHideSearchForm()"><span class="underline">T</span>oggle Search Form</button>
        <button type="button" class="regular toggle" onclick="showHideDatatable()"><span class="underline">T</span>oggle Tabulated Data</button>
    </div>
    <div id="contentHeader">Listing Candidate</div>
</div>
<input type="hidden" name="class_global" id="class_global" value="<?php echo $classId; ?>" />
<div class="clear"></div>
<div class="display">
    <div id="sessionRecord" style="display: none">
        <fieldset class="displayElements">
            <dl>
                <dt style="width: 15%">
                    <label for="session_d">Session :</label>
                </dt>
                <dd style="width: 30%">
                    <span id="session_d"></span>
                </dd>
                <dt style="width: 15%">
                    <label for="class">Class :</label>
                </dt>
                <dd style="width: 30%">
                    <span id="class_d"></span>
                </dd>
            </dl>
        </fieldset>
    </div>
</div>
<div class="clear"></div>

<div class="display">
    <div id="displayRecord" style="display: none">
        <fieldset class="displayElements">
            <div class="legend">
                <span id="legendDisplayDetail">Showing Details Of The Record </span>
            </div>
            <dl>
                <dt style="width: 18%;">
                    <label for="name">Candidate Name :</label>
                </dt>
                <dd style="width: 30%">
                    <span id="candidateName"></span>
                </dd>
                <dt style="width: 18%;">
                    <label for="registrationNumber">Reg. Number :</label>
                </dt>
                <dd style="width: 30%">
                    <span id="registrationNumber"></span>
                </dd>
            </dl>
            <dl>
                <dt style="width: 18%;">
                    <label for="dob">Date Of Birth :</label>
                </dt>
                <dd style="width: 30%">
                    <span id="dob"></span>
                </dd>
                <dt style="width: 18%">
                    <label for="gender">Gender :</label>
                </dt>
                <dd style="width: 30%">
                    <span id="gender"></span>
                </dd>
            </dl>
            <dl>
                <dt style="width: 18%;">
                    <label for="pEmail">Personal Email :</label>
                </dt>
                <dd style="width: 30%">
                    <span id="pEmail"></span>

                </dd>
                <dt style="width: 18%">
                    <label for="oEmail">Official Email :</label>
                </dt>
                <dd style="width: 30%">
                    <span id="oEmail"></span>
                </dd>
            </dl>
            <dl>
                <dt style="width: 18%;">
                    <label for="mobileNo">Contact No :</label>
                </dt>
                <dd style="width: 30%">
                    <span id="mobileNo"></span>
                </dd>
                <dt style="width: 18%">
                    <label for="contactNo">Alt. Contact No :</label>
                </dt>
                <dd style="width: 30%">
                    <span id="contactNo"></span>
                </dd>
            </dl>

            <dl>
                <dt style="width: 18%;">
                    <label for="address">Address :</label>
                </dt>
                <dd style="width: 80%">
                    <span id="address"></span>
                </dd>
            </dl>
            <dl>
                <dt style="width: 18%;">
                    <label for="compulsorySubject">Compulsory Subjects :</label>
                </dt>
                <dd style="width: 80%">
                    <span id="compulsorySubject"></span>
                </dd>
            </dl>
            <dl>
                <dt style="width: 18%;">
                    <label for="optionalSubject">Optional Subject :</label>
                </dt>
                <dd style="width: 80%">
                    <span id="optionalSubject"></span>
                </dd>
            </dl>
        </fieldset>
        <fieldset class="action buttons">
            <button type="button" name="submit" class="regular hide" id="submit"
                    onclick="hideDisplayPortion()">Hide Candidate Details</button>
            <button type="button" class="negative edit" id="editRecordButton"
                    class="editRecordButton">Change Subjects</button>
        </fieldset>
    </div>
</div>
<div class="clear"></div>
<div class="inputs">
    <form id="searchForm" class="searchForm" onsubmit="return getSearchResults()">
        <fieldset class="formelements">
            <div class="legend">Search Candidate</div>
            <dl>
                <dt style="width: 18%">
                </dt>
                <dd style="width: 30%">
                </dd>
                <dt style="width: 18%">
                    <label for="search_type">Search Type :</label>
                </dt>
                <dd>
                    <select name="search_type" id="search_type" style="width: 150px">
                        <option value="all">All Record</option>
                        <option value="1" selected="selected">Active Records</option>
                        <option value="0">In-Active Records</option>
                    </select>
                </dd>
            </dl>
        </fieldset>
        <fieldset class="action buttons">
            <button type="button" name="toggleInsert" id="toggleInsert" class="regular toggle"
                    onclick="toggleInsertForm()">Toggle Insert Form</button>
            <button type="submit" name="searchData" id="searchData" class="positive search">Get Search
                Results</button>
        </fieldset>
    </form>
</div>
<div class="clear"></div>
<div class="datatable buttons" id="displayDatatable" style="display: nones">
    <fieldset class="formelements">
        <div class="legend">
            <span>Tabulated Listing Of Candidates</span>
        </div>
        <table  class="display"
               id="groupRecords">
            <thead>
            <tr>
                <th>ID</th>
                <th>Candidate Name</th>
                <th>Gender</th>
                <th>Section</th>
                <th style="width: 160px">View Details</th>
                <th style="width: 170px">Change Subjects</th>
            </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </fieldset>
</div>
