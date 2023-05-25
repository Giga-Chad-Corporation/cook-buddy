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
        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('provider_id');
            $table->text('message_content');
            $table->timestamps();

            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');

            $table->foreign('provider_id')
                ->references('id')
                ->on('providers')
                ->onDelete('cascade');
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('messages');
    }

};
