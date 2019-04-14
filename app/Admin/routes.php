<?php

use Illuminate\Routing\Router;

Admin::registerAuthRoutes();

Route::group([
    'prefix'        => config('admin.route.prefix'),
    'namespace'     => config('admin.route.namespace'),
    'middleware'    => config('admin.route.middleware'),
], function (Router $router) {

    $router->get('/', 'HomeController@index');
    $router->get('/category/tree-category', 'CategoryController@treeCategory');
    $router->get('/category/tree-category/create', 'CategoryController@create');
    $router->post('/category/tree-category', 'CategoryController@store');
    $router->resource('/category', 'CategoryController');

});
