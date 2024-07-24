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
        Schema::table('companies', function (Blueprint $table) {
           
            $table->string('porte', 30)->nullable()->change();
            $table->char('country_code', 2)->nullable()->change();
            $table->string('email', 191)->nullable()->change();
            $table->unsignedBigInteger('state_id')->nullable()->change();
            $table->unsignedBigInteger('city_id')->nullable()->change();
            $table->string('cep', 9)->nullable()->change();
            $table->string('bairro', 80)->nullable()->change();
            $table->string('logradouro', 191)->nullable()->change();
            $table->string('numero', 10)->nullable()->change();
            $table->string('ddd', 2)->nullable()->change();
            $table->string('telefone', 20)->nullable()->change();;
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('companies', function ($table) {
            $table->dropColumn('active');
        });
    }
};
