<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ZKTecoController;

Route::match(['GET', 'POST'], '/iclock/cdata', [ZKTecoController::class, 'cdata'])->middleware(['zkteco.sign', 'zkteco.profile']);
Route::get('/iclock/getrequest', [ZKTecoController::class, 'getrequest'])->middleware(['zkteco.sign', 'zkteco.profile']);
