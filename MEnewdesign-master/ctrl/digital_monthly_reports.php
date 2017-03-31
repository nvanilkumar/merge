<?php

session_start();

include_once("MT/cGlobali.php");
include 'loginchk.php';
include_once("includes/common_functions.php");
$common=new functions();

if(empty($_REQUEST["start_date"])){
    $start_date = date('d/m/Y', strtotime('-30 days'));
    $end_date = date("d/m/Y", mktime(0, 0, 0, date("m"), (date("d") - 1), date("Y")));
    $report_type = $_REQUEST['report_type'];
    include 'templates/digital_monthly_reports_tpl.php';
    exit;
}




 
class transaction_reports {

    public $globali = '';

    public function __construct() {
        $this->globali = new cGlobali();
    }

    //To send the transaction details between passed details
    
    public function get_reports($request) {
       
    if($request["report_type"] == "Transacted Users"){
     $count_query = "select   count(es.id) as es_count
                from eventsignup as es
                where
                es.signupdate between '" . $request['start_date'] . "' and '" . $request['end_date'] . "' and es.totalamount > 0
                and
                ((es.paymentgatewayid=2) or es.paymenttransactionid!='A1' or (es. paymentmodeid=2 and es.paymenttransactionid='A1' and es.paymentstatus='Verified')   or es.totalamount = 0)
                        and es.paymentstatus not in ('Canceled','Refunded')
                order by es.signupdate ";

        
        $select_query_res = $this->globali->SelectQuery($count_query);

        $response["total_records"] =  $select_query_res[0]['es_count'];
		
		if($response["total_records"] > 3000)
		{
			ini_set('memory_limit','900M');
			ini_set('max_execution_time', 360);
		}
		
        
                
        $query = "select  u.name 'Name',u.email as Email,u.mobile 'Phone',es.quantity 'Ticket Qty',(es.totalamount) 'Paid Amount', 
                c.name 'EventCity',c2.name 'UserCity',s.name as EventState,s1.name as UserState,
                ct.name 'Category'
                from user as u Left Join eventsignup as es on u.id = es.userid
                Inner Join event as e on es.eventid=e.id
                Left Join city as c2 on u.cityid=c2.id
                Inner Join category as ct on e.categoryid=ct.id
                Left Join city as c on e.cityid=c.id
                Left Join state as s on e.stateid=s.id
                Left Join state as s1 on u.stateid=s1.id
                where
                es.signupdate between '" . $request['start_date'] . "' and '" . $request['end_date'] . "' and es.totalamount > 0
                and
                ((es.paymentgatewayid=2) or es.paymenttransactionid!='A1' or (es. paymentmodeid=2 and es.paymenttransactionid='A1' and es.paymentstatus='Verified')   or es.totalamount = 0)
                        and es.paymentstatus not in ('Canceled','Refunded')
                order by es.signupdate desc";
               
        $query.=" limit " . $request['offset'] . "," . $request['records_per_page'];
        
        $response["records"] = $this->globali->SelectQuery($query,MYSQLI_ASSOC);
        
    
            return $response;
       } 
      else{
           
            $userids = "select distinct(u.id)as Id from user u 
                        inner join eventsignup es on es.userid = u.id
                        where u.signupdate between '" . $request['start_date'] . "' and '" . $request['end_date'] . "' 
                        order by u.signupdate desc";
           $select_userquery_res = $this->globali->SelectQuery($userids);
      
		   $idstr = NULL;
		   foreach($select_userquery_res as $uids)
		   {
			   $idstr.=$uids['Id'].",";
		   }
		   if(strlen($idstr > 0))
		   {
		   	$idstr=substr($idstr,0,-1);
		   }
     
           $user_query = "SELECT count(u.id) as ucount from user as u
                          WHERE u.id NOT
                          IN ( ".$idstr." )  
                          AND u.signupdate 
                          BETWEEN '" . $request['start_date'] . "' and '" . $request['end_date'] . "'" ; 
           $select_countquery_res = $this->globali->SelectQuery($user_query);
           
           $response["total_records"] =  $select_countquery_res[0]['ucount'];
		   
		   if($response["total_records"] > 3000)
		{
			ini_set('memory_limit','900M');
			ini_set('max_execution_time', 360);
		}
                

  
           $user = "SELECT u.name 'Name', u.email as Email, u.mobile 'Phone', ' ' AS 'Ticket Qty', ' ' AS 'Paid Amount', ' ' AS 'EventCity', c.name 'UserCity', ' ' AS EventState, s.name AS UserState, ' ' AS 'Category'
                    FROM user AS u
                    LEFT JOIN city AS c ON u.cityid = c.id
                    LEFT JOIN state AS s ON u.stateid = s.id
                    WHERE u.signupdate
                    BETWEEN '" . $request['start_date'] . "'
                    AND '" . $request['end_date'] . "'
                    AND u.id NOT
                    IN ( ".$idstr." )
                    ORDER BY u.signupdate DESC "; 

            $user.=" limit " . $request['offset'] . "," . $request['records_per_page'];
            $response["records"] = $this->globali->SelectQuery($user,MYSQLI_ASSOC);
       
          return $response;
           
       }  
       
   }
    public function data_output($columns, $data) {
        $out = array();

        for ($i = 0, $ien = count($data); $i < $ien; $i++) {
            $row = array();

            for ($j = 0, $jen = count($columns); $j < $jen; $j++) {
                $column = $columns[$j];

                // Is there a formatter?
                if (isset($column['formatter'])) {
                    $row[$column['dt']] = $column['formatter']($data[$i][$column['db']], $data[$i]);
                } else {
                    $row[$column['dt']] = $data[$i][$columns[$j]['db']];
                }
            }

            $out[] = $row;
        }

        return $out;
    }

}

if ($_REQUEST['start_date'] != "" && $_REQUEST['end_date'] != "") {
    $start_date = $_REQUEST['start_date'];
    $SDtExplode = explode("/", $start_date);
    $query_start_date = $SDtExplode[2] . '-' . $SDtExplode[1] . '-' . $SDtExplode[0] . ' 00:00:00';
    $query_start_date = $common->convertTime($query_start_date, DEFAULT_TIMEZONE);
		

    $end_date = $_REQUEST['end_date'];
    $EDtExplode = explode("/", $end_date);
    $query_end_date = $EDtExplode[2] . '-' . $EDtExplode[1] . '-' . $EDtExplode[0] . ' 23:59:59';
    $query_end_date = $common->convertTime($query_end_date, DEFAULT_TIMEZONE);
} else if ($_REQUEST['start_date'] != "") {
    $start_date = $_REQUEST['start_date'];
    $SDtExplode = explode("/", $start_date);
    $query_start_date = $SDtExplode[2] . '-' . $SDtExplode[1] . '-' . $SDtExplode[0] . ' 00:00:00';
    $query_start_date = $common->convertTime($query_start_date, DEFAULT_TIMEZONE);

    $query_end_date = $SDtExplode[2] . '-' . $SDtExplode[1] . '-' . $SDtExplode[0] . ' 23:59:59';
    $query_end_date = $common->convertTime($query_end_date, DEFAULT_TIMEZONE);
} else {
    $query_start_date = date('Y-m-d H:i:s', strtotime('-30 days'));
    $query_start_date = $common->convertTime($query_start_date, DEFAULT_TIMEZONE);
     
    $query_end_date = $common->convertTime(date('Y-m-d H:i:s'), DEFAULT_TIMEZONE);
}

//front end display field names map with databse query field names
$columns = array(
    array('db' => 'Name', 'dt' => 0),
    array('db' => 'Email', 'dt' => 1),
    array('db' => 'Phone', 'dt' => 2),
    array('db' => 'Ticket Qty', 'dt' => 3),
    array('db' => 'Paid Amount', 'dt' => 4),
    array('db' => 'EventCity', 'dt' => 5),
    array('db' => 'UserCity', 'dt' => 6),
    array('db' => 'EventState', 'dt' => 7),
    array('db' => 'UserState', 'dt' => 8),
    array('db' => 'Category', 'dt' => 9),
);

$data["start_date"] = $query_start_date;
$data["end_date"] = $query_end_date;
$data["report_type"]=$_REQUEST['report_type'];
$data["records_per_page"] = (isset($_REQUEST['iDisplayLength'])) ? $_REQUEST['iDisplayLength'] : 10;



$data["offset"] = ($_REQUEST['iDisplayStart']) ? $_REQUEST['iDisplayStart'] : 0;
$reports_object = new transaction_reports();
$result = $reports_object->get_reports($data);
//print_r($result);

$reports_result = array(
    "recordsTotal" => $result["total_records"],
    "recordsFiltered" => $result["total_records"],
    "data" => $reports_object->data_output($columns, $result["records"])
);

//echo count($reports_result['data']);

$jsonData=json_encode($reports_result);
echo $jsonData;

exit;

?>