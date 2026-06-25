<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->enum('type', ['deposit', 'withdrawal', 'roi', 'commission', 'bonus']);
            $table->decimal('amount', 16, 2);
            $table->enum('wallet_type', ['deposit_balance', 'roi_balance', 'referral_balance', 'bonus_balance'])->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected', 'completed'])->default('completed');
            $table->string('description')->nullable();
            $table->string('reference_id')->nullable(); // Can link to deposits/withdrawals/investments
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
