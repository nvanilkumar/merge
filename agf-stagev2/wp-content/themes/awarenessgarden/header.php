<?php
/**   
 * The Header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="main">
 *
 * @package WordPress
 * @subpackage Twenty_Ten
 * @since Twenty Ten 1.0
 */

/*
$sslPages = array('donation','8x8-brick','4x8-brick','12x12-blue-stone');
$pagename = basename(get_permalink());
  
if($_SERVER["HTTPS"] != "on" && in_array($pagename,$sslPages) ) {
  $newurl = "https://" . $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"];
  header("Location: $newurl");
  exit();
}
*/

?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>" />
<title><?php
	global $page, $paged;
	wp_title( '|', true, 'right' );
	bloginfo( 'name' );
	$site_description = get_bloginfo( 'description', 'display' );
	if ( $site_description && ( is_home() || is_front_page() ) )
		echo " | $site_description";

	if ( $paged >= 2 || $page >= 2 )
		echo ' | ' . sprintf( __( 'Page %s', 'twentyten' ), max( $paged, $page ) );

	?></title>
<link rel="profile" href="http://gmpg.org/xfn/11" />
<link rel="stylesheet" type="text/css" media="all" href="<?php bloginfo( 'stylesheet_url' ); ?>" />
<link rel="stylesheet" href="<?php bloginfo('template_url'); ?>/css/ad-reset.css" type="text/css" media="screen" />
<link rel="stylesheet" href="<?php bloginfo('template_url'); ?>/css/global.css" type="text/css" media="screen" />
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
<?php
	if ( is_singular() && get_option( 'thread_comments' ) )
		wp_enqueue_script( 'comment-reply' );

	wp_head();
?>
  
</head>

<body <?php //body_class(); ?>>
<div id="page_container">
<div id="header">
  <div id="header_nav">
  <table style="width:100%;">
  <tbody><tr>
    <td style="width: 375px;">
      <a href="<?php bloginfo("url"); ?>/"><img border="0" alt="" src="<?php bloginfo('template_url'); ?>/images/logo.jpg" id="header logo"></a>
    </td>
    <td style="vertical-align: bottom;">
      <table style="width: 100%;">
        <tbody><tr>
          <td>
          <div style="width: 450px; height: 100px; margin-bottom:10px; float: right; overflow: hidden;  ">
            
            <div id="global-alert-box">
<!--             <img src="<?php bloginfo('template_url'); ?>/images/global_alert__planting-the-seeds-H025.png"><br>-->
             <?php
                $args = array( 'numberposts' => 1, 'category' => 1, 'order' => 'desc', 'post_status' => 'publish' );
                $data = get_posts( $args );
                if($data){

                 echo   "<div id='header_div'>"
                    . "<a href='".get_permalink( $data[0]->ID )."' > <h4>".$data[0]->post_title." </h4></a> "
                         . "</div>";
                    echo "<div id='content_div'>".$data[0]->post_content  . "</div>";
                    
                }
             ?>
            </div>
            
          </div>
          </td>
        </tr>
        <tr>
          <td>
          <div id="header-menu">
            <ul>             
               <li><a title="Home" href="<?php bloginfo("url"); ?>/">Home</a></li>
               <li><img alt="" src="<?php bloginfo('template_url'); ?>/images/seperator-bar.png"></li>
               <li><a title="About" href="<?php bloginfo("url"); ?>/about-awareness-garden">About</a></li>
               <li><img alt="" src="<?php bloginfo('template_url'); ?>/images/seperator-bar.png"></li>
               <li><a title="Order" href="<?php bloginfo("url"); ?>/order">Order</a></li>
               <li><img alt="" src="<?php bloginfo('template_url'); ?>/images/seperator-bar.png"></li>
               <li><a title="Donate" href="<?php bloginfo("url"); ?>/donation">Donate</a></li>
               <li><img alt="" src="<?php bloginfo('template_url'); ?>/images/seperator-bar.png"></li>
               <li><a title="Programs" href="<?php bloginfo("url"); ?>/programs">Programs</a></li>
               <li><img alt="" src="<?php bloginfo('template_url'); ?>/images/seperator-bar.png"></li>
               <li><a title="Events" href="<?php bloginfo("url"); ?>/events">Events</a></li>
               <li><img alt="" src="<?php bloginfo('template_url'); ?>/images/seperator-bar.png"></li>
               <li><a title="Contact" href="<?php bloginfo("url"); ?>/contact-us">Contact</a></li>
            </ul>   
          </div>
          </td>
        </tr>
      </tbody></table>
    </td>
  </tr>
  </tbody></table>
  </div>
</div>
<?php if (has_post_thumbnail( $post->ID ) ){ ?>
<?php $image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'single-post-thumbnail' ); ?>
<div style="padding:0px 0px 20px; height: 300px; background: url('<?php echo $image[0]; ?>')no-repeat center;" id="header_flash">&nbsp;</div>
<?php }else{ ?>
<div style="padding:0px 0px 20px; height: 300px; background: url('<?php bloginfo('template_url'); ?>/images/header-image-home.jpg') no-repeat center;" id="header_flash">&nbsp;</div>
<?php }?>
<table style="width: 100%;">
    <tbody>
        <tr>
            <td id="contentLeftWrapper" style="width: 205px; vertical-align: top;"><?php get_sidebar(); ?></td>
            <td id="contentMiddleWrapper" style="vertical-align: top; ">

