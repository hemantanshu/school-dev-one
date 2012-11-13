<?php
error_reporting(0);
session_start ();
/**
 * this class defines the global variables to be used in the entire software
 * all the elements of this class are either private or protected
 * @category Global Configuration
 * @author Hemant Kumar Sah
 * @license Support-Gurukul
 * @version 1.0.0
 */

class config {
	/**
	 * This variable will be used to store the database server path
	 * @var mysqlServer
	 */
	private $_mysqlServer;
	
	/**
	 * This variable will be used to store the database name
	 * @var database
	 */
	private $_databaseName;
	
	/**
	 * This variable will be used to store the database connection username
	 * @var user
	 */
	private $_user;
	
	/**
	 * This variable will be used to used to store the password of the database connection
	 * @var mysqlPassword
	 */
	private $_mysqlUserPassword;
	
	/**
	 * This variable will be used to store the salt for the password hashing
	 * @var salt
	 */
	private $_salt;
	
	/**
	 * This variable will be used to store the session Identifier for the login hashing
	 * @var salt
	 */
	private $_loginUserSessionId;
	
	/**
	 * This variable will be used to give the local datetime of the software installed place
	 * @var timeZone
	 */
	private $_timeZone;

    /**
     * This variable will be used to give the timeout for the login session
     * @var timeZone
     */
    private $_loginSessionTimeOut;
	/**
	 * This method of constructor will be used to initiate the global variables
	 * @method constructor
	 */
	private $_baseServer;
	
	private $_basePath;
	
	protected $_debug;
	
	
	protected function __construct() {
		$this->_mysqlServer == "localhost";
		
		$this->_user = "root";
		
		$this->_mysqlUserPassword = "databasestech09";
		
		$this->_databaseName = "suppor46_school";
		//$this->_databaseName = "suppor46_dpskashi";
		
		$this->_salt = "gurukul";
		
		$this->_loginUserSessionId = "loggedUserAtServer";
		
		$this->_timeZone = "Asia/Calcutta";
		
		date_default_timezone_set ( $this->_timeZone );
		
		$this->_baseServer = "http://localhost/school/";		
		
		$this->_basePath = "c:/xampp/htdocs/school/";
		
		$this->_debug = true;
	}
	
	/**
	 * This method to get the server name
	 */
	protected function getGlobalMysqlServer() {
		return $this->_mysqlServer;
	}
	
	/**
	 * This method to get the user of the server
	 */
	protected function getGlobalMysqlUser() {
		return $this->_user;
	}
	
	/**
	 * This method is used to get the password for the user of the mysql server to access the database
	 */
	protected function getGlobalMysqlUserPassword() {
		return $this->_mysqlUserPassword;
	}
	
	/**
	 * This method is used to get the database to which the software will connect to
	 */
	protected function getGlobalDatabaseName() {
		return $this->_databaseName;
	}
	
	/**
	 * This method is used to get the salt for the passwords used in the encryption of the password
	 */
	protected function getPasswordSalt() {
		return $this->_salt;
	}
	
	/**
	 * This method is used to get the login session identifier 
	 */
	protected function getLoginSessionIdentifier() {
		return $this->_loginUserSessionId;
	}
	
	public function getBaseServer(){
		return $this->_baseServer;
	}
	
	protected function getPath(){
		return $this->_basePath;
	}

    protected function setLoginSessionTimeOut($seconds){
        $this->_loginSessionTimeOut = $seconds;
    }
    public function getLoginSessionTimeOut(){
        return $this->_loginSessionTimeOut;
    }
}
?>