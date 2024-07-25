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
        $table->string('porte', 30)->nullable();
        $table->string('cnpj', 14)->unique();
        $table->string('email', 191)->nullable();
        $table->string('logo', 255)->nullable();
        $table->string('site', 200)->nullable();
        $table->char('country_code', 2)->nullable();
        $table->unsignedBigInteger('state_id')->nullable();
        $table->unsignedBigInteger('city_id')->nullable();
        $table->string('cep', 9)->nullable();
        $table->string('bairro', 80)->nullable();
        $table->string('logradouro', 191);
        $table->string('numero', 10)->nullable();
        $table->string('complemento', 191)->nullable();
        $table->string('ddd', 2)->nullable();
        $table->string('telefone', 20)->nullable();
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
