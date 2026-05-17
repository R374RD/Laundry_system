<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            if (! Schema::hasColumn('orders', 'customer_id')) {
                $table->foreignId('customer_id')->nullable()->after('user_id')->constrained('users')->nullOnDelete();
            }

            if (! Schema::hasColumn('orders', 'customer_email')) {
                $table->string('customer_email')->nullable()->after('customer_contact');
            }
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            if (Schema::hasColumn('orders', 'customer_id')) {
                $table->dropConstrainedForeignId('customer_id');
            }

            if (Schema::hasColumn('orders', 'customer_email')) {
                $table->dropColumn('customer_email');
            }
        });

    }
};
