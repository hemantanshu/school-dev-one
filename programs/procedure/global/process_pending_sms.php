<?php
	require_once 'config.php';
	require_once BASE_PATH.'include/global/class.sms.php';
	
	$sms = new Sms();
	
	$sourceId = $sms->getGlobalVariable('sms_source');
	$tokenId = $sms->getGlobalVariable('sms_token');
	
	
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, "http://localhost/main/programs/procedure/global/glb_sms_capture.php");
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_POST, true);
			
	$pendingIds = $sms->getPendingSMSIds();
	foreach ($pendingIds as $pendingId){
		$details = $sms->getTableIdDetails($pendingId);
		$data = array(
				'source_id' => urlencode($sourceId),
				'passcode' => urlencode($tokenId),
				'referenceId' => urlencode($details['id']),
				'mobileNo' => urlencode($details['mobile_number']),
				'sms_content' => urlencode($details['sms_content']),
				'priority' => urlencode($details['priority']),
				'type' => urlencode($details['sms_type'])				
		);		
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		$output = curl_exec($ch);	
		$output = json_decode($output);
		if($output){
			$sms->setProcessedSMS($sourceId, $output, $details['user_name'], $details['mobile_number'], $details['sms_content'], $details['sms_type'], $details['priority'], 'awaiting confirmation');
			$sms->dropPendingSms($pendingId);
		}	
	}
	
	curl_close($ch);
	
	

	
?>