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
$body->startBody ( 'library', 'LMENUL143', 'Library Vendor List' );
?>
<div id="content_header">
    <div id="pageButton" class="buttons"></div>
    <div id="contentHeader">Library Vendor List</div>
</div>
<div class="clear"></div>
<div class="inputs">
    <form id="searchForm" class="searchForm" onsubmit="return getSearchResults()">
        <fieldset class="formelements">
            <div class="legend">Search Vendor Name</div>
            <dl>
                <dt style="width: 15%">
                    <label for="hint">Vendor Name :</label>
                </dt>
                <dd style="width: 30%">
                    <input type="text" name="hint" id="hint" class=""
                           style="width: 200px" title="Enter The Vendor Name" />
                </dd>
                <dt style="width: 15%">
                    <label for="search_type">Search Type :</label>
                </dt>
                <dd>
                    <select name="search_type" id="search_type" style="width: 150px">
                        <option value="all">All Records</option>
                        <option value="1" selected="selected">Active Records</option>
                        <option value="0">In-Active Records</option>
                    </select>
                </dd>
            </dl>
        </fieldset>
        <fieldset class="action buttons">
            <button type="submit" name="searchData" id="searchData" class="positive search">Get Search
                Results</button>
        </fieldset>
    </form>
</div>
<div class="clear"></div>
<div class="datatable" id="displayDatatable" style="display:none">
    <fieldset class="formelements">
        <div class="legend">
            <span>Tabulated Listing Of All Vendors</span>
        </div>
        <table  class="display"
               id="groupRecords">
            <thead>
            <tr>
            	<th>Vendor Name</th>
            	<th>Win Rate</th>
            	<th>Contact No</th>
            	<th>Email</th>
            </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </fieldset>
</div>