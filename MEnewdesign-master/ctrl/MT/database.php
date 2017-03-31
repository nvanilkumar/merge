<?php
error_reporting(1);
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
define('_HTTP_CF_ROOT','http://menew.com');

define('_HTTP_Content','content');
define('_BUCKET','phinnytest');
if ($_SERVER['HTTP_HOST'] != "localhost") {
    define('_DOC_ROOT', $_SERVER["DOCUMENT_ROOT"]);
} else {

    define('_DOC_ROOT', $_SERVER["DOCUMENT_ROOT"]."/master/Meraevents");

}

define('_ME_PUB_KEY', 'file://C:/Users/Phinny/Desktop/durgesh/newpub');
define('_ME_PRIV_KEY', 'file://C:/Users/Phinny/Desktop/durgesh/newpvt');
define('_ME_PASS_PHRASE', 'testing');

date_default_timezone_set('Asia/Calcutta');
$sunburnEvents=array();
$sunburnTickets=array();
$hostname=strtolower($_SERVER['HTTP_HOST']);
if(strcmp($hostname,'www.meraevents.com')==0 || strcmp($hostname,'meraevents.com')==0)
{
	$sunburnEvents=array(66564,66566,66567);
        $sunburnTickets=array(53846,53847,53848,53849,53850,53851,53852,53853,53854);
}
else
{
	$sunburnEvents=array(64934,55305,33124);
    $sunburnTickets=array(45817,45820,45821,27166,27167,27176);
	$emergeEvents=array(73922, 73886);

}



$VIVO_BMS_EVENTID=33104;
$Vh1GOAEventID=68947;
$Vh1GOATktIds=array(55621);



//no dashbaord access to admin
if((!isset($_GET['uid']) || (isset($_GET['uid']) && $_GET['uid']==1)) && (isset($_SESSION['uid']) && in_array($_SESSION['uid'], array(1)))){
    if (stripos($_SERVER['PHP_SELF'], '/dashboard/') !== false){
        header('Location:'._HTTP_SITE_ROOT.'/Home');exit;
    }
}




class DB
{
	var $dbconn;
		
	public function DB()    // class constructor.  Initializations here.
	{
		$this->dbconn=$this->db_connect();
	}
	
//-------------------------------------------------------------------------------------------------------------------
	
	public $DBServerName = "mestgdbv2.ckqsu4xn3xb3.us-east-1.rds.amazonaws.com";
	public $DBServerNameOnly = "mestgdbv2.ckqsu4xn3xb3.us-east-1.rds.amazonaws.com";
	public $portNumber=56456;
	public $DBUserName = "Mstgv2";
	public $DBPassword = "hMjK1P8#$";
	public $DBIniCatalog = "menew";//"meraeven_dmeraevent";
	public $db_connection;

	public function db_connect() //returns connection
	{

		if($this->db_connection) //checking if connection already exits.
		{
			return $this->db_connection;

		}
		else
		{
			try
			{
				$db_conn = new mysqli($this->DBServerNameOnly, $this->DBUserName,$this->DBPassword,$this->DBIniCatalog,$this->portNumber);
				
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
		
	public function SelectQuery($sql,$type=MYSQLI_BOTH)	//returns result row
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
			$res=$conn->query($sql);
	
			if(!$res)
			{
				throw new Exception("Select query could not be executed!");
			}
			
			$i=0;
			
			
			
			while($row=$res->fetch_array($type))
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
	
//-------------------------------------------------------------------------------------------------------------------	
	
	public function ExecuteQuery($sql)	//returns affected rows
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
			$res=$conn->query($sql);
			
			if(!$res)
			{
				throw new Exception("Error in execute query");
			}
			
			$AffectedRows = $conn->affected_rows;
                    

			
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
			$res=$conn->query($sql);
			
			if(!$res)
			{
				throw new Exception("Error in execute query");
			}
			
			$AffectedRows = $conn->affected_rows;
                   

			
			if ($AffectedRows > 0)		//Insert Succeeded.
			{
				return $conn->insert_id;
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
  $conn=$this->db_connect();
  try{
   $res=$conn->query($sql);
   return $res;
  }
  catch(Exception $e)
  {
   throw new Exception("Select query could not be executed!");
  }
 }

	function __destruct() // the destuctor function auto executes at end of each page.....
	{
		$conn=$this->db_connect();
		if(!$conn)
		{
				throw new Exception("Database Connection null");
		}
		else
		{
			$conn->close();

		}
	}

}
?>
