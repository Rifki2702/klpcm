<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('kelengkapan', function (Blueprint $table) {
            $table->dropForeign(['isi_form_id']);

            $table->foreign('isi_form_id')
                ->references('id')
                ->on('isi_form')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kelengkapan', function (Blueprint $table) {
            //
        });
    }
};
