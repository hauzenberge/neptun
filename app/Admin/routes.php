<?php

use Illuminate\Routing\Router;

Admin::routes();

Route::group([
    'prefix'        => config('admin.route.prefix'),
    'namespace'     => config('admin.route.namespace'),
    'middleware'    => config('admin.route.middleware'),
], function (Router $router) {
    $router->get('/'						, 'HomeController@index')->name('admin.home');
    $router->get('/auth/logout'				, 'AuthController@logout')->name('admin.logout');
    $router->get('/ship_calls/sync'			, 'ShipCallsSyncController@index');
    
	$router->resource('menu'				, MenuController::class);
	$router->resource('pages'				, PagesController::class);
	$router->resource('email-templates'		, EmailTemplatesController::class);
	$router->resource('articles/categories'	, ArticlesCategoryController::class);
	$router->resource('articles'			, ArticlesController::class);
	$router->resource('feedback'			, FeedbackController::class);
	$router->resource('jobs'				, JobsController::class);
	$router->resource('langs'				, LangsController::class);
	$router->resource('ship_calls'			, ShipCallsController::class);
});
