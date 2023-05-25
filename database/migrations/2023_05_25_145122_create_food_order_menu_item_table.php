<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFoodOrderMenuItemTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('food_order_menu_item', function (Blueprint $table) {
            $table->id();
            $table->foreignId('food_order_id')->constrained()->onDelete('cascade');
            $table->foreignId('menu_item_id')->constrained()->onDelete('cascade');
            $table->integer('quantity');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('food_order_menu_item');
    }
};
