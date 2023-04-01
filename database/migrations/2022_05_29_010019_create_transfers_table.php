<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransfersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transfers', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->string('person_on_charge');
            $table->string('transfer_from_name');
            $table->string('transfer_to_name');
            $table->string('kind');
            $table->string('kind_name');
            $table->string('karat')->nullable();
            $table->string('shares')->nullable();
            $table->string('shares_to_transfer')->nullable();
            $table->string('weight_to_transfer');
            $table->string('item_weight_before_transfer');
            $table->string('item_weight_after_transfer');
            $table->foreignId('department_item_id')->constrained('department_items');
            $table->foreignId('transfer_from')->constrained('departments');
            $table->foreignId('transfer_to')->constrained('departments');
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
        Schema::dropIfExists('transfers');
    }
}
