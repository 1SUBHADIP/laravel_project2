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
        Schema::table('members', function (Blueprint $table) {
            $table->string('address')->nullable();
            $table->enum('membership_type', ['Standard', 'Premium', 'Student'])->default('Standard');
            $table->date('membership_date')->default(now()->toDateString());
            $table->enum('status', ['Active', 'Inactive', 'Suspended'])->default('Active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('members', function (Blueprint $table) {
            //
        });
    }
};
