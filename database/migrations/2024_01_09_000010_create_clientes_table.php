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
            $table->uuid('id')->primary();
            $table->text('nome')->nullable();
            $table->timestamp('data_cadastro')->nullable()->useCurrent();
            $table->json('tags_personalidade')->nullable();
            $table->date('data_nascimento')->nullable();
            $table->text('email')->nullable();
            $table->string('phone_1', 20)->nullable();
            $table->string('phone_2', 20)->nullable();
            $table->string('phone_3', 20)->nullable();

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