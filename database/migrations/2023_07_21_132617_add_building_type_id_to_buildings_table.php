<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('buildings', function (Blueprint $table) {
            $table->unsignedBigInteger('building_type_id')->after('id');

            $table->foreign('building_type_id')
                ->references('id')
                ->on('building_types')
                ->onDelete('cascade');
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('buildings', function (Blueprint $table) {
            //
        });
    }
};
