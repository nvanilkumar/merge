<?php
$hostname=  strtolower($_SERVER['HTTP_HOST']);
if(strcmp($hostname,"meraevents.com")==0 || strcmp($hostname,'www.meraevents.com')==0){
$idsite=2;
}else{ $idsite=1; }
$UserIdScript = NULL;
$userId = $this->customsession->getData('userId');
if(isset($userId) && $userId > 0)
{
//userid with salt from common helper
$piwik_user_id = recommendationsUserIdWithSalt($userId);
$UserIdScript = ' _paq.push(["setUserId", "'.$piwik_user_id.'"]);';			
}
$eventId = isset($eventData['id'])?$eventData['id']:NULL;
//$callclass = $this->router->fetch_class();
//$callmethod = $this->router->fetch_method();
//if($callclass == 'home' || $callclass == 'search'){}
$script='<!-- Piwik -->
<script type="text/javascript">
var _paq = _paq || [];
_paq.push(["setCookieNamePrefix","me_"]);
 _paq.push(["setCustomVariable","1","ItemID","'.$eventId.'","page"]);';		   
$script.=  $UserIdScript; 		
$script.= '_paq.push(["trackPageView"]);
 _paq.push(["enableLinkTracking"]);
(function() {
var u="//piwik.stage.meraevents.com/piwik/";
_paq.push(["setTrackerUrl", u+"piwik.php"]);
_paq.push(["setSiteId", '.$idsite.']);';				
$script.= 'var d=document, g=d.createElement("script"), s=d.getElementsByTagName("script")[0];
g.type="text/javascript"; g.async=true; g.defer=true; g.src=u+"piwik.min.js.gz"; s.parentNode.insertBefore(g,s);
})();
</script>
<noscript><p><img src="//piwik.stage.meraevents.com/piwik/piwik.php?idsite='.$idsite.'" style="border:0;" alt="" /></p></noscript>
<!-- End Piwik Code -->';               	
echo $script;
?>