<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $cats = [
            "Business",
            "Leisure",
            "Entertainment",
            "Environment",
            "Adventure",
            "Photographer",
            "Employment",
            "History, Museums & Arts",
            "Place to Eat",
            "Shopping",
            "Sports",
            "Fitness & Spa",
        ];

        foreach ($cats as $cat) {
            \App\Models\Category::create([
                'name' => $cat,
            ]);
        }
    }
}
