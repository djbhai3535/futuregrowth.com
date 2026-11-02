<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('deposits', function (Blueprint $table) {
            // Network chosen by user: trc20 or bep20
            $table->enum('network', ['trc20', 'bep20'])->default('trc20')->after('txid');
            // Idempotency guard: ensures the wallet is credited exactly once
            $table->boolean('credited')->default(false)->after('network');
        });
    }

    public function down(): void
    {
        Schema::table('deposits', function (Blueprint $table) {
            $table->dropColumn(['network', 'credited']);
        });
    }
};
