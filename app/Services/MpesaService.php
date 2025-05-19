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
        $this->baseUrl = env('MPESA_BASE_URL', 'https://sandbox.safaricom.co.ke');
        $this->shortcode = env('MPESA_SHORTCODE');
        $this->passkey = env('MPESA_PASSKEY');
        $this->consumerKey = env('MPESA_CONSUMER_KEY');
        $this->consumerSecret = env('MPESA_CONSUMER_SECRET');
        $this->callbackUrl = env('MPESA_CALLBACK_URL');
    }

    public function getAccessToken()
    {
        $url = $this->baseUrl . '/oauth/v1/generate?grant_type=client_credentials';

        $response = Http::withHeaders([
            'Authorization' => 'Basic ' . base64_encode("{$this->consumerKey}:{$this->consumerSecret}")
        ])->get($url);

        if ($response->successful()) {
            return $response->json()['access_token'];
        }

        Log::error('Failed to fetch M-Pesa token: ' . $response->body());
        return null;
    }

    public function stkPush($amount, $phone, $accountReference = "N/A", $transactionDesc)
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
            "PartyB" => env(key: 'MPESA_SHORTCODE'),
            "PhoneNumber" => $phone,
            "CallBackURL" => route(name: 'stk.callback'),
            "AccountReference" => $accountReference,
            "TransactionDesc" => $accountReference,
        ];

        $token = $this->getAccessToken();

        if (!$token) {
            return [
                'success' => false,
                'message' => 'Failed to get access token'
            ];
        }

        $response = Http::withToken($token)
            ->post($this->baseUrl . '/mpesa/stkpush/v1/processrequest', $payload);

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
