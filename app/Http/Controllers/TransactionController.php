<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Transaction;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        // JWT Token for authorization
        $jwtToken = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoxLCJ1c2VybmFtZSI6ImFkbWluIiwiZXhwIjoxNzI4Nzg0OTQ1LCJlbWFpbCI6InN1YmFndGlAYWwtYXpoYXIub3IuaWQiLCJvcmlnX2lhdCI6MTcyODE4MDE0NX0.r7Ph0qeLzXoUQxNMxO8Q6vAsjLOVfodK-v7HpEh1XGU';

        // Get the query parameters from the request, set page_size to 1200
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

        // Store fetched transactions into the database
        foreach ($transactions['data'] as $transaction) {
            Transaction::updateOrCreate(
                ['emp_code' => $transaction['emp_code'], 'punch_time' => $transaction['punch_time']],
                [
                    'first_name' => $transaction['first_name'],
                    'last_name' => $transaction['last_name'],
                    'department' => $transaction['department'],
                    'position' => $transaction['position'],
                    'punch_state' => $transaction['punch_state'],
                    'verify_type' => $transaction['verify_type'],
                    'work_code' => $transaction['work_code'],
                    'gps_location' => $transaction['gps_location'],
                    'terminal_sn' => $transaction['terminal_sn'],
                    'terminal_alias' => $transaction['terminal_alias'],
                    'upload_time' => $transaction['upload_time'],
                ]
            );
        }

        // Pass the transactions to the Blade view
        return view('transactions', compact('transactions'));
    }
}
