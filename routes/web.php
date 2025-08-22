<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ZKTecoController;
use App\Http\Middleware\DebugZK;
use App\Http\Middleware\ProfileZKTecoRequest;
use App\Http\Middleware\VerifyCsrfToken;
use App\Http\Middleware\VerifyZKTecoSignature;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Csrf;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\View\Middleware\ShareErrorsFromSession;

Route::get('/', function () {
    return view('welcome');
});

Route::prefix('iclock')
    ->middleware([]) // ← jangan pakai group 'web' atau 'api' kalau api-mu dikasih throttle dsb
    ->group(function () {
        Route::match(['GET', 'POST'], '/cdata', [ZKTecoController::class, 'cdata'])
            ->withoutMiddleware([Csrf::class, StartSession::class, EncryptCookies::class, AddQueuedCookiesToResponse::class, ShareErrorsFromSession::class]);

        Route::match(['GET', 'POST'], '/registry', [ZKTecoController::class, 'registry'])
            ->withoutMiddleware([Csrf::class, StartSession::class, EncryptCookies::class, AddQueuedCookiesToResponse::class, ShareErrorsFromSession::class]);

        Route::match(['GET', 'POST'], '/devicecmd', [ZKTecoController::class, 'devicecmd'])
            ->withoutMiddleware([Csrf::class, StartSession::class, EncryptCookies::class, AddQueuedCookiesToResponse::class, ShareErrorsFromSession::class]);

        Route::match(['GET', 'POST'], '/getrequest', [ZKTecoController::class, 'getrequest'])
            ->withoutMiddleware([Csrf::class, StartSession::class, EncryptCookies::class, AddQueuedCookiesToResponse::class, ShareErrorsFromSession::class]);
    });