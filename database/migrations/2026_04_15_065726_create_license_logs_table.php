<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('license_logs', function (Blueprint $table) {
            $table->id();

            $table->foreignId('license_id')
                ->constrained('licenses')
                ->cascadeOnDelete();

            $table->enum('action', [
                'generated',
                'sent',
                'verified',
                'expired',
                'blocked',
                'cancelled',
            ]);

            $table->text('note')->nullable();

            $table->foreignId('created_by')
                ->nullable()
                ->constrained('project_admins')
                ->nullOnDelete();

            $table->timestamp('created_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('license_logs');
    }
};