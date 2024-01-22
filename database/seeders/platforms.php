<?php

namespace Database\Seeders;

use App\Models\platform as platform;
use Illuminate\Database\Seeder;

class platforms extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        platform::create([
            'id' => 1,
            'title' => 'PlayStation',
            'slug' => '/playstation',
            'icon_path' => 'public/icons/icon-play.svg',
            'icon_filename' => 'icon-play.svg',
        ]);

        platform::create([
            'id' => 2,
            'title' => 'PC',
            'slug' => '/pc',
            'icon_path' => 'public/icons/icon-pc.svg',
            'icon_filename' => 'icon-pc.svg',
        ]);

        platform::create([
            'id' => 3,
            'title' => 'Xbox',
            'slug' => '/xbox',
            'icon_path' => 'public/icons/icon-xbx.svg',
            'icon_filename' => 'icon-xbx.svg',
        ]);

        platform::create([
            'id' => 4,
            'title' => 'Switch',
            'slug' => '/switch',
            'icon_path' => 'public/icons/icon-swt.svg',
            'icon_filename' => 'icon-swt.svg',
        ]);

    }
}