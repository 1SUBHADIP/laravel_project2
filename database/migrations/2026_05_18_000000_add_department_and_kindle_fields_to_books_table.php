<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('books', function (Blueprint $table) {
            $table->foreignId('department_id')->nullable()->after('category_id')->constrained('departments')->nullOnDelete();
            $table->boolean('has_kindle_version')->default(false)->after('department_id');
            $table->string('kindle_link')->nullable()->after('has_kindle_version');
        });
    }

    public function down(): void
    {
        Schema::table('books', function (Blueprint $table) {
            $table->dropForeign(['department_id']);
            $table->dropColumn(['department_id', 'has_kindle_version', 'kindle_link']);
        });
    }
};
