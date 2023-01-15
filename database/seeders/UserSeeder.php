<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // create 50 users and 50 agents
        \App\Models\User::factory()->count(100)->create()->each(function ($user, $key) {
            if ($key % 2 == 0) {
                $user->assignRole('user');
            } else {
                $user->assignRole('agent');
            }
        });
    }
}
