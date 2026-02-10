<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('jobs', function (Blueprint $table) {
            if (!Schema::hasColumn('jobs', 'assign_torna')) {
                $table->boolean('assign_torna')->default(true)->after('assign_tesviye');
            }
            if (!Schema::hasColumn('jobs', 'torna_minutes')) {
                $table->integer('torna_minutes')->nullable()->after('tesviye_minutes');
            }
        });
    }

    public function down(): void
    {
        Schema::table('jobs', function (Blueprint $table) {
            $table->dropColumn(['assign_torna', 'torna_minutes']);
        });
    }
};
