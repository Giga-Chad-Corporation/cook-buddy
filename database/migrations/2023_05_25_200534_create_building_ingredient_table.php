<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBuildingIngredientTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('building_ingredient', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('building_id');
            $table->unsignedBigInteger('ingredient_id');
            $table->integer('quantity');
            $table->timestamps();

            $table->foreign('building_id')
                ->references('id')
                ->on('buildings')
                ->onDelete('cascade');

            $table->foreign('ingredient_id')
                ->references('id')
                ->on('ingredients')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('building_ingredient');
    }
};
