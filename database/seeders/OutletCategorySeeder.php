<?php

namespace Database\Seeders;

use App\Models\Agent;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OutletCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
      DB::table('outlet_categories')->insert([
        [
          'title' => 'Kiosks',
          'description' => 'Description for a Kiosk outlet',
          'proximity_radius' => 10.5,
          'active_timestamp' => Carbon::now(),
          'active' => 2,
          'created_at' => Carbon::now(),
          'updated_at' => Carbon::now(),
        ],
        [
          'title' => 'Others',
          'description' => 'Description for Other outlet',
          'proximity_radius' => 15.0,
          'active_timestamp' => Carbon::now(),
          'active' => 2,
          'created_at' => Carbon::now(),
          'updated_at' => Carbon::now(),
        ],
        [
          'title' => 'Retailer',
          'description' => 'Description for retailer outlet',
          'proximity_radius' => 20.0,
          'active_timestamp' => Carbon::now(),
          'active' => 2,
          'created_at' => Carbon::now(),
          'updated_at' => Carbon::now(),
        ],
        [
          'title' => 'Liquor Store',
          'description' => 'Description for Liquor Store outlet',
          'proximity_radius' => 20.0,
          'active_timestamp' => Carbon::now(),
          'active' => 2,
          'created_at' => Carbon::now(),
          'updated_at' => Carbon::now(),
        ],
        [
          'title' => 'Cosmetic',
          'description' => 'Description for Cosmetic outlet',
          'proximity_radius' => 20.0,
          'active_timestamp' => Carbon::now(),
          'active' => 2,
          'created_at' => Carbon::now(),
          'updated_at' => Carbon::now(),
        ],
        [
          'title' => 'Supermarket',
          'description' => 'Description for Supermarket outlet',
          'proximity_radius' => 20.0,
          'active_timestamp' => Carbon::now(),
          'active' => 2,
          'created_at' => Carbon::now(),
          'updated_at' => Carbon::now(),
        ],
        [
          'title' => 'Minimarket',
          'description' => 'Description for Minimarket outlet',
          'proximity_radius' => 20.0,
          'active_timestamp' => Carbon::now(),
          'active' => 2,
          'created_at' => Carbon::now(),
          'updated_at' => Carbon::now(),
        ],
        [
          'title' => 'Pharmaceutical',
          'description' => 'Description for Pharmaceutical outlet',
          'proximity_radius' => 20.0,
          'active_timestamp' => Carbon::now(),
          'active' => 2,
          'created_at' => Carbon::now(),
          'updated_at' => Carbon::now(),
        ],
        [
          'title' => 'Automotive',
          'description' => 'Description for Automotive outlet',
          'proximity_radius' => 20.0,
          'active_timestamp' => Carbon::now(),
          'active' => 2,
          'created_at' => Carbon::now(),
          'updated_at' => Carbon::now(),
        ],
        [
          'title' => 'Bar/Club',
          'description' => 'Description for Bar/Club outlet',
          'proximity_radius' => 20.0,
          'active_timestamp' => Carbon::now(),
          'active' => 2,
          'created_at' => Carbon::now(),
          'updated_at' => Carbon::now(),
        ],
        [
          'title' => 'Wholesaler',
          'description' => 'Description for Wholesaler outlet',
          'proximity_radius' => 20.0,
          'active_timestamp' => Carbon::now(),
          'active' => 2,
          'created_at' => Carbon::now(),
          'updated_at' => Carbon::now(),
        ],
      ]);
    }
}
