<?php
/******************************************************************************************************************************************
 *	File deatils:
 *	Edit the city name aginst the state
 *	
 *	Created / Updated on:
 *	1.	Using the MT the file is updated on 22nd Aug 2009
******************************************************************************************************************************************/

	session_start();
	
	include_once("MT/cGlobali.php");
	include_once("MT/cCities.php");
	
	$Global = new cGlobali();
 include 'loginchk.php';
// error_reporting(-1);ini_set('display_errors',1);
 $cityid = isset($_GET['cityid'])?$_GET['cityid']:$_GET['id'];
 $stateid = isset($_GET['stateid'])?$_GET['stateid']:"";
 $countryid = isset($_GET['countryid'])?$_GET['countryid']:"";
 
 if(empty($stateid)){
     
      $stateQuer="select stateid,countryid from statecity as sc join state as st on st.id=sc.stateid where cityid=".$cityid;
     $stateQuerRes = $Global->SelectQuery($stateQuer);
     $stateid=$stateQuerRes[0]['stateid'];
     $countryid=$stateQuerRes[0]['countryid'];
     
 } 
    $statename=$countryname="";
    if($stateid > 0)
        $statename = $Global->GetSingleFieldValue("select name from state where id =".$stateid);
    
    if($countryid > 0)
     $countryname = $Global->GetSingleFieldValue("select name from country where id =".$countryid);
 

    
        //To check the duplicate state value is updating or not
    if(isset($_POST['Submit'])){
        $city_name = $_POST['city'];
        $city_name = trim($city_name);
        $check_duplicate_status=check_duplicate_city($Global,$city_name,$cityid,$stateid,$countryid);
        $error_message=($check_duplicate_status == TRUE)?"You are trying to inserting the duplicate value":"";
    }
	if($_POST['Submit'] == "Update" && !$check_duplicate_status)
	{
		
//		$city_name = strtolower($city_name);
		
		// MAKE ALL FIRST LETTERS OF EACH WORD CAPITAL
		$names = explode(" ",$city_name);
		foreach($names as $key => $val)
		{
			$words[] = ucfirst($val);
		}
		$city_name = implode(" ",$words);
		$featured = $_POST['featured'];
		$status= $_POST['status'];
		if(isset($_POST['specialcity']) && $_POST['specialcity']==1){
			$splcitystateid = $_REQUEST['stateid'];
		}else{
			$splcitystateid = 0;
		}
		$order = $_POST['order'];
		$order = trim($order);
		
		try
		{
			 $query = "update city set `name`='". $city_name."',`status`='".$status."',`featured`='".$featured."',`order`='".$order."',`splcitystateid`='".$splcitystateid."',`mts`=now(),`modifiedby`='".$_SESSION['uid']."' where id =".$cityid ; 
			$res =  $Global->ExecuteQuery($query);
			
			//$City = new cCities($Id, $city_name, $state_id);$Id = $City->Save();

			
            if(isset($_GET["type"])){
                header("location: auto_suggest_review.php?&update_record=".$_GET["type"]);exit;
            }
	
			if($res)
			{
				header("location:editcities.php?countryid=".$countryid."&stateid=".$stateid);
			}
		}
		catch (Exception $Ex)
		{
			echo $Ex->getMessage();
		}
	}// END IF update
 
    $Id = $_GET['id'];


    //$CityQuery = "SELECT * FROM Cities WHERE Id = '".$Id."'";
           $CityQuery = "SELECT c.*  FROM city AS c WHERE c.id = '".$cityid."'";
           $CityList = $Global->SelectQuery($CityQuery);


function check_duplicate_city($global,$compare_string,$ignore_id,$state_id,$countryid){
    $query = "SELECT c.* FROM city AS c "
                . " Left Join statecity as cs on c.id = cs.id "
                . " WHERE c.name= '".$compare_string."' and c.id!=".$ignore_id." and c.status=1 and cs.stateid=".$state_id." and c.countryid=".$countryid;
    $record_list = $global->SelectQuery($query);
    if(count($record_list)> 0){
        return TRUE;
    }
    return FALSE;
}
	
	include 'templates/city_edit.tpl.php';
?>