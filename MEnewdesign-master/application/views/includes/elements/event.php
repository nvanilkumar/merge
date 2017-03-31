<?php
$eventUrl=$eventList[$key]['eventUrl'];
$title=$eventList[$key]['title'];
$thumbImage=$eventList[$key]['thumbImage'];
$defaultThumbImage=$eventList[$key]['defaultThumbImage'];
$startDate=$eventList[$key]['startDate'];
$eventUrl=isset($eventList[$key]['eventExternalUrl'])?$eventList[$key]['eventExternalUrl']:$eventList[$key]['eventUrl'];
$categoryName=$eventList[$key]['categoryName'];
?>
<li class="col-xs-12 col-sm-6 col-md-4 col-lg-4 thumbBlock"  itemscope itemtype="http://schema.org/Event">
<a href="<?php echo $eventUrl; ?>" itemprop="url" class="thumbnail">
<div class="eventImg">
<img itemprop="image" src="<?php echo $thumbImage; ?>" width="" height=""
alt="<?php echo $title; ?>" title="<?php echo $title; ?>"  onError="this.src='<?php echo $defaultThumbImage; ?>'" />
</div>
<h2>
<span class="eveHeadWrap" itemprop="name"><?php echo $title; ?></span>
</h2>
<div class="info">
<span itemprop="startDate" content="<?php echo $startDate;?>"><?php echo allTimeFormats($startDate,15); ?></span> 
</div>
<div itemprop="location" itemscope itemtype="http://schema.org/Place" class="eventCity" style='display:<?php if($defaultCityName == "All Cities"){ ?> block <?php } else { ?> none <?php } ?>;'>
<span itemprop="name"><?php echo $cityName;?></span>
</div>
<div class="overlay">
<div class="overlayButt">
<div class="overlaySocial">
<span class="icon-fb"></span> <span class="icon-tweet"></span>
<span class="icon-google"></span>
</div>
</div>
</div>
</a> <a href="<?php echo $eventUrl; ?>" class="category">
<span class="icon1-<?php echo strtolower(str_replace(" ", "", $categoryName)); ?> col<?php echo strtolower(str_replace(" ", "", $categoryName)); ?>"></span> <span
class="catName"><em><?php echo $categoryName; ?></em></span>
</a>
</li>