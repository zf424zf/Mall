<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

//banner路由
Route::get('api/:version/banner/:id', 'api/:version.Banner/getBanner');

//主题路由
Route::group('api/:version/theme', function () {
    Route::get('/', 'api/:version.Theme/getSimpleList');
    Route::get('/:id', 'api/:version.Theme/getOne');
});

//商品路由
Route::group('api/:version/product/', function () {
    Route::get(':id', 'api/:version.Product/info', [], ['id' => '\d+']);
    Route::get('recent', 'api/:version.Product/recent');
    Route::get('in_category', 'api/:version.Product/productInCategory');
});

//分类路由
Route::get('api/:version/category/all', 'api/:version.Category/getAllCategory');

//用户登录
Route::post('api/:version/token/user', 'api/:version.Token/getToken');

//用户地址
Route::post('api/:version/address','api/:version.Address/createOrUpdate');

//订单
Route::post('api/:version/order','api/:version.Order/placeOrder');
Route::post('api/:version/pay_order','api/:version.Pay/payOrder');
Route::post('api/:version/pay/notify','api/:version.Pay/receiveNotify');