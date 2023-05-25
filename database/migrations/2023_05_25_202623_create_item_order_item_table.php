<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItemOrderItemTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('item_order_item', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('item_order_id');
            $table->unsignedBigInteger('item_id');
            $table->integer('quantity');
            $table->timestamps();

            $table->foreign('item_order_id')
                ->references('id')
                ->on('item_orders')
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
        Schema::dropIfExists('item_order_item');
    }
};
