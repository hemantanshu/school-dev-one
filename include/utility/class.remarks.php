<?php
/**
 * This class will hold the remarks functionalities to be used throughout the software
 * This class extends the sqlFunction class
 * @author Hemant Kumar Sah
 * @category Global
 * @license Support-Gurukul
 * @version 1.0.0
 */
require_once 'config.php';
require_once BASE_PATH.'include/global/class.general.php';

class remarks extends general {
	
	public function __construct() {
		parent::__construct ();
	}
	
	// functions related to the remarks
	public function getRemarkIdDetails($id) {
		return $this->getTableIdDetails ( $id );
	}
	
	public function setRemark($genericId, $remark, $repeatFlag) {
		if ($repeatFlag) {
			$counter = $this->getCounter ( 'remarks' );
			$sqlQuery = "INSERT INTO utl_remarks (id, remarks, remark_type, last_update_date, last_updated_by, creation_date, created_by, active)
			VALUES (\"$counter\", \"" . htmlspecialchars ( $remark ) . "\", \"$genericId\", \"" . $this->getCurrentDateTime () . "\", \"" . $this->getLoggedUserId () . "\", \"" . $this->getCurrentDateTime () . "\", \"" . $this->getLoggedUserId () . "\", \"y\")";
			if ($this->processQuery ( $sqlQuery, $counter )) {
				$this->logOperation ( $genericId, "new remark has been posted" );
				return $counter;
			}
		} else {
			$remarkId = $this->getRemarkId ( $genericId, 1 );
			if (sizeof ( $remarkId, 0 )) {
				$sqlQuery = "UPDATE utl_remarks SET remarks = \"" . htmlspecialchars ( $remark ) . "\" WHERE id = \"$remarkId[0]\" ";
				if ($this->processQuery ( $sqlQuery, $remarkId [0] )) {
					$this->logOperation ( $remarkId, "The remark has been updated" );
					return $remarkId [0];
				}
			} else {
				$counter = $this->getCounter ( 'remarks' );
				$sqlQuery = "INSERT INTO utl_remarks (id, remarks, remark_type, last_update_date, last_updated_by, creation_date, created_by, active)
				VALUES (\"$counter\", \"" . htmlspecialchars ( $remark ) . "\", \"$genericId\", \"" . $this->getCurrentDateTime () . "\", \"" . $this->getLoggedUserId () . "\", \"" . $this->getCurrentDateTime () . "\", \"" . $this->getLoggedUserId () . "\", \"y\")";
				if ($this->processQuery ( $sqlQuery, $counter )) {
					$this->logOperation ( $genericId, "new remark has been posted" );
					return $counter;
				}
			}
		}
	}
	
	public function getRemarkId($genericId, $active) {
		if ($active) {
			if ($active === 'all')
				$sqlQuery = "SELECT id FROM utl_remarks WHERE remark_type = \"$genericId\" ";
			else
				$sqlQuery = "SELECT id FROM utl_remarks WHERE remark_type = \"$genericId\" AND active = \"y\"";
		} else
			$sqlQuery = "SELECT id FROM utl_remarks WHERE remark_type = \"$genericId\" AND active != \"y\"";
		
		return $this->getDataArray ( $this->processQuery ( $sqlQuery ) );
	}
	
	public function getGenericRemarkValue($genericId){
		$sqlQuery = "SELECT remarks FROM utl_remarks WHERE remark_type = \"$genericId\" ";
		$sqlQuery = $this->processArray($sqlQuery);
		
		return htmlspecialchars_decode($sqlQuery[0]);
	}
	
	public function getRemarkIdValue($remarkId){
		$sqlQuery = "SELECT remarks FROM utl_remarks WHERE id = \"$remarkId\" ";
		$sqlQuery = $this->processArray($sqlQuery);
		return htmlspecialchars_decode($sqlQuery[0]);
	}
}
?>









