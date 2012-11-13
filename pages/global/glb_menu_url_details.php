<?php
require_once 'config.php';
	require_once BASE_PATH.'include/global/class.body.php';
    $body = new body ();
    $body->startBody('global', 'LMENUL2', 'Menu Url Details');
?>
<div id="content_header">
    <div id="pageButton" class="buttons"></div>
    <div id="contentHeader">Menu Url Details Form</div>
</div>
<div class="inputs">
    <form id="searchForm" class="searchForm" method="post">
        <fieldset class="formelements">
            <div class="legend"> <span>Search Menu Url Name</span>
            </div>
            <dl class="element">
                <dt style="width: 15%;"><label for="menuName"> Menu Name:</label></dt>
                <dd style="width: 40%"><input type="text" class="required" size="50" id="menuName" name="menuName" onblur="fetchDetails()" onclick="this.value=''" value="" title="Enter the Menu whose details you want to search" /></dd>
                <dd><input type="hidden" name="menuId" id="menuId" value=""></dd>
            </dl>
        </fieldset>
    </form>
</div>
<div class="clear"></div>
<div id="display" class="display" style="display: none">
    <fieldset class="displayElements">
    	<div class="legend">
            <span id="legendDisplayAssignment">Menu Details :</span>
        </div>
        <dl>
            <dt style="width: 15%;"><label for="menu_name">Menu Name : </label></dt>
            <dd style="width: 30%;"><span id="menuNameDisplay"></span></dd>
            <dt style="width: 15%;"><label for="menuIdDisplay">Menu ID :</label></dt>
            <dd style="width: 30%;"><span id="menuIdDisplay"></span></dd>
        </dl>
        <dl>
            <dt style="width: 15%;"><label for="tagline">Tagline : </label></dt>
            <dd style="width: 30%;"><span id="tagline"></span></dd>
            <dt style="width: 15%;"><label for="description">Description :</label></dt>
            <dd style="width: 30%;"><span id="description"></span></dd>
        </dl>        
        <dl>
            <dt style="width: 15%;"><label for="menuEdit">Menu Edit : </label></dt>
            <dd style="width: 30%;"><span id="menuEdit"></span></dd>
            <dt style="width: 15%;"><label for="menuAuth">Authentication Required :</label></dt>
            <dd style="width: 30%;"><span id="menuAuth"></span></dd>
        </dl>
        <dl>
            <dt style="width: 15%;"><label for="menuUrlDisplay">Menu URL : </label></dt>
            <dd style="width: 30%;"><span id="menuUrlDisplay"></span></dd>            
            <dt style="width: 15%;"><label for="active">Active/ Inactive : </label></dt>
            <dd style="width: 30%;"><span id="active"></span></dd>
        </dl>
        <dl>
            <dt style="width: 15%;"><label for="parentMenu">Parent Menu : </label></dt>
            <dd style="width: 30%;"><span id="parentMenu"></span></dd>            
            <dt style="width: 15%;"><label for="sourceId">Source URL : </label></dt>
            <dd style="width: 30%;"><span id="sourceId"></span></dd>
        </dl>        
        <dl>
            <dt style="width: 15%;"><label for="topMenu">Top Menus assigned :</label></dt>
            <dd style="width: 30%;"><span id="topMenu"></span></dd>
        </dl>
        <dl>
            <dt style="width: 15%;"><label for="subMenu">Sub Menus assigned :</label></dt>
            <dd style="width: 30%;"><span id="subMenu"></span></dd>
        </dl>
		<dl>
            <dt style="width: 15%;"><label for="lastUpdateDate">Last Update Date : </label></dt>
            <dd style="width: 30%;"><span id="lastUpdateDate"></span></dd>            
            <dt style="width: 15%;"><label for="lastUpdatedBy">Updated By : </label></dt>
            <dd style="width: 30%;"><span id="lastUpdatedBy"></span></dd>
        </dl>
        <dl>
            <dt style="width: 15%;"><label for="creationDate">Creation Date : </label></dt>
            <dd style="width: 30%;"><span id="creationDate"></span></dd>            
            <dt style="width: 15%;"><label for="createdBy">Created By : </label></dt>
            <dd style="width: 30%;"><span id="createdBy"></span></dd>
        </dl>     
        <dl style="height:25px"></dl>
    </fieldset>
    <fieldset class="action buttons">
        <input type="hidden" name="menu_id_d" id="menu_id_d" value="" />        
        <button type="button" name="hide" class="regular hide" id="hide" onclick="hideDetailsForm()">Hide Details</button>
        <button type="button" name="hideLog" id="hideLog" class="regular hide" onclick="hideLog()">Hide Log Details</button>
        <button type="button" name="log" id="log" class="positive search" onclick="showLogDetails()">Show Log Details</button>
    </fieldset>
</div>

<div class="clear"></div>
<div class="datatable buttons" id="showLogs" style="display: none">
    <fieldset class="displayElements"><div class="legend">
            <span>Log For This Menu URL</span>
        </div>
        <table  class="display" id="groupMenus">
            <thead>
                <tr>
                    <th>Officer</th>
                    <th>Datetime</th>
                    <th>Operation</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </fieldset>
</div>

