<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateListasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('listas', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('quadro_id');
            $table->string('nome', 50);
            $table->text('descricao')->nullable();
            $table->integer('minutos_pomodoro')->default(25);
            $table->integer('short_timebreak')->default(5);
            $table->integer('long_timebreak')->default(10);
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
        Schema::dropIfExists('listas');
    }
}
