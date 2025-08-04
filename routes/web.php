<?php

/** @var \Laravel\Lumen\Routing\Router $router */

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

// API Routes
$router->group(['prefix' => 'api', 'middleware' => 'api.key'], function () use ($router) {

    // Здания
    $router->get('/buildings', 'Api\BuildingController@index');
    $router->get('/buildings/{id}', 'Api\BuildingController@show');

    // Организации - сначала специфичные маршруты
    $router->get('/organizations/radius', 'Api\OrganizationController@getByRadius');
    $router->get('/organizations/area', 'Api\OrganizationController@getByArea');
    $router->get('/organizations/building/{buildingId}', 'Api\OrganizationController@getByBuilding');
    $router->get('/organizations/activity/{activityId}', 'Api\OrganizationController@getByActivity');
    $router->get('/organizations/search/activity/{activityId}', 'Api\OrganizationController@searchByActivity');
    $router->get('/organizations/search/name', 'Api\OrganizationController@searchByName');

    // Общие маршруты организаций - в конце
    $router->get('/organizations/{id}', 'Api\OrganizationController@show');

});

// Документация API (без аутентификации)
$router->get('/api/docs', 'Api\DocumentationController@generateOpenApi');
