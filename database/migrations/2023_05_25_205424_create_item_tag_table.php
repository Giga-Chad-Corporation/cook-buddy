<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItemTagTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('item_tag', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('item_id');
            $table->unsignedBigInteger('tag_id');
            $table->timestamps();

            $table->foreign('item_id')
                ->references('id')
                ->on('items')
                ->onDelete('cascade');

            $table->foreign('tag_id')
                ->references('id')
                ->on('tags')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('item_tag');
    }
}
;
