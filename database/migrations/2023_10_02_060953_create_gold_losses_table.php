<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGoldLossesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gold_losses', function (Blueprint $table) {
            $table->id();
            // $table->foreignId('item_id')->constrained('items');
            // $table->double('actual_shares')->unsigned()->nullable();
            $table->double('loss_weight_in_21');
            $table->double('total_used_gold_in_21');
            $table->unsignedBigInteger('worker_id')->nullable();
            $table->unsignedBigInteger('lossable_id');
            $table->string('lossable_type');
            $table->date('date');
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
        Schema::dropIfExists('gold_losses');
    }
}
