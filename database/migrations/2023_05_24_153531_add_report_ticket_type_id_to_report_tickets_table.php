<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddReportTicketTypeIdToReportTicketsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('report_tickets', function (Blueprint $table) {
            $table->unsignedBigInteger('report_ticket_type_id')->after('id');

            $table->foreign('report_ticket_type_id')
                ->references('id')
                ->on('report_ticket_types')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('report_tickets', function (Blueprint $table) {
            $table->dropForeign(['report_ticket_type_id']);
            $table->dropColumn('report_ticket_type_id');
        });
    }
}
