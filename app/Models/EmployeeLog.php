<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeLog extends Model
{
    use HasFactory;

    protected $table = 'employee_log'; 

    public $timestamps = false;

    protected $fillable = [
        'emp_code',
        'first_name',
        'department',
        'below5mins_late',
        'above5mins_late',
        'below5mins_signout',
        'above5mins_signout',
        'attendance',
        'clock_in',
        'clock_out',
        'ukk',
    ];
}
