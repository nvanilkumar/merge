<?php
	include_once("includes/application_top.php");
	include_once("includes/functions.php");
	include('includes/logincheck.php');
	
	$aid = $_GET['aid'];
	
	// *------------------- GET ACTIVE DATE AND IMAGE OF THE ADVERTISEMENT -------------------------
/**************************commented on 17082009 need to remove afterwords**************************
	$sql_info = "SELECT files.filepath,ad_statistics.date,node.title,node.status FROM ad_image,files,ad_statistics,node WHERE node.nid = files.nid AND ad_statistics.action='active' AND ad_image.fid = files.fid AND ad_image.aid = ad_statistics.aid AND ad_statistics.aid=".$aid." ";
	$sql_ad_res = mysql_query($sql_info);
	$sql_ad_row = mysql_fetch_array($sql_ad_res);
	
	// SEPERATE year,month,day,hour FROM THE DATE STRING  
	$year = substr($sql_ad_row['date'],0,4);
	$month = substr($sql_ad_row['date'],4,2);
	$day = substr($sql_ad_row['date'],6,2);
	$hour = substr($sql_ad_row['date'],8,10);
	
	$active_str = $month."/".$day."/".$year;
	$active_str = strtotime($active_str);
	$active_since = date("F d Y ",$active_str);
	
	$file_path = $sql_ad_row['filepath'];
	$title = $sql_ad_row['title'];
	
	if($sql_ad_row['status'] == 1){
	$status = "This advertisement is actively being displayed.";
	$active_date = "This advertisement has been active since <i>".$active_since."</i>";
	}
	// *--------------------------------------------------------------------------------------------
	
	// *------------------- GET AD STATISTICS WHERE ACTION IS click----------------------------------
	$sql_stats = "SELECT * FROM ad_statistics WHERE aid=".$aid." AND action='click' ";
	$sql_ad_stats = mysql_query($sql_stats);
	
	$today_count=0;
	$this_hour_count=0;
	$last_seven_count=0;
	$this_month_count=0;
	$this_year_count=0;
	$all_count=0;
	
	while($stats_row = mysql_fetch_array($sql_ad_stats)){
	
			$all_count+= $stats_row['count'];
					
			// SEPERATE year,month,day,hour FROM THE DATE STRING  
			$year = substr($stats_row['date'],0,4);
			$month = substr($stats_row['date'],4,2);
			$day = substr($stats_row['date'],6,2);
			$hour = substr($stats_row['date'],8,10);
			
			// CHECK IF DATE IS OF LAST HOUR
			$date_str = $year.$month.$day.$hour;
			$this_hour = date("YmdH",strtotime("-1 hour"));
			if($date_str == $this_hour){
				$this_hour_count+= $stats_row['count'];
			}
			
			// CHECK IF DATE IS OF TODAY	
			$date_str = $year.$month.$day;
			$today_str = date("Ymd",strtotime("now"));
			if($date_str == $today_str){
				$today_count+= $stats_row['count']; 
			}
			
			// CHECK IF DATE IS OF LAST 7 DAYS
			$date_str = $year.$month.$day;
			$last_seven = date("Ymd",strtotime("-7 days"));
			if($date_str >= $last_seven && $date_str <= $today_str){
				$last_seven_count+= $stats_row['count'];
			}
			
			// CHECK IF DATE IS OF THIS MONTH
			$date_str = $year.$month;
			$this_month = date("Ym",strtotime("this month"));
			if($date_str == $this_month){
				$this_month_count+= $stats_row['count'];
			}
		
			// CHECK IF DATE IS OF THIS YEAR
			$date_str = $year;
			$this_year = date("Y",strtotime("this year"));
			if($date_str == $this_year){
				$this_year_count+= $stats_row['count'];
			}
		
	}// END WHILE
	
	// *------------------------------------------------------------------------------------------------------
	
	// *--------------------- GET CLICK HISTORY FOR THE ADVERTISEMENT ----------------------------------------
	
	$sql_history = "SELECT * FROM ad_clicks WHERE aid=".$aid." ";
	$sql_history_res = mysql_query($sql_history);
	
	// *------------------------------------------------------------------------------------------------------
***************************************************/
	$current_page_content = 'ad_stats.tpl.php';
	include_once(_CURRENT_TEMPLATE_DIR.'main.tpl.php');	
?>