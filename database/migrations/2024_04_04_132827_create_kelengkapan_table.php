<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
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
                $table->unsignedBigInteger('user_id');
                $table->unsignedBigInteger('pasien_id');
                $table->enum('kualitatif', ['lengkap', 'tidak lengkap']);
                $table->enum('kuantitatif', ['lengkap', 'tidak lengkap']);
                $table->timestamp('tglberkas')->nullable();
                $table->timestamp('tglcek')->nullable()->default(DB::raw('CURRENT_TIMESTAMP'));
                $table->timestamps();

                $table->foreign('formulir_id')->references('id')->on('formulirs')->onDelete('cascade');
                $table->foreign('isi_form_id')->references('id')->on('isi_forms');
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
        Schema::dropIfExists('kelengkapan');
    }
}
