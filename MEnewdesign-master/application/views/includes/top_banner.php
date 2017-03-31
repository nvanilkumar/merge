<?php
$topBannerCount = count($topBannerList);
?>
<div class="carousel-inner" role="listbox">
<?php
if($topBannerCount > 0) {
?>
<?php
$bloop = 0;
foreach($topBannerList as $banValue) {
$active = ($bloop == 0) ? "active" : "";
$bannerTitle = $banValue['title'];
$bannerImage = $banValue['bannerImage'];
$bannerUrl = $banValue['url'];
$bannerTarget = "_self";
if(strlen($bannerImage)> 0){
?>
<div class="item <?php echo $active;?>">
<a target="<?php echo $bannerTarget; ?>" href="<?php echo $bannerUrl; ?>"><img src="<?php echo $bannerImage;?>" width="1280" height="370" alt="<?php echo $bannerTitle;?>" title="<?php echo $bannerTitle;?>" /></a>
<div class="carousel-caption">
</div>
</div>
<?php
$bloop++;
}
}
?>	
</div>
<a class="left carousel-control" href="#carousel-example-generic" role="button" data-slide="prev"> <span class="icon-prevArrow" aria-hidden="true"></span></a> 											  
<a class="right carousel-control" 																						href="#carousel-example-generic" role="button" data-slide="next">
<span class="icon-nextArrow" aria-hidden="true"></span>
</a>
<?php } ?>