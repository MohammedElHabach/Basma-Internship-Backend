<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use App\Models\News;

class NewsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        News::create([
            'title' => 'Sample News 1',
            'text' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.',
            'pic' => $this->copyImage('news1.jpg'),
            'category_id' => 6, 
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        News::create([
            'title' => 'Sample News 2',
            'text' => 'Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.',
            'pic' => $this->copyImage('news2.webp'),
            'category_id' => 7, 
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        News::create([
            'title' => 'Sample News 3',
            'text' => 'Sed tro eiusmod tempor incididunt ut labore et dolore magna aliqua.',
            'pic' => $this->copyImage('news3.webp'),
            'category_id' => 8, 
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    protected function copyImage($imageName)
    {
        $sourcePath = public_path('seeder_images/' . $imageName);
        $destinationPath = 'news_images/' . $imageName;

        Storage::putFileAs('public', $sourcePath, $destinationPath);

        return $destinationPath;
    }
}
