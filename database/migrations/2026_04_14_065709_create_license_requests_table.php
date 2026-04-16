<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('license_requests', function (Blueprint $table) {
            $table->id();

            $table->string('company_name')->nullable();
            $table->string('owner_name');
            $table->string('username');
            $table->string('email')->unique();

            // إذا بدك تخزنيها مؤقتًا لازم تكون hashed
            $table->string('password')->nullable();

            $table->timestamp('email_verified_at')->nullable();

            $table->enum('status', [
                'pending',
                'email_verified',
                'activation_sent',
                'activated',
                'expired',
                'blocked',
            ])->default('pending');

            $table->timestamp('requested_at')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('license_requests');
    }
};