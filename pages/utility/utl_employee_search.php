<?php
/**
 *
 * @author hemantanshu@supportgurukul.com(html)
 * @category Utility
 * @license Support-Gurukul
 * @version 1.0.0
 */

require_once 'config.php';
require_once BASE_PATH . 'include/global/class.body.php';

$body = new body ();
$body->startBody ( 'utility', 'LMENUL73', 'Search Employee Details' );
?>
<br />
<div class="clear"></div>
<div class="inputs">
    <form id="searchForm" class="searchForm"
          onsubmit="return valid.validateForm(this) ? getSearchDetails() : false;">
        <fieldset class="formelements">
            <div class="legend">Search Employee Details</div>
            <dl>
                <dt style="width: 15%">
                    <label for="menu_hint">Employee Name :</label>
                </dt>
                <dd style="width: 30%">
                    <input type="text" name="searchString" tabindex="30" id="searchString"
                           class="" style="width: 200px"
                           onchange="javascript: valid.validateInput(this);" />
                    <div id="menu_hintError" class="validationError"
                         style="display: none;"></div>
                </dd>
                <div id="search_typeError" class="validationError"
                     style="display: none;"></div>
            </dl>
            <dl>
            </dl>
        </fieldset>
        <fieldset class="action buttons">
            <div class="buttons">
                <button type="reset" name="searchReset" id="searchReset" class="negative reset"
                        accesskey="L">
                    Reset Search Fie<span class="underline">l</span>ds
                </button>
                <button accesskey="S" type="submit" name="toggleInsert1" class="positive search"
                        tabindex="33" id="toggleInsert1">
                    Get <span class="underline">S</span>earch Results
                </button>
            </div>
        </fieldset>
    </form>

</div>
<div class="clear"></div>


<div class="datatable buttons" id="displayDatatable" style="display: none">
    <fieldset class="formelements">
        <div class="legend">
            <span>Tabulated Listing Of Sections</span>
        </div>
        <table  class="display"
                id="groupEmployee">
            <thead>
            <tr>
                <th>Photograph</th>
                <th>Employee ID</th>
                <th>Personal Details</th>
                <th>DOB</th>
                <th>Other Details</th>
                <th style="width: 150px">View Details</th>
            </tr>
            </thead>
            <tbody>

            </tbody>
        </table>
    </fieldset>
</div>
