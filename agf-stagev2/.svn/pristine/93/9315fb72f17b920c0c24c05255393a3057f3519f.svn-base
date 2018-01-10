<?php
/*
 * Plugin Name: Facebook Box
 * Version: 10.0
 * Plugin URI: 
 * Description: Facebook Like Box enables users to: see how many users already like this page.
 * Author: Ram Avtar Sharma
 */
class FacebookBoxWidget extends WP_Widget
{
	/**
	* Declares the FacebookBoxWidget class.
	*
	*/
	function FacebookBoxWidget(){
		$widget_ops = array('classname' => 'widget_FacebookBox', 'description' => __( "Facebook Box enables users to see how many users like this page.") );
		$control_ops = array('width' => 300, 'height' => 300);
		$this->WP_Widget('FacebookBox', __('Facebook Box Widget'), $widget_ops, $control_ops);
	}
	
	/**
	* Displays the Widget
	*
	*/
	function widget($args, $instance){
		extract($args);
		$title = apply_filters('widget_title', empty($instance['title']) ? 'Facebook Box' : $instance['title']);
		
		$pluginDisplayType = empty($instance['pluginDisplayType']) ? 'like_box' : $instance['pluginDisplayType'];
		$layoutMode = empty($instance['layoutMode']) ? 'xfbml' : $instance['layoutMode'];
		$pageURL = empty($instance['pageURL']) ? '' : $instance['pageURL'];
		$fblike_button_style = empty($instance['fblike_button_style']) ? 'standard' : $instance['fblike_button_style'];
		$fblike_button_showFaces = empty($instance['fblike_button_showFaces']) ? 'no' : $instance['fblike_button_showFaces'];
		$fblike_button_verb_to_display = empty($instance['fblike_button_verb_to_display']) ? 'recommend' : $instance['fblike_button_verb_to_display'];
		$fblike_button_font = empty($instance['fblike_button_font']) ? 'lucida grande' : $instance['fblike_button_font'];
		$fblike_button_width = empty($instance['fblike_button_width']) ? '292' : $instance['fblike_button_width'];
		$fblike_button_colorScheme = empty($instance['fblike_button_colorScheme']) ? 'light' : $instance['fblike_button_colorScheme'];
		
		$pageID = empty($instance['pageID']) ? '' : $instance['pageID'];
		$connection = empty($instance['connection']) ? '10' : $instance['connection'];
		$width = empty($instance['width']) ? '292' : $instance['width'];
		$height = empty($instance['height']) ? '255' : $instance['height'];
		$streams = empty($instance['streams']) ? 'yes' : $instance['streams'];
		$colorScheme = empty($instance['colorScheme']) ? 'light' : $instance['colorScheme'];
		$borderColor = empty($instance['borderColor']) ? 'AAAAAA' : $instance['borderColor'];
		$showFaces = empty($instance['showFaces']) ? 'yes' : $instance['showFaces'];
		$header = empty($instance['header']) ? 'yes' : $instance['header'];
		
		if ($fblike_button_showFaces == "yes") {
			$fblike_button_showFaces == "true";			
		} else {
			$fblike_button_showFaces == "false";
		}		
		if ($showFaces == "yes") {
			$showFaces = "true";			
		} else {
			$showFaces = "false";
		}
		if ($streams == "yes") {
			$streams = "true";
			$height = $height + 300;
		} else {
			$streams = "false";
		}
		if ($header == "yes") {
			$header = "true";
			$height = $height + 32;
		} else {
			$header = "false";
		}

		# Before the widget
		echo $before_widget;
				
		//this is to check for backward compatibility, previous version all is using Page ID instead of Page URL
		//If Page URL is filled, we will use it
		$isUsingPageURL = false;
		if (strlen($pageURL) > 23) {	
			$isUsingPageURL = true;  //flag to be used for backward
			$like_box_iframe = "<iframe src=\"https://www.facebook.com/plugins/likebox.php?href=$pageURL&amp;width=$width&amp;colorscheme=$colorScheme&amp;border_color=$borderColor&amp;show_faces=$showFaces&amp;connections=$connection&amp;stream=$streams&amp;header=$header&amp;height=$height\" scrolling=\"no\" frameborder=\"0\" style=\"border:none; overflow:hidden; width:" . $width . "px; height:" . $height . "px;\" allowTransparency=\"true\"></iframe>";
			$like_box_xfbml = "<script src=\"https://connect.facebook.net/en_US/all.js#xfbml=1\"></script><fb:like-box href=\"$pageURL\" width=\"$width\" show_faces=\"$showFaces\" border_color=\"$borderColor\" stream=\"$streams\" header=\"$header\"></fb:like-box>";
		} else {
			$like_box_iframe = "<iframe src=\"https://www.facebook.com/plugins/likebox.php?id=$pageID&amp;width=$width&amp;colorscheme=$colorScheme&amp;border_color=$borderColor&amp;show_faces=$showFaces&amp;connections=$connection&amp;stream=$streams&amp;header=$header&amp;height=$height\" scrolling=\"no\" frameborder=\"0\" style=\"border:none; overflow:hidden; width:" . $width . "px; height:" . $height . "px;\" allowTransparency=\"true\"></iframe>";
			$like_box_xfbml = "<script src=\"https://connect.facebook.net/en_US/all.js#xfbml=1\"></script><fb:like-box id=\"$pageID\" width=\"$width\" show_faces=\"$showFaces\" border_color=\"$borderColor\" stream=\"$streams\" header=\"$header\"></fb:like-box>";		
		}
		$like_button_xfbml  = "<script src=\"https://connect.facebook.net/en_US/all.js#xfbml=1\"></script><fb:like layout=\"$fblike_button_style\" show_faces=\"$fblike_button_showFaces\" width=\"$fblike_button_width\" action=\"$fblike_button_verb_to_display\" font=\"$fblike_button_font\" colorscheme=\"$fblike_button_colorScheme\"></fb:like>";

		switch ($pluginDisplayType) {
			case 'like_box' :
				if (strcmp($layoutMode, "iframe") == 0) {
					$renderedHTML = $like_box_iframe;
				} else {
					$renderedHTML = $like_box_xfbml;
				}
				break;
			case 'like_button' :
				$renderedHTML = $like_button_xfbml;
				break;
			case 'both':
				if (strcmp($layoutMode, "iframe") == 0) {
					$renderedHTML = $like_box_iframe;
				} else {
					$renderedHTML = $like_box_xfbml;
				}
				$renderedHTML = $renderedHTML . "\n" . $like_button_xfbml;
				break;
		}
		echo $renderedHTML;
		
		# After the widget
		//echo $after_widget;
	}
	
	/**
	* Saves the widgets settings.
	*
	*/
	function update($new_instance, $old_instance){
		$instance = $old_instance;
		$instance['title'] = strip_tags(stripslashes($new_instance['title']));
		$instance['pageID'] = strip_tags(stripslashes($new_instance['pageID']));
		$instance['connection'] = strip_tags(stripslashes($new_instance['connection']));
		$instance['width'] = strip_tags(stripslashes($new_instance['width']));
		$instance['height'] = strip_tags(stripslashes($new_instance['height']));
		$instance['creditOn'] = strip_tags(stripslashes($new_instance['creditOn']));
		$instance['header'] = strip_tags(stripslashes($new_instance['header']));
		$instance['streams'] = strip_tags(stripslashes($new_instance['streams']));   
		$instance['colorScheme'] = strip_tags(stripslashes($new_instance['colorScheme']));
		$instance['borderColor'] = strip_tags(stripslashes($new_instance['borderColor']));
		$instance['showFaces'] = strip_tags(stripslashes($new_instance['showFaces']));
		
		$instance['pluginDisplayType'] = strip_tags(stripslashes($new_instance['pluginDisplayType']));
		$instance['layoutMode'] = strip_tags(stripslashes($new_instance['layoutMode']));
		$instance['pageURL'] = strip_tags(stripslashes($new_instance['pageURL']));
		$instance['fblike_button_style'] = strip_tags(stripslashes($new_instance['fblike_button_style']));
		$instance['fblike_button_showFaces'] = strip_tags(stripslashes($new_instance['fblike_button_showFaces']));
		$instance['fblike_button_verb_to_display'] = strip_tags(stripslashes($new_instance['fblike_button_verb_to_display']));
		$instance['fblike_button_font'] = strip_tags(stripslashes($new_instance['fblike_button_font']));
		$instance['fblike_button_width'] = strip_tags(stripslashes($new_instance['fblike_button_width']));
		$instance['fblike_button_colorScheme'] = strip_tags(stripslashes($new_instance['fblike_button_colorScheme']));
		
		return $instance;
	}
	
	/**
	* Creates the edit form for the widget.
	*
	*/
	function form($instance){
		//Defaults
		$instance = wp_parse_args( (array) $instance, array('title'=>'', 'pageID'=>'116947191652175', 'height'=>'250', 'width'=>'255', 'connection'=>'6', 'streams'=>'yes', 'colorScheme'=>'light', 'showFaces'=>'yes', 'borderColor'=>'AAAAAA','header'=>'yes', 'creditOn'=>'no', 'pluginDisplayType'=>'like_box', 'layoutMode'=>'xfbml', 'pageURL'=>'https://www.facebook.com/pages/Awareness-Garden/116947191652175', 'fblike_button_style'=>'standard', 'fblike_button_showFaces'=>'false','fblike_button_verb_to_display'=>'recommend','fblike_button_font'=>'arial', 'fblike_button_width'=>'292','fblike_button_colorScheme'=>'light') );
		
		
		$title = htmlspecialchars($instance['title']);		
		$pluginDisplayType = empty($instance['pluginDisplayType']) ? 'like_box' : $instance['pluginDisplayType'];
		$layoutMode = empty($instance['layoutMode']) ? 'xfbml' : $instance['layoutMode'];
		$pageURL = empty($instance['pageURL']) ? 'https://www.facebook.com/pages/...' : $instance['pageURL'];
		$fblike_button_style = empty($instance['fblike_button_style']) ? 'standard' : $instance['fblike_button_style'];
		$fblike_button_showFaces = empty($instance['fblike_button_showFaces']) ? 'no' : $instance['fblike_button_showFaces'];
		$fblike_button_verb_to_display = empty($instance['fblike_button_verb_to_display']) ? 'recommend' : $instance['fblike_button_verb_to_display'];
		$fblike_button_font = empty($instance['fblike_button_font']) ? 'lucida grande' : $instance['fblike_button_font'];
		$fblike_button_width = empty($instance['fblike_button_width']) ? '292' : $instance['fblike_button_width'];
		$fblike_button_colorScheme = empty($instance['fblike_button_colorScheme']) ? 'light' : $instance['fblike_button_colorScheme'];		
		$pageID = empty($instance['pageID']) ? '' : $instance['pageID'];
		$connection = empty($instance['connection']) ? '10' : $instance['connection'];
		$width = empty($instance['width']) ? '292' : $instance['width'];
		$height = empty($instance['height']) ? '255' : $instance['height'];
		$streams = empty($instance['streams']) ? 'yes' : $instance['streams'];
		$colorScheme = empty($instance['colorScheme']) ? 'yes' : $instance['colorScheme'];
		$borderColor = empty($instance['borderColor']) ? 'AAAAAA' : $instance['borderColor'];
		$showFaces = empty($instance['showFaces']) ? 'yes' : $instance['showFaces'];
		$header = empty($instance['header']) ? 'yes' : $instance['header'];
		
		$pageID = htmlspecialchars($instance['pageID']);
		$connection = htmlspecialchars($instance['connection']);
		$streams = htmlspecialchars($instance['streams']);
		$colorScheme = htmlspecialchars($instance['colorScheme']);
		$borderColor = htmlspecialchars($instance['borderColor']);
		$showFaces = htmlspecialchars($instance['showFaces']);
		$header = htmlspecialchars($instance['header']);
		
		$pluginDisplayType = htmlspecialchars($instance['pluginDisplayType']);
		$layoutMode = htmlspecialchars($instance['layoutMode']);
		$pageURL = htmlspecialchars($instance['pageURL']);
		$fblike_button_style = htmlspecialchars($instance['fblike_button_style']);
		$fblike_button_showFaces = htmlspecialchars($instance['fblike_button_showFaces']);
		$fblike_button_verb_to_display = htmlspecialchars($instance['fblike_button_verb_to_display']);
		$fblike_button_font = htmlspecialchars($instance['fblike_button_font']);
		$fblike_button_width = htmlspecialchars($instance['fblike_button_width']);
		$fblike_button_colorScheme = htmlspecialchars($instance['fblike_button_colorScheme']);
		
		
				
		# Output the options
		echo '<p style="text-align:right;"><label for="' . $this->get_field_name('title') . '">' . __('Title:') . ' <input style="width: 250px;" id="' . $this->get_field_id('title') . '" name="' . $this->get_field_name('title') . '" type="text" value="' . $title . '" /></label></p>';
		# Fill Display Type Selection
		echo '<p style="text-align:right;"><label for="' . $this->get_field_name('pluginDisplayType') . '">' . __('Display:') . ' <select name="' . $this->get_field_name('pluginDisplayType')  . '" id="' . $this->get_field_id('pluginDisplayType')  . '">"';
?>
		<option value="like_box" <?php if ($pluginDisplayType == 'like_box') echo 'selected="yes"'; ?> >Like Box</option>
		<option value="like_button" <?php if ($pluginDisplayType == 'like_button') echo 'selected="yes"'; ?> >Like Button</option>			 
		<option value="both" <?php if ($pluginDisplayType == 'both') echo 'selected="yes"'; ?> >Like Box &amp; Button</option>			 
<?php
		echo '</select></label>';
		# Fill Layout Mode Selection
		echo '<p style="text-align:right;"><label for="' . $this->get_field_name('layoutMode') . '">' . __('Render Mode:') . ' <select name="' . $this->get_field_name('layoutMode')  . '" id="' . $this->get_field_id('layoutMode')  . '">"';
?>
		<!--- <option value="iframe" <?php if ($layoutMode == 'iframe') echo 'selected="yes"'; ?> >IFRAME</option> --->
		<option value="xfbml" <?php if ($layoutMode == 'xfbml') echo 'selected="yes"'; ?> >XFBML</option>		
<?php
		echo '</select></label>';
		echo '<hr/><p style="text-align:left;"><b>Like Box Setting</b></p>';
		echo '<p style="text-align:left;"><i>Fill Page ID Or Page URL below:</i></p>';
		# Fill Page ID
		echo '<p style="text-align:right;"><label for="' . $this->get_field_name('pageID') . '">' . __('Facebook Page ID:') . ' <input style="width: 150px;" id="' . $this->get_field_id('pageID') . '" name="' . $this->get_field_name('pageID') . '" type="text" value="' . $pageID . '" /></label></p>';
		# Fill Page URL
		echo '<p style="text-align:right;"><label for="' . $this->get_field_name('pageURL') . '">' . __('Facebook Page URL:') . ' <input style="width: 150px;" id="' . $this->get_field_id('pageURL') . '" name="' . $this->get_field_name('pageURL') . '" type="text" value="' . $pageURL . '" /></label></p>';
		
		# Connection
		echo '<p style="text-align:right;"><label for="' . $this->get_field_name('connection') . '">' . __('Connections:') . ' <input style="width: 100px;" id="' . $this->get_field_id('connection') . '" name="' . $this->get_field_name('connection') . '" type="text" value="' . $connection . '" /></label></p>';
		# Width
		echo '<p style="text-align:right;"><label for="' . $this->get_field_name('width') . '">' . __('Width:') . ' <input style="width: 100px;" id="' . $this->get_field_id('width') . '" name="' . $this->get_field_name('width') . '" type="text" value="' . $width . '" /></label></p>';
		# Height
		echo '<p style="text-align:right;"><label for="' . $this->get_field_name('height') . '">' . __('Height:') . ' <input style="width: 100px;" id="' . $this->get_field_id('height') . '" name="' . $this->get_field_name('height') . '" type="text" value="' . $height . '" /></label></p>';		
		# Fill Streams Selection
		echo '<p style="text-align:right;"><label for="' . $this->get_field_name('streams') . '">' . __('Streams:') . ' <select name="' . $this->get_field_name('streams')  . '" id="' . $this->get_field_id('streams')  . '">"';
?>
		<option value="yes" <?php if ($streams == 'yes') echo 'selected="yes"'; ?> >Yes</option>
		<option value="no" <?php if ($streams == 'no') echo 'selected="yes"'; ?> >No</option>			 
<?php
		echo '</select></label>';
# Fill Color Scheme Selection
		echo '<p style="text-align:right;"><label for="' . $this->get_field_name('colorScheme') . '">' . __('Color Scheme:') . ' <select name="' . $this->get_field_name('colorScheme')  . '" id="' . $this->get_field_id('colorScheme')  . '">"';
?>
		<option value="light" <?php if ($colorScheme == 'light') echo 'selected="yes"'; ?> >Light</option>
		<option value="dark" <?php if ($colorScheme == 'dark') echo 'selected="yes"'; ?> >Dark</option>			 
<?php
		echo '</select></label>';
		# Border Color
		echo '<p style="text-align:right;"><label for="' . $this->get_field_name('borderColor') . '">' . __('Border Color:') . ' <input style="width: 100px;" id="' . $this->get_field_id('borderColor') . '" name="' . $this->get_field_name('borderColor') . '" type="text" value="' . $borderColor . '" /></label></p>';
# Fill Show Faces Selection
		echo '<p style="text-align:right;"><label for="' . $this->get_field_name('showFaces') . '">' . __('Show Faces:') . ' <select name="' . $this->get_field_name('showFaces')  . '" id="' . $this->get_field_id('showFaces')  . '">"';
?>
		<option value="yes" <?php if ($showFaces == 'yes') echo 'selected="yes"'; ?> >Yes</option>
		<option value="no" <?php if ($showFaces == 'no') echo 'selected="yes"'; ?> >No</option>			 
<?php
		echo '</select></label>';
	# Fill header Selection
		echo '<p style="text-align:right;"><label for="' . $this->get_field_name('header') . '">' . __('Header:') . ' <select name="' . $this->get_field_name('header')  . '" id="' . $this->get_field_id('header')  . '">"';
?>
		<option value="yes" <?php if ($header == 'yes') echo 'selected="yes"'; ?> >Yes</option>
		<option value="no" <?php if ($header == 'no') echo 'selected="yes"'; ?> >No</option>			 
<?php
		echo '</select></label>';	
		echo '<hr/><p style="text-align:left;"><b>Like Button Setting</b></p>';
		# Fill Like Button Style Selection
		echo '<p style="text-align:right;"><label for="' . $this->get_field_name('fblike_button_style') . '">' . __('Button Style:') . ' <select name="' . $this->get_field_name('fblike_button_style')  . '" id="' . $this->get_field_id('fblike_button_style')  . '">"';
?>
		<option value="standard" <?php if ($fblike_button_style == 'standard') echo 'selected="yes"'; ?> >standard</option>
		<option value="button_count" <?php if ($fblike_button_style == 'button_count') echo 'selected="yes"'; ?> >button_count</option>		
		<option value="box_count" <?php if ($fblike_button_style == 'box_count') echo 'selected="yes"'; ?> >box_count</option>		
<?php
		echo '</select></label>';
		# Fill Verb To Display Selection
		echo '<p style="text-align:right;"><label for="' . $this->get_field_name('fblike_button_verb_to_display') . '">' . __('Verb To Display:') . ' <select name="' . $this->get_field_name('fblike_button_verb_to_display')  . '" id="' . $this->get_field_id('fblike_button_verb_to_display')  . '">"';
?>
		<option value="like" <?php if ($fblike_button_verb_to_display == 'like') echo 'selected="yes"'; ?> >like</option>
		<option value="recommend" <?php if ($fblike_button_verb_to_display == 'recommend') echo 'selected="yes"'; ?> >recommend</option>				
<?php
		echo '</select></label>';
		# Like Button Width
		echo '<p style="text-align:right;"><label for="' . $this->get_field_name('fblike_button_width') . '">' . __('Width:') . ' <input style="width: 100px;" id="' . $this->get_field_id('fblike_button_width') . '" name="' . $this->get_field_name('fblike_button_width') . '" type="text" value="' . $fblike_button_width . '" /></label></p>';
		# Fill Like Button Color Scheme Selection
		echo '<p style="text-align:right;"><label for="' . $this->get_field_name('fblike_button_colorScheme') . '">' . __('Color Scheme:') . ' <select name="' . $this->get_field_name('fblike_button_colorScheme')  . '" id="' . $this->get_field_id('fblike_button_colorScheme')  . '">"';
?>
		<option value="light" <?php if ($fblike_button_colorScheme == 'light') echo 'selected="yes"'; ?> >Light</option>
		<option value="dark" <?php if ($fblike_button_colorScheme == 'dark') echo 'selected="yes"'; ?> >Dark</option>			 
<?php
		echo '</select></label>';
# Fill Like Button Show Faces Selection
		echo '<p style="text-align:right;"><label for="' . $this->get_field_name('fblike_button_showFaces') . '">' . __('Show Faces:') . ' <select name="' . $this->get_field_name('fblike_button_showFaces')  . '" id="' . $this->get_field_id('fblike_button_showFaces')  . '">"';
?>
		<option value="yes" <?php if ($fblike_button_showFaces == 'yes') echo 'selected="yes"'; ?> >Yes</option>
		<option value="no" <?php if ($fblike_button_showFaces == 'no') echo 'selected="yes"'; ?> >No</option>			 
<?php
		echo '</select></label>';
		# Fill Like Button Font Selection
		echo '<p style="text-align:right;"><label for="' . $this->get_field_name('fblike_button_font') . '">' . __('Font:') . ' <select name="' . $this->get_field_name('fblike_button_font')  . '" id="' . $this->get_field_id('fblike_button_font')  . '">"';
?>
		<option value="arial" <?php if ($fblike_button_font == 'arial') echo 'selected="yes"'; ?> >arial</option>
		<option value="lucida grande" <?php if ($fblike_button_font == 'lucida grande') echo 'selected="yes"'; ?>>lucida grande</option>	
		<option value="tahoma" <?php if ($fblike_button_font == 'tahoma') echo 'selected="yes"'; ?> >tahoma</option>	
		<option value="verdana" <?php if ($fblike_button_font == 'verdana') echo 'selected="yes"'; ?> >verdana</option>	
<?php
		echo '</select></label>';
		echo '<p/>';
		echo '<hr/>';	
	} //end of form

}// END class
	
	/**
	* Register  widget.
	*
	* Calls 'widgets_init' action after widget has been registered.
	*/
	function FacebookBoxInit() {
	register_widget('FacebookBoxWidget');
	}	
	add_action('widgets_init', 'FacebookBoxInit');
?>