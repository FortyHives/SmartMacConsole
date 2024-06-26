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
        Schema::create('agents', function (Blueprint $table) {
          $table->id();
          $table->json('name');
          $table->string('email')->unique();
          $table->string('photo_url')->default('');
          $table->string('phone_number')->unique();
          $table->string('id_number')->unique();
          $table->string('country')->default('Kenya');
          $table->string('role')->default(1)->comment('1=Mapping, 2=Sales, 3=Survey');
          $table->timestamp('email_verified_timestamp')->default(now());
          $table->tinyInteger('email_verified')->default(1)->comment('1=False, 2=True');
          $table->timestamp('active_timestamp')->default(now());
          $table->tinyInteger('active')->default(2)->comment('1=False, 2=True');
          $table->timestamp('suspended_timestamp')->default(now());
          $table->tinyInteger('suspended')->default(2)->comment('1=False, 2=True');
          $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('agents');
    }
};
