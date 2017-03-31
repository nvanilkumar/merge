<!-- Start of top filter   - D -->
<div id="locationContainer" class="locSearchContainer"  >
<span class="hiddenfilter-lg hiddenfilter-md hiddenfilter-sm">Filters</span><span class="CloseFilter">
<img src="<?php echo $this->config->item('images_static_path'); ?>icon-check.png">
</span>
<a class="btn collapsed city collapse_bt hiddenfilter-lg hiddenfilter-md hiddenfilter-sm"
 href="#headerDD" aria-expanded="false" aria-controls="collapseOne"  ng-click="getEventCount('','city')" ng-init="defaultFilter()"> <span class="icon_city hiddenfilter-lg hiddenfilter-md"></span><span  id="selectedCity"  class="cityClass" ><?php echo $defaultCityName;?></span>
<span class="icon-downArrowH"></span>
</a>
<div class="SearchFilter_Holder hiddenfilter-xs hiddenfilter-md "   >
You are in  <a class="btn collapsed city collapse_bt" href="#headerDD" aria-expanded="false" aria-controls="collapseOne" ng-click="getEventCount('','city')" ng-init="defaultFilter()"><span  id="selectedCity"  class="cityClass" ><?php if($defaultCityName === "All Cities"){echo $defaultCountryName;}else{echo $defaultCityName;} ?></span>
<span class="icon-downArrowH"></span>
</a> looking for <a class="btn collapsed categories collapse_bt" href="#headerDD1" aria-expanded="false"
 aria-controls="collapseTwo" ng-click="getEventCount('','category')" ng-init="selectedCategoryId=<?php echo $defaultCategoryId;?>"  ><span class="categoryClass"  ><?php if($defaultCategory === "All Categories"){echo "All";}else{echo $defaultCategory;} ?></span> <span class="icon-downArrowH"></span></a>
events happening <a class="btn time collapsed collapse_bt"
 href="#headerDD2" aria-expanded="false"
 aria-controls="collapseThree" ng-init="selectedCustomFilterId=<?php echo $defaultCustomFilterId;?> ; selectedCustomFilterName='<?php echo $defaultCustomFilterName;?>'" ng-click="getEventCount('','customFilter')"><span   class="CustomFilterClass"    ><?php echo $defaultCustomFilterName;?></span> <span
 class="icon-downArrowH"></span></a><span id="resetInput"  ng-click="reset()" class="icon-refresh"></span>
</div>
<a class="btn collapsed categories collapse_bt hiddenfilter-lg hiddenfilter-md hiddenfilter-sm"
  href="#headerDD1" aria-expanded="false"
  aria-controls="collapseTwo" ng-click="getEventCount('','category')" ng-init="selectedCategoryId=<?php echo $defaultCategoryId;?>"  ><span class="icon_cat hiddenfilter-lg hiddenfilter-md"></span><span class="categoryClass"  ><?php if($defaultCategory === "All Categories "){echo "All";}else{echo  $defaultCategory;} ?></span> <span class="icon-downArrowH"></span></a>
<a class="btn time collapsed collapse_bt hiddenfilter-lg hiddenfilter-md hiddenfilter-sm"
  href="#headerDD2" aria-expanded="false"
  aria-controls="collapseThree" ng-init="selectedCustomFilterId=<?php echo $defaultCustomFilterId;?> ; selectedCustomFilterName='<?php echo $defaultCustomFilterName;?>'" ng-click="getEventCount('','customFilter')"><span class="icon_date hiddenfilter-lg hiddenfilter-md"> </span><span   class="CustomFilterClass"    ><?php echo $defaultCustomFilterName;?></span>
<span class="icon-downArrowH"></span></a>
<div class="filterdiv hiddenfilter-lg hiddenfilter-md  city-search-list" id="headerDD" style="width: 100%;">  
<div class="accTextCont cityList">
<span class="floatR locSearchContainer  btnClass"><a
  href="javascript:void(0)" class="btn"><img
  src="<?php echo $this->config->item('images_static_path'); ?>close-icon.png"></a> </span>
<h6>Top Cities</h6>
<ul ng-init="init(<?php echo htmlspecialchars(json_encode($cityList)); ?>,'city')">
<li ng-repeat="city in cityList | orderBy:'name'"  id="{{city.id}}_mobcity">
<a  title="{{city.name}}" ng-click="setFilter('city',city.id,city.name, 0,city.splcitystateid)"  >
<label ng-cloak>{{city.name}} <span ng-cloak>{{city.eventCount}}</span>
</label>
</a>
</li>
<li>
<a  title="All Cities" ng-click="setFilter('city',0,'All Cities', 0,0)">
<label>All Cities <span ng-cloak>{{allCityCount}}</span></label>
</a>
</li>
</ul>
<?php include 'city_search.php';?>
<div class="clearBoth"></div>
</div>
</div>
<div class="filterdiv hiddenfilter-sm hiddenfilter-xs  city-search-list" id="headerDD" style="width: 100%;">    
<div class="accTextCont cityList">
<span class="floatR locSearchContainer  btnClass"><a
  href="javascript:void(0)" class="btn"><img
  src="<?php echo $this->config->item('images_static_path'); ?>close-icon.png"></a> </span>
<h6>Top Cities</h6>
<ul ng-init="init(<?php echo htmlspecialchars(json_encode($cityList)); ?>,'city')">
<li ng-repeat="city in cityList | orderBy:'name'"  id="{{city.id}}_city">
<a title="{{city.name}}" ng-click="setFilter('city',city.id,city.name, 0,city.splcitystateid)"  > <label ng-cloak>{{city.name}} <span ng-cloak>{{city.eventCount}}</span>
</label>
</a>
</li>
<li>
<a  title="All Cities" ng-click="setFilter('city',0,'All Cities', 0,0)">
<label>All Cities <span ng-cloak>{{allCityCount}}</span></label>
</a>
</li>              
</ul>
<?php include 'city_search.php';?>
<div class="clearBoth"></div>
</div>
</div> 

<div class="filterdiv hiddenfilter-lg hiddenfilter-md category-search-list" id="headerDD1" style="width: 100%; margin-bottom : 150px;">
<div class="accTextCont categoryList">
<span class="floatR locSearchContainer btnClass"> <a
 href="javascript:void(0)" class="btn"><img
 src="<?php echo $this->config->item('images_static_path'); ?>close-icon.png"></a>
</span>
<h6>Top Categories</h6>
<ul ng-init="init(<?php echo htmlspecialchars(json_encode($categoryList)); ?>,'category')">
<li ng-repeat="category in categoryList | orderBy:'name'" id="{{category.id}}_mobcategory">
<a  title="{{category.name}}"  ng-click="setFilter('category',category.id,category.name, 0,0)" >
<i class="icon1-{{category.name | replaceSpaceFilter | lowercase}} col{{category.name | replaceSpaceFilter| lowercase}} "></i>   
<label ng-cloak>{{category.name}}
<span ng-cloak>{{category.eventCount}}</span>
</label>
</a></li>
<li>
<a href="javascript:void(0)" title="All Categories"  ng-click="setFilter('category', 0, 'All Categories', 0,0)" >
<label>All Categories <span ng-cloak>{{allCategoryCount}}</span></label>
</a>
</li>
<li id="mobsubcat"   style="position: relative;  display:<?php echo ( $defaultCategoryId > 0)?"inline-block":"none !important";?>" >
<a  ng-click="getSubCategoryCount()" class="btn collapsed showSubCategories  showMore"
 data-parent="#headerDD1" href="#showMOre" aria-expanded="false"
 aria-controls="showMore"> Show Sub Categories </a>
<input id="isSubCatClosed" value="0" type="hidden" /> 
</li>
</ul>
<div class="clearBoth"></div>
</div>
<div class="filterdiv" id="showMOre"
 style="border-bottom: 1px solid #EBEBEB;">
 <div class="showMoreCat">
 <span ng-if="subcatErrorMessage != '' " ng-cloak>{{subcatErrorMessage}}</span>
<ul ng-init="init('','subCategoryList')">
<h6>Sub Category</h6>
<hr>                 
<li ng-repeat="subcategory in subCategoryList | orderBy:'name'">
<a  title="{{subcategory.name}}" ng-click="setFilter('Subcategory',subcategory.id,subcategory.name, 0,0)">
<label ng-cloak>{{subcategory.name}}
<span ng-cloak>({{subcategory.count}})</span>
</label> 
</a>
</li>
<li class="subcategorysearch"><?php include 'subcategory_search.php';?></li>       
</ul>
<div class="clearBoth"></div>
</div>
</div>
</div>
<div class="filterdiv hiddenfilter-sm hiddenfilter-xs category-search-list" id="headerDD1" style="width: 100%;">
<div class="accTextCont categoryList">
<span class="floatR locSearchContainer btnClass"> <a
 href="javascript:void(0)" class="btn"><img
 src="<?php echo $this->config->item('images_static_path'); ?>close-icon.png"></a>
</span>
<h6>Top Categories</h6>
<ul ng-init="init(<?php echo htmlspecialchars(json_encode($categoryList)); ?>,'category')">
<li ng-repeat="category in categoryList | orderBy:'name'" id="{{category.id}}_category">
<a  title="{{category.name}}"  ng-click="setFilter('category',category.id,category.name, 0,0)" >
<i class="icon1-{{category.name | replaceSpaceFilter | lowercase}} col{{category.name | lowercase}}"></i>     
<label ng-cloak>{{category.name}}
<span ng-cloak>{{category.eventCount}}</span>
</label>
</a></li>
<li>
<a href="javascript:void(0)" title="All Categories"  ng-click="setFilter('category', 0, 'All Categories', 0,0)" >
<label>All Categories <span ng-cloak>{{allCategoryCount}}</span></label>
</a>
</li>
<li id="subcat2" style="position: relative;  display:<?php echo ( $defaultCategoryId > 0)?"inline-block":"none";?>" >
<a id="showSubCateg" ng-click="getSubCategoryCount()" class="btn collapsed showSubCategories  showMore"
 data-parent="#headerDD1" href="#showMOre" aria-expanded="false"
 aria-controls="showMore"> Show Sub Categories </a>
<input id="isSubCatClosed" value="0" type="hidden" />
</li>
</ul>
<div class="clearBoth"></div>
</div>
<div class="filterdiv" id="showMOre"
 style="border-bottom: 1px solid #EBEBEB;">
<div class="showMoreCat">
<span ng-if="subcatErrorMessage != '' " ng-cloak>{{subcatErrorMessage}}</span>
<ul ng-init="init('','subCategoryList')">
<h6>Sub Category</h6>
<hr>                 
<li ng-repeat="subcategory in subCategoryList | orderBy:'name'"><a  title="{{subcategory.name}}" ng-click="setFilter('Subcategory',subcategory.id,subcategory.name, 0,0)">
<label ng-cloak>{{subcategory.name}}
<span ng-cloak>({{subcategory.count}})</span>
</label> 
</a></li>
<li class="subcategorysearch"><?php include 'subcategory_search.php';?></li>                      
</ul>
<div class="clearBoth"></div>
</div>
</div>
</div>  
<div class="filterdiv search-timelist" id="headerDD2" style="width: 100%;">
<div class="accTextCont timeList">
<span class="floatR locSearchContainer btnClass hiddenfilter-xs">
<a href="javascript:void(0)" class="btn">
<img src="<?php echo $this->config->item('images_static_path'); ?>close-icon.png">
</a>
</span>
<h6>Dates</h6>
<ul  ng-init="init(<?php echo htmlspecialchars(json_encode($customFilterList)); ?>,'customFilter')">
<li ng-repeat="customFilter in customFilterList " id="{{customFilter.id}}_dates">
<a ng-if="customFilter.id<7" title="{{customFilter.name}}" ng-click="setFilter('CustomFilter',customFilter.id,customFilter.name, 0,0)" >
<label ng-cloak>{{customFilter.name}}
<span ng-cloak>{{customFilter.eventCount}}</span>
</label>
</a>
<input ang-datepicker ng-model="customDateValue" ng-if="customFilter.id==7" type="text" 
 id="datepicker" readonly filter-id="{{customFilter.id}}" filter-name="{{customFilter.name}}"
 class="cal_styles custom_date" style=""
 placeholder="{{customFilter.name}}" value="{{customDateValue}}" />
</li>                
</ul>
<div class="clearBoth"></div>
</div>
</div>
</div>
<!-- End of top filter   - D -->