<?php 
$locality = $urlExtension  = "";
if ($defaultCityName == LABEL_ALL_CITIES) {	$locality = $defaultCountryName; }
else { 	$locality = $defaultCityName; }
?>
<script type="application/ld+json">
{
    "@context": "http://schema.org",
    "@type": "WebSite",
    "name" : "MeraEvents",
    "alternateName" : "MeraEvents <?php echo $locality; ?>",
    "url": "<?php echo $this->config->item('protocol'); ?><?php echo getenv('HTTP_HOST');?><?php echo $urlExtension; ?>",
    "potentialAction": {
        "@type": "SearchAction",
        "target": "<?php echo $this->config->item('protocol'); ?><?php echo getenv('HTTP_HOST');?>/search?keyword={search_term}",
        "query-input": "required name=search_term"
    }
}
</script>
<script>
    var defaultMatchingKeyword = "<?php if (isset($keyword) && trim($keyword) != '') { ?>matching  '<?php echo ( isset($keyword)) ? $keyword : ""; ?>'<?php } ?>";
        var api_commonRequestProcessRequest = "<?php echo commonHelperGetPageUrl('api_commonRequestProcessRequest') ?>";
        var api_subcategoryEventsCount = "<?php echo commonHelperGetPageUrl('api_subcategoryEventsCount') ?>";
        var api_cityEventsCount = "<?php echo commonHelperGetPageUrl('api_cityEventsCount') ?>";
        var api_categoryEventsCount = "<?php echo commonHelperGetPageUrl('api_categoryEventsCount') ?>";
        var api_filterEventsCount = "<?php echo commonHelperGetPageUrl('api_filterEventsCount') ?>";
        var api_categorycityEventsCount = "<?php echo commonHelperGetPageUrl('api_categorycityEventsCount') ?>";
        var api_subcategorycityEventsCount = "<?php echo commonHelperGetPageUrl('api_subcategorycityEventsCount') ?>";
        var api_eventList = "<?php echo commonHelperGetPageUrl('api_eventList') ?>";
        var api_searchSearchEventAutocomplete = "<?php echo commonHelperGetPageUrl('api_searchSearchEventAutocomplete') ?>";
        var api_eventEventsCount = "<?php echo commonHelperGetPageUrl('api_eventEventsCount') ?>";
        var api_searchSearchEvent = "<?php echo commonHelperGetPageUrl('api_searchSearchEvent') ?>";
</script>
<div class="page-container" ng-controller="filterController">
    <div class="wrap">
        <div class="container SearchContainer">
            <!-- Main component for a primary marketing message or call to action -->

            <div id="locationContainer" class="locSearchContainer ">
                <div class="hiddenfilter-xs hiddenfilter-md row {{selectedCategoryClass| lowercase}} searchresult" style="text-align: center">
                    <span class="icon icon1-{{selectedCategoryClass| lowercase}}" style="
                          font-size: 45px"></span>
                    <p ng-cloak>{{selectedCategoryName}}</p>
                    <a class="btn collapsed city collapse_bt" href="#headerDD" aria-expanded="false" aria-controls="collapseOne" ng-click="getEventCount('', 'city')" ng-init="defaultFilter()">
                        <span  id="selectedCity"  class="cityClass" >
                            <?php
                            if ($defaultCityName === "All Cities") {
                                echo $defaultCountryName;
                            } else {
                                echo $defaultCityName;
                            }
                            ?>
                        </span>
                        <span class="icon-downArrowH"></span>
                    </a>
                    <a class="btn collapsed categories collapse_bt" href="#headerDD1" aria-expanded="false"
                       aria-controls="collapseTwo" ng-click="getEventCount('', 'category')" ng-init="selectedCategoryId =<?php echo $defaultCategoryId; ?>"  ><span class="categoryClass"  ><?php echo $defaultCategory; ?></span> <span class="icon-downArrowH"></span></a>
                    <a class="btn collapsed showMore" ng-show="selectedCategoryClass != 'AllCategories'" href="#headerDD44" ng-click="getSubCategoryCount()" aria-expanded="false" aria-controls="showMore" ng-cloak><span class="subCategoryClass">{{selectedSubCategoryName}} </span><span class="icon-downArrowH"></span></a>

                    <a class="btn time collapsed collapse_bt"
                       href="#headerDD2" aria-expanded="false"
                       aria-controls="collapseThree" ng-init="selectedCustomFilterId =<?php echo $defaultCustomFilterId; ?>;
                                       selectedCustomFilterName = '<?php echo $defaultCustomFilterName; ?>'" ng-click="getEventCount('', 'customFilter')"><span   class="CustomFilterClass"    ><?php echo $defaultCustomFilterName; ?></span> <span
                            class="icon-downArrowH"></span></a>
                    <a ng-click="getEventRegCount()" class="btn collapsed collapse_bt" href="#headerDD33" aria-expanded="false" aria-controls="collapseFour" ng-cloak>
                         <span class="freepaid">{{typeName}}</span>&nbsp;<span class="icon-downArrowH"></span></a>
                    <span id="resetInput"  ng-click="reset()" class="icon-refresh"></span>
                </div>
            </div>

            <?php include 'includes/elements/search_filter.php'; ?>

            <!--EO carousal-->
            <div class="clearfix"></div>
            <script type="text/javascript">
                        var totalResultCount = "<?php echo ($eventsList['total'] >0 )? $eventsList['total'] : 0?>";
            </script>
            <div class="row">
                <h4 class="subHeadingFont">
                
                    <span id="searchres-show"<?php //if (count($eventsList['eventList']) > 0) {echo 'style="display:block"';}else{ echo 'style="display:none;"';}?> ng-cloak>Showing {{totalResultCount}} Event(s) in {{selectedCategoryName}} {{matchingKeyword}}</span>
                </h4>
                <div class="search-container searchABC" style="clear: both;top:-15px;" >
                    <input class="search form-control icon-me_search" value="<?php echo ( isset($keyword)) ? $keyword : ""; ?>"
                           id="searchId" type="search" ng-keyup="keywordSearch($event.keyCode)"
                           placeholder="Search by Event Name , Event ID , Kyey Words">

                    <a class="search icon-me_search"></a>
                </div>

                <div id="selectedFilter" class="hiddenfilter-lg hiddenfilter-md hiddenfilter-sm row">
                    <div class="tags filterCity col-xs-4"><span class="pull-right">X</span>Bengaluru </div>
                    <div class="tags filterCat col-xs-4"><span class="pull-right">X</span>Professional </div>
                    <div class="tags filterDate col-xs-4"><span class="pull-right">X</span> Tomorrow </div>
                </div>

                <ul id="eventThumbs" class="eventThumbs">
                    <?php
                    if (count($eventsList['eventList']) > 0) {
                        ?>				
                        <?php
                        $eventsListOnly = $eventsList['eventList'];
                        foreach ($eventsListOnly as $key => $eventData) {
                            $eventData['eventList'] = $eventsListOnly;
                            $eventData['key'] = $key;
                            $this->load->view('includes/elements/event', $eventData);
                        }
                    }?>
                    
                  
                </ul>
                <div class="clearBoth"></div>
                <div class="alignCenter" style="position: relative;">
                    <!-- <button class="btn btn-primary borderGrey">View More Events</button>-->
					<div id="nosearchresults" <?php if (count($eventsList['eventList']) > 0) {echo 'style="display:none"';}else{ echo 'style="display:block;"';}?> >
<!--					<p style="font-size: 30px; font-weight: bold" aria-expanded="false" aria-controls="popularEvents">Please move on </p>-->
                    	<a style="font-size:16px;" href="javascript:;" id="nosearchres" >click here to view all events.</a>
                    </div>
                    <a ng-click="getMoreEvents()" id="viewMoreEvents"
                       class="btn btn-primary borderGrey collapsed"
                       data-wipe="View More Events" style="position: relative; display:<?php echo (count($eventsList) > 0 && $eventsList['nextPage']) ? "inline-block" : "none"; ?>"
                       data-toggle="collapse" href="#popularEvents"
                       aria-expanded="false" aria-controls="popularEvents"> View
                        More Events </a>
					
                    <div id="noMoreEvents" style="position: relative;display:<?php echo ($eventsList['nextPage']) ? "none" : "inline-block"; ?>;"  >
<!--                        <p style="font-size: 30px; font-weight: bold"
                           aria-expanded="false" aria-controls="popularEvents"><?php// echo ERROR_NO_MOR_EVENTS; ?></p>-->
                        <?php if (count($eventsList['eventList']) > 0) { ?> 
                        <a id="returnToTop" style="font-size: 20px;  font-weight: normal;" href="javascript:;" >Please return to top</a>
                        <?php }?> 
                    </div>

                    <input type="hidden" id="currentPage" value="<?php echo $page + 1; ?>">
                    <input type="hidden" id="currentLimit" value="<?php echo $limit; ?>">
                </div>
            </div>
            <!-- EO Row -->
        </div>
    </div>
    <!-- /.wrap -->
</div>

<script>
$(document).on('click','#nosearchres',function(){
	var input = {names: ['cityId', 'categoryId', 'dayFilter', 'subCategoryId', 'splCityStateId'], values: [0, 0, 6, 0, 0]};
    updateCookieservice(input);
    setTimeout(function(){window.location.href=site_url;},2000);

});

  </script>