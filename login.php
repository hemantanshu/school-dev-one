<?php
require_once 'include/global/class.body.php';
$body = new body ();

?>
<html>
<head>
    <title>Support Gurukul India Pvt Ltd</title>
    <link rel="stylesheet" type="text/css" media="all" href="css/global/footerpanel.css" />
    <link rel="stylesheet" type="text/css" media="all" href="css/global/menu.css" />
    <link rel="stylesheet" type="text/css" media="all" href="css/global/common.css" />
    <link rel="stylesheet" type="text/css" media="all" href="css/global/form.css" />
    <link rel="stylesheet" type="text/css" media="all" href="css/global/demo_table_jui.css" />
    <link rel="stylesheet" type="text/css" media="all" href="css/global/jquery.ui.custom.css" />
    <link rel="stylesheet" type="text/css" media="all" href="css/global/datepicker.css" />
    <link rel="stylesheet" type="text/css" media="all" href="css/global/jquery.autocomplete.css" />
    <link rel="stylesheet" type="text/css" media="all" href="css/global/buttons.css" />
    <link rel="stylesheet" type="text/css" media="all" href="css/global/jquery.colorbox.css" />


    <script type="text/javascript" src="javascript/global/jquery.js" ></script>
    <script type="text/javascript" src="javascript/global/jquery.validation.js"></script>
    <script type="text/javascript" src="javascript/header/global/login.js"></script>
</head>
<body>
<div class="centerBox">
    <div class="insideBox">
        <div class="login-box">
            <section class="login-box-top">
                <header>
                    <h2 class="logoMessage">Support Gurukul Login</h2>
                </header>
                <section>
                    <div class="loginMessage" id="loginMessage">Please login to continue</div>
                    <div class="inputs">
                        <form id="form" id="form" onsubmit="return valid.validateForm(this) ? processMainLoginProcess() : false;">
                            <dl class="element" id="">
                                <dt style="width: 30%"><label for="username">Username :</label>	</dt>
                                <dd style="width: 55%">
                                    <input type="text" name="username" id="username" class="required" tabindex="1" onchange="javascript: valid.validateInput(this);" title="Input Your Username" />
                                    <div id="usernameError" class="validationError" style="display: none"></div></dd>
                            </dl>
                            <div class="loginSeparator"></div>
                            <dl class="element" id="">
                                <dt style="width: 30%"><label for="password">Password :</label>	</dt>
                                <dd style="width: 55%">
                                    <input type="password" name="password" id="password" class="required" tabindex="2" onchange="javascript: valid.validateInput(this);" title="Input Your Password" />
                                    <div id="passwordError" class="validationError" style="display: none"></div></dd>
                            </dl>
                            <div class="loginSeparator"></div>
                            <div class="buttons" style="float: right">
                                <button class="positive edit" type="submit"><img src="images/global/icons/Unlock.png" alt="" />Submit To Login</button>
                            </div>
                        </form>
                    </div>
                </section>
            </section>
        </div>
    </div>
</div>
<div id="loading"></div>
</body>

</html>


