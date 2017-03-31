<?php
/**
 * Blog related business logic will be defined in this class
 *
 * @package		CodeIgniter
 * @author		Qison  Dev Team
 * @copyright	Copyright (c) 2015, Meraevents.
 * @Version		Version 1.0
 * @Since       Class available since Release Version 1.0 
 * @Created     16-06-2015
 * @Last Modified 16-06-2015
 */

require_once(APPPATH . 'handlers/handler.php');

class Blog_handler extends Handler {

    var $ci;
	
    public function __construct() 
	{
		parent::__construct();
		$this->ci = parent::$CI;
    }
	
	/*
    * Function to get the Blog list with category data
    *
    * @access	public
    * @param	$inputArray contains
    * 				xml feed data from the blog
    * @return	array
    */
	function getBlogData() {
		if ($this->ci->config->item('memcacheEnabled')) {
			$this->ci->load->library('memcached_library');
			$memcacheKey = BLOG_MEMCACHE;
			$cacheResults = $this->ci->memcached_library->get($memcacheKey);
			if ($cacheResults != FALSE && count($cacheResults)>0) {// Data is not availeble in memcache.
				return $cacheResults;
			}
		}
        $curl = curl_init();
        curl_setopt_array($curl, Array(
            CURLOPT_URL => 'http://blog.meraevents.com/feed/',
            CURLOPT_RETURNTRANSFER => TRUE,
            CURLOPT_ENCODING => 'UTF-8',
            CURLOPT_TIMEOUT => 60
        ));
        $data = curl_exec($curl);
        curl_close($curl);
        $xml = simplexml_load_string($data, null, LIBXML_NOCDATA);
        $loopCount = 0;
        foreach ($xml->channel->item as $item) {
            if ($loopCount < 3) {
                $categoryArray = json_decode(json_encode($item->category), TRUE);
                if (count($categoryArray) > 0) {
                    $blogDataArray[$loopCount]['category_name'] = $categoryArray[0];
					$categoryData ['name'] = 'Professional';
                    $categoryData ['themecolor'] = '#33A4B1';
                    $blogDataArray[$loopCount]['categoryData'] = $categoryData;
                }
                $titleTrimmed = substr($item->title, 0, 50);
                if (strlen($item->title) > 50) {
                    $titleTrimmed = $titleTrimmed . '..';
                }
                $blogDataArray[$loopCount]['title'] = $titleTrimmed;
                $blogDataArray[$loopCount]['link'] = (string) $item->link[0];

                $descriptionFull = $item->description;
                // Getting the Description image src
                preg_match('@src="([^"]+)"@', $descriptionFull, $backgroundImageArray);
                // Removing the Image from description
                $descriptionWithoutImg = trim(preg_replace("/<img[^>]+\>/i", " ", $descriptionFull));
                // Removing the `p` tag from description
                $descriptionWithoutImg = trim(preg_replace("/<p>/i", " ", $descriptionWithoutImg));
                // Get the first 100 characters of the description
                $descriptionTrimmed = substr($descriptionWithoutImg, 0, 200);
                if (strlen($descriptionWithoutImg) > 200) {
                    $descriptionTrimmed = $descriptionTrimmed . '..';
                }
                $blogDataArray[$loopCount]['description'] = $descriptionTrimmed;
                if (count($backgroundImageArray) > 0) {
                    $blogDataArray[$loopCount]['background_image'] = $backgroundImageArray[1];
                }
                $loopCount++;
            } else {
                break;
            }
        }
        if (count($blogDataArray) > 0) {
        	if ($this->ci->config->item('memcacheEnabled')) {
        		$memcacheKey = BLOG_MEMCACHE;
        		$this->ci->load->library('memcached_library');
        		$this->ci->memcached_library->add(BLOG_MEMCACHE, $blogDataArray,BLOG_MEMCACHEEXPIRYTIME);
       		}
       		return $blogDataArray;
        } else { 
            $output =array();
            return $output;
        }
    }
    
}

