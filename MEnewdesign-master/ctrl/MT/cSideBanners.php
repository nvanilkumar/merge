<?php
include_once("cGlobal.php");

class cSideBanners
{
	private $TableNm = "SideBanners";
   	public $Id = 0;
	public $StartDt = 0;
	public $EndDt = 0;
	public $FileName = '';
	public $URL = '';
	public $Active = 0;
	public $SeqNo = 0;
	public $Main = 0;
	public $Banglore = 0;
	public $Chennai = 0;
	public $Delhi = 0;
	public $Hyderabad = 0;
	public $Mumbai = 0;
	public $Pune = 0;
    public $Kolkata = 0;
    public $Goa = 0;
    public $Jaipur = 0;
    public $Ahmedabad = 0;
	public $AllCities = 0;
	public $OtherCities = 0;
    public $CategoryId = 0;
	public $clickscount=0;

	//----------------------------------------------------------------------------------------------------

	public function __construct($lId, $sStartDt, $sEndDt, $sFileName, $sURL, $sActive, $sSeqNo,$sMain, $sBanglore,$sChennai,$sDelhi, $sHyderabad ,$sMumbai,$sPune,$sKolkata,$sJaipur,$sGoa,$sAhmedabad,$sAllCities,$sOtherCities,$sCategoryId,$clickscount )
	{
		$this->Id 		=  $lId;
		$this->StartDt = $sStartDt;
		$this->EndDt = $sEndDt;
		$this->FileName =  $sFileName;
		$this->URL 		=  $sURL;
		$this->Active 	=  $sActive;
		$this->SeqNo	=  $sSeqNo;
		$this->Main	    =  $sMain;
		$this->Banglore	=  $sBanglore;
		$this->Chennai	=  $sChennai;
		$this->Delhi	=  $sDelhi;
		$this->Hyderabad=  $sHyderabad;
		$this->Mumbai	=  $sMumbai;
		$this->Pune 	=  $sPune;
		$this->Kolkata 	=  $sKolkata;
        $this->Jaipur 	=  $sJaipur;
		$this->Goa 	=  $sGoa;
		$this->Ahmedabad 	=  $sAhmedabad;
		$this->AllCities 	=  $sAllCities;
		$this->OtherCities 	=  $sOtherCities;
        $this->CategoryId = $sCategoryId;
		$this->clickscount = $clickscount;
	}

	//----------------------------------------------------------------------------------------------------

	public function Save()
	{
		$Success = FALSE;

		try
		{
			$Global=new cGlobal();
			$Conn = new mysqli($Global->DBServerNameOnly, $Global->DBUserName,$Global->DBPassword,$Global->DBIniCatalog,$Global->portNumber);
			//$Conn->connect();


			if ($Conn->errno > 0)
			{
				throw new Exception("Could not connect to DB. Error: " . $Conn->error);
			}

			if (!$Conn)
			{
				throw new Exception("Could not connect to DB");
			}

			if ($this->Id > 0)		//Edited Record: Update
			{
				$EditedRecord = TRUE;
			}
			else
			{
				$EditedRecord = FALSE;
			}

			$myQuery = " StartDt = ?, EndDt = ?, FileName = ?, URL = ?, Active = ?, SeqNo = ?, Main = ? , Banglore = ? , Chennai = ? , Delhi = ? , Hyderabad = ? , Mumbai = ? , Pune = ?, Kolkata = ?,Goa=?,Ahmedabad = ?,Jaipur = ?, AllCities = ?, OtherCities = ?,  CategoryId=?, clickscount=? ";
				
			if ($EditedRecord)
			{
				$myQuery = "UPDATE " . $this->TableNm . " SET " . $myQuery . " WHERE Id = ?";
			}
			else	//New Record: Insert
			{
				$myQuery = "INSERT INTO " . $this->TableNm . " SET " . $myQuery;
			}

			$stmt = $Conn->stmt_init();    // Create a statement object

			$Success = $stmt->prepare($myQuery);    // Prepare the statement for execution
			if (! $Success) throw new Exception("Statement couldn't be prepared. Error: " . $Conn->error);


			if ($EditedRecord)
			{
				$Success = $stmt->bind_param("ssssiiiiiiiiiiiiiiiiii", $this->StartDt, $this->EndDt, $this->FileName, $this->URL, $this->Active, $this->SeqNo, $this->Main, $this->Banglore, $this->Chennai, $this->Delhi, $this->Hyderabad, $this->Mumbai, $this->Pune, $this->Kolkata,$this->Goa,$this->Ahmedabad,$this->Jaipur, $this->AllCities,  $this->OtherCities, $this->CategoryId, $this->clickscount, $this->Id);    // Bind the parameters
			}
			else
			{
				$Success = $stmt->bind_param("ssssiiiiiiiiiiiiiiiii", $this->StartDt, $this->EndDt, $this->FileName, $this->URL, $this->Active, $this->SeqNo, $this->Main, $this->Banglore, $this->Chennai, $this->Delhi, $this->Hyderabad, $this->Mumbai, $this->Pune, $this->Kolkata,$this->Goa,$this->Ahmedabad,$this->Jaipur,$this->AllCities,  $this->OtherCities, $this->CategoryId,$this->clickscount);
		  // Bind the parameters
			}

			if (! $Success) throw new Exception("Parameters couldn't be bound. Error: " . $Conn->error);

			$Success = $stmt->execute();		//Execute Statement
			if (! $Success) throw new Exception("Statement couldn't be Executed. Error: " . $Conn->error);

			$AffectedRows = $stmt->affected_rows;
			if ($AffectedRows > 0)
			{
				if (! $EditedRecord)	$this->Id = $Conn->insert_id;
			}
			else
			{
				$this->Id = 0;
			}

			$Conn->close();
			$stmt->close();

			return $this->Id;
		}
		catch (Exception $Ex)
		{
			throw $Ex;
		}
	}		//Save

	//---------------------------------------------------------------------------------------------------------------

	public function Load()
	{
		$Success = FALSE;

		try
		{
			$Global=new cGlobal();

			$Conn = new mysqli($Global->DBServerNameOnly, $Global->DBUserName,$Global->DBPassword,$Global->DBIniCatalog,$Global->portNumber);
			//$Conn->connect();


			if ($Conn->errno > 0)
			{
				throw new Exception("Could not connect to DB. Error: " . $Conn->error);
			}

			if (!$Conn)
			{
				throw new Exception("Could not connect to DB");
			}

			//echo $this->Id;

			$myQuery="SELECT Id, StartDt, EndDt, FileName, URL, Active, SeqNo, Main, Banglore, Chennai, Delhi, Hyderabad , Mumbai, Pune, Kolkata,AllCities,OtherCities, CategoryId, clickscount   FROM ".$this->TableNm." WHERE Id = ?";
			$stmt = $Conn->stmt_init();    // Create a statement object

			$Success = $stmt->prepare($myQuery);    // Prepare the statement for execution
			if (! $Success) throw new Exception("Statement couldn't be prepared. Error: " . $Conn->error);

			$Success = $stmt->bind_param("i", $this->Id);    // Bind the parameters
			if (! $Success) throw new Exception("Parameters couldn't be bound. Error: " . $Conn->error);

			$Success = $stmt->execute();		//Execute Statement
			if (! $Success) throw new Exception("Statement couldn't be Executed. Error: " . $Conn->error);

			$Success = $stmt->bind_result($this->Id, $this->StartDt, $this->EndDt,  $this->FileName, $this->URL, $this->Active, $this->SeqNo,$this->Main, $this->Banglore, $this->Chennai, $this->Delhi, $this->Hyderabad, $this->Mumbai, $this->Pune, $this->Kolkata, $this->AllCities, $this->OtherCities, $this->CategoryId, $this->clickscount);	// Bind the result parameters

			if ($stmt->fetch())		//Fetch values actually into bound fields of the result
			{
				$Success = TRUE;
			}
			else
			{
				$Success = FALSE;
			}

			$Conn->close();
			$stmt->close();

			return $Success;
		}
		catch (Exception $Ex)
		{
			throw $Ex;
		}
	}

	//----------------------------------------------------------------------------------------------------

	public function Delete()
	{
		$Success = FALSE;

		try
		{
			$Global=new cGlobal();
			$Conn = new mysqli($Global->DBServerNameOnly, $Global->DBUserName,$Global->DBPassword,$Global->DBIniCatalog,$Global->portNumber);
			//$Conn->connect();


			if ($Conn->errno > 0)
			{
				throw new Exception("Could not connect to DB. Error: " . $Conn->error);
			}

			if (!$Conn)
			{
				throw new Exception("Could not connect to DB");
			}

			$myQuery="DELETE FROM " . $this->TableNm . " WHERE Id = ?";

			$stmt = $Conn->stmt_init();    // Create a statement object

			$Success = $stmt->prepare($myQuery);    // Prepare the statement for execution
			if (! $Success) throw new Exception("Statement couldn't be prepared. Error: " . $Conn->error);


			$Success = $stmt->bind_param("i", $this->Id);    // Bind the parameters
			if (! $Success) throw new Exception("Parameters couldn't be bound. Error: " . $Conn->error);


			$Success = $stmt->execute();		//Execute Statement
			if (! $Success) throw new Exception("Statement couldn't be Executed. Error: " . $Conn->error);

			$AffectedRows = $stmt->affected_rows;
			if ($AffectedRows > 0)
			{
				$Success = TRUE;
			}
			else
			{
				$Success = FALSE;
			}

			$Conn->close();
			$stmt->close();

			return $Success;
		}
		catch (Exception $Ex)
		{
			throw $Ex;
		}
	}		//Delete

	//---------------------------------------------------------------------------------------------------------------

}		//Class



?>