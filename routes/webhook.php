<?php

use App\Http\Controllers\WebhookMidtrans;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Webhook / Callback Routes
|--------------------------------------------------------------------------
|
|
*/

Route::post('/webhook/midtrans', WebhookMidtrans::class);
