<?php

namespace App\Http\Controllers;


use App\Models\MpesaTransaction;

class MpesaTransactionController extends Controller
{
    //
    public function index()
    {
    $transactions = MpesaTransaction::with('order')->latest()->get();
    return view('mpesa_transactions.index', compact('transactions'));
}
}
