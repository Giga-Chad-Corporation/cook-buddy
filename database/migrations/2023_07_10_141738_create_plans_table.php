<?php


use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePlansTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('plans', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->float('monthly_price')->nullable();
            $table->float('annual_price')->nullable();
            $table->boolean('has_ads')->default(false);
            $table->boolean('can_comment')->default(false);
            $table->unsignedInteger('lesson_access')->nullable()->default(0);
            $table->boolean('has_chat_access')->default(false);
            $table->integer('boutique_discount')->default(0);
            $table->boolean('boutique_free_shipping')->default(false);
            $table->boolean('has_cooking_space')->default(false);
            $table->boolean('invitation_to_events')->default(false);
            $table->integer('renewal_discount')->nullable()->default(null);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('plans');
    }
}

