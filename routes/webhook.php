<?php

use App\Http\Controllers\Api\V1\TransactionController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Webhook / Callback Routes
|--------------------------------------------------------------------------
|
|
*/

Route::post('/webhook/midtrans', [TransactionController::class, 'webhook']);
