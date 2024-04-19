<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('kelengkapan', function (Blueprint $table) {
            if (Schema::hasColumn('kelengkapan', 'kualitatif')) {
                $table->dropColumn('kualitatif'); // Hanya menghapus kolom jika kolom tersebut ada
            }
            $table->boolean('kuantitatif')->change(); // Mengubah kolom kuantitatif menjadi boolean
        });
    }

    public function down(): void
    {
        Schema::table('kelengkapan', function (Blueprint $table) {
            if (!Schema::hasColumn('kelengkapan', 'kualitatif')) {
                $table->enum('kualitatif', ['lengkap', 'tidak lengkap'])->after('analisis_id'); // Menambahkan kembali kolom kualitatif jika tidak ada
            }
            $table->enum('kuantitatif', ['lengkap', 'tidak lengkap'])->change(); // Mengubah kembali kolom kuantitatif menjadi enum
        });
    }
};
