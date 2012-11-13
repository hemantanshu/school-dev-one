<?php
/**
 * This class will hold the functionalities regarding the different personal info of the user.
 * This class extends the general class
 * @author Hemant Kumar Sah
 * @category Global
 * @license Support-Gurukul
 * @version 1.0.0
 */
require_once 'config.php';
require_once BASE_PATH.'include/global/class.general.php';

class employeeRegistration extends general {

    private $_registrationDetails;

    public function __construct() {
        parent::__construct();
    }

    public function searchEmployeeData($employeeName, $active){
        if($active){
            if($active === 'all')
                $sqlQuery = "SELECT
                                uer.id, uer.employee_id, uer.employee_code
                                FROM utl_employee_registration uer,
                                     utl_personal_info upi
                                WHERE uer.employee_id = upi.id
                                AND (upi.first_name LIKE \"%$employeeName%\"
                                    OR upi.middle_name LIKE \"%$employeeName%\"
                                    OR upi.last_name LIKE \"%$employeeName%\")";
            else
                $sqlQuery = "SELECT
                                uer.id, uer.employee_id, uer.employee_code
                                FROM utl_employee_registration uer,
                                     utl_personal_info upi
                                WHERE uer.employee_id = upi.id
                                AND (upi.first_name LIKE \"%$employeeName%\"
                                    OR upi.middle_name LIKE \"%$employeeName%\"
                                    OR upi.last_name LIKE \"%$employeeName%\")
                                AND uer.active = \"y\" ";
        }else
            $sqlQuery = "SELECT
                                uer.id, uer.employee_id, uer.employee_code
                                FROM utl_employee_registration uer,
                                     utl_personal_info upi
                                WHERE uer.employee_id = upi.id
                                AND (upi.first_name LIKE \"%$employeeName%\"
                                    OR upi.middle_name LIKE \"%$employeeName%\"
                                    OR upi.last_name LIKE \"%$employeeName%\")
                                AND uer.active != \"y\" ";

        return $this->getDataArray($this->processQuery($sqlQuery), 3);
    }
    
    public function getEmployeeIds($active){
    	if($active){
    		if($active === 'all')
    			$sqlQuery = "SELECT employee_id 
    							FROM utl_employee_registration 
    							ORDER BY employee_code ASC ";
    		else
    			$sqlQuery = "SELECT employee_id 
    							FROM utl_employee_registration 
    							WHERE active = \"y\" 
    							ORDER BY employee_code ASC ";    		
    	}else
    		$sqlQuery = "SELECT employee_id 
    						FROM utl_employee_registration 
    						WHERE active != \"y\" 
    						ORDER BY employee_code ASC ";
    	
    	return $this->getDataArray($this->processQuery($sqlQuery));
    }

    public function getEmployeeIds4EmployeeType($employeeType, $active){
        if($active){
            if($active === 'all')
                $sqlQuery = "SELECT employee_id
    							FROM utl_employee_registration
    							WHERE employee_type = \"$employeeType\"
    							ORDER BY employee_code ASC ";
            else
                $sqlQuery = "SELECT employee_id
    							FROM utl_employee_registration
    							WHERE employee_type = \"$employeeType\"
    								AND active = \"y\"
    							ORDER BY employee_code ASC ";
        }else
            $sqlQuery = "SELECT employee_id
    						FROM utl_employee_registration
    						WHERE employee_type = \"$employeeType\"
    							AND active != \"y\"
    						ORDER BY employee_code ASC ";

        return $this->getDataArray($this->processQuery($sqlQuery));
    }

    public function getEmployeeIds4Department($departmentId, $active){
        if($active){
            if($active === 'all')
                $sqlQuery = "SELECT employee_id
    							FROM utl_employee_registration
    							WHERE department_id = \"$departmentId\"		
    							ORDER BY employee_code ASC ";
            else
                $sqlQuery = "SELECT employee_id
    							FROM utl_employee_registration
    							WHERE department_id = \"$departmentId\"
    								AND active = \"y\"
    							ORDER BY employee_code ASC ";
        }else
            $sqlQuery = "SELECT employee_id
    						FROM utl_employee_registration
    						WHERE department_id = \"$departmentId\"
    							AND active != \"y\"
    						ORDER BY employee_code ASC ";

        return $this->getDataArray($this->processQuery($sqlQuery));
    }

    
    public function setEmployeeRegistrationDetails($employeeId, $employeeCode, $applicationId, $joiningDate, $department, $type, $record1, $record2, $record3){
    	$counter = $this->getCounter("employee_registration");
    	$sqlQuery = "INSERT 
    					INTO utl_employee_registration 
    						(id, employee_id, employee_code, application_id, joining_date, department_id, employee_type, record1_id, record2_id, record3_id, last_update_date, last_updated_by, creation_date, created_by, active) 
    					VALUES 
    						(\"$counter\", \"$employeeId\", \"$employeeCode\", \"$applicationId\", \"$joiningDate\", \"$department\", \"$type\", \"$record1\", \"$record2\", \"$record3\", \"" . $this->getCurrentDateTime() . "\", \"" . $this->getLoggedUserId() . "\", \"" . $this->getCurrentDateTime() . "\", \"" . $this->getLoggedUserId() . "\", \"y\") ";
    	$sqlQuery = $this->processQuery($sqlQuery, $counter);
    	if($sqlQuery){
    		$this->logOperation($employeeId, "Registration Details Successfully Inserted");
    		$this->logOperation($counter, "Employee Information Inserted");
    		return $counter;
    	} 
    	return false;    	
    }
    
    public function getEmployeeId4EmployeeCode($employeeCode){
    	$sqlQuery = "SELECT employee_id FROM utl_employee_registration WHERE employee_code = \"$employeeCode\" ";
    	$sqlQuery = $this->processArray($sqlQuery);
    	if($sqlQuery[0] != "")
    		return $sqlQuery[0];
    	return false;
    }
    
    public function getEmployeeRegistrationNumberId($employeeCode){
    	$sqlQuery = "SELECT id FROM utl_employee_registration WHERE employee_code = \"$employeeCode\" ";
    	$sqlQuery = $this->processArray($sqlQuery);
    	
    	if($sqlQuery[0] != "")
    		return $sqlQuery[0];
    	return false;
    }
    
    public function getApplicationIdRegistrationId($applicationId){
    	$sqlQuery = "SELECT id FROM utl_employee_registration WHERE application_id = \"$applicationId\" ";
    	$sqlQuery = $this->processArray($sqlQuery);
    	 
    	if($sqlQuery[0] != "")
    		return $sqlQuery[0];
    	return false;
    }
    
    public function getRegistrationIdDetails($id){
    	return $this->getTableIdDetails($id);
    }
    
    public function getEmployeeRegistrationNumber($employeeId){
    	$sqlQuery = "SELECT employee_code FROM utl_employee_registration WHERE employee_id = \"$employeeId\" ";
    	$sqlQuery = $this->processArray($sqlQuery);
    	return $sqlQuery[0];
    }
    
    public function getEmployeeRegistrationId($employeeId){
    	$sqlQuery = "SELECT id FROM utl_employee_registration WHERE employee_id = \"$employeeId\" ";
    	$sqlQuery = $this->processArray($sqlQuery);
    	 
    	if($sqlQuery[0] != "")
    		return $sqlQuery[0];
    	return false;
    }
    public function commitRegistrationDetailsUpdate($registrationId){
        if ($this->sqlConstructQuery == "")
            return false;

        $this->sqlConstructQuery .= ", last_update_date=\"" . $this->getCurrentDateTime() . "\", last_updated_by=\"" . $this->getLoggedUserId() . "\"";
        $sqlQuery = "UPDATE utl_employee_registration 
                                                    SET $this->sqlConstructQuery 
                                                    WHERE id = \"$registrationId\" ";
        $this->sqlConstructQuery = "";

        if ($this->processQuery($sqlQuery, $registrationId)) {
            $this->logOperation($registrationId, "The Employee Details Has Been Updated");
            return true;
        }
        return false;
    }
    
    public function dropRegistrationDetails($registrationId){
    	if($this->dropTableId($registrationId, false)){
    		$this->logOperation($registrationId, "The registration details has been dropped");
    		return true;
    	}
    	return false;    	
    }
    
    public function activateRegistrationDetails($registrationId){
    	if($this->activateTableId($registrationId)){
    		$this->logOperation($registrationId, "The registration details has been activated");
    		return true;
    	}
    }
}
?>