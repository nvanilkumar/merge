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
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>" />
<title><?php
	/*
	 * Print the <title> tag based on what is being viewed.
	 */
	global $page, $paged;

	wp_title( '|', true, 'right' );

	// Add the blog name.
	bloginfo( 'name' );

	// Add the blog description for the home/front page.
	$site_description = get_bloginfo( 'description', 'display' );
	if ( $site_description && ( is_home() || is_front_page() ) )
		echo " | $site_description";

	// Add a page number if necessary:
	if ( $paged >= 2 || $page >= 2 )
		echo ' | ' . sprintf( __( 'Page %s', 'twentyten' ), max( $paged, $page ) );

	?></title>
<link rel="profile" href="http://gmpg.org/xfn/11" />
<link rel="stylesheet" type="text/css" media="all" href="<?php bloginfo( 'stylesheet_url' ); ?>" />
<link rel="stylesheet" href="<?php bloginfo('template_url'); ?>/css/ad-reset.css" type="text/css" media="screen" />
<link rel="stylesheet" href="<?php bloginfo('template_url'); ?>/css/global.css" type="text/css" media="screen" />
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
<?php
	/* We add some JavaScript to pages with the comment form
	 * to support sites with threaded comments (when in use).
	 */
	if ( is_singular() && get_option( 'thread_comments' ) )
		wp_enqueue_script( 'comment-reply' );

	/* Always have wp_head() just before the closing </head>
	 * tag of your theme, or you will break many plugins, which
	 * generally use this hook to add elements to <head> such
	 * as styles, scripts, and meta tags.
	 */
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
             
              
             
             
              <?php
                $args = array( 'numberposts' => 1, 'category' => 1, 'order' => 'desc', 'post_status' => 'publish' );
                $data = get_posts( $args );
                if($data){
//                 
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
                 <li><a title="Programs" href="<?php bloginfo("url"); ?>/education">Programs</a></li>
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
<div style="padding:0px 0px 20px; height: 300px; background: url('<?php bloginfo('template_url'); ?>/images/header-image-home.jpg') no-repeat center;" id="header_flash">&nbsp;</div>