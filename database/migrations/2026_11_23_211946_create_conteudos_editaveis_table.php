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
        Schema::create('conteudos_editaveis', function (Blueprint $table) {
            $table->id();
            $table->string('chave')->unique();
            $table->string('situacao');
            $table->json('publicado')->nullable();
            $table->json('rascunho')->nullable();
            $table->foreignId('rascunho_editado_por')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('aprovacao_solicitada_por')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('aprovacao_solicitada_em')->nullable();
            $table->foreignId('publicado_por')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('publicado_em')->nullable();
            $table->timestamps();
        });

        Schema::create('versoes_conteudo', function (Blueprint $table) {
            $table->id();
            $table->foreignId('conteudo_editavel_id')->constrained('conteudos_editaveis')->cascadeOnDelete();
            $table->json('conteudo');
            $table->foreignId('publicado_por')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('publicado_em')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('versoes_conteudo');
        Schema::dropIfExists('conteudos_editaveis');
    }
};
