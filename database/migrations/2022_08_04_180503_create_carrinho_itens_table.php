<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCarrinhoItensTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('carrinho_itens', function (Blueprint $table) {
            $table->id();
            $table->decimal('quantidade')->nullable();
            $table->decimal('preco')->nullable();
            $table->string('tp_desconto')->nullable();
            $table->decimal('qtd_desconto')->nullable();
            $table->decimal('valor_desconto')->nullable();
            $table->decimal('valor')->nullable();
            $table->integer('estoque_id_alltech');
            $table->unsignedBigInteger('carrinho_id');
            
            $table->timestamps();
            
            $table->foreign('carrinho_id')->references('id')->on('carrinhos')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('carrinho_itens', function (Blueprint $table) {
            $table->dropForeign('carrinho_itens_carrinho_id_foreign');

            $table->dropColumn('carrinho_id');
        });
        Schema::dropIfExists('carrinho_itens');
    }
}
