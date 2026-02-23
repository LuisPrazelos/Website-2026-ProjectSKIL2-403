<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Picture;
use App\Models\Dessert;
use Illuminate\Support\Facades\Storage;

class TablePictureSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {


        // Define image paths
        $imagePaths = [
            'table_pictures/Gato.png',
            'table_pictures/Rijstpap.png',
        ];

        // Seed table_pictures with image URLs
        foreach ($imagePaths as $index => $path) {
            Picture::create([
                'title' => $index === 0 ? 'Chocolate Cake Picture' : 'Vanilla Ice Cream Picture',
                'hash' => $path,
            ]);
        }
    }
}
