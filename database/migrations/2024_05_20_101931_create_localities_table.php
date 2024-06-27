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
      Schema::create('localities', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('region_id');
        $table->unsignedBigInteger('mapped_by_id');
        $table->double('latitude');
        $table->double('longitude');
        $table->double('proximity_radius');
        $table->double('population');
        $table->double('attitude');
        $table->string('country');
        $table->string('name')->unique();
        $table->timestamp('verified_timestamp')->default(DB::raw('CURRENT_TIMESTAMP'));
        $table->boolean('verified')->default(2)->comment('1=False, 2=True');
        $table->json('search_keywords');
        $table->timestamp('timestamp')->default(DB::raw('CURRENT_TIMESTAMP'));
        $table->timestamps();
      });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('localities');
    }
};
