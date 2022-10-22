<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        Role::create(['name' => 'user']);
        Role::create(['name' => 'agent']);
        Role::create(['name' => 'admin']);
        Role::create(['name' => 'staff']);

        User::factory()->create([
            'name' => 'Adam Inn',
            'email' => 'admin@example.com'
        ])->assignRole('admin');
    }
}
