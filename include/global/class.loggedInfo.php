<?php

require_once 'class.sqlFunction.php';

class loggedInfo extends sqlFunction {
	
	public function __construct() {
		parent::__construct ();
	}
	
	public function authenticateUser($user, $pwd, $flag) {
		$password = md5 ( md5 ( $pwd . $this->getPasswordSalt () ) );
		$sqlQuery = "SELECT * FROM glb_login WHERE username = \"$user\" ";
		$sqlQuery = $this->processQuery ( $sqlQuery );		
		if (mysql_num_rows ( $sqlQuery )) {
			
			$result = mysql_fetch_array ( $sqlQuery );
			if ($result ['password'] != $password) {
				// password didn't match
				$sqlQuery = "UPDATE glb_login SET attempts  = \"" . $result ['attempts'] . "\" WHERE username = \"$user\" ";
				$this->processQuery ( $sqlQuery );
				$this->userLog ( $result [0], 'n' );
				return 2;
			}
			if ($result ['active'] != 'y' || $result ['start_date'] > $this->getCurrentDate () || $result ['end_date'] < $this->getCurrentDate () || $result ['attempts'] > $this->getGlobalVariable ( 'maxLoginAttemp' )) {
				// the account is locked
				$this->userLog ( $result [0], 'l' );
				return 3;
			}
			
			if ($result ['attempts'] != 0) {
				$sqlQuery = "UPDATE glb_login SET attempts = \"0\" WHERE username = \"$user\" ";
				$this->processQuery ( $sqlQuery );
			}
			$this->userLog ( $result [0], 'y' );
			$_SESSION ['session_timer'] = $this->getGlobalVariable ( 'login_timer' );
			$_SESSION [$this->getLoginSessionIdentifier ()] = $result [0];
			
			$_SESSION ['lastActivityTime'] = time ();
			if($flag)
				$_SESSION ['mainUrlLoaded'] = 'LMENUL0';
			
            $_SESSION ['currentClassSessionId'] = $this->getCurrentClassSessionId();           
			return 0;
		}
		return 1;
	}
	
	public function isLoggedUserAdmin() {
		return false;
		$sqlQuery = "SELECT type FROM glb_login WHERE id = \"".$this->getLoggedUserId()."\" ";
		$sqlQuery = $this->processArray($sqlQuery);
		if($sqlQuery[0] == "ADMIN")
			return true;
		return false;
	}
	
	public function isUserLogged($flag = true) {
		if (isset ( $_SESSION [$this->getLoginSessionIdentifier ()] ) && isset ( $_SESSION ['lastActivityTime'] )) {
			if ((time () - $_SESSION ['lastActivityTime']) <= $_SESSION ['session_timer'] / 1000) {
				return true;
			}
		}
		return false;
	}
	
	public function isRequestAuthorised4Form($formId) {
		$statusCode = $this->checkValidityOfFormAccess($formId);
        $_SESSION ['lastActivityTime'] = time ();
        if($statusCode != "SUCC100"){
			echo json_encode($statusCode);
			exit(0);
		}
        return true;
	}
	
	public function checkValidityOfFormAccess($formId){		
		if ($this->isUserLogged ()) {
			if (! isset ( $_SESSION ['mainUrlLoaded'] )){
				return 'ERR406';
			}
			$menuId = $_SESSION ['mainUrlLoaded'];
			if ($menuId == $formId)
				return "SUCC100";
				
			$sqlQuery = "SELECT id FROM glb_menu_access WHERE menu_id = \"$formId\" && access_menu_id = \"$menuId\" && active = \"y\" ";
			$sqlQuery = $this->processQuery ( $sqlQuery );
			if (mysql_num_rows ( $sqlQuery ))
				return "SUCC100";
		
			// checking for the quick page request
			if (isset ( $_SESSION ['quickUrlLoaded'] )){
                $menuId = $_SESSION ['quickUrlLoaded'];
                if ($menuId == $formId)
                    return "SUCC100";

                $sqlQuery = "SELECT id FROM glb_menu_access WHERE menu_id = \"$formId\" && access_menu_id = \"$menuId\"  && active = \"y\" ";
                $sqlQuery = $this->processQuery ( $sqlQuery );
                if (mysql_num_rows ( $sqlQuery ))
                    return "SUCC100";
                
                return 'ERR404';
            }else{
                return 'ERR404';
            }
		}
		return 'ERR401';
	}
	
	public function logOutUser() {
		$logoutTime = date ( 'c' );
		$sqlQuery = "UPDATE glb_login_log SET logout_datetime = \"$logoutTime\" WHERE id = \"" . $_SESSION ['loginSessionIdentifier'] . "\"";
		if ($this->processQuery ( $sqlQuery )) {
			unset ( $_SESSION [$this->getLoginSessionIdentifier ()] );
			unset ( $_SESSION ['loginSessionIdentifier'] );
			unset ( $_SESSION ['lastActivityTime'] );
			
			return true;
		}
		return false;
	}
	
	public function getOfficerName($officerId) {
		$sqlQuery = "SELECT first_name, middle_name, last_name FROM utl_personal_info WHERE id = \"$officerId\" ";
		$sqlQuery = $this->processArray ( $sqlQuery );
		if($sqlQuery[0] != "")
			return $sqlQuery [2] . ' , ' . $sqlQuery [0] . ' ' . $sqlQuery [1];
		else
			return "";
	}
	
	public function getEmployeeCode($employeeId){
		$sqlQuery = "SELECT employee_code FROM utl_employee_registration WHERE employee_id = \"$employeeId\" ";
		return $this->processSingleElementQuery($sqlQuery);
	}
	
	public function getCandidateName($candidateId) {
		$sqlQuery = "SELECT first_name, middle_name, last_name FROM utl_personal_info WHERE id = \"$candidateId\" ";
		$sqlQuery = $this->processArray ( $sqlQuery );
		if($sqlQuery[0] != "")
			return $sqlQuery [0] . '  ' . $sqlQuery [1] . ' ' . $sqlQuery [2];
		else
			return "";
	}
	
	
	private function userLog($username, $flag) { // the log of the login process
	                                            // is saved by this function
		$agent = $_SERVER ['HTTP_USER_AGENT'];
		$ip = $_SERVER ['REMOTE_ADDR'];
		if (getenv ( 'HTTP_X_FORWARDED_FOR' ))
			$ip2 = getenv ( 'HTTP_X_FORWARDED_FOR' );
		else
			$ip2 = getenv ( 'REMOTE_ADDR' );
		$counter = $this->getCounter ( "login_log" );
		$query = " INSERT INTO glb_login_log 
    				(id, officer_id, local_ip, global_ip, login_datetime, success, browser)
    				VALUES (\"$counter\", \"$username\" , \"$ip2\" ,\"$ip\",  \"" . $this->getCurrentDateTime () . "\" , \"$flag\", \"$agent\") ";
		$this->processQuery ( $query );
		
		$_SESSION ['loginSessionIdentifier'] = $counter;
		return true;
	}
	
	/**
	 * This method is used to get the current datetime as per the server storing
	 * timezone set
	 *
	 * @return Datetime the datetime of the server
	 */
	public function getCurrentDateTime() {
		return date ( 'c' );
	}
	
	/**
	 * This method will be used to get the current date of the server
	 *
	 * @return Date The date of the server
	 */
	public function getCurrentDate() {
		return date ( 'Y-m-d' );
	}
	
	public function getGlobalVariable($varType) {
		$sqlQuery = "SELECT data_value FROM glb_global_vars WHERE field = \"$varType\" ";
		$sqlQuery = $this->processArray ( $sqlQuery );
		if ($sqlQuery [0] != "")
			return $sqlQuery [0];
		return false;
	}

    protected function getCurrentClassSessionId(){
        return $this->getSessionId4Date($this->getCurrentDate(), 'class');
    }

    public function getSessionId4Date($date, $sessionType){
        $sqlQuery = "SELECT id
						FROM glb_session_details
						WHERE start_date <= \"$date\"
							AND end_date >= \"$date\"
							AND session_type = \"$sessionType\" ";
        $sqlQuery = $this->processArray($sqlQuery);
        if($sqlQuery[0] != '')
            return $sqlQuery[0];
        return false;
    }
}

?>