<?php //error_reporting(-1);
//echo _HTTP_CF_ROOT;?>
<!DOCTYPE html>
<!--[if IE 8]> <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--><html class="no-js"><!--<![endif]-->
<head>
<meta property="og:title" content="<?php echo $pageTitle;?>" />
<meta property="og:type" content="website" data-page-subject="true"  />
<meta property="og:image" content="<?=_HTTP_CF_ROOT;?>images/NYE-Side-Banner-2015.png" />
<meta charset="utf-8">
<title><?php echo $pageTitle;?></title>
<meta name="description"  http-equiv="description" content="<?php echo $metaDescription; ?>">
<meta name="keywords" http-equiv="keywords" content="<?php echo $metaKeywords; ?>">
<meta content="yes" name="apple-mobile-web-app-capable" />
<meta name="viewport" content="minimum-scale=1.0, width=device-width, maximum-scale=1, user-scalable=no" />
<link href='http://fonts.googleapis.com/css?family=Roboto+Condensed:300,400,700' rel='stylesheet' type='text/css'>
<link rel="stylesheet" type="text/css" href="<?=_HTTP_CF_ROOT;?>newyear/assets/css/bootstrap.min.min.css.gz">
<link rel="stylesheet" href="<?=_HTTP_CF_ROOT;?>newyear/assets/css/font-awesome.min.min.css.gz">
<link rel="stylesheet" type="text/css" href="<?=_HTTP_CF_ROOT;?>newyear/assets/css/flexslider.min.css.gz">
<link rel="stylesheet" type="text/css" href="<?=_HTTP_CF_ROOT;?>newyear/assets/css/jquery.vegas.min.css.gz">
<link rel="stylesheet" href="<?=_HTTP_CF_ROOT;?>newyear/assets/css/main.min.css.gz">
<script src="<?=_HTTP_CF_ROOT;?>newyear/assets/js/modernizr-2.6.2-respond-1.1.0.min.min.js.gz"></script>
<!--Files for Ticker-->
<link rel="stylesheet" type="text/css" href="<?=_HTTP_CF_ROOT;?>newyear/assets/css/BreakingNews.min.css.gz"/>
<link rel="stylesheet" href="<?=_HTTP_CF_ROOT;?>newyear/assets/css/jquery-ui.min.css.gz">

<style>
 .ui-autocomplete > li > a.ui-state-hover {width:100%;float:left;background:#dadada !important;}
 .ui-autocomplete a {
width: 100%;
float: left;
}
</style>


<script src="<?=_HTTP_CF_ROOT;?>js/jquery.1.7.2.min.min.js.gz"></script> 
<script src="<?=_HTTP_CF_ROOT;?>newyear/assets/js/BreakingNews.min.js.gz"></script><!--Files for Ticker-->

<script src="<?=_HTTP_CF_ROOT;?>newyear/assets/js/jquery-ui.min.js.gz"></script>
<script type="text/javascript" language="javascript">

var page=1;
function load_more(val,fromsearch){       //fromsearch parameter is set to 1 when coming from ADVANCESEARCH else 0


	if(fromsearch==0)
	{
		$("#galleryBox").html('<div class="row" id="load_more_div"><div class="col-lg-12"><p  class="LoadMoreEvents"><img src="<?=_HTTP_CF_ROOT;?>newyear/assets/img/ajax-loader.gif" /> &nbsp;&nbsp;Loading events, please wait... </p></div></div>');
	}
	else
	{
		$("#load_more_div").html("<div class='col-lg-12'><p   class='LoadMoreEvents'><img src='<?=_HTTP_CF_ROOT;?>newyear/assets/img/ajax-loader.gif' /> &nbsp;&nbsp;Loading events, please wait... </p></div>");
	}


var categoryname='Dandiya';
page=val;
if(val==0)
val='';


var SearchText= $('#SearchText').val();
var Stags=$('#Stags').val();
var Kids=$('#Kids').val();
var Couples=$('#Couples').val();
/*
var Seating=$('#Seating').val();
var Parking=$('#Parking').val();
*/
var par="";
par+="&SearchText="+SearchText;



if($('#Kids').is(":checked"))
{
par+="&Kids=1";
}
if($('#Stags').is(":checked"))
{
par+="&Stags=1";
}


if($('#Couples').is(":checked"))
{
par+="&Couples=1";
}

if($('#stagsandcouples').is(":checked"))
{
par+="&SaC=1";
}

if($('#incfb').is(":checked"))
{
par+="&incfb=1";
}

if($('#incstay').is(":checked"))
{
par+="&incstay=1";
}



//par+="&param="+param;

par+="&categoryname="+categoryname;	


if(fromsearch==1)
page++;

//alert("<?php //echo _HTTP_SITE_ROOT; ?>/newyear/load-grid_list.php?AX=Yes&search=<?=$_GET['search'];?>&page="+page+par);

var datas="AX=Yes&search=<?=$_GET['search'];?>&cityname=<?=$cookie_city?>&page="+page+par;
$.ajax({
		url: "<?php echo _HTTP_SITE_ROOT; ?>dandiya/load-grid_list.php",
		type: "GET",
		data: datas,
		beforeSend:function (data) {
			
			
		},
		success: function (data) {
			
			
			$("#next").hide();
			
			$("#next"+(page-1)).hide();
			
			if(fromsearch==0)
			{
				$("#load_more_div").remove();
				$("#galleryBox").html(data); 
			}
			else
			{
				$("#load_more_div").remove();
				$("#galleryBox").append(data); 
			}

			
		}
	});



}



function lookup(inputString) {
		if(inputString.length == 0) {
			// Hide the suggestion box.
			$('#suggestions').hide();
		} else { 
			$.post("<?php echo _HTTP_SITE_ROOT; ?>dandiya/get_list.php", {queryString: ""+inputString+""}, function(data){
				if(data.length >0) {
					$('#suggestions').show();
					$('#autoSuggestionsList').html(data);
                                  
				}
			});
		}
} // lookup
	
function fill(thisValue) {
            
	$('#SearchText').val(thisValue);
	setTimeout("$('#suggestions').hide();", 200); 
    var val= $('#SearchText').val();   
    if(val!="")         
    setTimeout("document.getElementById('event-check').submit()",200);           
}
  
function formsubmit()
{
	var value=encodeURI($('#SearchText').val());
	$('#SearchTextvalue').val(value);
	document.getElementById('event-check').submit();
}


</script>

<?php
if (strtolower($_SERVER['HTTP_HOST'])=='stage.meraevents.com' || strtolower($_SERVER['HTTP_HOST'])=='localhost')
{}
else
{
	?>
	<script>(function() {
    var _fbq = window._fbq || (window._fbq = []);
    if (!_fbq.loaded) {
    var fbds = document.createElement('script');
    fbds.async = true;
    fbds.src = '//connect.facebook.net/en_US/fbds.js';
    var s = document.getElementsByTagName('script')[0];
    s.parentNode.insertBefore(fbds, s);
    _fbq.loaded = true;
    }
    _fbq.push(['addPixelId', '1463712837251114']);
    })();
    window._fbq = window._fbq || [];
    window._fbq.push(['track', 'PixelInitialized', {}]);
    </script>
    <noscript><img height="1" width="1" alt="" style="display:none" src="https://www.facebook.com/tr?id=1463712837251114&amp;ev=PixelInitialized" /></noscript>
    <?php
}
?>


</head>
<body >
<!--=====  Header  ====-->
<header>
  <div class="social-links">
    <div class="container">
      <div class="col-md-6 col-sm-6"><!--<div class="supporttext"> For Support Call : 07306616664 </div>--></div>
      <div class="col-md-6 col-sm-6">
        <ul class="social">
            <li><a href="http://www.facebook.com/meraevents" target="_blank"><span class="icon-facebook"></span></a></li><li><a href="http://twitter.com/#!/meraevents_com" target="_blank"><span class="icon-twitter"></span></a></li><li><a href="http://pinterest.com/meraevents/" target="_blank"><span class="icon-pinterest"></span></a></li><li><a href="https://plus.google.com/114189418356737609354/about" target="_blank"><span class="icon-google-plus"></span></a></li>
        </ul>
      </div>
    </div>
  </div>
  <nav class="navbar yamm navbar-default">
    <div class="container">
      <div class="navbar-header">
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse"> <i class="icon-bar"></i> <i class="icon-bar"></i> <i class="icon-bar"></i> </button>
        <a class="navbar-brand" href="<?php echo _HTTP_SITE_ROOT; ?>dandiya/All"><div class="logo">  <img src="<?php echo $this->config->item('images_static_path');?>me-logo.svg" /></div></a> </div>
      <div class="navbar-collapse collapse">
        <ul class="nav navbar-nav">
          <li <?php if($city=="All Cities") { echo 'class="active"'; } ?>><a href="<?php echo _HTTP_SITE_ROOT; ?>dandiya/All">All Cities</a> </li>
          <li <?php if($city=="Hyderabad") { echo 'class="active"'; } ?>><a href="<?php echo _HTTP_SITE_ROOT; ?>dandiya/Hyderabad" >Hyderabad</a></li>
          <li <?php if($city=="Bengaluru") { echo 'class="active"'; } ?>><a href="<?php echo _HTTP_SITE_ROOT; ?>dandiya/Bengaluru">Bengaluru</a></li>
          <li <?php if($city=="Chennai") { echo 'class="active"'; } ?>><a href="<?php echo _HTTP_SITE_ROOT; ?>dandiya/Chennai">Chennai</a></li>
          <li <?php if($city=="NewDelhi") { echo 'class="active"'; } ?>><a href="<?php echo _HTTP_SITE_ROOT; ?>dandiya/NewDelhi">Delhi/NCR</a></li>
          <li <?php if($city=="Mumbai") { echo 'class="active"'; } ?>><a href="<?php echo _HTTP_SITE_ROOT; ?>dandiya/Mumbai">Mumbai</a></li>
          <li <?php if($city=="Pune") { echo 'class="active"'; } ?>><a href="<?php echo _HTTP_SITE_ROOT; ?>dandiya/Pune">Pune</a></li>
          <li <?php if($city=="Goa") { echo 'class="active"'; } ?>><a href="<?php echo _HTTP_SITE_ROOT; ?>dandiya/Goa">Goa</a></li>
          <li <?php if($city=="Other") { echo 'class="active"'; } ?>><a href="<?php echo _HTTP_SITE_ROOT; ?>dandiya/Other">Other Cities</a></li>
        </ul>
      </div>
      <!--/.nav-collapse --> 
    </div>
  </nav>
</header>
<!--=====  Home =====-->
<section id="home-slider">
  <div class="container">
    <div class="home-inner">
      <div class="slider-nav"> <a id="home-prev" href="#" class="prev icon-chevron-left"></a> <a id="home-next" href="#" class="next icon-chevron-right"></a> </div>
      <div id="flex-home" class="flexslider">
        <ul class="slides">
        <?php
		for($i = 0 ; $i < count($sql_sp_offer); $i++)
        {
			?><li> <a href="<?=$sql_sp_offer[$i]['URL'];?>" target="_blank"><img src="<?php echo _HTTP_CDN_ROOT.$sql_sp_offer[$i]['FileName']; ?>" alt="<?=$sql_sp_offer[$i]['Title'];?>" title="<?=$sql_sp_offer[$i]['Title'];?>" ></a></li><?php
		}
		?>
        </ul>
      </div>
    </div>
  </div>
</section>
<!--=====  JPlayer (Audio Player) =====--> 
<!--=====  Latest Albumbs ====-->
<section id="latest-events">
  <div class="container">
    <div class="row">
      <div class="col-lg-12 col-md-12 col-sm-12">
        <div class="nav-search">
        <form method="post" action=""  name="event-check" id="event-check">
        	<input type="text" id="SearchText" name="SearchText" autocomplete="off"   placeholder="Search with Event Name or Event ID" />
            <button  onClick="formsubmit();"><i class="icon-search"></i></button>  
        </form>
        </div>
        <div class="suggestionsBox" id="suggestions" style="display: none;">
				<div class="suggestionList" id="autoSuggestionsList">
					&nbsp;
				</div>
			</div>
        
      </div>
    </div>
  </div>
</section>
<div class="clearfix"></div>
<section id="latest-newsticker">
  <!--<div class="container">
    <div class="row">
      <div class="col-lg-12 col-md-12 col-sm-12">
        <div class="BreakingNewsController easing" id="breakingnews2">
          <div class="bn-title"></div>
          
          
          
          <ul>
          	<?php
			foreach($nye_discounts as $nye_discount_details)
			{
				?><li><a  target="_blank" href="<?php //echo _HTTP_SITE_ROOT.'/event/'.$nye_discount_details['URL']; ?>"><?php echo stripslashes($nye_discount_details['title']); ?> <span class="disc-code-bg"><?php echo stripslashes($nye_discount_details['promo_code']); ?></span></a></li><?php
			}
			?>
            
          </ul>
          <div class="bn-arrows"><span class="bn-arrows-left"></span><span class="bn-arrows-right"></span></div>
        </div>
      </div>
    </div>
  </div>-->
</section>
<!--=====  Upcoming events ======--> 
<!--======  Latest blog  =====-->
<section id="gallery">
  <div class="container">
    <div class="row">
      <div class="photo-filter">
        <div class="container"><!-- <h1 class="MainText">Feet thumping music</h1><br> -->
          <?php
		  //if($city=='All'){$city='All Cities';}
		  if($city=='Other'){$city='Other Cities';}
		  elseif($city=='NewDelhi'){$city='Delhi/NCR';}
		  elseif($city=='All Cities'){$city='India';}
		  ?>
          <h1>Dandiya Events in <?php echo $city; ?> 2015 </h1><br>
         <!--<h5>Search Filter:</h5>
         <ul>
            <li>
              <input id="all" class="css-checkbox" type="checkbox" onClick="load_more(0,0,'newfil');" />
              <label for="all" name="all" class="css-label">All</label>
            </li>
            <li>
              <input id="Couples" class="css-checkbox" type="checkbox" onClick="load_more(0,0,'newfil');" />
              <label for="Couples" name="Couples" class="css-label" >Couples only</label>
            </li>
            <li>
              <input id="Stags" class="css-checkbox" type="checkbox" onClick="load_more(0,0,'newfil');" />
              <label for="Stags" name="Stags" class="css-label">Stag</label>
            </li>
            <li>
              <input id="stagsandcouples" class="css-checkbox" type="checkbox"  onClick="load_more(0,0,'newfil');" />
              <label for="stagsandcouples" name="stagsandcouples" class="css-label">Stag accompanied with couple only</label>
            </li>
            <li>
              <input id="incfb" class="css-checkbox" type="checkbox"  onClick="load_more(0,0,'newfil');"/>
              <label for="incfb" name="incfb" class="css-label" >Inclusive of F&B </label>
            </li>
            <li>
              <input id="incstay" class="css-checkbox" type="checkbox"  onClick="load_more(0,0,'newfil');" />
              <label for="incstay" name="incstay" class="css-label">Inclusive of Stay</label>
            </li>
             <li>
              <input id="Kids" class="css-checkbox" type="checkbox"  onClick="load_more(0,0,'newfil');" />
              <label for="Kids" name="Kids" class="css-label" >Kidz Zone</label>
            </li>
            <li>&nbsp;</li>
        </ul>-->
          
         
        </div>
      </div>
      <!--// filter-->
      <div class="container">
        <div class="" id="galleryBox"> 
          <!-- http://www.meraevents.com/event/reincarnation-2014 -->
          
          <?php
			if($TotRes > 0)
			{
				$k=0;
			

				//Subtracting the extra count added for checking if view more option is to be displayed			
				$count=$TotRes;			
				if($TotRes>12)
				$count=$TotRes-1;
				
				$typecount=0;
			
				for($i=0; $i<$count; $i++)
				{
					$typecount+=1;
					
				//	$State = $Globali->GetSingleFieldValue("select State from States where Id='".$SearchCurrentResult[$i]['StateId']."'");
				
				//	$City = $Globali->GetSingleFieldValue("select City from Cities where Id='".$SearchCurrentResult[$i]['CityId']."'");
					
					
					
					$Title = $SearchCurrentResult[$i]['Title'];
						$Title=str_replace('&','and',$Title);
						 $Title = str_replace("'",'',$Title);
						 $Title = str_replace("/", "or",$Title);
						 $Title = str_replace(":", "",$Title);
						 $Title = str_replace(";", "",$Title);
						 $Title = str_replace("*", "",$Title);
						 $Title = str_replace('"','',$Title);
						 $Title = str_replace("Ã‚Â®","",$Title);
					 
					$URL = $list_row['URL'];
					
			//		$event_link = str_replace(' ','-',$State).'/';
			//		$event_link.= str_replace(' ','-',$City).'/';
					
								
					$event_link= $SearchCurrentResult[$i]['URL']; 
								$k++;  
								if($k==3){ $last="last"; $k=0;} else { $last=""; }       
					
				?>
                <div class="photo-item type">
            <figure> <a href="<?=_HTTP_SITE_ROOT?>event/<?=$event_link?>"  target="_blank">
                <?php
							
				
				if(($SearchCurrentResult[$i]['Logo'] =='eventlogo/')||($SearchCurrentResult[$i]['Logo'] ==''))
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
                <?php
				
				  }
							?>
                            </a> </figure>
            <div class="fig-caption"> <a href="<?=_HTTP_SITE_ROOT?>event/<?=$event_link?>" data-rel="prettyPhoto"  target="_blank">
			<?php if(strlen($SearchCurrentResult[$i]['Title'])>100){ echo substr(stripslashes($SearchCurrentResult[$i]['Title']),0,100).".."; }else{ echo $SearchCurrentResult[$i]['Title']; } ?>
              <p>@ <?php echo substr(stripslashes($SearchCurrentResult[$i]['Venue']),0,60); ?></p></a> </div>
          </div>
                            <?php
				
	
				}  
			
			}
			else {
				?>
                <div class="row Errordiv-holder">
                    <div class="col-lg-12">
                         <p  class="ErrorResults">Sorry, We did not find any events. <a href="<?php echo _HTTP_SITE_ROOT; ?>dandiya/All">Click Here</a> to load all Events.</p>
                    </div>
                </div>
                <?php 
		 	}
		 ?>
          
          
          
          
        
        
        
        <?php
        if($TotRes > 12){
			?>
        <div class="row" id="load_more_div">
            <div class="col-lg-12" onClick="load_more('<?=$page;?>','all');">
              <p  class="LoadMoreEvents">Load More Events...</p>
            </div>
       </div>
       <?php
		}
		?>
        
        </div>
       
       
       
        
      </div>
      <!--//photo gallery--> 
    </div>
    <!--row--> 
  </div>
  <!--//container--> 
</section>
<!--========  Footer ======-->
<footer>
  <div class="container">
    <div class="row">
      <div class="col-lg-8 col-md-8 col-sm-8">
        <h4><span class="compass-icon"></span>Browse Events</h4>
        <div class="row"> 
           <ul class="sitemap">
            <li><a href="All">Dandiya Events 2015 All India</a></li>
            <li><a href="Hyderabad">Dandiya Events 2015 Hyderabad</a></li>
            <li><a href="Bengaluru">Dandiya Events 2015 Bengaluru</a></li>
            <li><a href="Chennai">Dandiya Events 2015 Chennai</a></li>
            <li><a href="NewDelhi">Dandiya Events 2015 New Delhi/NCR</a></li>
            <li><a href="Mumbai">Dandiya Events 2015 Mumbai</a></li>
            <li><a href="Pune">Dandiya Events 2015 Pune</a></li>
            <li><a href="Goa">Dandiya Events 2015 Goa</a></li>
            <li><a href="Other">Dandiya Events 2015 Other Cities</a></li>
          </ul><!-- </div> -->           
        </div>
      </div>
      
      <div class="col-lg-4 col-md-4 col-sm-4">
        <h4><span class="letter-icon"></span>Contact US</h4><!-- newsletter signup -->
        <div class="contactus-text"><p>For any Queries Mail us : <a href="mailto:support@meraevents.com">support@meraevents.com</a></p>
          <!--<br><p>For Support Call : <br><span>07306616664</span></p>--></div>
      </div><!--\\column-->      
    </div><!--\\row-->
    <div class="row"><div class="col-lg-12"><p class="copyright">&copy; <?php echo date("Y"); ?> Versant Online Solutions Pvt Ltd, All Rights Reserved</p></div></div>
  </div><!--\\container--> 
</footer>
<!--======  Script Source ======-->  
<script src="<?=_HTTP_CF_ROOT;?>newyear/assets/js/BreakingNews.min.js.gz"></script> 
<script src="<?=_HTTP_CF_ROOT;?>newyear/assets/js/jquery.mousewheel.min.min.js.gz"></script> 
<script src="<?=_HTTP_CF_ROOT;?>newyear/assets/js/jquery.easing-1.3.pack.min.js.gz"></script> 
<script src="<?=_HTTP_CF_ROOT;?>newyear/assets/js/jquery.flexslider-min.min.js.gz"></script> 
<script src="<?=_HTTP_CF_ROOT;?>newyear/assets/js/jquery.carouFredSel-6.2.1-packed.min.js.gz"></script> 
<!-- ########### Do not change the Order ########### --> 
<!--<script  src="<?php echo $microsite_path;?>/assets/js/isotope.js"></script> --> 
<script src="<?=_HTTP_CF_ROOT;?>newyear/assets/js/jquery.vegas.min.min.js.gz"></script>
<script src="<?=_HTTP_CF_ROOT;?>newyear/assets/js/main.min.js.gz"></script> 

<!--<script src="<?=_HTTP_CF_ROOT;?>js/jquery-ui.min.js.gz"></script>-->



<!--Google Analytics Code Below--> 
<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-41640740-1', 'meraevents.com');
 // ga('require', 'displayfeatures');    //ET Media Lab tracker
 // ga('require', 'linkid', 'linkid.js');//ET Media Lab tracker
  ga('send', 'pageview');

</script> 
<script>
  /*$.noConflict();*/
    $(document).ready(function(){
		$("#SearchText").autocomplete({
        	source: function(request, response) {
            $.ajax({
                url: '<?php echo _HTTP_SITE_ROOT; ?>dandiya/get_list.php',
                type: "POST",
                dataType: "json",
                data: { queryString: request.term },
                success: function(data) {
                    response(data);
					}
				});
			},
			select: function(event, ui) {
				$(this).val(ui.item.Id);
				setTimeout("document.getElementById('event-check').submit()",200);
				return false;
			},
			selectFirst: true,
			autoFocus: true
       });
	
	
	
       $("#breakingnews2").BreakingNews({
        background    :'#ddd',
        title     :'OFFERS & DISCOUNTS',
        titlecolor    :'#FFF',
        titlebgcolor  :'#e62948',//099
        linkcolor   :'#333',//c1213c
        linkhovercolor  :'#e62948',//099
        fonttextsize  :16,
        isbold      :false,
        border      :'solid 1px #333',
        width     :'99%',
  //height :  '26px',
  timer     :5000,
        autoplay    :true,
        effect      :'slide'
      });
    }); 
</script>

<!-- Google Code for Remarketing Tag --> 
<script type="text/javascript">
/* <![CDATA[ */
var google_conversion_id = 985224900;
var google_custom_params = window.google_tag_params;
var google_remarketing_only = true;
/* ]]> */
</script>
<script type="text/javascript" src="//www.googleadservices.com/pagead/conversion.js"></script>
<noscript>
<div style="display:inline;">
<img height="1" width="1" style="border-style:none;" alt="" src="//googleads.g.doubleclick.net/pagead/viewthroughconversion/985224900/?value=0&amp;guid=ON&amp;script=0"/>
</div>
</noscript>

<?php
if($city=="Hyderabad")
{
	?>
	<script type="text/javascript">
       var clevertap = {event:[], profile:[], account:[]};
       clevertap.account.push({"id": "ZW9-8K4-ZZ4Z"});
       (function () {
           var wzrk = document.createElement('script');
           wzrk.type = 'text/javascript';
           wzrk.async = true;
           wzrk.src = ('https:' == document.location.protocol ? 'https://d2r1yp2w7bby2u.cloudfront.net' : 'http://static.clevertap.com') + '/js/a.js';
           var s = document.getElementsByTagName('script')[0];
           s.parentNode.insertBefore(wzrk, s);
       })();
    </script>
    <?php
}
?>


</body>
</html>