<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('razao_social', 191);
            $table->string('porte', 30);
            $table->string('cnpj', 14)->unique();
            $table->string('email', 191)->unique();
            $table->string('logo', 255)->nullable();
            $table->string('site', 200)->nullable();
            $table->char('country_code', 2);
            $table->unsignedBigInteger('state_id');
            $table->unsignedBigInteger('city_id');
            $table->string('cep', 9);
            $table->string('bairro', 80);
            $table->string('logradouro', 191);
            $table->string('numero', 10);
            $table->string('complemento', 191)->nullable();
            $table->string('ddd', 2);
            $table->string('telefone', 20);
            $table->enum('situacao', ['0', '1'])->default('1');
            $table->text('atividade_principal')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('state_id')->references('id')->on('estados');
            $table->foreign('city_id')->references('id')->on('cidades');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('companies');
    }
};
