<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRoomTypeIdToRoomsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('rooms', function (Blueprint $table) {
            $table->unsignedBigInteger('room_type_id')->after('id')->nullable();
            $table->foreign('room_type_id')
                ->references('id')
                ->on('room_types')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('rooms', function (Blueprint $table) {
            $table->dropForeign(['room_type_id']);
            $table->dropColumn('room_type_id');
        });
    }
};
