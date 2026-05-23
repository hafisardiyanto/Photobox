<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FrameSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\Frame::create([
            'name' => 'Minimalist White',
            'template_path' => 'templates/white.png',
            'orientation' => 'vertical',
        ]);

        \App\Models\Frame::create([
            'name' => 'Dark Mode',
            'template_path' => 'templates/dark.png',
            'orientation' => 'vertical',
        ]);

        \App\Models\Frame::create([
            'name' => 'Pastel Pink',
            'template_path' => 'templates/pink.png',
            'orientation' => 'vertical',
        ]);

        \App\Models\Frame::create([
            'name' => 'Valentine Love',
            'template_path' => 'templates/valentine.png',
            'orientation' => 'vertical',
        ]);

        \App\Models\Frame::create([
            'name' => 'Sunset Glow',
            'template_path' => 'templates/sunset.png',
            'orientation' => 'vertical',
        ]);

        \App\Models\Frame::create([
            'name' => 'Cyberpunk Neon',
            'template_path' => 'templates/cyberpunk.png',
            'orientation' => 'vertical',
        ]);
    }
}
