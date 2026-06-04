<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('addresses', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable()->after('id');
            $table->string('full_name')->nullable();
            $table->string('phone')->nullable();
            $table->text('address_line_1')->nullable();
            $table->text('address_line_2')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('pincode')->nullable();
            $table->string('country')->default('India');
            $table->boolean('is_default')->default(false);
        });
    }

    public function down(): void
    {
        Schema::table('addresses', function (Blueprint $table) {
            $table->dropColumn([
                'user_id',
                'full_name',
                'phone',
                'address_line_1',
                'address_line_2',
                'city',
                'state',
                'pincode',
                'country',
                'is_default',
            ]);
        });
    }
};