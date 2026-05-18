<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Update pricing table
        Schema::table('pricing', function (Blueprint $table) {
            $table->decimal('price_per_load', 10, 2)->nullable();
            $table->decimal('max_kilo_per_load', 10, 2)->nullable();
        });

        // 2. Migrate existing pricing data (kilo → load assumption)
        DB::table('pricing')->update([
            'price_per_load' => DB::raw('price_per_kilo'),
            'max_kilo_per_load' => 8 // default assumption (YOU CAN CHANGE THIS)
        ]);

        // 3. Drop old column AFTER migration
        Schema::table('pricing', function (Blueprint $table) {
            $table->dropColumn('price_per_kilo');
        });

        // 4. Update orders table
        Schema::table('orders', function (Blueprint $table) {
            $table->decimal('load_count', 8, 2)->nullable();
            $table->decimal('base_price_per_load', 10, 2)->nullable();
        });

        // 5. Backfill existing orders
        $pricing = DB::table('pricing')->first();

        if ($pricing) {
            DB::table('orders')->update([
                'load_count' => DB::raw("CEIL(weight_kg / {$pricing->max_kilo_per_load})"),
                'base_price_per_load' => $pricing->price_per_load,
            ]);

            DB::table('orders')->update([
                'subtotal' => DB::raw("load_count * base_price_per_load")
            ]);
        }
    }

    public function down(): void
    {
        Schema::table('pricing', function (Blueprint $table) {
            $table->decimal('price_per_kilo', 10, 2)->nullable();
        });

        DB::table('pricing')->update([
            'price_per_kilo' => DB::raw('price_per_load')
        ]);

        Schema::table('pricing', function (Blueprint $table) {
            $table->dropColumn(['price_per_load', 'max_kilo_per_load']);
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['load_count', 'base_price_per_load']);
        });
    }
};