<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Employee;

class EmployeeController extends Controller
{
    public function index(Request $request)
{
    $page = $request->input('page', 1);
    $pageSize = $request->input('page_size', 1200);

    $jwtToken = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoxLCJ1c2VybmFtZSI6ImFkbWluIiwiZXhwIjoxNzI5MjI5MTIzLCJlbWFpbCI6InN1YmFndGlAYWwtYXpoYXIub3IuaWQiLCJvcmlnX2lhdCI6MTcyODYyNDMyM30.GWfxJlUcBmmM9WwuuZiqrX9VRYXD5A95rYK4xrTSENs';

    $response = Http::withHeaders([
        'Authorization' => 'JWT ' . $jwtToken,
        'Content-Type' => 'application/json',
    ])->get('https://biotime.bag-itd.my.id/personnel/api/employees/', [
        'page' => $page,
        'page_size' => $pageSize
    ]);

    $employees = $response->json();

    // Log the full response for debugging
    \Log::info($employees);

    $employeeData = $employees['data'] ?? [];
    $next = $employees['next'] ?? null;
    $previous = $employees['previous'] ?? null;

    return view('employees', [
        'employees' => $employeeData,
        'page' => $page,
        'page_size' => $pageSize,
        'count' => $employees['count'] ?? 0,
        'next' => $next,
        'previous' => $previous,
    ]);
}

}

