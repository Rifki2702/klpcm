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
            $table->unsignedBigInteger('role_id');
            $table->foreign('role_id')->references('id')->on('roles');
        });
    }

    public function down()
    {
        Schema::dropIfExists('formulir');
    }
}