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
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->timestamp('start_date_time')->useCurrent();
            $table->timestamp('end_date_time')->nullable();
            $table->text('title')->nullable();
            $table->text('description')->nullable();
            $table->unsignedInteger('number_places')->default(1);
            $table->string('picture')->nullable();
            $table->decimal('cost', 8, 2);  // add the cost field
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};

