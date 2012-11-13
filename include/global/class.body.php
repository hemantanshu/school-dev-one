<?php
require_once 'class.menu.php';
class body extends menu {

    public function __construct() {
        parent::__construct();
    }

    public function startBody($module, $urlId, $title, $mainUrlId = '', $flag = true) {    	
    	if($this->isUserLogged(false)){
    		if(!$this->isLoggedUserAdmin()){
    			$urlToCheck = empty($mainUrlId) ? $urlId : $mainUrlId;
    			$details = $this->getMenuUrlIdDetails($urlToCheck);
    			if($details['menu_authorized'] == "y"){
    				if(!$this->isOfficerAuthorized4Menu($this->getLoggedUserId (), $urlId, $details['menu_editable'])){
    					echo "wrong navigation";
    					exit(0);
    				}
    			}
    		}	
    	}else{
    		//the user is not logged
    		exit(0);
    	}    	
        $this->generateTopHeader($module, $urlId, $title, $flag);
    }

    public function startMainBody(){
        if($this->getLoggedUserId() == "")
            $this->redirectUrl($this->getBaseServer());
        $this->generateMainTopBodyHeader();
    }

    private function generateTopHeader($module, $urlId, $title, $flag) {
        echo "<script type=\"text/javascript\" language=\"javascript\" src=\"" . $this->getBaseServer() . "javascript/header/".$module."/" . $urlId . ".js\"></script>";
        if($flag){
            $_SESSION['mainUrlLoaded'] = $urlId;
            unset($_SESSION['quickUrlLoaded']);
        }else{
            $_SESSION['quickUrlLoaded'] = $urlId;
        }
    }

    private function generateMainTopBodyHeader(){
        echo "
<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">
<html xmlns=\"http://www.w3.org/1999/xhtml\">
<head>
	<meta http-equiv=\"Content-Type\" content=\"text/html; charset=ISO-8859-1\" />
	<title>Delhi Public School, Kashi</title>
	<link rel=\"icon\" type=\"image/png\" href=\"" . $this->getBaseServer() . "images/global/favicon.ico\" />
	<link rel=\"stylesheet\" type=\"text/css\" media=\"all\" href=\"" . $this->getBaseServer() . "css/global/footerpanel.css\" />
	<link rel=\"stylesheet\" type=\"text/css\" media=\"all\" href=\"" . $this->getBaseServer() . "css/global/menu.css\" />
	<link rel=\"stylesheet\" type=\"text/css\" media=\"all\" href=\"" . $this->getBaseServer() . "css/global/common.css\" />
	<link rel=\"stylesheet\" type=\"text/css\" media=\"all\" href=\"" . $this->getBaseServer() . "css/global/form.css\" />
	<link rel=\"stylesheet\" type=\"text/css\" media=\"all\" href=\"" . $this->getBaseServer() . "css/global/demo_table_jui.css\" />
	<link rel=\"stylesheet\" type=\"text/css\" media=\"all\" href=\"" . $this->getBaseServer() . "css/global/jquery.ui.custom.css\" />
	<link rel=\"stylesheet\" type=\"text/css\" media=\"all\" href=\"" . $this->getBaseServer() . "css/global/jquery.autocomplete.css\" />
	<link rel=\"stylesheet\" type=\"text/css\" media=\"all\" href=\"" . $this->getBaseServer() . "css/global/buttons.css\" />
	<link rel=\"stylesheet\" type=\"text/css\" media=\"all\" href=\"" . $this->getBaseServer() . "css/global/jquery.colorbox.css\" />
	<link rel=\"stylesheet\" type=\"text/css\" media=\"all\" href=\"" . $this->getBaseServer() . "css/global/jquery.freeow.css\" />	
	<link rel=\"stylesheet\" type=\"text/css\" media=\"all\" href=\"" . $this->getBaseServer() . "css/global/jquery.tableTools.css\"/>
	<link rel=\"stylesheet\" type=\"text/css\" media=\"all\" href=\"" . $this->getBaseServer() . "css/global/print.css\" media=\"print\" />
	

	<script type=\"text/javascript\" language=\"javascript\" src=\"" . $this->getBaseServer() . "javascript/global/jquery.js\" ></script>
	<script type=\"text/javascript\" language=\"javascript\" src=\"" . $this->getBaseServer() . "javascript/global/jquery-ui.min.js\" ></script>
	<script type=\"text/javascript\" language=\"javascript\" src=\"" . $this->getBaseServer() . "javascript/global/jquery.tools.js\" ></script>			
	<script type=\"text/javascript\" language=\"javascript\" src=\"" . $this->getBaseServer() . "javascript/global/jquery.alert.js\" ></script>
	<script type=\"text/javascript\" language=\"javascript\" src=\"" . $this->getBaseServer() . "javascript/global/jquery.colorbox.js\" ></script>
	<script type=\"text/javascript\" language=\"javascript\" src=\"" . $this->getBaseServer() . "javascript/global/jquery.validation.js\"></script>
    <script type=\"text/javascript\" language=\"javascript\" src=\"" . $this->getBaseServer() . "javascript/global/jquery.dataTables.js\" ></script>
    <script type=\"text/javascript\" language=\"javascript\" src=\"" . $this->getBaseServer() . "javascript/global/jquery.tableTools.js\" ></script>
    <script type=\"text/javascript\" language=\"javascript\" src=\"" . $this->getBaseServer() . "javascript/global/jquery.fixedColumns.js\" ></script>
    <script type=\"text/javascript\" language=\"javascript\" src=\"" . $this->getBaseServer() . "javascript/global/jquery.zeroClipboard.js\" ></script>
	<script type=\"text/javascript\" language=\"javascript\" src=\"" . $this->getBaseServer() . "javascript/global/menu.js\"></script>
    <script type=\"text/javascript\" language=\"javascript\" src=\"" . $this->getBaseServer() . "javascript/global/menu_nav.js\"></script>
    <script type=\"text/javascript\" language=\"javascript\" src=\"" . $this->getBaseServer() . "javascript/global/common.js\"></script>
    <script type=\"text/javascript\" language=\"javascript\" src=\"" . $this->getBaseServer() . "javascript/global/jquery.autocomplete.js\"></script>
    <script type=\"text/javascript\" language=\"javascript\" src=\"" . $this->getBaseServer() . "javascript/global/jquery.cookie.js\"></script>
    <script type=\"text/javascript\" language=\"javascript\" src=\"" . $this->getBaseServer() . "javascript/global/jquery.freeow.js\"></script>
    <script type=\"text/javascript\" language=\"javascript\" src=\"" . $this->getBaseServer() . "javascript/global/page.operations.js\"></script>
</head>
<body>
	<div id=\"mainContainer\">
	<div id=\"toppanel\" class=\"noPrint\">
		<div id=\"navcontainer\">
			<div id=\"nav\">
        		<div id=\"sgmenudiv\" class=\"sgmenu\">";
                $this->generateTopMenu();

        echo "
                <br style=\"clear: left\" />
				</div>
			</div>
		</div>
	</div>
	<div id=\"contentBoxContainer\">
		<div id=\"displayRegion\">
			";
    }

    public function endBody($module, $urlId = '') {
        if (!empty($urlId))
            echo "
	            <script type='text/javascript' src='" . $this->getBaseServer() . "javascript/footer/".$module."/" . $urlId . ".js'></script>";
    }

    public function endMainBody(){
        echo "
    	</div>
        <p>&nbsp;</p>
	</div>
	<div id=\"notification\" class=\"freeow freeow-bottom-right\"></div>
	<div id=\"loading\"></div>
    <input type=\"hidden\" name=\"globalServerUrl\" id=\"globalServerUrl\" value=\"".$this->getBaseServer()."\" />
	<div id=\"footpanel\" class=\"noPrint\">
        <ul id=\"mainpanel\">
            <li><a href=\"#\" class=\"home\">".$this->getOfficerName($this->getLoggedUserId())."<small>Home</small></a></li>
            <li><a href=\"#\" class=\"profile\">View Profile <small>View Profile</small></a></li>
            <li><a href=\"#\" class=\"logout\" onclick=\"confirmLogoutClick()\">Logout <small>Logout</small></a></li>
            <li><a href=\"#\" class=\"bookmark\" onclick=\"showBookmarkPage()\" accesskey=\"D\">Bookmark <small>Bookmark</small></a></li>
            <li><a href=\"#\" class=\"refresh\" onclick=\"refreshThePage()\" accesskey=\"R\">Refresh <small>Refresh Page</small></a></li>
            <li><a href=\"#\" class=\"back\" onclick=\"restorePreviousPage()\" accesskey=\"B\">Back <small>Last Page</small></a></li>
            <li><a href=\"#\" class=\"lock\" onclick=\"lockTheScreen()\" accesskey=\"L\">Lock <small>Lock Screen</small></a></li>

            <li id=\"notificationPanel\"><a href=\"#\" class=\"blog\">Alerts</a>
                <div class=\"subpanel\" style=\"height: auto; display: none;\">
                    <h3><span> ? </span>Notifications</h3>
                    <ul style=\"height: auto;\" id=\"notificationMessages\">
                    </ul>
                </div>
            </li>
            <li id=\"alertPanel\"><a href=\"#\" class=\"alert\">Alerts</a>
                <div class=\"subpanel\" style=\"height: auto; display: none;\">
                    <h3><span> ? </span>Alert Messsages</h3>
                    <ul style=\"height: auto;\" id=\"snotificationMessages\">
                    </ul>
                </div>
            </li>
            <li id=\"chatpanel\"><a href=\"#\" class=\"chat\">Bookmark</a>
                <div class=\"subpanel\" style=\"display: none; height: 672px;\">
                    <h3>Bookmarked Pages</h3>
                    <ul style=\"height: 647px;\" id=\"bookmarkedMenuListing\">
                    </ul>
                </div>
            </li>
            <li id=\"extraMenuPanel\"><a href=\"#\" class=\"extraMenu\">Additional Menu</a>
                <div class=\"subpanel\" style=\"height: 400px;\">
                    <h3>Additional Menus</h3>
                    <ul style=\"height: 647px;\" id=\"extraMenuListing\">
                    </ul>
                </div>
            </li>
            <li id=\"pendingTasks\"><a href=\"#\" class=\"pending\">Pending Tasks</a>
                <div class=\"subpanel\" style=\"height: 400px;\">
                    <h3>Pending Taks</h3>
                    <ul style=\"height: 400px;\" id=\"extraPendingTasks\">
                    </ul>
                </div>
            </li>
            
        </ul>
	</div>
</div>
            <script type='text/javascript' src='" . $this->getBaseServer() . "javascript/footer/global/default.js'></script>
</html>";
    }

}

?>