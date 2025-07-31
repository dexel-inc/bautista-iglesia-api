<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('missionaries', function (Blueprint $table) {
            $table->string('contact_email', 50)->nullable();
            $table->string('contact_name', 50)->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('missionaries', function (Blueprint $table) {
            $table->dropColumn(['contact_email', 'contact_name']);
        });
    }
};
