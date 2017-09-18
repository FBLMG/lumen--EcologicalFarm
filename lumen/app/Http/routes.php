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

define('ROUTES_BASE', '/');

$app->get(ROUTES_BASE, function () use ($app) {
	return $app->version();
});


$routesArray = array(

	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	// AdminBaseController.php
	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    'admin/login' => 'AdminBaseController@login',
    'admin/loginInfo' => 'AdminBaseController@loginInfo',
    'admin/uploadFile' => 'AdminBaseController@uploadFile',
    'admin/uploadFileForm' => 'AdminBaseController@uploadFileForm',

	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	// AdminNewsController.php
	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    'admin/newsGet' => 'AdminNewsController@newsGet',
    'admin/newsGetList' => 'AdminNewsController@newsGetList',
    'admin/newsInsert' => 'AdminNewsController@newsInsert',
    'admin/newsUpdate' => 'AdminNewsController@newsUpdate',
    'admin/newsDelete' => 'AdminNewsController@newsDelete',

	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	// AdminRegionController.php
	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    'admin/regionGet' => 'AdminRegionController@regionGet',
    'admin/regionGetList' => 'AdminRegionController@regionGetList',
    'admin/parentRegionInsert' => 'AdminRegionController@parentRegionInsert',
    'admin/parentRegionUpdate' => 'AdminRegionController@parentRegionUpdate',
    'admin/parentRegionDelete' => 'AdminRegionController@parentRegionDelete',
    'admin/subRegionGet' => 'AdminRegionController@subRegionGet',
    'admin/subRegionInsert' => 'AdminRegionController@subRegionInsert',
    'admin/subRegionUpdate' => 'AdminRegionController@subRegionUpdate',
    'admin/subRegionDelete' => 'AdminRegionController@subRegionDelete',

	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	// AdminFarmController.php
	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    'admin/farmGet' => 'AdminFarmController@farmGet',
    'admin/farmGetList' => 'AdminFarmController@farmGetList',
    'admin/farmInsert' => 'AdminFarmController@farmInsert',
    'admin/farmUpdate' => 'AdminFarmController@farmUpdate',
    'admin/farmDelete' => 'AdminFarmController@farmDelete',

    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // AdminProductTraceabilityController.php
    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    'admin/productTraceabilityGet' => 'AdminProductTraceabilityController@productTraceabilityGet',
    'admin/productTraceabilityGetList' => 'AdminProductTraceabilityController@productTraceabilityGetList',

   ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // AdminUserController.php
    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    'admin/adminGet' => 'AdminUserController@adminGet',
    'admin/adminGetList' => 'AdminUserController@adminGetList',
    'admin/adminInsert' => 'AdminUserController@adminInsert',
    'admin/adminUpdate' => 'AdminUserController@adminUpdate',
    'admin/adminDelete' => 'AdminUserController@adminDelete',
    'admin/adminStart' => 'AdminUserController@adminStart',
    'admin/adminStop' => 'AdminUserController@adminStop',

    'admin/userGet' => 'AdminUserController@userGet',
    'admin/userGetList' => 'AdminUserController@userGetList',
    'admin/userUpdateLevel' => 'AdminUserController@userUpdateLevel',
    'admin/userUpdatepower' => 'AdminUserController@userUpdatepower',
  
    'admin/cleanCache' => 'AdminUserController@cleanCache',


    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // AppBaseController.php
    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    'app/userinfo' => 'AppBaseController@userinfo',
    'app/wxLogin' => 'AppBaseController@wxLogin',
    'app/uploadImageWithWx' => 'AppBaseController@uploadImageWithWx',
    'app/uploadVideoWithWx' => 'AppBaseController@uploadVideoWithWx',
    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // AppNewsController.php
    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    'app/newsGet' => 'AppNewsController@newsGet',
    'app/newsGetList' => 'AppNewsController@newsGetList',

    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // AppFarmController.php
    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    'app/farmGet' => 'AppFarmController@farmGet',
    'app/farmGetList' => 'AppFarmController@farmGetList',
    'app/regionGetList' => 'AppFarmController@regionGetList',
    'app/countyGetList' => 'AppFarmController@countyGetList',

    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // AppProductTraceabilityController.php
    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    'app/ProductTraceabilityGet' => 'AppProductTraceabilityController@ProductTraceabilityGet',
    'app/ProductTraceabilityGetList' => 'AppProductTraceabilityController@ProductTraceabilityGetList', 

    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // AppProductsController.php
    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    'app/productGet' => 'AppProductsController@productGet',
    'app/productGetList' => 'AppProductsController@productGetList', 
    'app/productInsert' => 'AppProductsController@productInsert', 
    'app/productUpdate' => 'AppProductsController@productUpdate', 
    'app/productDelete' => 'AppProductsController@productDelete', 

    'app/growthStatusGet' => 'AppProductsController@growthStatusGet',
    'app/growthStatusGetList' => 'AppProductsController@growthStatusGetList', 
    'app/growthStatusInsert' => 'AppProductsController@growthStatusInsert', 
    'app/growthStatusUpdate' => 'AppProductsController@growthStatusUpdate', 
    'app/growthStatusDelete' => 'AppProductsController@growthStatusDelete',

    'app/vaccineStatusGet' => 'AppProductsController@vaccineStatusGet',
    'app/vaccineStatusGetList' => 'AppProductsController@vaccineStatusGetList', 
    'app/vaccineStatusInsert' => 'AppProductsController@vaccineStatusInsert', 
    'app/vaccineStatusUpdate' => 'AppProductsController@vaccineStatusUpdate', 
    'app/vaccineStatusDelete' => 'AppProductsController@vaccineStatusDelete',

    'app/feedStatusGet' => 'AppProductsController@feedStatusGet',
    'app/feedStatusGetList' => 'AppProductsController@feedStatusGetList', 
    'app/feedStatusInsert' => 'AppProductsController@feedStatusInsert', 
    'app/feedStatusUpdate' => 'AppProductsController@feedStatusUpdate', 
    'app/feedStatusDelete' => 'AppProductsController@feedStatusDelete',

    'app/circulationRecordGet' => 'AppProductsController@circulationRecordGet',
    'app/circulationRecordGetList' => 'AppProductsController@circulationRecordGetList', 
    'app/circulationRecordInsert' => 'AppProductsController@circulationRecordInsert', 
    'app/circulationRecordUpdate' => 'AppProductsController@circulationRecordUpdate', 
    'app/circulationRecordDelete' => 'AppProductsController@circulationRecordDelete',

	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	// ExampleController.php
	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    
	'example/test' => 'ExampleController@test',
);



foreach ($routesArray as $key => $item) {
	$app->get(ROUTES_BASE . $key, $item);
	$app->post(ROUTES_BASE . $key, $item);
	$app->options(ROUTES_BASE . $key, $item);
}



