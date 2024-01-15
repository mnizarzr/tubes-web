<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateTransactionRequest;
use App\Models\Transaction;
use BadMethodCallException;
use Midtrans\Notification;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(Transaction::all());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store()
    {
        throw new BadMethodCallException('Function not implemented');
    }

    /**
     * Display the specified resource.
     */
    public function show(Transaction $transaction)
    {
        return response()->json($transaction);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTransactionRequest $request, Transaction $transaction)
    {
        $validated = $request->validated();

        return response()->json($transaction->update($validated));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Transaction $transaction)
    {
        return response()->json($transaction->delete());
    }

    /**
     * Midtrans payment notification handler
     */
    public function webhook()
    {
        $notification = new Notification;
        $res = $notification->getResponse();

        $transaction = Transaction::firstOrFail($res['order_id']);
        $transaction->status = $res['transaction_status'];
        $transaction->expire_at = $res['expiry_time'];
        $transaction->save();

        return response(status: 200);
    }
}
