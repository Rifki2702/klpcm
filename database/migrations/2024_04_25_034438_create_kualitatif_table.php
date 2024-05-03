<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKualitatifTable extends Migration
{
    public function up()
    {
        Schema::create('kualitatif', function (Blueprint $table) {
            $table->id();
            $table->string('isi');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('kualitatif');
    }
}
