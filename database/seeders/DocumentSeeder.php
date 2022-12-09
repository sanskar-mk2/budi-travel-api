<?php

namespace Database\Seeders;

use App\Models\Document;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DocumentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Document::create([
            'title' => 'Terms and Conditions',
            'content' => 'Document 1 content',
        ]);
        Document::create([
            'title' => 'Privacy Policy',
            'content' => 'Document 2 content',
        ]);
    }
}
