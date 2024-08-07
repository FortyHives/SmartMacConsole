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
      // Drop the table if it exists
      Schema::dropIfExists('planograms');

        Schema::create('planograms', function (Blueprint $table) {
            $table->id();
          $table->string('name');
          $table->string('description')->default('');
          $table->string('primary_product_id')->default("");
          $table->json('comparison_products_id');
          $table->string('category_id')->default("");
          $table->string('photo_url')->default("");
          $table->timestamp('active_timestamp')->default(DB::raw('CURRENT_TIMESTAMP'));
          $table->tinyInteger('active')->default(2)->comment('1=False, 2=True');
          $table->timestamp('suspended_timestamp')->default(DB::raw('CURRENT_TIMESTAMP'));
          $table->tinyInteger('suspended')->default(1)->comment('1=False, 2=True');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('planograms');
    }
};
