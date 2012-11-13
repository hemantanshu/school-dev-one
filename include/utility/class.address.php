<?php
/**
 * This class will hold the functionalities related to the address details
 * This class extends the general class
 * @author Hemant Kumar Sah
 * @category Utility
 * @license Support-Gurukul
 * @version 1.0.0
 */
require_once 'config.php';
require_once BASE_PATH . 'include/global/class.loggedInfo.php';
require_once BASE_PATH . 'include/global/class.options.php';

class address extends options {
	
	public function __construct() {
		parent::__construct ();
	}
	
	public function getAddressIdDetails($addressId) {
		return $this->getTableIdDetails ( $addressId );
	}
	
	public function getAddressDisplay($addressId){
		$details = $this->getAddressIdDetails($addressId);
		return $details['street_address'].", ".$details['street_address1'].", ".$this->getOptionIdValue($details['city'])."-".$details['pincode'].", ".$this->getOptionIdValue($details['state']).", ".$this->getOptionIdValue($details['country']);
	}
	
	
	public function dropAddressDetails($addressId) {
		if ($this->dropTableId ( $addressId, false )) {
			$this->logOperation ( $addressId, "The Address Has Been Dropped" );
			return true;
		}
		return false;
	}
	
	public function activateAddressDetails($addressId) {
		if ($this->activateTableId ( $addressId )) {
			$this->logOperation ( $addressId, "The Address Has Been Activated" );
			return true;
		}
		return false;
	}
	
	public function setAddressDetails($house, $street, $city, $state, $country, $pincode) {
		$counter = $this->getCounter ( "address" );
		$sqlQuery = "INSERT 
                        INTO utl_address_details 
                        (id, street_address, street_address1, city, state, country, pincode, last_update_date, last_updated_by,creation_date, created_by, active) 
                        VALUES (\"$counter\", \"$house\", \"$street\", \"$city\", \"$state\", \"$country\", \"$pincode\", \"" . $this->getCurrentDateTime () . "\", \"" . $this->getLoggedUserId () . "\", \"" . $this->getCurrentDateTime () . "\", \"" . $this->getLoggedUserId () . "\", \"y\") ";
		if ($this->processQuery ( $sqlQuery, $counter )) {
			$this->logOperation ( $counter, "The address details has been set" );
			return $counter;
		}
		return false;
	}
	
	public function commitAddressUpdate($id) {
		if ($this->sqlConstructQuery == "")
			return $id;
		
		return $this->commitUpdate($id);
	}

}

?>