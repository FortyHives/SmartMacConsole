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
        Schema::table('agents', function (Blueprint $table) {
          $table->string('pin')->nullable();
          $table->boolean('pin_activated')->default(1)->comment('1=False, 2=True');
          $table->timestamp('pin_activated_timestamp')->default(DB::raw('CURRENT_TIMESTAMP'));
          $table->string('current_locality_id')->nullable();
          $table->string('current_region_id')->nullable();
          $table->string('selected_locality_id')->nullable();
          $table->string('selected_region_id')->nullable();
          $table->string('selected_category_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('agents', function (Blueprint $table) {
            // If needed, you can define the rollback steps here
            $table->dropColumn('pin');
            $table->dropColumn('pin_activated');
            $table->dropColumn('pin_activated_timestamp');
          $table->dropColumn('current_locality_id');
          $table->dropColumn('current_region_id');
          $table->dropColumn('selected_locality_id');
          $table->dropColumn('selected_region_id');
          $table->dropColumn('selected_category_id');
        });
    }
};
