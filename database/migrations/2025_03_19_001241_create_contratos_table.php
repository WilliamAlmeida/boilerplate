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
        Schema::create('contratos', function (Blueprint $table) {
            $table->id();
            $table->string('cpf', 14);
            $table->string('cliente', 100)->nullable();
            $table->foreignIdFor(\App\Models\Clientes::class, 'cliente_id')->nullable()->cascadeOnUpdate()->nullOnDelete();
            $table->decimal('pmt', 10, 2)->nullable();
            $table->integer('prazo')->nullable();
            $table->decimal('taxa_original', 5, 2)->nullable();
            $table->decimal('saldo_devedor', 15, 2)->nullable();
            $table->decimal('producao', 10, 2)->nullable();
            $table->decimal('troco_cli', 10, 2)->nullable();
            $table->string('pos_venda', 50)->nullable();
            $table->string('vendedor', 100)->nullable();
            $table->foreignIdFor(\App\Models\Vendedores::class, 'vendedor_id')->nullable()->cascadeOnUpdate()->nullOnDelete();
            $table->date('data_inclusao')->nullable();
            $table->string('telefone', 20)->nullable();
            $table->string('banco_perfil', 50)->nullable();
            $table->string('produto', 50)->nullable();
            $table->decimal('tabela', 10, 2)->nullable();
            $table->string('status', 40)->nullable();
            $table->decimal('financiado', 15, 2)->nullable();
            $table->text('obs_1')->nullable();
            $table->text('obs_2')->nullable();
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
        Schema::dropIfExists('contratos');
    }
};