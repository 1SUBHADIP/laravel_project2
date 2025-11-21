<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('loans', function (Blueprint $table) {
            $table->decimal('daily_fine_rate', 8, 2)->default(1.00)->after('fine_amount'); // 1 rupee per day
            $table->integer('overdue_days')->default(0)->after('daily_fine_rate');
            $table->decimal('total_fine', 8, 2)->default(0.00)->after('overdue_days');
            $table->boolean('fine_paid')->default(false)->after('total_fine');
            $table->timestamp('fine_calculated_at')->nullable()->after('fine_paid');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('loans', function (Blueprint $table) {
            $table->dropColumn(['daily_fine_rate', 'overdue_days', 'total_fine', 'fine_paid', 'fine_calculated_at']);
        });
    }
};
