<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('licenses', function (Blueprint $table) {
            $table->id();

            $table->foreignId('license_request_id')
                ->constrained('license_requests')
                ->cascadeOnDelete();

           $table->foreignId('client_installation_id')
    ->nullable()
    ->constrained('client_installations');

            $table->enum('license_type', ['initial', 'renewal'])
                ->default('initial');

           $table->foreignId('parent_license_id')
    ->nullable()
    ->constrained('licenses');

            $table->string('activation_token_hash');
            $table->string('activation_token_hint', 3)->nullable();

            $table->enum('duration_type', [
                'fourteen_days',
                'one_year',
            ]);

            $table->timestamp('starts_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->timestamp('sent_at')->nullable();
            $table->timestamp('activated_at')->nullable();
            $table->timestamp('token_expires_at')->nullable();
            $table->timestamp('used_at')->nullable();

            $table->enum('status', [
                'generated',
                'sent',
                'active',
                'expired',
                'cancelled',
            ])->default('generated');

            $table->foreignId('generated_by')
                ->nullable()
                ->constrained('project_admins')
                ->nullOnDelete();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('licenses');
    }
};