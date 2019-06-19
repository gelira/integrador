<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateListaTarefaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lista_tarefa', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('lista_id');
            $table->unsignedBigInteger('tarefa_id');
            $table->timestamps();

            $table->foreign('lista_id')
                ->references('id')
                ->on('listas');
            $table->foreign('tarefa_id')
                ->references('id')
                ->on('tarefas');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('lista_tarefa');
    }
}
