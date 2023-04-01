<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Schema;

class CreateHesabatTransfersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hesabat_transfers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('sender_branch_id');
            $table->string('sender_branch_name');
            $table->date('date');
            $table->string('person_on_charge');
            $table->string('transfer_sanad_num');
            $table->foreignId('department_id')->constrained('departments');
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
        Schema::dropIfExists('hesabat_transfers');
    }
}
