<?php

namespace Database\Factories;

use App\Models\SiteSetting;
use Illuminate\Database\Eloquent\Factories\Factory;

class SiteSettingFactory extends Factory
{
    protected $model = SiteSetting::class;

    public function definition(): array
    {
        return [
            'site_name' => 'BeritaKita',
            'site_tagline' => 'Berita terkini, akurat, dan terpercaya',
            'ticker_text' => fake()->sentence(10),
            'ticker_enabled' => true,
            'location_text' => 'Jakarta, Indonesia',
            'footer_about' => fake()->paragraph(),
            'footer_address' => fake()->address(),
            'footer_phone' => fake()->phoneNumber(),
            'footer_email' => 'redaksi@beritakita.test',
            'social_facebook' => 'https://facebook.com/beritakita',
            'social_twitter' => 'https://twitter.com/beritakita',
            'social_instagram' => 'https://instagram.com/beritakita',
            'social_youtube' => 'https://youtube.com/@beritakita',
            'meta_title' => 'BeritaKita — Berita Terkini',
            'meta_description' => 'Portal berita independen dengan liputan terkini.',
            'google_analytics' => null,
        ];
    }
}