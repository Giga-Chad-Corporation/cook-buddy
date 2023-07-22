<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('admins', function (Blueprint $table) {
            $table->id();
            $table->foreignId("user_id")->constrained("users")->onDelete("cascade");
            $table->string("email")->unique();
            $table->string("password");
            $table->boolean("is_super_admin")->default(false);
            $table->boolean("manage_admins")->default(false);
            $table->boolean("manage_users")->default(false);
            $table->boolean("manage_providers")->default(false);
            $table->boolean("manage_services")->default(false);
            $table->boolean("manage_plans")->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admins');
    }
};
