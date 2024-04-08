<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIsiFormTable extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('isi_form')) {
            Schema::create('isi_form', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('formulir_id');
                $table->foreign('formulir_id')->references('id')->on('formulir')->onDelete('cascade');
                $table->text('isi');
                $table->timestamps();
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('isi_form');
    }
}
