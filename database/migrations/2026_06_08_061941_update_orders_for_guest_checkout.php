<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {

            if (!Schema::hasColumn('orders', 'customer_name')) {
                $table->string('customer_name')->nullable();
            }

            if (!Schema::hasColumn('orders', 'customer_email')) {
                $table->string('customer_email')->nullable();
            }

            if (!Schema::hasColumn('orders', 'customer_phone')) {
                $table->string('customer_phone')->nullable();
            }

            if (!Schema::hasColumn('orders', 'shipping_address')) {
                $table->text('shipping_address')->nullable();
            }

            if (!Schema::hasColumn('orders', 'city')) {
                $table->string('city')->nullable();
            }

            if (!Schema::hasColumn('orders', 'state')) {
                $table->string('state')->nullable();
            }

            if (!Schema::hasColumn('orders', 'pincode')) {
                $table->string('pincode')->nullable();
            }

            if (!Schema::hasColumn('orders', 'gateway_order_id')) {
                $table->string('gateway_order_id')->nullable();
            }

            if (!Schema::hasColumn('orders', 'payment_response')) {
                $table->json('payment_response')->nullable();
            }

            if (!Schema::hasColumn('orders', 'paid_at')) {
                $table->timestamp('paid_at')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {

            $columns = [
                'customer_name',
                'customer_email',
                'customer_phone',
                'shipping_address',
                'city',
                'state',
                'pincode',
                'gateway_order_id',
                'payment_response',
                'paid_at'
            ];

            foreach ($columns as $column) {
                if (Schema::hasColumn('orders', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};