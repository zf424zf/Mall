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

Route::get('api/:version/banner/:id', 'api/:version.Banner/getBanner');

Route::get('api/:version/theme','api/:version.Theme/getSimpleList');

Route::get('api/:version/theme/:id','api/:version.Theme/getOne');

Route::get('api/:version/product/recent','api/:version.Product/recent');
Route::get('api/:version/product/in_category','api/:version.Product/productInCategory');

Route::get('api/:version/category/all','api/:version.Category/getAllCategory');

Route::post('api/:version/token/user','api/:version.Token/getToken');