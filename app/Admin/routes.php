<?php
/*
Очистка кэша фасада:
php artisan cache:clear
Очистка кэша роутов:
php artisan route:cache
Очистка кэша view:
php artisan view:clear
Очистка кэша конфигов:
php artisan config:cache очищаем всегда кеш после изменения конфигов
*/
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
