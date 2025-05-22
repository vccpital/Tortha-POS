<?php

namespace App\Http\Controllers;

use App\Models\MpesaTransaction;
use Illuminate\Support\Facades\Auth;

class MpesaTransactionController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // For the 'admin' user, filter transactions based on the user's store and group by cashier
        if ($user->usertype === 'admin') {
            // Get the transactions for the admin's store and group by cashier
            $transactions = MpesaTransaction::with(['order', 'order.cashier'])
                ->whereHas('order', function ($query) use ($user) {
                    $query->where('store_id', $user->store_id); // Filter by store_id
                })
                ->latest()
                ->get()
                ->groupBy(function ($transaction) {
                    return $transaction->order->cashier->name ?? 'Unknown Cashier'; // Group by cashier
                });
        }
        // For the 'devadmin', show all transactions, grouped by store (similar to the previous requirement)
        elseif ($user->usertype === 'devadmin') {
            $transactions = MpesaTransaction::with(['order', 'order.store'])
                ->latest()
                ->get()
                ->groupBy(function ($transaction) {
                    return $transaction->order->store->name ?? 'Unknown Store'; // Group by store
                });
        }
        else {
            // Default case: all transactions for regular users or cashiers, no grouping
            $transactions = MpesaTransaction::with(['order'])->latest()->get();
        }

        return view('mpesa_transactions.index', compact('transactions'));
    }
}
