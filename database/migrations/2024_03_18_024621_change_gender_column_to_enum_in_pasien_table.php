<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeGenderColumnToEnumInpasienTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pasien', function (Blueprint $table) {
            $table->enum('gender', ['Laki-laki', 'Perempuan'])->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pasien', function (Blueprint $table) {
            $table->string('gender')->change();
        });
    }
}

