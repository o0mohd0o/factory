<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->string('code');
            $table->string('sub_code');
            $table->decimal('fare',8,2)->nullable();
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->unsignedBigInteger('parent_code')->nullable();
            // $table->unsignedBigInteger('parent_2_id')->nullable();
            // $table->unsignedBigInteger('parent_3_id')->nullable();
            // $table->unsignedBigInteger('parent_4_id')->nullable();
            $table->string('name');
            $table->string('karat')->nullable();
            $table->tinyInteger('level_num');
            $table->string('desc_1')->nullable();
            $table->string('desc_2')->nullable();
            $table->string('desc_3')->nullable();
            $table->string('desc_4')->nullable();
            $table->string('desc_5')->nullable();
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
        Schema::dropIfExists('items');
    }
}
