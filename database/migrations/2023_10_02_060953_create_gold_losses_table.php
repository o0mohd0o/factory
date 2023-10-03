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
            $table->foreignId('department_id')->constrained();
            $table->double('weight_in_21');
            $table->string('person_on_charge');
            $table->unsignedBigInteger('lossable_id');
            $table->string('lossable_type');
            $table->softDeletes();
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
