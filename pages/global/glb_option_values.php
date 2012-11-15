<?php
require_once 'config.php';
require_once BASE_PATH.'include/global/class.body.php';
require_once BASE_PATH.'include/global/class.options.php';

$body = new body();
$options = new options();
$body->startBody('global', 'LMENUL9', 'Option Value Entry');

$optionType = $_GET['optionTypeId'];
$details = $options->getOptionTypeIdDetails($optionType);

if($details['comments'] == "")
	exit(0);
?>
<div id="content_header">
    <div id="pageButton" class="buttons">
        <button type="button" class="regular toggle" onclick="showHideSearchForm()"><span class="underline">T</span>oggle Search Form</button>
        <button type="button" class="regular toggle" onclick="showHideDatatable()"><span class="underline">T</span>oggle Tabulated Data</button>
    </div>
    <div id="contentHeader"><?php echo $details['comments']?> Option Value Details Form</div>
</div>
<input type="hidden" value="<?php echo $details['flag'];?>" name="optionType_glb" id="optionType_glb" />
<div class="clear"></div>
<div class="inputs">
    <form id="insertForm" class="insertForm" onsubmit="return valid.validateForm(this) ? processMainForm() : false;">
        <fieldset class="formelements">
            <div class="legend">
                <span>New Record Insert Form</span>
            </div>
            <dl class="element">
                 <dt style="width:15% ">                        
                    <label for="valueName">Value Name :</label>
                    </dt>
                    <dd style=" width:30%">                    
                     <input type="text" name="valueName" id="valueName"  class="required" title="Enter The New Value" value="" size="40" onchange="javascript: valid.validateInput(this);"/>
                     <div id="valueNameError" class="validationError" style="display: none"></div>                 
                    </dd>
                 <dt style="width:15% ">                        
                    <label for="reserved">Reserved Keyword :</label>
                    </dt>
                    <dd style=" width:30%">                    
                     <input type="checkbox" name="reserved" id="reserved"  class="" title="Is This The Reserved Menu" value="y" onchange="javascript: valid.validateInput(this);"/>
                     <div id="reservedError" class="reservedError" style="display: none"></div>                 
                 </dd>   
                    
            </dl>
         </fieldset>       
        <fieldset class="action buttons">
			<button type="button" name="submit" onclick="hideInsertForm()" class="regular hide"
				accesskey="H">
				<span class="underline">H</span>ide Insert Form
			</button>

			<button type="reset" name="insertReset" id="insertReset" class="negative reset"
				accesskey="R">
				<span class="underline">R</span>eset Form Fields
			</button>

			<button type="submit" name="submit" id="submit" accesskey="I" class="positive insert">
				<span class="underline">I</span>nsert New Record
			</button>
		</fieldset>

        
    </form>
</div>


<div class="clear"></div>
<div class="inputs">
    <form id="updateForm" class="updateForm" onsubmit="return valid.validateForm(this) ? processUpdateForm() : false;">
        <fieldset class="formelements">
            <div class="legend">
                <span id="legend_updateForm">Update Record Details Form</span>
            </div>
            <dl class="element">
                 <dt style="width:15% ">
                        
                    <label for="valueName_u">Value Name :</label>
                    </dt>
                    <dd style=" width:30%">
                   
                     <input type="text" name="valueName_u" id="valueName_u"  class="required" title="Enter The Value For Update" value="" size="40" onchange="javascript: valid.validateInput(this);"/>
                     <div id="valueName_uError" class="validationError" style="display: none"></div>                 
                    </dd>
            </dl>
        </fieldset>
      
        <fieldset class="action buttons">
            <input type="hidden" name="valueId_u" id="valueId_u" value="" /> 
    	<input type="hidden" name="rowPosition_u" id="rowPosition_u" value="" />
        <button type="button" class="positive activate" name="activateOptionValue_u" id="activateOptionValue_u">Activate Value</button>
        <button type="button" class="negative drop" name="dropOptionValue_u" id="dropOptionValue_u">Drop Value</button>
        <button type="button" name="submit" id="submit" class="regular hide"
                onclick="hideUpdateForm()">Hide Update Portion</button>
        <button type="submit" class="positive update" accesskey="U" ><span class="underline">U</span>pdate Record</button>
        </fieldset>
        
    </form>
</div>
<!-- 
<div class="clear"></div>
<div class="inputs">
    <form id="ChangeForm" class="ChangeForm">
        <fieldset>
            <div class="legend">
                <span id="legend_ChangeForm">Change Assignment Form </span>
            </div>
            <dl class="element">
                 <dt style="width:15% ">
                        
                    <label for="pValue">Parent Value :</label>
                    </dt>
                    <dd style=" width:30%">
                    <input type="hidden" name="pValue_val" id="pValue_val" value="" />
                     <input type="text" name="pValue" id="pValue"  class="required" title="Enter The Parent Value" value="" size="40"/>                 
                    </dd>
                    <dt style="width:15% ">
                        
                    <label for="cValue">Child Value :</label>
                    </dt>
                    <dd style=" width:30%">
                    <input type="hidden" name="cValue_val" id="cValue_val" value="" />
                     <input type="text" name="cValue" id="cValue"  class="required" title="Enter The Child Value" value="" size="40"/>                 
                    </dd>
            </dl>
        </fieldset>
      
        <fieldset class="action buttons">
            <input type="hidden" name="valueId_c" id="valueId_c" value="" /> 
    	<input type="hidden" name="rowPosition_c" id="rowPosition_c" value="" />
        
        <button type="button" class="edit" onclick="processChangeForm()">Change Assignment</button>
        <button type="button" name="submit" id="submit"
                onclick="hideChangeForm()">Hide Change Assignment</button>
        </fieldset>
        
    </form> 
</div>
 -->
<div class="clear"></div>
<div class="display">
 <div id="displayValue">
    <fieldset class="displayElements">
        <div class="legend">
            <span id="legendDisplayAssignment">Value Details  : </span>
        </div>
        <dl>
          <dt style="width:15%">
            <label for="optionId">Option Id : </label>
            </dt>
            <dd style="width:30%"><span id="optionId"></span></dd>
        </dl>
        <dl>
            <dt style="width:15%;">
            <label for="valueName_d">Value Name  : </label>
            </dt>
            <dd style="width:30%"><span id="valueName_dDisplay"></span></dd>
            <dt style="width:15% ">
            <label for="total">Total Assignment :</label>
            </dt>
            <dd style=" width:30%"><span id="totalDisplay"></span></dd>
        </dl>     
        <dl>
            <dt style="width:15%;">
            <label for="updateDate">Last Update Date : </label>
            </dt>
            <dd style="width:30%"><span id="lastUpdateDateDisplay"></span></dd>
            <dt style="width:15% ">
            <label for="updatedBy">Updated BY :</label>
            </dt>
            <dd style=" width:30%"><span id="lastUpdatedByDisplay"></span></dd>
        </dl>                 
        <dl>
            <dt style="width:15%;">
            <label for="creationDate">Creation Date : </label>
            </dt>
            <dd style="width:30%"><span id="creationDateDisplay"></span></dd>
            <dt style="width:15% ">
            <label for="createdBy">Created BY :</label>
            </dt>
            <dd style=" width:30%"><span id="createdByDisplay"></span></dd>
        </dl>       
        <dl>
            <dt style="width:15%;">
            <label for="active">Active/Inactive : </label>
            </dt>
            <dd style="width:30%"><span id="activeDisplay"></span></dd>
        </dl>
    </fieldset>
    
    <fieldset class="action buttons">
            <input type="hidden" name="valueId_d" id="valueId_d" value="" /> 
    	<input type="hidden" name="rowPosition_d" id="rowPosition_d" value="" />
        <button type="button" class="positive activate" name="activateOptionValue_d" id="activateOptionValue_d">Activate value</button>
        <button type="button" class="negative drop" name="dropOptionValue_d" id="dropOptionValue_d">Drop value</button>
        <button type="button" name="submit" class="regular hide" id="submit"
                onclick="hideDisplayPortion()">Hide Display Details Portion</button>
        <button type="button" class="positive edit" id="update_value_button" onclick="processDisplayForm()">Edit Details</button>
        </fieldset>
    
  </div>
</div>
<div class="clear"></div>
<div class="inputs">
    <form id="searchForm" class="searchForm">
        <fieldset class="formelements"><div class="legend">Search Option Values</div>
            <dl>
                <dt style="width: 15%"><label for="menu_hint">Value Name :</label>
                </dt>
                <dd style="width: 30%"><input type="text" name="menu_hint"
                                              id="menu_hint" class="required" style="width: 200px" title="Enter The Option Name" /></dd>
                <dt style="width:15%"><label for="search_type">Search Type :</label></dt>
                <dd><select name="search_type" id="search_type" style="width:150px">
                        <option value="all">All Menus</option>
                        <option value="1" selected="selected">Active Menus</option>
                        <option value="0">In-Active Menus</option>                        
                    </select></dd>
            </dl>
        </fieldset>
      
        <fieldset class="action buttons">
            <button type="button" name="toggleInsert1" class="regular toggle" id="toggleInsert1"
                    onclick="toggleInsertForm()">Toggle Insert Form</button>
            <button type="button" name="toggleInsert1" id="toggleInsert1" class="positive search"
                    onclick="getOptionValueSearchDetails()">Get Search Results</button>
        </fieldset>
    </form>
</div>


<div class="clear"></div>

<div id="displayDatatable" class="buttons">
    <div class="datatable" id="groupMenusM">
        <fieldset>
            <div class="legend">
                <span>Option Value Tabulated Listing</span>
            </div>
            <table  class="display" id="groupValues">
                <thead>
                    <tr>
                        <th>Value Id</th>
                        <th>Value Name</th>
                        <th style="width: 150px">View Details</th>
                        <th style="width: 150px">Edit Details</th>
                    </tr>
                </thead>
                <tbody>

                </tbody>
            </table>
        </fieldset>
    </div>
</div>
