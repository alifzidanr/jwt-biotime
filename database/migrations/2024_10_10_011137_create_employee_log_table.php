<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeeLogTable extends Migration
{
    public function up()
    {
        Schema::create('employee_log', function (Blueprint $table) {
            $table->id();  // Adds an auto-incrementing 'id' primary key
            $table->string('emp_code')->charset('utf8mb4')->collate('utf8mb4_general_ci');
            $table->string('first_name', 100)->nullable();
            $table->string('department', 100)->nullable();
            $table->boolean('below5mins_late')->nullable();
            $table->boolean('above5mins_late')->nullable();
            $table->boolean('below5mins_signout')->nullable();
            $table->boolean('above5mins_signout')->nullable();
            $table->boolean('attendance')->nullable();
            $table->dateTime('clock_in')->nullable();
            $table->dateTime('clock_out')->nullable();
            $table->enum('punctuality', ['100%', '50%', 'NO'])->nullable(); // Adds the 'punctuality' column
            $table->boolean('punch_count')->default(0); // Adds the 'punch_count' column
            $table->enum('ukk', ['100%', 'NO', '50%']);
            $table->timestamps(); // Adds created_at and updated_at columns
        });
    }

    public function down()
    {
        Schema::dropIfExists('employee_log');
    }
}