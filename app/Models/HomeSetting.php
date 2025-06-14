<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HomeSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'hero_badge_text',
        'hero_heading',
        'hero_highlight',
        'hero_description',
        'hero_button_text',
        'hero_button_link',
        'hero_image',
        'featured_title',
        'featured_subtitle',
        'category_badge',
        'category_title',
        'category_subtitle',
        'latest_badge',
        'latest_title',
        'latest_subtitle',
        'show_featured',
        'show_categories',
        'show_latest',
        'show_services',
        'show_blog'
    ];

    protected $casts = [
        'show_featured' => 'boolean',
        'show_categories' => 'boolean',
        'show_latest' => 'boolean',
        'show_services' => 'boolean',
        'show_blog' => 'boolean'
    ];
}
