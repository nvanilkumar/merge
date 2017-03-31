<?php
session_start();

include_once("../MT/cGlobali.php"); 
include_once("../MT/cStates.php");	
include 'cookie.php';
$Globali = new cGlobali();

include_once '../includes/functions.php';
$commonFunctions=new functions();
$_REQUEST=$commonFunctions->stripData($_REQUEST);
$_GET=$commonFunctions->stripData($_GET);

/* dandiya Category Id */

$CaId=" and CategoryId=1 ";
$SubCat=' and subcategoryid=8 ';


$categoryname=$_GET['categoryname'];








//AND (StartDt >= now() OR EndDt > now()) AND NoTck = 0 and now() < ADDDATE(StartDt, INTERVAL 5 DAY)
$dates="   AND Private = 0 AND Published = 1   order by IsFamous desc,StartDt asc";
$dates2=" AND Private = 0 AND Published = 1 AND NoTck = 0 ";	






/* Search Condition */

if($_GET['SearchText']!="" && $_GET['SearchText']!="Search with Event Name or Event ID")
{
  if(is_numeric($_REQUEST['SearchText']))
  {
  $SearchQuery .= " AND Id='".$Globali->dbconn->real_escape_string($_REQUEST['SearchText'])."'";
  }
  else
  {
	  
  	  $SearchTerm=$_REQUEST['SearchText'];
      $SearchQuery .= " AND (Title Like '%".$Globali->dbconn->real_escape_string($SearchTerm)."%' or Description Like '%".$Globali->dbconn->real_escape_string($SearchTerm)."%') "; 

	 if($SearchTerm != "Search with Event Name or Event ID" && $SearchTerm != "")
	 {
		$CitiesResult=$Globali->SelectQuery("select Id from Cities where City like '%".$Globali->dbconn->real_escape_string($SearchTerm)."%'");
		if(count($CitiesResult)>0)
		{
			$cityIdsStr=NULL;
			foreach($CitiesResult as $cityIds)
			{
				//var_dump($cityIds);
				$cityIdsStr.=$cityIds['Id'].",";
			}
			
			if(strlen($cityIdsStr)>0){
				$cityIdsStr=substr($cityIdsStr,0,-1);
				$SearchQuery .= " AND CityId in (".$Globali->dbconn->real_escape_string($cityIdsStr).")";
			}
		}
	
		$StatesResult=$Globali->SelectQuery("select Id from States  where State like '%".$Globali->dbconn->real_escape_string($SearchTerm)."%'");
		$sqlst=$StatesResult->fetch_array;
		if($sqlst['Id']!=""){
		$SearchQuery .= " AND StateId='".$Globali->dbconn->real_escape_string($sqlst['Id'])."'";
		}
	
	 }
  }

}  


$rrp = 13;
if(isset($_GET['page']) && strlen($_GET['page'])>0){
$page = $_GET['page'];
} else {
$page = 1;
}

//ROWS PER PAGE & OFFSET

$offset = ($page)*$rrp-$page;	

$max = ' limit '. $offset.','. $rrp; 






if($city=="all cities" || $city=="")
{
$conCity="";
}else if($city=="NewDelhi")
{
$conCity=" AND StateId=53";
}
else if($city=="Goa")
{
$conCity=" AND StateId=11";
}
else if($city=="Hyderabad")
{
$conCity=" AND CityId in (47,448)";
}
else if($city=="Bengaluru")
{
	$sqlBangCity="select Id from `Cities` where `city` like '%bangalore%' or city like '%bengaluru%'";
	$dataBangCity=$Globali->SelectQuery($sqlBangCity);
	
	$bangCityIdStr=NULL;
	if(count($dataBangCity)>0)
	{
		foreach($dataBangCity as $bangKey=>$bangCityId)
		{
			$bangCityIdStr.=$bangCityId['Id'].",";
		}
		$bangCityIdStr=substr($bangCityIdStr,0,-1);
		$conCity=" AND CityId in ($bangCityIdStr) ";
	}
	

}






if(!empty($conCity))
{
	$citysubstr=substr($conCity,0,4);
	$citysubstror=substr($conCity,4);
	
	
	$conCity=' '.$citysubstr.' (('.$citysubstror.') or IsWebinar=1 )';	
}


	



/****************** query for most popular events  ends here************************************************/



  $SearchCurrentQuery ="SELECT  StateId,CityId,Title,URL,Logo,UserID,CategoryId,StartDt,Venue FROM events  WHERE 1  and enddt > now() and (StartDt > ADDDATE(now(), INTERVAL -10 DAY)) AND NoTck = 0 $conCity $CaId $SubCat  $SearchQuery $SearchQuery3 $Paid  $dates $max";
  
  //if($_SERVER['HTTP_X_FORWARDED_FOR']=='183.82.4.87'){  echo $SearchCurrentQuery; }
  
 //echo $param."<br>".$filterEventsQuery;

//echo $max."<br>";


$SearchCurrentResult = $Globali->SelectQuery($SearchCurrentQuery);
 

 $TotRes=count($SearchCurrentResult);

           
if($TotRes > 0)
		{
			
		$k=0;
		$count=$TotRes;
		if($TotRes>12)
		$count=$TotRes-1;
		
		  	for($i=0; $i<$count; $i++)
			{
				$Title = $SearchCurrentResult[$i]['Title'];
				$Title=str_replace('&','and',$Title);
				$Title = str_replace("'",'',$Title);
				$Title = str_replace("/", "or",$Title);
				$Title = str_replace(":", "",$Title);
				$Title = str_replace(";", "",$Title);
				$Title = str_replace("*", "",$Title);
				$Title = str_replace('"','',$Title);
				$Title = str_replace("®","",$Title);
				$URL = $list_row['URL'];			
				$event_link= $SearchCurrentResult[$i]['URL']; 
				$k++;  
				if($k==3){ $last="last"; $k=0;} else { $last=""; }       
			?>
             <div class="photo-item type">
            <figure> <a href="<?=_HTTP_SITE_ROOT?>/event/<?=$event_link?>"  target="_blank">  
				<?php
				//if($_SERVER['HTTP_X_FORWARDED_FOR']=='183.82.4.87'){ echo $SearchCurrentResult[$i]['Logo']; }
				
				 if(($SearchCurrentResult[$i]['Logo'] =='eventlogo/') || ($SearchCurrentResult[$i]['Logo'] ==''))
                {   
               $sql_logo="select CLogo from organizer where UserId='".$Globali->dbconn->real_escape_string($SearchCurrentResult[$i]['UserID'])."'";
			   $sel_logo = $Globali->SelectQuery($sql_logo);
                if($sel_logo[0]['CLogo']!="" && $sel_logo[0]['CLogo']!="logo/"){
                ?>
				
                <img src="<?=_HTTP_CDN_ROOT?>/<?=$sel_logo[0]['CLogo']?>"  class="img-latest-event" alt="<?php echo strip_tags(stripslashes($SearchCurrentResult[$i]['Title']));?>" title="<?php echo strip_tags(stripslashes($SearchCurrentResult[$i]['Title']));?>" />
                
                 <? } else { 
				 
				 ?>
                 
				<img  src="<?=_HTTP_CDN_ROOT?>/images/eventlogo.jpg"  class="img-latest-event"  alt="<?php echo strip_tags(stripslashes($SearchCurrentResult[$i]['Title']));?>" title="<?php echo strip_tags(stripslashes($SearchCurrentResult[$i]['Title']));?>" />
                
				<?php  } } else {
				// need  _t image here -Ph
				$resizedImage=$commonFunctions->getResizedImagepath($SearchCurrentResult[$i]['Logo']);
				//if($_SERVER['HTTP_X_FORWARDED_FOR']=='183.82.4.87'){ echo $resizedImage; }
				//$thumburl = preg_replace('/([^\.]*).([^|S]*)/', '$1_t.$2', $SearchCurrentResult[$i]['Logo']);
				
				?>
                <img src="<?=_HTTP_CDN_ROOT?>/<?=$resizedImage;?>"  class="img-latest-event"   alt="<?php echo strip_tags(stripslashes($SearchCurrentResult[$i]['Title']));?>" title="<?php echo strip_tags(stripslashes($SearchCurrentResult[$i]['Title']));?>"  />
                <?php
				
				 }  ?>
          </a> </figure>
            <div class="fig-caption"> <a href="<?=_HTTP_SITE_ROOT?>/event/<?=$event_link?>" data-rel="prettyPhoto"  target="_blank">
			<?php if(strlen($SearchCurrentResult[$i]['Title'])>100){ echo substr(stripslashes($SearchCurrentResult[$i]['Title']),0,100).".."; }else{ echo $SearchCurrentResult[$i]['Title']; } ?>
               <p>@ <?php echo substr(stripslashes($SearchCurrentResult[$i]['Venue']),0,60); ?></p></a> </div>
          </div>
            

          
        
    
		<?php }  
 
		} //ends count condition
		else
		{
			
			echo '<div class="row Errordiv-holder">
                    <div class="col-lg-12">
                         <p  class="ErrorResults">Sorry, We did not find any events. <a href="'._HTTP_SITE_ROOT.'/dandiya/All">Click Here</a> to load all Events.</p>
                    </div>
                </div>';
				
		}//else condition to display default value.
		
		
	
		 if($TotRes > 12)
		{ 
		 
		?>
			<div class="row" id="load_more_div">
            <div class="col-lg-12" onClick="load_more('<?php echo ($page+1);?>','all','newfil');">
              <p  class="LoadMoreEvents">Load More Events...</p>
            </div>
       </div>
		<?php
		}
		?>

     
