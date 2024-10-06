<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionsTable extends Migration
{
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->string('emp_code');
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('department')->nullable();
            $table->string('position')->nullable();
            $table->timestamp('punch_time')->nullable();
            $table->string('punch_state')->nullable();
            $table->string('verify_type')->nullable();
            $table->string('work_code')->nullable();
            $table->string('gps_location')->nullable();
            $table->string('terminal_sn')->nullable();
            $table->string('terminal_alias')->nullable();
            $table->timestamp('upload_time')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('transactions');
    }
}
