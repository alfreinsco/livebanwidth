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
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'active_mikrotik_id')) {
                $table->unsignedBigInteger('active_mikrotik_id')->nullable()->after('password');
                $table->foreign('active_mikrotik_id')
                    ->references('id')
                    ->on('mikrotiks')
                    ->onDelete('set null');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'active_mikrotik_id')) {
                $table->dropForeign(['active_mikrotik_id']);
                $table->dropColumn('active_mikrotik_id');
            }
        });
    }
};
