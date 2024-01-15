<?php

namespace App\Http\Controllers;

use Midtrans\Notification;
use Illuminate\Support\Facades\Mail;
use App\Mail\TicketMail;
use App\Models\Transaction;
use App\Models\Ticket;
use App\Models\User;

class WebhookMidtrans extends Controller
{
    /**
     * Midtrans payment notification handler
     */
    public function __invoke()
    {
        $notification = new Notification;
        $res = (array) $notification->getResponse();
        $status = $res['transaction_status'];

        $transaction = Transaction::find($res['order_id']);
        $transaction->status = $status;
        $transaction->expire_at = $res['expiry_time'];
        $transaction->save();

        if ($status == 'settlement') {
            $ticket = Ticket::where('transaction_id', $transaction->id)->first();
            $ticket->code = Ticket::generateSerialNumber();
            $ticket->save();

            $user = User::find($ticket->user_id);

            Mail::to($user)->send(new TicketMail($ticket));
        }

        return response(status: 200);
    }
}
