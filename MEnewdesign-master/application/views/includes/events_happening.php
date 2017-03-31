<!--Eventa happening-->
<div class="container eventsHappening">
<p id="totalCount" class="totalCount"><?php echo $eventsHappeningTotal;?></p>
<h1 id="eventHappeningCity" ng-cloak >Events in {{selectedCityName}}</h1>  
<div class="eventsCat">
<ul ng-init="eventsData=<?php echo htmlspecialchars(json_encode(array_values($eventsHappeningCounts)));?>;" id="eventsHappening">
<li class="col-lg-2 col-md-2 col-sm-4 col-xs-6" ng-repeat="data in eventsData|orderBy:'-eventCount'|limitTo:6">
<span style="cursor: pointer;" onclick="eventsHappeningRedirect('<?php echo commonHelperGetPageUrl("search"); ?>', this)" id="{{data.id}}" > 
<img ng-src="{{data.imagefileid}}" alt="{{data.name}}" title="{{data.name}}" style="border-bottom: 4px solid {{data.themecolor}}" ng-cloak> <span class="eventsCount colorWhite" ng-cloak>{{data.eventCount}}</span>
<h6 class="eventName colorWhite" ng-cloak>{{data.name}}</h6>
</span></li>
</ul>
<div class="clearBoth"></div>
<br>
<div class="alignCenter">
<div class="collapse" id="viewMore" aria-expanded="false">
<div class="showMoreCat">
<ul id="eventsMoreHappening" ng-init="subcatEventsData=[]"> 
<li ng-repeat="data in subcatEventsData|orderBy:'-count'" onclick="eventsHappeningSubcategoryRedirect('<?php echo commonHelperGetPageUrl("search"); ?>', this)" id="{{data.id}}" catid="{{data.categoryId}}" subcatname="{{data.name}}"><a href="javascript:void(0)" title="{{data.name}}"> <label ng-cloak>{{data.name}}</label>
<span ng-cloak>({{data.count}})</span></a></li>
</ul>
<div class="clearBoth"></div>
</div>
</div>
<a id="viewMoreCat" ng-click = "viewMoreSubcategories()" class="btn btn-primary borderGrey collapsed"
 style="position: relative" data-toggle="collapse"
 href="#viewMore" aria-expanded="false" aria-controls="viewMore">
View more </a>
</div>
</div>
</div>