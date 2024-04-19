<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAnalisisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('analisis')) {
            Schema::create('analisis', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_id');
                $table->unsignedBigInteger('pasien_id');
                $table->date('tglberkas');
                $table->dateTime('tglcek');
                // Tambahkan kolom lain yang dibutuhkan untuk tabel analisis
                $table->timestamps();

                // Foreign key constraints
                $table->foreign('user_id')->references('id')->on('users');
                $table->foreign('pasien_id')->references('id')->on('pasiens');
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
        Schema::dropIfExists('analisis');
    }
}
