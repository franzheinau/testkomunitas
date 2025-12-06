<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('posts', function (Blueprint $table) {
            if (! Schema::hasColumn('posts', 'image')) {
                $table->string('image')->nullable()->after('title');
            }
            if (! Schema::hasColumn('posts', 'caption')) {
                // optional: jika mau simpan caption terpisah dari body
                $table->text('caption')->nullable()->after('image');
            }
        });
    }

    public function down()
    {
        Schema::table('posts', function (Blueprint $table) {
            if (Schema::hasColumn('posts', 'image')) {
                $table->dropColumn('image');
            }
            if (Schema::hasColumn('posts', 'caption')) {
                $table->dropColumn('caption');
            }
        });
    }
};
