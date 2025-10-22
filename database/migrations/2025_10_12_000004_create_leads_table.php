<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('leads', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('ip_address')->nullable();
            $table->text('user_agent')->nullable();
            $table->boolean('is_registered')->default(false)->nullable();
            $table->foreignId('registered_user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->boolean('discount_code_used')->default(false)->nullable();
            $table->enum('status', ['new', 'contacted', 'converted'])->default('new')->nullable();
            $table->timestamp('created_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('leads');
    }
};
