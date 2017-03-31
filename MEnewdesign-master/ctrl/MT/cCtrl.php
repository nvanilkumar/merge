<?php
include_once("../MT/cGlobali.php");
$Globali=new cGlobali();

//static cities for ctrl files
$ctrlCities=array(
			0=>array(0=>"Hyderabad",1=>"Hyderabad"),
			1=>array(0=>"Chennai",1=>"Chennai"),
			2=>array(0=>"Bengaluru",1=>"Bengaluru"),
			3=>array(0=>"NewDelhi",1=>"Delhi-NCR"),
			4=>array(0=>"Pune",1=>"Pune"),
			5=>array(0=>"Mumbai",1=>"Mumbai"),
		);
			
			
class cCtrl
{

	//sales person details
	public function getSalesPersonData()
	{
		global $Globali;
		$SalesPersonQuery = "SELECT id AS SalesId, `name` AS SalesName FROM salesperson ORDER BY `name`" ; 
		$res = $Globali->justSelectQuery($SalesPersonQuery);
		
		$SalesPersonData=array();
		while($row=$res->fetch_assoc())
		{
			$SalesPersonData[]=$row;
		}
		return $SalesPersonData;
	}
	
	
	//get CARD transactions by eventwise 
	public function getEventCardTransactions($extracharge=FALSE,$condition)
	{
		global $Globali;
		
		if($extracharge){$ecSql=" ROUND(sum(s.totalamount),2) 'totalAmount' ";}else{$ecSql=" ROUND(sum((s.totalamount)-s.eventextrachargeamount),2) 'totalAmount' ";}
		
		$sql="SELECT count(s.id) 'TransCount',s.eventid as EventId, s.`id` as Id,  sum(s.quantity) 'ticketQty',$ecSql, s.eventextrachargeamount as Ccharge,e.title as Title 
				FROM eventsignup AS s 
				INNER JOIN event AS e ON s.eventid = e.id 
				INNER JOIN eventdetail AS ed ON ed.eventid = e.id
				WHERE 1 AND e.deleted = 0 ".$condition."  AND (s.totalamount != 0 AND s.paymenttransactionid != 'A1' and s.paymentmodeid=1) 
				and s.paymentstatus not in ('Canceled','refunded')  
				group by s.eventid ORDER BY s.eventid, s.signupdate DESC";
		$res = $Globali->justSelectQuery($sql);
		return $res;
	}
	
	
	
	//get PAYATCOUNTER transactions by eventwise 
	public function getEventPACTransactions($extracharge=FALSE,$condition)
	{
		global $Globali;
		
		if($extracharge){$ecSql=" ROUND(sum(s.totalamount),2) 'totalAmount' ";}else{$ecSql=" ROUND(sum((s.totalamount)-s.Ccharge),2) 'totalAmount' ";}
		$sql="SELECT count(s.id) 'TransCount',s.eventid as EventId, s.`id` as Id,  sum(s.quantity) 'ticketQty', $ecSql, e.title as Title 
			FROM eventsignup AS s 
			INNER JOIN events AS e ON s.eventid = e.Id 
			INNER JOIN eventdetail AS ed ON ed.eventid = e.id
			WHERE 1 ".$condition." AND (s.totalamount != 0 AND (s.discount = 'Y' or s.discount ='PayatCounter' )) and s.paymentstatus!='Canceled'  
			group by s.eventid ORDER BY s.eventid, s.signupdate DESC";
		$res = $Globali->justSelectQuery($sql);
		return $res;
	}
	
	
	//get COD transactions by eventwise 
	public function getEventCODTransactions($extracharge=FALSE,$condition)
	{
		global $Globali;
		
		if($extracharge){$ecSql=" ROUND(sum(s.totalamount),2) 'totalAmount' ";}else{$ecSql=" ROUND(sum((s.totalamount)-s.Ccharge),2) 'totalAmount' ";}
		$sql="SELECT count(s.id) 'TransCount',s.eventid as EventId, s.`id` as Id,  sum(s.quantity) 'ticketQty', $ecSql, e.title as Title 
			FROM eventsignup AS s 
			INNER JOIN events AS e ON s.eventid = e.Id 
			INNER JOIN eventdetail AS ed ON ed.eventid = e.id
			WHERE 1 ".$condition." AND (s.totalamount != 0 AND (s.paymentgatewayid =2 )) and s.paymentstatus!='Canceled'  
			group by s.eventid ORDER BY s.eventid, s.signupdate DESC";
		$res = $Globali->justSelectQuery($sql);
		return $res;
	}
	
	
	
	//get Cheque transactions by eventwise 
	public function getEventChequeTransactions($extracharge=FALSE,$condition)
	{
		global $Globali;
		
		if($extracharge){$ecSql=" ROUND(sum(s.totalamount),2) 'totalAmount' ";}else{$ecSql=" ROUND(sum((s.totalamount)-s.Ccharge),2) 'totalAmount' ";}
		$sql="SELECT count(s.id) 'TransCount',s.eventid as EventId, s.`id` as Id,  sum(s.quantity) 'ticketQty', $ecSql, 
			e.title as Title,  cq.chequenumber as ChqNo, cq.chequedate as ChqDt, cq.chequebank as ChqBank, cq.status as Cleared, cq.id AS chequeId
			FROM eventsignup AS s
			Inner Join chequepayments AS cq on s.id = cq.eventsignupid 
			INNER JOIN events AS e ON s.eventid = e.Id 
			INNER JOIN eventdetail AS ed ON ed.eventid = e.id
			WHERE 1 ".$condition."  AND (s.totalamount != 0 AND s.paymentmodeid = 3) and s.paymentstatus!='Canceled'  
			group by s.eventid ORDER BY s.eventid, s.signupdate DESC";
		$res = $Globali->justSelectQuery($sql);
		return $res;
	}
	
	
	//get total delegates signedup for specific dates 
	public function getTotalDelgatesSignedup($condition)
	{
		global $Globali;
		
		$sql="SELECT sum(s.quantity) as totusers   
		FROM eventsignup AS s 
		INNER JOIN events AS e ON s.eventid = e.Id 
		INNER JOIN eventdetail AS ed ON ed.eventid = e.id
		WHERE 1 ".$condition." AND (s.totalamount=0 or (s.paymentmodeid=1 and s.paymenttransactionid != 'A1') or s.paymentmodeid=2  or s.paymentmodeid=3 or s.paymentmodeid=4 or (s.discount !='X'))  
		ORDER BY s.eventid, s.signupdate DESC";
		$res = $Globali->SelectQuery($sql);
		return $res;
	}
	
	
	
	//get total delegates signedup for free tickets and specific dates 
	public function getTotalDelgatesSignedupForFreeTickets($condition)
	{
		global $Globali;
		
		$sql="SELECT sum(s.quantity) as totusersfree   
		FROM eventsignup AS s 
		INNER JOIN events AS e ON s.eventid = e.Id 
		INNER JOIN eventdetail AS ed ON ed.eventid = e.id
		WHERE 1 ".$condition." AND (e.registrationtype=1 or s.totalamount=0)  
		ORDER BY s.eventid, s.signupdate DESC";
		$res = $Globali->SelectQuery($sql);
		return $res;
	}
	
	
	//get total delegates signedup for paid tickets and specific dates 
	public function getTotalDelgatesSignedupForPaidTickets($condition)
	{
		global $Globali;
		
		$sql="SELECT sum(s.quantity) as totuserspaid   
		FROM eventsignup AS s 
		INNER JOIN events AS e ON s.eventid = e.Id 
		INNER JOIN eventdetail AS ed ON ed.eventid = e.id
		WHERE 1 ".$condition." and s.totalamount > 0 AND ((s.paymentmodeid=1 and s.paymenttransactionid != 'A1') or s.paymentmodeid=2  or s.paymentmodeid=3 or s.paymentmodeid=4 or (s.discount !='X'))
		ORDER BY s.eventid, s.signupdate DESC";
		$res = $Globali->SelectQuery($sql);
		return $res;
	}
	
	
	
	//get total users signedup for specific dates 
	public function getTotalUsersSignedup($condition)
	{
		global $Globali;
		
		$sql="SELECT count(u.id) 'count'  FROM `user` u WHERE 1 ".$condition;
		$res = $Globali->justSelectQuery($sql);
		$data=$res->fetch_assoc();
		return $data['count'];
	}
	
	
	//get total organizers signedup for specific dates 
	public function getTotalOrgsSignedup($condition)
	{
		global $Globali;
		
		$sql="SELECT count(u.id) 'count' FROM user AS u, organizer AS org WHERE u.id = org.userid ".$condition;
		$res = $Globali->justSelectQuery($sql);
		$data=$res->fetch_assoc();
		return $data['count'];
	}
	
	
	//get total events added for specific dates 
	public function getTotalEventsAdded($condition)
	{
		global $Globali;
		
		$sql="SELECT count(e.id) 'count' FROM event AS e where 1 AND e.deleted = 0 ".$condition;
		$res = $Globali->justSelectQuery($sql);
		$data=$res->fetch_assoc();
		return $data['count'];
	}
	
	
	//get total paid events added for specific dates 
	public function getTotalPaidEventsAdded($condition)
	{
		global $Globali;
		
		$sql="SELECT count(e.id) 'count' FROM event AS e where 1 AND e.deleted = 0 ".$condition;
		$res = $Globali->justSelectQuery($sql);
		$data=$res->fetch_assoc();
		return $data['count'];
	}
	
	
	//get total free events added for specific dates 
	public function getTotalFreeEventsAdded($condition)
	{
		global $Globali;
		
		$sql="SELECT count(e.id) 'count' FROM event AS e where 1 AND e.deleted = 0 ".$condition;
		$res = $Globali->justSelectQuery($sql);
		$data=$res->fetch_assoc();
		return $data['count'];
	}
	
	
	//get total noreg events added for specific dates 
	public function getTotalNoRegEventsAdded($condition)
	{
		global $Globali;
		
		$sql="SELECT count(e.id) 'count' FROM event AS e where 1 AND e.deleted = 0 ".$condition;
		$res = $Globali->justSelectQuery($sql);
		$data=$res->fetch_assoc();
		return $data['count'];
	}
	
	
	//get total Unique events added for specific dates 
	public function getTotalUniqueEventsAdded($condition)
	{
		global $Globali;
		
		$sql="select count(DISTINCT e.id) 'count' FROM eventsignup AS s 
		INNER JOIN event AS e ON s.eventid = e.id 
		WHERE 1 and e.deleted = 0 ".$condition." and s.totalamount > 0 AND ((s.paymentmodeid=1 and s.paymenttransactionid != 'A1') or s.paymentmodeid=2  or s.paymentmodeid=3 or s.paymentmodeid=4 or (s.discount !='X')) 
		and s.paymentstatus!='Canceled' ";
		$res = $Globali->justSelectQuery($sql);
		$data=$res->fetch_assoc();
		return $data['count'];
	}
	
	
	//get total Unique orgs added for specific dates 
	public function getTotalUniqueOrgsAdded($condition)
	{
		global $Globali;
		
		$sql="select count(distinct e.ownerid) 'count' FROM eventsignup AS s 
		INNER JOIN event AS e ON s.eventid = e.id 
		WHERE 1 and e.deleted = 0 ".$condition." and s.totalamount != 0 AND ((s.paymentmodeid=1 and s.paymenttransactionid != 'A1') or s.paymentmodeid=2  or s.paymentmodeid=3 or s.paymentmodeid=4 or (s.discount !='X')) 
		and s.paymentstatus!='Canceled' ";
		
		$res = $Globali->justSelectQuery($sql);
		$data=$res->fetch_assoc();
		return $data['count'];
	}
	
	
	//get total Unique Cities added for specific dates 
	public function getTotalUniqueCitiesAdded($condition)
	{
		global $Globali;
		
		$sql="select count(distinct(e.cityid)) as unqCityCount 
		FROM eventsignup AS s 
		INNER JOIN event AS e ON s.eventid = e.id 
		WHERE 1 ".$condition." and s.totalamount!= 0 AND ((s.paymentmodeid=1 and s.paymenttransactionid != 'A1') or s.paymentmodeid=2  or s.paymentmodeid=3 or s.paymentmodeid=4 or (s.discount !='X')) 
		and s.paymentstatus!='Canceled' ";
		$res = $Globali->SelectQuery($sql);
		return $res;
	}
	
	
	
	//get transactions by org and event wise (ctrl -> Management Report -> sales by org)
	public function getTransactionsByEventAndOrgWise($extracharge=FALSE,$condition,$organizerCity)
	{
		global $Globali;
		
		if($extracharge){$ecSql=" ROUND(sum(s.totalamount),2) 'totalAmount' ";}else{$ecSql=" ROUND(sum((s.totalamount)-s.eventextrachargeamount),2) 'totalAmount' ";}
		$selCity=$cityJoin="";
                if(!empty($organizerCity)){
                    $selCity=",c.name as cityname";
                    $cityJoin=" LEFT JOIN city c ON c.id=u.cityid";
                    switch ($organizerCity) {
                        case 'other':$organizerQry=" and u.cityid IN (select c.id from user u INNER JOIN organizer o ON o.userid=u.id INNER JOIN city c ON c.id=u.cityid where c.status=0)";
                            break;
                        case 'allcities':
                            break;
                        default:$organizerQry=" and u.cityid IN(".$organizerCity.") ";$selCity=$cityJoin="";
                            break;
                    }
                }
                
                $sql="SELECT s.eventid as EventId, s.`id` as Id, s.signupdate as SignupDt, SUM(s.quantity) 'tktQty',  $ecSql, s.paymenttransactionid as PaymentTransId, e.ownerid as UserID,e.title as Title,u.username as UserName,cy.code,u.company as Company $selCity  
				FROM eventsignup AS s 
				INNER JOIN event AS e ON s.eventid = e.id 
				INNER JOIN eventdetail AS ed ON ed.eventid = e.id
				INNER JOIN user AS u ON e.ownerid = u.id INNER JOIN currency cy on cy.id=s.fromcurrencyid $cityJoin
				WHERE 1 AND e.deleted = 0 ".$condition.$organizerQry." AND (s.paymenttransactionid != 'A1' or s.discount = 'Y' or s.discount ='PayatCounter' or  s.paymentgatewayid = 2 or s.paymentmodeid=2) and s.paymentstatus not in ('Canceled','refunded') and cy.code!=''
				group by s.eventid,cy.id ORDER BY e.ownerid DESC";
		$res = $Globali->justSelectQuery($sql);
		return $res;
	}
	
		public function getTransactionsByEventAndOrgWiseAndRevenue($extracharge=FALSE,$condition,$organizerCity)
	{
		global $Globali;
		
		if($extracharge){$ecSql=" ROUND(sum(s.totalamount),2) 'totalAmount' ";}else{$ecSql=" ROUND(sum((s.totalamount)-s.eventextrachargeamount),2) 'totalAmount' ";}
		
		$selCity=$cityJoin="";
                 if(!empty($organizerCity)){
                    $selCity=",c.name as cityname";
                    $cityJoin=" LEFT JOIN city c ON c.id=u.cityid";
                    }
					
               $sql="SELECT 
                 round((select value from commission cm where cm.eventid=e.id and cm.type=1 and cm.deleted=0 group by cm.eventid),2) as cardcommission,
               round((select value from commission cm where cm.eventid=e.id and cm.type=11 and cm.deleted=0 group by cm.eventid),2) as mecommission,
                 s.eventid as EventId,ca.`name` as Category,es.percentage as percentage,  sum(s.eventextrachargeamount) AS ExAmount, s.`id` as Id, s.signupdate as SignupDt, SUM(s.quantity) 'tktQty',  $ecSql,
				SUM(CASE WHEN s.promotercode = '' AND s.totalamount != 0  Then (s.quantity)
                     else 0
                end) 'MEQty',
					SUM(CASE WHEN s.promotercode != '' AND s.promotercode != 'organizer'    Then (s.quantity)
                     else 0
                end) 'OthQty',
				SUM(CASE WHEN s.promotercode != '' AND s.promotercode != 'organizer'  AND s.totalamount != 0  Then (s.totalamount-s.eventextrachargeamount)
                     else 0
                end) 'OthAmount',
				SUM(CASE WHEN s.promotercode = '' AND s.totalamount != 0  Then (s.totalamount-s.eventextrachargeamount)
                     else 0
                end) 'MEAmount',
				SUM(CASE WHEN s.promotercode = 'organizer' AND s.totalamount != 0 Then (s.quantity)
                     else 0
                end) 'OrgQty',
       SUM(CASE WHEN s.promotercode = 'organizer' AND s.totalamount != 0 AND s.signupdate < '2016-03-31 18:30:01'   Then (s.totalamount-s.eventextrachargeamount)
                     else 0
                end) 'OrgAmount',
        SUM(CASE WHEN s.promotercode = 'organizer' AND s.totalamount != 0 AND s.signupdate > '2016-03-31 18:30:01' AND  s.signupdate < '2016-05-31 18:30:01'  Then (s.totalamount-s.eventextrachargeamount)
                     else 0
                end) 'OrgAmount1', 
         SUM(CASE WHEN s.promotercode = 'organizer' AND s.totalamount != 0 AND s.signupdate > '2016-05-31 18:30:01'   Then (s.totalamount-s.eventextrachargeamount)
                     else 0
                end) 'OrgAmount2',  				
				s.paymenttransactionid as PaymentTransId, e.ownerid as UserID,e.title as Title,u.username as UserName,cy.code,u.company as Company $selCity  
				FROM eventsignup AS s 
				INNER JOIN event AS e ON s.eventid = e.id 
				INNER JOIN eventdetail AS ed ON ed.eventid = e.id
				INNER JOIN eventsetting AS es on es.eventid = e.id
				INNER JOIN category AS ca ON ca.id = e.categoryid
				INNER JOIN user AS u ON e.ownerid = u.id INNER JOIN currency cy on cy.id=s.fromcurrencyid $cityJoin
				WHERE 1 ".$condition.$organizerQry." AND (s.paymenttransactionid != 'A1' or s.discount = 'Y' or s.discount ='PayatCounter' or  s.paymentgatewayid = 2 or s.paymentmodeid=2) and s.paymentstatus not in ('Canceled','refunded') and cy.code!=''
				group by s.eventid,cy.id ORDER BY e.ownerid DESC";
		$res = $Globali->justSelectQuery($sql);
		return $res;
	}
	
	public function getTransactionsByEventAndOrgWiseAndRevenueUpcoming($extracharge=FALSE,$condition,$organizerCity)
	{
		global $Globali;
		
			if($extracharge){$ecSql=" ROUND(sum(s.totalamount),2) 'totalAmount' ";}else{$ecSql=" ROUND(sum((s.totalamount)-s.eventextrachargeamount),2) 'totalAmount' ";}
		
		$selCity=$cityJoin="";
                if(!empty($organizerCity)){
                    $selCity=",c.name as cityname";
                    $cityJoin=" LEFT JOIN city c ON c.id=u.cityid";
                    }
                
               $sql="SELECT 
                  round((select value from commission cm where cm.eventid=e.id and cm.type=1 and cm.deleted=0 group by cm.eventid),2) as cardcommission,
               round((select value from commission cm where cm.eventid=e.id and cm.type=11 and cm.deleted=0 group by cm.eventid),2) as mecommission,
                  s.eventid as EventId,ca.`name` as Category,es.percentage as percentage,  sum(s.eventextrachargeamount) AS ExAmount, s.`id` as Id, s.signupdate as SignupDt, SUM(s.quantity) 'tktQty',  $ecSql,
				SUM(CASE WHEN s.promotercode = '' AND s.totalamount != 0  Then (s.quantity)
                     else 0
                end) 'MEQty',
					SUM(CASE WHEN s.promotercode != '' AND s.promotercode != 'organizer'  AND s.totalamount != 0  Then (s.quantity)
                     else 0
                end) 'OthQty',
				SUM(CASE WHEN s.promotercode != '' AND s.promotercode != 'organizer'  AND s.totalamount != 0  Then (s.totalamount-s.eventextrachargeamount)
                     else 0
                end) 'OthAmount',
				SUM(CASE WHEN s.promotercode = '' AND s.totalamount != 0  Then (s.totalamount-s.eventextrachargeamount)
                     else 0
                end) 'MEAmount',
				SUM(CASE WHEN s.promotercode = 'organizer' AND s.totalamount != 0 Then (s.quantity)
                     else 0
                end) 'OrgQty',
        SUM(CASE WHEN s.promotercode = 'organizer' AND s.totalamount != 0 AND s.signupdate < '2016-03-31 18:30:01'   Then (s.totalamount-s.eventextrachargeamount)
                     else 0
                end) 'OrgAmount',
       SUM(CASE WHEN s.promotercode = 'organizer' AND s.totalamount != 0 AND s.signupdate > '2016-03-31 18:30:01' AND  s.signupdate < '2016-05-31 18:30:01'  Then (s.totalamount-s.eventextrachargeamount)
                     else 0
                end) 'OrgAmount1', 
         SUM(CASE WHEN s.promotercode = 'organizer' AND s.totalamount != 0 AND s.signupdate > '2016-05-31 18:30:01'   Then (s.totalamount-s.eventextrachargeamount)
                     else 0
                end) 'OrgAmount2', 		
				s.paymenttransactionid as PaymentTransId, e.ownerid as UserID,e.title as Title,u.username as UserName,cy.code,u.company as Company $selCity  
				FROM eventsignup AS s 
				INNER JOIN event AS e ON s.eventid = e.id 
				INNER JOIN eventdetail AS ed ON ed.eventid = e.id
				INNER JOIN eventsetting AS es on es.eventid = e.id
				INNER JOIN category AS ca ON ca.id = e.categoryid
				INNER JOIN user AS u ON e.ownerid = u.id INNER JOIN currency cy on cy.id=s.fromcurrencyid $cityJoin
				WHERE 1 ".$condition.$organizerQry." AND (s.paymenttransactionid != 'A1' or s.discount = 'Y' or s.discount ='PayatCounter' or  s.paymentgatewayid = 2 or s.paymentmodeid=2) and s.paymentstatus not in ('Canceled','refunded') and cy.code!=''
				group by s.eventid,cy.id ORDER BY e.ownerid DESC";
		$res = $Globali->justSelectQuery($sql);
		return $res;
	}
	
	/*sales by city page*/
	//get CARD transactions by citywise 
	public function getEventCardTrsByCity($extracharge=FALSE,$condition)
	{
		global $Globali;
		
		if($extracharge){$ecSql=" ROUND(sum(s.totalamount),2) 'totalAmount' ";}else{$ecSql=" ROUND(sum((s.totalamount)-s.eventextrachargeamount),2) 'totalAmount' ";}
		
		 $sql="SELECT count(s.Id) 'count',s.EventId, s.Id,e.CityId, e.StateId, $ecSql, s.eventextrachargeamount as Ccharge,e.Title 
				FROM eventsignup AS s 
				INNER JOIN event AS e ON s.eventid = e.id 
				INNER JOIN eventdetail AS ed ON ed.eventid = e.id
				WHERE 1 AND e.deleted = 0 ".$condition."  AND (s.totalamount != 0 AND s.paymenttransactionid != 'A1') 
				and s.paymentstatus not in ('Canceled','refunded')  
				group by e.cityid ORDER BY s.eventid DESC";
                 
              
		$res = $Globali->justSelectQuery($sql);
		return $res;
	}
	
	/*sales by Category page*/
	//get CARD transactions by category 
	public function getEventCardTrsByCategory($extracharge=FALSE,$condition)
	{
		global $Globali;
		
		if($extracharge){$ecSql=" ROUND(sum(s.totalamount),2) 'totalAmount' ";}else{$ecSql=" ROUND(sum((s.totalamount)-s.eventextrachargeamount),2) 'totalAmount' ";}
		
		 $sql="SELECT count(s.id) 'count',s.eventid, s.id,e.cityid,e.categoryid, e.stateid, $ecSql, s.eventextrachargeamount as Ccharge,e.title 
				FROM eventsignup AS s 
				INNER JOIN event AS e ON s.eventid = e.id 
				INNER JOIN eventdetail AS ed ON ed.eventid = e.id
				WHERE 1 AND e.deleted = 0 ".$condition."  AND (s.totalamount != 0 AND s.paymenttransactionid != 'A1') 
				and s.paymentstatus not in ('Canceled','refunded')  
				group by e.categoryid ORDER BY s.eventid DESC";
                 
              
		$res = $Globali->justSelectQuery($sql);
		return $res;
	}
	
	
	//get PayAtCounter transactions by citywise 
	public function getEventPACTrsByCity($extracharge=FALSE,$condition)
	{
		global $Globali;
		
		if($extracharge){$ecSql=" ROUND(sum(s.totalamount),2) 'totalAmount' ";}else{$ecSql=" ROUND(sum((s.totalamount)-s.eventextrachargeamount),2) 'totalAmount' ";}
		
		 $sql="SELECT count(s.id) 'count',s.eventid as EventId, s.`id` as Id, $ecSql, s.eventextrachargeamount as Ccharge,e.title as Title ,e.cityid as CityId, e.stateid as StateId 
				FROM eventsignup AS s 
				INNER JOIN event AS e ON s.eventid = e.id 
				INNER JOIN eventdetail AS ed ON ed.eventid = e.id
				WHERE 1 ".$condition."  AND (s.totalamount != 0 AND (s.discount = 'Y' or s.discount ='PayatCounter' )) 
				and s.paymentstatus not in ('Canceled','refunded')  
				group by e.cityid ORDER BY s.eventid DESC";
		
		
		$res = $Globali->justSelectQuery($sql);
		return $res;
	}
	
	
	//get COD transactions by citywise 
	public function getEventCODTrsByCity($extracharge=FALSE,$condition)
	{
		global $Globali;
		
		if($extracharge){$ecSql=" ROUND(sum(s.totalamount),2) 'totalAmount' ";}else{$ecSql=" ROUND(sum((s.totalamount)-s.eventextrachargeamount),2) 'totalAmount' ";}
		
		$sql="SELECT count(s.id) 'count',s.eventid as EventId, s.`id` as Id, $ecSql, s.eventextrachargeamount as Ccharge,e.cityid as CityId, e.stateid as StateId 
				FROM eventsignup AS s 
				INNER JOIN event AS e ON s.eventid = e.id 
				INNER JOIN eventdetail AS ed ON ed.eventid = e.id
				WHERE 1 ".$condition."  AND (s.totalamount != 0 AND s.paymentgatewayid = 2 AND s.paymentmodeid=2) 
				and s.paymentstatus not in ('Canceled','refunded')  
				group by e.cityid ORDER BY s.eventid DESC";
		
		
		$res = $Globali->justSelectQuery($sql);
		return $res;
	}
	
	
	//get Cheque transactions by citywise 
	public function getEventChqTrsByCity($extracharge=FALSE,$condition)
	{
		global $Globali;
		
		if($extracharge){$ecSql=" ROUND(sum(s.totalamount),2) 'totalAmount' ";}else{$ecSql=" ROUND(sum((s.totalamount)-s.eventextrachargeamount),2) 'totalAmount' ";}
		
		$sql="SELECT count(s.id) 'count',s.eventid as EventId, s.`id` as Id, $ecSql, s.eventextrachargeamount as Ccharge,e.title as Title , cq.chequenumber as ChqNo, cq.chequedate as ChqDt, cq.chequebank as ChqBank, cq.status as Cleared, cq.id AS chequeId
			FROM eventsignup AS s
			Inner Join chequepayments AS cq on s.id = cq.eventsignupid 
			INNER JOIN `event` AS e ON s.eventid = e.id 
			INNER JOIN eventdetail AS ed ON ed.eventid = e.id
			
				WHERE 1 AND e.deleted = 0 ".$condition."  AND (s.totalamount != 0 AND s.paymentmodeid=3) 
				and s.paymentstatus not in ('Canceled','refunded')  
				group by e.cityid ORDER BY s.eventid, s.signupdate DESC";
		
		
		$res = $Globali->justSelectQuery($sql);
		return $res;
	}
	
	
	//get total users by citywise 
	public function getTotalUsersSignedUpByCity($condition)
	{
		global $Globali;
		
		$sql="SELECT sum(s.quantity) 'totalSignedUpUsers',e.cityid as CityId,e.stateid as StateId
				FROM eventsignup AS s 
				INNER JOIN event AS e ON s.eventid = e.id 
			    INNER JOIN eventdetail AS ed ON ed.eventid = e.id
				WHERE 1 AND e.deleted = 0 ".$condition."  AND (s.totalamount=0 or (s.paymentmodeid=1 and s.paymenttransactionid != 'A1') or s.paymentmodeid=2  or s.paymentmodeid=3 or s.paymentmodeid=4 or (s.discount !='X'))  
				group by s.userid,e.cityid ORDER BY s.eventid, s.signupdate DESC";
		
		
		$res = $Globali->justSelectQuery($sql);
		return $res;
	}
	
	
	//get total Free users by citywise 
	public function getTotalFreeUsersSignedUpByCity($condition)
	{
		global $Globali;
		
		 $sql="SELECT sum(s.quantity) 'totalFreeUsers',e.cityid as CityId,e.stateid as StateId
				FROM eventsignup AS s 
				INNER JOIN event AS e ON s.eventid = e.id 
			    INNER JOIN eventdetail AS ed ON ed.eventid = e.id
				WHERE 1 AND e.deleted = 0 ".$condition."  AND (e.registrationtype=1 or s.totalamount=0)  
				group by s.userid,e.cityid ORDER BY s.eventid, s.signupdate DESC";
		
		
		$res = $Globali->justSelectQuery($sql);
		return $res;
	}
	
	
	//get total Paid users by citywise 
	public function getTotalPaidUsersSignedUpByCity($condition)
	{
		global $Globali;
		
		$sql="SELECT sum(s.quantity) 'totalPaidUsers',e.cityid as CityId,e.stateid as StateId
				FROM eventsignup AS s 
				INNER JOIN event AS e ON s.eventid = e.id 
			    INNER JOIN eventdetail AS ed ON ed.eventid = e.id
				WHERE 1 AND e.deleted = 0 ".$condition."   AND (s.totalamount=0 or (s.paymentmodeid=1 and s.paymenttransactionid != 'A1') or s.paymentmodeid=2  or s.paymentmodeid=3 or s.paymentmodeid=4 or (s.discount !='X')) 
				group by s.userid,e.cityid ORDER BY s.eventid DESC";
		
		
		$res = $Globali->justSelectQuery($sql);
		return $res;
	}
	
	
	
	//get total users by citywise 
	public function getTotalUsersByCity($condition)
	{
		global $Globali;
		
		$sql="SELECT count(u.id) 'totalUsers', u.cityid as CityId,u.stateid as StateId FROM `user` u WHERE 1 ".$condition." group by u.cityid, u.email ";
		
		
		$res = $Globali->justSelectQuery($sql);
		return $res;
	}
	
	//get total orgs by citywise 
	public function getTotalOrgsByCity($condition)
	{
		global $Globali;
		
		$sql="SELECT count(distinct u.id) 'totalOrgs', u.cityid as CityId,u.stateid as StateId FROM `user` u 
			    inner join organizer o on u.id=o.userid
				WHERE 1 ".$condition." group by u.cityid, u.email ";
		
		
		$res = $Globali->justSelectQuery($sql);
		return $res;
	}
	
	
	//get total events added by citywise 
	public function getTotalEventsByCity($condition)
	{
		global $Globali;
		
		$sql="SELECT count(e.id) 'totalEvents', e.cityid as CityId,e.stateid as StateId FROM event as e WHERE 1 ".$condition." and e.deleted=0 group by e.cityid ";
		
		$res = $Globali->justSelectQuery($sql);
		return $res;
	}
	
	
	//get total free events added by citywise 
	public function getTotalFreeEventsByCity($condition)
	{
		global $Globali;
		
		$sql="SELECT count(e.id) 'totalFreeEvents', e.cityid as CityId,e.stateid as StateId FROM event as e WHERE 1 ".$condition." and e.registrationtype=1  group by e.cityid ";
		
		$res = $Globali->justSelectQuery($sql);
		return $res;
	}
	
	
	//get total paid events added by citywise 
	public function getTotalPaidEventsByCity($condition)
	{
		global $Globali;
		
		$sql="SELECT count(e.id) 'totalPaidEvents', e.cityid as CityId,e.stateid as StateId FROM event as e WHERE 1 ".$condition." and e.registrationtype=2 and e.deleted=0 group by e.cityid";
		
		$res = $Globali->justSelectQuery($sql);
		return $res;
	}
	
	
	//get total NoReg events added by citywise 
	public function getTotalNoRegEventsByCity($condition)
	{
		global $Globali;
		
		$sql="SELECT count(e.id) 'totalNoRegEvents', e.cityid as CityId,e.stateid as StateId FROM event as e WHERE 1 ".$condition." and e.registrationtype = 3 and e.deleted=0  group by e.cityid ";
		
		$res = $Globali->justSelectQuery($sql);
		return $res;
	}
	
	
	//get total unique events added by citywise 
	public function getTotalUnqEventsByCity($condition)
	{
		global $Globali;
		
		$sql="select count(distinct e.id) 'totalUnqEvents',e.cityid as CityId,e.stateid as StateId FROM eventsignup as s
				INNER JOIN event AS e ON s.eventid = e.id
				WHERE 1 AND e.deleted = 0 ".$condition." AND s.totalamount!=0 and ( (s.paymentmodeid=1 and s.paymenttransactionid != 'A1') or s.paymentmodeid=2  or s.paymentmodeid=3 or s.paymentmodeid=4 or (s.discount !='X')) 
				and s.paymentstatus!='Canceled' 
				group by e.cityid ";
		
		$res = $Globali->justSelectQuery($sql);
		return $res;
	}
	
	
	//get total unique users added by citywise 
	public function getTotalUnqUsersByCity($condition)
	{
		global $Globali;
		
		 $sql="select count(distinct e.ownerid) 'totalUnqUsers',e.cityid as CityId,e.stateid as StateId FROM eventsignup as s
				INNER JOIN event AS e ON s.eventId = e.id
				WHERE 1 AND e.deleted = 0 ".$condition." AND s.totalamount!=0 and ( (s.paymentmodeid=1 and s.paymenttransactionid != 'A1') or s.paymentmodeid=2  or s.paymentmodeid=3 or s.paymentmodeid=4 or (s.discount !='X')) 
				and s.paymentstatus!='Canceled' 
				group by e.cityid ";
		
		$res = $Globali->justSelectQuery($sql);
		return $res;
	}
	
	
	
	
	
	
	
	
	
	

};




?>