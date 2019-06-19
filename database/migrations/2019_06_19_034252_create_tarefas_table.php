<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTarefasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tarefas', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('quadro_id');
            $table->string('nome', 50);
            $table->enum('status', ['Por fazer', 'Fazendo', 'Feito'])->default('Por fazer');
            $table->text('descricao')->nullable();
            $table->text('anotacoes')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('quadro_id')
                ->references('id')
                ->on('quadros');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tarefas');
    }
}
