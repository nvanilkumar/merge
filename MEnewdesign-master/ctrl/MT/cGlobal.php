<?php
error_reporting(1);session_start();
 if(strpos($_SERVER['HTTP_HOST'], 'dhamaal.stage.meraevents.com') !== false)
{
define('_HTTP_SITE_ROOT','http://dhamaal.stage.meraevents.com');
}
else{
define('_HTTP_SITE_ROOT','http://menew.com');
}
//define('_HTTP_SITE_ROOT','http://stage.meraevents.com');
//define('_HTTP_CDN_ROOT','http://content.stage.meraevents.com');
define('_HTTP_CDN_ROOT','http://menew.com');
//define('_HTTP_CDN_ROOT','http://d2n37i1sblutf6.cloudfront.net/content');
define('_HTTP_CF_ROOT','http://menew.com');
define('_HTTP_Content','content');
define('_BUCKET','phinnytest');
define('_imageOptimizeScriptPath', '/var/test/bash/');
if ($_SERVER['HTTP_HOST'] != "localhost") {
    define('_DOC_ROOT', $_SERVER["DOCUMENT_ROOT"]);
} else {
    define('_DOC_ROOT', $_SERVER["DOCUMENT_ROOT"]."/master/Meraevents");
}
date_default_timezone_set('Asia/Calcutta');

class cGlobal
{	
	public function cGlobal()    // class constructor.  Initializations here.
	{
		//$this->dbconn = $this->db_connect();
	}
	
//-------------------------------------------------------------------------------------------------------------------

/* 	public $DBServerName = "mestgdbv2.ckqsu4xn3xb3.us-east-1.rds.amazonaws.com";
        public $DBServerNameOnly="192.168.10.29";
        public $portNumber=56456;
	public $DBUserName = "Mstgv2";
	public $DBPassword = "hMjK1P8#$";
	public $DBIniCatalog = "meold"; */ //"meraeven_dmeraevent";//"meraeven_dmeraevent";
	   public $DBServerName ="10.10.11.70";
    public $DBServerNameOnly = "10.10.11.70";
    public $portNumber=3306;
    public $DBUserName = "me";
    public $DBPassword = "root";
    public $DBIniCatalog = "mestgv2";
    public $db_connection; 
   // static $starttime = "";

	private function db_connect() //returns connection
	{

		if($this->db_connection) //checking if connection already exits.
		{
			return $this->db_connection;

		}
		else
		{
			try
			
			{
				$db_conn = mysql_connect($this->DBServerName,$this->DBUserName,$this->DBPassword);
				
				if(!$db_conn)
				{
					throw new Exception("Unable to connect to DB");
				}
			}
			catch(Exception $e)
			{
				throw new Exception('DB Connection Failed: ' .$e->getMessage());
			}
			
			try
			{
				$isSelected=mysql_select_db($this->DBIniCatalog);

				if(!$isSelected)
				{
					throw new Exception("Unable to select database");
				}

				$this->db_connection=$db_conn;
				return $db_conn;
			}

			catch(Exception $e)
			{
				throw new Exception('Unable to select DB: ' .$e->getMessage());
			}

		}//end of if condition to check if connection already exists

	}
			 
//-------------------------------------------------------------------------------------------------------------------			 
		
	public function SelectQuery($sql)	//returns result row
	{
		//>>Open connection>>
		try
		{
			$conn=$this->db_connect();
			if(!$conn)
		    {
				throw new Exception("Database Connection null");
			}
		}
		catch(Exception $e)
		{
			throw $e;
		}	
		//<<Open connection<<
				
		
		//>>Execute Query>>
		try
		{
                   // echo $sql."_________";
		
		
			$res=mysql_query($sql);
			
			
			
			if(!$res)
			{
				throw new Exception("Select query could not be executed!");
			}
			
			$i=0;
			
			
			
			while($row=mysql_fetch_array($res))
			{
				
				$resultArray[$i]=$row;
				$i++;
			}
			
/*			if($conn)
		    {
				mysql_close($conn);
			}
*/			
			return $resultArray;	//returns row
		}
		catch(Exception $e)
		{
			throw $e;
		}
		//<<Execute Query<<		
	}
	
        public function realEscape($var)
        {
           try
		{
			$conn=$this->db_connect();
			if(!$conn)
		    {
				throw new Exception("Database Connection null");
			}
		}
		catch(Exception $e)
		{
			throw $e;
		}
                
            return       mysql_real_escape_string($var);
            
        }


//-------------------------------------------------------------------------------------------------------------------	
	
	public function ExecuteQuery($sql)	//returns affected rows
	{
            $sql;
		//>>Open connection>>
		try
		{
			$conn=$this->db_connect();
			if(!$conn)
		    {
				throw new Exception("Database Connection null");
			}
		}
		catch(Exception $e)
		{
			throw $e;
		}	
		//<<Open connection<<
		

		//>>Execute Query>>		
		try
		{
			$res=mysql_query($sql);
			
			if(!$res)
			{
				throw new Exception("Error in execute query".$sql."----". mysql_error());
			}
			
			$AffectedRows = mysql_affected_rows();
                    

			
			if ($AffectedRows > 0)
			{
				return true;  // no rows were afected
			}
			else
			{
				return false;	//afected rows
			}	
		}
		catch(Exception $e)
		{
			throw $e;
		}	
		//<<Execute Query<<		
	}
	
//-------------------------------------------------------------------------------------------------------------------		

	public function ExecuteQueryId($sql)	//returns affected rows
	{
		//>>Open connection>>
		try
		{
			$conn=$this->db_connect();
			if(!$conn)
		    {
				throw new Exception("Database Connection null");
			}
		}
		catch(Exception $e)
		{
			throw $e;
		}	
		//<<Open connection<<
		

		//>>Execute Query>>		
		try
		{
			$res=mysql_query($sql);
			
			if(!$res)
			{
				throw new Exception("Error in execute query");
			}
			
			$AffectedRows = mysql_affected_rows();
                   

			
			if ($AffectedRows > 0)		//Insert Succeeded.
			{
				return mysql_insert_id($conn);
			}
			else		//Insert Failed
			{
				return 0;	//Id of newly inserted record
			}	
		}
		catch(Exception $e)
		{
			throw $e;
		}	
		//<<Execute Query<<		
	}
	
//-------------------------------------------------------------------------------------------------------------------		
	
	public function GetSingleFieldValue($sql)	//returns result first column
	{
		
		$resultArray =$this->SelectQuery($sql);
		return $resultArray[0][0];
	}

//-------------------------------------------------------------------------------------------------------------------		
	
	function getDBString($stringResult) //string
	{
		  if(is_null($stringResult))
		  {
			return "";
		  }
		 
		 else 
		 {
			return $stringResult;
		 }
	 }
//-------------------------------------------------------------------------------------------------------------------
	
	function getDBInt($intResult) //integer
	{
		if(is_null($intResult))
		{
			return 0;
		}
		else
		{
			return $intResult;
		}
	}
//-------------------------------------------------------------------------------------------------------------------

	function getDBFloat($floatResult) //float
	{
			if(is_null($floatResult))
			{
				return 0.0;
			}
			else
			{
				return $floatResult;
			}
	}

//---------------------------------------------------------------------------------------------------------------------

	public function getDBSaveDtFormat($myDate)		//Convert Date to "yyyy-MM-dd HH:Mi:ss" format
	{
		//$EndDt=$_POST['enddate'];

		$ReturnDt = new DateTime($myDate);
		return $ReturnDt->format("Y-m-d H:i:s");

	}

//---------------------------------------------------------------------------------------------------------------------

	public function getDBRetrieveDtFormat($myDate)		//Convert DateTime to "dd/MM/yyyy hh:mi:ss tt" format
	{
		//$EndDt=$_POST['enddate'];
		$ReturnDt = new DateTime($myDate);
		return $ReturnDt->format("d/m/Y h:i a");
	}

//---------------------------------------------------------------------------------------------------------------------


	public function getDBRetrieveDtOnlyFormat($myDate)		//Convert DateTime to "dd/MM/yyyy hh:mi:ss tt" format
	{
		//$EndDt=$_POST['enddate'];
		$ReturnDt = new DateTime($myDate);
		return $ReturnDt->format("d/m/Y");
	}

//---------------------------------------------------------------------------------------------------------------------

public function getCategory($EId)		//get venue EventType(s)
	{
		$sql="select CategoryId from  eventncat where EventId=".$EId." limit 0,1";
		$resultArray =$this->SelectQuery($sql);
		$Category="";
		for($i=0;$i<count($resultArray);$i++)
		{
		$Category.=$this->GetSingleFieldValue("select Category from categories where Id=".$resultArray[$i]['CategoryId']).", ";

		}
		
		
		return substr($Category,0,-2);
	}
	
	
	
	
	public function justSelectQuery($sql)
	{
		try{
			$res=mysql_query($sql);
			return $res;
		}
		catch(Exception $e)
		{
			throw new Exception("Select query could not be executed!");
		}
	}


	function __destruct() // the destuctor function auto executes at end of each page.....
	{
		if($this->db_connection)
		{
			mysql_close($this->db_connection);

		}
	}

}
?>
