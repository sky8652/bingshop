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

use App\Http\Controllers\Auth\LoginController;
use App\Http\Wechat\AddressController;
use App\Http\Wechat\CategoryController;
use App\Http\Wechat\GoodsController;
use App\Http\Wechat\OrderController;
use App\Http\Wechat\ShoppingCartController;
use App\Http\Wechat\UserController;

$api = app('Dingo\Api\Routing\Router');

$api->version('v1', function ($api) {

    $api->group(['prefix' => 'wechat','middleware' => 'api.throttle', 'limit' => 100, 'expires' => 1], function ($api) {

        $api->group(['prefix' => 'auth'], function ($api) {
            /** 登录 */
            $api->post('/login', LoginController::class . '@apiLogin');
        });

        $api->group(['middleware' => 'wechat'], function ($api) {

            /** 获取用户信息 **/
            $api->get('/user',UserController::class . '@personal');

            /** 获取商品分类列表 **/
            $api->get("/categories",CategoryController::class . "@categories");

            /** 获取指定商品分类的商品列表 **/
            $api->get("/category/{id}/goods",CategoryController::class . "@categoryGoods");

            /** 获取商品详情 **/
            $api->get("/goods/{id}",GoodsController::class . "@detail");

            /** 商品分页数据 **/
            $api->get("/goods",GoodsController::class . '@goodsList');

            /** 加入购物车 **/
            $api->post("/cart",ShoppingCartController::class . "@addToCart");

            /** 获取用户购物车商品 **/
            $api->get("/carts",ShoppingCartController::class . "@carts");

            /** 删除购物车中指定的sku商品 **/
            $api->delete("/cart/{id}/delete",ShoppingCartController::class . "@delete");

            /** 获取用户购物车商品数量 **/
            $api->get("/cart/num",ShoppingCartController::class . "@getCartNum");

            /** 商品购物车数量减一 **/
            $api->put("/cart/{id}/reduce",ShoppingCartController::class . '@reduceCartNum');

            /** 创建商品订单 **/
            $api->post('/order',OrderController::class . '@createOrder');

            /** 统计商品状态 **/
            $api->get('/order/status',OrderController::class . '@countOrderStatus');

            /** 获取用户的所有订单 **/
            $api->get('/orders',OrderController::class . '@orderList');

            /** 获取用户的订单详情**/
            $api->get('/order/{id}',OrderController::class . '@detail');

            /** 用户确认订单 **/
            $api->put('/order/receipt',OrderController::class . '@receipt');

            /** 新建收货地址 **/
            $api->post('/address',AddressController::class . '@createAddress');

            /** 获取用户收货地址 **/
            $api->get('/address',AddressController::class . '@getAddress');

            /** 删除用户收货地址 **/
            $api->delete('/address/{id}',AddressController::class . '@deleteAddress');

            /** 获取地址详情 **/
            $api->get('/address/{id}',AddressController::class . '@addressDetail');
        });

    });

    $api->post('/pay/callback', OrderController::class . '@payCallback');

});


