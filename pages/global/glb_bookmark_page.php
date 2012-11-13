<?php
require_once 'config.php';
require_once BASE_PATH . 'include/global/class.body.php';
$body = new body ();
?>
<div class="inputs">
    <form id="insertForm" class="insertForm"
          onsubmit="return valid.validateForm(this) ? processBookmarkInsert() : false;">
        <fieldset class="formelements">
            <div class="legend">
                <span>New Bookmark Record Entry Form</span>
            </div>
            <dl class="element">
                <dt style="width: 30%"><label for="pageName">Bookmark Name :</label>	</dt>
                <dd style="width: 60%">
                    <input type="text" name="pageNameD" id="pageNameD" class="required" tabindex="1" size="30" onchange="javascript: valid.validateInput(this);" title="Enter Bookmark Identifier Name" />
                    <div id="pageNameDError" class="validationError" style="display: none"></div></dd>
            </dl>
            <dl class="element">
                <dt style="width: 30%"><label for="priority">Priority :</label>	</dt>
                <dd style="width: 60%">
                    <input type="text" name="priorityD" id="priorityD" class="required numeric" tabindex="2" size="10" onchange="javascript: valid.validateInput(this);" title="Lower the priority, higher will be order" />
                    <div id="priorityDError" class="validationError" style="display: none"></div></dd>
            </dl>
        </fieldset>
        <fieldset class="action buttons">
            <button type="submit" name="submit" id="submit" accesskey="I" class="positive insert">
                <img src="<?php echo $body->getBaseServer(); ?>images/global/icons/Create.png" alt="" />
                <span class="underline">I</span>nsert New Record
            </button>
        </fieldset>
    </form>
</div>

