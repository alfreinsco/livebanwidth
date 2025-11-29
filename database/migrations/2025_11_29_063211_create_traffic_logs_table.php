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
        Schema::create('traffic_logs', function (Blueprint $table) {
            $table->id();
            $table->string('interface_name', 100)->index();
            $table->bigInteger('tx_bits')->default(0)->comment('Transmit bits per second');
            $table->bigInteger('rx_bits')->default(0)->comment('Receive bits per second');
            $table->decimal('tx_mbps', 10, 2)->default(0)->comment('Transmit in Mbps');
            $table->decimal('rx_mbps', 10, 2)->default(0)->comment('Receive in Mbps');
            $table->timestamps();

            // Index untuk query berdasarkan interface dan waktu
            $table->index(['interface_name', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('traffic_logs');
    }
};
