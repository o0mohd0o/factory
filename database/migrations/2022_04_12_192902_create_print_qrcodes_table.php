<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePrintQrcodesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('print_qrcodes', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->string('person_on_charge')->nullable();
            $table->integer('count');
            $table->decimal('total_weight',8,2);
            $table->decimal('total_fare',8,2);
            $table->decimal('gold18',8,2)->nullable();
            $table->decimal('gold21',8,2)->nullable();
            $table->decimal('gold22',8,2)->nullable();
            $table->decimal('gold24',8,2)->nullable();
            $table->decimal('weight_all21',8,2);
            $table->decimal('weight_all24',8,2);
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
        Schema::dropIfExists('print_qrcodes');
    }
}
