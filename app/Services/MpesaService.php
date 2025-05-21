<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class MpesaService
{
protected $baseUrl;
protected $shortcode;
protected $passkey;
protected $consumerKey;
protected $consumerSecret;
protected $callbackUrl;

public function __construct()
{
    $this->consumerKey = env('MPESA_CONSUMER_KEY');
    $this->consumerSecret = env('MPESA_CONSUMER_SECRET');

    if (!$this->consumerKey || !$this->consumerSecret) {
        Log::error('Missing M-Pesa consumer key or secret');
    }
    $this->baseUrl = env('MPESA_ENV') == 'production'
        ? "https://api.safaricom.co.ke"
        : "https://sandbox.safaricom.co.ke";

    $this->shortcode = env('MPESA_SHORTCODE');
    $this->passkey = env('MPESA_PASSKEY');
    $this->callbackUrl = env('MPESA_CALLBACK_URL');
}

public function getAccessToken()
{
    $url = $this->baseUrl . '/oauth/v1/generate?grant_type=client_credentials';

    $response = Http::withBasicAuth($this->consumerKey, $this->consumerSecret)->get($url);

    if ($response->successful()) {
        return $response->json()['access_token'];
    }

    Log::error('Failed to fetch M-Pesa token: ' . $response->body());
    return null;
}


public function stkPush($amount, $phone, $transactionDesc, $accountReference)
{
    $timestamp = Carbon::now()->format('YmdHis');
    $password = base64_encode($this->shortcode . $this->passkey . $timestamp);

    $payload = [
        "BusinessShortCode" => $this->shortcode,
        "Password" => $password,
        "Timestamp" => $timestamp,
        "TransactionType" => "CustomerPayBillOnline",
        "Amount" => $amount,
        "PartyA" => $phone,
        "PartyB" => $this->shortcode,
        "PhoneNumber" => $phone,
        "CallBackURL" => route('stk.callback', [], true),
        "AccountReference" => $accountReference,
        "TransactionDesc" => $transactionDesc,
    ];

    $token = $this->getAccessToken();

    if (!$token) {
        Log::error('Failed to fetch M-Pesa token');
        return [
            'success' => false,
            'message' => 'Failed to get access token'
        ];
    }

    $response = Http::withToken($token)
        ->post($this->baseUrl . '/mpesa/stkpush/v1/processrequest', $payload);

    Log::info('M-Pesa STK Push request payload:', $payload);
    Log::info('M-Pesa STK Push response:', $response->json());

    if ($response->successful()) {
        return [
            'success' => true,
            'data' => $response->json()
        ];
    }

    Log::error('STK Push failed: ' . $response->body());
    return [
        'success' => false,
        'message' => 'STK Push failed',
        'response' => $response->json()
    ];
}
}
