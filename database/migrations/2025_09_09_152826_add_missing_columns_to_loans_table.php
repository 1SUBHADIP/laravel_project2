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
            $table->date('return_date')->nullable();
            $table->enum('status', ['Active', 'Returned', 'Overdue'])->default('Active');
            $table->decimal('fine_amount', 8, 2)->default(0.00);
            $table->text('notes')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('loans', function (Blueprint $table) {
            $table->dropColumn(['return_date', 'status', 'fine_amount', 'notes']);
        });
    }
};
