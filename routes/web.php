<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\TransactionController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// // Protecting routes with authentication
// Route::middleware(['auth'])->group(function () {
//     Route::get('/employees', [EmployeeController::class, 'index'])->name('employees.index');
//     Route::get('/transactions', [TransactionController::class, 'index'])->name('transactions.index');
//     Route::get('/fetch-employees', [EmployeeController::class, 'index']);
// });

Route::get('/employees', [EmployeeController::class, 'index'])->name('employees.index');
Route::get('/transactions', [TransactionController::class, 'index'])->name('transactions.index');
Route::get('/fetch-employees', [EmployeeController::class, 'index']);