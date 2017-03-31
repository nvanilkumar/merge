<?php
if(strpos($_SERVER['REQUEST_URI'],"ticket") > 0)
{
	$ticketfile=str_replace("/sbicard/","",$_SERVER['REQUEST_URI']);
	require_once($ticketfile.".php");
	exit;	
}
?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>SBI Card</title>
<meta name="description" content="">
<meta name="author" content="">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">





<!-- End Retina Images -->
<!-- Fonts -->
<link href='http://fonts.googleapis.com/css?family=Lato:300,400,900' rel='stylesheet' type='text/css'>
<!-- End Fonts -->
<link rel="stylesheet" href="<?php echo $microsite_path;?>/css/style.min.css">
<!--<link rel="stylesheet" href="css/slider.css">

<link rel="stylesheet" href="css/owl.theme.css" />
<link rel="stylesheet" href="css/owl.transitions.css" />-->
<!--[if lt IE 9]>
<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
<![endif]-->
<link rel="shortcut icon" href="<?php echo $microsite_path;?>/images/favicon.ico">
<!-- JAVASCRIPT LIBRARY -->


</head>
<body>
<div id="mobile-nav">
  <div class="container clearfix">
    <div> 
      <!-- Mobile Nav Button -->
     <!-- <div class="navigationButton sixteen columns clearfix"> <img src="images/mobile-nav.png" alt="Navigation" width="29" height="17" /> </div>-->
      <!-- End Mobile Nav Button -->
     
    </div>
  </div>
  <!-- END CONTAINER --> 
</div>

<!--<header class="clearfix">
  <div class='container-top'> 
   
    <nav id="nav" class="eleven columns clearfix">
       <ul id="navigation">
        <li class="logo"><a href="#" target="_blank">
       
        </a> </li>
        
      </ul>
    </nav>
   
  </div>
</header>-->


<header class="clearfix">
  <div class='container'> 
    <!-- Logo -->
    <div id="logo" class="five columns clearfix"><a href="http://www.meraevents.com/sbicard" target="_blank"><img src="<?php echo $microsite_path;?>/images/sbi-logo.png" width="126" alt="logo" /></a></div>
    <!-- End Logo --> 
    <!-- Navigation -->
    <nav id="nav" class="eleven columns clearfix"><!--fourteen-->
       <ul id="navigation">
        <!--<li class="logo"><a href="#" target="_blank">
        <img src="images/sbi-logo.png" width="126" alt="logo" />
        </a> </li>-->
        
      </ul>
    </nav>
    <!-- End Navigation --> 
  </div>
</header>






<!-- END CONTAINER -->
<section class="container2 clearfix"> 
  <div class="text">
  Exclusive Event Access for SBI Cardholders
  </div>
    
        <div class="slider-banner">
         
              <div id="owl-demo" class="owl-carousel">
			<div class="item">
            
               <img src="<?php echo $microsite_path;?>/images/banner.jpg" ></div>
               
        </div>
   

</section>
<!-- END CONATINER -->
<section id="filters">
          <div class="container clearfix">
    <div class="sixteen columns">
              <h2>Filter by City</h2>
            </div>
    
    <!-- Filters -->
    <ul class="sixteen columns" id="filter">
    		  <li class="current"><a href="#">All</a></li>
              <li class=""><a href="#" style="outline:medium none;">Hyderabad</a></li>
              <li class=""><a href="#" style="outline: medium none;">Mumbai</a></li>
              <li class=""><a href="#" style="outline: medium none;">Bengaluru</a></li>
              <li class=""><a href="#" style="outline: medium none;">Delhi NCR</a></li>
              
            </ul>
    <!-- End Filters --> 
    
  </div>
        </section>
<section id="work" class="container clearfix">
          <div class="container clearfix"> 
    
    <!-- Thumbnails -->
    <ul id="portfolio">
              <li class="thumb one-third column delhi-ncr"> <a class="screen-roll" href="http://www.meraevents.com/sbicard/ticket"> <span>
              <div>
                <h3 id="demo">BOOK NOW</h3>
                 <h2 class="details five columns">Jagjit Singh Live in Concert</h2>
                 <h2 class="details2 five columns">18th Mar 2016 | New Delhi</h2>
                 <h2 class="details3 five columns">Exclusive discount : 10%
</h2>
              </div>
              
              </span> 
              
                            <img src="http://static.meraevents.com/content/eventlogo/97444/jagjit-t_thumb1457769669.jpg" alt="thumb"/> </a> 
                            
          <div class="eventname">Jagjit Singh Live<span class="city">
          <a class="screen-roll" href="http://www.meraevents.com/sbicard/ticket">
          BOOK NOW</a></span></div>                  
                            </li>
              
            </ul>
    <!-- End Thumbnails --> 
  </div>
        </section>

        </section>
<footer class="container clearfix"> 




<div  class="container clearfix twelve columns">
<div class="customer"><a href="http://www.meraevents.com/" target="_blank">
Powered by<br><br>
<img src="http://static.meraevents.com/newyear/assets/img/meraevents-logo-y.svg" width="150"></a></div><br><br>
<div class="customer">Customer Care</div>
<div class="customer"> <a href="mailto:support@meraevents.com" target="_blank">
support@meraevents.com </a></div>
<div class="customer"> +91-9396555888</div>
</div>
 
    

    
  <!-- Navigation -->
  
  <!-- End Navigation --> 
  <!-- Copyright -->
  <div id="copyright" class="twelve columns">
    <p>&copy; 2016, Versant Online Solutions Pvt.Ltd, All Rights Reserved.</p>
  </div>
  
 
  
  <!-- End Copyright --> 
</footer>
<!-- END CONTAINER -->



<script type="text/javascript" src="<?php echo $microsite_path;?>/js/jquery-1.9.1.min.js"></script>
<script src="<?php echo $microsite_path;?>/js/owl.carousel.min.js" type="text/javascript"></script>


<!-- Slider JS -->

<script type="text/javascript" src="<?php echo $microsite_path;?>/js/bootstrap.min.js"></script>
<!-- Main JS -->
<script src="<?php echo $microsite_path;?>/js/custom.js" type="text/javascript"></script>



<script>

  

    //$(document).ready(function() {
     
      $("#owl-demo").owlCarousel({
     
          navigation : false, // Show next and prev buttons
          slideSpeed : 300,
          paginationSpeed : 400,
          singleItem:true
     
          // "singleItem:true" is a shortcut for:
          // items : 1, 
          // itemsDesktop : false,
          // itemsDesktopSmall : false,
          // itemsTablet: false,
          // itemsMobile : false
     
      });
     
    //});


</script>



</body>
</html>