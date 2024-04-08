<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAnalisisKualitatifTable extends Migration
{
    public function up()
    {
        Schema::create('analisis_kualitatif', function (Blueprint $table) {
            $table->id();
            $table->text('isi');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('analisis_kualitatif');
    }
}
