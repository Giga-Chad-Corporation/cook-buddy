<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('buildings', function (Blueprint $table) {
            $table->unsignedBigInteger('region_id')->nullable(); // assuming that a building might not be associated with a region.
            $table->foreign('region_id')->references('id')->on('regions')->onDelete('set null'); //assuming you want to set the region_id to null when the associated region is deleted.
        });
    }

    public function down()
    {
        Schema::table('buildings', function (Blueprint $table) {
            $table->dropForeign(['region_id']);
            $table->dropColumn(['region_id']);
        });
    }
};
