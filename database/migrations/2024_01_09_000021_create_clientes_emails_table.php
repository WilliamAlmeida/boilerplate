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
        Schema::create('clientes_emails', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cliente_id')->references('id')->on('clientes')->cascadeOnUpdate()->cascadeOnDelete();
            $table->string('tipo', 10)->nullable();
            $table->string('email', 150);
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
        Schema::dropIfExists('clientes_emails');
    }
};