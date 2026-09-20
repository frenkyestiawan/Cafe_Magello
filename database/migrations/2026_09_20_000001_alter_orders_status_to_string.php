<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (DB::getDriverName() === 'sqlite') {
            return;
        }

        DB::statement("UPDATE orders SET status = CASE status
            WHEN 'pending' THEN 'menunggu'
            WHEN 'processing' THEN 'diproses'
            WHEN 'ready' THEN 'selesai'
            WHEN 'completed' THEN 'selesai'
            WHEN 'cancelled' THEN 'sudah_diambil'
            ELSE 'menunggu'
        END WHERE status IS NOT NULL");

        DB::statement("ALTER TABLE orders MODIFY COLUMN status VARCHAR(50) NOT NULL DEFAULT 'menunggu'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (DB::getDriverName() === 'sqlite') {
            return;
        }

        DB::statement("ALTER TABLE orders MODIFY COLUMN status ENUM('pending', 'processing', 'ready', 'completed', 'cancelled') NOT NULL DEFAULT 'pending'");
    }
};
