<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Schema;

class CreateOfficeTransfersReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('office_transfer_reports', function (Blueprint $table) {
            $table->id();
            $table->string('weight');
            $table->string('department_previous_balance');
            $table->string('department_current_balance');
            $table->date('date');
            $table->string('kind');
            $table->string('kind_name'); 
            $table->string('karat')->nullable(); 
            $table->string('shares')->nullable();
            $table->string('transfer_type'); 
            $table->unsignedBigInteger('doc_num');
            $table->foreignId('department_id')->constrained('departments');
            $table->string('department_name');  //transfering to department name
            $table->string('type')->default('create');
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
        Schema::dropIfExists('office_transfer_reports');
    }
}
