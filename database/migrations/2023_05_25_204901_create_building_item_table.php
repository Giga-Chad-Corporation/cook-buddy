<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBuildingItemTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('building_item', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('building_id');
            $table->unsignedBigInteger('item_id');
            $table->integer('quantity')->default(0);
            $table->timestamps();

            $table->foreign('building_id')
                ->references('id')
                ->on('buildings')
                ->onDelete('cascade');

            $table->foreign('item_id')
                ->references('id')
                ->on('items')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('building_item');
    }
}
;
