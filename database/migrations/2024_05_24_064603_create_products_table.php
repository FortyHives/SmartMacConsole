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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
          $table->string('name');
          $table->string('description')->default('');
          $table->json('photo_urls');
          $table->json('measurements');
          $table->json('search_keywords');
          $table->string('measurement')->default(1)->comment('1=Size, 2=Units, 3=Weight');
          $table->string('brand');
          $table->string('classification');
          $table->string('category');
          $table->timestamp('active_timestamp')->default(now());
          $table->tinyInteger('active')->default(2)->comment('1=False, 2=True');
          $table->timestamp('draft_timestamp')->default(now());
          $table->tinyInteger('draft')->default(2)->comment('1=False, 2=True');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
