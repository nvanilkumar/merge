<?php
include_once("cGlobali.php");

class cEventSignup
{
	private $TableNm = "EventSignup";

	public $Id = 0;
	public $UserID = 0;
	public $EventId = 0;
	public $Qty = 0;
	public $Fees = 0.0;
	public $Ccharge = 0.0;
	public $STax = 0.0;
	public $EntTax = 0.0;
	public $DAmount = 0.0;
    public $referralDAmount = 0.00;
	public $CurrencyId = 0;
	public $Name = "";
	public $EMail = "";
	public $Phone = "";
	public $Address = "";
	public $CityId = 0;
	public $StateId = 0;
	public $CountryId = 0;
	public $PIN = "";
	public $PromotionCode = "";
	public $PromoCodeId = 0;
	public $PaymentModeId = 0;
	public $PaymentTransId = "";
	public $SignupDt;
    public $source;
    public $userPointsId;
    public $referralCode;
    public $ucode;
    public $paypal_converted_amount;
    public $PaymentGateway;
	//----------------------------------------------------------------------------------------------------

	public function __construct($lId, $lUserID, $iEventId, $iQty, $dFees, $dCcharge, $dSTax, $dEntTax, $dDAmount,$totalReferralDiscAmt, $byCurrencyId, $sName, $sEMail, $sPhone,
	$sAddress, $lCityId, $lStateId, $byCountryId, $sPIN, $sPromotionCode, $iPromoCodeId, $byPaymentModeId, $sPaymentTransId, $dtSignupDt,$sSource,$userPointsId,$referralCode,$ucode)
	{
		$this->Id =  $lId;
		$this->UserID =  $lUserID;
		$this->EventId =  $iEventId;
		$this->Qty =  $iQty;
		$this->Fees =  $dFees;
		$this->Ccharge =  $dCcharge;
		$this->STax =  $dSTax;
		$this->EntTax =  $dEntTax;
		$this->DAmount =  $dDAmount;
        $this->referralDAmount = $totalReferralDiscAmt;
		$this->CurrencyId =  $byCurrencyId;
		$this->Name = $sName;
		$this->EMail = $sEMail;
		$this->Phone = $sPhone;
		$this->Address = $sAddress;
		$this->CityId = $lCityId ;
		$this->StateId =  $lStateId;
		$this->CountryId =  $byCountryId;
		$this->PIN = $sPIN;
		$this->PromotionCode = $sPromotionCode;
		$this->PromoCodeId= $iPromoCodeId;
		$this->PaymentModeId = $byPaymentModeId;
		$this->PaymentTransId = $sPaymentTransId;
		$this->SignupDt = $dtSignupDt;
                $this->source=$sSource;
                $this->userPointsId = $userPointsId;
                $this->referralCode = $referralCode;
                $this->ucode=$ucode;
	}

	//----------------------------------------------------------------------------------------------------

	public function Save()
	{
		$Success = FALSE;

		try
		{
			$Globali=new cGlobali();
			//$Globali->dbconn = new mysqli($Global->DBServerNameOnly, $Global->DBUserName,$Global->DBPassword,$Global->DBIniCatalog,$Global->portNumber);
			//$Globali->dbconn->connect();


			if ($Globali->dbconn->errno > 0)
			{
				throw new Exception("Could not connect to DB. Error: " . $Globali->dbconn->error);
			}

			if (!$Globali->dbconn)
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

			$myQuery = " UserID = ?, EventId = ?, Qty = ?, Fees = ?, Ccharge = ?, STax = ?, EntTax=?, DAmount = ?,referralDAmount = ?, CurrencyId = ?,
					Name = ?, EMail = ?, Phone = ?, Address = ?, CityId = ?, StateId = ?, CountryId = ?, PIN = ?,
					PromotionCode = ?, PromoCodeId = ?, PaymentModeId = ?, PaymentTransId = ?, SignupDt = ?, source = ?, userpointsid = ?, referralcode = ?, ucode = ?";
				
			if ($EditedRecord)
			{
				$myQuery = "UPDATE " . $this->TableNm . " SET " . $myQuery . " WHERE Id = ?";
			}
			else	//New Record: Insert
			{
				$myQuery = "INSERT INTO " . $this->TableNm . " SET " . $myQuery;
			}

			$stmt = $Globali->dbconn->stmt_init();    // Create a statement object

			$Success = $stmt->prepare($myQuery);    // Prepare the statement for execution
			if (! $Success) throw new Exception("Statement couldn't be prepared. Error: " . $Globali->dbconn->error);


			if ($EditedRecord)
			{
				$Success = $stmt->bind_param("iiiddddddissssiiissiississss", $this->UserID , $this->EventId , $this->Qty , $this->Fees , $this->Ccharge,$this->STax,$this->EntTax,$this->DAmount,$this->referralDAmount,$this->CurrencyId , $this->Name, $this->EMail, $this->Phone, $this->Address, $this->CityId , $this->StateId , $this->CountryId , $this->PIN, $this->PromotionCode, $this->PromoCodeId, $this->PaymentModeId, $this->PaymentTransId, $this->SignupDt,   $this->source,$this->userPointsId,$this->referralCode,$this->ucode,$this->Id);    // Bind the parameters
			}
			else
			{
			
				$Success = $stmt->bind_param("iiiddddddissssiiissiissssss", $this->UserID , $this->EventId , $this->Qty , $this->Fees , $this->Ccharge,$this->STax,$this->EntTax,$this->DAmount,$this->referralDAmount, $this->CurrencyId , $this->Name, $this->EMail, $this->Phone, $this->Address, $this->CityId , $this->StateId , $this->CountryId , $this->PIN, $this->PromotionCode, $this->PromoCodeId, $this->PaymentModeId, $this->PaymentTransId, $this->SignupDt, $this->source,$this->userPointsId,$this->referralCode,$this->ucode);
		  // Bind the parameters
			}

			if (! $Success) throw new Exception("Parameters couldn't be bound. Error: " . $Globali->dbconn->error);


			$Success = $stmt->execute();		//Execute Statement
			if (! $Success) throw new Exception("Statement couldn't be Executed. Error: " . $Globali->dbconn->error);

			$AffectedRows = $stmt->affected_rows;
			if ($AffectedRows > 0)
			{
				if (! $EditedRecord)	$this->Id = $Globali->dbconn->insert_id;
			}
			else
			{
				$this->Id = 0;
			}

			$Globali->dbconn->close();
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
			$Globali=new cGlobali();
			//$Globali->dbconn = new mysqli($Global->DBServerNameOnly, $Global->DBUserName,$Global->DBPassword,$Global->DBIniCatalog,$Global->portNumber);
			//$Globali->dbconn->connect();


			if ($Globali->dbconn->errno > 0)
			{
				throw new Exception("Could not connect to DB. Error: " . $Globali->dbconn->error);
			}

			if (!$Globali->dbconn)
			{
				throw new Exception("Could not connect to DB");
			}

			//echo $this->Id;

			$myQuery="SELECT Id, UserID, EventId, Qty, Fees, Ccharge, STax,EntTax, DAmount, CurrencyId, Name, EMail, Phone, Address, CityId, StateId, CountryId, PIN, PromotionCode, PromoCodeId, PaymentModeId, PaymentTransId, SignupDt, source,paypal_converted_amount,PaymentGateway  FROM " . $this->TableNm . " WHERE Id = ?";
			$stmt = $Globali->dbconn->stmt_init();    // Create a statement object

			$Success = $stmt->prepare($myQuery);    // Prepare the statement for execution
			if (! $Success) throw new Exception("Statement couldn't be prepared. Error: " . $Globali->dbconn->error);

			$Success = $stmt->bind_param("i", $this->Id);    // Bind the parameters
			if (! $Success) throw new Exception("Parameters couldn't be bound. Error: " . $Globali->dbconn->error);

			$Success = $stmt->execute();		//Execute Statement
			if (! $Success) throw new Exception("Statement couldn't be Executed. Error: " . $Globali->dbconn->error);

			$Success = $stmt->bind_result($this->Id , $this->UserID , $this->EventId , $this->Qty , $this->Fees , $this->Ccharge, $this->STax, $this->EntTax, $this->DAmount, $this->CurrencyId , $this->Name, $this->EMail, $this->Phone, $this->Address, $this->CityId , $this->StateId , $this->CountryId , $this->PIN, $this->PromotionCode, $this->PromoCodeId, $this->PaymentModeId, $this->PaymentTransId, $this->SignupDt, $this->source,  $this->paypal_converted_amount,  $this->PaymentGateway);	// Bind the result parameters

			if ($stmt->fetch())		//Fetch values actually into bound fields of the result
			{
				$Success = TRUE;
			}
			else
			{
				$Success = FALSE;
			}

			$Globali->dbconn->close();
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
			$Globali=new cGlobali();
			//$Globali->dbconn = new mysqli($Global->DBServerNameOnly, $Global->DBUserName,$Global->DBPassword,$Global->DBIniCatalog,$Global->portNumber);
			//$Globali->dbconn->connect();


			if ($Globali->dbconn->errno > 0)
			{
				throw new Exception("Could not connect to DB. Error: " . $Globali->dbconn->error);
			}

			if (!$Globali->dbconn)
			{
				throw new Exception("Could not connect to DB");
			}

			$myQuery="DELETE FROM " . $this->TableNm . " WHERE Id = ?";

			$stmt = $Globali->dbconn->stmt_init();    // Create a statement object

			$Success = $stmt->prepare($myQuery);    // Prepare the statement for execution
			if (! $Success) throw new Exception("Statement couldn't be prepared. Error: " . $Globali->dbconn->error);


			$Success = $stmt->bind_param("i", $this->Id);    // Bind the parameters
			if (! $Success) throw new Exception("Parameters couldn't be bound. Error: " . $Globali->dbconn->error);


			$Success = $stmt->execute();		//Execute Statement
			if (! $Success) throw new Exception("Statement couldn't be Executed. Error: " . $Globali->dbconn->error);

			$AffectedRows = $stmt->affected_rows;
			if ($AffectedRows > 0)
			{
				$Success = TRUE;
			}
			else
			{
				$Success = FALSE;
			}

			$Globali->dbconn->close();
			$stmt->close();

			return $Success;
		}
		catch (Exception $Ex)
		{
			throw $Ex;
		}
	}		//Delete
       
        //function to fetch different transactions count
		//org detail report query
            function getTransactionsByEventId($eventid)
            {
               $Globali=new cGlobali();
                    $query="select 
                                IFNULL(SUM(CASE
                                    WHEN
                                        es.PaymentTransId not in ('A1' , 'Offline','SpotCash','SpotCard')
                                            and estd.TicketAmt > 0
                                    THEN
                                        (estd.NumOfTickets)
                                    ELSE 0
                                END),0) as 'EBS_COUNT',
                                IFNULL(SUM(CASE
                                    WHEN
                                        es.PaymentTransId = 'SpotCash'
                                            and estd.TicketAmt > 0
                                    THEN
                                        (estd.NumOfTickets)
                                    ELSE 0
                                END),0) as 'spot_cash',
                                IFNULL(SUM(CASE
                                    WHEN
                                        es.PaymentTransId ='SpotCard'
                                            and estd.TicketAmt > 0
                                    THEN
                                        (estd.NumOfTickets)
                                    ELSE 0
                                END),0) as 'spot_card',
                                IFNULL(SUM(CASE
                                    WHEN
                                        ((es.Fees = 0 and (es.PromotionCode = 'FreeTicket' or es.PromotionCode = 'X')) or (estd.TicketAmt = 0 and es.PaymentTransId != 'A1'))
                                    THEN
                                        (estd.NumOfTickets)
                                    ELSE 0
                                END),0) as 'FREE_COUNT',
                                IFNULL(SUM(CASE
                                    WHEN
                                        es.PaymentTransId = 'Offline'
                                            and estd.TicketAmt > 0
                                    THEN
                                        (estd.NumOfTickets)
                                    ELSE 0
                                END),0) as 'OFFLINE_COUNT',
                                IFNULL(SUM(CASE
                                    WHEN
                                        es.PaymentGateway = 'CashonDelivery'
                                            and eChecked = 'Verified'
                                            and estd.TicketAmt > 0
                                    THEN
                                        (estd.NumOfTickets)
                                    ELSE 0
                                END),0) as 'COD_COUNT'
                            FROM
                                EventSignup as es
                                    Inner Join
                                eventsignupticketdetails estd ON es.id = estd.eventsignupid
                                    Inner Join
                                tickets t ON t.id = estd.ticketid
                                    Left Outer Join
                                ChqPmnts ch ON es.id = ch.eventsignupid
                            WHERE
                                es.EventId = '".$Globali->dbconn->real_escape_string($eventid)."'
                                    and es.eChecked not in ('Canceled' , 'Refunded')
                            order by es.id desc;";
                    $res=$Globali->SelectQuery($query);
                    $all_count=$res[0]['EBS_COUNT']+$res[0]['COD_COUNT']+$res[0]['FREE_COUNT']+$res[0]['OFFLINE_COUNT']+$res[0]['spot_cash']+$res[0]['spot_card'];
                    $transCountArr=array('card'=>$res[0]['EBS_COUNT'],'cod'=>$res[0]['COD_COUNT'],
                        'free'=>$res[0]['FREE_COUNT'],'all'=>$all_count,'incomplete'=>0,
                        'offline'=>$res[0]['OFFLINE_COUNT'],'spot_cash'=>$res[0]['spot_cash'],'spot_card'=>$res[0]['spot_card']);
                    return $transCountArr;
            }
            
            //function to fetch Incomplete transactions By event id
            function getIncompleteTransactionsByEventId($eventid)
            {
                    $Globali=new cGlobali();
					
					
//					$sqlcount = "SELECT s.Id
//FROM EventSignup As s
//	Inner Join events As e on s.EventId=e.Id
//	Inner Join currencies as c on s.CurrencyId=c.Id
//	Inner Join eventsignupticketdetails As estd on s.id=estd.eventsignupId
//WHERE   ((s.PaymentModeId=1 and s.PaymentTransId='A1') or s.PaymentModeId!=2 ) and s.Fees!=0 
//and s.EventId='".$Globali->dbconn->real_escape_string($eventid)."' and e.Published=1
//and s.EMail not in (SELECT DISTINCT EMail FROM EventSignup WHERE 1 and ((PaymentModeId=1 and PaymentTransId!='A1') or PaymentModeId=2 ) AND EventId='".$Globali->dbconn->real_escape_string($eventid)."' 
//and DATE(SignupDt)=DATE(s.SignupDt)) GROUP BY s.Email,DATE(s.SignupDt),s.EventId ORDER BY s.Id DESC ";


                    /*echo $sqlcount="SELECT count( s.Id ) AS fCount,e.SalesId FROM EventSignup As s,events As e 
                               WHERE s.EventId=e.Id 
                               and ((s.PaymentModeId=1 and s.PaymentTransId='A1') or s.PaymentModeId!=2 or s.PromotionCode ='X' ) and s.Fees!=0 and s.EMail not in (SELECT EMail FROM EventSignup WHERE 1 and ((PaymentModeId=1 and PaymentTransId!='A1') or PaymentModeId=2 or PromotionCode !='X') AND EventId='".$Globali->dbconn->real_escape_string($eventid)."' ) AND EventId='".$Globali->dbconn->real_escape_string($eventid)."' AND eStatus='Open'  GROUP BY s.Id,DATE(s.SignupDt) ORDER BY s.Id DESC";*/
//                    $ResCount = $Globali->SelectQuery($sqlcount);
//					
//					$resNewArr=array();
//					foreach($ResCount as $key=>$val)
//					{
//						$resNewArr[]=$val['Id'];
//					}
//					
//                    $ResCount=array_unique($resNewArr);
					return 0;
            }
            

	//---------------------------------------------------------------------------------------------------------------

}		//Class


/* >>>Useful notes>>>
 * >>Bind_Param Types (First Argument)
 • i: All INTEGER types
 • d: The DOUBLE and FLOAT types
 • b: The BLOB types
 • s: All other types (including strings)
 * <<Bind_Param Types<<
 *
 *
 */

?>

