<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('services', function (Blueprint $table) {
            if (!Schema::hasColumn('services', 'service_type_id')) {
                $table->unsignedBigInteger('service_type_id')->after('id');

                $table->foreign('service_type_id')
                    ->references('id')
                    ->on('service_types')
                    ->onDelete('cascade');
            }
        });
    }



    /**
     * Reverse the migrations.
     */
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('services', function (Blueprint $table) {
            $table->dropForeign(['service_type_id']); // drop foreign key
            $table->dropColumn('service_type_id'); // drop the column
        });
    }

};
