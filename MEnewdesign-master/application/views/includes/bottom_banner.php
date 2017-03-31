<?php
if(count($bottomBannerList) > 0) {
$bottomBannerTitle = $bottomBannerList[0]['title'];
$bottomBannerImage = $bottomBannerList[0]['bannerImage'];
$bottomBannerUrl = $bottomBannerList[0]['url'];
$bottomBannerTarget = "_self";
?>
<a href="<?php echo $bottomBannerUrl;?>" target="<?php echo $bottomBannerTarget;?>"> <img src="<?php echo $bottomBannerImage;?>" title="<?php echo $bottomBannerTitle;?>" alt="<?php echo $bottomBannerTitle;?>"></a>
<?php } ?>