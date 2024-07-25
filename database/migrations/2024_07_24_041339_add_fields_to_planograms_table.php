<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('planograms', function (Blueprint $table) {
          $table->string('photo_url')->default("");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('planograms', function (Blueprint $table) {
            // If needed, you can define the rollback steps here
            $table->dropColumn('photo_url');
        });
    }
};
