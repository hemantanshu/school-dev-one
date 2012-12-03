<?php

require_once 'class.sqlFunction.php';
require_once 'class.menuTask.php';
require_once 'class.notification.php';
require_once '../utility/class.sections.php';
require_once '../exam/class.grading.php';
require_once '../exam/class.resultTypeEntry.php';



class testing extends sqlFunction {
	private $_menuTask, $_notification, $_section, $_grading, $_resultType;
	
	public function __construct() {
		parent::__construct ();
		$this->_menuTask = new MenuTask();
		$this->_notification = new Notification();
		$this->_section = new sections();
		$this->_grading = new Grading();
		$this->_resultType = new ResultTypeEntry();
	}
	
	public function getCandidateAmnNos(){
		$sqlQuery = "SELECT admno FROM candidate_new a where not exists (select 1 from utl_candidate_registration b where a.admno = b.registration_number)";
		return $this->getDataArray($this->processQuery($sqlQuery));
	}
	
	public function getAdmNoDetails($admNo){
		$sqlQuery = "SELECT * FROM candidate_new where admno = \"$admNo\" ";
		return $this->processArray($sqlQuery);
	}
	
	
	public function correctDOB(){
		$sqlQuery = "SELECT * FROM candidate_new";
		$query = $this->processQuery($sqlQuery);
		while($result = mysql_fetch_array($query)){
			$admNo = $result['admno'];
			$admDate = $result['dob'];
			
			$admDate = $admDate == '' ? '10.10.80' : $admDate;
			
			$date = explode('.', $admDate);
			$year = strlen($date[2]) == 2 ? '20'.$date[2] : $date[2];
			$month = strlen($date[1]) == 1 ? '0'.$date[1] : $date[1];
			$day = strlen($date[0]) == 1 ? '0'.$date[0] : $date[0];
			
			$date = $year."-".$month."-".$day;
			
			$sqlQuery = "UPDATE candidate_new set dob_new = \"$date\" where admno = \"$admNo\" ";
			$this->processQuery($sqlQuery);
			echo $admDate."==>".$date."<br />";
		}
	}
	
	public function correctName(){
		$sqlQuery = "SELECT * FROM candidate_new";
		$query = $this->processQuery($sqlQuery);
		while($result = mysql_fetch_array($query)){
			$lName = $mName = $fName = "";
			$admNo = $result['admno'];
			$name = $result['name'];

			$splitName = explode(' ', $name);
			$size = count($splitName);
			if($size == 1){
				$fName = $splitName[0];
			}elseif($size == 2){
				$fName = $splitName[0];
				$lName = $splitName[1];
			}else{
				$fName = $splitName[0];
				$mName = $splitName[1];
				$lName = $splitName[2];				
			}							
			$sqlQuery = "UPDATE candidate_new set first_name = \"$fName\", middle_name = \"$mName\", last_name = \"$lName\" where admno = \"$admNo\" ";
			$this->processQuery($sqlQuery);
			echo $name."==>".$lName.", ".$fName." ".$mName."<br />";
		}
	}
	
	public function splitFatherSalutation(){
		$sqlQuery = "SELECT * FROM candidate_new";
		$query = $this->processQuery($sqlQuery);
		while($result = mysql_fetch_array($query)){
			$lName = $mName = $fName = $salutation = "";
			$admNo = $result['admno'];
			$name = trim($result['father']);
		
			$splitName = explode('.', $name);
			$size = count($splitName);
			if($size == 1){
				$fName = trim($splitName[0]);
			}else{
				$salutation = trim($splitName[0]);
				$fName = trim($splitName[1]);
			}
			$sqlQuery = "UPDATE candidate_new set father_name = \"$fName\", salutation = \"$salutation\" where admno = \"$admNo\" ";
			$this->processQuery($sqlQuery);
			echo $name."==>".$salutation.", ".$fName." ".$mName."<br />";
		}
	}
	
	public function correctFatherName(){
		$sqlQuery = "SELECT * FROM candidate_new";
		$query = $this->processQuery($sqlQuery);
		while($result = mysql_fetch_array($query)){
			$lName = $mName = $fName = "";
			$admNo = $result['admno'];
			$name = trim($result['father_name']);
	
			$splitName = explode(' ', $name);
			
			$size = count($splitName);
			if($size == 1){
				$fName = $splitName[0];
			}elseif($size == 2){
				$fName = $splitName[0];
				$lName = $splitName[1];
			}else{
				$fName = $splitName[0];
				$mName = $splitName[1];
				$lName = $splitName[2];
			}
			$sqlQuery = "UPDATE candidate_new set f_first_name = \"$fName\", f_middle_name = \"$mName\", f_last_name = \"$lName\" where admno = \"$admNo\" ";
			$this->processQuery($sqlQuery);
			echo $size."  ".$name."==>".$lName.", ".$fName." ".$mName."<br />";
		}
	}
	
	public function splitMotherSalutation(){
		$sqlQuery = "SELECT * FROM candidate_new";
		$query = $this->processQuery($sqlQuery);
		while($result = mysql_fetch_array($query)){
			$lName = $mName = $fName = $salutation = "";
			$admNo = $result['admno'];
			$name = trim($result['mother']);
	
			$splitName = explode('.', $name);
			$size = count($splitName);
			if($size == 1){
				$fName = trim($splitName[0]);
			}else{
				$salutation = trim($splitName[0]);
				$fName = trim($splitName[1]);
			}
			$sqlQuery = "UPDATE candidate_new set mother_name = \"$fName\", m_salutation = \"$salutation\" where admno = \"$admNo\" ";
			$this->processQuery($sqlQuery);
			echo $name."==>".$salutation.", ".$fName." ".$mName."<br />";
		}
	}
	
	public function correctMotherName(){
		$sqlQuery = "SELECT * FROM candidate_new";
		$query = $this->processQuery($sqlQuery);
		while($result = mysql_fetch_array($query)){
			$lName = $mName = $fName = "";
			$admNo = $result['admno'];
			$name = trim($result['mother_name']);
	
			$splitName = explode(' ', $name);
				
			$size = count($splitName);
			if($size == 1){
				$fName = $splitName[0];
			}elseif($size == 2){
				$fName = $splitName[0];
				$lName = $splitName[1];
			}else{
				$fName = $splitName[0];
				$mName = $splitName[1];
				$lName = $splitName[2];
			}
			$sqlQuery = "UPDATE candidate_new set m_first_name = \"$fName\", m_middle_name = \"$mName\", m_last_name = \"$lName\" where admno = \"$admNo\" ";
			$this->processQuery($sqlQuery);
			echo $size."  ".$name."==>".$lName.", ".$fName." ".$mName."<br />";
		}
	}
	
	public function correctAddress(){
		$sqlQuery = "SELECT * FROM candidate_new";
		$query = $this->processQuery($sqlQuery);
		while($result = mysql_fetch_array($query)){
			
			$admNo = $result['admno'];
			$name = trim($result['address']);
		
			$splitName = explode(',', $name);
		
			$size = count($splitName);
			
			//$sqlQuery = "UPDATE candidate_new set m_first_name = \"$fName\", m_middle_name = \"$mName\", m_last_name = \"$lName\" where admno = \"$admNo\" ";
			//$this->processQuery($sqlQuery);
			print_r($splitName);
			echo $size."  ".$name."<br />";
		}
	}
	
	public function correctMobileNo(){
		$sqlQuery = "SELECT * FROM candidate_new";
		$query = $this->processQuery($sqlQuery);
		while($result = mysql_fetch_array($query)){
				
			$admNo = $result['admno'];
			$name = trim($result['mobile']);
		
			$splitName = explode(',', $name);
		
			$size = count($splitName);
				
			//$sqlQuery = "UPDATE candidate_new set m_first_name = \"$fName\", m_middle_name = \"$mName\", m_last_name = \"$lName\" where admno = \"$admNo\" ";
			//$this->processQuery($sqlQuery);
			print_r($splitName);
			echo $size."  ".$name."<br />";
		}
	}
	
	public function correctExaminationDates(){
		$sqlQuery = "SELECT * FROM exam_examination_dates where actual_mark_submission_date = \"0000-00-00 00:00:00\"  ";
		$query = $this->processQuery($sqlQuery);
		while($result = mysql_fetch_array($query)){
			$sqlQuery = "UPDATE exam_examination_dates SET mark_submission_date = \"2012-07-29\", mark_verification_date = \"2012-07-31\" WHERE id = \"".$result['id']."\" ";
			$this->processQuery($sqlQuery);
			
			$submitId = $result[0]."-S";
			$sqlQuery = "UPDATE glb_menu_task_assignment SET end_date = \"2012-07-29\" WHERE id = \"$submitId\"";
			$this->processQuery($sqlQuery);
			
			$submitId = $result[0]."-V";
			$sqlQuery = "UPDATE glb_menu_task_assignment SET end_date = \"2012-07-31\" WHERE id = \"$submitId\"";
			$this->processQuery($sqlQuery);		
		}
		
		$sqlQuery = "SELECT * FROM exam_mark_records";
		$query = $this->processQuery($sqlQuery);
		while($result = mysql_fetch_array($query)){
			$sqlQuery = "UPDATE exam_mark_records SET submission_officer_id = \"".$result['last_updated_by']."\", submission_date = \"".$result['last_update_date']."\", submitted_mark = \"".$result['absolute_mark']."\" WHERE id = \"".$result['id']."\" ";
			$this->processQuery($sqlQuery);
		}
		
		
	}
	
	public function correctTaskName(){
		$sqlQuery = "SELECT * FROM exam_examination_dates";
		$query = $this->processQuery($sqlQuery);
		while($result = mysql_fetch_array($query)){
			$subjectDetails = $this->getTableIdDetails($result['subject_id']);
			$sectionDetails = 	$this->getTableIdDetails($result['section_id']);
			$classDetails = $this->getTableIdDetails($result['class_id']);
			$classDetails = $this->getTableIdDetails($classDetails['class_id']);
			
			$submissionName = "Submission : ".$classDetails['class_name']." ".$sectionDetails['section_name']." ".$subjectDetails['subject_code']." ".$subjectDetails['subject_name'];
			echo $submissionName."<br />";
			
			$verificationName = "Verification : ".$classDetails['class_name']." ".$sectionDetails['section_name']." ".$subjectDetails['subject_code']." ".$subjectDetails['subject_name'];
			echo $verificationName."<br /><hr />";
			$examinationId = $result['id']."-S";
			$sqlQuery = "UPDATE glb_menu_task_assignment SET menu_display_name = \"$submissionName\" WHERE source_id = \"$examinationId\"";
			$this->processQuery($sqlQuery);
			
			$examinationId = $result['id']."-V";
			$sqlQuery = "UPDATE glb_menu_task_assignment SET menu_display_name = \"$verificationName\" WHERE source_id = \"$examinationId\"";
			$this->processQuery($sqlQuery);
				
		}
		
		
	}
	
	public function correctSectionName(){
		$sqlQuery = "SELECT * FROM utl_candidate_classes";
		$query = $this->processQuery($sqlQuery);
		while($result = mysql_fetch_array($query)){
			$candidateId = $result['candidate_id'];
			$sectionId = $result['section_id'];
			
			$sqlQuery = "UPDATE exam_mark_records SET section_id = \"$sectionId\" WHERE candidate_id = \"$candidateId\" ";
			$this->processQuery($sqlQuery);
			
			$sqlQuery = "UPDATE exam_result_records SET section_id = \"$sectionId\" WHERE candidate_id = \"$candidateId\" ";
			$this->processQuery($sqlQuery);
		
		}
	}
	
	public function changeOptionValues(){		
		$tableArray = array();
		array_push($tableArray, 'exam_mark_records');
		array_push($tableArray, 'exam_result_records');
		array_push($tableArray, 'exam_examination_dates');
		
		
	foreach ($tableArray as $tableName){
				$sqlQuery = "SHOW COLUMNS FROM $tableName";
				$columnArray = $this->getDataArray($this->processQuery($sqlQuery));
				
				foreach ($columnArray as $columnName){
					$sqlQuery = "UPDATE $tableName SET $columnName = \"LSUBJD9\" WHERE $columnName = \"LSUBJD32\" ";		
					echo $sqlQuery."<br />";
					$sqlQuery = $this->processQuery($sqlQuery);										
				}
				echo "<hr />Table : ".$tableName."<hr />";
			}	
		
	}
	
	public function changeCounterStarter(){
				
		$query = "SELECT starter from glb_counters ORDER BY STARTER ASC ";
		$counterArray = $this->getDataArray($this->processQuery($query));
		$i = 0;
		foreach ($counterArray as $starter){
			if(strlen($starter) == 6)
				continue;
			if($i > 10)
				break;
			$newStarter = 'L'.$starter;
			
			$sqlQuery = "UPDATE glb_counters SET starter = REPLACE(starter, \"$starter\", \"$newStarter\")";
			$this->processQuery($sqlQuery);	
			//retriving all the tables
		}
								
			
	}
	
	public function correctSerialNumberCandidate(){
		$sqlQuery = "SELECT id, registration_number FROM utl_candidate_registration ";
		$query = $this->processQuery($sqlQuery);
		while($result = mysql_fetch_array($query)){
			$id = $result[0];
			$registrationNumber = $result[1];
			
			$serialNumber = explode('/', $registrationNumber);
			$sqlQuery = "UPDATE utl_candidate_registration SET serial_number = \"".$serialNumber[0]."\" WHERE id = \"$id\" ";
			$this->processQuery($sqlQuery);
			echo $registrationNumber." ". $serialNumber[0]."<br />";
			
		}
	}
	
	public function correctResultData(){
		$sqlQuery = "SELECT * FROM exam_result_attendance WHERE active = \"y\" ";
		$query = $this->processQuery($sqlQuery);
		while($result = mysql_fetch_array($query)){
			$details = $result;
			
			$counter = $this->getCounter('resultData');
			$sqlQuery = "INSERT INTO exam_result_data 
							(id, session_id, result_id, class_id, section_id, result_section_id, candidate_id, data, type, last_update_date, last_updated_by, creation_date, created_by, active) 
							VALUES (\"".$counter."\", \"".$details['session_id']."\", \"".$details['result_id']."\", \"".$details['class_id']."\", \"".$details['section_id']."\", \"".$details['result_section_id']."\", \"".$details['candidate_id']."\", \"".$details['attendance']."\", \"ATTND\", \"".$details['last_update_date']."\", \"".$details['last_updated_by']."\", \"".$details['creation_date']."\", \"".$details['created_by']."\", \"".$details['active']."\")";
			$this->processQuery($sqlQuery);
		}
		
		$sqlQuery = "SELECT * FROM exam_result_remarks WHERE active = \"y\" ";
		$query = $this->processQuery($sqlQuery);
		while($result = mysql_fetch_array($query)){
			$details = $result;
				
			$counter = $this->getCounter('resultData');
			$sqlQuery = "INSERT INTO exam_result_data
			(id, session_id, result_id, class_id, section_id, result_section_id, candidate_id, data, type, last_update_date, last_updated_by, creation_date, created_by, active)
			VALUES (\"".$counter."\", \"".$details['session_id']."\", \"".$details['result_id']."\", \"".$details['class_id']."\", \"".$details['section_id']."\", \"".$details['result_section_id']."\", \"".$details['candidate_id']."\", \"".$details['remark_id']."\", \"REMKS\", \"".$details['last_update_date']."\", \"".$details['last_updated_by']."\", \"".$details['creation_date']."\", \"".$details['created_by']."\", \"".$details['active']."\")";
			$this->processQuery($sqlQuery);
		}		
	}
	
	public function correctLoanRecord(){
		$sqlQuery = "SELECT id FROM accounts_salary_loan_record ";
		$query = $this->processQuery($sqlQuery);
		while($result = mysql_fetch_array($query)){
			$id = $result[0];
			$starter = substr($id, 0, 5);
			$value = substr($id, 5, 2);
			$newId = "AEMLR".$value;
			$sqlQuery = "UPDATE accounts_salary_loan_record SET id = \"$newId\" WHERE id = \"$id\" ";
			$this->processQuery($sqlQuery);
		}
	}
	
	public function loadSMSData(){
		$classIds = array('LCLASS10', 'LCLASS11', 'LCLASS12', 'LCLASS13', 'LCLASS14', 'LCLASS15', 'LCLASS16');
		foreach($classIds as $classId){
			$sqlQuery = "SELECT a.mobile_no, a.first_name, a.middle_name, a.last_name
							FROM utl_personal_info a, utl_candidate_classes b
							WHERE a.id = b.candidate_id
								AND a.mobile_no != \"\"
								AND b.class_id = \"$classId\"
								AND a.active = \"y\"
								AND b.active = \"y\"
						AND NOT EXISTS (SELECT 1 from glb_sms_processed1 c WHERE c.mobile_number = a.mobile_no) ";
			$query = $this->processQuery($sqlQuery);
			$i = 1;
			while($result = mysql_fetch_array($query)){
				$counter = $this->getCounter('smsPending');
				//$counter = 1;
				$userName = ucwords(strtolower($result[1])).' '.ucwords(strtolower($result[2])).' '.ucwords(strtolower($result[3]));
				$content = "Dear Parent, school will close for Dushhera from 20th oct to 27th oct and will reopen on 29th oct. DPS Kashi";
				$sqlQuery = "INSERT INTO glb_sms_pending
					(id, source_id, user_name, mobile_number, sms_content, sms_type, priority, last_update_date, last_updated_by, creation_date, created_by, active)
					VALUES (\"$counter\", \"PTM-MESSAGE\", \"$userName\", \"".$result[0]."\", \"$content\", \"LRESER22\", \"1\", \"".date('c')."\", \"LUSERS0\", \"".date('c')."\", \"LUSERS0\", \"y\")";
				$this->processQuery($sqlQuery, $counter);
				echo $counter." ".$result[0]." ".$userName." ".$result[0]."<br />";
				++$i;
			}	
		}
		$this->sendAdminMessages($content, 'PTM-MESSAGE');		
	}
	
	public function loadSMSData4WholeSchoolCandidate(){
		$sqlQuery = "SELECT a.mobile_no, a.first_name, a.middle_name, a.last_name, b.amount amt
							FROM utl_personal_info a, table95 b
							WHERE a.id = b.candidate_id
								AND length(trim(a.mobile_no)) = 10
								AND a.active = \"y\"
								AND b.candidate_id != \"\" ";
		
		$query = $this->processQuery($sqlQuery);
		$i = 1;
		while($result = mysql_fetch_array($query)){
			$counter = $this->getCounter('smsPending');
			$userName = ucwords(strtolower($result[1])).' '.ucwords(strtolower($result[2])).' '.ucwords(strtolower($result[3]));
			$amount = $result['amt'];
			$content = "Dear Parent, please pay your ward's due fee of Rs. ".$amount." by 15 Dec 2012 to avoid late fine. Please deposit school slip, if already paid. DPS Kashi";
			$sqlQuery = "INSERT INTO glb_sms_pending
			(id, source_id, user_name, mobile_number, sms_content, sms_type, priority, last_update_date, last_updated_by, creation_date, created_by, active)
			VALUES (\"$counter\", \"BK-FEEMS\", \"$userName\", \"".$result[0]."\", \"$content\", \"LRESER22\", \"1\", \"".date('c')."\", \"LUSERS0\", \"".date('c')."\", \"LUSERS0\", \"y\")";
			$this->processQuery($sqlQuery, $counter);
			echo $counter." ".$userName." ".$result[0]."<br />";
			++$i;
		}
		$this->sendAdminMessages($content, 'BK-FEEMS');
	}
	
	public function sendAdminMessages($content, $source){
		$counter = $this->getCounter('smsPending');
		$sqlQuery = "INSERT INTO glb_sms_pending
			(id, source_id, user_name, mobile_number, sms_content, sms_type, priority, last_update_date, last_updated_by, creation_date, created_by, active)
			VALUES (\"$counter\", \"$source\", \"Alaka Sahu\", \"9598082222\", \"$content\", \"LRESER22\", \"1\", \"".date('c')."\", \"LUSERS0\", \"".date('c')."\", \"LUSERS0\", \"y\")";
		$this->processQuery($sqlQuery, $counter);
		
		$counter = $this->getCounter('smsPending');
		$sqlQuery = "INSERT INTO glb_sms_pending
			(id, source_id, user_name, mobile_number, sms_content, sms_type, priority, last_update_date, last_updated_by, creation_date, created_by, active)
			VALUES (\"$counter\", \"$source\", \"Neelam Pal\", \"9598084444\", \"$content\", \"LRESER22\", \"1\", \"".date('c')."\", \"LUSERS0\", \"".date('c')."\", \"LUSERS0\", \"y\")";
		$this->processQuery($sqlQuery, $counter);
		
		$counter = $this->getCounter('smsPending');
		$sqlQuery = "INSERT INTO glb_sms_pending
			(id, source_id, user_name, mobile_number, sms_content, sms_type, priority, last_update_date, last_updated_by, creation_date, created_by, active)
			VALUES (\"$counter\", \"$source\", \"Ashwani Chauhan\", \"9598085555\", \"$content\", \"LRESER22\", \"1\", \"".date('c')."\", \"LUSERS0\", \"".date('c')."\", \"LUSERS0\", \"y\")";
		$this->processQuery($sqlQuery, $counter);
		
	}
	
	public function correctCounter(){
		$sqlQuery = "SHOW TABLES";
		$tableArray = $this->getDataArray($this->processQuery($sqlQuery));
	
		$sqlQuery = "SELECT starter FROM glb_counters ORDER BY starter ASC";
		$counterStarters = $this->getDataArray($this->processQuery($sqlQuery));
		$i = 0;
		foreach($counterStarters as $starter){
			if(strlen($starter) > 5)
				continue;
			if($i > 9)
				break;
			$newStarter = 'L'.$starter;
			foreach ($tableArray as $tableName){
				$sqlQuery = "SHOW COLUMNS FROM $tableName";
				$columnArray = $this->getDataArray($this->processQuery($sqlQuery));
	
				foreach ($columnArray as $columnName){
					$sqlQuery = "UPDATE $tableName SET $columnName = REPLACE($columnName, \"$starter\", \"$newStarter\")";
					$this->processQuery($sqlQuery);
					echo $sqlQuery."<br />";
				}
				echo "<hr />".$tableName."<hr />";
			}
	
			++$i;
		}
	}
	
	public function checkLimit(){
		$i = 1;
		
		$myFile = $this->getPath()."/programs/logs/maxIntTest.txt";
		$fh = fopen($myFile, 'a') or die("can't open file");
		
		$startTime = microtime(true);
		$midTime = microtime(true);		
		
		while(true){
			if($i > 50000000)
				break;
			$counter = $this->getCounter('test');			
			$sqlQuery = "INSERT INTO test (id, last_update_date, last_updated_by, creation_date, created_by, active) 
							values (\"$counter\", \"".date('c')."\", \"LUSERS0\", \"".date('c')."\", \"LUSERS0\", \"y\")";
			$this->processQuery($sqlQuery);
			
			if($i % 1000 == 0){
				$time = microtime(true);
				
				$stringData =  str_pad(($time - $startTime), 20)." ".str_pad(($time - $midTime), 20)." $counter"."\r ";				
				
				fwrite($fh, $stringData);
				$midTime = $time;
			}
			if($counter == 'LTESTS2000000')
				break;
			++$i;
		}	
		fwrite($fh, $stringData);
		fclose($fh);
	}
	
	public function putProcessedSms(){
		$sqlQuery = "SELECT * from glb_sms_processed1 where creation_date > \"2012-10-01\" ";
		$query = $this->processQuery($sqlQuery);
		while($result = mysql_fetch_array($query)){			
			$counter = $this->getCounter('smsProcessed');
			
			$sqlQuery = "INSERT INTO glb_sms_processed 
							(id, source_id, confirmation_id, user_name, mobile_number, sms_content, sms_type, priority, sms_status, last_update_date, last_updated_by, creation_date, created_by) 
							values (\"$counter\", \"BISHWAKARMA\", \"".$result['reference_id']."\", \"".$result['ss']."\", \"".$result['mobile_number']."\", \"".$result['sms_content']."\", \"".$result['sms_type']."\", \"".$result['priority']."\", \"AWAITING CONFIRMATION\", \"".$result['last_update_date']."\", \"LUSERS0\", \"".$result['creation_date']."\", \"LUSERS0\")";
			echo $sqlQuery."<br />";
			$this->processQuery($sqlQuery);
			
			
		}
	}
	
	public function correctMobileName(){
		$sqlQuery = "SELECT id FROM glb_sms_processed where user_name = ''";
		$smsProcessedIds = $this->getDataArray($this->processQuery($sqlQuery));
		foreach($smsProcessedIds as $smsProcessId){
			$details = $this->getTableIdDetails($smsProcessId);
				
			$sqlQuery = "UPDATE glb_sms_processed SET user_name = \"".$this->getMobileName($details['mobile_number'])."\" WHERE mobile_number = \"".$details['mobile_number']."\" ";
			$this->processQuery($sqlQuery);
		}
	}
	
	public function getMobileName($mobileNo){
		$sqlQuery = "SELECT CONCAT(first_name , ' ', middle_name, ' ' , last_name) FROM utl_personal_info WHERE mobile_no = \"$mobileNo\" ";
		$sqlQuery =  $this->processArray($sqlQuery);
	
		return $sqlQuery[0];
	}
	
	public function correctMaxMark(){
		$sqlQuery = "SELECT id FROM exam_examination_dates WHERE marking_type != \"\" ";
		$examinationDateIds = $this->getDataArray($this->processQuery($sqlQuery));
		foreach($examinationDateIds as $examinationDateId){
			$details = $this->getTableIdDetails($examinationDateId);
			$maxMark = $this->_grading->getGradingTypeMaxScore($details['marking_type']);
			$sqlQuery = "UPDATE exam_examination_dates SET max_mark = \"$maxMark\" WHERE id = \"$examinationDateId\" ";
			$this->processQuery($sqlQuery);
		}
	}
	
	public function correctSectionSetupForAbsoluteMarking(){
		$classNameArray = array('LCLASN15', 'LCLASN16');
		$sectionIds = array();
		foreach ($classNameArray as $className){
			$sqlQuery = "SELECT a.id FROM utl_class_sections a, utl_class_details b WHERE b.class_id = \"$className\" AND b.id = a.class_id ";
			$sqlQuery = $this->processQuery($sqlQuery);
			while($result = mysql_fetch_array($sqlQuery))
				array_push($sectionIds, $result[0]);
		}
		
		$sqlQuery = "SELECT * FROM exam_result_sections WHERE active = \"y\" ";
		$query = $this->processQuery($sqlQuery);
		while($result = mysql_fetch_array($query)){
			if(in_array($result['section_id'], $sectionIds)){
				$this->_resultType->setResultTypeDataEntry($result['result_id'], $result['section_id'], 'LEXRTF1', $result['total_mark']);
				$this->_resultType->setResultTypeDataEntry($result['result_id'], $result['section_id'], 'LEXRTF2', $result['total_attendance']);
				
				$this->_resultType->setResultTypeSubmissionEntry($result['result_id'], $result['section_id'], 'LEXRTF3', $result['remarks_date'], $result['remarks_officer']);
				$this->_resultType->setResultTypeSubmissionEntry($result['result_id'], $result['section_id'], 'LEXRTF4', $result['attendance_date'], $result['attendance_officer']);				
			}
		} 
		
	}
	
	public function correctSectionSetupForGradingSystem(){
		$classNameArray = array('LCLASN10', 'LCLASN11', 'LCLASN12', 'LCLASN13', 'LCLASN14');
		$sectionIds = array();
		foreach ($classNameArray as $className){
			$sqlQuery = "SELECT a.id FROM utl_class_sections a, utl_class_details b WHERE b.class_id = \"$className\" AND b.id = a.class_id ";
			$sqlQuery = $this->processQuery($sqlQuery);
			while($result = mysql_fetch_array($sqlQuery))
				array_push($sectionIds, $result[0]);
		}
	
		$sqlQuery = "SELECT * FROM exam_result_sections WHERE active = \"y\" ";
		$query = $this->processQuery($sqlQuery);
		while($result = mysql_fetch_array($query)){
			if(in_array($result['section_id'], $sectionIds)){
				$this->_resultType->setResultTypeDataEntry($result['result_id'], $result['section_id'], 'LEXRTF5', $result['total_mark']);
				$this->_resultType->setResultTypeDataEntry($result['result_id'], $result['section_id'], 'LEXRTF6', $result['total_attendance']);
	
				$this->_resultType->setResultTypeSubmissionEntry($result['result_id'], $result['section_id'], 'LEXRTF7', $result['remarks_date'], $result['remarks_officer']);
				$this->_resultType->setResultTypeSubmissionEntry($result['result_id'], $result['section_id'], 'LEXRTF8', $result['attendance_date'], $result['attendance_officer']);
			}
		}	
	}
	
	
	
	
	public function copyActivityMarks($examDateId, $genericId){
		$sqlQuery = "SELECT * FROM exam_mark_records WHERE exam_date_id = \"$examDateId\" AND active = \"y\" ";
		$query = $this->processQuery($sqlQuery);
		while($result = mysql_fetch_array($query)){
			$counter = $this->getCounter('resultEntry');
			
			$sqlQuery = "INSERT INTO exam_result_records
						(id, result_id, generic_id, session_id, class_id, section_id, subject_id, subject_component_id, candidate_id, submitted_mark, absolute_mark, submission_date, submission_officer_id, verification_date, verification_officer_id, last_update_date, last_updated_by, creation_date, created_by, active)
						VALUES (\"$counter\", \"LRESDF10\", \"$genericId\", \"" . $result ['session_id'] . "\", \"" . $result ['class_id'] . "\", \"".$result['section_id']."\", \"".$result['subject_id']."\", \"".$result['subject_component_id']."\", \"".$result['candidate_id']."\", \"".$result['submitted_mark']."\",  \"".$result['absolute_mark']."\", \"".$result['submission_date']."\", \"".$result['submission_officer_id']."\", \"".$result['verification_date']."\", \"".$result['verification_officer_id']."\", \"".$result['last_update_date']."\", \"".$result['last_updated_by']."\", \"".$result['creation_date']."\", \"".$result['created_by']."\", \"y\") ";
			echo $sqlQuery."<br />";
			$this->processQuery($sqlQuery);
			
			$sqlQuery = "DELETE FROM exam_mark_records WHERE id = \"".$result['id']."\" ";
			$this->processQuery($sqlQuery);
		}
	}
	
	public function correctTable95Data(){
		$sqlQuery = "SELECT * FROM table95";
		$query = $this->processQuery($sqlQuery);
		while($result = mysql_fetch_array($query)){
			$admno = $result[1];
			
			$sqlQuery = "SELECT * from utl_candidate_registration where registration_number = \"$admno\"";
			$sqlQuery = $this->processArray($sqlQuery);
			
			if($sqlQuery){
				$candidateId = $sqlQuery['candidate_id'];
				$sqlQuery = "UPDATE table95 SET candidate_id = \"$candidateId\" where admno = \"$admno\" ";
				echo $candidateId."<br />";
				$this->processQuery($sqlQuery);
			}
		}
	}
	
	public function dumpSms(){
		$sqlQuery = "SELECT * 
						FROM  `glb_sms_processed` 
						WHERE  `creation_date` >  '2012-11-19 00:00:00' ";
		$query = $this->processQuery($sqlQuery);
		$i = 1;
		while($result = mysql_fetch_array($query)){
			$counter = $this->getCounter('smsPending');
			
			$content = $result['sms_content'];
			$userName = $result['user_name'];
			
			$sqlQuery = "INSERT INTO glb_sms_pending
				(id, source_id, user_name, mobile_number, sms_content, sms_type, priority, last_update_date, last_updated_by, creation_date, created_by, active)
				VALUES (\"$counter\", \"BK-FEEMS\", \"$userName\", \"".$result['mobile_number']."\", \"$content\", \"LRESER22\", \"1\", \"".date('c')."\", \"LUSERS0\", \"".date('c')."\", \"LUSERS0\", \"y\")";
			$this->processQuery($sqlQuery, $counter);
			
			echo $counter." ".$userName." ".$result['mobile_number']."<br />";
			++$i;
		}
		$this->sendAdminMessages($content, 'BK-FEEMS');		
	}
}

?>