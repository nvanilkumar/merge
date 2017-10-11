<!DOCTYPE html>
<html>
    <head>
        <title>{{ (@$title)?$title:"" }}</title>
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <!--Let browser know website is optimized for mobile-->
        <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
        
  
              <link href="http://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

        <!--Import Google Icon Font-->
        <link href="{{ asset('/css/icon.css') }}" rel="stylesheet">
        <!--Import materialize.css-->
        <link type="text/css" rel="stylesheet" href="{{ asset('/css/materialize.min.css') }}"  media="screen,projection"/>
        
        <!--Import datatable styles-->
        <link type="text/css" rel="stylesheet" href="{{ asset('/css/jquery.dataTables.min.css') }}"  media="screen,projection"/>
        
        <!--Import custom styles-->
        <link type="text/css" rel="stylesheet" href="{{ asset('/css/custom-styles.css') }}"  media="screen,projection"/>
        <link type="text/css" rel="stylesheet" href="{{ asset('/css/jquery.datetimepicker.css') }}"  media="screen,projection"/>
        
        
        <script type="text/javascript" src="{{ asset('/js/jquery-2.1.1.min.js') }}"></script>
        <script type="text/javascript" src="{{ asset('/js/materialize.min.js') }}"></script>
        <script type="text/javascript" src="{{ asset('/js/mtcmain.js') }}"></script>
        <script>
            var mtcBaseUrl = "<?php echo url('/'); ?>";
        </script>

    </head>

    <body>
        <div class="main-wrap">
        <ul id="dropdown1" class="dropdown-content">
            <li><a href="{{ action('UserController@getUserList',['student']) }}">View Students</a></li>
            <li class="divider"></li>
            <li><a href="{{ action('UserController@getUserList',['staff']) }}">View Admin Staff</a></li>
            <li class="divider"></li>
            <li><a href="{{ action('UserController@createUserView') }}">Create Users</a></li>
        </ul>

        <ul id="dropdown2" class="dropdown-content">
            <li><a href="{{action('GroupController@getGrouopList')}}">View All</a></li>
            <li class="divider"></li>
            <li><a href="{{action('GroupController@createGroupView')}}">Create Group</a></li>
        </ul>

        <ul id="dropdown3" class="dropdown-content">
            <li><a href="{{action('EventController@getEventList')}}">View All</a></li>
            <li class="divider"></li>
            <li><a href="{{action('EventController@createEventView')}}">Create Event</a></li>
        </ul>

        <ul id="dropdown4" class="dropdown-content">
            <li><a href="{{action('SurveyController@getSurveyList')}}">View All</a></li>
            <li class="divider"></li>
            <li><a href="{{action('SurveyController@createSurveyView')}}">Create Survey</a></li>
        </ul>

        <ul id="dropdown5" class="dropdown-content">
            <li><a href="{{action('LinkController@getLinkList')}}">View All</a></li>
            <li class="divider"></li>
            <li><a href="{{action('LinkController@creatLinkView')}}">Create Link</a></li>
        </ul>

        <ul id="dropdown6" class="dropdown-content">
            <li><a href="{{action('CategoryController@getCategoryList')}}">View All Categories</a></li>
            <li class="divider"></li>
            <li><a href="{{action('CategoryController@createCategoryView')}}">Create Category</a></li>
            <li class="divider"></li>
            <li><a href="{{action('TopicController@getTopicList')}}">View All Topic</a></li>
            <li class="divider"></li>
            <li><a href="{{action('TopicController@createTopicView')}}">Create Topic</a></li>
            <li class="divider"></li>
            <li><a href="{{action('TopicController@flaggedComments')}}">Moderate Messages</a></li>
        </ul>

        <ul id="dropdown7" class="dropdown-content">
            <li><a href="{{action('NotificationController@getNotifications')}}">View All</a></li>
            <li class="divider"></li>
            <li><a href="{{action('NotificationController@createNotificationView')}}">Create Notification</a></li>
        </ul>
            
        <nav>
            <div class="nav-wrapper">
                    <div class="logo-div">
                        <a href="{{action('UserController@dashboard')}}">
                            <img src="{{ asset('/img/logo.png') }}" alt="" />
                        </a>
                    </div>
                    <div class="menu-div">
                            <ul id="nav-mobile" class="hide-on-med-and-down">
                                <li>
                                    <a class="{{ (Request::is('dashboard') ? 'active' : '') }}" href="{{action('UserController@dashboard')}}">
                                        <i class="material-icons">dashboard</i> 
                                        Dashboard
                                    </a>
                                </li>
                                <li>
                                    <a class="{{ (Request::is('users/*') ? 'active' : '') }} dropdown-button" data-activates="dropdown1">
                                        <i class="material-icons">person</i>Users
                                    </a>
                                </li>
                                <li>
                                    <a class="{{(Request::is('groups/*') ? 'active' : '') }} dropdown-button" data-activates="dropdown2">
                                        <i class="material-icons">group_work</i> Groups
                                    </a>
                                </li>
                                <li>
                                    <a class="{{(Request::is('events/*') ? 'active' : '') }} dropdown-button" data-activates="dropdown3">
                                        <i class="material-icons">event</i> Events
                                    </a>
                                </li>
                                <li>
                                    <a class="{{(Request::is('surveys/*') ? 'active' : '') }} dropdown-button" data-activates="dropdown4">
                                        <i class="material-icons">check_circle</i> 
                                        Surveys
                                    </a>
                                </li>
                                <li>
                                    <a href="{{action('UserController@chat')}}" class="{{(Request::is('chat') ? 'active' : '') }}">
                                        <i class="material-icons">chat</i> Chat
                                    </a>
                                </li>
                                <li>
                                    <a class="{{(Request::is('links/*') ? 'active' : '') }} dropdown-button" data-activates="dropdown5">
                                        <i class="material-icons">link</i> Links
                                    </a>
                                </li>
                                <li>
                                    <a class="{{( ( (Request::is('topics/*')) || (Request::is('categories/*'))  ) ? 'active' : '') }} dropdown-button" data-activates="dropdown6">
                                        <i class="material-icons">forum</i> Message Board
                                    </a>
                                </li>
                                <li>
                                    <a class="{{(Request::is('notifications/*') ? 'active' : '') }} dropdown-button" data-activates="dropdown7">
                                        <i class="material-icons">notifications</i> Notifications
                                    </a>
                                </li>
                            </ul>
                    </div>
                    <div class="userlogin-div">
                        <p class="userlogin-link">{{Session::get("user_name")}}<br /><a class="user-links" href="{{action('UserController@changePassword')}}">Change Password</a>&nbsp; | &nbsp;<a class="user-links" href="{{action('UserController@logout')}}">Log Out</a></p>
                    </div>
            </div>
        </nav>


        <div class="fixed-action-btn click-to-toggle">
            <a class="btn-floating btn-large pulse-bg"><i class="material-icons">add</i></a>
            <ul>
                <li>
                    <a class="btn-floating bluegrey2 tooltipped" href="{{ action('UserController@createUserView') }}"
                       data-position="left" data-delay="50" data-tooltip="Create User">
                        <i class="material-icons">person</i>
                    </a>
                </li>
                <li>
                    <a class="btn-floating bluegrey2 tooltipped" href="{{action('GroupController@createGroupView')}}"
                       data-position="left" data-delay="50" data-tooltip="Create Group">
                        <i class="material-icons">group_work</i>
                    </a>
                </li>
                <li>
                    <a class="btn-floating bluegrey2 tooltipped" href="{{action('EventController@createEventView')}}"
                       data-position="left" data-delay="50" data-tooltip="Create Event">
                        <i class="material-icons">event</i>
                    </a>
                </li>
                <li>
                    <a class="btn-floating bluegrey2 tooltipped" href="{{action('SurveyController@createSurveyView')}}"
                       data-position="left" data-delay="50" data-tooltip="Create Survey">
                        <i class="material-icons">check_circle</i>
                    </a>
                </li>
                <li>
                    <a class="btn-floating bluegrey2 tooltipped" href="{{action('LinkController@creatLinkView')}}"
                       data-position="left" data-delay="50" data-tooltip="Create Link">
                        <i class="material-icons">link</i>
                    </a>
                </li>
            </ul>
        </div>

        
            @yield('content')
        </div>
        <footer class="page-footer">
            <div class="footer-copyright">
                <div class="container"><span class="right">&copy; 2017 - 2019 Midlands Technical College, All rights reserved</span></div>
            </div>
        </footer>

        <script>
            $(document).ready(function () {
                $('select').material_select();
                $(".dropdown-button").dropdown();
            });


        </script>
    </body>
</html>
