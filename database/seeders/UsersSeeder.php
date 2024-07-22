<?php

namespace Database\Seeders;

use App\Models\Agent;
use App\Models\User;
use Illuminate\Database\Seeder;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
      //User::factory(10)->create();

      User::factory()->create([
        'name' => 'Developer',
        'email' => 'developer@fortyhives.com',
      ]);
    }
}
