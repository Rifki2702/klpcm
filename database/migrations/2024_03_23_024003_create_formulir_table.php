<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFormulirTable extends Migration
{
    public function up()
    {
        Schema::create('formulir', function (Blueprint $table) {
            $table->id();
            $table->string('nama_formulir');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('formulir');
    }
}