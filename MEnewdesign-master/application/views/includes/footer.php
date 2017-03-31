<?php $footerFAQLink = commonHelperGetPageUrl("faq"); 
  $footerAboutusLink = commonHelperGetPageUrl("aboutus"); 
  $contactUsLink = commonHelperGetPageUrl("contactUs"); 
  $privacyLink = commonHelperGetPageUrl("privacypolicy");
  $footerCareerLink = commonHelperGetPageUrl("career");
  $footerBlogLink = commonHelperGetPageUrl("blog");
  $footerNewsLink = commonHelperGetPageUrl("news");
  $footerClientfbLink = commonHelperGetPageUrl("client_feedback");
  $footerPricingLink = commonHelperGetPageUrl("pricing");
  $createEventLink = commonHelperGetPageUrl('create-event');
  $bugBountyLink = commonHelperGetPageUrl("bugbounty");
  $searchLink = commonHelperGetPageUrl("search");
  $jsExt = $this->config->item('js_gz_extension');
  $publicPath = $this->config->item('js_public_path');
  $imgStaticPath = $this->config->item('images_static_path');
  if($defaultCityId == 0){$defaultCityName = LABEL_ALL_CITIES;}
  $urihome = $this->uri->segment(1);
  ?>
<footer>
	<div class="container ftr_container">
		<nav class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
			<ul class="col-md-3 col-sm-4 col-xs-3 col-lg-3">
                            <li><a href="javascript:void(0)"><h4>Categories</h4></a></li>
                              <?php  $cityName = str_replace(' ', '', $defaultCityName);
               foreach ($categoryList as $cat){ 
                   //$catName = strtolower(str_replace(' ','-', $cat['name']));
                   $catValue = strtolower($cat['value']);
                                ?>
                <li value="<?php echo $cat['id'];?>">
                    <a class="footerCategorySearch" id="<?php echo $cat['id']; ?>" <?php if($cityName == "AllCities" || $urihome == ""){ ?> href ="<?php echo site_url().$catValue;?>" <?php }else if($catValue == "newyear"){ ?> href ="<?php echo site_url().$catValue.'/'.strtolower($defaultCityName);?>" <?php } else { ?> href="<?php echo site_url().strtolower($defaultCityName).'/'.$catValue; ?>"<?php } ?>><?php echo $cat['name'];?></a>
                </li>
               <?php }?>
                <!-- <li class="bugbounty_img"><a href="<?php// echo $bugBountyLink; ?>" target="_blank"><img src="<?php//  echo $imgStaticPath; ?>bugbounty.png"></a></li> -->
			</ul>
			<ul class="col-md-3 col-sm-4 col-xs-3 col-lg-3">
				<li><a href="javascript:void(0)">
					<h4>Services</h4>
				</a></li>
				<li><a href="<?php echo commonHelperGetPageUrl("eventregistration"); ?>">Free Events Registration</a></li>
				<li><a href="<?php echo commonHelperGetPageUrl("selltickets"); ?>">Sell Tickets Online</a></li>
				<li><a href="<?php echo $createEventLink;?>">Create Event</a></li>
				<!--<li><a href="discount.html">Discount</a></li> -->
				<li><a id="eventFind" href="<?php echo $searchLink; ?>">Find Event</a></li>
				<li><a href="<?php echo $footerPricingLink; ?>" target="_blank">Fees & Pricings</a></li>
                                 <li><a href="<?php echo commonHelperGetPageUrl("apidevelopers");?>" target="_blank">Developers</a></li>
                                 <li><a href="<?php echo commonHelperGetPageUrl("dashboard-global-affliate-home");?>" target="_blank">Global Affiliate Marketing</a></li>
			</ul>
			<ul class="col-md-3 col-sm-4 col-xs-3 col-lg-3">
				<li><a href="javascript:void(0)">
					<h4>Need Help?</h4>
				</a></li>
				<li><a href="<?php echo $footerAboutusLink; ?>">About Us</a></li>
<!--				<li><a href="<?php// echo $footerCareerLink; ?>">Careers</a></li>-->
				<li><a href="<?php echo $footerBlogLink; ?>" target="_blank">Blog</a></li>
				<li><a href="<?php echo $footerFAQLink; ?>">FAQs</a></li>
<!--				<li><a href="http://www.meraevents.com/apidevelopers">Developers</a></li>  This link's href needs to be changed after developing api developer page - Sai Sudheer-->
				<li><a href="<?php echo $footerNewsLink; ?>">News</a></li>
				<li><a href="<?php echo commonHelperGetPageUrl("mediakit"); ?>">Media Kit</a></li>
<!--				<li><a href="<?php //echo $footerClientfbLink; ?>">Client's Feedback</a></li>-->
				<li><a href="<?php echo commonHelperGetPageUrl("terms"); ?>" target="_blank">Terms of use</a></li>
				<li><a href="<?php echo $privacyLink; ?>" target="_blank">Privacy Policy</a></li>
<!--				<li><a href="<?php //echo commonHelperGetPageUrl("team"); ?>">Team</a></li>-->
			</ul>
			<ul class="col-md-3 col-sm-4 col-xs-3 col-lg-3">
				<li><a href="javascript:void(0)"><h4>Products</h4></a></li>
                <li><a href="http://www.planica.in/" target="_blank">planica</a></li>
				<li><a href="http://www.moozup.com/" target="_blank">moozup</a></li>
                <li><a href="http://www.easytag.in/" target="_blank">EasyTag</a></li>
            </ul>
		</nav>
		<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 mobileVer">
			<ul>
				<li><a href="<?php echo $footerAboutusLink; ?>">About Us</a></li>
				<li><a href="<?php echo $footerBlogLink; ?>" target="_blank">Blog</a></li>
				<li><a href="<?php echo $footerFAQLink; ?>">Help</a></li>
<!--				<li><a href="<?php //echo $footerCareerLink; ?>">Careers</a></li>-->
				<!--<li><a href="discount.html">Discount</a></li>-->
				<!-- <li><a href="<?php //echo commonHelperGetPageUrl('support');; ?>" target="_blank">Support</a></li> -->
				<li><a href="<?php echo $contactUsLink; ?>" target="_blank">Support</a></li>
				<li><a href="<?php echo commonHelperGetPageUrl('print_pass');?>">Print Ticket</a></li>
				<li><a href="<?php echo commonHelperGetPageUrl("dashboard-global-affliate-home");?>" target="_blank">Global Affiliate Marketing</a></li>
			</ul>
			<ul>
<!--				<li><a href="#">Privacy</a></li>-->
				<li><a href="<?php echo $footerFAQLink; ?>">FAQs</a></li>
				<li><a href="<?php echo $footerPricingLink; ?>">Pricing</a></li>
				<li><a href="<?php echo $footerNewsLink; ?>">News & Press</a></li>
				<li><a href="<?php echo commonHelperGetPageUrl("terms"); ?>">Terms of Use</a></li>
				<li><a href="<?php echo $privacyLink; ?>">Privacy Policy</a></li>
				<li>&nbsp;</li>
<!--				<li><a href="<?php //echo $footerClientfbLink; ?>">Clients Feedback</a></li>-->
				<!-- <li><a href="<?php echo $bugBountyLink; ?>" target="_blank">Bug Bounty Program</a></li> -->
			</ul>
			<ul>
				<li><a href="http://www.planica.in/" target="_blank">planica</a></li>
				<li><a href="http://www.moozup.com/" target="_blank">moozup</a></li>
                <li><a href="http://www.easytag.in/" target="_blank">EasyTag</a></li>
			</ul>
		</div>
		<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 footerRight">
		<div class="row">
				<h4>Follow Us</h4>
				<ul>
                     <li><a href="http://www.facebook.com/meraevents" target="_blank" class="icon-fb fb" ></a></li>
					<li><a href="http://twitter.com/#!/meraeventsindia" target="_blank" class="icon-tweet tweet" ></a></li>
					<li><a href="https://www.linkedin.com/company/meraevents" target="_blank" class="icon-in in"></a></li>
					<li>  <a href="https://plus.google.com/114189418356737609354/about" target="_blank" class="icon-google google" ></a></li>
				</ul>
			</div>
			<div class="row socialIcons">
				<h4>Get Weekly Updates On Events</h4>
                                <form action="http://meraevents.us12.list-manage.com/subscribe/post?u=f96a2420628d423aab0d3cbaa&amp;id=e85e25c728" method="post" id="mc-embedded-subscribe-form" name="mc-embedded-subscribe-form" class="validate" target="_blank" novalidate>
                                    <span class="icon-nextArrow" style="top:68% !important;" onclick="document.getElementById('mc-embedded-subscribe-form').submit()"></span> 
                                    <input type="email" value="" name="EMAIL" id="mce-EMAIL" placeholder="Email ID" class="form-control require email">
                                </form>
			</div>
			<div class="row" style="margin-top:20px;">	
				<p><!-- <i class="icon2-phone"></i> --> <a href="<?php echo $contactUsLink; ?>" target="_blank" style="font-size:18px; color:#fff;padding:5px 10px;">@ Contact Us</a></p>			 
				<!-- <p style="font-size:18px; color:#fff;padding:5px 10px;"><i class="icon2-phone"></i> +91-9396555888  (Mon-Sun 10AM to 7PM)</p>			 
				<p style="font-size:18px; color:#fff;padding:5px 10px;"><i class="icon2-envelope-o"></i> <a href="mailto:support@meraevents.com">support@meraevents.com</a></p> -->
			</div>
		</div>
	</div>
	<div class="container" id="FooterCategories">
		<div class="col-lg-12 col-md-12 col-sm-12 f-padd">
		<div class="footercat-links col-lg-12 col-md-12 col-sm-4 col-xs-4">
			<ul class="footerlinks-list">
				<li class="footercat-heading">Cities <span class="f-sep">:</span> </li>
                                <?php $numItems = count($cityList);$i = 0;
                                foreach ($cityList as $city) { ?>
                                    <li>
                                        <a id="<?php echo $city['id']; ?>" href="<?php echo site_url().strtolower($city['name'])."-events"; ?>"><?php echo $city['name']; ?></a>
                                        <?php if(++$i !== $numItems) { ?>
                                         <span class="f-sep">|</span>
                                  <?php  }?>
                                    </li>
                                <?php } ?>
			</ul>
		</div>
		</div>
	</div>
	<p class="text-center" style="color:#7e7e7e;"><span>&copy; 2009-<?php echo date("Y");?>, Copyright @ Versant Online Solutions. All Rights Reserved</span></p>
</footer>
<script>
var api_commonrequestsUpdateCookie = "<?php echo commonHelperGetPageUrl('api_commonrequestsUpdateCookie')?>";
 </script>
<?php 
// Loading Wizrocket 
  include("elements/wizrocket.php"); 
	$callclass = $this->router->fetch_class(); 
	if($callclass == 'home' || $callclass == 'search' ||  $callclass == 'user' || $callclass == 'nopage' ){?>
	<script src="<?php echo $this->config->item('js_angular_path').'angular'.$jsExt;?>"></script>
<?php } ?>
<script src="<?php echo $publicPath.'combined'.$jsExt;?>"></script>
<script src="<?php echo $publicPath.'common'.$jsExt;?>"></script>
<?php
	if(isset($jsArray) && is_array($jsArray)) {
		foreach($jsArray as $js) {
				echo '<script src="'.$js.$jsExt.'"></script>';
                echo "\n";
                }
        }
//Loading Google ANalytics
include("elements/googleanalytics.php");
// Loading TrueSemantic
if ($this->config->item('tsFeedbackEnable')) {
    include("elements/truesemantic.php"); 
}
    // Loading Piwik recommendations script
    //include("elements/piwikrecommendations.php");
// Loading adroll tag
include("elements/adroll_tag.php");
// Loading facebook pixcel code
include("elements/facebook_pixel.php");
?>
<!-- <script type="text/javascript" >
    document.getElementById('dvLoading').style.display = 'none';
</script> -->
<!--Start of Zopim Live Chat Script--> 
<script type="text/javascript">
var Eventurl = "<?php echo $createEventLink;?>";
var EventEditurl = "<?php echo commonHelperGetPageUrl('edit-event')?>";
var winloc = window.location.href + "/";
if( winloc.indexOf(Eventurl) != -1|| winloc.indexOf(EventEditurl) != -1 ){
	loadtinyMce();
}
window.$zopim||(function(d,s){var z=$zopim=function(c){
z._.push(c)},$=z.s=
d.createElement(s),e=d.getElementsByTagName(s)[0];z.set=function(o){z.set.
_.push(o)};z._=[];z.set._=[];$.async=!0;$.setAttribute('charset','utf-8');
$.src='//cdn.zopim.com/?re1Tn1zzg8om9TaoaKDzgaN1esPgyzg7';z.t=+new Date;$.
type='text/javascript';e.parentNode.insertBefore($,e)})(document,'script');
</script> 
<!--End of Zopim Live Chat Script-->
</body>
</html>
