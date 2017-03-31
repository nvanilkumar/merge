<?
include('includes/commondbdetails.php');

$db_conn = mysql_connect($DBServerName,$DBUserName,$DBPassword);
	mysql_select_db($DBIniCatalog);

mysql_select_db("meraeven_meraevent") or die("DB not found :".mysql_error());
if($_GET['cid'])
{
	$state="select * from country_master where country_name='".$_GET['cid']."'";
	$state_res=mysql_query($state)or die(mysql_error());
	$state_row=mysql_fetch_assoc($state_res);
	
	$state1="select * from state_master where country_id='".$state_row['country_id']."'";
	$state_res1=mysql_query($state1)or die(mysql_error());
	
	
	while($state_row1=mysql_fetch_assoc($state_res1))
	{
	$arr2[]=$state_row1['state_name'];
	}
	
	//echo $state_row1;
	//print_r($arr2);
	$st=implode(",",$arr2);
	echo $st;
}
if($_GET['sid'])
{
	$city="select * from state_master where state_name='".$_GET['sid']."'";
	$city_res=mysql_query($city)or die(mysql_error());
	$city_row=mysql_fetch_assoc($city_res);
	
	$city1="select * from city_master where state_id='".$city_row['state_id']."'";
	$city_res1=mysql_query($city1)or die(mysql_error());
	
	
	while($city_row1=mysql_fetch_assoc($city_res1))
	{
	$arr2[]=$city_row1['city_name'];
	}
	
	//echo $state_row1;
	//print_r($arr2);
	$st=implode(",",$arr2);
	echo $st;
}
?>



  