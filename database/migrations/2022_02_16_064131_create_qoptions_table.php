<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQoptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('qoptions', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('option_name','64')->nullable();
            $table->longText('option_value')->nullable();
            $table->text('obs')->nullable();
            $table->text('config')->nullable();
            $table->string('painel','2')->nullable();
            $table->enum('excluido',['n','s']);
            $table->text('reg_excluido')->nullable();
            $table->enum('deletado',['n','s']);
            $table->text('reg_deletado')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('qoptions');
    }
}
