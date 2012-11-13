<?php
require_once 'config.php';
require_once BASE_PATH . 'include/global/class.body.php';

$body = new body ();
?>
<script type="text/javascript" language="javascript" src="<?php echo $body->getBaseServer();?>javascript/header/global/login.js"></script>
<div class="login-box">
    	<section class="login-box-top">
            <header>
                <h2 class="logoMessage">Support Gurukul Login</h2>
            </header>
            <section>
                <div class="loginMessage" id="loginMessage">Your login session has expired. Please login to continue</div>
                <div class="inputs">
                    <form id="loginForm" id="loginForm" onsubmit="return valid.validateForm(this) ? processLoginProcess() : false;">
                        <dl class="element" id="">
                            <dt style="width: 30%"><label for="username">Username :</label>	</dt>
                            <dd style="width: 55%">
                                <input type="text" size="30" name="username" id="username" class="required" tabindex="101" onchange="javascript: valid.validateInput(this);" title="Input Your Username" />
                                <div id="usernameError" class="validationError" style="display: none"></div></dd>
                        </dl>
                        <div class="loginSeparator"></div>
                        <dl class="element" id="">
                            <dt style="width: 30%"><label for="password">Password :</label>	</dt>
                            <dd style="width: 55%">
                                <input type="password" size="30" name="password" id="password" class="required" tabindex="102" onchange="javascript: valid.validateInput(this);" title="Input Your Password" />
                                <div id="passwordError" class="validationError" style="display: none"></div></dd>
                        </dl>
                        <div class="loginSeparator"></div>
                        <div class="buttons" style="float: right">
                            <button class="negative cross" id="logoffButton" onclick="logoffUser()" type="button"><img src="<?php echo $body->getBaseServer();?>images/global/icons/logoff.png" alt="" />Close To Logout</button>
                            <button class="positive edit" type="submit"><img src="<?php echo $body->getBaseServer();?>images/global/icons/Unlock.png" alt="" />Submit To Login</button>
                        </div>
                    </form>
                </div>
            </section>
    	</section>
	</div>

