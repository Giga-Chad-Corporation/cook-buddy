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
        Schema::create('service_provider', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_id')->constrained();
            $table->foreignId('provider_id')->constrained();
            $table->decimal('commission', 5, 2); // Column to store commission percentage
            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('service_provider');
    }

};
