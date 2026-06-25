<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('investments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('plan_id')->constrained()->cascadeOnDelete();
            $table->decimal('amount', 16, 2);
            $table->decimal('total_earned', 16, 2)->default(0);
            $table->decimal('daily_roi_percent', 5, 2);
            $table->enum('status', ['active', 'completed', 'cancelled'])->default('active');
            $table->timestamp('last_roi_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('investments');
    }
};
