<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (! Schema::hasColumn('users', 'staff_signup_status')) {
                $table->string('staff_signup_status')->default('approved')->after('is_active');
            }

            if (! Schema::hasColumn('users', 'must_change_password')) {
                $table->boolean('must_change_password')->default(false)->after('staff_signup_status');
            }

            if (! Schema::hasColumn('users', 'approved_at')) {
                $table->timestamp('approved_at')->nullable()->after('must_change_password');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'approved_at')) {
                $table->dropColumn('approved_at');
            }

            if (Schema::hasColumn('users', 'must_change_password')) {
                $table->dropColumn('must_change_password');
            }

            if (Schema::hasColumn('users', 'staff_signup_status')) {
                $table->dropColumn('staff_signup_status');
            }
        });
    }
};
