<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\BlogCategory;
use Illuminate\Support\Str;
use App\Models\ServiceCategory;
use App\Models\Blog;
use App\Models\Service;
use Faker\Factory as Faker;


use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\OptionType;
class DatabaseSeeder extends Seeder
{
    /** Pick one random gallery image from 1.jpg…10.jpg */
    protected function randomGallery(int $count = 4): array
    {
        $photos = [];
        while (count($photos) < $count) {
            $n = rand(1, 10) . '.jpg';
            if (! in_array($n, $photos)) {
                $photos[] = $n;
            }
        }
        return $photos;
    }

    public function run()
   {
    
   }
}