<?php

/*
  |--------------------------------------------------------------------------
  | Application Routes
  |--------------------------------------------------------------------------
  |
  | Here is where you can register all of the routes for an application.
  | It is a breeze. Simply tell Lumen the URIs it should respond to
  | and give it the Closure to call when that URI is requested.
  |
 */

$app->get('/', function () use ($app) {
    return $app->version();
});

/**
 * @SWG\Swagger(
 *     basePath="api/v1/",
 *     host=SWAGGER_LUME_CONST_HOST,
 *     schemes={"http"},
 * @SWG\Info(title="MTC Project API",
  description="This is an  API List for MTC",
  version="1/.01" )
 * )
 *
 */
$app->group(array('prefix' => '/api/v1', 'namespace' => 'App\Http\Controllers','middleware'=>'auth'), function() use ($app) {
   
    $app->put('/users/create', 'UserController@usersCreate');
    $app->post('/login', 'UserController@loginCheck');
    $app->post('/users/forgotpassword', 'UserController@forgotPassword');
    $app->post('/users/changepassword', 'UserController@changePassword');
    $app->post('/users/resetpassword', 'UserController@resetPassword');
    $app->get('/chat/users', 'UserController@chatUsers');
    $app->get('/dashboard/{user_id}', 'UserController@dashboard');
    
    $app->put('/devicetokens/create', 'UserController@deviceTokenCreate');

    $app->get('/links', 'LinkController@index');
    
    $app->get('/surveys/list', 'SurveyController@index');
    $app->post('/surveys/status', 'SurveyController@surveyUserStatus');
    
    $app->get('/events/list', 'EventController@index');
    $app->get('/events/{event_id}', 'EventController@getEvent');
    $app->post('/events/attende/confirmation', 'EventController@changeEventUserStatus');
    $app->put('/events/create', 'EventController@createEvent');

    $app->get('/categories/list', 'TopicController@categoryList');
    $app->get('/topics/list', 'TopicController@index');
    $app->get('/topics/{topic_id}', 'TopicController@getTopic');
    $app->put('/topics/create', 'TopicController@createTopic');
    $app->post('/topics/{topicid}/reply', 'TopicController@createComment');
    $app->get('/comments/review/{comment_id}', 'TopicController@markComment');
    
});


