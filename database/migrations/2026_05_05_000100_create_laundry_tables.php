<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('branches', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('address')->nullable();
            $table->timestamps();
        });

        Schema::table('users', function (Blueprint $table) {
            if (! Schema::hasColumn('users', 'branch_id')) {
                $table->foreignId('branch_id')->nullable()->after('id');
            }

            if (! Schema::hasColumn('users', 'role')) {
                $table->enum('role', ['admin', 'staff'])->default('staff')->after('password');
            }

            if (! Schema::hasColumn('users', 'is_active')) {
                $table->boolean('is_active')->default(true)->after('role');
            }

            $table->foreign('branch_id')->references('id')->on('branches')->nullOnDelete();
        });

        Schema::create('pricing', function (Blueprint $table) {
            $table->id();
            $table->decimal('price_per_kilo', 10, 2);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('add_on_services', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->decimal('price', 10, 2);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique();
            $table->foreignId('branch_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('customer_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('customer_name');
            $table->string('customer_contact')->nullable();
            $table->string('customer_email')->nullable();
            $table->decimal('weight_kg', 8, 2);
            $table->decimal('price_per_kilo', 10, 2);
            $table->decimal('subtotal', 10, 2);
            $table->decimal('add_on_total', 10, 2)->default(0);
            $table->decimal('total_amount', 10, 2);
            $table->decimal('paid_amount', 10, 2)->default(0);
            $table->enum('payment_status', ['unpaid', 'partial', 'paid'])->default('unpaid');
            $table->enum('status', ['pending', 'washing', 'drying', 'ironing', 'ready_for_pickup', 'claimed'])->default('pending');
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('order_add_on', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->foreignId('add_on_service_id')->constrained()->cascadeOnDelete();
            $table->decimal('price', 10, 2);
            $table->timestamps();
        });

        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->decimal('amount', 10, 2);
            $table->enum('method', ['cash', 'gcash', 'card', 'other'])->default('cash');
            $table->text('remarks')->nullable();
            $table->foreignId('received_by')->constrained('users')->cascadeOnDelete();
            $table->timestamps();
        });

        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('action');
            $table->text('details')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
        Schema::dropIfExists('payments');
        Schema::dropIfExists('order_add_on');
        Schema::dropIfExists('orders');
        Schema::dropIfExists('add_on_services');
        Schema::dropIfExists('pricing');

        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['branch_id']);
        });

        Schema::dropIfExists('branches');
    }
};
