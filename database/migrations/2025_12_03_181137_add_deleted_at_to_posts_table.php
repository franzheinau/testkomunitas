<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        // Cek dulu, kalau BELUM ada kolom deleted_at baru tambah
        if (!Schema::hasColumn('posts', 'deleted_at')) {
            Schema::table('posts', function (Blueprint $table) {
                $table->softDeletes();
            });
        }
    }

    public function down()
    {
        // Pas rollback, cek dulu kalau kolomnya ada baru di-drop
        if (Schema::hasColumn('posts', 'deleted_at')) {
            Schema::table('posts', function (Blueprint $table) {
                $table->dropSoftDeletes(); // atau $table->dropColumn('deleted_at');
            });
        }
    }
};
