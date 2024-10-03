<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('event_ticket_categories', function (Blueprint $table) {
            $table->text('benefits')->after('color');
            $table->text('color_secondary')->after('color');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('event_ticket_categories', function (Blueprint $table) {
            $table->dropColumn('benefits');
            $table->dropColumn('color_secondary');
        });
    }
};
