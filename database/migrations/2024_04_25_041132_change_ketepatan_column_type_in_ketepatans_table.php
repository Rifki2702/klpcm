<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeKetepatanColumnTypeInKetepatansTable extends Migration
{
    public function up()
    {
        Schema::table('ketepatan', function (Blueprint $table) {
            $table->boolean('ketepatan')->default(false)->change();
        });
    }

    public function down()
    {
        Schema::table('ketepatan', function (Blueprint $table) {
            $table->integer('ketepatan')->change();
        });
    }
}
