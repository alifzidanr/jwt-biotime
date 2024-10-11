<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Transaction;

class TransactionController extends Controller
{
    public function index(Request $request)
{
    $jwtToken = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoxLCJ1c2VybmFtZSI6ImFkbWluIiwiZXhwIjoxNzI5MjI5MTIzLCJlbWFpbCI6InN1YmFndGlAYWwtYXpoYXIub3IuaWQiLCJvcmlnX2lhdCI6MTcyODYyNDMyM30.GWfxJlUcBmmM9WwuuZiqrX9VRYXD5A95rYK4xrTSENs';

    // Get the query parameters from the request, set default page_size to 3000
    $params = [
        'page' => $request->get('page', 1),
        'page_size' => 3000,
        'emp_code' => $request->get('emp_code'),
        'terminal_sn' => $request->get('terminal_sn'),
        'terminal_alias' => $request->get('terminal_alias'),
        'start_time' => $request->get('start_time'),
        'end_time' => $request->get('end_time'),
    ];

    // Make an API call to fetch transaction data
    $response = Http::withHeaders([
        'Authorization' => 'JWT ' . $jwtToken,
        'Content-Type' => 'application/json',
    ])->get('https://biotime.bag-itd.my.id/iclock/api/transactions/', $params);

    $transactions = $response->json();

    // Handle 'next' and 'previous' for pagination
    $transactionData = $transactions['data'] ?? [];
    $next = $transactions['next'] ?? null;
    $previous = $transactions['previous'] ?? null;

    // Pass the transactions and pagination info to the view
    return view('transactions', [
        'transactions' => $transactionData,
        'page' => $params['page'],
        'page_size' => $params['page_size'],
        'count' => $transactions['count'] ?? 0,
        'next' => $next,
        'previous' => $previous,
    ]);
}
}
