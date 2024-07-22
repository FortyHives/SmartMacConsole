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
      Schema::create('regions', function (Blueprint $table) {
        $table->id();
        $table->double('latitude');
        $table->double('longitude');
        $table->double('proximity_radius');
        $table->string('country');
        $table->string('name')->unique();
        $table->json('search_keywords');
        $table->timestamp('timestamp');
        $table->timestamps();
      });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('regions');
    }
};
