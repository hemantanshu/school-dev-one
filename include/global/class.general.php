<?php
/**
 * This class will hold the general functionalities to be used throughout the software
 * This class extends the sqlFunction class
 * @author Hemant Kumar Sah
 * @category Global
 * @license Support-Gurukul
 * @version 1.0.0
 */
require_once 'class.loggedInfo.php';
class general extends loggedInfo {
	
	public function __construct() {
		parent::__construct ();
	}
	
	/**
	 * This method is used to redirect to the designated url
	 *
	 * @param
	 *       	 url The url of the page to which the page has to be redirected
	 *       	 to
	 */
	public function redirectUrl($url) {
		echo "<script type=\"text/javascript\">
                    window.location= \"$url\"
            </script>";
		echo "Please Wait For Some Time. If The Time Exceeds For More Than 15 Seconds Then The javascript Of Your Browser is Disabled. So Please Enable it to use the software";
		exit ( 0 );
	}
	
	/**
	 * This method is used to give user a alert to the user and then redirect to
	 * a given url
	 *
	 * @param
	 *       	 message The message that is to be alerted the user with
	 * @param
	 *       	 url The url to which the page will be redirected to
	 */
	public function palert($message, $url) {
		echo "<script>
          alert( \"$message\" );
          </script>";
		$this->redirectUrl ( $url );
	}
	
	// functions related to the manipulation of datetime
	
	/**
	 * The public display of the date.
	 * Every date has to be parsed with these function so as to display as per
	 * the customer timezone
	 *
	 * @param $date Date
	 *       	 The date to be parsed to be displayed
	 * @return Date The date as per the timezone of the customer
	 */
	public function getDisplayDate($date) {
		$data = explode ( '-', $date );
		return date ( "F d, Y", mktime ( 0, 0, 0, $data [1], $data [2], $data [0] ) );
	}
	
	/**
	 * The public display of the datetime.
	 * Every datetime has to be parsed with these function so as to display as
	 * per the customer timezone
	 *
	 * @param $datetime Datetime
	 *       	 The datetime which has to be parsed
	 * @return Datetime The datetiem as per the timezone of the client
	 */
	public function getDisplayDateTime($datetime) {
		return $datetime;
	}
	
	/**
	 * The protected function which will enable to convert the date of another
	 * format n timezone as per the timezone of the server
	 *
	 * @param $date Date
	 *       	 The date inputed by the client as per their date format
	 * @return Date The date as per the server date timezone n format
	 */
	public function getServerDate($date) {
		return $date;
	}
	
	public function getMonthNameOfDate($date){
		$dateArray = explode('-', $date);
		return date('F, Y', mktime(0, 0, 0, $dateArray[1], $dateArray[2], $dateArray[0]));
	}
	
	public function getMonthOfDate($date){
		$dateArray = explode('-', $date);
		return date('Ym', mktime(0, 0, 0, $dateArray[1], $dateArray[2], $dateArray[0]));
	}
	
	public function getMonthNameOfMonth($month){
		return date('F, Y', mktime(0, 0, 0, substr($month, 4, 2), 15, substr($month, 0, 4)));
	}
	
	public function getFutureDate($date, $days){
		$dateArray = explode('-', $date);
		return date('Y-m-d', mktime(0, 0, 0, $dateArray[1], $dateArray[2] + $days, $dateArray[0]));
	}
	//
	
	public function getOperationLogIds($operationId) {
		$sqlQuery = "SELECT id 
                            FROM utl_userlog 
                            WHERE operation_id = \"$operationId\" 
                            ORDER BY datetime DESC";
		return $this->getDataArray ( $this->processQuery ( $sqlQuery ) );
	}
	
	public function getOperationLogIdDetails($logId) {
		return $this->getTableIdDetails ( $logId );
	}
	
	public function getBasePath() {
		return $this->getPath ();
	}
	
	public function getDateDifference($startDate, $endDate) {
		return (strtotime ( $endDate ) - strtotime ( $startDate ));
	}
	
	public function getNotificationIds($userId, $active) {
		if ($active) {
			if ($active === 'all')
				$sqlQuery = "SELECT id
				    			FROM glb_push_notification
				    			WHERE user_id like \"%$userId%\"
				    			ORDER BY start_date DESC";
			else
				$sqlQuery = "SELECT id
					    		FROM glb_push_notification
					    		WHERE user_id like \"%$userId%\"
					    			AND active = \"y\"
					    		ORDER BY start_date DESC";
		} else
			$sqlQuery = "SELECT id
					    	FROM glb_push_notification
					    	WHERE user_id like \"%$userId%\"
					    		AND active != \"y\"
					    	ORDER BY start_date DESC";
		return $this->getDataArray ( $this->processQuery ( $sqlQuery ) );
	}
	
	public function nameAmount($x){
		$x = (int) $x;
	
		$nwords = array(	"zero", "one", "two", "three", "four", "five", "six", "seven",
				"eight", "nine", "ten", "eleven", "twelve", "thirteen",
				"fourteen", "fifteen", "sixteen", "seventeen", "eighteen",
				"nineteen", "twenty", 30 => "thirty", 40 => "forty",
				50 => "fifty", 60 => "sixty", 70 => "seventy", 80 => "eighty",
				90 => "ninety" );
		if(!is_numeric($x))
		{
			$w = '#';
		}else if(fmod($x, 1) != 0)
		{
			$w = '#';
		}else{
			if($x < 0)
			{
				$w = 'minus ';
				$x = -$x;
			}else{
				$w = '';
			}
			if($x < 21)
			{
				$w .= $nwords[$x];
			}else if($x < 100)
			{
				$w .= $nwords[10 * floor($x/10)];
				$r = fmod($x, 10);
				if($r > 0)
				{
					$w .= '-'. $nwords[$r];
				}
			} else if($x < 1000)
			{
				$w .= $nwords[floor($x/100)] .' hundred';
				$r = fmod($x, 100);
				if($r > 0)
				{
					$w .= ' and '.$this->nameAmount($r);
				}
			} else if($x < 1000000)
			{
				$w .= $this->nameAmount(floor($x/1000)) .' thousand';
				$r = fmod($x, 1000);
				if($r > 0)
				{
					$w .= ' ';
					if($r < 100)
					{
						$w .= 'and ';
					}
					$w .= $this->nameAmount($r);
				}
			} else {
				$w .= $this->nameAmount(floor($x/1000000)) .' million';
				$r = fmod($x, 1000000);
				if($r > 0)
				{
					$w .= ' ';
					if($r < 100)
					{
						$word .= 'and ';
					}
					$w .= $this->nameAmount($r);
				}
			}
		}
		return ucwords($w);
	}
}
?>