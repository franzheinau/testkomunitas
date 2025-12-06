<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Jika tabel posts belum ada, buat dari awal (termasuk kolom standar)
        if (! Schema::hasTable('posts')) {
            Schema::create('posts', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->string('title');
                $table->text('body');
                $table->timestamp('published_at')->nullable();
                $table->softDeletes();
                $table->timestamps();
            });

            return;
        }

        // Kalau tabel sudah ada, tambahkan kolom yang hilang (cek dulu pakai hasColumn)
        Schema::table('posts', function (Blueprint $table) {
            if (! Schema::hasColumn('posts', 'user_id')) {
                // jika database tidak mendukung constrained pada DB yang sudah berisi data, kita tambahkan kolom dulu
                $table->unsignedBigInteger('user_id')->nullable()->after('id');
                // tambahkan FK hanya jika kamu yakin tabel users ada dan ingin constraint:
                // $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            }

            if (! Schema::hasColumn('posts', 'title')) {
                $table->string('title')->nullable()->after('user_id');
            }

            if (! Schema::hasColumn('posts', 'body')) {
                $table->text('body')->nullable()->after('title');
            }

            if (! Schema::hasColumn('posts', 'published_at')) {
                $table->timestamp('published_at')->nullable()->after('body');
            }

            if (! Schema::hasColumn('posts', 'deleted_at')) {
                $table->softDeletes();
            }

            if (! Schema::hasColumn('posts', 'created_at') || ! Schema::hasColumn('posts', 'updated_at')) {
                $table->timestamps();
            }
        });
    }

    public function down()
    {
        // rollback: coba hapus kolom yang kita tambahkan — berhati-hati di production
        if (! Schema::hasTable('posts')) {
            return;
        }

        Schema::table('posts', function (Blueprint $table) {
            if (Schema::hasColumn('posts', 'published_at')) {
                $table->dropColumn('published_at');
            }

            if (Schema::hasColumn('posts', 'body')) {
                $table->dropColumn('body');
            }

            if (Schema::hasColumn('posts', 'title')) {
                $table->dropColumn('title');
            }

            // NOTE: jangan drop user_id/softDeletes/timestamps kalau ada data penting
            // jika yakin, uncomment di bawah
            // if (Schema::hasColumn('posts', 'user_id')) { $table->dropColumn('user_id'); }
            // if (Schema::hasColumn('posts', 'deleted_at')) { $table->dropSoftDeletes(); }
            // if (Schema::hasColumn('posts', 'created_at')) { $table->dropTimestamps(); }
        });
    }
};
