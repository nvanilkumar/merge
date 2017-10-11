<?php

/*
  |--------------------------------------------------------------------------
  | Web Routes
  |--------------------------------------------------------------------------
  |
  | Here is where you can register web routes for your application. These
  | routes are loaded by the RouteServiceProvider within a group which
  | contains the "web" middleware group. Now create something great!
  |
 */

 
Auth::routes();

Route::get('staff/login', 'UserController@show');
Route::post('staff/login', 'UserController@loginCheck');
Route::get('staff/logout', 'UserController@logout');
Route::post('users/usernamecheck', 'UserController@userNameCheck');

Route::group(['middleware' => 'customauth'], function () {
    Route::get('dashboard', 'UserController@dashboard');
    
    Route::get('users/create', 'UserController@createUserView');
    Route::post('users/create', 'UserController@createUser');
    Route::get('users/update/{user_id}', 'UserController@updateUserView');
    Route::post('users/update', 'UserController@updateUser');
    Route::get('users/list/{type}', 'UserController@getUserList');
    Route::get('users/{user_id}/delete', ['uses' => 'UserController@deleteUser', 'as' => 'users.deactivate']);
    Route::post('users/group', 'UserController@assignGroupUsersView');
    Route::post('users/group/insert', 'UserController@assignGroupUsers');
    Route::get('chat', 'UserController@chat');
    Route::get('password/change', 'UserController@changePassword');
    Route::post('password/change', 'UserController@userUpdatePassword');
    
    Route::get('links/create', 'LinkController@creatLinkView');
    Route::post('links/create', 'LinkController@creatLink');
    Route::get('links/update/{link_id}', 'LinkController@updateLinkView');
    Route::post('links/update', 'LinkController@updateLink');
    Route::get('links/list', 'LinkController@getLinkList');
    Route::post('links/save_menu_order', 'LinkController@updateMenuPositon');
    Route::get('links/{link_id}/delete', ['uses' => 'LinkController@deleteLink', 'as' => 'links.deactivate']);
    
    Route::get('notifications/create', 'NotificationController@createNotificationView');
    Route::post('notifications/create', 'NotificationController@createNotification');
    Route::get('notifications/list', 'NotificationController@getNotifications');
    Route::get('notifications/view/{notification_id}', 'NotificationController@notificationView');
    
    Route::get('groups/create', 'GroupController@createGroupView');
    Route::post('groups/create', 'GroupController@createGroup');
    Route::get('groups/update/{group_id}', 'GroupController@updateGroupView');
    Route::post('groups/update', 'GroupController@updateGroup');
    Route::get('groups/list', 'GroupController@getGrouopList');
    Route::get('groups/{group_id}/delete', ['uses' => 'GroupController@deleteGroup', 'as' => 'groups.deactivate']);
    Route::post('groups/groupnamecheck', 'GroupController@groupNameCheck');
   
    Route::get('categories/create', 'CategoryController@createCategoryView');
    Route::post('categories/create', 'CategoryController@createCategory');
    Route::get('categories/update/{category_id}', 'CategoryController@updateCategoryView');
    Route::post('categories/update', 'CategoryController@updateCategory');
    Route::get('categories/list', 'CategoryController@getCategoryList');
    Route::get('categories/{category_id}/delete', ['uses' => 'CategoryController@deleteCategory', 'as' => 'categories.deactivate']);

    Route::get('events/create', 'EventController@createEventView');
    Route::post('events/create', 'EventController@createEvent');
    Route::get('events/update/{event_id}', 'EventController@updateEventView');
    Route::post('events/update', 'EventController@updateEvent');
    Route::get('events/list', 'EventController@getEventList');
    Route::get('events/{event_id}/delete', ['uses' => 'EventController@deleteEvent', 'as' => 'events.deactivate']);
   
    Route::get('surveys/create', 'SurveyController@createSurveyView');
    Route::post('surveys/create', 'SurveyController@createSurvey');
    Route::get('surveys/update/{survey_id}', 'SurveyController@updateSurveyView');
    Route::post('surveys/update', 'SurveyController@updateSurvey');
    Route::get('surveys/list', 'SurveyController@getSurveyList');
    Route::get('surveys/{survey_id}/delete', ['uses' => 'SurveyController@deleteSurvey', 'as' => 'surveys.deactivate']);
    
    Route::get('topics/create', 'TopicController@createTopicView');
    Route::get('topics/details/{topic_id}', 'TopicController@topicDetailsView');
    Route::post('topics/create', 'TopicController@createTopic');
    Route::get('topics/update/{topic_id}', 'TopicController@updateTopicView');
    Route::post('topics/update', 'TopicController@updateTopic');
    Route::get('topics/list/{category_id?}', 'TopicController@getTopicList');
    Route::get('topics/{topic_id}/delete', ['uses' => 'TopicController@deleteTopic', 'as' => 'topics.deactivate']);
    Route::get('topics/flagged/comments', 'TopicController@flaggedComments' );
    
    Route::post('comments/create', 'CommentController@createComment');
    Route::post('comments/update', 'CommentController@updateComment');
    Route::get('comments/{comment_id}/delete', ['uses' => 'CommentController@deleteComment', 'as' => 'comments.deactivate']);
    
   
});

 

