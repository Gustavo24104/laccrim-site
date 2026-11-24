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
        Schema::table('convites', function (Blueprint $table) {
            $table->foreignId('perfil_id')->nullable()->after('email')->constrained('perfis')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('convites', function (Blueprint $table) {
            $table->dropConstrainedForeignId('perfil_id');
        });
    }
};
