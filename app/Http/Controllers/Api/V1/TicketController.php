<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTicketRequest;
use App\Http\Requests\UpdateTicketRequest;
use App\Models\Event;
use App\Models\Ticket;
use App\Models\Transaction;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Midtrans\Snap;

class TicketController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(Ticket::where('user_id', Auth::user()->id)->whereNotNull('code')->get());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTicketRequest $request)
    {
        $user = Auth::user();
        $validated = $request->validated();
        $event = Event::find($validated['event_id']);

        $newTicket = new Ticket;

        $newTicket->user_id = $user->id;
        $newTicket->event_id = $validated['event_id'];
        $newTicket->holder_name = $validated['holder_name'];
        $newTicket->holder_gender = $validated['holder_gender'];
        $newTicket->holder_email = $validated['holder_email'];
        $newTicket->holder_phone = $validated['holder_phone'];
        $newTicket->purchase_amount = $validated['purchase_amount'];
        $newTicket->notes = $validated['notes'] ?? null;

        $newTicket->save();

        $newTransactionId = Transaction::generateSerialNumber();
        $transaction_details = [
            'order_id' => $newTransactionId,
            'gross_amount' => $newTicket->purchase_amount, // no decimal allowed for creditcard
        ];

        $item_details = [
            'id' => $newTicket->id,
            'price' => $newTicket->purchase_amount,
            'quantity' => 1,
            'name' => $event->name . ' ticket',
        ];

        $customer_details = [
            'first_name' => $newTicket->holder_name,
            'email' => $newTicket->holder_email,
            'phone' => $newTicket->holder_phone,
        ];

        $params = [
            'transaction_details' => $transaction_details,
            'customer_details' => $customer_details,
            'item_details' => [$item_details],
        ];

        try {

            $paymentUrl = Snap::createTransaction($params);

            $newTransaction = new Transaction;
            $newTransaction->id = $newTransactionId;
            $newTransaction->amount = $newTicket->purchase_amount;
            $newTransaction->status = 'pending';
            $newTransaction->payment_channel = 'cashless';
            $newTransaction->payment_link = $paymentUrl->redirect_url;
            $newTransaction->expire_at = Carbon::now()->addDays(6);
            $newTransaction->save();

            $newTicket->transaction_id = $newTransactionId;
            $newTicket->save();

            return response()->json((array) $paymentUrl, 201);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Ticket $ticket)
    {
        return response()->json($ticket);
    }

    /**
     * Display the specified resource.
     */
    public function showByTransactionId(Transaction $transaction)
    {
        $user = Auth::user();
        $ticket = Ticket::where('transaction_id', $transaction->id)->first();

        if ($ticket->user_id != $user->id) {
            abort(403, 'Forbidden');
        }

        return response()->json($ticket);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTicketRequest $request, Ticket $ticket)
    {
        $validated = $request->validated();

        return response()->json($ticket->update($validated));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Ticket $ticket)
    {
        return response()->json($ticket->delete());
    }
}
