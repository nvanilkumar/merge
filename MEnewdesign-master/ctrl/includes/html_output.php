<?php
// generate link for the site
  function getHtmlLink($page) {
  	return _HTTP_SITE_ROOT.'/'.$page;
  }
  
  // generate src
  function getHtmlImageSource($imagename) 
  {
  	return _IMAGES_TEMPLATE_URL.$imagename;
  }
  
  // generate a drop down list with Data Query
  function getHtmlDropDownByQuery($items_query, $selected_value='', $params='')
  {
  		$strDropDown	=	"";
		while ($item	=	db_fetch_array($items_query) ) {
			$strDropDown	.=	'<option value="'.$item['value'].'" '.(($item['value']==$selected_value)? ' selected="selected" ':'').$params.' >'.$item['text'].'</option>';
		}
		return $strDropDown;
  }
  
 // generate a drop down list with Data ARRAY
  function getHtmlDropDownByArray($items_array, $selected_value='', $params='')
  {
  		$strDropDown	=	"";
		for ($i=0; $i<count($items_array); $i++ ) 
		{
			$strDropDown	.=	'<option value="'.$items_array[$i]['value'].'" '.(($items_array[$i]['value']==$selected_value)? ' selected="selected" ':'').$params.' >'.$items_array[$i]['text'].'</option>';
		}
		return $strDropDown;
  }	
  
  // generate selection HTML code for checkbox, radio, select tag
  function getHtmlSelection($selected,$list_type)
  {
  		switch ($list_type) { 
		 	case 'checkbox':
			case 'radio':  		
				return ($selected) ? ' checked="checked" ' : ''; 
				break;
			case 'selected':
				return ($selected) ? ' selected="selected" ' : '';			
				break;
				
		}
  }
    
	// get buyer images 
  function getBuyerLogo($buyer_id,$logo_name)
  {
	   return	(trim($logo_name)=='') ? getHtmlImageSource(DEFAULT_BUYER_LOGO_IMAGE): _IMAGE_SITE_URL.'buyers/'.$buyer_id.'/'.$logo_name;
  		 
  }	
  
	// get buyer images 
  function getBuyerPhoto($buyer_id,$photo_name)
  {
	   return	(trim($photo_name)=='') ? getHtmlImageSource(DEFAULT_BUYER_PHOTO_IMAGE): _IMAGE_SITE_URL.'buyers/'.$buyer_id.'/'.$photo_name;
  		 
  }	  
  
	// get buyer images 
  function getProviderLogo($profile_id,$logo_name)
  {
	   return	(trim($logo_name)=='') ? getHtmlImageSource(DEFAULT_PROVIDER_LOGO_IMAGE): _IMAGE_SITE_URL.'providers/'.$profile_id.'/'.$logo_name;
  		 
  }	
  
	// get buyer images 
  function getProviderPhoto($profile_id,$photo_name)
  {
	   return	(trim($photo_name)=='') ? getHtmlImageSource(DEFAULT_PROVIDER_PHOTO_IMAGE): _IMAGE_SITE_URL.'providers/'.$profile_id.'/'.$photo_name;
  		 
  }	    
?>