<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddItemTypeIdToItemsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('items', function (Blueprint $table) {
            $table->unsignedBigInteger('item_type_id')->nullable();

            $table->foreign('item_type_id')
                ->references('id')
                ->on('item_types')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('items', function (Blueprint $table) {
            $table->dropForeign(['item_type_id']);
            $table->dropColumn('item_type_id');
        });
    }
};
