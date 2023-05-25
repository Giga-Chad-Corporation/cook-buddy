<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBuildingIdToRoomsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('rooms', function (Blueprint $table) {
            $table->unsignedBigInteger('building_id')->after('id')->nullable();
            $table->foreign('building_id')
                ->references('id')
                ->on('buildings')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('rooms', function (Blueprint $table) {
            $table->dropForeign(['building_id']);
            $table->dropColumn('building_id');
        });
    }
};
