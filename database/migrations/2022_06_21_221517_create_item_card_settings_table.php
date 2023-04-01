<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItemCardSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('item_card_settings', function (Blueprint $table) {
            $table->id();
            $table->tinyInteger('level_1')->default(2);
            $table->tinyInteger('level_2')->default(2);
            $table->tinyInteger('level_3')->default(3);
            $table->tinyInteger('level_4')->default(3);
            $table->tinyInteger('level_5')->default(3);
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
        Schema::dropIfExists('item_card_settings');
    }
}
