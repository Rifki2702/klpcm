<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class AddDokterIdToAnalisisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn('analisis', 'dokter_id')) {
            Schema::table('analisis', function (Blueprint $table) {
                $table->unsignedBigInteger('dokter_id')->nullable()->after('id');

                $table->foreign('dokter_id')
                    ->references('id')->on('dokters')
                    ->onDelete('cascade');
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
        Schema::table('analisis', function (Blueprint $table) {
            if (Schema::hasColumn('analisis', 'dokter_id')) {
                $table->dropForeign(['dokter_id']);
                $table->dropColumn('dokter_id');
            }
        });
    }
}
