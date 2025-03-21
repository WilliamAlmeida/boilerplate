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
        Schema::create('financiamentos', function (Blueprint $table) {
            $table->id();
            $table->string('telefone', 20);
            $table->string('banco_perfil', 50);
            $table->string('produto', 50);
            $table->decimal('tabela', 10, 2);
            $table->string('status', 20);
            $table->string('cpf', 14);
            $table->string('cliente', 100);
            $table->foreignIdFor(\App\Models\Clientes::class, 'cliente_id')->nullable()->cascadeOnUpdate()->nullOnDelete();
            $table->decimal('pmt', 10, 2);
            $table->decimal('financiado', 15, 2);
            $table->decimal('producao', 10, 2);
            $table->string('vendedor', 100);
            $table->foreignIdFor(\App\Models\Vendedores::class, 'vendedor_id')->nullable()->cascadeOnUpdate()->nullOnDelete();
            $table->date('data');
            $table->text('obs')->nullable();
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
        Schema::dropIfExists('financiamentos');
    }
};