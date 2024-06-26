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
        Schema::create('outlet_categories', function (Blueprint $table) {
            $table->id();
          $table->string('title')->unique();
          $table->string('description')->unique();
          $table->double('proximity_radius');
          $table->timestamp('active_timestamp')->default(now());
          $table->tinyInteger('active')->default(2)->comment('1=False, 2=True');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('outlet_categories');
    }
};
