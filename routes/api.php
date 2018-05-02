<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

$api = app('Dingo\Api\Routing\Router');

$api->version('v1', [
    'namespace' => 'App\Http\Controllers\Api'
], function ($api) {

    //使用Dingo提供的调用频率限制中间件，暂定时间为1分钟1次
    $api->group([
        'middleware' => 'api.throttle',
        'limit' => config('api.rate_limits.sign.limit'),
        'expires' => config('api.rate_limits.sign.expires'),
    ], function ($api) {
        //游客可以访问的接口
        //短信验证码
        $api->post('verificationCodes', 'VerificationCodesController@store')
            ->name('api.verificationCodes.store');

        //用户注册
        $api->post('users', 'UsersController@store')
            ->name('api.users.store');

        //图片验证码
        $api->post('captchas', 'CaptchasController@store')
            ->name('api.captchas.store');

        //需要token验证的接口
        $api->group(['middleware' => 'api.auth'], function ($api) {
            //当前登录用户信息
            $api->get('user', 'UsersController@me')
                ->name('api.user.show');
        });
    });

});
