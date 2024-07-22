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
        Schema::create('outlets', function (Blueprint $table) {
            $table->id();
          $table->string('name');
          $table->string('contact_name');
          $table->string('contact_email')->default('');
          $table->string('contact_photo_url')->default('');
          $table->string('contact_phone_number')->unique();
          $table->bigInteger('category_id')->nullable();
          $table->bigInteger('region_id')->nullable();
          $table->bigInteger('locality_id')->nullable();
          $table->bigInteger('classification_id')->nullable();
          $table->bigInteger('mapped_by_id')->nullable();
          $table->string('country')->default('Kenya');
          $table->double('latitude')->default(0.0);
          $table->double('longitude')->default(0.0);
          $table->json('photo_urls');
          $table->string('remarks')->default('');
          $table->json('search_keywords');
          $table->timestamp('active_timestamp')->default(DB::raw('CURRENT_TIMESTAMP'));
          $table->tinyInteger('active')->default(2)->comment('1=False, 2=True');
          $table->tinyInteger('verified')->default(2)->comment('1=False, 2=True');
          $table->timestamp('verified_timestamp')->default(DB::raw('CURRENT_TIMESTAMP'));
          $table->tinyInteger('draft')->default(2)->comment('1=False, 2=True');
          $table->timestamp('draft_timestamp')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('outlets');
    }
};
