<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasColumn('orders', 'price_per_kilo')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->dropColumn('price_per_kilo');
            });
        }
    }

    public function down(): void
    {
        if (! Schema::hasColumn('orders', 'price_per_kilo')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->decimal('price_per_kilo', 10, 2)->nullable()->after('weight_kg');
            });
        }

        DB::table('orders')
            ->whereNull('price_per_kilo')
            ->update([
                'price_per_kilo' => DB::raw('COALESCE(base_price_per_load, 0)'),
            ]);
    }
};
