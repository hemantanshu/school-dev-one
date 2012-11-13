<?php
/**
 * This class will be used to process all the sql functions to be used in the software
 * It will handle all the core process regarding handling the sql operations
 * This class extends the configuration file
 * @author Hemant Kumar Sah
 * @category Global Configuration
 * @license Support-Gurukul
 * @version 1.0.0
 */
require_once 'class.config.php';

class sqlFunction extends config {
	/**
	 * @param This variable is used to store the mysql connection resource
	 * @param This variable is used to store the current datetime of the server
	 * @param This variable is used to create the update query statement
	 * @param This variable is used to store the update log which will be set as operation log
	 */
	private $_connection;
	private $_currentDatetime;
	public $sqlConstructQuery;
	protected $updateLog;
	
	/**
	 * This constructor method will be used to retrive the constructor of the previous one
	 * This mehtod will be used to connect to the database
	 */
	protected function __construct() {
		parent::__construct ();
		$this->_connection = mysql_connect ( $this->getGlobalMysqlServer (), $this->getGlobalMysqlUser (), $this->getGlobalMysqlUserPassword () );
		$this->_currentDatetime = date ( 'c' );
	}
	
	/**
	 * This method will be used to process the query. This query wont send the requested query for further syncing with the server
	 * @return Resource id on successful or else returns false if the query fails
	 * @param Var This is the exact sqlquery to be used to send to the server
	 * @param Optional This parameter if is set is to be the unique id, which will uniquely represent the table and the sql has to be a dml 
	 * @param Optional This parameter if set is along with the second parameter which will be the table against which the query is fired
	 */
	protected function processQuery($sqlQuery, $uniqueId = '') {
        mysql_select_db ( $this->getGlobalDatabaseName (), $this->_connection );
        $debugFlag = $this->_debug;        
        if($debugFlag == true){
        	$myFile = $this->getPath()."/programs/logs/sqlDump.txt";
        	$fh = fopen($myFile, 'a') or die("can't open file");
        	$stringData = $sqlQuery."\r ";
        	fwrite($fh, $stringData);
        	fclose($fh);
        }
		$query = mysql_query ( $sqlQuery ) or die ( mysql_error () );
		if ($query && ! empty ( $uniqueId )) {
			$this->insertDataForSyncing ( $uniqueId );
		}
		
		if ($query)
			return $query;
		return false;
	}
	
	/**
	 * This method will be used to return back the array of the query but only for the single row of the query
	 * @return Array of elements of the query if true, false if the query is not successful or not a single row select sql query
	 * @param Var exact sql query so as to return the only one row through this 
	 */
	protected function processArray($sqlQuery) {
		$query = $this->processQuery ( $sqlQuery );
		if ($query)
			return mysql_fetch_array ( $query );
		return false;
	}
	
	/**
	 * This method will be used to return the single element out of the query
	 * @return var The element out of the query
	 * @param var $sqlQuery The sql query to retrieve the single unit
	 */
	
	protected function processSingleElementQuery($sqlQuery){
		$query = $this->processArray($sqlQuery);
		if($query){
			if($query[0] != '')
				return $query[0];
		}
		return false;
	}
	
	/**
	 * This method is used to get the single column out of a single query function whicih includes a single conditional statement
	 * @return Var The single column which was referred as the first parameter to the function
	 * @param Var The column name of which you want
	 * @param Var The table corresponding to that column
	 * @param Var The conditional column which u want to match with the statement column
	 * @param Var The value of that conditional column which will check against that column
	 */
	protected function getValue($column, $tableName, $column_check, $column_value) {
		$sqlQuery = "SELECT $column FROM $tableName WHERE $column_check = \"$column_value\" LIMIT 1 ";
		$sqlQuery = $this->processArray ( $sqlQuery );
		if ($sqlQuery)
			return $sqlQuery [0];
		return false;
	}
	
	/**
	 * This method is used to get the data array of the sql resource, and it is capable of handling multi dimensional array function. It is very much dependent on the table column order and returns the data as the order of the table columns
	 * @return Array It returns the array of the data through the resource file
	 * @param Var The sql resource file which can be iterated to get the data array
	 * @param Optional The dimension of the array, so as to iterate on how many times, as how many columns to be selected through that sql
	 */
	protected function getDataArray($resourceId) {
		$data = array ();
		if (func_num_args () > 1) {
			//handling multi column support for the data array
			$i = 0;
			while ( $result = mysql_fetch_array ( $resourceId ) ) {
				
				$data [$i] = array ();
				for($count = 0; $count < func_get_arg ( 1 ); ++ $count)
					array_push ( $data [$i], $result [$count] );
                ++ $i;
			}
		} else {
			while ( $result = mysql_fetch_array ( $resourceId ) ) {
				//handling the single column function
				array_push ( $data, $result [0] );
			}
		}
		return $data;
	}
	/**
	 * This method is used to store the data required to sync with the global server
	 * @param Var $id The unique id that would represent every table row
	 */
	private function insertDataForSyncing($id) {
		$counter = $this->getCounter ( "data_syncing" );
		$sqlQuery = "INSERT INTO glb_data_syncing (id, generic_id, table_name, datetime_created) VALUES (\"$counter\", \"$id\", \"" . $this->getCounterTable ( $id ) . "\", \"" . date ( 'c' ) . "\")";
		$this->processQuery ( $sqlQuery );
	}
	
	/**
	 * This method is used to get the counter of any field. It returns the useable value. Uses the table counter
	 * @return Var The composite that includes, the starter+value which is unique to every iteration or else false if not found
	 * @param Var The unique identifier of that counter which describes the type of the counter 
	 */
	protected function getCounter($field) {
		$sqlQuery = "LOCK TABLE glb_counters WRITE";
		$this->processQuery ( $sqlQuery );
		
		$query = "SELECT CONCAT(starter, value+1) value FROM glb_counters WHERE field = \"$field\" ";
		if ($query = $this->processQuery ( $query )) {
			if (mysql_num_rows ( $query ) == 1) {
				$query = mysql_fetch_array ( $query );
				
				$counter = $query ['value'];
				$this->updateCounter ( $counter );
				
				$sqlQuery = "UNLOCK TABLE";
				$this->processQuery ( $sqlQuery );
				
				return $counter;
			}
		}
		$sqlQuery = "UNLOCK TABLE";
		$this->processQuery ( $sqlQuery );
		return false;
	}
	
	/**
	 * The method is used to update the counter to the latest value. Its invoked by the getCounter method explicitly. Table affected counter
	 * @return Boolean True|False on the success of the execution of the counter updation
	 * @param Var The counter value which will be analysed and updated on the counter
	 */
	private function updateCounter($counter) {
		$starter = substr ( $counter, 0, 6 ); //the first three characters of the counter
		$length = strlen ( $counter ) - 6; //the length of the value so as to get that value
		

		$counter = substr ( $counter, 6, $length ); //the counter value that has to be updated
		$query = "UPDATE glb_counters SET value = \"$counter\" WHERE starter = \"$starter\" ";
		return $this->processQuery ( $query );
	}
	
	/**
	 * The method is used to get the table name corresponding to a given counter, as every table has a counter associated with it
	 * @return Var Table name of the current database
	 * @param Var The unique counter against which the table has to be found  
	 */
	private function getCounterTable($id) {
		$starter = substr ( $id, 0, 6 ); //finding teh starter of the unique id
		$table = $_SESSION[$starter];
		if($table == ''){
			$sqlQuery = "SELECT table_name FROM glb_counters WHERE starter = \"$starter\" ";
			$sqlQuery = $this->processArray ( $sqlQuery );		
			 
			$_SESSION[$starter] = $sqlQuery[0];
			$table = $sqlQuery[0];
		}		
		return $table;
	}
	
	/**
	 *The method is used to return the array of columns corresponding to a given unique counter id
	 *@return Array The array of elements that is in the order of columsn corresponding to the given table against that counter id
	 *@param Var The unique counter against which the data array has to be returned 
	 */
	public function getTableIdDetails($id) {
		$table = $this->getCounterTable ( $id );
        if ($table == "")
            $table = 'glb_option_flag';
		$sqlQuery = "SELECT * FROM $table WHERE id = \"$id\" ";
		return $this->processArray ( $sqlQuery );
	}
	
	/**
	 * This method is used to construct the set parameters of the update query
	 * @param Var $parameterName The Column Name Which has to be updated, should be exact in accordance with the table name
	 * @param Var $parameterValue The value against that column which is set to be updated
	 */
	public function updateTableParameter($parameterName, $parameterValue) {
		$this->sqlConstructQuery .= $this->sqlConstructQuery == "" ? "$parameterName = \"$parameterValue\" " : ", $parameterName = \"$parameterValue\" ";
	}
	
	/**
	 * @param var $log The log for the update process
	 */	
	public function setUpdateLog($log){
		$this->updateLog .= ' '.$log;
	}
	
	protected function commitUpdate($id){
		if ($this->sqlConstructQuery == "")
			return false;
		
		$tableName = $this->getCounterTable($id);	
		
		$this->sqlConstructQuery .= ", last_update_date=\"" . $this->getCurrentDateTime() . "\", last_updated_by=\"" . $this->getLoggedUserId() . "\"";
		$sqlQuery = "UPDATE $tableName
						SET $this->sqlConstructQuery
						WHERE id = \"$id\" ";
					
		$this->sqlConstructQuery = '';
		
		if ($this->processQuery($sqlQuery, $id)) {
			$this->logOperation($id, "Details Updated ".$this->updateLog);
			$this->updateLog = '';
			return true;
		}
		return false;
	}
	
	
	
	/**
	 * The method is used to drop a single row of the table identified by the unique counter
	 * @param Var $id The unique id against which the row has to be dropped
	 * @param Var $flag The true/false. In case of true the complete row will be deleted / in false the active flag will be set to n
	 * @return Boolean True|False, true on successful execution, false on failure
	 */
	protected function dropTableId($id, $flag) {
		$table = $this->getCounterTable ( $id );
		if ($flag)
			$sqlQuery = "DELETE FROM $table WHERE id = \"$id\" ";
		else
			$sqlQuery = "UPDATE $table SET last_updated_by = \"" . $this->getLoggedUserId () . "\", last_update_date = \"$this->_currentDatetime\", active = \"n\" WHERE id = \"$id\" ";
		
		
        if ($this->processQuery ( $sqlQuery, $id ))
			return true;
		
		return false;
	}
	
	/**
	 * The method is used to activate a single row of the table identified by the unique counter, which had been deactivated
	 * @param Var $id The unique id against which the row has to be activated
	 * @return Boolean TRUE|FALSE, true on successful execution, false on failure
	 */
	
	protected function activateTableId($id) {
		$table = $this->getCounterTable ( $id );
		$sqlQuery = "UPDATE $table SET last_updated_by = \"" . $this->getLoggedUserId () . "\", last_update_date = \"$this->_currentDatetime\", active = \"y\" WHERE id = \"$id\" ";
		if ($this->processQuery ( $sqlQuery, $id ))
			return true;
		
		return false;
	}
	
	/**
	 * The method is used to get the officer id of the active logged user
	 * @return Var The logged User id
	 */
	public function getLoggedUserId() {
	   if(isset($_SESSION[$this->getLoginSessionIdentifier()]))
		  return $_SESSION [$this->getLoginSessionIdentifier ()];
	}
	
	/**
	 * The method is used to insert log of the software operation
	 * @param Var $operationId The operation ID usually the unique id against which any new operation has been performed
	 * @param Var $operation The operation that was performed
	 */
	protected function logOperation($operationId, $operation) {
		$counter = $this->getCounter('userLog');            
		$sqlQuery = "INSERT INTO utl_userlog 
    					(id, operation_id, officer_id, datetime, operation) 
    					VALUES (\"$counter\", \"$operationId\", \"" . $this->getLoggedUserId () . "\", \"$this->_currentDatetime\", \"$operation\")";
		$this->processQuery($sqlQuery, $counter);
    }
    
    /**
     * The destruct method used to close the mysql connection 
     */
    public function __destruct(){
    	if ($this->_connection)
    		mysql_close($this->_connection);
    }
    
}
?>