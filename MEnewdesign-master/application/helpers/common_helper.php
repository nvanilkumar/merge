<?php

// Function to get the client IP address
function commonHelperGetClientIp() {
    $ipAddress = '';
    if (getenv('HTTP_X_FORWARDED_FOR'))
        $ipAddress = getenv('HTTP_X_FORWARDED_FOR');
    else
        $ipAddress = $_SERVER['REMOTE_ADDR'];
    return $ipAddress;
}

// Function for HTTPS Url

function https_url(){
	return  str_replace('http','https',site_url());
}

function commonHelperGetIdArray($input, $groupByKey = 'id') {
    $returnArray = array();
    if (count($input) > 0) {
        foreach ($input as $key => $val) {
            $keyname = $val[$groupByKey];
            foreach ($val as $id => $value) {
                if ($id == $groupByKey)
                    $keyname = $value;
                $returnArray[$keyname][$id] = $value;
            }
        }
    }
    return $returnArray;
}

 function eventType($eventType) {
 	$eventType = strtolower($eventType);
        $eventTypeValues = array();
        switch ($eventType) {
            case 'paid':
                $eventTypeValues['registrationType'] = 2;
                break;
            case 'free':
                $eventTypeValues['registrationType'] = 1;
                break;
            case 'webinar':
                $eventTypeValues['eventMode'] = 1;
                $eventTypeValues['registrationType'] = 4;
                break;
            case 'nonwebinar':
                $eventTypeValues['eventMode'] = 0;
                $eventTypeValues['registrationType'] = 4;
                break;
            case 'noreg':
                $eventTypeValues['registrationType'] = 3;
                break;
            case 'info only':
           $eventTypeValues['registrationType']= 3 ;
               break;
            default:
                $eventTypeValues[] = '';
                break;
        }
        return $eventTypeValues;
    }

//function to get tinyurl for long URL
function getTinyUrl($url) {
    $ch = curl_init();
    $timeout = 5;
    curl_setopt($ch, CURLOPT_URL, 'http://tinyurl.com/api-create.php?url=' . $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
    $data = curl_exec($ch);
    curl_close($ch);
    return $data;
}

// function to get customFilterArray
function commonHelperCustomFilterArray() {
    $filterArray = array(0 => array("id" => 1, "name" => 'today', "value" => 'today'),
        1 => array("id" => 2, "name" => 'tomorrow', "value" => 'tomorrow'),
        2 => array("id" => 3, "name" => 'this week', "value" => 'this-week'),
        3 => array("id" => 4, "name" => 'this weekend', "value" => 'this-weekend'),
        4 => array("id" => 5, "name" => 'this month', "value" => 'this-month'),
        5 => array("id" => 6, "name" => 'all time', "value" => 'all-time'),
        6 => array("id" => 7, "name" => 'Custom Date', "value" => 'custom-date')
    );

    return $filterArray;
}

function commonHelperEventDetailUrl($url) {
    return site_url("event/" . $url);
}

function site_url_get() {
    $protocol = strpos(strtolower($_SERVER['SERVER_PROTOCOL']), 'https') === FALSE ? 'http' : 'https';
    $host = $_SERVER['HTTP_HOST'];
    $currentUrl = $protocol . '://' . $host . '/';
    return $currentUrl;
}

//To get the pick a theme related shor/thumb/banner images array
//@image_path: site url of the project    
function get_theme_images_array($image_path) {
    $theme_images = array();
    $image_path = $image_path . 'picktheme/';
//    $theme_images[0]['short'] = $image_path . "short1.jpg";
//    $theme_images[0]['thumb'] = $image_path . "thumb1.jpg";
//    $theme_images[0]['banner'] = $image_path . "banner1.jpg";
//    $theme_images[0]['theam'] =  "Music";
    
    $theme_images[1]['short'] =$image_path . "entertainment-short.jpg";
    $theme_images[1]['thumb'] = $image_path . "Entertainment-thumb.jpg";
    $theme_images[1]['banner'] = $image_path . "Entertainment.jpg";
    $theme_images[1]['theam'] =  "Entertainment";
    
    $theme_images[2]['short'] =$image_path . "campus-short.jpg";
    $theme_images[2]['thumb'] = $image_path . "campus-thumbnail.jpg";
    $theme_images[2]['banner'] = $image_path . "campus-banner.jpg";
    $theme_images[2]['theam'] =  "Campus";
    
    $theme_images[3]['short'] =$image_path . "professional-short.jpg";
    $theme_images[3]['thumb'] = $image_path . "Professional-thumb.jpg";
    $theme_images[3]['banner'] = $image_path . "Professional.jpg";
    $theme_images[3]['theam'] =  "Professional";
    
    $theme_images[4]['short'] =$image_path . "spiritual-short.jpg";
    $theme_images[4]['thumb'] = $image_path . "Spiritual-thumb.jpg";
    $theme_images[4]['banner'] = $image_path . "Spiritual.jpg";
    $theme_images[4]['theam'] =  "Spiritual";
    
    $theme_images[5]['short'] =$image_path . "sports-short.jpg";
    $theme_images[5]['thumb'] = $image_path . "Sports-thumb.jpg";
    $theme_images[5]['banner'] = $image_path . "Sports.jpg";
    $theme_images[5]['theam'] =  "Sports";
    
    $theme_images[6]['short'] =$image_path . "tradeshows-short.jpg";
    $theme_images[6]['thumb'] = $image_path . "TradeShows-thumb.jpg";
    $theme_images[6]['banner'] = $image_path . "TradeShows.jpg";
    $theme_images[6]['theam'] =  "TradeShows";
    
    $theme_images[7]['short'] =$image_path . "training-short.jpg";
    $theme_images[7]['thumb'] = $image_path . "training-thumbnail.jpg";
    $theme_images[7]['banner'] = $image_path . "training-banner.jpg";
    $theme_images[7]['theam'] =  "Training";
    
//    $theme_images[8]['short'] =$image_path . "spcl-short.jpg";
//    $theme_images[8]['thumb'] = $image_path . "spcl-t.jpg";
//    $theme_images[8]['banner'] = $image_path . "spcl.jpg";
//    $theme_images[8]['theam'] =  "SpecialOccasion";
    
//    $theme_images[9]['short'] =$image_path . "new-year-short.jpg";
//    $theme_images[9]['thumb'] = $image_path . "newyear-t.jpg";
//    $theme_images[9]['banner'] = $image_path . "newyear.jpg";
//    $theme_images[9]['theam'] =  "NewYear";

    return $theme_images;
}

function dateFormate($date, $separator) {
    $dates = explode($separator, $date);

    $month = $dates[0];
    $day = $dates[1];
    $year = $dates[2];

    $finalDate = $year . '-' . $month . '-' . $day;
    return $finalDate;
}

//To get the login user id
function getUserId() {
    $ci = &get_instance();
    $userId = $ci->customsession->getUserId();
    if ($userId) {
        return $userId;
    }
}
// To get the Dashboard Url
function getDashboardUrl(){
	require_once(APPPATH . 'handlers/dashboard_handler.php');
	$dashboardHandler = new Dashboard_handler();
	$ci = &get_instance();
	$inputArray['loginredirectCheck']=true;
	if($ci->uri->segment(1)=='confirmation'){
		return commonHelperGetPageUrl('user-attendeeview-current');
	}
       
	$userCurrentevents = $dashboardHandler->getUpcomingPastEventsCount();
        //echo 'url1';print_r($userCurrentevents); echo 'url1';
	if($userCurrentevents['status'] && isset($userCurrentevents['response']['upcomingEventsCount'])){
		return $redirectUrl = commonHelperGetPageUrl('dashboard-myevent');
	}
	if($userCurrentevents['status'] && isset($userCurrentevents['response']['pastEventCount'])){
		$redirectUrl = commonHelperGetPageUrl('dashboard-pastevent');
		return $redirectUrl;
	}
}

//To get the attend view URl
function getAttendeeUrl() {
    require_once(APPPATH . 'handlers/profile_handler.php');
    $profileHandler = new Profile_handler();
    $ci = &get_instance();
    $redirectUrl = commonHelperGetPageUrl('user-attendeeview-past');
    $inputArray['ticketType'] = 'current';
    $currentTickets = $profileHandler->getUserTicketListCount($inputArray);
    if ($currentTickets['status'] && $currentTickets['response']['total'] > 0) {
            $redirectUrl = commonHelperGetPageUrl('user-attendeeview-current');
    }
    return $redirectUrl;
}

//To get the promoter view URl
function getPromoterViewUrl(){
	require_once(APPPATH . 'handlers/promoter_handler.php');
	$promoterHandler = new Promoter_handler();
	$ci = &get_instance();
	$userCurrentevents = $promoterHandler->getUpcomingPastPromoterEventsCount();
	if($userCurrentevents['status'] && isset($userCurrentevents['response']['upcomingPromoterEventsCount'])){
		return $redirectUrl = commonHelperGetPageUrl('user-promoterview-current');
	}
	if($userCurrentevents['status'] && isset($userCurrentevents['response']['pastPromoterEventsCount'])){
		return $redirectUrl = commonHelperGetPageUrl('user-promoterview-past');
	}
}

// date comparison function --date should be (02/07/2015 15:38) formate
function dateCompare($date, $time, $compareDate = "", $compareTime = "") {
    $compareDateTime = $compareDate . ' ' . $compareTime;
    if ($compareDate == "" && $compareTime == "") {
        $compareDateTime = date("m/d/Y H:i");
    }
    $dateTime = $date . ' ' . $time;
    $dateTime = strtotime($dateTime);
    $compareDateTime = strtotime($compareDateTime);

    if ($compareDateTime < $dateTime) {
        return true;
    } else {
        return false;
    }
}

// Data Value validation

function dateValidation($date, $separator = '/') {
    if (count(explode($separator, $date)) == 3) {
        $pattern = "@([0-9]{2})" . $separator . "([0-9]{2})" . $separator . "([0-9]{4})@";
        if (preg_match($pattern, $date, $parts)) {
            if (checkdate($parts[1], $parts[2], $parts[3]))
                return TRUE;
            else
                return FALSE;
        }
    }
}

//date time validation
function checkDateTime($data) {
    if (date('Y-m-d H:i:s', strtotime($data)) == $data) {
        return true;
    } else {
        return false;
    }
}
/**
 * Append current time stamp to passed file name
 * @Parm fileName
 */
function appendTimeStamp($fileName) {
    $currentTime = strtotime("now");
    $path_parts = pathinfo($fileName);
    $path_parts['filename']=cleanUrl($path_parts['filename']);
    $newFileName = $path_parts['filename'] . $currentTime . "." . $path_parts['extension'];
    return $newFileName;
}

// function to get Change sale button title
function saleButtonTitle() {
    $filterArray = array(0 => array("id" => 1, "name" => 'Register Now'),
        1 => array("id" => 2, "name" => 'Book Now'),
        2 => array("id" => 3, "name" => 'Donate'),
    );

    return $filterArray;
}

function commonHelperDefaultImage($path, $type) {
    if (strlen($path) > 1) {
        return $path;
    } else { 
    	$image = "";
    	$ci = &get_instance();
            switch ($type) {
                case 'eventlogo':
                    $image = $ci->config->item('images_content_path') . "eventlogo/defaulteventlogo.jpg";
                    break;
                case 'topbanner':
                    $image = $ci->config->item('images_content_path') . "banners/defaulteventbanner.jpg";
                case 'userprofile':
                    $image = $ci->config->item('images_static_path') . DEFAULT_PROFILE_IMAGE;
            }
            return $image;
        }
    }


function commonHelperCheckUrl($url) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    // don't download content
    curl_setopt($ch, CURLOPT_NOBODY, 1);
    curl_setopt($ch, CURLOPT_FAILONERROR, 1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    if (curl_exec($ch) !== FALSE) {
        return true;
    } else {
        return false;
    }
}

/**
 * Extract date in mm/dd/YYY format from dd/mm/yy h:m:s
 */
function extractDate($dateString) {
    return date("m/d/Y", strtotime($dateString));
}

/**
 * Extract time in h:m:s format from dd/mm/yy h:m:s
 */
function extractTime($dateString) {
    return date("H:i:s", strtotime($dateString));
}

function commonHelpergenerateRandomString($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $randomString;
}

/**
 * To return the mathed key values array
 */
function arrayComparison($arrayList, $ticketId) {
    foreach ($arrayList as $key => $value) {
        if ($value['id'] == $ticketId) {
            return $value;
        }
    }
    return FALSE;
}

function commonHelperGetEventType($eventType) {
    $eventTypeValues = array();
    switch ($eventType) {
        case 'paid' :
            $eventTypeValues['registrationType'] = 2;
            break;
        case 'free' :
            $eventTypeValues['registrationType'] = 1;
            break;
        case 'webinar' :
            $eventTypeValues['eventMode'] = 1;
            break;
        //            case 'topselling':
        //                    $eventTypeValues['']='';
        //                break;
        default :
            $eventTypeValues[] = '';
            break;
    }
    return $eventTypeValues;
}

//To remove the script tags from the text
function removeScriptTag($removeText) {
    $removeText = str_replace('<script>', ' ', $removeText);
    $removeText = str_replace('</script>', ' ', $removeText);
    return $removeText;
}

//To encrypt the password 
function encryptPassword($passwordString) {
    return md5($passwordString);
}

function commonHelperGetPageUrl($pageName, $params = "", $getParams = "") {
    $pageUrls = array();
    $pageSiteUrl = site_url();
    $pageUrls['home'] = $pageSiteUrl;
    $pageUrls['dashboard-myevent'] = $pageSiteUrl . "dashboard";
    $pageUrls['dashboard-eventhome'] = $pageSiteUrl . "dashboard/home/";
    $pageUrls['dashboard-pastevent'] = $pageSiteUrl . "dashboard/pastEventList";
    $pageUrls['dashboard-ticketwidget'] = $pageSiteUrl . "dashboard/configure/ticketWidget/";
    $pageUrls['ticketWidget'] = $pageSiteUrl . "ticketWidget";    
    $pageUrls['dashboard-webhook'] = $pageSiteUrl . "dashboard/configure/webhookUrl/";
    $pageUrls['dashboard-gallery'] = $pageSiteUrl . "dashboard/configure/gallery/";
    $pageUrls['dashboard-seo'] = $pageSiteUrl . "dashboard/configure/seo/";
    $pageUrls['dashboard-ticketOption'] = $pageSiteUrl . "dashboard/configure/ticketOptions/";
    $pageUrls['dashboard-customField'] = $pageSiteUrl . "dashboard/configure/customFields/";
    $pageUrls['dashboard-discount'] = $pageSiteUrl . "dashboard/promote/discount/";
    $pageUrls['dashboard-viralticket'] = $pageSiteUrl . "dashboard/promote/viralTicket/";
    $pageUrls['dashboard-affliate'] = $pageSiteUrl . "dashboard/promote/affiliate/";
    $pageUrls['dashboard-add-affliate'] = $pageSiteUrl . "dashboard/promote/addPromoter/";
    $pageUrls['dashboard-transaction-report'] = $pageSiteUrl . "dashboard/reports/";
    $pageUrls['promoter-transaction-report'] = $pageSiteUrl . "promoter/reports/";
    $pageUrls['dashboard-saleseffort-report'] = $pageSiteUrl . "dashboard/saleseffort/";
    $pageUrls['dashboard-add-discount'] = $pageSiteUrl . "dashboard/promote/addDiscount/";
    $pageUrls['dashboard-list-discount'] = $pageSiteUrl . "dashboard/promote/discount/";
    /*$pageUrls['dashboard-list-collaborator'] = $pageSiteUrl . "dashboard/promote/collaboratorlist/";
    $pageUrls['dashboard-add-collaborator'] = $pageSiteUrl . "dashboard/promote/addcollaborator/";
    $pageUrls['dashboard-edit-collaborator'] = $pageSiteUrl . "dashboard/promote/editcollaborator/";*/
    $pageUrls['dashboard-list-collaborator'] = $pageSiteUrl . "dashboard/collaborator/collaboratorlist/";
    $pageUrls['dashboard-add-collaborator'] = $pageSiteUrl . "dashboard/collaborator/addcollaborator/";
    $pageUrls['dashboard-edit-collaborator'] = $pageSiteUrl . "dashboard/collaborator/editcollaborator/";
    $pageUrls['user-myprofile'] = $pageSiteUrl . "profile/";
    $pageUrls['user-companyprofile'] = $pageSiteUrl . "profile/company";
    $pageUrls['user-bankdetail'] = $pageSiteUrl . "profile/bank";
    $pageUrls['user-alert'] = $pageSiteUrl . "profile/alert";
    $pageUrls['user-savedevent'] = $pageSiteUrl . "profile/";
    $pageUrls['changepassword'] = $pageSiteUrl . "profile/changePassword";
    $pageUrls['developerapi'] = $pageSiteUrl . "profile/developerapi";
    $pageUrls['createApp'] = $pageSiteUrl . "profile/createApp";
    $pageUrls['updateApp'] = $pageSiteUrl . "profile/updateApp";
    $pageUrls['user-changePassword'] = $pageSiteUrl . "changePassword/";    
    $pageUrls['user-logout'] = $pageSiteUrl . "logout";
    $pageUrls['create-event'] = $pageSiteUrl . "dashboard/event/create/";
    $pageUrls['edit-event'] = $pageSiteUrl . "dashboard/event/edit/";
    $pageUrls['preview-event'] = $pageSiteUrl . "event/";
    $pageUrls['dashboard-bulkdiscount'] = $pageSiteUrl . "dashboard/promote/bulkDiscount/";
    $pageUrls['dashboard-add-bulkdiscount'] = $pageSiteUrl . "dashboard/promote/addBulkDiscount/";
    $pageUrls['dashboard-tnc'] = $pageSiteUrl . "dashboard/configure/tnc/";
    $pageUrls['user-attendeeview-current'] = $pageSiteUrl . "currentTicket";
    $pageUrls['user-attendeeview-past'] = $pageSiteUrl . "pastTicket";
    $pageUrls['user-attendeeview-referal'] = $pageSiteUrl . "referalBonus";
    $pageUrls['dashboard-add-offline-promoter'] = $pageSiteUrl . "dashboard/promote/addOfflinePromoter/";
    $pageUrls['dashboard-edit-offline-promoter'] = $pageSiteUrl . "dashboard/promote/editOfflinePromoter/";
    $pageUrls['dashboard-offlinepromoter'] = $pageSiteUrl . "dashboard/promote/offlinePromoterlist/";
    $pageUrls['user-noaccess'] = $pageSiteUrl . "noAccess/";
    $pageUrls['user-login'] = $pageSiteUrl . "login";
    $pageUrls['user-promoterview-current'] = $pageSiteUrl . "promoter/currentlist";
    $pageUrls['user-promoterview-past'] = $pageSiteUrl . "promoter/pastlist";
    $pageUrls['user-promoterview-offlinebooking'] = $pageSiteUrl . "promoter/offlinebooking";
    $pageUrls['user-promoterview-eventdetailslist'] = $pageSiteUrl . "promoter/eventDetailsList/";
    $pageUrls['search'] = $pageSiteUrl . "search";
    $pageUrls['career'] = $pageSiteUrl . "career";
    $pageUrls['faq'] = $pageSiteUrl . "faq";
    $pageUrls['pricing'] = $pageSiteUrl . "pricing";
    $pageUrls['pricingtab'] = $pageSiteUrl . "pricingtab.php";
    $pageUrls['blog'] = "http://blog.meraevents.com/";
    $pageUrls['news'] = $pageSiteUrl . "news";
    $pageUrls['mediakit'] = $pageSiteUrl . "mediakit";
    $pageUrls['eventregistration'] = $pageSiteUrl . "eventregistration";
    $pageUrls['selltickets'] = $pageSiteUrl . "selltickets";
    $pageUrls['terms'] = $pageSiteUrl . "terms";
    $pageUrls['apidevelopers'] = $pageSiteUrl . "apidevelopers";
    $pageUrls['client_feedback'] = $pageSiteUrl . "client_feedback";
    $pageUrls['aboutus'] = $pageSiteUrl . "aboutus";
    $pageUrls['team'] = $pageSiteUrl . "team";
    $pageUrls['dashboard-gallery'] = $pageSiteUrl . "dashboard/configure/gallery/";
    $pageUrls['dashboard-contactinfo'] = $pageSiteUrl . "dashboard/configure/contactInfo/";
    $pageUrls['dashboard-paymentMode'] = $pageSiteUrl . "dashboard/configure/paymentMode/";
    $pageUrls['dashboard-refund'] = $pageSiteUrl . "dashboard/payment/refund/";
    $pageUrls['dashboard-payment-receipts'] = $pageSiteUrl . "dashboard/payment/receipts/";
    $pageUrls['dashboard-emailAttendees'] = $pageSiteUrl . "dashboard/configure/emailAttendees/";    
    $pageUrls['dashboard-deleteRequest'] = $pageSiteUrl . "dashboard/configure/deleteRequest/";  
    $pageUrls['event-tnc-popup'] = $pageSiteUrl . "dashboard/event/termsAndConditions/";
    $pageUrls['print_pass'] = $pageSiteUrl . "printpass/";   
    $pageUrls['dashboard-guestlist-booking'] = $pageSiteUrl . "dashboard/promote/guestListBooking/";
    $pageUrls['captcha'] = $pageSiteUrl . "captcha.php";
    $pageUrls['user-activationLink']=$pageSiteUrl . "activationLink/";
    $pageUrls['user-signup']=$pageSiteUrl . "signup/";
    $pageUrls['event-preview']=$pageSiteUrl . "previewevent";
	$pageUrls['event-detail']=$pageSiteUrl . "event/";
    $pageUrls['multiple-offlinepass']=$pageSiteUrl . "confirmation/getMultipleOfflinePasses";
    $pageUrls['download-file'] =   $pageSiteUrl . "home/download";
    $pageUrls['content-page'] =   $pageSiteUrl . "content";    
   /*$pageUrls['api_getTicketCalculation'] = https_url()."api/event/getTicketCalculation";
    $pageUrls['api_bookNow'] =  https_url()."api/event/bookNow";
    $pageUrls['api_bookingSaveData'] =  https_url()."api/booking/saveData"; */
    $pageUrls['api_getTicketCalculation'] = $pageSiteUrl."api/event/getTicketCalculation";
    $pageUrls['api_bookNow'] =  $pageSiteUrl."api/event/bookNow";
    $pageUrls['api_bookingSaveData'] =  $pageSiteUrl."api/booking/saveData";
    $pageUrls['api_citySearch'] =  $pageSiteUrl."api/city/search";
    $pageUrls['api_countrySearch'] =  $pageSiteUrl.'api/country/search';
    $pageUrls['api_stateSearch'] =  $pageSiteUrl.'api/state/search';
    $pageUrls['api_delegateSmsSend'] =  $pageSiteUrl.'api/transaction/resendSuccessEventsignupsmstoDelegate';
    $pageUrls['api_emailPrintpass'] =  $pageSiteUrl.'api/transaction/emailPrintpass';
    $pageUrls['api_eventPromoCodes'] =  $pageSiteUrl.'api/eventpromocodes/check';
    $pageUrls['confirmation'] =  $pageSiteUrl . 'confirmation';
    $pageUrls['payment'] =  $pageSiteUrl . 'payment';
    $pageUrls['payment_ebsProcessingPage'] =   $pageSiteUrl . "payment/ebsProcessingPage";
    $pageUrls['payment_mobikwikProcessingPage'] =   $pageSiteUrl . "payment/mobikwikProcessingPage";
    $pageUrls['payment_paytmProcessingPage'] =   $pageSiteUrl . "payment/paytmProcessingPage";
    $pageUrls['payment_paypalProcessingPage'] =   $pageSiteUrl . "payment/paypalProcessingPage";
    $pageUrls['bugbounty'] =   $pageSiteUrl."bugbounty";
    $pageUrls['support'] =   "http://support.meraevents.com/anonymous_requests/new";
    $pageUrls['contactUs'] =   $pageSiteUrl."support";
    $pageUrls['privacypolicy'] =   $pageSiteUrl."privacypolicy";
    $pageUrls['dashboard-global-affliate-home'] = $pageSiteUrl . "globalaffiliate/home";
    $pageUrls['dashboard-global-affliate-why'] = $pageSiteUrl . "globalaffiliate/why";
    $pageUrls['dashboard-global-affliate'] = $pageSiteUrl . "globalaffiliate/join";
    $pageUrls['dashboard-global-affliate-faq'] = $pageSiteUrl . "globalaffiliate/faq";
    $pageUrls['dashboard-global-affliate-bonus'] = $pageSiteUrl . "profile/index/affiliateBonus";
    
    $pageUrls['api_commonRequestProcessRequest'] =   $pageSiteUrl . "api/common_requests/processRequest";
    $pageUrls['api_subcategoryList'] =   $pageSiteUrl . "api/subcategory/list";
    $pageUrls['api_countryDetails'] =   $pageSiteUrl . "api/country/details";
    $pageUrls['api_checkUrlExists'] =   $pageSiteUrl . "api/event/checkUrlExists";
    $pageUrls['api_subcategoryEventsCount'] =   $pageSiteUrl . "api/subcategory/eventsCount";
    $pageUrls['api_categoryEventsCount'] =   $pageSiteUrl . "api/category/eventCount";
    $pageUrls['api_cityEventsCount'] =   $pageSiteUrl . "api/city/eventCount";
    $pageUrls['api_filterEventsCount'] =   $pageSiteUrl . "api/filter/eventCount";
    $pageUrls['api_categorycityEventsCount'] =   $pageSiteUrl . "api/category/cityEventsCount";
    $pageUrls['api_subcategorycityEventsCount'] =   $pageSiteUrl . "api/subcategory/cityEventsCount";
    $pageUrls['api_bannerList'] =   $pageSiteUrl . "api/banner/list";
    $pageUrls['api_stateList'] =   $pageSiteUrl . "api/state/list";
    $pageUrls['api_cityCitysByState'] =   $pageSiteUrl . "api/city/citysByState";
    $pageUrls['api_eventList'] =   $pageSiteUrl . "api/event/list";
    $pageUrls['api_eventEventsCount'] =   $pageSiteUrl . "api/event/eventCount";
    $pageUrls['api_searchSearchEvent'] =   $pageSiteUrl . "api/search/searchEvent";
    $pageUrls['api_searchSearchEventAutocomplete'] =   $pageSiteUrl . "api/search/searchEventAutocomplete";
    $pageUrls['api_UsersignupEmailCheck'] =   $pageSiteUrl . "api/user/signupEmailCheck";
    $pageUrls['api_Usersignup'] =   $pageSiteUrl . "api/user/signup";
    $pageUrls['api_UserLogin'] =   $pageSiteUrl . "api/user/login";
    $pageUrls['resendActivationLink'] =   $pageSiteUrl . "resendActivationLink";
    $pageUrls['api_UserchangePassword'] =   $pageSiteUrl . "api/user/changePassword";
    $pageUrls['api_ticketCalculateTaxes'] =   $pageSiteUrl . "api/ticket/calculateTaxes";
    $pageUrls['api_blogBloglist'] =   $pageSiteUrl . "api/blog/blogList";
    $pageUrls['api_eventMailInvitations'] =   $pageSiteUrl . "api/event/mailInvitations";
    $pageUrls['api_tagsList'] =   $pageSiteUrl . "api/tag/list";
    $pageUrls['api_ticketDelete'] =   $pageSiteUrl . "api/ticket/delete";
    $pageUrls['api_eventCreate'] =   $pageSiteUrl . "api/event/create";
    $pageUrls['api_eventEdit'] =   $pageSiteUrl . "api/event/edit";
    $pageUrls['api_dashboardEventchangeStatus'] =   $pageSiteUrl . "api/event/changeStatus";
    $pageUrls['api_promoteofflineTickets'] =   $pageSiteUrl . "api/promote/offlineTickets";
    $pageUrls['api_promoteticketsData'] =   $pageSiteUrl . "api/promote/ticketsData";
    $pageUrls['api_promotesetStatus'] =   $pageSiteUrl . "api/promote/setStatus";
    $pageUrls['api_bookingOfflineBooking'] =   $pageSiteUrl . "api/booking/offlineBooking";
    $pageUrls['url_dashboardReports'] =   $pageSiteUrl . "dashboard/reports";
    $pageUrls['api_reportsGetReportDetails'] =   $pageSiteUrl . "api/reports/getReportDetails";
    //$pageUrls['api_reportsExportTransactions'] =   $pageSiteUrl . "api/reports/exportTransactions";
  	 $pageUrls['api_reportsExportTransactions'] =   site_url() . "download/downloadCsv";
    $pageUrls['api_reportsDownloadImages'] =   $pageSiteUrl . "api/reports/downloadImages";
    $pageUrls['api_reportsEmailTransactions'] =   $pageSiteUrl . "api/reports/emailTransactions";
    $pageUrls['api_collaboratorAdd'] =   $pageSiteUrl . "api/collaborator/add";
    $pageUrls['api_collaboratorUpdateStatus'] =   $pageSiteUrl . "api/collaborator/updateStatus";
    $pageUrls['api_collaboratorUpdate'] =   $pageSiteUrl . "api/collaborator/Update";
    $pageUrls['dasboard-configureAddcustomfields'] =   $pageSiteUrl . "dashboard/configure/addcustomfields";
    $pageUrls['api_configureGetDashboardEventCustomFields'] =   $pageSiteUrl . "api/configure/getDashboardEventCustomFields";
    $pageUrls['api_configureUpdateStatus'] =   $pageSiteUrl . "api/configure/updateStatus";
    $pageUrls['api_reportsGetWeekwiseSales'] =   $pageSiteUrl . "api/reports/getWeekwiseSales";
    $pageUrls['api_reportsSalesEffortReports'] =   $pageSiteUrl . "api/reports/salesEffortReports";
    $pageUrls['api_commonrequestsUpdateCookie'] =   $pageSiteUrl . "api/common_requests/updateCookie";
    $pageUrls['api_userGetUserData'] =   $pageSiteUrl . "api/user/getUserData";
    $pageUrls['api_sendTicketsoldDataToorganizer'] =   $pageSiteUrl . "api/ticket/sendTicketsoldDataToorganizer";
    $pageUrls['api_getEvents'] =   $pageSiteUrl . "api/dashboard/getEvents";
    $pageUrls['api_copyEvent'] =   $pageSiteUrl . "api/event/copyEvent";
    $pageUrls['api_organizationEvents'] =   $pageSiteUrl . "api/organization/list";
    $pageUrls['api_organizerContactEmails'] =   $pageSiteUrl . "api/organization/contactOrg";
    $pageUrls['api_updateSeats'] =   $pageSiteUrl . "api/seating/updateSeats";
    $pageUrls['api_checkUpdateSeats'] =   site_url() . "api/seating/checkUpdateSeats";
    $pageUrls['api_checkUserNameExist'] =   site_url() . "api/user/userNameCheck";
    $pageUrls['api_resendDelegateEmail'] =  $pageSiteUrl.'api/transaction/resendTransactionSuccessEmailToDelegate';
    $pageUrls['api_globalPromoter'] =   $pageSiteUrl . "api/promote/addGlobalPromoter";
    $pageUrls['api_checkGlobalCodeAvailability'] =   $pageSiteUrl . "api/promote/checkGlobalCodeAvailability";
    $pageUrls['microsite_shivkhera'] =  $pageSiteUrl.'shivkhera';
	$pageUrls['api_getProfileDropdown'] =   site_url() . "api/user/getProfileDropdown";
    
    $ci = &get_instance();
    if($ci->config->item('https_enabled')== true){
    	$pageUrls['api_getTicketCalculation'] = https_url()."api/event/getTicketCalculation";
    	$pageUrls['api_bookNow'] =  https_url()."api/event/bookNow";
    	$pageUrls['api_bookingSaveData'] =  https_url()."api/booking/saveData";
    	$pageUrls['confirmation'] =  https_url() . 'confirmation';
    	$pageUrls['payment'] =  https_url() . 'payment';
    }
    
    $params = str_replace('&', '/', $params);
    $return = (isset($pageUrls[$pageName])) ? $pageUrls[$pageName] : $pageUrls['dashboard-myevent'];
    $return.= (strlen($params) > 0) ? str_replace("&", "/", $params) : "";
    if (strlen($getParams) > 0) {
        $return.= $getParams;
    }
    return $return;
}

function commonHtmlElement($type,$display = 0,$isGuestLogin = 0,$dashboardUrl='')
	{
		$returnString = "";
		switch ($type) 
		{
			case 'myprofile': 
				if($display == 1 && $isGuestLogin == 0) {
					$returnString = "<li><a href='".commonHelperGetPageUrl('user-myprofile')."'><span class='icon2-user'></span> Profile</a></li>";
				}
				break;
			case 'myevent': 
				if($display == 1 && $isGuestLogin == 0) {
                                    if($dashboardUrl=='' || $dashboardUrl==' '){$dashboardUrl=  getDashboardUrl();}
                    $returnString = "<li><a href='".$dashboardUrl."'><span class='icon-event'></span> Organizer View</a></li>";
				}
				break;
			case 'create-event':
                if($isGuestLogin == 0) {
                    $returnString = "<li><a href='".commonHelperGetPageUrl('create-event')."'><span class='icon2-pencil'></span> Create Event</a></li>";
                }
				break;
			case 'logout': 
				if($display == 1) {
                    $returnString = "<li><a href='".commonHelperGetPageUrl('user-logout')."'><span class='icon2-sign-out'></span> Logout</a></li>";
                    
				} else {
					$returnString = "<li><a href='".commonHelperGetPageUrl('user-login')."' target='_self'><span class='icon2-sign-in' ></span> Login</a></li>";
                    
				}
				break;
			case 'savedevent': 
				if($display == 1 && $isGuestLogin == 0) {
                    $returnString = "<li><a href='".commonHelperGetPageUrl('user-savedevent')."'><span class='icon2-bookmark' ></span> Saved Events</a></li>";
				}
				break;
			case 'attendeeview': 
				if($display == 1 && $isGuestLogin == 0) {
                    $returnString = "<li><a href='".  getAttendeeUrl()."'><span class='icon2-ticket' ></span> Attendee View</a></li>";
				}
				break;
			case 'promoterview': 
				if($display == 1 && $isGuestLogin == 0) {
                    $returnString = "<li><a href='". getPromoterViewUrl()."'><span class='icon2-bullhorn' ></span> Promoter View</a></li>";
				}
				break;
			case 'dashboardButton': 
				if($display == 1 && $isGuestLogin == 0) {
                                     if($dashboardUrl==''||$dashboardUrl==' '){$dashboardUrl=getDashboardUrl();}
                    $returnString = "<a href='".$dashboardUrl."' class='btn btn-default pinkColor colorWhite'>dashboard</a>";
				}
				break;
				
		}
		return $returnString;
	}

function commonHelperDownload($file) {
	$ci = &get_instance();
     $file = $ci->input->get("filePath");
    if (strlen($file) > 0) {
    	$ci->load->helper('download');
		//$data = file_get_contents($file); // Read the file's contents
		
		$filePathInfo = pathinfo($file);
		$filebasename = urlencode($filePathInfo['basename']);

		$actualfile=$filePathInfo['dirname']."/".$filebasename;
        $data =url_get_contents($actualfile);
    	$name = urlencode(basename($file));
    	force_download($name, $data);
    } else {
        echo "Oops..!, something went wrong. Please try again.";
    }
}

function random_password($length = 6) {
    $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()_-=+;:,.?";
    $password = substr(str_shuffle($chars), 0, $length);
    return $password;
}

//To check the string is in md5 or not
function isValidMd5($md5 = '') {
    return strlen($md5) == 32 && ctype_xdigit($md5);
}

//To format to mysql date format(Y-m-d H:i:s)
function formatToMysqlDate($inputArray) {
    $date = $inputArray['date'];
    $time = $inputArray['time'];

    $dateSplit = explode('/', $date);
    $month = $dateSplit[0];
    $day = $dateSplit[1];
    $year = $dateSplit[2];
    $finalDate = $year . '-' . $month . '-' . $day;

    $timeSplit = explode(' ', $time);
    $dateTime = $finalDate . " " . $timeSplit[0] . " " . $timeSplit[1];

    $finalDateTime = date('Y-m-d H:i:s', strtotime($dateTime));
    return $finalDateTime;
}

/**
 * 
 * @param type $dateTime
 * @param type $timeZoneName
 * @param type $utc default false convert the passed  datetime to utc time
 * $utc passed as true convert the utc datetime to passed timezone format
 * @return type
 */
function convertTime($dateTime, $timeZoneName, $utc = FALSE) {
    if (!$utc) {
        $sourceTimeZone = $timeZoneName;
        $destinationTimeZone = "UTC";
    } else {
        $sourceTimeZone = "UTC";
        $destinationTimeZone = $timeZoneName;
    }
        $date = new DateTime($dateTime, new DateTimeZone($sourceTimeZone));
    $date->setTimezone(new DateTimeZone($destinationTimeZone));
    return $date->format('Y-m-d H:i:s');
}

/**
 * @param type $dateTime
 * @param type $timeZoneName
 * @param type $utc default false convert the passed  datetime to utc time
 * $utc passed as true convert the utc datetime to passed timezone format
 * @return statr date with timzone format
 */
function appendTimeZone($dateTime, $timeZoneName, $utc = FALSE) {
       $sourceTimeZone = $timeZoneName;
       $destinationTimeZone = $timeZoneName;
        $date = new DateTime($dateTime, new DateTimeZone($sourceTimeZone));
        $checkDate=$date->setTimezone(new DateTimeZone($destinationTimeZone));
        $timeformat= date_format($checkDate, 'Y-m-d H:i:sP');
        $date_frmt= explode(" ", $timeformat);
        $date= $date_frmt[0];
        $time= $date_frmt[1];
        return $finalTime=$date.'T'.$time;    
}

/**
 * To get the ticket type related enum value
 * @param type $ticketTypeNo
 * @return int
 */
function getTicketType($ticketTypeNo) {

    switch ($ticketTypeNo) {
        case 1:
            $type = 'free';
            break;
        case 2:
            $type = 'paid';
            break;
        case 3:
            $type = 'donation';
            break;
        case 4:
            $type = 'addon';
            break;
        default:
            $type = 2;
    }
    return $type;
}

/* * To remove unwanted characters from string
 * @param type $string
 * @return string
 */

function cleanUrl($string) {
    // Remove special charcters
    $string = preg_replace('/[^A-Za-z0-9_\-]/', ' ', $string);
    // Replacing spaces with "_"
    $string = str_replace(' ', '-', $string);
    return preg_replace('/-+/', '-', $string); // Replaces multiple hyphens with single one.
}

//Convert the date from (2015-08-24 17:16:00)format to (14 Apr 2015, 05:20 PM )format
function convertDateTime($inputDate) {
    $time = strtotime($inputDate);
    $formattedTime = date("d M Y\, h:i A", $time);
    return $formattedTime;
}

function commonHelperGetEventName($eventId = 0) {
    $eventName = "";
    $ci = &get_instance();
    $eventData = $ci->config->item('eventData');
    if (isset($eventData["event" . $eventId]['eventName'])) {
        $eventName = $eventData["event" . $eventId]['eventName'];
    }
    return $eventName;
}
function getExtension($str) {
    $i = strrpos($str, ".");
    if (!$i) {
        return "";
    }
    $l = strlen($str) - $i;
    $ext = substr($str, $i + 1, $l);
    return $ext;
}


/**
 * If admin logied in we send admin user id othe wise we will
 *  send user related session info
 */
function getSessionUserId(){
    $ci = &get_instance();
    $adminId=$ci->customsession->getData("adminId");
    if ($adminId) {
        return $adminId;
    }
    return getUserId();       
}

function onlyCurrentDate(){
    return date('m/d/Y');
}
function currentDateTime(){
    return date('Y-m-d H:i:s');
}
function timeConvert($inputDate){
   return  date('h:i a',strtotime($inputDate));
}
function reminderDate($inputDate){
   return  date('Ymd',strtotime($inputDate));
}
function nowDate($inputDate){
   $timezone = new DateTimeZone($inputDate); 
   $date =  new DateTime('', $timezone);   
   return $date->format('Y-m-d H:i:s'); 
}
function seoFormat($inputDate){
   return  date('h:i a',strtotime($inputDate));
}
//1.->If Date is given,'specified date' converts into specified format type, 
//2.->If Date is not given,'current time' will be given in the specified format type
function allTimeFormats($inputDate, $formatType) {
    switch ($formatType) {
        case 1:
            if ($inputDate) {
                $formattedTime = date('m/d/Y', strtotime($inputDate));
            } else {
                $formattedTime = date('m/d/Y');
            }break;
        case 2:
            if ($inputDate) {
                $formattedTime = date('g:i A', strtotime($inputDate));
            } else {
                $formattedTime = date('g:i A');
            }break;
        case 3: if ($inputDate) {
                $formattedTime = date('l\, jS M Y', strtotime($inputDate));
            } else {
                $formattedTime = date('l\, jS M Y');
            }break;
        case 4: if ($inputDate) {
                $formattedTime = date('h:i A', strtotime($inputDate));
            } else {
                $formattedTime = date('h:i A');
            }break;
        case 6: if ($inputDate) {
                $formattedTime = date('Y-m-d 00:00:00', strtotime($inputDate));
            } else {
                $formattedTime = date('Y-m-d 00:00:00');
            }break;
        case 7: if ($inputDate) {
                $formattedTime = date('d M Y, h:i A', strtotime($inputDate));
            } else {
                $formattedTime = date('d M Y, h:i A');
            }break;
        case 8: if ($inputDate) {
                $formattedTime = date('F j, Y', strtotime($inputDate));
            } else {
                $formattedTime = date('F j, Y');
            }break;
        case 9: if ($inputDate) {
                $formattedTime = date('Y-m-d', strtotime($inputDate));
            } else {
                $formattedTime = date('Y-m-d');
            }break;
        case 11: if ($inputDate) {
                $formattedTime = date('Y-m-d H:i:s', strtotime($inputDate));
            } else {
                $formattedTime = date('Y-m-d H:i:s');
            }break;
        case 12: if ($inputDate) {
                $formattedTime = date('H:i:s', strtotime($inputDate));
            } else {
                $formattedTime = date('H:i:s');
            }break;
        case 14: if ($inputDate) {
                $formattedTime = date('Ymd', strtotime($inputDate));
            } else {
                $formattedTime = date('Ymd');
            }break;
        case 15: if ($inputDate) {
                $formattedTime = date('F d, Y', strtotime($inputDate));
            } else {
                $formattedTime = date('F d, Y');
            }break;
        case 16: if ($inputDate) {
                $formattedTime = date('h:i a', strtotime($inputDate));
            } else {
                $formattedTime = date('h:i a');
            }break;
        case 17: if ($inputDate) {
                $formattedTime = date('Y', strtotime($inputDate));
            } else {
                $formattedTime = date('Y');
            }break;
        case 18: if ($inputDate) {
                $formattedTime = date('F d, Y,h:i A', strtotime($inputDate));
            }else {
                $formattedTime = date('F d, Y');
            }break;
		case 19: if ($inputDate) {
					$formattedTime = gmdate('Y-m-d\TH:i:s.u\Z', strtotime($inputDate));
				} else {
					$formattedTime = gmdate('Y-m-d\TH:i:s.u\Z', strtotime(date('Y-m-d H:i:s.u')));
				}break;
    }
    return $formattedTime;
}


function getNormalJSONinput($stripData = true, $exclude = NULL) {
    $jsonOutput = file_get_contents('php://input');
    if ($stripData) {
        $jsonOutputArray = (array) json_decode($jsonOutput);
        $jsonOutputString = json_encode(stripData($jsonOutputArray, $exclude));
        $jsonOutputObject = json_decode($jsonOutputString);
    } else {
        $jsonOutputObject = json_decode($jsonOutput);
    }
    return $jsonOutputObject;
}

function stripData($array, $exclude = NULL) {
    foreach ($array as $key => $value) {
        if (is_object($value)) {//if result value is an object
            $value = (array) $value;
        }
        if (is_array($value)) {
            $array[$key] = $this->stripData($value);
        } else {
            if (is_array($exclude)) {
                if (in_array($key, $exclude)) {

                    continue;
                }//end of in_array
            }//end of is_array
            $array[$key] = variable_filter(strip_tags($value));
        }
    }

    return $array;
}

function variable_filter($variable) {
    $lenn = strlen($variable);
    if ($lenn > 0) {
        if (preg_match('/[^\w\d_ -]/si', $variable)) {
        
            return preg_replace('/[^a-zA-Z0-9_@,\s-\.\(\):\/\+;|=]/s', '', $variable);
        } else {
            return preg_replace('/\s/', ' ', $variable);
        }
    } else {
        return trim($variable);
    }
}

	function google_place_key() {
		
		$google_keys = array('AIzaSyDfnbNjGBLlptc4NpuABPKDgardzRAssjY','AIzaSyDNK0j79Mu6UZEbPzy8DXTUeGLiT6m6Mc4');
		$arrayLength = count($google_keys);
		$key = mt_rand(0,$arrayLength-1);
		return $google_keys[$key];
	}
	
	function https_get($url) {
		
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);  // return the transfer as a string 
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        $json = curl_exec($ch); 
    curl_close($ch);
		return $json;     		
}

/**
  * To get the url related content based on the passed url
  * @param $url 
  * @return url content
  */
 function url_get_contents($url) {
     $ch = curl_init();
     curl_setopt($ch, CURLOPT_URL, $url);
     curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
     $output = curl_exec($ch);
     curl_close($ch);
     return $output;
 }
 
 function getCurrentTimeUTC() {
	$time = gmdate('Y-m-d\TH:i:s.u\Z', strtotime(date('Y-m-d H:i:s.u')));
	return $time;
 }
 
 function convertDate($inputDate) {
    $time = strtotime($inputDate);
    $formattedTime = date("l\, jS M Y", $time);
    return $formattedTime;
}

// Function to convert the mysql date format(2015-08-10 09:00:00 ) to  August 10, 2015
function convertDateTo($inputDate) {
    $time = strtotime($inputDate);
    $formattedTime = date("F d, Y", $time);
    return $formattedTime;
}

//Last Date conversion format type 11-04-2015
function lastDateFormat($inputDate){
    return date('d-m-Y', strtotime($inputDate));
    
}


function commonHelperRedirect($url){
    redirect($url,'location',302);exit;
}

function recommendationsUserIdWithSalt($userid) {
	$ci = &get_instance();
	$salt=$ci->config->item('piwik_user_salt');
		
	$userslat=md5($userid.$salt);
	setcookie("piwikUserId",$userslat , (2592000 + time()));
	return $userslat;
}
//To get the random generated strings for client id & client scret
function random_strings_for_client() {
	$rand=(time()*rand(1,9)).rand(1000,9999);
    return substr(str_shuffle($rand),0,9);
}

function eventTypeById($registrationType,$eventMode) {
		
		
        switch ($registrationType) {
            case '2':
				$type = 'paid';
                break;
            case '1':
				$type = 'free';
                break;
            case 'webinar':
                $eventTypeValues['eventMode'] = 1;
                $eventTypeValues['registrationType'] = 4;
                break;
            case 'nonwebinar':
                $eventTypeValues['eventMode'] = 0;
                $eventTypeValues['registrationType'] = 4;
                break;
			case '4':
				if($eventMode == '1') {
					$type = 'webinar';
				} else {
					$type = 'nonwebinar';
				}
				
                break;
            case '3':
                $type = 'noreg';
                break;
            default:
                $type = 'paid';
                break;
        }
        return $type;
    }

