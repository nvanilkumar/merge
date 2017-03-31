<?php

session_start();
include 'loginchk.php';
include_once("MT/cGlobali.php");

$Global = new cGlobali();
$Globali=new cGlobali();
$status_message="";
$uid =	$_SESSION['uid'];

$edit_record_name=$_GET["edit_record"];
if(isset($edit_record_name)){
     $status=($_GET["ignore_type"]=="yes")?2:1;
     
     $update_id=$_GET["id"];
     $table_names_map=array("country"=>"country",
                             "state" =>"state",   
                             "city" =>"city",   
                             "location" =>"Location",   
                             "designation" =>"Designations",   
         );
     
     $compare_string=get_name($table_names_map[$edit_record_name],$update_id,$Global);
     $type=check_value($table_names_map[$edit_record_name],$Global,$compare_string,$update_id);
     if(!$type ||($_GET["ignore_type"]=="yes")){
        
       update_status($table_names_map[$edit_record_name],$update_id,$status,$Globali);
       $status_message=($_GET["ignore_type"]=="yes")? "Record Ignored successfully":"Record updated successfully"; 
     }else{
         $status_message="you are trying to adding duplicate record";
         $ignore_message=$update_id;
     }

     
   
}//end of edit if
   $dateFilter="";     
//To Retrive the Inactive records list
if ((($_POST['submit'] == "Show Records")&& strlen($_POST['review_type'])>0) || ($edit_record_name)||($_GET["update_record"])) {
    $record_type=($_POST["review_type"])?$_POST["review_type"]:$edit_record_name;
    $record_type=($record_type)?$record_type:$_GET["update_record"];
    $join_type="Left ";
    
    //form does not seting we are unseting the session
    if(empty($_POST["event_records"]) && strlen($_GET["update_record"])===0 ){
        unset($_SESSION["event_records"]);
    }
    if(empty($_POST["published"]) && strlen($_GET["published"])===0 ){
        unset($_SESSION["published"]);
    }
    $isPublished="";
    if($_POST["published"]=="published" || (isset($_SESSION["published"]))){
         $_SESSION["published"]="published";
         $isPublished=" and e.status=1";
    }
    if($_POST["event_records"]=="event_records" || (isset($_SESSION["event_records"]))){
        $_SESSION["event_records"]="event_records";
        $join_type="";
        $dateFilter=" and e.enddatetime>=now()";
    }else if(strlen($_GET["update_record"])===0){  
        unset($_SESSION["event_records"]);
    }
    
    switch($record_type){

        case "country":
                	$query = "SELECT c.*,e.id as eId,e.ownerid,e.title,e.status as Published  FROM country c  "
                                .$join_type. "Join event as e on e.countryid=c.id   "
                                . "where c.status = 2".$dateFilter.$isPublished; 
                    $table_head= array("Name", "Edit", "Status","Event Details","Event Status");
                    $table_body_field_names= array("Country" => "name", 
                                                   "Edit" => "country_edit.php?type=country&id=",
                                                   "Status" => "id",
                                                   "Event_Details" => "Event Details" ,
                                                "Event Status" =>"Published"   
                    );
                break;
        case "state":
                $query= "SELECT s.*, c.name as countryname,e.id as eId,e.ownerid,e.title,e.status as Published FROM state AS s "
                ."Left Join country as c on c.id = s.countryid "
                .$join_type." Join event as e on e.stateid=s.id"
                . " WHERE  s.status =2".$dateFilter.$isPublished." ORDER BY c.name, s.name ASC";
                $table_head= array("State Name", "Country", "Edit", "Status","Event Details","Event Status");
                $table_body_field_names= array("State" => "name", 
                                                "Country" => "countryname", 
                                                "Edit" => "state_edit.php?&type=state&id=",
                                                "Status" => "id",
                                                "Event_Details" => "Event Details",
                                                "Event Status" =>"Published"   
                    );
                break;
        case "city":
                $query = "SELECT c.*, s.name as statename,e.id as eId,e.ownerid,e.title,e.status as Published FROM city AS c "
				. "Left Join statecity as sc on sc.cityid = c.id "
                . "Left Join state as s on s.id = sc.stateid "
                .$join_type. "Join event as e on e.cityid=c.id "
                . " WHERE c.status = 2".$dateFilter.$isPublished." ORDER BY s.name, c.name DESC";
                $table_head= array("City Name", "State", "Edit", "Status","Event Details","Event Status");
                $table_body_field_names= array("City" => "name", 
                                                "State" => "statename", 
                                                "Edit" => "city_edit.php?&type=city&id=",
                                                "Status" => "id",
                                                "Event_Details" => "Event Details",   
                                                "Event Status" =>"Published"
                    );
                break;
        /*case "location":
                $query= "SELECT a.Id as Id, a.StateId as StateId, a.CityId as CityId,a.Loc as Loc, b.State as State,c.City as City FROM Location a, States b, Cities c WHERE b.Id = a.StateId and c.Id = a.CityId and a.status=0 order by  b.State";
                $table_head= array("State","City","Name", "Edit", "Status");
                $table_body_field_names= array("State" => "State", 
                                "City" => "City", 
                                "Loc" => "Loc", 
                                "Edit" => "location_edit.php?id=",
                                "Status" => "Id");
                break;
        case "designation":
                $query= "SELECT * FROM Designations where status=0 ORDER BY Designation ASC";
                $table_head= array("Name", "Edit", "Status");
                $table_body_field_names= array("Designation" => "Designation", 
                                                   "Edit" => "designation_edit.php?id=",
                                                   "Status" => "id");
                break;*/
    }//end of switch
	
    $record_list = $Global->SelectQuery($query);
        
    
}// END of show records

//Update the record
function update_status($table_name,$update_id,$status,$Globali){
    $query="UPDATE ".$table_name." SET status = ? WHERE id = ?";
        $update_stmt=$Globali->dbconn->prepare($query);
        $update_stmt->bind_param("ii",$status,$update_id);

        $update_stmt->execute();

        
}

//To get the filed value
function get_name($table_name,$id,$global){
    $table_filed_name=array("country"=>"Country",
                             "state" =>"State",   
                             "city" =>"City");
    $query="select * from ".$table_name." where id=".$id;
    $record_list = $global->SelectQuery($query);

    return $record_list[0][$table_filed_name[$table_name]];
        
}

//compare the value exist are not before change the status -1
function check_value($table_name,$global,$compare_string,$ignore_id){
        
    switch ($table_name){
        case "country":
                	$query = "SELECT * FROM country c "
                             . " where name= '".$compare_string."' and id!=".$ignore_id." and c.status=1";
                break;
        case "state":
                $query= "SELECT s.* FROM state AS s "
                . " Left Join country as c on c.id = s.countryid"
                . " WHERE  s.name= '".$compare_string."' and s.id!=".$ignore_id." and s.status=1";
        
                break;
        case "city":
                $query = "SELECT c.* FROM city AS c "
				. " Left Join statecity as sc on sc.cityid = c.id "
                . " Left Join state as s on sc.stateid = s.id "
                . " WHERE c.name= '".$compare_string."' and c.id!=".$ignore_id." and c.status=1";
        
                break;
        
    }
        
    $record_list = $global->SelectQuery($query);
    if(count($record_list)> 0){
        return true;
    }
    return false;
}

include 'templates/auto_suggest_review_tpl.php';
?>