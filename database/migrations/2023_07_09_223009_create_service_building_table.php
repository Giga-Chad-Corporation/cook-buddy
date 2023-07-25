<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateServiceBuildingTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('service_building', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_id')->constrained()->onDelete('cascade');
            $table->foreignId('building_id')->constrained()->onDelete('cascade');
            // Add any additional columns as needed for the relationship
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('service_building');
    }
}

