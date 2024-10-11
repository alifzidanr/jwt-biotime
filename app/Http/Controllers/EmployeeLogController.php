<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EmployeeLog;
use App\Models\Employee;
use App\Models\Transaction;
use Carbon\Carbon;

class EmployeeLogController extends Controller
{
    public function index(Request $request)
    {
        // Process transactions to set clock_in and clock_out
        $this->updateClockInOut();

        // Get the current page and page size from the request, or default to 1 and 50
        $page = $request->get('page', 1);
        $pageSize = $request->get('page_size', 50);

        // Fetch paginated employee log data
        $employeeLogs = EmployeeLog::paginate($pageSize, ['*'], 'page', $page);

        // Pass the data and pagination info to the Blade view
        return view('employee_log', [
            'employeeLogs' => $employeeLogs->items(), // Only the logs for the current page
            'page' => $employeeLogs->currentPage(),
            'page_size' => $pageSize,
            'next' => $employeeLogs->nextPageUrl(),
            'previous' => $employeeLogs->previousPageUrl(),
        ]);
    }

    // Define the missing updateClockInOut method
    private function updateClockInOut()
    {
        // Define standard times for comparison
        $clockInStandard = Carbon::createFromTime(7, 0, 0);  // 07:00 AM
        $clockOutStandard = Carbon::createFromTime(15, 0, 0);  // 03:00 PM
        $below5minsSignoutStandard = Carbon::createFromTime(14, 55, 0); // 02:55 PM

        // Fetch transactions and group them by emp_code and date
        $transactions = Transaction::all();
        $groupedTransactions = [];

        foreach ($transactions as $transaction) {
            $punchTime = Carbon::parse($transaction->punch_time);
            $date = $punchTime->format('Y-m-d');  // Get date part of punch_time

            $groupedTransactions[$transaction->emp_code][$date][] = $punchTime;
        }

        foreach ($groupedTransactions as $empCode => $dates) {
            foreach ($dates as $date => $punchTimes) {
                sort($punchTimes);  // Sort punch times
                $clockIn = $punchTimes[0];  // Earliest punch time
                $clockOut = count($punchTimes) > 1 ? $punchTimes[count($punchTimes) - 1] : null;  // Latest punch time

                // Fetch employee data
                $employee = Employee::where('emp_code', $empCode)->first();
                if (!$employee) {
                    continue; // Skip if employee not found
                }

                // Initialize UKK variables
                $ukk = 'NO'; // Default to 'NO'
                $hasClockIn = isset($clockIn);
                $hasClockOut = isset($clockOut);

                // Debugging: log current empCode, clockIn, and clockOut
                \Log::info("empCode: $empCode, date: $date, clockIn: $clockIn, clockOut: $clockOut");

                // Check for '100%' condition
                if ($hasClockIn && $hasClockOut) {
                    if ($clockIn->lessThan($clockInStandard) && $clockOut->greaterThanOrEqualTo($clockOutStandard)) {
                        $ukk = '100%'; // Both conditions met for 100%
                    } 
                    // Check for '50%' condition
                    elseif ($clockIn->greaterThanOrEqualTo($clockInStandard) && $clockIn->lessThan($clockInStandard->copy()->addMinutes(5))) {
                        $ukk = '50%'; // Clock in between 07:01 and 07:04
                    } elseif ($clockOut && $clockOut->greaterThanOrEqualTo($below5minsSignoutStandard) && $clockOut->lessThan($clockOutStandard)) {
                        $ukk = '50%'; // Clock out between 14:55 and 15:00
                    }
                    // Check for 'NO' conditions
                    elseif ($clockIn->greaterThan($clockInStandard->copy()->addMinutes(5)) || 
                            ($clockOut && $clockOut->lessThan($below5minsSignoutStandard))) {
                        $ukk = 'NO'; // Clock in after 07:05 or clock out before 14:55
                    }
                } elseif ($hasClockIn && !$hasClockOut) {
                    // Only clock_in exists
                    $ukk = '50%'; // Set to 50% if only clock_in exists
                } elseif (!$hasClockIn && $hasClockOut) {
                    // Only clock_out exists
                    $ukk = '50%'; // Set to 50% if only clock_out exists
                }

                // Debugging: log the determined UKK value
                \Log::info("Determined UKK for empCode $empCode: $ukk");

                // Update or create the employee log entry
                EmployeeLog::updateOrCreate(
                    ['emp_code' => $empCode], // Use emp_code as the unique identifier
                    [
                        'first_name' => $employee->first_name,
                        'department' => $employee->department,
                        'emp_code' => str_pad($empCode, 8, '0', STR_PAD_LEFT),
                        'clock_in' => $hasClockIn ? $clockIn : null,
                        'clock_out' => $hasClockOut ? $clockOut : null,
                        'below5mins_late' => $hasClockIn && $clockIn->greaterThan($clockInStandard) && $clockIn->lessThan($clockInStandard->copy()->addMinutes(5)) ? 1 : 0,
                        'above5mins_late' => $hasClockIn && $clockIn->greaterThan($clockInStandard->copy()->addMinutes(5)) ? 1 : 0,
                        'below5mins_signout' => $hasClockOut && $clockOut->greaterThan($below5minsSignoutStandard) && $clockOut->lessThan($clockOutStandard) ? 1 : 0,
                        'above5mins_signout' => $hasClockOut && $clockOut->lessThan($below5minsSignoutStandard) ? 1 : 0,
                        'attendance' => ($hasClockIn || $hasClockOut) ? 1 : 0,
                        'ukk' => $ukk,
                    ]
                );
            }
        }
    }
}
