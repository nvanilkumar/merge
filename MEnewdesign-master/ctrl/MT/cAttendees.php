<?php
include_once("cGlobali.php");

class cAttendees
{
	private $TableNm = "Attendees";

	public $Id = 0;
	public $EventSignupId = 0;
	public $Name = "";
	public $EMail = "";
	public $Company= "";
	public $Phone = "";
	
	//----------------------------------------------------------------------------------------------------

	public function __construct($lId, $lEventSignupId, $sName, $sEMail, $sCompany, $sPhone )
	{
		$this->Id = $lId;
		$this->EventSignupId =  $lEventSignupId;
		$this->Name = $sName;
		$this->EMail = $sEMail;
		$this->Company = $sCompany;
		$this->Phone = $sPhone;
	}

	//----------------------------------------------------------------------------------------------------

	public function Save()
	{
		$Success = FALSE;

		try
		{
			$Globali=new cGlobali();
			//$Conn = new mysqli($Globali->DBServerNameOnly, $Globali->DBUserName,$Globali->DBPassword,$Globali->DBIniCatalog,$Globali->portNumber);
			//$Conn->connect();

                       
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

			$myQuery = " EventSignupId = ?, Name = ?, EMail = ?, Company = ?, Phone = ? ";
				
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
				$Success = $stmt->bind_param("issssi", $this->EventSignupId, $this->Name, $this->EMail, $this->Company, $this->Phone, $this->Id);    // Bind the parameters
			}
			else
			{
				$Success = $stmt->bind_param("issss", $this->EventSignupId, $this->Name, $this->EMail, $this->Company, $this->Phone);
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

	public function CountAttendees($qId)
	{
		$Success = FALSE;

		try
		{

			$Globali=new cGlobali();
include_once("../includes/config.php");	      
// $myQuery = "SELECT DISTINCT e.Id, e.Title, (SELECT COUNT( a.Id )FROM Attendees AS a WHERE a.EventSignupId = e.Id) AS AttendeeCount FROM events AS e";
	 	
		$myQuery = "SELECT * FROM Attendees WHERE EventSignupId  IN(SELECT `Id` FROM `EventSignup` WHERE `EventId`= ".$qId.") ";
	 	   $Result = mysql_query($myQuery); 
		     $count = mysql_num_rows($Result);
			   if($count!=0)
		   	   return $count;
			   else
			   return(0);
		}
		catch (Exception $Ex)
		{
			throw $Ex;
		}
	}

//----------------------------------------------------------------------------------------------------

	public function Load()
	{
		$Success = FALSE;

		try
		{
			$Globali=new cGlobali();
			//$Conn = new mysqli($Globali->DBServerNameOnly, $Globali->DBUserName,$Globali->DBPassword,$Globali->DBIniCatalog,$Globali->portNumber);
			//$Conn->connect();


			if ($Globali->dbconn->errno > 0)
			{
				throw new Exception("Could not connect to DB. Error: " . $Globali->dbconn->error);
			}

			if (!$Globali->dbconn)
			{
				throw new Exception("Could not connect to DB");
			}

			//echo $this->Id;

			$myQuery="SELECT Id, EventSignupId, Name, EMail, Company, Phone FROM " . $this->TableNm . " WHERE Id = ?";
			$stmt = $Globali->dbconn->stmt_init();    // Create a statement object

			$Success = $stmt->prepare($myQuery);    // Prepare the statement for execution
			if (! $Success) throw new Exception("Statement couldn't be prepared. Error: " . $Globali->dbconn->error);

			$Success = $stmt->bind_param("i", $this->Id);    // Bind the parameters
			if (! $Success) throw new Exception("Parameters couldn't be bound. Error: " . $Globali->dbconn->error);

			$Success = $stmt->execute();		//Execute Statement
			if (! $Success) throw new Exception("Statement couldn't be Executed. Error: " . $Globali->dbconn->error);

			$Success = $stmt->bind_result($this->Id, $this->EventSignupId, $this->Name, $this->EMail, $this->Company, $this->Phone);	// Bind the result parameters

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
public function LoadAllByEventId($qId,$filterType=0,$ticketId=NULL)
	{
		$Success = FALSE;
		$sqlLimit=$uniquecondition=NULL;
		$filterTypeEx=explode("^^^^",$filterType);
		$currencyType=$filterTypeEx[2];
		$mode=$filterType[1];
		if (count($filterTypeEx) >= 2) {
            $isfree = $filterTypeEx[0];
			if($mode!='referral'){
            $promoter = $filterTypeEx[1];
				if (strlen(trim($promoter)) > 0) {
					if($promoter=="mEraEvents"){
						$uniquecondition=" AND es.ucode IS NULL and es.referralDAmount=0 ";
					}
					elseif($promoter=="offline"){
						$uniquecondition=" AND es.ucode like 'OFFLINE_%' ";
					}else{
					$uniquecondition = " and es.ucode='" . $promoter . "' ";
					}
				} else {
					$uniquecondition = " and (es.ucode!='' and es.ucode!='organizer')  ";
				}
			}
        } else {
            $isfree = $filterType;
        }
		if(!empty($currencyType)){
			$currencyCondition=" and c.code='".$currencyType."' ";
		}
		
		
		
		
		
		
		try
		{    		$Globali=new cGlobali();
	
		//mysql_connect($Globali->DBServerName, $Globali->DBUserName, $Globali->DBPassword);
		//mysql_select_db($Globali->DBIniCatalog);	
	//select * from Attendees where eventsignupId IN(SELECT `Id` FROM `EventSignup` WHERE `EventId`=37)

		$myQuery=$myQueryCount=$ticketCond=NULL;
		if(!empty($ticketId))
            $ticketCond = " and t.Id='".$ticketId."'";
		
		//taking care of old transactions	
		
		if (strtolower($_SERVER['HTTP_HOST'])=='stage.meraevents.com'  || strtolower($_SERVER['HTTP_HOST'])=='stage.meraevents.com:8674') {
			$sqlAdd=" IF(es.id>137214,estd.discountamount,(es.discountamount+es.referraldiscountamount)) 'normalDiscount',
					IF(es.id>137214,IF(estd.totalamount=0,0,((estd.totalamount+estd.totaltaxamount-estd.discountamount-estd.bulkdiscountamount-estd.referraldiscountamount)/estd.ticketquantity)),(es.totalamount/es.quantity) - (es.eventextrachargeamount/es.quantity)) 'Paid' ,";
		}
		else{
			$sqlAdd=" IF(es.id>151909,estd.discountamount,(es.discountamount+es.referraldiscountamount)) 'normalDiscount',
					IF(es.Id>151909,IF(estd.totalamount=0,0,((estd.totalamount+estd.totaltaxamount-estd.discountamount-estd.bulkdiscountamount-estd.referraldiscountamount)/estd.ticketquantity)),(es.totalamount/es.quantity) - (es.eventextrachargeamount/es.quantity)) 'Paid' ,";
		}
		
		                
	if($isfree==1) //for paid events es.extrainfo 'PmiRegNo',
	{
		/*   CASE at.customfieldid
	   WHEN 1 THEN at.value as 'Name'
	   WHEN 2 THEN at.value as 'Email'
	   WHEN 3 THEN at.value as 'Mobile'
	   WHEN 4 THEN at.value as 'Address'
	   END 'Phone', echo*/
		 $myQuery.="select es.id 'EventSIgnupId', es.referraldiscountamount ,                         
       es.signupdate 'Signup Date',es.paymentgatewayid,
              CASE WHEN paymentgatewayid=2 THEN 'Cash on Delivery'
                   WHEN paymentmodeid=2 and paymenttransactionid='A1' THEN ch.chequenumber
          ELSE es.paymenttransactionid 
          END 'paymenttransactionid', 
       es.discount 'PromotionCode',
	   estd.discountcode 'newPromotionCode',
       CASE es.paymentmodeid 
			WHEN 1 THEN CASE es.totalamount 
							WHEN 0 THEN 
								CASE es.paymentstatus WHEN 'Verified' THEN 'Successful' ELSE 'Pending' END
						ELSE
							CASE es.paymentstatus WHEN 'Verified' THEN 'Successful' ELSE 'Captured' END 
						END
	   ELSE 
	   		CASE es.paymentstatus WHEN 'Verified' THEN 'Successful' ELSE 'Pending' END 
	   END 'PaymentStatus',
	 
        a.id 'attendeeId',    
		c.code 'currencyCode',
        es.totalamount,es.paymentmodeid,es.convertedamount,es.barcodenumber,(es.eventextrachargeamount),es.quantity,
          ".$sqlAdd."
t.name,t.price 'ticketPrice',(estd.amount/estd.ticketquantity) 'Amount' ,
estd.ticketquantity, (estd.amount/estd.ticketquantity) 'TicketAmt', estd.bulkdiscountamount, estd.referraldiscountamount, estd.totaltaxamount,
es.promotercode  'promoterCode',
if(es.promotercode like 'OFFLINE_%',p.name,'') AS 'PromoterName'       
FROM attendee as a 
            Inner Join attendeedetail as at on a.id = at.attendeeid
			Inner Join eventsignup as es on a.eventsignupid=es.id
            Inner Join eventsignupticketdetail estd on a.eventsignupid=estd.eventsignupid
            Inner Join ticket t on t.id=estd.ticketid 
            Left Outer Join chequepayments ch on es.id=ch.eventsignupid         
			INNER JOIN currency c on es.fromcurrencyid=c.id 
			Left JOIN promoter p on es.promotercode=p.code and p.eventid=  ".$qId." and p.type='offline' 
    WHERE  es.eventid=  ".$qId."
        and ((es.paymentgatewayid=2) or es.paymenttransactionid!='A1' or (es. paymentmodeid=2 and paymenttransactionid='A1' and es.paymentstatus='Verified')   or es.totalamount=0)
        and es.paymentstatus!='Canceled'
        and es.paymentstatus!='Refunded'
        and a.ticketid=estd.ticketid $uniquecondition $ticketCond $currencyCondition order by es.id desc ".$sqlLimit.""; 
	
	}
	else if($isfree==2)//for free events
	{
		
		
		$myQuery.="select es.id 'EventSIgnupId', es.referralDAmount ,                         
       es.SignupDt 'Signup Date',es.PaymentGateway,es.extrainfo 'PmiRegNo',
              CASE WHEN paymentgateway='CashonDelivery' THEN 'Cash on Delivery'
                   WHEN PaymentModeId=2 and PaymentTransId='A1' THEN ch.chequenumber
          ELSE es.PaymentTransId 
          END 'PaymentTransId', 
       es.PromotionCode 'PromotionCode',
	   estd.promoCode 'newPromotionCode',
       CASE es.PaymentModeId 
			WHEN 1 THEN CASE es.Fees 
							WHEN 0 THEN 
								CASE es.eChecked WHEN 'Verified' THEN 'Successful' ELSE 'Pending' END
						ELSE
							CASE es.eChecked WHEN 'Verified' THEN 'Successful' ELSE 'Captured' END 
						END
	   ELSE 
	   		CASE es.eChecked WHEN 'Verified' THEN 'Successful' ELSE 'Pending' END 
	   END 'PaymentStatus',
       a.name 'UserName' ,
    a.Id 'attendeeId',    
ct.City,s.State,
		c.code 'currencyCode',
       a.field1,
       a.field2,
       a.field3,
       a.field4,
       a.Email 'Email', es.Fees,es.PaymentModeId,es.paypal_converted_amount,(es.Ccharge),es.Qty,
       a.company 'Company',
       a.Phone 'Phone', '' Mobile,
      ".$sqlAdd."
t.name,t.Price 'ticketPrice',(estd.TicketAmt/estd.NumOfTickets) 'Amount' ,
estd.NumOfTickets, (estd.TicketAmt/estd.NumOfTickets) 'TicketAmt', estd.BulkDiscount, estd.ReferralDiscount, estd.ServiceTax,estd.EntTax,
es.ucode  'promoterCode',
if(es.ucode like 'OFFLINE_%',p.username,'') AS 'PromoterName'       
FROM Attendees as a 
            Inner Join EventSignup as es on a.EventSignupId=es.Id
            Inner Join eventsignupticketdetails estd on a.eventsignupid=estd.eventsignupid
            Inner Join tickets t on t.id=estd.ticketid 
            Left Outer Join ChqPmnts ch on es.id=ch.eventsignupid         
			INNER JOIN currencies c on es.CurrencyId=c.Id 
			Left JOIN States s on es.StateId=s.Id   
			Left JOIN Cities ct on es.CityId=ct.Id 
			Left JOIN promoters p on es.ucode=p.promoter and p.eventid=  ".$qId." and p.type='offline' 
    WHERE es.eventid=".$qId."
	and es.eChecked not in ('Canceled','Refunded')
        and a.ticketid=estd.ticketid
	 $uniquecondition $ticketCond $currencyCondition   order by es.id desc ".$sqlLimit.";";
		
			
		
		}
    else if($isfree==3)
    {
                     $myQuery = "SELECT DISTINCT(s.Id) AS 'EventSIgnupId',s.field2 'PmiRegNo', s.EventId,s.Name,s.CityId,s.StateId, s.SignupDt AS 'Signup Date',s.field1, e.Title, s.Qty, s.Fees, s.PaymentTransId,s.PaymentModeId,s.PromotionCode,s.PaymentGateway,s.PromotionCode AS 'PromotionCode',(es.Ccharge),es.Qty,
                        CASE s.eChecked WHEN 'Verified' THEN 'Successful' 
                        ELSE 'Captured' 
                        End 'PaymentStatus',(s.fees - (s.Ccharge/s.Qty)) 'Paid',a.Id AS 'attendeeId'  FROM  EventSignup AS s LEFT JOIN Attendees as a ON s.Id=a.EventSIgnupId, events AS e,currencies c WHERE s.EventId = e.Id and es.CurrencyId=c.Id AND e.UserId = '".$Globali->dbconn->real_escape_string($_SESSION['uid'])."' and ((s.PaymentModeId=1 and s.PaymentTransId='A1') or s.PaymentModeId!=2 or  s.PromotionCode ='X' ) and s.Fees!=0  and s.EMail not in (SELECT EMail FROM EventSignup WHERE 1  and ((PaymentModeId=1 and PaymentTransId!='A1') or PaymentModeId=2 or  PromotionCode !='X')  AND  EventId='".$Globali->dbconn->real_escape_string($qId)."')   AND EventId='".$Globali->dbconn->real_escape_string($qId)."' AND  s.eStatus='Open' GROUP BY s.Id ORDER BY s.Id DESC"; 
                    //$myQuery = "SELECT s.Id AS 'EventSIgnupId', s.EventId,s.CityId,s.StateId, s.SignupDt AS 'Signup Date',s.field1, e.Title, s.Qty, s.Fees, s.PaymentTransId,s.PaymentModeId,s.PromotionCode,s.PaymentGateway FROM  EventSignup AS s, events AS e WHERE s.EventId = e.Id AND e.UserId = '".$Globali->dbconn->real_escape_string($_SESSION['uid'])."' and ((s.PaymentModeId=1 and s.PaymentTransId='A1') or s.PaymentModeId!=2 or  s.PromotionCode ='X' ) and s.Fees!=0  and s.EMail not in (SELECT EMail FROM EventSignup WHERE 1  and ((PaymentModeId=1 and PaymentTransId!='A1') or PaymentModeId=2 or  PromotionCode !='X')  AND  EventId='".$Globali->dbconn->real_escape_string($qId)."')   AND EventId='".$Globali->dbconn->real_escape_string($qId)."' AND a.eventsignupid=s.id AND  s.eStatus='Open' ORDER BY s.Id DESC"; 
                    //$CanTranRES = $Globali->dbconn->SelectQuery($myQuery);
                    //print_r($CanTranRES);
                    //return $CanTranRES;        
                    
                }
    else if($isfree==4)//for referral transactions
    {
                  $myQuery = "select es.id 'EventSIgnupId',es.field2 'PmiRegNo', es.referralDAmount ,es.Qty 'quantity',   estd.Id 'estdid',                       
       es.SignupDt 'Signup Date',es.PaymentGateway,
              CASE WHEN paymentgateway='CashonDelivery' THEN 'Cash on Delivery'
                   WHEN PaymentModeId=2 and PaymentTransId='A1' THEN ch.chqno
          ELSE es.PaymentTransId 
          END 'PaymentTransId', 
       es.PromotionCode 'PromotionCode',
	   estd.promoCode 'newPromotionCode',
       CASE es.PaymentModeId 
			WHEN 1 THEN CASE es.Fees 
							WHEN 0 THEN 
								CASE es.eChecked WHEN 'Verified' THEN 'Successful' ELSE 'Pending' END
						ELSE
							CASE es.eChecked WHEN 'Verified' THEN 'Successful' ELSE 'Captured' END 
						END
	   ELSE 
	   		CASE es.eChecked WHEN 'Verified' THEN 'Successful' ELSE 'Pending' END 
	   END 'PaymentStatus',
       a.name 'UserName' ,
    a.Id 'attendeeId',    
ct.City,s.State,
		c.code 'currencyCode',
       a.field1,
       a.field2,
       a.field3,
       a.field4,
       a.Email 'Email', es.Fees,es.PaymentModeId,es.paypal_converted_amount,(es.Ccharge),es.Qty,
       a.company 'Company',
       a.Phone 'Phone', '' Mobile,
       ".$sqlAdd."

t.name,(estd.TicketAmt/estd.NumOfTickets) 'Amount',
estd.NumOfTickets,(estd.TicketAmt/estd.NumOfTickets) 'TicketAmt', estd.BulkDiscount, estd.ReferralDiscount, estd.ServiceTax  ,estd.EntTax    
FROM Attendees as a 
            Inner Join EventSignup as es on a.EventSignupId=es.Id
            Inner Join eventsignupticketdetails estd on a.eventsignupid=estd.eventsignupid
            Inner Join tickets t on t.id=estd.ticketid 
            Left Outer Join ChqPmnts ch on es.id=ch.eventsignupid
			INNER JOIN currencies c on es.CurrencyId=c.Id
			Left JOIN States s on es.StateId=s.Id   
			Left JOIN Cities ct on es.CityId=ct.Id  
			Left JOIN promoters p on es.ucode=p.promoter and p.eventid=  ".$qId." and p.type='offline'          
    WHERE  es.EventId=  ".$qId."
        and ((es.paymentgateway='CashonDelivery') or es.paymenttransid!='A1' or (es. PaymentModeId=2 and PaymentTransId='A1' and es.echecked='Verified')   or es.Fees=0)
        and es.eChecked!='Canceled'
        and es.eChecked!='Refunded' and es.referralDAmount>0 
        and a.ticketid=estd.ticketid $ticketCond $currencyCondition order by es.id desc;"; 
                                             
                }
	elseif($isfree==5) //for offline bookings
	{
		
		$myQuery.="select es.id 'EventSIgnupId', es.referralDAmount ,                         
       es.SignupDt 'Signup Date',es.PaymentGateway,es.field2 'PmiRegNo',
              CASE WHEN paymentgateway='CashonDelivery' THEN 'Cash on Delivery'
                   WHEN PaymentModeId=2 and PaymentTransId='A1' THEN ch.chqno
          ELSE es.PaymentTransId 
          END 'PaymentTransId', 
       es.PromotionCode 'PromotionCode',
	   estd.promoCode 'newPromotionCode',
       CASE es.PaymentModeId 
			WHEN 1 THEN CASE es.Fees 
							WHEN 0 THEN 
								CASE es.eChecked WHEN 'Verified' THEN 'Successful' ELSE 'Pending' END
						ELSE
							CASE es.eChecked WHEN 'Verified' THEN 'Successful' ELSE 'Captured' END 
						END
	   ELSE 
	   		CASE es.eChecked WHEN 'Verified' THEN 'Successful' ELSE 'Pending' END 
	   END 'PaymentStatus',
       a.name 'UserName' ,
    a.Id 'attendeeId',    
ct.City,s.State,
		c.code 'currencyCode',
       a.field1,
       a.field2,
       a.field3,
       a.field4,
       a.Email 'Email', es.Fees,es.PaymentModeId,es.paypal_converted_amount,(es.Ccharge),es.Qty,
       a.company 'Company',
       a.Phone 'Phone', '' Mobile,
      ".$sqlAdd."
t.name,t.Price 'ticketPrice',(estd.TicketAmt/estd.NumOfTickets) 'Amount' ,
estd.NumOfTickets, (estd.TicketAmt/estd.NumOfTickets) 'TicketAmt', estd.BulkDiscount, estd.ReferralDiscount, estd.ServiceTax,estd.EntTax,
es.ucode  'promoterCode' ,
if(es.ucode like 'OFFLINE_%',p.username,'') AS 'PromoterName'    
FROM Attendees as a 
            Inner Join EventSignup as es on a.EventSignupId=es.Id
            Inner Join eventsignupticketdetails estd on a.eventsignupid=estd.eventsignupid
            Inner Join tickets t on t.id=estd.ticketid 
            Left Outer Join ChqPmnts ch on es.id=ch.eventsignupid         
			INNER JOIN currencies c on es.CurrencyId=c.Id   
			Left JOIN States s on es.StateId=s.Id   
			Left JOIN Cities ct on es.CityId=ct.Id
			Left JOIN promoters p on es.ucode=p.promoter and p.eventid=  ".$qId." and p.type='offline'
    WHERE  es.EventId=  ".$qId."
        and ((es.paymentgateway='CashonDelivery') or es.paymenttransid='offline' or (es. PaymentModeId=2 and PaymentTransId='A1' and es.echecked='Verified')   or es.Fees=0)
        and es.eChecked!='Canceled'
        and es.eChecked!='Refunded'
        and a.ticketid=estd.ticketid $uniquecondition $ticketCond $currencyCondition order by es.id desc ".$sqlLimit.";"; 
	
	}
		else
		{
			$myQuery="SELECT a.*,s.field2 'PmiRegNo', FROM Attendees as a,EventSignup as s WHERE a.EventSignupId=s.Id and s.EventId= ".$qId."   order by a.EventSignupId desc ";
		}

		//$myQuery.=" order by es.id desc"; 
		 

		//echo $myQuery."<br><br>";
			
			$Result = $Globali->dbconn->query($myQuery); 
			return $Result;
		}
		catch (Exception $Ex)
		{
			throw $Ex;
		}
	}
	
	
	//----------------------------------------------------------------------------------------------------



//----------------------------------------------------------------------------------------------------
public function LoadAllTransByEventId($qId,$filterType=0,$ticketId=NULL)
	{
		$Success = FALSE;
		$sqlLimit=$uniquecondition=NULL;
		
		$filterTypeEx=explode("^^^^",$filterType);
		$currencyType=$filterTypeEx[2];
		$mode=$filterTypeEx[1];
		if (count($filterTypeEx) >= 2) {
			$isfree = $filterTypeEx[0];
			if($mode!='referral'){
				$promoter = $filterTypeEx[1];
				if (strlen(trim($promoter)) > 0) {
					if($promoter=="mEraEvents"){
						$uniquecondition=" AND es.ucode IS NULL and es.referralDAmount=0 ";
					}
					elseif($promoter=="offline"){
						$uniquecondition=" AND es.ucode like 'OFFLINE_%' ";
					}
                                        elseif($promoter=="online"){
						$uniquecondition=" AND es.ucode NOT like 'OFFLINE_%' AND es.ucode!='organizer' AND es.ucode!=''";
					}else{
					$uniquecondition = " and es.ucode='" . $promoter . "' ";
					}
				} else {
					$uniquecondition = " and (es.ucode!='' and es.ucode!='organizer' and es.ucode NOT LIKE 'OFFLINE_%')  ";
				}
			}
        } else {
            $isfree = $filterType;
        }
		if(!empty($currencyType)){
			$currencyCondition=" and c.code='".$currencyType."' ";
		}
		
		
		
		
		try
		{    		$Globali=new cGlobali();
	
		
		
	    $myQuery = "";
		$ticketCond=NULL;
	if(!empty($ticketId))
            $ticketCond = " and t.Id='".$ticketId."'";
			
			
	//taking care of old transactions	
		if (strtolower($_SERVER['HTTP_HOST'])=='stage.meraevents.com'  || strtolower($_SERVER['HTTP_HOST'])=='stage.meraevents.com:8674') {
			$sqlAdd=" IF(es.id>137214,estd.discountamount,(es.discountamount+es.referraldiscountamount)) 'normalDiscount',
					IF(es.id>137214,IF(estd.totalamount=0,0,((estd.totalamount+estd.totaltaxamount-estd.discountamount-estd.bulkdiscountamount-estd.referraldiscountamount)/estd.ticketquantity)),(es.totalamount/es.quantity) - (es.eventextrachargeamount/es.quantity)) 'Paid' ,";
		}
		else{
			$sqlAdd=" IF(es.id>151909,estd.discountamount,(es.discountamount+es.referraldiscountamount)) 'normalDiscount',
					IF(es.Id>151909,IF(estd.totalamount=0,0,((estd.totalamount+estd.totaltaxamount-estd.discountamount-estd.bulkdiscountamount-estd.referraldiscountamount)/estd.ticketquantity)),(es.totalamount/es.quantity) - (es.eventextrachargeamount/es.quantity)) 'Paid' ,";
		}
	
		//taking care of old transactions			
	if($isfree==1) //for paid events
	{
		
		$myQuery="select estd.ticketquantity, es.id 'EventSIgnupId', es.referralDAmount ,                         
       es.SignupDt 'Signup Date',es.PaymentGateway,es.field2 'PmiRegNo',
              CASE WHEN paymentgateway='CashonDelivery' THEN 'Cash on Delivery'
                   WHEN PaymentModeId=2 and PaymentTransId='A1' THEN ch.chqno
          ELSE es.PaymentTransId 
          END 'PaymentTransId', 
       es.PromotionCode 'PromotionCode',
	   estd.promoCode 'newPromotionCode',
       CASE es.PaymentModeId 
			WHEN 1 THEN CASE es.Fees 
							WHEN 0 THEN 
								CASE es.eChecked WHEN 'Verified' THEN 'Successful' ELSE 'Pending' END
						ELSE
							CASE es.eChecked WHEN 'Verified' THEN 'Successful' ELSE 'Captured' END 
						END
	   ELSE 
	   		CASE es.eChecked WHEN 'Verified' THEN 'Successful' ELSE 'Pending' END 
	   END 'PaymentStatus',
       a.name 'UserName' ,
    a.Id 'attendeeId',    
ct.City,s.State,
c.code 'currencyCode',
       a.field1,
       a.field2,
       a.field3,
       a.field4,
       a.Email 'Email', es.Fees,es.PaymentModeId,es.paypal_converted_amount,es.BarcodeNumber,es.Ccharge,es.Qty,
       a.company 'Company',
       a.Phone 'Phone', '' Mobile,
	".$sqlAdd."
t.name,estd.TicketAmt 'Amount',
(estd.TicketAmt/estd.NumOfTickets) 'TicketAmt',estd.NumOfTickets, estd.BulkDiscount, estd.ReferralDiscount, estd.ServiceTax,estd.EntTax,
es.ucode  'promoterCode' ,
if(es.ucode like 'OFFLINE_%',p.username,'') AS 'PromoterName'    
FROM Attendees as a 
            Inner Join EventSignup as es on a.EventSignupId=es.Id
            Inner Join eventsignupticketdetails estd on a.eventsignupid=estd.eventsignupid
            Inner Join tickets t on t.id=estd.ticketid 
			INNER JOIN currencies c on es.CurrencyId=c.Id
            Left Outer Join ChqPmnts ch on es.id=ch.eventsignupid  
			Left JOIN States s on es.StateId=s.Id   
			Left JOIN Cities ct on es.CityId=ct.Id 
			Left JOIN promoters p on es.ucode=p.promoter and p.eventid=  ".$qId." and p.type='offline'
			         
    WHERE  es.EventId= ".$qId."
        and ((es.paymentgateway='CashonDelivery') or es.paymenttransid!='A1' or (es. PaymentModeId=2 and PaymentTransId='A1' and es.echecked='Verified')   or es.Fees=0)
        and es.eChecked!='Canceled'
        and es.eChecked!='Refunded'
        and a.ticketid=estd.ticketid  $uniquecondition $ticketCond $currencyCondition  group by t.id, es.id order by es.id desc ".$sqlLimit.";"; 

		
		
		
		
	
	}
	else if($isfree==2)//for free events
	{
			
		$myQuery="select estd.NumOfTickets, es.id 'EventSIgnupId', es.referralDAmount ,                         
       es.SignupDt 'Signup Date',es.PaymentGateway,es.field2 'PmiRegNo',
              CASE WHEN paymentgateway='CashonDelivery' THEN 'Cash on Delivery'
                   WHEN PaymentModeId=2 and PaymentTransId='A1' THEN ch.chqno
          ELSE es.PaymentTransId 
          END 'PaymentTransId', 
       es.PromotionCode 'PromotionCode',
	   estd.promoCode 'newPromotionCode',
       CASE es.PaymentModeId 
			WHEN 1 THEN CASE es.Fees 
							WHEN 0 THEN 
								CASE es.eChecked WHEN 'Verified' THEN 'Successful' ELSE 'Pending' END
						ELSE
							CASE es.eChecked WHEN 'Verified' THEN 'Successful' ELSE 'Captured' END 
						END
	   ELSE 
	   		CASE es.eChecked WHEN 'Verified' THEN 'Successful' ELSE 'Pending' END 
	   END 'PaymentStatus',
       a.name 'UserName' ,
    a.Id 'attendeeId',    
ct.City,s.State,
c.code 'currencyCode',
       a.field1,
       a.field2,
       a.field3,
       a.field4,
       a.Email 'Email', es.Fees,es.PaymentModeId,es.paypal_converted_amount,es.Ccharge,es.Qty,
       a.company 'Company',
       a.Phone 'Phone', '' Mobile,
	".$sqlAdd."
t.name,estd.TicketAmt 'Amount',
(estd.TicketAmt/estd.NumOfTickets) 'TicketAmt',estd.NumOfTickets, estd.BulkDiscount, estd.ReferralDiscount, estd.ServiceTax,estd.EntTax,
es.ucode  'promoterCode' ,
if(es.ucode like 'OFFLINE_%',p.username,'') AS 'PromoterName'    
FROM Attendees as a 
            Inner Join EventSignup as es on a.EventSignupId=es.Id
            Inner Join eventsignupticketdetails estd on a.eventsignupid=estd.eventsignupid
            Inner Join tickets t on t.id=estd.ticketid 
			INNER JOIN currencies c on es.CurrencyId=c.Id
            Left Outer Join ChqPmnts ch on es.id=ch.eventsignupid  
			Left JOIN States s on es.StateId=s.Id   
			Left JOIN Cities ct on es.CityId=ct.Id 
			Left JOIN promoters p on es.ucode=p.promoter and p.eventid=  ".$qId." and p.type='offline'
			         
    WHERE  es.EventId= ".$qId."
		
        and es.eChecked not in ('Canceled','Refunded')
        and a.ticketid=estd.ticketid  $uniquecondition $ticketCond $currencyCondition  group by t.id, es.id order by es.id desc ".$sqlLimit.";"; 



			
		 
		}

    else if($isfree==3) //incomplete transactions
    {
		$myQuery = "SELECT c.code 'currencyCode',count( s.Id ) AS fCount,sum( s.Qty ) AS tktCount,sum( s.Fees ) AS 'Paid',s.Id AS 'EventSIgnupId',s.field2 'PmiRegNo', s.`EventId`,s.`SignupDt` AS 'Signup Date' ,SUM(s.`Qty`) AS 'Qty' ,s.`Fees` ,s.`Name` ,s.`EMail` ,s.`Phone` ,s.`CityId` ,s.`eStatus` ,s.PaymentGateway,s.PaymentTransId,s.PromotionCode AS 'PromotionCode', s.`PaymentStatus` ,e.SalesId
FROM EventSignup As s
	Inner Join events As e on s.EventId=e.Id
	Inner Join currencies as c on s.CurrencyId=c.Id
	Inner Join eventsignupticketdetails As estd on s.id=estd.eventsignupId
WHERE   ((s.PaymentModeId=1 and s.PaymentTransId='A1') or s.PaymentModeId!=2 ) and s.Fees!=0 
and EventId='".$Globali->dbconn->real_escape_string($qId)."'
and s.EMail not in (SELECT DISTINCT EMail FROM EventSignup WHERE 1 and ((PaymentModeId=1 and PaymentTransId!='A1') or PaymentModeId=2 ) AND EventId='".$Globali->dbconn->real_escape_string($qId)."' 
and DATE(SignupDt)=DATE(s.SignupDt)) AND e.Published=1 GROUP BY s.Email,DATE(s.SignupDt),s.EventId ORDER BY s.Id DESC ";


                    //$myQuery = "SELECT count( s.Id ) AS fCount,sum( s.Qty ) AS tktCount,sum( s.Fees ) AS 'Paid',s.Id AS 'EventSIgnupId',s.field2 'PmiRegNo', s.`EventId`,s.`SignupDt` AS 'Signup Date' ,SUM(s.`Qty`) AS 'Qty' ,s.`Fees` ,s.`Name` ,s.`EMail` ,s.`Phone` ,s.`CityId` ,s.`eStatus` ,s.PaymentGateway,s.PaymentTransId,s.PromotionCode AS 'PromotionCode', s.`PaymentStatus` ,e.SalesId FROM EventSignup As s,events As e WHERE s.EventId=e.Id  and ((s.PaymentModeId=1 and s.PaymentTransId='A1') or s.PaymentModeId!=2  ) and s.Fees!=0  and  EventId='".$Globali->dbconn->real_escape_string($qId)."'   and s.EMail not in (SELECT EMail FROM EventSignup WHERE 1  and ((PaymentModeId=1 and PaymentTransId!='A1') or PaymentModeId=2 ) AND  EventId='".$Globali->dbconn->real_escape_string($qId)."') GROUP BY s.Email,DATE(s.SignupDt),s.EventId ORDER BY s.Id DESC "; 

                    //$CanTranRES = $Globali->dbconn->SelectQuery($myQuery);
                    //print_r($CanTranRES);
                    //return $CanTranRES;        
                    
                }
    else if($isfree==4)//for referral transactions
    {
         $myQuery = "select estd.NumOfTickets,es.id 'EventSIgnupId', es.referralDAmount ,es.Qty 'quantity',   estd.Id 'estdid',                       
       es.SignupDt 'Signup Date',es.PaymentGateway,es.field2 'PmiRegNo',
              CASE WHEN paymentgateway='CashonDelivery' THEN 'Cash on Delivery'
                   WHEN PaymentModeId=2 and PaymentTransId='A1' THEN ch.chqno
          ELSE es.PaymentTransId 
          END 'PaymentTransId', 
       es.PromotionCode 'PromotionCode',
	   estd.promoCode 'newPromotionCode',
      CASE es.PaymentModeId 
			WHEN 1 THEN CASE es.Fees 
							WHEN 0 THEN 
								CASE es.eChecked WHEN 'Verified' THEN 'Successful' ELSE 'Pending' END
						ELSE
							CASE es.eChecked WHEN 'Verified' THEN 'Successful' ELSE 'Captured' END 
						END
	   ELSE 
	   		CASE es.eChecked WHEN 'Verified' THEN 'Successful' ELSE 'Pending' END 
	   END 'PaymentStatus',
       a.name 'UserName' ,
    a.Id 'attendeeId',    
ct.City,s.State,
		c.code 'currencyCode',
       a.field1,
       a.field2,
       a.field3,
       a.field4,
       a.Email 'Email', es.Fees,es.PaymentModeId,es.paypal_converted_amount,es.Ccharge,es.Qty,
       a.company 'Company',
       a.Phone 'Phone', '' Mobile,
		".$sqlAdd."
t.name,estd.TicketAmt 'Amount',
(estd.TicketAmt/estd.NumOfTickets) 'TicketAmt',estd.NumOfTickets, estd.BulkDiscount, estd.ReferralDiscount, estd.ServiceTax,estd.EntTax      
FROM Attendees as a 
            Inner Join EventSignup as es on a.EventSignupId=es.Id
            Inner Join eventsignupticketdetails estd on a.eventsignupid=estd.eventsignupid
            Inner Join tickets t on t.id=estd.ticketid 
            Left Outer Join ChqPmnts ch on es.id=ch.eventsignupid   
			INNER JOIN currencies c on es.CurrencyId=c.Id  
			Left JOIN States s on es.StateId=s.Id   
			Left JOIN Cities ct on es.CityId=ct.Id
			Left JOIN promoters p on es.ucode=p.promoter and p.eventid=  ".$qId." and p.type='offline'        
    WHERE  es.EventId=  ".$qId."
        and ((es.paymentgateway='CashonDelivery') or es.paymenttransid!='A1' or (es. PaymentModeId=2 and PaymentTransId='A1' and es.echecked='Verified')   or es.Fees=0)
        and es.eChecked!='Canceled'
        and es.eChecked!='Refunded' and es.referralDAmount>0 
        and a.ticketid=estd.ticketid $ticketCond $currencyCondition group by t.id, es.Id order by es.id desc;"; 
                                             
                }
	
	elseif($isfree==5) //for offline bookings
	{
		
		$myQuery="select estd.NumOfTickets, es.id 'EventSIgnupId', es.referralDAmount ,                         
       es.SignupDt 'Signup Date',es.PaymentGateway,es.field2 'PmiRegNo',
              CASE WHEN paymentgateway='CashonDelivery' THEN 'Cash on Delivery'
                   WHEN PaymentModeId=2 and PaymentTransId='A1' THEN ch.chqno
          ELSE es.PaymentTransId 
          END 'PaymentTransId', 
       es.PromotionCode 'PromotionCode',
	   estd.promoCode 'newPromotionCode',
       CASE es.PaymentModeId 
			WHEN 1 THEN CASE es.Fees 
							WHEN 0 THEN 
								CASE es.eChecked WHEN 'Verified' THEN 'Successful' ELSE 'Pending' END
						ELSE
							CASE es.eChecked WHEN 'Verified' THEN 'Successful' ELSE 'Captured' END 
						END
	   ELSE 
	   		CASE es.eChecked WHEN 'Verified' THEN 'Successful' ELSE 'Pending' END 
	   END 'PaymentStatus',
       a.name 'UserName' ,
    a.Id 'attendeeId',    
ct.City,s.State,
c.code 'currencyCode',
       a.field1,
       a.field2,
       a.field3,
       a.field4,
       a.Email 'Email', es.Fees,es.PaymentModeId,es.paypal_converted_amount,es.Ccharge,es.Qty,
       a.company 'Company',
       a.Phone 'Phone', '' Mobile,
	".$sqlAdd."
t.name,estd.TicketAmt 'Amount',
(estd.TicketAmt/estd.NumOfTickets) 'TicketAmt',estd.NumOfTickets, estd.BulkDiscount, estd.ReferralDiscount, estd.ServiceTax,estd.EntTax,
es.ucode  'promoterCode',
if(es.ucode like 'OFFLINE_%',p.username,'') AS 'PromoterName'     
FROM Attendees as a 
            Inner Join EventSignup as es on a.EventSignupId=es.Id
            Inner Join eventsignupticketdetails estd on a.eventsignupid=estd.eventsignupid
            Inner Join tickets t on t.id=estd.ticketid 
			INNER JOIN currencies c on es.CurrencyId=c.Id
            Left Outer Join ChqPmnts ch on es.id=ch.eventsignupid
			Left JOIN States s on es.StateId=s.Id   
			Left JOIN Cities ct on es.CityId=ct.Id 
			Left JOIN promoters p on es.ucode=p.promoter and p.eventid=  ".$qId." and p.type='offline'  
			         
    WHERE  es.EventId= ".$qId."
        and ((es.paymentgateway='CashonDelivery') or es.paymenttransid!='A1' or (es. PaymentModeId=2 and PaymentTransId='A1' and es.echecked='Verified')   or es.Fees=0)
        and es.eChecked!='Canceled'
        and es.eChecked!='Refunded'
        and a.ticketid=estd.ticketid  $uniquecondition $ticketCond $currencyCondition  group by t.id, es.id order by es.id desc ".$sqlLimit.";"; 

		
		
		
		
		
	
	}
	else
	{
		$myQuery="SELECT a.*,s.field2 'PmiRegNo', FROM Attendees as a,EventSignup as s WHERE a.EventSignupId=s.Id and s.EventId= ".$qId."   order by a.EventSignupId desc ";
	}

		//$myQuery.=" order by es.id desc"; 
		 


		//echo $myQueryCount;


		 
	 	   /*$Result = $Globali->dbconn->query($myQuery); 
			return $Result;*/
			
			$Result = $Globali->dbconn->query($myQuery);
			return $Result;
			
			
			
		}
		catch (Exception $Ex)
		{
			throw $Ex;
		}
	}
	
	
	//----------------------------------------------------------------------------------------------------

//----------------------------------------------------------------------------------------------------



public function LoadAllRefundedByEventId($qId,$isfree=0)
{
	$Success = FALSE;

	try
	{    		
		$Globali=new cGlobali();
		
		//taking care of old transactions	
		if (strtolower($_SERVER['HTTP_HOST'])=='stage.meraevents.com'  || strtolower($_SERVER['HTTP_HOST'])=='stage.meraevents.com:8674') {
			$sqlAdd=" IF(es.Id>137214,estd.Discount,(es.DAmount+es.referralDAmount)) 'normalDiscount',
					IF(es.Id>137214,IF(estd.TicketAmt=0,0,((estd.TicketAmt+estd.ServiceTax+estd.EntTax-estd.Discount-estd.BulkDiscount-estd.ReferralDiscount)/estd.NumOfTickets)),IF(estd.TicketAmt=0,0,(es.fees - (es.Ccharge/es.Qty)))) 'Paid' ,";
		}
		else {
			$sqlAdd=" IF(es.Id>151909,estd.Discount,(es.DAmount+es.referralDAmount)) 'normalDiscount',
					IF(es.Id>151909,IF(estd.TicketAmt=0,0,((estd.TicketAmt+estd.ServiceTax+estd.EntTax-estd.Discount-estd.BulkDiscount-estd.ReferralDiscount)/estd.NumOfTickets)),IF(estd.TicketAmt=0,0,(es.fees - (es.Ccharge/es.Qty)))) 'Paid' ,";
		}
		
		//taking care of old transactions	
		
			
	    $myQuery = "";
	
	if($isfree==1) //for paid events
	{$myQuery.=" select es.id 'EventSIgnupId',                          
       es.SignupDt 'Signup Date',
              CASE WHEN paymentgateway='CashonDelivery' THEN 'Cash on Delivery'
                   WHEN PaymentModeId=2 and PaymentTransId='A1' THEN ch.chqno
          ELSE es.PaymentTransId 
          END 'PaymentTransId', 
       es.PromotionCode 'PromotionCode',
	   estd.promoCode 'newPromotionCode',
       CASE es.eChecked WHEN 'Verified' THEN 'Successful' 
          ELSE 'Pending' 
          End 'PaymentStatus',
       a.name 'Name' ,
	   a.Id 'attendeeId',
	   
ct.City,s.State,
       a.field1,
       a.field2,
       a.field3,
       a.field4,
       a.Email 'Email', es.Fees,es.paypal_converted_amount,
       a.company 'Company',
       a.Phone 'Phone', '' Mobile,
	".$sqlAdd."
t.name,(estd.TicketAmt/estd.NumOfTickets) 'Amount'      
FROM Attendees as a 
            Inner Join EventSignup as es on a.EventSignupId=es.Id
            Inner Join eventsignupticketdetails estd on a.eventsignupid=estd.eventsignupid
            Inner Join tickets t on t.id=estd.ticketid 
            Left Outer Join ChqPmnts ch on es.id=ch.eventsignupid
			Left JOIN States s on es.StateId=s.Id   
			Left JOIN Cities ct on es.CityId=ct.Id
			Left JOIN promoters p on es.ucode=p.promoter and p.eventid=  ".$qId." and p.type='offline'
            
    WHERE  es.EventId= ".$qId."
        and ((es.paymentgateway='CashonDelivery') or es.paymenttransid!='A1' or (es. PaymentModeId=2 and PaymentTransId='A1' and es.echecked='Verified')   or es.Fees=0)
        and es.eChecked!='Canceled'
        and es.eChecked='Refunded'
        and a.ticketid=estd.ticketid
 
     order by es.id desc;

       ";}
		else if($isfree==2)
		{
		$myQuery.="select es.id 'EventSIgnupId',                          
       es.SignupDt 'Signup Date', a.field1,a.field2,a.field3,a.field4,es.PaymentTransId,es.PaymentGateway,
      
       es.PromotionCode 'PromotionCode',
	   estd.promoCode 'newPromotionCode',
	    es.Fees,es.paypal_converted_amount,
       CASE es.eChecked WHEN 'Verified' THEN 'Successful' 
          ELSE 'Pending' 
          End 'PaymentStatus',
       a.name 'Name' ,
	   a.Id 'attendeeId',
       a.Email 'Email',
	   es.CityId,es.StateId,
       a.company 'Company',
       a.Phone 'Phone', '' Mobile,
	   ".$sqlAdd."
	   t.name
	   FROM Attendees  a,EventSignup es ,tickets t, eventsignupticketdetails estd   WHERE a.eventsignupid=es.id and t.id=estd.ticketid 
and a.ticketid=estd.ticketid AND estd.eventsignupid = es.id and es.eventid=".$qId."  order by es.id desc"; 
		}
		else
		{
			$myQuery="SELECT a.* FROM Attendees as a,EventSignup as s WHERE a.EventSignupId=s.Id and s.EventId= ".$qId."   order by a.EventSignupId desc ";
		}

		//$myQuery.=" order by es.id desc"; 
		 

		//echo $myQuery;


		 
	 	   $Result = $Globali->dbconn->query($myQuery); 
			return $Result;
		}
		catch (Exception $Ex)
		{
			throw $Ex;
		}
	}
	
	
	//----------------------------------------------------------------------------------------------------
public function LoadAllRefunds($qId)
 {
  $Success = FALSE;

  try
  {      $Globali=new cGlobali();

	
	//taking care of old transactions	
		if (strtolower($_SERVER['HTTP_HOST'])=='stage.meraevents.com'  || strtolower($_SERVER['HTTP_HOST'])=='stage.meraevents.com:8674') {
			$sqlAdd=" IF(es.Id>137214,estd.Discount,(es.DAmount+es.referralDAmount)) 'normalDiscount',
					IF(es.Id>137214,IF(estd.TicketAmt=0,0,((estd.TicketAmt+estd.ServiceTax+estd.EntTax-estd.Discount-estd.BulkDiscount-estd.ReferralDiscount)/estd.NumOfTickets)),IF(estd.TicketAmt=0,0,(es.fees - (es.Ccharge/es.Qty)))) 'Paid' ,";
		}
		else {
			$sqlAdd=" IF(es.Id>151909,estd.Discount,(es.DAmount+es.referralDAmount)) 'normalDiscount',
					IF(es.Id>151909,IF(estd.TicketAmt=0,0,((estd.TicketAmt+estd.ServiceTax+estd.EntTax-estd.Discount-estd.BulkDiscount-estd.ReferralDiscount)/estd.NumOfTickets)),IF(estd.TicketAmt=0,0,(es.fees - (es.Ccharge/es.Qty)))) 'Paid' ,";
		}
		
		//taking care of old transactions	
		
		
	
     $myQuery = "";
 $myQuery.=" select es.id 'EventSIgnupId',                          
       es.SignupDt 'Signup Date',
              CASE WHEN paymentgateway='CashonDelivery' THEN 'Cash on Delivery'
                   WHEN PaymentModeId=2 and PaymentTransId='A1' THEN ch.chqno
          ELSE es.PaymentTransId 
          END 'PaymentTransId', 
       es.PromotionCode 'PromotionCode',
	   estd.promoCode 'newPromotionCode',
       CASE es.eChecked WHEN 'Verified' THEN 'Successful' 
          ELSE 'Refunded' 
          End 'PaymentStatus',
       a.name 'Name' ,
    	c.code 'currencyCode',
ct.City,s.State,
       a.field1,
       a.field2,
       a.field3,
       a.field4,
       a.Email 'Email', es.Fees,es.paypal_converted_amount,
       a.company 'Company',
       a.Phone 'Phone', '' Mobile,
".$sqlAdd."
t.name,(estd.TicketAmt/estd.NumOfTickets) 'Amount'      
FROM Attendees as a 
            Inner Join EventSignup as es on a.EventSignupId=es.Id
            Inner Join eventsignupticketdetails estd on a.eventsignupid=estd.eventsignupid
            Inner Join tickets t on t.id=estd.ticketid 
			INNER JOIN currencies c ON c.Id=es.CurrencyId
            Left Outer Join ChqPmnts ch on es.id=ch.eventsignupid
			Left JOIN States s on es.StateId=s.Id   
			Left JOIN Cities ct on es.CityId=ct.Id
			Left JOIN promoters p on es.ucode=p.promoter and p.eventid=  ".$qId." and p.type='offline'
            
    WHERE  es.EventId= ".$qId."
        and ((es.paymentgateway='CashonDelivery') or es.paymenttransid!='A1' or (es. PaymentModeId=2 and PaymentTransId='A1' and es.echecked='Verified')  or es.Fees=0)
        and es.eChecked='Refunded'
        and a.ticketid=estd.ticketid
 
     order by es.id desc;

       ";


   
      $Result = $Globali->dbconn->query($myQuery); 
  return $Result;
  }
  catch (Exception $Ex)
  {
   throw $Ex;
  }
 }
//----------------------------------------------------------------------------------------------------
	//----------------------------------------------------------------------------------------------------
public function LoadAllCancelledTrans($qId)
 {
  $Success = FALSE;

  try
  {      $Globali=new cGlobali();
 	
	//taking care of old transactions	
		if (strtolower($_SERVER['HTTP_HOST'])=='stage.meraevents.com'  || strtolower($_SERVER['HTTP_HOST'])=='stage.meraevents.com:8674') {
			$sqlAdd=" IF(es.Id>137214,estd.Discount,(es.DAmount+es.referralDAmount)) 'normalDiscount',
					IF(es.Id>137214,IF(estd.TicketAmt=0,0,((estd.TicketAmt+estd.ServiceTax+estd.EntTax-estd.Discount-estd.BulkDiscount-estd.ReferralDiscount)/estd.NumOfTickets)),IF(estd.TicketAmt=0,0,(es.fees - (es.Ccharge/es.Qty)))) 'Paid' ,";
		}
		else{
			$sqlAdd=" IF(es.Id>151909,estd.Discount,(es.DAmount+es.referralDAmount)) 'normalDiscount',
					IF(es.Id>151909,IF(estd.TicketAmt=0,0,((estd.TicketAmt+estd.ServiceTax+estd.EntTax-estd.Discount-estd.BulkDiscount-estd.ReferralDiscount)/estd.NumOfTickets)),IF(estd.TicketAmt=0,0,(es.fees - (es.Ccharge/es.Qty)))) 'Paid' ,";
		}
		
		//taking care of old transactions	
	
	
     $myQuery = "";
 $myQuery.=" select es.id 'EventSIgnupId',                          
       es.SignupDt 'Signup Date',
              CASE WHEN paymentgateway='CashonDelivery' THEN 'Cash on Delivery'
                   WHEN PaymentModeId=2 and PaymentTransId='A1' THEN ch.chqno
          ELSE es.PaymentTransId 
          END 'PaymentTransId', 
       es.PromotionCode 'PromotionCode',
	   estd.promoCode 'newPromotionCode',
       CASE es.eChecked WHEN 'Verified' THEN 'Successful' 
          ELSE 'Canceled' 
          End 'PaymentStatus',
       a.name 'Name' ,
     a.Id 'attendeeId', 
ct.City,s.State,
c.code 'currencyCode',
       a.field1,
       a.field2,
       a.field3,
       a.field4,
       a.Email 'Email', es.Fees,es.paypal_converted_amount,
       a.company 'Company',
       a.Phone 'Phone', '' Mobile,
".$sqlAdd."
t.name,(estd.TicketAmt/estd.NumOfTickets) 'Amount', 
cmt.Comment 'comment'     
FROM Attendees as a 
            Inner Join EventSignup as es on a.EventSignupId=es.Id
            Inner Join eventsignupticketdetails estd on a.eventsignupid=estd.eventsignupid
            Inner Join tickets t on t.id=estd.ticketid
			INNER JOIN currencies c on es.CurrencyId=c.Id  
            Left Outer Join ChqPmnts ch on es.id=ch.eventsignupid
            Left Outer Join CancelTransComments cmt on es.id=cmt.eventsignupid
			Left JOIN States s on es.StateId=s.Id   
			Left JOIN Cities ct on es.CityId=ct.Id
			Left JOIN promoters p on es.ucode=p.promoter and p.eventid=  ".$qId." and p.type='offline'
    WHERE  es.EventId= ".$qId."
        and ((es.paymentgateway='CashonDelivery') or es.paymenttransid!='A1' or (es. PaymentModeId=2 and PaymentTransId='A1' and es.echecked='Verified')  or es.Fees=0)
        and es.eChecked='Canceled'
        and a.ticketid=estd.ticketid
 
     order by es.id desc;

       ";


   
      $Result = $Globali->dbconn->query($myQuery); 
  return $Result;
  }
  catch (Exception $Ex)
  {
   throw $Ex;
  }
 }
//---
//----------------------------------------------------------------------------------------------------


//----------------------------------------------------------------------------------------------------
public function LoadPromoterDetailReportByEventId($qId,$filterType=0,$ticketId=NULL)
	{
		$Success = FALSE;
		$sqlLimit=$uniquecondition=NULL;
		$filterTypeEx=explode("^^^^",$filterType);
		$currencyType=$filterTypeEx[2];
		$mode=$filterType[1];
		if (count($filterTypeEx) >= 2) {
            $isfree = $filterTypeEx[0];
			if($mode!='referral'){
            $promoter = $filterTypeEx[1];
				if (strlen(trim($promoter)) > 0) {
					if($promoter=="mEraEvents"){
						$uniquecondition=" AND es.ucode IS NULL and es.referralDAmount=0 ";
					}else{
					$uniquecondition = " and es.ucode='" . $promoter . "' ";
					}
				} else {
					$uniquecondition = " and (es.ucode!='' and es.ucode!='organizer')  ";
				}
			}
        } else {
            $isfree = $filterType;
        }
		if(!empty($currencyType)){
			$currencyCondition=" and c.code='".$currencyType."' ";
		}
		
		
		
		
		
		
		try
		{    		$Globali=new cGlobali();
	
		//mysql_connect($Globali->DBServerName, $Globali->DBUserName, $Globali->DBPassword);
		//mysql_select_db($Globali->DBIniCatalog);	
	//select * from Attendees where eventsignupId IN(SELECT `Id` FROM `EventSignup` WHERE `EventId`=37)

		$myQuery=$myQueryCount=$ticketCond=NULL;
		if(!empty($ticketId))
            $ticketCond = " and t.Id='".$ticketId."'";
		
		
		//taking care of old transactions	
		if (strtolower($_SERVER['HTTP_HOST'])=='stage.meraevents.com'  || strtolower($_SERVER['HTTP_HOST'])=='stage.meraevents.com:8674') {
			$sqlAdd=" IF(es.Id>137214,estd.Discount,(es.DAmount+es.referralDAmount)) 'normalDiscount',
					IF(es.Id>137214,IF(estd.TicketAmt=0,0,((estd.TicketAmt+estd.ServiceTax+estd.EntTax-estd.Discount-estd.BulkDiscount-estd.ReferralDiscount)/estd.NumOfTickets)),es.fees - (es.Ccharge/es.Qty)) 'Paid' ,";
		}
		else {
			$sqlAdd=" IF(es.Id>151909,estd.Discount,(es.DAmount+es.referralDAmount)) 'normalDiscount',
					IF(es.Id>151909,IF(estd.TicketAmt=0,0,((estd.TicketAmt+estd.ServiceTax+estd.EntTax-estd.Discount-estd.BulkDiscount-estd.ReferralDiscount)/estd.NumOfTickets)),es.fees - (es.Ccharge/es.Qty)) 'Paid' ,";
		}
	
			
			
	if($isfree==1) //for paid events
	{
		
		$myQuery.="select es.id 'EventSIgnupId', es.referralDAmount ,                         
       es.SignupDt 'Signup Date',es.PaymentGateway,es.field2 'PmiRegNo',
              CASE WHEN paymentgateway='CashonDelivery' THEN 'Cash on Delivery'
                   WHEN PaymentModeId=2 and PaymentTransId='A1' THEN ch.chqno
          ELSE es.PaymentTransId 
          END 'PaymentTransId', 
       es.PromotionCode 'PromotionCode',
	   estd.promoCode 'newPromotionCode',
       CASE es.PaymentModeId 
			WHEN 1 THEN CASE es.Fees 
							WHEN 0 THEN 
								CASE es.eChecked WHEN 'Verified' THEN 'Successful' ELSE 'Pending' END
						ELSE
							CASE es.eChecked WHEN 'Verified' THEN 'Successful' ELSE 'Captured' END 
						END
	   ELSE 
	   		CASE es.eChecked WHEN 'Verified' THEN 'Successful' ELSE 'Pending' END 
	   END 'PaymentStatus',
       a.name 'UserName' ,
    a.Id 'attendeeId',    
ct.City,s.State,
		c.code 'currencyCode',
       a.field1,
       a.field2,
       a.field3,
       a.field4,
       a.Email 'Email', es.Fees,es.PaymentModeId,es.paypal_converted_amount,
       a.company 'Company',
       a.Phone 'Phone', '' Mobile,
      ".$sqlAdd."
t.name,t.Price 'ticketPrice',(estd.TicketAmt/estd.NumOfTickets) 'Amount' ,
estd.NumOfTickets, (estd.TicketAmt/estd.NumOfTickets) 'TicketAmt', estd.BulkDiscount, estd.ReferralDiscount, estd.ServiceTax,estd.EntTax,
es.ucode  'promoterCode',
if(es.ucode like 'OFFLINE_%',p.username,'') AS 'PromoterName'     
FROM Attendees as a 
            Inner Join EventSignup as es on a.EventSignupId=es.Id
            Inner Join eventsignupticketdetails estd on a.eventsignupid=estd.eventsignupid
            Inner Join tickets t on t.id=estd.ticketid 
            Left Outer Join ChqPmnts ch on es.id=ch.eventsignupid         
			INNER JOIN currencies c on es.CurrencyId=c.Id  
			Left JOIN States s on es.StateId=s.Id   
			Left JOIN Cities ct on es.CityId=ct.Id
			Left JOIN promoters p on es.ucode=p.promoter and p.eventid=  ".$qId." and p.type='offline' 
    WHERE  es.EventId=  ".$qId."
        and ((es.paymentgateway='CashonDelivery') or es.paymenttransid!='A1' or (es. PaymentModeId=2 and PaymentTransId='A1' and es.echecked='Verified')   or es.Fees=0)
        and es.eChecked!='Canceled'
        and es.eChecked!='Refunded'
        and a.ticketid=estd.ticketid $uniquecondition $ticketCond $currencyCondition order by es.id desc ".$sqlLimit.";"; 
	
	}
	else if($isfree==2)//for free events
	{
		$myQuery.="select es.id 'EventSIgnupId', es.referralDAmount ,                         
       es.SignupDt 'Signup Date',es.PaymentGateway,es.field2 'PmiRegNo',
              CASE WHEN paymentgateway='CashonDelivery' THEN 'Cash on Delivery'
                   WHEN PaymentModeId=2 and PaymentTransId='A1' THEN ch.chqno
          ELSE es.PaymentTransId 
          END 'PaymentTransId', 
       es.PromotionCode 'PromotionCode',
	   estd.promoCode 'newPromotionCode',
       CASE es.PaymentModeId 
			WHEN 1 THEN CASE es.Fees 
							WHEN 0 THEN 
								CASE es.eChecked WHEN 'Verified' THEN 'Successful' ELSE 'Pending' END
						ELSE
							CASE es.eChecked WHEN 'Verified' THEN 'Successful' ELSE 'Captured' END 
						END
	   ELSE 
	   		CASE es.eChecked WHEN 'Verified' THEN 'Successful' ELSE 'Pending' END 
	   END 'PaymentStatus',
       a.name 'UserName' ,
    a.Id 'attendeeId',    
ct.City,s.State,
		c.code 'currencyCode',
       a.field1,
       a.field2,
       a.field3,
       a.field4,
       a.Email 'Email', es.Fees,es.PaymentModeId,es.paypal_converted_amount,
       a.company 'Company',
       a.Phone 'Phone', '' Mobile,
      ".$sqlAdd."
t.name,t.Price 'ticketPrice',(estd.TicketAmt/estd.NumOfTickets) 'Amount' ,
estd.NumOfTickets, (estd.TicketAmt/estd.NumOfTickets) 'TicketAmt', estd.BulkDiscount, estd.ReferralDiscount, estd.ServiceTax,estd.EntTax,
es.ucode  'promoterCode',
if(es.ucode like 'OFFLINE_%',p.username,'') AS 'PromoterName'     
FROM Attendees as a 
            Inner Join EventSignup as es on a.EventSignupId=es.Id
            Inner Join eventsignupticketdetails estd on a.eventsignupid=estd.eventsignupid
            Inner Join tickets t on t.id=estd.ticketid 
            Left Outer Join ChqPmnts ch on es.id=ch.eventsignupid         
			INNER JOIN currencies c on es.CurrencyId=c.Id  
			Left JOIN States s on es.StateId=s.Id   
			Left JOIN Cities ct on es.CityId=ct.Id
			Left JOIN promoters p on es.ucode=p.promoter and p.eventid=  ".$qId." and p.type='offline' 
    WHERE  es.EventId=  ".$qId."
        
        and es.eChecked not in ('Canceled','Refunded')
        and a.ticketid=estd.ticketid $uniquecondition $ticketCond $currencyCondition order by es.id desc ".$sqlLimit.";"; 
			
		
		}
                else if($isfree==3)
                {
                     $myQuery = "SELECT DISTINCT(s.Id) AS 'EventSIgnupId',s.field2 'PmiRegNo', s.EventId,s.Name,s.CityId,s.StateId, s.SignupDt AS 'Signup Date',s.field1, e.Title, s.Qty, s.Fees, s.PaymentTransId,s.PaymentModeId,s.PromotionCode,s.PaymentGateway,s.PromotionCode AS 'PromotionCode',
                        CASE s.eChecked WHEN 'Verified' THEN 'Successful' 
                        ELSE 'Captured' 
                        End 'PaymentStatus',(s.fees - (s.Ccharge/s.Qty)) 'Paid',a.Id AS 'attendeeId'  FROM  EventSignup AS s LEFT JOIN Attendees as a ON s.Id=a.EventSIgnupId, events AS e,currencies c WHERE s.EventId = e.Id and es.CurrencyId=c.Id AND e.UserId = '".$Globali->dbconn->real_escape_string($_SESSION['uid'])."' and ((s.PaymentModeId=1 and s.PaymentTransId='A1') or s.PaymentModeId!=2 or  s.PromotionCode ='X' ) and s.Fees!=0  and s.EMail not in (SELECT EMail FROM EventSignup WHERE 1  and ((PaymentModeId=1 and PaymentTransId!='A1') or PaymentModeId=2 or  PromotionCode !='X')  AND  EventId='".$Globali->dbconn->real_escape_string($qId)."')   AND EventId='".$Globali->dbconn->real_escape_string($qId)."' AND  s.eStatus='Open' GROUP BY s.Id ORDER BY s.Id DESC"; 
                    //$myQuery = "SELECT s.Id AS 'EventSIgnupId', s.EventId,s.CityId,s.StateId, s.SignupDt AS 'Signup Date',s.field1, e.Title, s.Qty, s.Fees, s.PaymentTransId,s.PaymentModeId,s.PromotionCode,s.PaymentGateway FROM  EventSignup AS s, events AS e WHERE s.EventId = e.Id AND e.UserId = '".$Globali->dbconn->real_escape_string($_SESSION['uid'])."' and ((s.PaymentModeId=1 and s.PaymentTransId='A1') or s.PaymentModeId!=2 or  s.PromotionCode ='X' ) and s.Fees!=0  and s.EMail not in (SELECT EMail FROM EventSignup WHERE 1  and ((PaymentModeId=1 and PaymentTransId!='A1') or PaymentModeId=2 or  PromotionCode !='X')  AND  EventId='".$Globali->dbconn->real_escape_string($qId)."')   AND EventId='".$Globali->dbconn->real_escape_string($qId)."' AND a.eventsignupid=s.id AND  s.eStatus='Open' ORDER BY s.Id DESC"; 
                    //$CanTranRES = $Globali->dbconn->SelectQuery($myQuery);
                    //print_r($CanTranRES);
                    //return $CanTranRES;        
                    
                }
                else if($isfree==4)//for referral transactions
                {
                  $myQuery = "select es.id 'EventSIgnupId',es.field2 'PmiRegNo', es.referralDAmount ,es.Qty 'quantity',   estd.Id 'estdid',                       
       es.SignupDt 'Signup Date',es.PaymentGateway,
              CASE WHEN paymentgateway='CashonDelivery' THEN 'Cash on Delivery'
                   WHEN PaymentModeId=2 and PaymentTransId='A1' THEN ch.chqno
          ELSE es.PaymentTransId 
          END 'PaymentTransId', 
       es.PromotionCode 'PromotionCode',
	   estd.promoCode 'newPromotionCode',
       CASE es.PaymentModeId 
			WHEN 1 THEN CASE es.Fees 
							WHEN 0 THEN 
								CASE es.eChecked WHEN 'Verified' THEN 'Successful' ELSE 'Pending' END
						ELSE
							CASE es.eChecked WHEN 'Verified' THEN 'Successful' ELSE 'Captured' END 
						END
	   ELSE 
	   		CASE es.eChecked WHEN 'Verified' THEN 'Successful' ELSE 'Pending' END 
	   END 'PaymentStatus',
       a.name 'UserName' ,
    a.Id 'attendeeId',    
ct.City,s.State,
		c.code 'currencyCode',
       a.field1,
       a.field2,
       a.field3,
       a.field4,
       a.Email 'Email', es.Fees,es.PaymentModeId,es.paypal_converted_amount,
       a.company 'Company',
       a.Phone 'Phone', '' Mobile,
       ".$sqlAdd."

t.name,(estd.TicketAmt/estd.NumOfTickets) 'Amount',
estd.NumOfTickets,(estd.TicketAmt/estd.NumOfTickets) 'TicketAmt', estd.BulkDiscount, estd.ReferralDiscount, estd.ServiceTax  ,estd.EntTax    
FROM Attendees as a 
            Inner Join EventSignup as es on a.EventSignupId=es.Id
            Inner Join eventsignupticketdetails estd on a.eventsignupid=estd.eventsignupid
            Inner Join tickets t on t.id=estd.ticketid 
            Left Outer Join ChqPmnts ch on es.id=ch.eventsignupid
			INNER JOIN currencies c on es.CurrencyId=c.Id   
			Left JOIN States s on es.StateId=s.Id   
			Left JOIN Cities ct on es.CityId=ct.Id  
			Left JOIN promoters p on es.ucode=p.promoter and p.eventid=  ".$qId." and p.type='offline'       
    WHERE  es.EventId=  ".$qId."
        and ((es.paymentgateway='CashonDelivery') or es.paymenttransid!='A1' or (es. PaymentModeId=2 and PaymentTransId='A1' and es.echecked='Verified')   or es.Fees=0)
        and es.eChecked!='Canceled'
        and es.eChecked!='Refunded' and es.referralDAmount>0 
        and a.ticketid=estd.ticketid $ticketCond $currencyCondition order by es.id desc;"; 
                                             
                }
		else
		{
			$myQuery="SELECT a.*,s.field2 'PmiRegNo', FROM Attendees as a,EventSignup as s WHERE a.EventSignupId=s.Id and s.EventId= ".$qId."   order by a.EventSignupId desc ";
		}

		//$myQuery.=" order by es.id desc"; 
		 

		//echo $myQuery;
			
			$Result = $Globali->dbconn->query($myQuery); 
			return $Result;
		}
		catch (Exception $Ex)
		{
			throw $Ex;
		}
	}
	
	
	//----------------------------------------------------------------------------------------------------
	
	
	
	//----------------------------------------------------------------------------------------------------
public function LoadPromoterSummaryReportByEventId($qId,$filterType=0,$ticketId=NULL)
	{
		$Success = FALSE;
		$sqlLimit=$uniquecondition=NULL;
		
		$filterTypeEx=explode("^^^^",$filterType);
		$currencyType=$filterTypeEx[2];
		$mode=$filterTypeEx[1];
		if (count($filterTypeEx) >= 2) {
			$isfree = $filterTypeEx[0];
			if($mode!='referral'){
				$promoter = $filterTypeEx[1];
				if (strlen(trim($promoter)) > 0) {
					if($promoter=="mEraEvents"){
						$uniquecondition=" AND es.ucode IS NULL and es.referralDAmount=0 ";
					}else{
					$uniquecondition = " and es.ucode='" . $promoter . "' ";
					}
				} else {
					$uniquecondition = " and (es.ucode!='' and es.ucode!='organizer')  ";
				}
			}
        } else {
            $isfree = $filterType;
        }
		if(!empty($currencyType)){
			$currencyCondition=" and c.code='".$currencyType."' ";
		}
		
		
		
		
		try
		{    		$Globali=new cGlobali();
	
		
		
	    $myQuery = "";
		$ticketCond=NULL;
	if(!empty($ticketId))
            $ticketCond = " and t.Id='".$ticketId."'";
			
			
	//taking care of old transactions	
		if (strtolower($_SERVER['HTTP_HOST'])=='stage.meraevents.com'  || strtolower($_SERVER['HTTP_HOST'])=='stage.meraevents.com:8674') {
			$sqlAdd=" IF(es.Id>137214,estd.Discount,(es.DAmount+es.referralDAmount)) 'normalDiscount',
					IF(es.Id>137214,IF(estd.TicketAmt=0,0,((estd.TicketAmt+estd.ServiceTax+estd.EntTax-estd.Discount-estd.BulkDiscount-estd.ReferralDiscount)/estd.NumOfTickets)),es.fees - (es.Ccharge/es.Qty)) 'Paid' ,";
		}
		else{
			$sqlAdd=" IF(es.Id>151909,estd.Discount,(es.DAmount+es.referralDAmount)) 'normalDiscount',
					IF(es.Id>151909,IF(estd.TicketAmt=0,0,((estd.TicketAmt+estd.ServiceTax+estd.EntTax-estd.Discount-estd.BulkDiscount-estd.ReferralDiscount)/estd.NumOfTickets)),es.fees - (es.Ccharge/es.Qty)) 'Paid' ,";
		}
	
		//taking care of old transactions	
			
	if($isfree==1) //for paid events
	{
		
		$myQuery="select estd.NumOfTickets, es.id 'EventSIgnupId', es.referralDAmount ,                         
       es.SignupDt 'Signup Date',es.PaymentGateway,es.field2 'PmiRegNo',
              CASE WHEN paymentgateway='CashonDelivery' THEN 'Cash on Delivery'
                   WHEN PaymentModeId=2 and PaymentTransId='A1' THEN ch.chqno
          ELSE es.PaymentTransId 
          END 'PaymentTransId', 
       es.PromotionCode 'PromotionCode',
	   estd.promoCode 'newPromotionCode',
       CASE es.PaymentModeId 
			WHEN 1 THEN CASE es.Fees 
							WHEN 0 THEN 
								CASE es.eChecked WHEN 'Verified' THEN 'Successful' ELSE 'Pending' END
						ELSE
							CASE es.eChecked WHEN 'Verified' THEN 'Successful' ELSE 'Captured' END 
						END
	   ELSE 
	   		CASE es.eChecked WHEN 'Verified' THEN 'Successful' ELSE 'Pending' END 
	   END 'PaymentStatus',
       a.name 'UserName' ,
    a.Id 'attendeeId',    
ct.City,s.State,
c.code 'currencyCode',
       a.field1,
       a.field2,
       a.field3,
       a.field4,
       a.Email 'Email', es.Fees,es.PaymentModeId,es.paypal_converted_amount,
       a.company 'Company',
       a.Phone 'Phone', '' Mobile,
	".$sqlAdd."
t.name,estd.TicketAmt 'Amount',
(estd.TicketAmt/estd.NumOfTickets) 'TicketAmt',estd.NumOfTickets, estd.BulkDiscount, estd.ReferralDiscount, estd.ServiceTax,estd.EntTax,
es.ucode  'promoterCode',
if(es.ucode like 'OFFLINE_%',p.username,'') AS 'PromoterName'     
FROM Attendees as a 
            Inner Join EventSignup as es on a.EventSignupId=es.Id
            Inner Join eventsignupticketdetails estd on a.eventsignupid=estd.eventsignupid
            Inner Join tickets t on t.id=estd.ticketid 
			INNER JOIN currencies c on es.CurrencyId=c.Id
            Left Outer Join ChqPmnts ch on es.id=ch.eventsignupid 
			Left JOIN States s on es.StateId=s.Id   
			Left JOIN Cities ct on es.CityId=ct.Id 
			Left JOIN promoters p on es.ucode=p.promoter and p.eventid=  ".$qId." and p.type='offline' 
			         
    WHERE  es.EventId= ".$qId."
        and ((es.paymentgateway='CashonDelivery') or es.paymenttransid!='A1' or (es. PaymentModeId=2 and PaymentTransId='A1' and es.echecked='Verified')   or es.Fees=0)
        and es.eChecked!='Canceled'
        and es.eChecked!='Refunded'
        and a.ticketid=estd.ticketid  $uniquecondition $ticketCond $currencyCondition  group by t.id, es.id order by es.id desc ".$sqlLimit.";"; 

		
		
		
		
		
	
	}
		else if($isfree==2)//for free events
		{
			
			
			
		$myQuery="select estd.NumOfTickets, es.id 'EventSIgnupId', es.referralDAmount ,                         
       es.SignupDt 'Signup Date',es.PaymentGateway,es.field2 'PmiRegNo',
              CASE WHEN paymentgateway='CashonDelivery' THEN 'Cash on Delivery'
                   WHEN PaymentModeId=2 and PaymentTransId='A1' THEN ch.chqno
          ELSE es.PaymentTransId 
          END 'PaymentTransId', 
       es.PromotionCode 'PromotionCode',
	   estd.promoCode 'newPromotionCode',
       CASE es.PaymentModeId 
			WHEN 1 THEN CASE es.Fees 
							WHEN 0 THEN 
								CASE es.eChecked WHEN 'Verified' THEN 'Successful' ELSE 'Pending' END
						ELSE
							CASE es.eChecked WHEN 'Verified' THEN 'Successful' ELSE 'Captured' END 
						END
	   ELSE 
	   		CASE es.eChecked WHEN 'Verified' THEN 'Successful' ELSE 'Pending' END 
	   END 'PaymentStatus',
       a.name 'UserName' ,
    a.Id 'attendeeId',    
ct.City,s.State,
c.code 'currencyCode',
       a.field1,
       a.field2,
       a.field3,
       a.field4,
       a.Email 'Email', es.Fees,es.PaymentModeId,es.paypal_converted_amount,
       a.company 'Company',
       a.Phone 'Phone', '' Mobile,
	".$sqlAdd."
t.name,estd.TicketAmt 'Amount',
(estd.TicketAmt/estd.NumOfTickets) 'TicketAmt',estd.NumOfTickets, estd.BulkDiscount, estd.ReferralDiscount, estd.ServiceTax,estd.EntTax,
es.ucode  'promoterCode',
if(es.ucode like 'OFFLINE_%',p.username,'') AS 'PromoterName'     
FROM Attendees as a 
            Inner Join EventSignup as es on a.EventSignupId=es.Id
            Inner Join eventsignupticketdetails estd on a.eventsignupid=estd.eventsignupid
            Inner Join tickets t on t.id=estd.ticketid 
			INNER JOIN currencies c on es.CurrencyId=c.Id
            Left Outer Join ChqPmnts ch on es.id=ch.eventsignupid 
			Left JOIN States s on es.StateId=s.Id   
			Left JOIN Cities ct on es.CityId=ct.Id 
			Left JOIN promoters p on es.ucode=p.promoter and p.eventid=  ".$qId." and p.type='offline' 
			         
    WHERE  es.EventId= ".$qId."
       
        and es.eChecked not in ('Canceled','Refunded')
        and a.ticketid=estd.ticketid  $uniquecondition $ticketCond $currencyCondition  group by t.id, es.id order by es.id desc ".$sqlLimit.";"; 

		 
		}

                else if($isfree==3) //incomplete transactions
                {
			$myQuery = "SELECT c.code 'currencyCode',count( s.Id ) AS fCount,sum( s.Qty ) AS tktCount,sum( s.Fees ) AS 'Paid',s.Id AS 'EventSIgnupId',s.field2 'PmiRegNo', s.`EventId`,s.`SignupDt` AS 'Signup Date' ,SUM(s.`Qty`) AS 'Qty' ,s.`Fees` ,s.`Name` ,s.`EMail` ,s.`Phone` ,s.`CityId` ,s.`eStatus` ,s.PaymentGateway,s.PaymentTransId,s.PromotionCode AS 'PromotionCode', s.`PaymentStatus` ,e.SalesId 
FROM EventSignup As s
	Inner Join events As e on s.EventId=e.Id
	Inner Join currencies as c on s.CurrencyId=c.Id
	Inner Join eventsignupticketdetails As estd on s.id=estd.eventsignupId
WHERE   ((s.PaymentModeId=1 and s.PaymentTransId='A1') or s.PaymentModeId!=2 ) and s.Fees!=0 
and EventId='".$Globali->dbconn->real_escape_string($qId)."'
and s.EMail not in (SELECT DISTINCT EMail FROM EventSignup WHERE 1 and ((PaymentModeId=1 and PaymentTransId!='A1') or PaymentModeId=2 ) AND EventId='".$Globali->dbconn->real_escape_string($qId)."' 
and DATE(SignupDt)=DATE(s.SignupDt)) GROUP BY s.Id,s.Email,DATE(s.SignupDt),s.EventId ORDER BY s.Id DESC ";

                    //$myQuery = "SELECT count( s.Id ) AS fCount,sum( s.Qty ) AS tktCount,sum( s.Fees ) AS 'Paid',s.Id AS 'EventSIgnupId',s.field2 'PmiRegNo', s.`EventId`,s.`SignupDt` AS 'Signup Date' ,SUM(s.`Qty`) AS 'Qty' ,s.`Fees` ,s.`Name` ,s.`EMail` ,s.`Phone` ,s.`CityId` ,s.`eStatus` ,s.PaymentGateway,s.PaymentTransId,s.PromotionCode AS 'PromotionCode', s.`PaymentStatus` ,e.SalesId FROM EventSignup As s,events As e WHERE s.EventId=e.Id  and ((s.PaymentModeId=1 and s.PaymentTransId='A1') or s.PaymentModeId!=2  ) and s.Fees!=0  and  EventId='".$Globali->dbconn->real_escape_string($qId)."'   and s.EMail not in (SELECT EMail FROM EventSignup WHERE 1  and ((PaymentModeId=1 and PaymentTransId!='A1') or PaymentModeId=2 ) AND  EventId='".$Globali->dbconn->real_escape_string($qId)."') GROUP BY s.Email,DATE(s.SignupDt),s.EventId ORDER BY s.Id DESC "; 

                    //$CanTranRES = $Globali->dbconn->SelectQuery($myQuery);
                    //print_r($CanTranRES);
                    //return $CanTranRES;        
                    
                }
                else if($isfree==4)//for referral transactions
                {
                     $myQuery = "select estd.NumOfTickets,es.id 'EventSIgnupId', es.referralDAmount ,es.Qty 'quantity',   estd.Id 'estdid',                       
       es.SignupDt 'Signup Date',es.PaymentGateway,es.field2 'PmiRegNo',
              CASE WHEN paymentgateway='CashonDelivery' THEN 'Cash on Delivery'
                   WHEN PaymentModeId=2 and PaymentTransId='A1' THEN ch.chqno
          ELSE es.PaymentTransId 
          END 'PaymentTransId', 
       es.PromotionCode 'PromotionCode',
	   estd.promoCode 'newPromotionCode',
      CASE es.PaymentModeId 
			WHEN 1 THEN CASE es.Fees 
							WHEN 0 THEN 
								CASE es.eChecked WHEN 'Verified' THEN 'Successful' ELSE 'Pending' END
						ELSE
							CASE es.eChecked WHEN 'Verified' THEN 'Successful' ELSE 'Captured' END 
						END
	   ELSE 
	   		CASE es.eChecked WHEN 'Verified' THEN 'Successful' ELSE 'Pending' END 
	   END 'PaymentStatus',
       a.name 'UserName' ,
    a.Id 'attendeeId',    
ct.City,s.State,
		c.code 'currencyCode',
       a.field1,
       a.field2,
       a.field3,
       a.field4,
       a.Email 'Email', es.Fees,es.PaymentModeId,es.paypal_converted_amount,
       a.company 'Company',
       a.Phone 'Phone', '' Mobile,
		".$sqlAdd."
t.name,estd.TicketAmt 'Amount',
(estd.TicketAmt/estd.NumOfTickets) 'TicketAmt',estd.NumOfTickets, estd.BulkDiscount, estd.ReferralDiscount, estd.ServiceTax,estd.EntTax      
FROM Attendees as a 
            Inner Join EventSignup as es on a.EventSignupId=es.Id
            Inner Join eventsignupticketdetails estd on a.eventsignupid=estd.eventsignupid
            Inner Join tickets t on t.id=estd.ticketid 
            Left Outer Join ChqPmnts ch on es.id=ch.eventsignupid   
			INNER JOIN currencies c on es.CurrencyId=c.Id   
			Left JOIN States s on es.StateId=s.Id   
			Left JOIN Cities ct on es.CityId=ct.Id 
			Left JOIN promoters p on es.ucode=p.promoter and p.eventid=  ".$qId." and p.type='offline'      
    WHERE  es.EventId=  ".$qId."
        and ((es.paymentgateway='CashonDelivery') or es.paymenttransid!='A1' or (es. PaymentModeId=2 and PaymentTransId='A1' and es.echecked='Verified')   or es.Fees=0)
        and es.eChecked!='Canceled'
        and es.eChecked!='Refunded' and es.referralDAmount>0 
        and a.ticketid=estd.ticketid $ticketCond $currencyCondition group by t.id, es.Id order by es.id desc;"; 
                                             
                }
		else
		{
			$myQuery="SELECT a.*,s.field2 'PmiRegNo', FROM Attendees as a,EventSignup as s WHERE a.EventSignupId=s.Id and s.EventId= ".$qId."   order by a.EventSignupId desc ";
		}

		//$myQuery.=" order by es.id desc"; 
		 


		//echo $myQueryCount;


		 
	 	   /*$Result = $Globali->dbconn->query($myQuery); 
			return $Result;*/
			
			$Result = $Globali->dbconn->query($myQuery); 
			return $Result;
			
			
			
		}
		catch (Exception $Ex)
		{
			throw $Ex;
		}
	}
	
	
	//----------------------------------------------------------------------------------------------------




public function LoadAllByEventIdAndUid($qId,$UserId)
	{
		$Success = FALSE;

		try
		{    		$Globali=new cGlobali();
	
	//mysql_connect($Globali->DBServerName, $Globali->DBUserName, $Globali->DBPassword);
	//mysql_select_db($Globali->DBIniCatalog);	
	//select * from Attendees where eventsignupId IN(SELECT `Id` FROM `EventSignup` WHERE `EventId`=37)
	     $myQuery = "SELECT * FROM Attendees WHERE EventSignupId  IN(SELECT `Id` FROM `EventSignup` WHERE `EventId`= ".$qId." And `UserId`= ".$UserId.") ";
	 	   $Result = $Globali->dbconn->query($myQuery); 
		return $Result;
		}
		catch (Exception $Ex)
		{
			throw $Ex;
		}
	}

	//----------------------------------------------------------------------------------------------------

//----------------------------------------------------------------------------------------------------

public function LoadAllByChkin($qId,$chkin,$regno,$chkemail,$chkCompany,$chkPhone,$chkName)
	{
		$Success = FALSE;

		try
		{    		$Globali=new cGlobali();
	
		//mysql_connect($Globali->DBServerName, $Globali->DBUserName, $Globali->DBPassword);
		//mysql_select_db($Globali->DBIniCatalog);	
	    $conchk="";
		$conregno="";
		$conemail="";
		$concompany="";
		$conname="";
		$conphone="";
	    if($chkin!=""){
		if($chkin=="Yes")
		{
		$conchk=" and checked_in=1";
		}else{
		$conchk=" and checked_in=0";
		}		
		}
		
		if($chkemail!="")
		{
		$conemail=" or EMail='".$chkemail."'";
		}
		if($chkName!="")
		{
		$conname=" or Name LIKE  '%".$chkName."%'";
		}
		if($chkCompany!="")
		{
		$concompany=" or Company='".$chkCompany."'";
		}
		if($chkPhone!="")
		{
		$conphone=" or Phone='".$chkPhone."'";
		}
		if($regno!="")
		{
		$conregno=" and Id=".$regno;
		 $conchk="";
		 $conemail="";
		 $concompany="";
		 $conname="";
		 $conphone="";
		}
	
	     $myQuery = "SELECT * FROM Attendees WHERE  EventSignupId  IN(SELECT `Id` FROM `EventSignup` WHERE `EventId`= ".$qId." $conregno $conemail ) $conchk $conphone $concompany $conname";
	 	   $Result = $Globali->dbconn->query($myQuery); 
		return $Result;
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
			//$Globali->dbconn = new mysqli($Globali->DBServerNameOnly, $Globali->DBUserName,$Globali->DBPassword,$Globali->DBIniCatalog,$Globali->portNumber);
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

	//---------------------------------------------------------------------------------------------------------------

}		//Class


/* >>>Useful notes>>>
 * >>Bind_Param Types (First Argument)
 â€¢ i: All INTEGER types
 â€¢ d: The DOUBLE and FLOAT types
 â€¢ b: The BLOB types
 â€¢ s: All other types (including strings)
 * <<Bind_Param Types<<
 *
 *
 */

?>
