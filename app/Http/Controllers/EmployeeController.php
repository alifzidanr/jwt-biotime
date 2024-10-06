<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Employee;

class EmployeeController extends Controller
{
    public function index(Request $request)
    {
        // Fetch data from Biotime API
        $jwtToken = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoxLCJ1c2VybmFtZSI6ImFkbWluIiwiZXhwIjoxNzI4Nzg0OTQ1LCJlbWFpbCI6InN1YmFndGlAYWwtYXpoYXIub3IuaWQiLCJvcmlnX2lhdCI6MTcyODE4MDE0NX0.r7Ph0qeLzXoUQxNMxO8Q6vAsjLOVfodK-v7HpEh1XGU';

        $response = Http::withHeaders([
            'Authorization' => 'JWT ' . $jwtToken,
            'Content-Type' => 'application/json',
        ])->get('https://biotime.bag-itd.my.id/personnel/api/employees/');

        $employees = $response->json();

        // Iterate through the employees and store them in the database
        foreach ($employees['data'] as $employeeData) {
            Employee::updateOrCreate(
                ['emp_code' => $employeeData['emp_code']], // Check if employee exists by emp_code
                [
                    'first_name' => $employeeData['first_name'],
                    'last_name' => $employeeData['last_name'],
                    'department' => $employeeData['department']['dept_name'] ?? null,
                    'hire_date' => $employeeData['hire_date'],
                ]
            );
        }

        // Return the view with the employee data
        return view('employees', compact('employees'));
    }
}
