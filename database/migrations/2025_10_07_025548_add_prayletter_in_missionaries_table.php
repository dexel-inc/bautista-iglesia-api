<?php

use App\Constants\TypesPrayletter;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('missionaries', function (Blueprint $table) {
            $table->enum('type', TypesPrayletter::values())->nullable();
            $table->string('url')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('missionaries', function (Blueprint $table) {
            $table->dropColumn(['type', 'url']);
        });
    }
};
