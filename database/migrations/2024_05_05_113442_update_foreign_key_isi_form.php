<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateForeignKeyIsiForm extends Migration
{
    public function up()
    {
        Schema::table('isi_form', function (Blueprint $table) {
            $table->dropForeign(['formulir_id']);
            $table->foreign('formulir_id')->references('id')->on('formulir')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('isi_form', function (Blueprint $table) {
            $table->dropForeign(['formulir_id']);
            $table->foreign('formulir_id')->references('id')->on('formulir');
        });
    }
}
