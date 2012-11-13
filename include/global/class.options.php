<?php

/**
 * This class will hold the functionalities regarding the different options and the option assignment.
 * This class extends the general class
 * @author Hemant Kumar Sah
 * @category Global
 * @license Support-Gurukul
 * @version 1.0.0
 */
require_once 'class.general.php';

class options extends general {

    public function __construct() {
        parent::__construct();
    }

    /////////////////// Start Of The Functions Related To The Options DDLs' ////////////////////////	
    /**
     * The method is used to set new option. It is capable of taking the value and if already present would return the option id rather than inserting into the base table
     * @return Var OptionId|False The optionId as given against the new option value to set, False on failure
     * @param Var The new value that has to be inserted into the base table
     * @param Var The type of the option that has to be inserted into the base table
     */
    public function setNewOptionValue($optionValue, $optionType, $flag = '') {
        if($optionValue == '')
            return null;        
    	$counter = $this->checkOptionValue($optionValue, $optionType); 
    	if(!$counter){
    		if(empty($flag))
    			$counter = $this->getCounter("options");
    		else
    			$counter = $this->getCounter("reservedOptions");
    		$sqlQuery = "INSERT 
    						INTO glb_option_values
    						(id, value_name, option_type, creation_date, created_by, last_update_date, last_updated_by, active)
    						VALUES (\"$counter\", \"" . $optionValue . "\", \"$optionType\", \"" . $this->getCurrentDateTime() . "\", \"" . $this->getLoggedUserId() . "\", \"" . $this->getCurrentDateTime() . "\", \"" . $this->getLoggedUserId() . "\", \"y\")";
    		if ($this->processQuery($sqlQuery, $counter)) {
    			$this->logOperation($counter, "New Option Has Been Set & Defined");
    		return $counter;
    		}
    		return false;
    	}
    	return $counter;    	
    }

    /**
     * The method is used to update the option value.
     * @param Var $optionId|False The option id against which the update has to be done, false on failure	 
     * @return Boolean True|False True on successful operation, false on failure
     */
    public function commitOptionValueUpdate($id) {
        if ($this->sqlConstructQuery == "")
            return false;

        return $this->commitUpdate($id);
    }

    /**
     * The Method is used to drop the option
     * @param Var $id The option id which has to be dropped
     * @return Boolean True|False True on successful operation, false on failure
     */
    public function dropOptionID($id) {
        if ($this->dropTableId($id, false)) {
            $this->logOperation($id, "The option value has been dropped");
            return true;
        }
        return false;
    }

    /**
     * The method is used to activate the disabled option
     * @param $id The dropped it against which it has to be activated
     * @return Boolean True|False True on successful operation, false on failure
     */
    public function activateOptionID($id) {
        if ($this->activateTableId($id)) {
            $this->logOperation($id, "The Option value has been activated");
            return true;
        }
        return false;
    }

    /////////////////// End Of The Functions Related To The Options DDLs' //////////////////////////
    /////////////////// Start of the functions related to the options DQLs' ////////////////////////
    /**
     * The method is used to get the option ids to be displayed generally in the page
     * @return Array The option ids for the given option type
     * @param Var $optionType The option type against which the id is to be populated
     * @param Flag $active True|False|All|Null If true is passed then all the active options, False then inactive options and in All or Null, all the option ids are returned
     * @param Num $page The pager on which page view is required by cutting down the returning list
     * @param Num $totalView The size of the each return which by default takes 100 if not passed on
     */
    public function getOptionValueIds($optionType, $active = '', $page = '', $totalView = '') {
        $pager = empty($page) || !is_numeric($page) ? 0 : $page - 1;
        $view = empty($totalView) || !is_numeric($totalView) ? 100 : $totalView;

        $startLimit = $view * $pager;

        if (empty($active) || $active) {
            if ($active == 'all')
            //the value set for all the options, when the active attribute is set to all
                $sqlQuery = "SELECT id 
								FROM glb_option_values 
								WHERE option_type = \"$optionType\" 
									ORDER BY value_name ASC 
									LIMIT $startLimit, $view";
            else
            //the value set for the active options, when either active attribute is set to true or not set 
                $sqlQuery = "SELECT id 
								FROM glb_option_values 
								WHERE option_type = \"$optionType\" && active = \"y\" 
									ORDER BY value_name ASC 
									LIMIT $startLimit, $view";
        } else
        //the value set for all the inactive options, when the active attribute is set to false
            $sqlQuery = "SELECT id 
							FROM glb_option_values 
							WHERE option_type = \"$optionType\" && active != \"y\" 
								ORDER BY value_name ASC 
								LIMIT $startLimit, $view";
        return $this->getDataArray($this->processQuery($sqlQuery));
    }

    /**
     * The Method is used to get the total count of entries present in the database
     * @return Number The total count of the entries
     * @param Var $optionType The option type against which the count has to be populated
     * @param Flag $active True|False|All|Null If true is passed then all the active options, False then inactive options and in All or Null, all the option ids are returned 
     */
    public function getOptionValueIdCount($optionType, $active = '') {
        if (empty($active) || $active) {
            if ($active == 'all')
            //the value set for all the options, when the active attribute is set to all
                $sqlQuery = "SELECT COUNT(id) 
								FROM glb_option_values 
								WHERE option_type = \"$optionType\" ";
            else
            //the value set for the active options, when either active attribute is set to true or not set 
                $sqlQuery = "SELECT COUNT(id) 
								FROM glb_option_values 
								WHERE option_type = \"$optionType\" && active = \"y\" ";
        } else
        //the value set for all the inactive options, when the active attribute is set to false
            $sqlQuery = "SELECT COUNT(id) 
							FROM glb_option_values 
							WHERE option_type = \"$optionType\" && active != \"y\" ";

        $sqlQuery = $this->processArray($sqlQuery);
        return $sqlQuery [0];
    }

    /**
     * The method is used to get the ids against the search string, usually to be used in ajax calls for autocompletion
     * @return Array The ids of those values against which the search string was required
     * @param Var $searchString The string that has to be searched against the value inserted inside
     * @param Var $optionType The option type against which the value set is required
     */
    public function getOptionSearchValueIds($searchString, $optionType, $active) {
    	if($active){
    		if($active == 'all')
    			$sqlQuery = "SELECT id
    							FROM glb_option_values
    							WHERE value_name LIKE \"%$searchString%\" 
    								AND option_type = \"$optionType\" 
    							ORDER BY value_name ASC";
    		else 
    			$sqlQuery = "SELECT id
    							FROM glb_option_values
    							WHERE value_name LIKE \"%$searchString%\" 
    								AND option_type = \"$optionType\" 
    								AND active = \"y\"
    							ORDER BY value_name ASC";
    	}else{
    		$sqlQuery = "SELECT id
    						FROM glb_option_values
    						WHERE value_name LIKE \"%$searchString%\" 
    							AND option_type = \"$optionType\" 
    							AND active != \"y\"
    						ORDER BY value_name ASC";
    	}    
        return $this->getDataArray($this->processQuery($sqlQuery));
    }
    
    public function checkOptionValue($value, $optionType){
    	$sqlQuery = "SELECT id
    					FROM glb_option_values
    					WHERE UPPER(value_name) = \"".strtoupper(trim($value))."\"
    					AND option_type = \"$optionType\" ";
    	$sqlQuery = $this->processArray($sqlQuery);
    	if($sqlQuery[0] == "")
    		return false;
    	return $sqlQuery[0];
    }

    /**
     * The listing of the entire array of values in the columns
     * @return Array The array of values of the column of the table represented by the unique id
     * @param Var $optionId The option id against which the details are required
     */
    public function getOptionIdDetails($optionId) {
        return $this->getTableIdDetails($optionId);
    }

    /**
     * The method gives back the value associated with that option id
     * @return Var The value which is represented by that option id
     * @param Var $optionId The option id against which the value is required
     */
    public function getOptionIdValue($optionId) {
        return $this->getValue('value_name', 'glb_option_values', 'id', $optionId);
    }
        
    /**
     * The method is used to get the option id agains the given value set
     * @return Var The unique option id
     * @param Var $optionValue The value against which the value is required
     * @param Var $optionType The type of option
     */
    public function getOptionValueId($optionValue, $optionType) {
        $sqlQuery = "SELECT id 
						FROM glb_option_values 
						WHERE value_name = \"$optionValue\" && option_type = \"$optionType\" && active = \"y\" 
						LIMIT 1 ";
        $sqlQuery = $this->processArray($sqlQuery);
        return $sqlQuery [0];
    }

    /////////////////// End Of The Functions Related To The Options DQLs' //////////////////////////
    /////////////////// Start Of The Functions Related To The Assignment DDLs' /////////////////////
    /**
     * The method is used to assign new assignment, generic one
     * @return Var|False The newly created assignment id or false on unsuccessful attempt
     * @param Var $genericId The Generic Id Agaist which the assignment has to be done
     * @param Var $valueId The optionid/valueid for that assignment
     * @param Var $assignmentType The type of the assignment
     */
    public function setNewAssignment($genericId, $valueId, $assignmentType) {
    	$counter = $this->getAssignmentId($genericId, $valueId, $assignmentType);
    	if(!$counter){
    		$counter = $this->getCounter("assignment");
    		$sqlQuery = "INSERT INTO glb_option_assignments
				    		(id, generic_id, value_set, assignment_type, creation_date, created_by, last_update_date, last_updated_by, active)
				    		VALUES (\"$counter\", \"$genericId\", \"$valueId\", \"$assignmentType\", \"" . $this->getCurrentDateTime() . "\", \"" . $this->getLoggedUserId() . "\", \"" . $this->getCurrentDateTime() . "\", \"" . $this->getLoggedUserId() . "\", \"y\")";
    		if ($this->processQuery($sqlQuery, $counter)) {
	    		$this->logOperation($genericId, "New Assignment Has Been Added");
	    		return $counter;
    		}	
    	}        
        return $counter;
    }

    /**
     * The method is used to drop the assignment	 
     * @param Var $assignmentId The assignment id which has to be dropped
     * @return Boolean True|False True on successful operation, false on failure
     */
    public function dropAssignment($assignmentId) {
        $this->dropTableId($assignmentId, false);
        $this->logOperation($assignmentId, "The Assignment Has Been Dropped");
    }

    /**
     * The method is used to activate an inactive assignment	 
     * @param Var $assignmentId The assignment id that has to be activated
     * @return Boolean True|False True on successful operation, false on failure
     */
    public function activateAssignment($assignmentId) {
        $this->activateTableId($assignmentId);
        $this->logOperation($assignmentId, "The Assignment Has Been Activated");
    }

    /////////////////// End Of The Functions Related To The Assignment DDLs' ////////////////////////
    /////////////////// Start Of The Functions Related To The Options DQLs' /////////////////////////
    /**
     * The method is used to get the assignment ids against a generic id. 
     * @return Var|False The assignment IDs on successful execution, false on failure
     * @param Var $genericId The generic id against which the assignment id has to be populated
     * @param Var $assignmentType The Assignment Type for which the id is required
     * @param Flag $active True|False|All|Null. True for all active ones, False for all inactivae ones All for all of them. If not passed All Is defaulted 
     * @param Number $page The page count of the view, if not passed first page is defaulted
     * @param Number $totalView The count of the view, if not passed 100 is defaulted
     * @param Flag $reverse True|False|Null If reverse flag is set to True, then the assignment id against the value_set is populated else normal behaviour is observed
     */
    public function getAssignmentIds($genericId, $assignmentType, $active = '', $page = '', $totalView = '', $reverse = '') {
        $pager = empty($page) || !is_numeric($page) ? 0 : $page - 1;
        $view = empty($totalView) || !is_numeric($totalView) ? 100 : $totalView;
        $columnName = !empty($reverse) ? 'value_set' : 'generic_id';
        $startLimit = $view * $pager;

        if (empty($active) || $active) {
            if ($active === 'all')
            //the value set for all the assignments, when the active attribute is set to all
                $sqlQuery = "SELECT id 
								FROM glb_option_assignments 
								WHERE $columnName = \"$genericId\" && assignment_type = \"$assignmentType\" 
									LIMIT $startLimit, $view";
            else
            //the value set for all the active assignments, when the active attribute is set to all
                $sqlQuery = "SELECT id 
								FROM glb_option_assignments 
								WHERE $columnName = \"$genericId\" && assignment_type = \"$assignmentType\" && active = \"y\" 
									LIMIT $startLimit, $view";
        } else
        //the value set for all the inactive assignments, when the active attribute is set to false
            $sqlQuery = "SELECT id 
							FROM glb_option_assignments 
							WHERE $columnName = \"$genericId\" && assignment_type = \"$assignmentType\" && active != \"y\"
								LIMIT $startLimit, $view";
        
                       
        return $this->getDataArray($this->processQuery($sqlQuery));
    }
    
    public function getAssignmentId($genericId, $valueId, $assignmentType){
    	$sqlQuery = "SELECT id FROM glb_option_assignments WHERE generic_id = \"$genericId\" AND value_set = \"$valueId\" AND assignment_type = \"$assignmentType\" AND active = \"y\" ";
    	$sqlQuery = $this->processArray($sqlQuery);
    	if($sqlQuery[0] == "")
    		return $sqlQuery[0];
    	return false;
    }

    /**
     * The method is used to get teh assignment id count against the given inputs
     * @return Number The count of the assignment ID
     * @param Var $genericId The generic id against which the assignment id has to be populated
     * @param Var $assignmentType The Assignment Type for which the id is required
     * @param Var $active True|False|All|Null True for all active ones, False for all inactivae ones All for all of them. If not passed All Is defaulted
     * @param Flag $reverse True|False|Null If reverse flag is set to True, then the assignment id against the value_set is populated or else normal behavioiur is observed
     */
    public function getAssignmentIdCount($genericId, $assignmentType, $active = '', $reverse = '') {
        $columnName = !empty($reverse) ? 'value_set' : 'generic_id';

        if (empty($active) || $active) {
            if ($active == 'all')
            //the value set for all the assignments, when the active attribute is set to all
                $sqlQuery = "SELECT COUNT(id) 
								FROM glb_option_assignments 
								WHERE $columnName = \"$genericId\" && assignment_type = \"$assignmentType\" ";
            else
            //the value set for all the active assignments, when the active attribute is set to all
                $sqlQuery = "SELECT COUNT(id) 
								FROM glb_option_assignments 
								WHERE $columnName = \"$genericId\" && assignment_type = \"$assignmentType\" && active = \"y\" ";
        } else
        //the value set for all the inactive assignments, when the active attribute is set to false
            $sqlQuery = "SELECT COUNT(id) 
							FROM glb_option_assignments 
							WHERE $columnName = \"$genericId\" && assignment_type = \"$assignmentType\" && active != \"y\" ";

        $sqlQuery = $this->processArray($sqlQuery);
        return $sqlQuery [0];
    }

    /**
     * The method is used to get the complete column array against a given assignment ID
     * @return Array The array of column elements in the order as per the table
     * @param Var $assignmentId The assignment ID against which the details has been sought
     */
    public function getAssignmentIdDetails($assignmentId) {
        return $this->getTableIdDetails($assignmentId);
    }

    /**
     * The method is used to get the assignment ID Value capable of returning both the generic id as well as the value_set depending upon the reverse input
     * @return Array The value_set if the reverse flag is not set else the generic id if the reverse flag is set to True
     * @param Var $assignmentId The assignment id against which the value has been sought
     * @param Flag $reverse True|False|Null If reverse flag is set to True, then the assignment id against the value_set is populated else normal behaviour is followed
     */
    public function getAssignmentIdValue($assignmentId, $reverse = '') {
        if (empty($reverse))
            return $this->getValue('value_set', 'glb_option_assignments', 'id', $assignmentId);
        return $this->getValue('generic_id', 'glb_option_assignments', 'id', $assignmentId);
    }

    /////////////////// End Of The Functions Realted To The Options DQLs' ///////////////////////////
    ///////////////////start of the functions related to the creation of the new option type ///////////

    public function setOptionType($optionName, $shortCode, $description) {
        $sqlQuery = "SELECT id 
        				FROM glb_option_flag 
        				WHERE flag = \"$shortCode\" ";
        $sqlQuery = $this->processQuery($sqlQuery);
        if (mysql_num_rows($sqlQuery))
            return 0;
        $counter = $this->getCounter('optionType');
        $sqlQuery = "INSERT 
                            INTO glb_option_flag 
                                (id, flag, comments, description, last_update_date, last_updated_by, creation_date, created_by, active) 
                                VALUES (\"$counter\", \"".strtoupper($shortCode)."\", \"$optionName\", \"$description\", \"" . $this->getCurrentDateTime() . "\", \"" . $this->getLoggedUserId() . "\", \"" . $this->getCurrentDateTime() . "\", \"" . $this->getLoggedUserId() . "\", \"y\")";
        if ($this->processQuery($sqlQuery, $counter)) {
            $this->logOperation($counter, "New Option Type Has Been Defined");
            return $counter;
        }
        return false;
    }
    
    public function checkShortCode($shortCode){
    	$sqlQuery = "SELECT id 
    					FROM glb_option_flag 
    					WHERE flag = \"$shortCode\" ";
    	$sqlQuery = $this->processArray($sqlQuery);
    	if($sqlQuery[0] == ""){
    		return true;
    	}
    	return false;
    }
    
    public function searchOptionFlag($string, $active){
    	$str = strtoupper($string);
    	if($active){
    		if($active === 'all')
    			$sqlQuery = "SELECT id FROM glb_option_flag WHERE (flag LIKE \"%$str%\" OR UPPER(comments) LIKE \"%$str%\") ORDER BY flag DESC";    		
    		else
    			$sqlQuery = "SELECT id FROM glb_option_flag WHERE (flag LIKE \"%$str%\" OR UPPER(comments) LIKE \"%$str%\") AND active = \"y\" ORDER BY flag DESC";
    	}else
    		$sqlQuery = "SELECT id FROM glb_option_flag WHERE (flag LIKE \"%$str%\" OR UPPER(comments) LIKE \"%$str%\") AND active != \"y\" ORDER BY flag DESC";  	
    	
    	
    	return $this->getDataArray($this->processQuery($sqlQuery));
    }

    public function getOptionTypeIds($active = '') {
        if (empty($active) || $active) {
            if ($active == 'all')
            //the value set for all the assignments, when the active attribute is set to all
                $sqlQuery = "SELECT id 
                                                FROM glb_option_flag 
                                                ORDER BY flag";
            else
            //the value set for all the active assignments, when the active attribute is set to all
                $sqlQuery = "SELECT id 
                                                FROM glb_option_flag 
                                                WHERE active  = \"y\"
                                                ORDER BY flag";
        } else
        //the value set for all the inactive assignments, when the active attribute is set to false
            $sqlQuery = $sqlQuery = "SELECT id 
                                                FROM glb_option_flag 
                                                WHERE active != \"y\"
                                                ORDER BY flag";

        return $this->getDataArray($this->processQuery($sqlQuery));
    }

    public function getOptionTypeIdDetails($optionId) {
        return $this->getTableIdDetails($optionId);
    }

    public function commitOptionTypeUpdate($id) {
        if ($this->sqlConstructQuery == "")
            return false;

        return $this->commitUpdate($id);
    }
    
    /**
     * The method is used to drop the option type
     * @param Var $optionId The option type which has to be dropped
     * @return Boolean True|False True on successful operation, false on failure
     */
    public function dropOptionType($optionId) {
    	$this->dropTableId($optionId, false);
    	$this->logOperation($optionId, "The option type Has Been Dropped");
    }
    
    /**
     * The method is used to activate an inactive option type
     * @param Var $assignmentId The assignment id that has to be activated
     * @return Boolean True|False True on successful operation, false on failure
     */
    public function activateOptionTYpe($optionId) {
    	$this->activateTableId($optionId);
    	$this->logOperation($optionId, "The option type Has Been Activated");
    }

    /////////////////// end of the functions related to the creation of the new option type ///////////
}

?>