<?php

namespace App\Http\Controllers;

use App\Mail\TicketMail;
use App\Models\Event;
use App\Models\Ticket;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Midtrans\Notification;

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
            $ticket->code = Ticket::generateSerialNumber($ticket->event_id);
            $ticket->save();

            $event = Event::find($ticket->event_id);
            // $user = User::find($ticket->user_id);

            Mail::to($ticket->holder_email)->send(new TicketMail($ticket->holder_name, $event, $ticket));
        }

        return response(status: 200);
    }
}
