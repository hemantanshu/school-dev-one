<?php

/**
 * This class will hold the functionalities related to the room details
 * This class extends the general class
 * @author Hemant Kumar Sah
 * @category Global
 * @license Support-Gurukul
 * @version 1.0.0
 */
require_once 'config.php';
require_once BASE_PATH.'include/global/class.loggedInfo.php';

class rooms extends loggedInfo {

    public function __construct() {
        parent::__construct();
    }

    public function getRoomIds($active) {
        if ($active) {
            if ($active === 'all')
                $sqlQuery = "SELECT id 
                                FROM utl_room_details 
                                ORDER BY room_name ASC";
            else
                $sqlQuery = "SELECT id 
                                FROM utl_room_details
                                WHERE active = \"y\"
                                ORDER BY room_name ASC";
        }else
            $sqlQuery = "SELECT id 
                            FROM utl_room_details
                            WHERE active != \"y\"
                            ORDER BY room_name ASC";
        
        return $this->getDataArray($this->processQuery($sqlQuery));
    }

    public function getRoomSearchIds($hint, $active){
        if ($active) {
            if ($active === 'all')
                $sqlQuery = "SELECT id 
                                FROM utl_room_details 
                                WHERE room_name LIKE \"%$hint%\"
                                ORDER BY room_name ASC";
            else
                $sqlQuery = "SELECT id 
                                FROM utl_room_details
                                WHERE active = \"y\"
                                AND room_name LIKE \"%$hint%\"
                                ORDER BY room_name ASC";
        }else
            $sqlQuery = "SELECT id 
                            FROM utl_room_details
                            WHERE active != \"y\"
                            AND room_name LIKE \"%$hint%\"
                            ORDER BY room_name ASC";
        
        return $this->getDataArray($this->processQuery($sqlQuery));
    }
    
    public function getRoomName($roomId){
        return $this->getValue('room_name', 'utl_room_details', 'id', $roomId);
    }
    
    public function getRoomIdDetails($roomId) {
        return $this->getTableIdDetails($roomId);
    }

    public function dropRoomDetails($roomId) {
        if($this->dropTableId($roomId, false)){
            $this->logOperation($roomId, "The Room Details Has Been Dropped");
            return true;
        }
        return false;
    }

    public function activateRoomDetails($roomId) {
        if($this->activateTableId($roomId)){
            $this->logOperation($roomId, "The Room Details Has Been Activated");
            return true;
        }
        return false;
    }

    public function setRoomDetails($roomName, $roomNo, $floor, $builiding, $seating_normal, $seating_exam, $roomType, $description) {
        $counter = $this->getCounter('room');
        $sqlQuery = "INSERT INTO utl_room_details 
                            (id, room_name, room_no, floor_no, building_id, seating_normal, seating_exam, room_type, description, last_update_date, last_updated_by, creation_date, created_by, active) 
                            VALUES (\"$counter\", \"$roomName\", \"$roomNo\", \"$floor\", \"$builiding\", \"$seating_normal\", \"$seating_exam\", \"$roomType\", \"$description\", \"" . $this->getCurrentDateTime() . "\", \"" . $this->getLoggedUserId() . "\", \"" . $this->getCurrentDateTime() . "\", \"" . $this->getLoggedUserId() . "\", \"y\")";
        if ($this->processQuery($sqlQuery, $counter)) {
            $this->logOperation($counter, "New Room Has Been Defined");
            return $counter;
        }
        return false;
    }
    
    public function commitRoomDetailsUpdate($roomId){
        if ($this->sqlConstructQuery == "")
            return $roomId;

        $this->commitUpdate($roomId);
        return false;
    }

}

?>