<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CoordinateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // get all agent roles
        $faker = \Faker\Factory::create();
        $agents = User::role('agent')->get();
        // update each agent with a random coordinate
        foreach ($agents as $key => $agent) {
            if ($key % 2 == 0) {
                $agent->coordinate()->updateOrCreate([
                    'latitude' => 0.0,
                    'longitude' => 0.0,
                ]);
            }
        }
    }
}
