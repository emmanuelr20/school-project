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

$router->get('/', function () use ($router) {
    return $router->app->version();
});

$router->get('/hello', function () use ($router) {
    return json_encode('hello world');
});

// authenticate
$router->post('/register', 'AuthController@register');
$router->post('/login', 'AuthController@login');
$router->post('/logout', 'AuthController@logout');
$router->post('/token/user', 'AuthController@getTokenUser');

//posts
$router->post('/posts', 'PostController@list');
$router->get('/posts/{id}', 'PostController@get');
$router->post('/posts/latest', 'PostController@latest');
$router->post('/posts/create', ['middleware' => ['admin'], 'uses' => 'PostController@create']);
$router->get('/posts/delete/{post}', 'PostController@delete');

//polls
$router->post('/polls', 'PollController@list');
$router->get('/polls/{id}', 'PollController@get');
$router->post('/polls/latest', 'PollController@latest');
$router->post('/polls/create', 'PollController@create');
$router->get('/polls/delete/{polls}', ['middleware' => ['admin'], 'uses' => 'PollController@delete']);

// comments
$router->post('/comments/post/list/{post}', 'CommentController@listPost');
$router->post('/comments/poll/list/{poll}', 'CommentController@listPoll');
$router->post('/comments/post/create/{id}', 'CommentController@createForPost');
$router->post('/comments/poll/create/{id}', 'CommentController@createForPoll');
$router->post('/comments/delete/{id}', 'CommentController@delete');

// users
$router->post('/users/create', ['middleware' => ['super_admin'], 'uses' => 'UserController@create']);


// images
$router->get('/images/{folder}/{reference}', 'ImageController@get');

//testing
$router->get('/test/{date}', 'DashboardController@test');
