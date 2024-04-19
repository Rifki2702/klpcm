<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKelengkapanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */

    public function up()
    {
        if (!Schema::hasTable('kelengkapan')) {
            Schema::create('kelengkapan', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('formulir_id');
                $table->unsignedBigInteger('isi_form_id');
                $table->unsignedBigInteger('analisis_id');
                $table->enum('kuantitatif', ['lengkap', 'tidak lengkap']);
                $table->enum('kualitatif', ['lengkap', 'tidak lengkap']);
                $table->timestamp('tglberkas')->nullable();
                $table->timestamp('tglcek')->nullable()->default(now());
                $table->timestamps();

                $table->foreign('formulir_id')->references('id')->on('formulir');
                $table->foreign('isi_form_id')->references('id')->on('isi_form');
                $table->foreign('analisis_id')->references('id')->on('analisis');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('kelengkapan');
    }
}
