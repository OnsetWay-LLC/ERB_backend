<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('client_installations', function (Blueprint $table) {
            $table->id();

            $table->foreignId('license_request_id')
                ->constrained('license_requests')
                ->cascadeOnDelete();

            $table->string('database_name')->nullable();
            $table->string('server_host')->nullable();
            $table->string('server_port')->nullable();
            $table->string('database_username')->nullable();
            $table->text('database_password')->nullable();
            $table->string('backend_path')->nullable();
            $table->string('master_device_name')->nullable();

            $table->enum('installation_status', [
                'pending',
                'database_created',
                'initialized',
                'failed',
            ])->default('pending');

            $table->timestamp('installed_at')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('client_installations');
    }
};