<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('orders')->where('status', 'completed')->update(['status' => 'claimed']);

        if (DB::connection()->getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE orders MODIFY status ENUM('pending', 'washing', 'drying', 'ironing', 'ready_for_pickup', 'claimed') NOT NULL DEFAULT 'pending'");
        }
    }

    public function down(): void
    {
        DB::table('orders')->where('status', 'claimed')->update(['status' => 'completed']);

        if (DB::connection()->getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE orders MODIFY status ENUM('pending', 'washing', 'drying', 'ready_for_pickup', 'completed') NOT NULL DEFAULT 'pending'");
        }
    }
};
