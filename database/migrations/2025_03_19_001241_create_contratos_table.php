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
            $table->string('cliente', 100);
            $table->foreignIdFor(\App\Models\Clientes::class, 'cliente_id')->nullable()->cascadeOnUpdate()->nullOnDelete();
            $table->decimal('pmt', 10, 2);
            $table->integer('prazo');
            $table->decimal('taxa_original', 5, 2);
            $table->decimal('saldo_devedor', 15, 2);
            $table->decimal('producao', 10, 2);
            $table->decimal('troco_cli', 10, 2)->nullable();
            $table->string('pos_venda', 50)->nullable();
            $table->string('vendedor', 100);
            $table->date('data_inclusao');
            $table->timestamps();
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