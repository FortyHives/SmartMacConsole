<?php

namespace Database\Seeders;

use App\Models\Agent;
use Illuminate\Database\Seeder;

class AgentsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Agent::create([
          'id' => 1,
          'name' => ['Test','','Agent'],
          'email' => 'agent@fortyhives.com',
          'phone_number' => '+254718505072',
          'id_number' => '0123456',
          'pin' => '',
          'pin_activated' => '1'
        ]);
    }
}
