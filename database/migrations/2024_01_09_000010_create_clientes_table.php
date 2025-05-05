<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clientes', function (Blueprint $table) {
            $table->id();
            $table->string('tipo', 45);
            $table->string('nome_fantasia', 200);
            $table->string('cpf', 45)->nullable();
            $table->string('cnpj', 45)->nullable();
            $table->string('razao', 200);
            $table->foreignIdFor(\App\Models\Estados::class, 'estado_id')->nullable()->cascadeOnUpdate()->nullOnDelete();
            $table->foreignIdFor(\App\Models\Cidades::class, 'cidade_id')->nullable()->cascadeOnUpdate()->nullOnDelete();
            $table->string('cep', 45)->nullable();
            $table->string('endereco', 200)->nullable();
            $table->string('bairro', 200)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('clientes');
    }
};