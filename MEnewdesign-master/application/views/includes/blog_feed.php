<div class="container blogContainer">
<?php if (count($blogData) > 0) { ?>
<h1>blog</h1>
<p>Get more insights into events, organizing of events, tips, trends, offers, and a lot more.</p>
<ul>
<?php foreach ($blogData as $blogItem) { ?>
<li class="col-lg-4 col-md-4 col-sm-4 col-xs-12 myoverlay">
<a href="<?php echo $blogItem['link']; ?>" target="_blank">
<div class="">
<img src="<?php echo $blogItem['background_image']; ?>" class="effect"
 alt="events img" />
</div>
<div class="latestBlog" 
 style="background:<?php
 if ($blogItem['categoryData']['themecolor'] != '') {
echo $blogItem['categoryData']['themecolor'];
} else {
echo '#ba36a6';
}
?>">
<h2><?php echo $blogItem['title']; ?></h2>
<span><?php echo $blogItem['description']; ?></span>
</div>
<div class="overlay">
<div class="overlayButt">
<div class="overlaySocial">
<!-- <span class="icon-<?php echo strtolower($blogItem['categoryData']['name']); ?>"></span> -->
 </div>
</div>
</div>
</a></li>
<?php } ?>
</ul>
<?php } ?>
</div>