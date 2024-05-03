<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKetepatanTable extends Migration
{
    public function up()
    {
        Schema::create('ketepatan', function (Blueprint $table) {
            $table->id();
            $table->string('ketepatan');
            $table->unsignedBigInteger('kualitatif_id');
            $table->unsignedBigInteger('analisis_id');
            $table->timestamps();

            $table->foreign('kualitatif_id')->references('id')->on('kualitatif')->onDelete('cascade');
            $table->foreign('analisis_id')->references('id')->on('analisis')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('ketepatan');
    }
}
