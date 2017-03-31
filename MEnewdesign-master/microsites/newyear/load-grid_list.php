<?php
session_start();

include_once("../MT/cGlobali.php"); 
//include_once("../MT/cStates.php");	
include 'cookie.php';
$Globali = new cGlobali();

include_once '../includes/functions.php';
$commonFunctions=new functions();
$_REQUEST=$commonFunctions->stripData($_REQUEST);
$_GET=$commonFunctions->stripData($_GET);

/* NewYear Category Id */
$CaId=" and CategoryId=8 ";

$categoryname=$_GET['categoryname'];

if(isset($_GET['param']))
{
$param=$_GET['param'];
}

$newfil="";
if($param == "newfil")
{
	$newfil.= "and (";
	if(isset($_GET['Kids']))
	{
	if($newfil== "and (")
	$newfil.= " n.Kids=1";
	else
	$newfil.= " or n.Kids=1 ";
	}
	if(isset($_GET['Stags']))
	{
	if($newfil== "and (")
	$newfil.= " n.Stags=1";
	else
	$newfil.= " or n.Stags=1 ";
	}
	if(isset($_GET['Couples']))
	{
	if($newfil== "and (")
	$newfil.= " n.Couples=1";
	else
	$newfil.= " or n.Couples=1 ";
	}
	
	
	if(isset($_GET['SaC']))
	{
	if($newfil== "and (")
	$newfil.= " n.stagsandcouples=1";
	else
	$newfil.= " or n.stagsandcouples=1 ";
	}
	
	if(isset($_GET['incfb']))
	{
	if($newfil== "and (")
	$newfil.= " n.incfb=1";
	else
	$newfil.= " or n.incfb=1 ";
	}
	
	if(isset($_GET['incstay']))
	{
	if($newfil== "and (")
	$newfil.= " n.incstay=1";
	else
	$newfil.= " or n.incstay=1 ";
	}
	
	
	if(isset($_GET['Seating']))
	{
	if($newfil== "and (")
	$newfil.= " n.Seating=1";
	else
	$newfil.= " or n.Seating=1 ";
	}
	
	if(isset($_GET['Parking']))
	{
	if($newfil== "and (")
	$newfil.= " n.Parking=1";
	else
	$newfil.= " or n.Parking=1 ";
	}
	$newfil.= ")";
	
}



if($newfil=="and ()")
{
	$newfil="";
	$newfilJoin="";
}
else
{
	$newfilJoin=" LEFT JOIN newyear_filters n ON e.id=n.EventId ";
}


//AND (StartDt >= now() OR EndDt > now()) AND NoTck = 0 and now() < ADDDATE(StartDt, INTERVAL 5 DAY)
$dates="   AND Private = 0 AND Published = 1  AND NoTck = 0  order by IsFamous desc,StartDt asc";
$dates2=" AND Private = 0 AND Published = 1 AND NoTck = 0 ";	




if(isset($_GET['page']) && strlen($_GET['page'])>0){
$page = $_GET['page'];
} else {
$page = 1;
}

//ROWS PER PAGE & OFFSET
$rrp = 25;
$offset = ($page*$rrp)-$page;	

$max = ' limit '. $offset.','. $rrp; 




if($city=="All Cities" || $city=="")
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
	$sqlBangCity="select Id from `Cities` where `city` in ('bangalore','bengaluru') and status=1 ";
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
	
	 if($param == "popular")
 {
	 $citysubstror=str_replace('CityId','e.CityId',$citysubstror);
	 $citysubstror=str_replace('StateId','e.StateId',$citysubstror);
	 $conCity=' '.$citysubstr.' (('.$citysubstror.') or IsWebinar=1 )';
 }
 else
$conCity=' '.$citysubstr.' (('.$citysubstror.') or IsWebinar=1 )';	
}


	


/****************** query for most popular events  ends here************************************************/



 $filterEventsQuery = "Select e.Id,e.StateId,e.CityId,e.Title,e.URL,e.Logo,e.UserID,e.CategoryId,e.StartDt,e.Venue from events e $newfilJoin  WHERE  (e.StartDt > ADDDATE(now(), INTERVAL -10 DAY))   $newfil $conCity $CaId $SubCat  $SearchQuery $SearchQuery3 $Paid  $dates $max";

  $SearchCurrentQuery ="SELECT  StateId,CityId,Title,URL,Logo,UserID,CategoryId,StartDt,Venue FROM events  WHERE 1  $conCity $conCat $SubCat  $SearchQuery $SearchQuery3 $Paid  $dates $max";
  
  //if($_SERVER['HTTP_X_FORWARDED_FOR']=='183.82.4.87'){ echo $SearchCurrentQuery; }

  
 //echo $param."<br>".$filterEventsQuery;

//echo $max."<br>";

 if($param == "popular")
 {
	 //echo $popularEventsQuery;
     $SearchCurrentResult=$Globali->SelectQuery($popularEventsQuery);
 } 
 if($param == "newfil"){
	 //echo $filterEventsQuery; 
	 $SearchCurrentResult=$Globali->SelectQuery($filterEventsQuery); 
 }
 

 $TotRes=count($SearchCurrentResult);

           
if($TotRes > 0)
		{
			
		$k=0;
		$count=$TotRes;
		if($TotRes>24)
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
				<?php if(($SearchCurrentResult[$i]['Logo'] =='eventlogo/')||($SearchCurrentResult[$i]['Logo'] ==''))
                {      
               $sql_logo="select CLogo from organizer where UserId='".$Globali->dbconn->real_escape_string($SearchCurrentResult[$i]['UserID'])."'";
			   $sel_logo = $Globali->SelectQuery($sql_logo);
                if($sel_logo[0]['CLogo']!="" && $sel_logo[0]['CLogo']!="logo/"){
                ?>
				
                <img src="<?=_HTTP_CDN_ROOT?>/<?=$sel_logo[0]['CLogo']?>"  class="img-latest-event" alt="<?php echo strip_tags(stripslashes($SearchCurrentResult[$i]['Title']));?>" title="<?php echo strip_tags(stripslashes($SearchCurrentResult[$i]['Title']));?>" />
                
                 <? } else { ?>
                 
				<img  src="<?=_HTTP_CDN_ROOT?>/images/eventlogo.jpg"  class="img-latest-event"  alt="<?php echo strip_tags(stripslashes($SearchCurrentResult[$i]['Title']));?>" title="<?php echo strip_tags(stripslashes($SearchCurrentResult[$i]['Title']));?>" />
                
				<?php  } } else {
				// need  _t image here -Ph
				$resizedImage=$commonFunctions->getResizedImagepath($SearchCurrentResult[$i]['Logo']);
				//$thumburl = preg_replace('/([^\.]*).([^|S]*)/', '$1_t.$2', $SearchCurrentResult[$i]['Logo']);
				
				
				?>
				<img src="<?=_HTTP_CDN_ROOT?>/<?=$resizedImage;?>"  class="img-latest-event"   alt="<?php echo strip_tags(stripslashes($SearchCurrentResult[$i]['Title']));?>" title="<?php echo strip_tags(stripslashes($SearchCurrentResult[$i]['Title']));?>"  />
	
       			<?php }   
							?>
          </a> </figure>
            <div class="fig-caption"> <a href="<?=_HTTP_SITE_ROOT?>/event/<?=$event_link?>" data-rel="prettyPhoto"  target="_blank">
			<?php if(strlen($SearchCurrentResult[$i]['Title'])>100){ echo substr(stripslashes($SearchCurrentResult[$i]['Title']),0,100).".."; }else{ echo $SearchCurrentResult[$i]['Title']; } ?>
               <p>@ <?php echo substr(stripslashes($SearchCurrentResult[$i]['Venue']),0,60); ?></p>
               <p><?php echo date("D, j M Y",strtotime($SearchCurrentResult[$i]['StartDt'])); ?></p>
               </a> </div>
          </div>
            

          
        
    
		<?php }  
 
		} //ends count condition
		
		
		
	
		 if($TotRes > 24)
		{ 
		 
		?>
			<div class="row" id="load_more_div">
            <div class="col-lg-12" onClick="load_more('<?php echo ($page+1);?>','India','newfil');">
              <p  class="LoadMoreEvents">Load More Events...</p>
            </div>
       </div>
		<?php
		}
		?>

     
