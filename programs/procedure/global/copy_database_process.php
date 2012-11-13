<?php
	require_once 'config.php';
	require_once BASE_PATH.'include/global/class.sqlFunction.php';
	
	class copyDB extends sqlFunction{
		public function __construct(){
			parent::__construct();
		}
		
		public function dropMenuOnes(){
			$sqlQuery = "SELECT table_name FROM glb_counters WHERE table_name LIKE \"glb_menu%\"";
			$tableArray = $this->getDataArray($this->processQuery($sqlQuery));
			foreach ($tableArray as $tableName){
				$table = "suppor46_dump.".$tableName;
				$sqlQuery = "DROP table $table";
				$this->processQuery($sqlQuery);			
				
			}
		}
	}