<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Schema;

class CreateDepartmentDailyReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('department_daily_reports', function (Blueprint $table) {
            $table->id();
            $table->string('previous_balance');
            $table->string('current_balance');
            $table->string('credit')->default(0);
            $table->string('debit')->default(0);
            $table->date('date');
            $table->string('kind');
            $table->string('kind_name'); 
            $table->string('karat')->nullable(); 
            $table->string('shares')->nullable(); 
            $table->foreignId('department_id')->constrained('departments');
            $table->string('department_name');  //transfering to department name
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('department_daily_reports');
    }
}
