<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDepartmentItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('department_items', function (Blueprint $table) {
            $table->id();
            $table->string('kind');
            $table->string('kind_name');
            $table->string('karat')->nullable();
            $table->string('shares')->nullable();
            $table->string('current_weight');
            $table->string('previous_weight');
            $table->foreignId('department_id')->constrained();
            // $table->foreignId('item_id')->constrained('items');
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
        Schema::dropIfExists('department_items');
    }
}
