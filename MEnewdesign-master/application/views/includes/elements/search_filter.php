

<div id="locationContainer" class="locSearchContainer"  >

    <div class="filterdiv hiddenfilter-lg hiddenfilter-md" id="headerDD" style="width: 100%;">
        <div class="accTextCont cityList">
            <h6>top cities</h6>
            <ul>
                <?php
                foreach ($cityList as $cityKey => $cityValue) {
                    ?> 
                    <li><a href="javascript:void(0)" title="city" > <label><?php echo $cityValue['name']; ?>
                                <span ng-cloak>{{<?php echo "eventcount" . str_replace(' ','',$cityValue['name']); ?>}}</span></a></label></li>
                    <?php
                }
                ?>
                <li>
                    <a href="javascript:void(0)" title="city">
                        <label>All Cities</label>
                    </a>
                </li>
                <li class="eventHappSearch"><input type="text" id="searchCityId2" placeholder="Enter your city"> </li>
            </ul>
            <div class="clearBoth"></div>
        </div>
    </div>
    <a class="btn collapsed categories collapse_bt hiddenfilter-lg hiddenfilter-md hiddenfilter-sm"
       href="#headerDD1" aria-expanded="false"
       aria-controls="collapseTwo"><span class="icon_cat hiddenfilter-lg hiddenfilter-md"></span>All Categories <span class="icon-downArrowH"></span></a>

    <a class="btn time collapsed collapse_bt hiddenfilter-lg hiddenfilter-md hiddenfilter-sm"
       href="#headerDD2" aria-expanded="false"
       aria-controls="collapseThree"> <span class="icon_date hiddenfilter-lg hiddenfilter-md"> </span>All Time <span
            class="icon-downArrowH"></span></a>



    <div class="filterdiv hiddenfilter-sm hiddenfilter-xs" id="headerDD" style="width: 100%;">
        <div class="accTextCont cityList">
                <!--<span class="icon-food floatR"></span>-->
            <span class="floatR locSearchContainer  btnClass"><a
                    href="javascript:void(0)" class="btn"><img
                        src="<?php echo $this->config->item('images_static_path'); ?>close-icon.png"></a> </span>
            <h6>Top Cities</h6>
            <ul ng-init="init(<?php echo htmlspecialchars(json_encode($cityList)); ?>, 'city')">
                <li  ng-repeat="city in cityList| orderBy:'name'"  id="{{city.id}}_city">
                    <a title="{{city.name}}" ng-click="setFilter('city', city.id, city.name, 0, city.splcitystateid)"  >
                        <label ng-cloak>{{city.name}} <span ng-cloak>{{city.eventCount}}</span>
                        </label>
                    </a>
                </li>
                <li>
                    <a  title="All Cities" ng-click="setFilter('city', 0, 'All Cities', 0, 0)">
                        <label>All Cities <span ng-cloak>{{allCityCount}}</span></label>
                    </a>
                </li>
            </ul>
            <?php include 'city_search.php'; ?>
            <div class="clearBoth"></div>
        </div>
    </div>
    <div class="filterdiv hiddenfilter-sm hiddenfilter-xs" id="headerDD1" style="width: 100%;">
        <div class="accTextCont categoryList">
            <span class="floatR locSearchContainer btnClass"> <a
                    href="javascript:void(0)" class="btn"><img
                        src="<?php echo $this->config->item('images_static_path'); ?>close-icon.png"></a>
            </span>
            <h6>Top Categories</h6>
            <ul ng-init="init(<?php echo htmlspecialchars(json_encode($categoryList)); ?>, 'category')">


                <li ng-repeat="category in categoryList| orderBy:'name'" id="{{category.id}}_category">
                    <a title="{{category.name}}"  ng-click="setFilter('category', category.id, category.name, 0, 0)" >
                        <i class="icon1-{{category.name|replaceSpaceFilterSearch |lowercase}} col{{category.name| lowercase}} "></i>       
                        <label ng-cloak>{{category.name}}
                            <span ng-cloak>{{category.eventCount}}</span>
                        </label>
                    </a></li>
                <li>
                    <a href="javascript:void(0)" title="All Categories"  ng-click="setFilter('category', 0, 'All Categories', 0, 0)" >
                        <label>All Categories <span ng-cloak>{{allCategoryCount}}</span></label>
                    </a>
                </li>
            </ul>
            <div class="clearBoth"></div>
        </div>
        <div class="filterdiv" id="showMOre"
             style="border-bottom: 1px solid #EBEBEB;">
            <div class="showMoreCat">
                <span ng-if="subcatErrorMessage != ''" ng-cloak>{{subcatErrorMessage}}</span>
                <ul ng-init="init('', 'subCategoryList')" >
                    <h6>Sub Category</h6>
                    <hr>                 
                    <li ng-repeat="subcategory in subCategoryList| orderBy:'name'">
                        <a  title="{{subcategory.name}}" ng-click="setFilter('Subcategory', subcategory.id, subcategory.name, 0, 0)">
                            <label ng-cloak>{{subcategory.name}}
                                <span ng-cloak>({{subcategory.count}})</span>
                            </label> 
                        </a>
                    </li>

                </ul>
                <?php include 'subcategory_search.php'; ?>
                <div class="clearBoth"></div>
            </div>
        </div>
    </div>
    <div class="filterdiv" id="headerDD2" style="width: 100%;">
        <div class="accTextCont timeList">
            <span class="floatR locSearchContainer btnClass hiddenfilter-xs">
                <a href="javascript:void(0)" class="btn">
                    <img src="<?php echo $this->config->item('images_static_path'); ?>close-icon.png">
                </a>
            </span>
            <h6>Dates</h6>
            <ul  ng-init="init(<?php echo htmlspecialchars(json_encode($customFilterList)); ?>, 'customFilter')">
                <li ng-repeat="customFilter in customFilterList" id="{{customFilter.id}}_dates">
                    <a ng-if="customFilter.id < 7 && customFilter.id != 6" title="{{customFilter.name}}" ng-click="setFilter('CustomFilter', customFilter.id, customFilter.name, 0, 0)" >
                        <label ng-cloak>{{customFilter.name}}
                            <span ng-cloak>{{customFilter.eventCount}}</span>
                        </label>
                    </a>
                    <a ng-if="customFilter.id == 6" title="time" ng-click="setFilter('CustomFilter', customFilter.id, 'time', 0, 0)" >
                        <label ng-cloak>{{customFilter.name}}
                            <span ng-cloak>{{customFilter.eventCount}}</span>
                        </label>
                    </a>
                    <input ang-datepicker ng-model="customDateValue" ng-if="customFilter.id == 7" type="text" 
                           id="datepicker" readonly filter-id="{{customFilter.id}}" filter-name="{{customFilter.name}}"
                           class="cal_styles custom_date" style=""
                           placeholder="{{customFilter.name}}" value="{{customDateValue}}" />
                </li>
            </ul>
            <div class="clearBoth"></div>
        </div>
    </div>
    <div class="filterdiv" id="headerDD33" style="width: 100%;">
        <div class="accTextCont freepaidList">
                <!--<span class="icon-food floatR"></span>-->

            <ul>
                <li id="1_type"><a href="javascript:void(0)" title="Free" ng-click="setFilter('freepaid', 1, 'free', 0, 0)"> <label>
                            Free
                            <span ng-cloak>({{freeCount}})</span>
                        </label>
                    </a></li>
                <li id="2_type"><a href="javascript:void(0)" title="Paid" ng-click="setFilter('freepaid', 2, 'paid', 0, 0)"> <label>
                            Paid <span ng-cloak>({{paidCount}})</span>
                        </label>
                    </a></li>
                <li id="4_type"><a href="javascript:void(0)" title="Webinar" ng-click="setFilter('freepaid', 4, 'webinar', 0, 0)"> <label>
                            Webinar <span ng-cloak>({{webinarCount}})</span>
                        </label>
                    </a></li>
                <li id="3_type"><a href="javascript:void(0)" title="Info" ng-click="setFilter('freepaid', 3, 'Info Only', 0, 0)"> <label>
                            Info Only <span ng-cloak>({{noRegCount}})</span>
                        </label>
                    </a></li>
                <li><a href="javascript:void(0)" title="All" ng-click="setFilter('freepaid', '', 'All', 0, 0)"> <label>
                            All <span ng-cloak>({{allReg}})</span>
                        </label>
                    </a></li>
            </ul>
            <div class="clearBoth"></div>
        </div>
    </div>

    <div class="filterdiv" id="headerDD44" style="border-bottom: 1px solid #EBEBEB;">
        <div class="showMoreCat">
            <ul ng-init="init('', 'subCategoryList')">              
                <li ng-repeat="subcategory in subCategoryList| orderBy:'name'">
                    <a  title="{{subcategory.name}}" ng-click="setFilter('Subcategory', subcategory.id, subcategory.name, 0, 0)">
                        <label ng-cloak>{{subcategory.name}}
                            <span ng-cloak>({{subcategory.count}})</span>
                        </label> 
                    </a></li>
                <li ng-if="allsubCategoryCount > 0">
                    <a href="javascript:void(0)" title="All Subcategories"  ng-click="setFilter('Subcategory', 0, 'Subcategories', 0, 0)" >
                        <label>All Subcategories <span>({{allsubCategoryCount> 0 ?allsubCategoryCount:"";}})</span></label></a>
                </li>
            </ul>
            <div class="clearBoth"></div>
        </div>
    </div>


</div>

<div id="locationContainerMobile" class="locSearchContainer">

    <!--Mobile start cities-->
    <span class="hiddenfilter-lg hiddenfilter-md hiddenfilter-sm">Filters</span><span class="CloseFilter">
        <img src="<?php echo $this->config->item('images_static_path'); ?>icon-check.png">
    </span>
    <a class="btn collapsed city collapse_bt hiddenfilter-lg hiddenfilter-md hiddenfilter-sm"
       href="#headerDD" aria-expanded="false" aria-controls="collapseOne"  ng-click="getEventCount('', 'city')" ng-init="defaultFilter()"> <span class="icon_city hiddenfilter-lg hiddenfilter-md"></span><span  id="selectedCity"  class="cityClass" ><?php echo $defaultCityName; ?></span>
        <span class="icon-downArrowH"></span>
    </a><!--Mobile-->

    <!--Mobile start categories-->
    <a class="btn collapsed categories collapse_bt hiddenfilter-lg hiddenfilter-md hiddenfilter-sm"
       href="#headerDD1" aria-expanded="false"
       aria-controls="collapseTwo" ng-click="getEventCount('', 'category')" ng-init="selectedCategoryId =<?php echo $defaultCategoryId; ?>"  ><span class="icon_cat hiddenfilter-lg hiddenfilter-md"></span><span class="categoryClass"  ><?php
               if ($defaultCategory === "All Categories ") {
                   echo "All";
               } else {
                   echo $defaultCategory;
               }
               ?></span> <span class="icon-downArrowH"></span></a><!--Mobile-->


    <!--Mobile start time-->
    <a class="btn time collapsed collapse_bt hiddenfilter-lg hiddenfilter-md hiddenfilter-sm"
       href="#headerDD2" aria-expanded="false"
       aria-controls="collapseThree" ng-init="selectedCustomFilterId =<?php echo $defaultCustomFilterId; ?>;
               selectedCustomFilterName = '<?php echo $defaultCustomFilterName; ?>'" ng-click="getEventCount('', 'customFilter')"><span class="icon_date hiddenfilter-lg hiddenfilter-md"> </span><span   class="CustomFilterClass"    ><?php echo $defaultCustomFilterName; ?></span>
        <span class="icon-downArrowH"></span></a>
        
    <a ng-click="getEventRegCount()" class="btn collapsed collapse_bt hiddenfilter-lg hiddenfilter-md hiddenfilter-sm" href="#headerDD33" aria-expanded="false" aria-controls="collapseFour" ng-cloak>
                         <span class="icon_webinar hiddenfilter-lg hiddenfilter-md"> </span><span class="freepaid">{{typeName}}</span>&nbsp;<span class="icon-downArrowH"></span></a>
        <!--Mobile-->
    

    <!--Cities List Strat-->
    <div class="filterdiv hiddenfilter-lg hiddenfilter-md  city-search-list" id="headerDD" style="width: 100%;">  
        <div class="accTextCont cityList">
            <span class="floatR locSearchContainer  btnClass"><a
                    href="javascript:void(0)" class="btn"><img
                        src="<?php echo $this->config->item('images_static_path'); ?>close-icon.png"></a> </span>
            <h6>Top Cities</h6>
            <ul ng-init="init(<?php echo htmlspecialchars(json_encode($cityList)); ?>, 'city')">            

                <li ng-repeat="city in cityList| orderBy:'name'"  id="{{city.id}}_mobcity">
                    <a  title="{{city.name}}" ng-click="setFilter('city', city.id, city.name, 0, city.splcitystateid)"  >
                        <label ng-cloak>{{city.name}} <span ng-cloak>{{city.eventCount}}</span>
                        </label>
                    </a>
                </li>
                <li>
                    <a  title="All Cities" ng-click="setFilter('city', 0, 'All Cities', 0, 0)">
                        <label>All Cities <span ng-cloak>{{allCityCount}}</span></label>
                    </a>
                </li>
            </ul>
            <?php include 'city_search.php'; ?>
            <div class="clearBoth"></div>
        </div>
    </div><!--Mobile-->
    <!--Cities List End-->
    <!--Categories List Strat-->
    <div class="filterdiv hiddenfilter-lg hiddenfilter-md category-search-list" id="headerDD1" style="width: 100%; margin-bottom : 150px;">
        <div class="accTextCont categoryList">
            <span class="floatR locSearchContainer btnClass"> <a
                    href="javascript:void(0)" class="btn"><img
                        src="<?php echo $this->config->item('images_static_path'); ?>close-icon.png"></a>
            </span>
            <h6>Top Categories</h6>
            <ul ng-init="init(<?php echo htmlspecialchars(json_encode($categoryList)); ?>, 'category')">


                <li ng-repeat="category in categoryList| orderBy:'name'" id="{{category.id}}_mobcategory">

                    <a  title="{{category.name}}"  ng-click="setFilter('category', category.id, category.name, 0, 0)" >
                        <i class="icon1-{{category.name| replaceSpaceFilterSearch | lowercase}} col{{category.name| replaceSpaceFilterSearch| lowercase}} "></i>   
                        <label ng-cloak>{{category.name}}
                            <span ng-cloak>{{category.eventCount}}</span>
                        </label>
                    </a></li>
                <li>
                    <a href="javascript:void(0)" title="All Categories"  ng-click="setFilter('category', 0, 'All Categories', 0, 0)" >
                        <label>All Categories <span ng-cloak>{{allCategoryCount}}</span></label>
                    </a>
                </li>
                <li id="subcat"   style="position: relative;  display:<?php echo ( $defaultCategoryId > 0) ? "inline-block" : "none !important"; ?>" >
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
                <span ng-if="subcatErrorMessage != ''" ng-cloak>{{subcatErrorMessage}}</span>
                <ul ng-init="init('', 'subCategoryList')">
                    <h6>Sub Category</h6>
                    <hr>                 
                    <li ng-repeat="subcategory in subCategoryList| orderBy:'name'">
                        <a  title="{{subcategory.name}}" ng-click="setFilter('Subcategory', subcategory.id, subcategory.name, 0, 0)">
                            <label ng-cloak>{{subcategory.name}}
                                <span ng-cloak>({{subcategory.count}})</span>
                            </label> 
                        </a>
                    </li>
                    <li class="subcategorysearch"><?php include 'subcategory_search.php'; ?></li>           
                </ul>

                <div class="clearBoth"></div>
            </div>
        </div>
    </div><!--Mobile-->
    <!--sm xs is related to normal view-->
    <!--Categories List End-->

    <!--Date Filter List Strat-->
    <div class="filterdiv search-timelist hiddenfilter-lg hiddenfilter-md" id="headerDD2" style="width: 100%;">
        <div class="accTextCont timeList">
            <span class="floatR locSearchContainer btnClass "><!-- hiddenfilter-xs -->
                <a href="javascript:void(0)" class="btn">
                    <img src="<?php echo $this->config->item('images_static_path'); ?>close-icon.png">
                </a>
            </span>
            <h6>Dates</h6>
            <ul  ng-init="init(<?php echo htmlspecialchars(json_encode($customFilterList)); ?>, 'customFilter')">
                <li ng-repeat="customFilter in customFilterList" id="{{customFilter.id}}_mobdates">
                    <a ng-if="customFilter.id < 7 && customFilter.id != 6" title="{{customFilter.name}}" ng-click="setFilter('CustomFilter', customFilter.id, customFilter.name, 0, 0)" >
                        <label ng-cloak>{{customFilter.name}}
                            <span ng-cloak>{{customFilter.eventCount}}</span>
                        </label>
                    </a>
                    <a ng-if="customFilter.id == 6" title="time" ng-click="setFilter('CustomFilter', customFilter.id, 'time', 0, 0)" >
                        <label ng-cloak>{{customFilter.name}}
                            <span ng-cloak>{{customFilter.eventCount}}</span>
                        </label>
                    </a>
                    <input ang-datepicker ng-model="customDateValue" ng-if="customFilter.id == 7" type="text" 
                           id="datepicker" readonly filter-id="{{customFilter.id}}" filter-name="{{customFilter.name}}"
                           class="cal_styles custom_date" style=""
                           placeholder="{{customFilter.name}}" value="{{customDateValue}}" />
                </li>                
            </ul>
            <div class="clearBoth"></div>
        </div>
    </div>
    <div class="filterdiv hiddenfilter-lg hiddenfilter-md" id="headerDD33" style="width: 100%;">
        <div class="accTextCont freepaidList">
                <!--<span class="icon-food floatR"></span>-->

            <ul>
                <li id="1_type"><a href="javascript:void(0)" title="Free" ng-click="setFilter('freepaid', 1, 'free', 0, 0)"> <label>
                            Free
                            <span ng-cloak>({{freeCount}})</span>
                        </label>
                    </a></li>
                <li id="2_type"><a href="javascript:void(0)" title="Paid" ng-click="setFilter('freepaid', 2, 'paid', 0, 0)"> <label>
                            Paid <span ng-cloak>({{paidCount}})</span>
                        </label>
                    </a></li>
                <li id="4_type"><a href="javascript:void(0)" title="Webinar" ng-click="setFilter('freepaid', 4, 'webinar', 0, 0)"> <label>
                            Webinar <span ng-cloak>({{webinarCount}})</span>
                        </label>
                    </a></li>
                <li id="3_type"><a href="javascript:void(0)" title="Info" ng-click="setFilter('freepaid', 3, 'Info Only', 0, 0)"> <label>
                            Info Only <span ng-cloak>({{noRegCount}})</span>
                        </label>
                    </a></li>
                <li><a href="javascript:void(0)" title="All" ng-click="setFilter('freepaid', '', 'All', 0, 0)"> <label>
                            All <span ng-cloak>({{allReg}})</span>
                        </label>
                    </a></li>
            </ul>
            <div class="clearBoth"></div>
        </div>
    </div><!--Mobile-->
    <!--Date Filter List End-->
</div> <!--Container End-->
    
    