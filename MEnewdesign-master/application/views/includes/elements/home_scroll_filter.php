<?php
$displayHeaderType = $this->uri->segment(1);
$displayMethodType = $this->uri->segment(2);
$createEditDisplayHeader = $this->uri->segment(3);
$userId = $this->customsession->getUserId();
$isLogin = ($userId > 0) ? 1 : 0 ;
$isAttendee = $this->customsession->getData('isAttendee');
$isPromoter = $this->customsession->getData('isPromoter');
$isOrganizer = $this->customsession->getData('isOrganizer');
/*$profileImagePath = $this->customsession->getData('profileImagePath');
 $profileImage = commonHelperDefaultImage($profileImagePath, 'userprofile');*/
?>
<!-- on scroll code-->
<div class="onScrollContainer"  >
<div class="topContainer">
<div class="wrap">
<div class="onScrollContainer__container">
<div class="navbar-header">
<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
<span class="sr-only">Toggle navigation</span> <span class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span>
</button>
<div class="logo_align">
<a class="navbar-brand onScrollContainer__site-logo" href="<?php echo site_url();?>">
<img src="<?php echo $this->config->item('images_static_path'); ?>me-logo.svg" alt="" />
</a>
</div>
</div>
<div class="btn-group ddCustom selCountry countryhome">
<?php foreach ($countryList as $value) { 
if($value['id']==$defaultCountryId){
?>
<a class="btn headerDD" href="javascript:void(0)">
<span class="status">
<span class="country-flag"><img src="<?php echo $value['logoFilePath']; ?>"></span>
<span class="country-code"><?php echo $value['shortName'];?></span>
</span>
</a>
<?php }   }?>
<!--append class "ddBG" if you wnat bg color-->
<ul class="dropdown-menu menu dropdown-inverse countryList ddBG headerDD" >
<?php foreach ($countryList as $value) { 
//   if($value['id']!=$defaultCountryId){
?>
<li onclick="setCookie('countryId', '<?php echo $value['id']; ?>', '<?php echo COOKIE_EXPIRATION_TIME;?>')" value="<?php echo $value['id'];?>">
<a href="javascript:void(0);"> 
<span class="country-flag">
<img src="<?php echo $value['logoFilePath']; ?>">
</span>
<span class="country-code"><?php echo $value['shortName']; ?></span>
</a>
</li>
<?php     //  }
}?>
</ul> 
<a class="btn  btn-lg btn-sm btn-md dropdown-toggle" type="button" data-toggle="dropdown">
<span class="icon-downArrow"></span>
</a>
</div>
<div class="locSearchContainer filterScrollSearch">
You are in  <a class="btn collapsed city collapse_bt"
 href="#headerDD3" aria-expanded="false"
 aria-controls="collapseOne" ng-click="getEventCount('','city')" ng-init="defaultFilter()" > <span class="cityClass" ><?php if($defaultCityName === "All Cities"){echo $defaultCountryName;}else{echo $defaultCityName;} ?></span> <span
 class="icon-downArrowH"></span></a> looking for <a
 class="btn collapsed categories collapse_bt" href="#headerDD4"
 aria-expanded="false" aria-controls="collapseTwo" ng-click="getEventCount('','category')" ng-init="selectedCategoryId=<?php echo $defaultCategoryId;?>"  ><span class="categoryClass"  ><?php if($defaultCategory === "All Categories"){echo "All";}else{echo $defaultCategory;} ?></span> <span
 class="icon-downArrowH"></span></a> events happening
 <a class="btn time collapsed collapse_bt" href="#headerDD5"
  aria-expanded="false" aria-controls="collapseThree"
  ng-init="selectedCustomFilterId=<?php echo $defaultCustomFilterId;?>; selectedCustomFilterName='<?php echo $defaultCustomFilterName;?>'"
  ng-click="getEventCount('','customFilter')">
<span class="CustomFilterClass" ><?php echo $defaultCustomFilterName;?></span>
<span class="icon-downArrowH"></span>
</a>
<span id="resetInput"  ng-click="reset()" class="icon-refresh resetInput" ></span>
<div class="filterdiv" id="headerDD3">
<div class="accTextCont cityList">
<span class="close_icon"><a href="javascript:void(0)" class="btn"><img src="<?php echo $this->config->item('images_static_path'); ?>close-icon.png"></a> </span>
<h6>top cities</h6>
<ul ng-init="init(<?php echo htmlspecialchars(json_encode($cityList)); ?>,'city')">
<li  ng-repeat="city in cityList | orderBy:'name'" id="{{city.id}}_scrollcity" ><a title="{{city.name}}" ng-click="setFilter('city',city.id,city.name, 0,city.splcitystateid)"  > <label ng-cloak>{{city.name}}
<span ng-cloak>{{city.eventCount}}</span></label></a></li>
<li><a  title="All Cities" ng-click="setFilter('city',0,'All Cities', 0,0)"> <label>All Cities  <span ng-cloak>{{allCityCount}}</span></label>
</a></li>                                                        
</ul>
<?php include 'city_search.php';?>
<div class="clearBoth"></div>
</div>
</div>
<div class="filterdiv" id="headerDD4">
<div class="accTextCont categoryList">
<span class="close_icon"> <a href="javascript:void(0)" class="btn"> <img src="<?php echo $this->config->item('images_static_path'); ?>close-icon.png"></a></span>
<h6>top categories</h6>
<ul ng-init="init(<?php echo htmlspecialchars(json_encode($categoryList)); ?>,'category')">
<li ng-repeat="category in categoryList | orderBy:'name'" id="{{category.id}}_scrollctg">
<a   title="{{category.name}}"  ng-click="setFilter('category',category.id,category.name, 0,0)" >
<i class="icon1-{{category.name |replaceSpaceFilter | lowercase}} col{{category.name|replaceSpaceFilter | lowercase}} "></i>   
<label ng-cloak>{{category.name}}
<span ng-cloak>{{category.eventCount}}</span>
</label>
</a></li>
<li><a href="javascript:void(0)" title="All Categories" ng-click="setFilter('category',0,'All Categories', 0,0)" > <label>All Categories<span ng-cloak>{{allCategoryCount}}</span></label>
</a></li>
<li id="subcat1" style="display:<?php echo ( $defaultCategoryId > 0)?"inline-block":"none"; ?>">
<a id="showSubCategoriesAnchor" ng-click="getSubCategoryCount()" 
 class="btn collapsed showSubCategories showMore" 								   data-parent="headerDD4" href="#showMOre1"
 aria-expanded="false" aria-controls="showMore"> 
 Show Sub Categories
 </a>
</li>
</ul>
<div class="clearBoth"></div>
</div>
<div class="filterdiv" id="showMOre1"
 style="border-bottom: 1px solid #EBEBEB;">
<div class="showMoreCat">
<span ng-if="subcatErrorMessage != '' " ng-cloak>{{subcatErrorMessage}}</span>
<ul ng-init="init('','subCategoryList')" class="overflow1">
<h6>Sub Category</h6>
<hr>                 
<li ng-repeat="subcategory in subCategoryList | orderBy:'name'">
<a  title="{{subcategory.name}}" ng-click="setFilter('Subcategory',subcategory.id,subcategory.name, 0,0)">
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
<div class="filterdiv" id="headerDD5" style="width: 100%;">
<div class="accTextCont timeList">
<span class="close_icon"> <a href="javascript:void(0)" class="btn"><img src="<?php echo $this->config->item('images_static_path'); ?>close-icon.png"></a></span>
<h6>Dates</h6>
<ul  ng-init="init(<?php echo htmlspecialchars(json_encode($customFilterList)); ?>,'customFilter')">
<li ng-repeat="customFilter in customFilterList " id="{{customFilter.id}}_scrolldt"><a ng-if="customFilter.id<7" title="{{customFilter.name}}"  ng-click="setFilter('CustomFilter',customFilter.id,customFilter.name, 0,0)" > <label ng-cloak>{{customFilter.name}}
<span ng-cloak>{{customFilter.eventCount}}</span>
</label>
</a>
<input ang-datepicker ng-model="customDateValue" ng-if="customFilter.id==7" type="text" id="datepicker" readonly filter-id="{{customFilter.id}}" filter-name="{{customFilter.name}}"
 class="cal_styles custom_date" style=""
 placeholder="{{customFilter.name}}" value="{{customDateValue}}" />
</li>
</ul>
<div class="clearBoth"></div>
</div>
</div>
</div>
<div class="search-container">
<input class="search form-control searchExpand icon-me_search"
 type="search" id="searchId" placeholder="Hi there! Let's search events"> <a
 class="search icon-me_search"></a>
</div>
<div class="navbar-collapse collapse">
<ul class="nav navbar-nav navbar-right">
<?php if ($userId) { ?>
<li class="dropdown">
<a class="dropdown-toggle afterlogindiv" style="cursor: pointer;"
 data-toggle="dropdown" role="button" aria-expanded="false"
 id="nav-toggle" onclick="getProfileLink('header');" href="javascript:;">
<img src="<?php echo $this->config->item('images_static_path'). DEFAULT_PROFILE_IMAGE; ?>" alt="<?php echo $this->config->item('images_static_path'). DEFAULT_PROFILE_IMAGE; ?>">
</a>
<ul class="dropdown-menu profile-dropdown" role="menu">
</ul>
</li>
<?php } else{?>
<li class="dropdown">
<a href="#" class="dropdown-toggle"
data-toggle="dropdown" role="button" aria-expanded="false"
 id="nav-toggle">
<span class="icon-set"></span>
</a>
<ul class="dropdown-menu" role="menu">
<li><a href="javascript:void(0);"><span class="icon2-question-circle"></span>Help</a></li>
<?php echo commonHtmlElement('create-event'); ?>
<?php echo commonHtmlElement('logout', $isLogin); ?>
</ul>
</li>
<?php } ?>
</ul>
</div>
</div>
</div>
</div>
</div>
</div>
<!-- END OF  on scroll code-->