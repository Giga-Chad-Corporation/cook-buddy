<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLessonIdToLessonSectionsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('lesson_sections', function (Blueprint $table) {
            $table->unsignedBigInteger('lesson_id')->after('id');
            $table->foreign('lesson_id')
                ->references('id')
                ->on('lessons')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('lesson_sections', function (Blueprint $table) {
            $table->dropForeign(['lesson_id']);
            $table->dropColumn(['lesson_id']);
        });
    }
}
