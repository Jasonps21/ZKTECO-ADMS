<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ZKTecoController;
use App\Http\Middleware\DebugZK;
use App\Http\Middleware\ProfileZKTecoRequest;
use App\Http\Middleware\VerifyCsrfToken;
use App\Http\Middleware\VerifyZKTecoSignature;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Csrf;

Route::get('/', function () {
    return view('welcome');
});

Route::match(['GET', 'POST'], '/iclock/cdata', [ZKTecoController::class, 'cdata'])
    ->withoutMiddleware([Csrf::class]);

Route::match(['GET', 'POST'], '/iclock/registry', [ZKTecoController::class, 'registry'])
    ->withoutMiddleware([Csrf::class]);

Route::match(['GET', 'POST'], '/iclock/devicecmd', [ZKTecoController::class, 'devicecmd'])
    ->withoutMiddleware([Csrf::class]);


Route::get('/iclock/getrequest', [ZKTecoController::class, 'getrequest'])
    ->withoutMiddleware([Csrf::class]);
