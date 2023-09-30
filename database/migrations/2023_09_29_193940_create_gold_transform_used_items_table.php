<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGoldTransformUsedItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gold_transform_used_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('gold_transform_id')->constrained('gold_transforms');
            $table->foreignId('department_item_id')->constrained('department_items');
            $table->double('weight');
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
        Schema::dropIfExists('gold_transform_used_items');
    }
}
