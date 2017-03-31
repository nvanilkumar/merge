<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" http-equiv="description" content="<?php echo $pageDescription; ?>">
    <meta name="keywords" http-equiv="keywords" content="<?php echo $pageKeywords; ?>" />
    <?php if(!empty($canonicalurl)){?>
         <link rel="canonical" href="<?php echo $canonicalurl; ?>"/>  
    <?php }?>
   
     <meta name="author" content="MeraEvents" />
    <meta name="rating" content="general" /> 
	<title><?php echo $pageTitle; ?></title>
	<!-- CSS -->
    <link rel="shortcut icon" href="http://static.meraevents.com/images/static/favicon.ico">
	<link rel="stylesheet" href="<?php echo _HTTP_MICROSITE_CF_ROOT; ?>/assets/css/bootstrap.min.css.gz" />
	<link rel="stylesheet" type="text/css" href="<?php echo _HTTP_MICROSITE_CF_ROOT; ?>/assets/css/style.min.css.gz" />
    <link rel="stylesheet" href="<?php echo _HTTP_MICROSITE_CF_ROOT; ?>/assets/css/jquery-ui.min.css.gz">
    
    <style>
	.discountCodeTicker{
		display: inline-block;
		font-size: 18px;
		height: 50px !important;
		line-height: 30px !important;
		overflow: hidden;
		padding: 0;
		text-decoration: none;
	}
	</style>  
        

	<!-- CSS file for StyleSwitcher -->
	<!--<link id="link" rel="stylesheet" type="text/css" media="screen" href="assets/css/style.css">-->

	<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
   <script type="text/javascript" src="<?php echo _HTTP_MICROSITE_CF_ROOT; ?>/assets/js/jquery-1.11.1.min.js.gz"></script>
   <script src="<?php echo _HTTP_MICROSITE_CF_ROOT; ?>/assets/js/jquery-ui.min.js.gz"></script>
   
    
</head>
<body data-spy="scroll" data-target=".navbar-default" data-offset="80">
	<!-- Preloader -->


	<!-- Social Media Sidebar -->
	<div id="social-sidebar">
		<a href="http://www.facebook.com/meraevents" target="_blank" class="sbar facebook">Facebook <i class="icon-facebook"></i></a>
		<a href="https://twitter.com/meraeventsindia" target="_blank" class="sbar twitter">Twitter <i class="icon-twitter"></i></a>
		<a href="http://pinterest.com/meraevents/" target="_blank" class="sbar soundcloud">Pinterest <i class="icon-pinterest"></i></a>
		<a href="https://plus.google.com/114189418356737609354/about" target="_blank" class="sbar instagram">Google Plus <i class="icon-gplus"></i></a>
	</div>
	
	<!-- Style Switcher -->
	
	
	<!-- Fixed navbar -->
    <nav class="navbar navbar-default navbar-fixed-top" role="navigation">
      	<div class="container">
        	<div class="navbar-header">
        	  	<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
        	  	  	<span class="sr-only">Toggle navigation</span>
        	  	  	<span class="icon-bar"></span>
        	  	  	<span class="icon-bar"></span>
        	  	  	<span class="icon-bar"></span>
        	  	</button>
        	  	<a class="navbar-brand" href="<?php echo site_url(); ?>holi"><img src="http://static.meraevents.com/newyear/assets/img/meraevents-logo-y.svg" width="180"></a>
        	</div>
	        <div id="navbar" class="navbar-collapse collapse">
	          	<ul class="nav navbar-nav navbar-right" role="tablist">
                	<li <?php if(strtolower($currentCity) == 'india'){ echo 'class="active"'; } ?> ><a href="<?php echo site_url(); ?>holi">All Cities</a></li>
	          	  	<li <?php if(strtolower($currentCity) == 'hyderabad'){ echo 'class="active"'; } ?>><a href="<?php echo site_url(); ?>holi/hyderabad">Hyderabad</a></li>
	          	  	<li <?php if(strtolower($currentCity) == 'bengaluru'){ echo 'class="active"'; } ?> ><a href="<?php echo site_url(); ?>holi/bengaluru">Bengaluru</a></li>
	          	  	<li <?php if(strtolower($currentCity) == 'mumbai'){ echo 'class="active"'; } ?> ><a href="<?php echo site_url(); ?>holi/mumbai">Mumbai</a></li>
	          	  	<li <?php if(strtolower($currentCity) == 'pune'){ echo 'class="active"'; } ?> ><a href="<?php echo site_url(); ?>holi/pune">Pune</a></li>
	          	  	<li <?php if(strtolower($currentCity) == 'delhi'){ echo 'class="active"'; } ?> ><a href="<?php echo site_url(); ?>holi/delhi">Delhi</a></li>
	          	  	<li <?php if(strtolower($currentCity) == 'chennai'){ echo 'class="active"'; } ?> ><a href="<?php echo site_url(); ?>holi/chennai">Chennai</a></li>
                    <li <?php if(strtolower($currentCity) == 'goa'){ echo 'class="active"'; } ?> ><a href="<?php echo site_url(); ?>holi/goa">Goa</a></li>
	          	  	<li <?php if(strtolower($currentCity) == 'ahmedabad'){ echo 'class="active"'; } ?> ><a href="<?php echo site_url(); ?>holi/ahmedabad">Ahmedabad</a></li>
	          	  	<li <?php if(strtolower($currentCity) == 'kolkata'){ echo 'class="active"'; } ?> ><a href="<?php echo site_url(); ?>holi/kolkata">Kolkata</a></li>
	          	</ul>
	        </div><!--/.nav-collapse -->
      	</div>
    </nav>

    <!-- Image Header -->
    <?php
	if(count($bannerList) > 0)
	{
		?>
	<div id="#" class="image-header" data-stellar-background-ratio=".5">
		<div class="container">
        
        <div class="col-sm-12 col-md-12 col-lg-12 carousel">
					<div id="slider-2" class="carousel slide simple-slider" data-ride="carousel">
                    <div class="carousel-inner">
                    <?php
					$bcount = 1;
					foreach($bannerList as $banner)
					{
						?><div class="item <?php echo ($bcount==1)?'active':''; ?>">
					      		<a href="<?php echo $banner['url']; ?>" target="_blank"><img src="<?php echo $banner['bannerImage']; ?>" alt="<?php echo $banner['title']; ?>" title="<?php echo $banner['title']; ?>"></a>
					    </div><?php
						$bcount++;
					}
					?>
                    </div>
                    <?php
					if(count($bannerList) > 1)
					{
						?>
                        <a class="left carousel-control" href="#slider-2" role="button" data-slide="prev">
						    <span class="glyphicon glyphicon-chevron-left"></span>
						</a>
						<a class="right carousel-control" href="#slider-2" role="button" data-slide="next">
						    <span class="glyphicon glyphicon-chevron-right"></span>
						</a>
                        <?php
					}
					?>
						
					</div>
				</div>
                
           
		</div>     
	</div>
    
    <?php
	}
	?>

	<!-- Audio Player -->
	<section id="latest-events" <?php if(count($bannerList) == 0){ echo 'style="padding-top:92px !important"'; } ?> >
  <div class="container">
    <div class="row">
      <div class="col-lg-12 col-md-12 col-sm-12">
        <div class="nav-search">
        <form id="event-check" name="event-check" action="/search" method="get">
        	<input type="text" placeholder="Search holi Events" autocomplete="off" name="keyWord" id="keyWord" class="ui-autocomplete-input">
            <button id="searchButton"><i class="icon-search"></i></button>  
        </form>
        </div>
        <div style="display: none;" id="suggestions" class="suggestionsBox">
				<div id="autoSuggestionsList" class="suggestionList">
					&nbsp;
				</div>
			</div>
        
      </div>
    </div>
  </div>
</section>


<!--Discount codes-->
<div class="clearfix"></div>
<section id="latest-newsticker">
  <div class="container">
    <div class="row">
      <div class="col-lg-12 col-md-12 col-sm-12">
        <div id="breakingnews2" class="BreakingNewsController easing" >
          <div class="bn-title"></div>
          
          
          <ul >
          <?php
		  foreach($discountList as $discount)
		  {
			  ?><!--<li ><a href="<?php echo $discount['eventurl']; ?>" target="_blank"><?php echo $discount['discountlable']; ?> <span class="disc-code-bg"><?php echo $discount['promocode']; ?></span></a></li>-->
			  <li class="discountCodeTicker"><?php echo $discount['discountlable']; ?> <span class="disc-code-bg"><?php echo $discount['promocode']; ?></span></li>
			  <?php
		  }
		  ?>
          
          </ul>
          <div class="bn-arrows"><span class="bn-arrows-left"></span><span class="bn-arrows-right"></span></div>
        </div>
      </div>
    </div>
  </div>
</section>
<!--Discount codes-->

	
	<!-- Latest Articles -->
	<div id="#" class="container last-news" <?php if(count($bannerList) == 0){ echo 'style="padding-top:92px !important"'; } ?> >
		<div class="row">
			<div class="col-sm-12 category-title">
				<h1>HOLI EVENTS 2016 IN <?php echo $currentCity; ?></h1>
				<div class="under-line">
					<span class="line"></span>
				</div>
				<p>&nbsp;</p>
			</div>
			
				
                <?php
				$eventsData = $eventsList['eventList'];
				if(count($eventsData) > 0)
				{
					?>
                    <div class="col-sm-12 latest-articles">
                    <ul id="eventsBox">
                    <?php
					foreach($eventsData as $event)
					{
						$eventVenue = $event['venueName'].",".$event['cityName'];
						?>
                        <li>
						<div class="wrapper-article">
							<img src="<?php echo (strlen($event['thumbImage'])>0)?$event['thumbImage']:_HTTP_MICROSITE_CF_ROOT.'/assets/img/holi-2016-default.jpg'; ?>"  alt="<?php echo $event['title']; ?>" class="img-responsive" onerror="this.src='<?php echo _HTTP_MICROSITE_CF_ROOT; ?>/assets/img/holi-2016-default.jpg'">
							<a class="hover" target="_blank" href="<?php echo $event['eventUrl']; ?>">
								<span class="category">BOOK NOW</span>
								<span class="line-h"></span>
								<h3><?php echo $event['title']; ?></h3>
								<p><?php echo substr($eventVenue,0,100); ?></p>
							</a>
						</div>
                        
                        <div class="eventname"><?php echo substr($event['title'],0,20); ?><span class="city"><?php echo $event['cityName']; ?></span></div>
						</li>
                        <?php
					}
					?>
                    </ul>
                    </div>
                    
                    
                    <div class="col-sm-12 category-title" style="display:<?php echo (count($eventsList) > 0 && $eventsList['nextPage'])?"inline-block":"none";?>">
            			<div class="wrapper-view" id="loadmore">
                             <a class="hover-view"><span class="category">VIEW MORE</span><span class="line-h"></span></a>
                        </div>            
                    </div>
                    
                    
                    <?php
					
				}
				else{
					?>
                    <div class="row">
                        <div class="col-sm-12 category-title">
                            <div class="wrapper-find">
                                <span class="category">Sorry, We did not find any events. <a class="hover-find" href="<?php echo site_url(); ?>holi">Click Here</a> to load all Events.</span>
                                <span class="line-h"></span>
                            </div>
                        </div>   
                    </div>
                    <?php
				}
				?>
                
					<!--<li>
						<div class="wrapper-article">
							<img src="http://static.meraevents.com/content/eventlogo/96356/Colors-t_thumb1456144659.jpg" alt="" class="img-responsive">
							<a class="hover" href="blog-single.html">
								<span class="category">BOOK NOW</span>
								<span class="line-h"></span>
								<h3>Colours The Sensational Holi Festival</h3>
								<p>24th Mar 2016 | Bengaluru</p>
							</a>
						</div>
					</li>-->
					
			
		</div>
	</div>
	
	<!-- Discography -->
		
	
	</div>



        
        
	<!-- Contact -->
	<div id="#" class="container contact">
		<div class="row">
			<div class="col-sm-12 category-title">
				<h1>QUICK LINKS</h1>
				<div class="under-line">
					<span class="line"></span>
				</div>
				
			</div>
			

<div class="col-sm-12 contact-details">
				<div class="wrapper-city">
					
					<p><a href="<?php echo site_url(); ?>holi">Holi Events 2016 All India</a></p>
                    <p><a href="<?php echo site_url(); ?>holi/chennai">Holi Events 2016 Chennai</a></p>
                    <p><a href="<?php echo site_url(); ?>holi/pune">Holi Events 2016 Pune</a></p>
				</div>

				<div class="wrapper-city">
					
					<p><a href="<?php echo site_url(); ?>holi/hyderabad">Holi Events 2016 Hyderabad</a></p>
                    <p><a href="<?php echo site_url(); ?>holi/delhi">Holi Events 2016 New Delhi/NCR</a></p>
                    <p><a href="<?php echo site_url(); ?>holi/goa">Holi Events 2016 Goa</a></p>
                    <p><a href="<?php echo site_url(); ?>holi/ahmedabad">Holi Events 2016 Ahmedabad</a></p>
				</div>

				<div class="wrapper-city">
					
				<p><a href="<?php echo site_url(); ?>holi/bengaluru">Holi Events 2016 Bengaluru</a></p>
                    <p><a href="<?php echo site_url(); ?>holi/mumbai">Holi Events 2016 Mumbai</a></p>
                    <p><a href="<?php echo site_url(); ?>holi/kolkata">Holi Events 2016 Kolkata</a></p>
				</div>
			</div>
            
            
            
			<div class="col-sm-12 contact-details2">
				<div class="wrapper">
					<div class="hexagon"><i class="icon-mail"></i></div>
					<p><a href="mailto:support@meraevents.com" target="_blank">support@meraevents.com</a></p>
				</div>

				<div class="wrapper">
					<div class="hexagon"><i class="icon-mic-1"></i></div>
					<p>Versant Online Solutions Pvt. Ltd.<br>Hyderabad</p>
				</div>

				<div class="wrapper">
					<div class="hexagon"><i class="icon-mobile"></i></div>
					<p>+91 9396 555 888</p>
				</div>
			</div>
		</div>
	</div>	

	<!-- Footer -->
	<div class="footer">
		
		<p>
		Copyright 2016. All Rights Reserved.</p>
		<span class="line-footer"></span>
		<ul class="social">
			<li><a href="http://www.facebook.com/meraevents" target="_blank"><i class="icon-facebook"></i></a></li>
            <li><a href="https://twitter.com/meraeventsindia" target="_blank"><i class="icon-twitter"></i></a></li>
			<li><a href="http://pinterest.com/meraevents/" target="_blank"><i class="icon-pinterest"></i></a></li>
			<li><a href="https://plus.google.com/114189418356737609354/about" target="_blank"><i class="icon-gplus"></i></a></li>
			
			
		</ul>
	</div>
    
    
  

	<!-- ======== JavaScript ======== -->
    <script type="text/javascript" language="javascript">
	var currentSite = '<?php echo site_url(); ?>';
	var cityId = '<?php echo $cityId; ?>';
	var stateId = '<?php echo $stateId; ?>';
	</script>
	<script src="<?php echo _HTTP_MICROSITE_CF_ROOT; ?>/assets/js/common1.min.js.gz"></script>
	<script type="text/javascript" src="<?php echo _HTTP_MICROSITE_CF_ROOT; ?>/assets/js/smooth-scroll.min.js.gz"></script>
	<script type="text/javascript" src="<?php echo _HTTP_MICROSITE_CF_ROOT; ?>/assets/js/bootstrap.min.js.gz"></script>
    <script type="text/javascript" src="<?php echo _HTTP_MICROSITE_CF_ROOT; ?>/assets/js/masonry.min.js.gz"></script>
    <script type="text/javascript" src="<?php echo _HTTP_MICROSITE_CF_ROOT; ?>/assets/js/imagesloaded.pkgd.min.js.gz"></script>
    <!--<script type="text/javascript" src="<?php echo _HTTP_MICROSITE_CF_ROOT; ?>/assets/js/jquery.validate.min.js"></script>-->
    <!-- jPlayer -->
	
	
    
<script>
  
	
	
$("#breakingnews2").BreakingNews({
   background    :'#ddd',
   title     :'OFFERS & DISCOUNTS',
   titlecolor    :'#FFF',
   titlebgcolor  :'#fa52a2',//099
   linkcolor   :'#333',//c1213c
   linkhovercolor  :'#fa52a2',//099
   fonttextsize  :16,
   isbold      :false,
   border      :'solid 1px #333',
   width     :'99%',
   //height :  '26px',
   timer     :5000,
   autoplay    :true,
   effect      :'slide'
});



(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-41640740-1', 'auto');
ga('send', 'pageview');
 
</script>
 <?php include(APPPATH."/views/includes/elements/wizrocket.php");?>
<script type="text/javascript">
wizrocket.event.push("Visited from Microsite", {"Event Name":"holi2016"});
</script>
</body>
</html>
<?php //echo site_url(); exit; ?>

