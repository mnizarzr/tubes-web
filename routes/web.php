<?php

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/test_mail', function () {
    $ticket = App\Models\Ticket::inRandomOrder()->first();
    $event = App\Models\Event::find($ticket->event_id);

    $mail = new App\Mail\TicketMail('PijarAPI', $event, $ticket);
    Mail::to('khususdroid4x@gmail.com')->send($mail);

    return $mail;
});
