<!-- TrueSemantic Feedback form -->
<?php  
$callclass = $this->router->fetch_class();
$callmethod = $this->router->fetch_method(); 
if ( $callclass == "event" && ($callmethod =="create" || $callmethod =="edit" )){
}
else {
?>
<script>
    
    tsa.auth.push({"_ts_org_license":"1e26ec5e22d0b5b1eaa6f02cf2fbef68"});

    <?php if ($this->customsession->getUserId() > 0) { ?>
    tsa.activity_data = {
	"ucategory" : "Online",
    };
    tsa.user_data = {	
        // embed data the user wants to change
        "email"  : "<?PHP echo $this->customsession->getData('userEmail');  ?>",
        "mobile" : "<?PHP echo $this->customsession->getData('userMobile'); ?>",
        "fname"  : "<?PHP echo $this->customsession->getData('userName'); ?>",
        "city"   : "<?PHP echo $this->customsession->getData('userCity'); ?>",
        "state"  : "<?PHP echo $this->customsession->getData('userState'); ?>",
        "country": "<?PHP echo $this->customsession->getData('userCountry'); ?>"
    };
<?php }
      else { ?>
        var tsTimerL = setInterval(function(){
            if(typeof window.ts !== typeof undefined)
            {
            	//console.log("LOGGIN OUT");
                ts.logout();
                clearInterval(tsTimerL)
            }
        },300);
     <?php }
    ?>
(function() {
    var tsexd = document.createElement('script'); tsexd.type = 'text/javascript'; tsexd.async = true;
    tsexd.src = ('https:' == document.location.protocol ? 'https://d17i51iptdy41y.cloudfront.net' : 'http://d17i51iptdy41y.cloudfront.net') + '/tslib/eXDM/easyXDM.min.js.gz';
    var where = document.getElementsByTagName('script');where = where[where.length - 1];
    where.parentNode.insertBefore(tsexd, where);

    var tsembd = document.createElement('script'); tsembd.type = 'text/javascript'; tsembd.async = true;
    tsembd.src = ('https:' == document.location.protocol ? 'https://d17i51iptdy41y.cloudfront.net' : 'http://d17i51iptdy41y.cloudfront.net') + '/tslib/take-survey.js.gz';
    var where = document.getElementsByTagName('script');where = where[where.length - 1];
    where.parentNode.insertBefore(tsembd, where);
})();
</script>
<?php
/*echo $callclass = $this->router->fetch_class();

echo $callmethod = $this->router->fetch_method();*/

if($callclass == 'event'){
	$eventid = isset($eventData['id'])?$eventData['id']:'';
	$eventtitle = isset($eventData['title'])?$eventData['title']:'';
	$categoryname = isset($eventData['categoryName'])?$eventData['categoryName']:'';
	$eventsubcategory = isset($eventData['subCategoryName'])?$eventData['subCategoryName']:'';
	$eventorganizer = isset($eventData['ownerId'])?$eventData['ownerId']:'';
	$eventdate = isset($eventData['startDate'])?date("d M Y, h:i A",strtotime($eventData['startDate'])):'';
        //$eventdate = date("d M Y, h:i A",strtotime($eventdate));
        $eventtype = "Free Event";
        $eveMode = ($eventData['eventMode']=="1")?'Webinar':'Event';
        if ($eventData['registrationType'] == 3) {
            $eventtype = "InfoOnly ".$eveMode;
        }
        else if ($eventData['registrationType'] == 2) {
            $eventtype = "Paid ".$eveMode;
        }
        else {
            $eventtype = "Free ".$eveMode;
        }
	$eventcountry = isset($eventData['location']['countryName'])?$eventData['location']['countryName']:'';
	$eventstate = isset($eventData['location']['stateName'])?$eventData['location']['stateName']:'';
	$eventcity = isset($eventData['location']['cityName'])?$eventData['location']['cityName']:'';
	$eventvenue = isset($eventData['location']['venueName'])?$eventData['location']['venueName']:'';
        $ucode = isset($_GET['ucode'])?$_GET['ucode']:'';
switch($callmethod){
	case "index":
		echo '<script>
                        tsa.activity_data = {
                            "eveid":"'.$eventid.'",
                            "evecategory":"'.$categoryname.'",
                            "evescategory":"'.$eventsubcategory.'",
                            "evename":"'.$eventtitle.'",
                            "eveorganizer":"'.$eventorganizer.'",
                            "evecity":"'.$eventcity.'",
                            "evestate":"'.$eventstate.'",
                            "evecountry":"'.$eventcountry.'",
                            "evedate":"'.$eventdate.'",
                            "evetype":"'.$eventtype.'",
                            "evevenue":"'.$eventvenue.'",
                            "ucode":"'.$ucode.'",
                            "fcategory":"Event Page Exit"
                        };
                    </script>';
		break;
	default:
		break;
}
}
if($callclass == 'search'){
	$keyword = $this->input->get('keyword');
	switch($callmethod){
		case "index":
			echo '<script type="text/javascript" language="javascript">
                            var subCategoryName = "'.$defaultSubCategoryName.'";
                            var customFilterName = "'.$defaultCustomFilterName.'";
                            var customFreepaid = "'.$defaultCustomFreePaid.'";
                            
                            if (subCategoryName.trim() == \'SubCategories\' || subCategoryName.trim() == \'All SubCategories\'){
                                subCategoryName = "";
                            }
                            if (customFilterName == \'time\') {
                                customFilterName = "all time";
                            }
                            
                            var filter = new Array (
                                    customFilterName.charAt(0).toUpperCase() + customFilterName.slice(1),
                                    customFreepaid.charAt(0).toUpperCase() + customFreepaid.slice(1)    
                                );
                                tsa.activity_data = {
                                    "skeyword":"'.$keyword.'",
                                    "sresults":totalResultCount,
                                    "scity":$(\'.cityClass\').html(),
                                    "scategory":$(\'.categoryClass\').html(),    
                                    "ssubcategory":subCategoryName,
                                    "sfilter":filter,
                                };
                                var tsTimer = setInterval(function(){
                                    if(typeof window.ts!== typeof undefined)
                                    {
                                        ts.save_parameters();
                                        clearInterval(tsTimer)
                                    }
                                },300);
</script>';
			break;
		default:
			break;


	}

}

if($callclass == 'home'){
	//$keyword = $this->input->get('keyword');
	switch($callmethod){
		case "index":
			
			echo '<script type="text/javascript" language="javascript">
                                var customFilterName = "'.$defaultCustomFilterName.'";
                                var customFreepaid = "'.$defaultCustomFreePaid.'";
                                var filter = new Array (
                                    customFilterName.charAt(0).toUpperCase() + customFilterName.slice(1),
                                    customFreepaid.charAt(0).toUpperCase() + customFreepaid.slice(1)    
                                );
                                tsa.activity_data = {
                                    "skeyword":"",
                                    "sresults":"'.$totalResultCount.'",                                    
                                    "scity":$(\'.cityClass\').html(),
                                    "scategory":$(\'.categoryClass\').html(),
                                    "ssubcategory":"", 
                                    "sfilter":filter,
                                };
                                var tsTimer = setInterval(function(){
                                    if(typeof window.ts!== typeof undefined)
                                    {
                                        console.log("IN HOMEPAGE SEARCH");
                                    	ts.save_parameters();
                                    	clearInterval(tsTimer);                                        
                                    }
                                },350);
</script>';
			break;
		default:
			break;


	}

}


if($callclass == 'payment'){
	switch($callmethod){
		case "index":
			echo '<script>
                                tsa.activity_data = {
                                    "fcategory" : "Booking Initiated"
                                };
                            </script>'; 
			break;
		default:
			break;

	}

}

if($callclass == 'confirmation'){
	
	$eventtitle = $eventData['title'];
	$categoryname = $eventData['categoryName'];
	switch($callmethod){
		case "index":
			echo '<script type="text/javascript">
                                tsa.activity_data = {
                                        "fcategory" : "Booking Success",
                                        "bsuccess": "Yes",
                                        "ts_invite_key" : "'.$eventsignupDetails['id'].'" //EventSignupId
                                };
                            </script>';
			break;
		default:
			break;


	}
}

}
?>
<!-- TrueSemantic Feedback form Ends Here -->