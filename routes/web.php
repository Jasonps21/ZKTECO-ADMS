<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ZKTecoController;
use App\Http\Middleware\DebugZK;
use App\Http\Middleware\ProfileZKTecoRequest;
use App\Http\Middleware\VerifyCsrfToken;
use App\Http\Middleware\VerifyZKTecoSignature;

Route::get('/', function () {
    return view('welcome');
});

Route::match(['GET', 'POST'], '/iclock/cdata', [ZKTecoController::class, 'cdata'])
    ->withoutMiddleware([\App\Http\Middleware\VerifyCsrfToken::class]);

Route::match(['GET', 'POST'], '/iclock/registry', [ZKTecoController::class, 'registry'])
    ->withoutMiddleware([VerifyCsrfToken::class]);

Route::match(['GET', 'POST'], '/iclock/devicecmd', [ZKTecoController::class, 'devicecmd'])
    ->withoutMiddleware([VerifyCsrfToken::class]);

Route::get('/iclock/getrequest', [ZKTecoController::class, 'getrequest'])
    ->withoutMiddleware([VerifyCsrfToken::class]);
