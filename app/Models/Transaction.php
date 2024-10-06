<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    // Specify the table if it doesn't follow Laravel's naming convention
    protected $table = 'transactions';

    // Fillable attributes for mass assignment
    protected $fillable = [
        'emp_code',
        'first_name',
        'last_name',
        'department',
        'position',
        'punch_time',
        'punch_state',
        'verify_type',
        'work_code',
        'gps_location',
        'terminal_sn',
        'terminal_alias',
        'upload_time',
    ];
}
