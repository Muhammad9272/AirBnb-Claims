<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SurveyOption;

class SurveyOptionSeeder extends Seeder
{
    public function run()
    {
        $options = [
            'Google Search',
            'Facebook',
            'Instagram',
            'Twitter',
            'LinkedIn',
            'Friend or Family',
            'Online Advertisement',
            'Blog or Article',
            'YouTube',
            'Other'
        ];

        foreach ($options as $text) {
            SurveyOption::create(['option_text' => $text]);
        }
    }
}
