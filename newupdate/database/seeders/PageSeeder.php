<?php

namespace Database\Seeders;

use App\Models\Page;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PageSeeder extends Seeder
{
    public function run(): void
    {
        {
            DB::table('pages')->insert([
//                [
//                    'id' => 2,
//                    'name' => 'Services',
//                    'slug' => 'services',
//                    'template_name' => 'homely',
//                    'custom_link' => NULL,
//                    'page_title' => 'Services',
//                    'meta_title' => 'Services',
//                    'meta_keywords' => '["Services"]',
//                    'meta_description' => 'This is meta Services',
//                    'meta_image' => NULL,
//                    'meta_image_driver' => NULL,
//                    'breadcrumb_image' => NULL,
//                    'breadcrumb_image_driver' => 'local',
//                    'breadcrumb_status' => 0,
//                    'status' => 1,
//                    'type' => 1,
//                    'is_breadcrumb_img' => 1,
//                    'created_at' => '2025-02-26 16:55:22',
//                    'updated_at' => '2025-02-26 16:55:22'
//                ],
//                [
//                    'id' => 3,
//                    'name' => 'Trip Boards',
//                    'slug' => 'trip-boards',
//                    'template_name' => 'homely',
//                    'custom_link' => NULL,
//                    'page_title' => 'Trip Boards',
//                    'meta_title' => 'Trip Boards',
//                    'meta_keywords' => '["trip boards"]',
//                    'meta_description' => 'This is meta Trip Boards',
//                    'meta_image' => NULL,
//                    'meta_image_driver' => NULL,
//                    'breadcrumb_image' => NULL,
//                    'breadcrumb_image_driver' => 'local',
//                    'breadcrumb_status' => 0,
//                    'status' => 1,
//                    'type' => 1,
//                    'is_breadcrumb_img' => 1,
//                    'created_at' => '2025-02-26 17:15:22',
//                    'updated_at' => '2025-02-26 17:15:22'
//                ],
//                [
//                    'id' => 4,
//                    'name' => 'Add your home',
//                    'slug' => 'add-your-home',
//                    'template_name' => 'homely',
//                    'custom_link' => NULL,
//                    'page_title' => 'Add your home',
//                    'meta_title' => 'Add your home',
//                    'meta_keywords' => '["Add your home"]',
//                    'meta_description' => 'This is meta Add your home',
//                    'meta_image' => NULL,
//                    'meta_image_driver' => NULL,
//                    'breadcrumb_image' => NULL,
//                    'breadcrumb_image_driver' => 'local',
//                    'breadcrumb_status' => 0,
//                    'status' => 1,
//                    'type' => 1,
//                    'is_breadcrumb_img' => 1,
//                    'created_at' => '2025-02-26 17:25:22',
//                    'updated_at' => '2025-02-26 17:25:22'
//                ],
//                [
//                    'id' => 4,
//                    'name' => 'Become an affiliate',
//                    'slug' => 'become-an-affiliate',
//                    'template_name' => 'homely',
//                    'custom_link' => NULL,
//                    'page_title' => 'Become an affiliate',
//                    'meta_title' => 'Become an affiliate',
//                    'meta_keywords' => '["Become an affiliate"]',
//                    'meta_description' => 'This is meta Become an affiliate',
//                    'meta_image' => NULL,
//                    'meta_image_driver' => NULL,
//                    'breadcrumb_image' => NULL,
//                    'breadcrumb_image_driver' => 'local',
//                    'breadcrumb_status' => 0,
//                    'status' => 1,
//                    'type' => 1,
//                    'is_breadcrumb_img' => 1,
//                    'created_at' => '2025-02-26 17:25:22',
//                    'updated_at' => '2025-02-26 17:25:22'
//                ],
//                [
//                    'id' => 61,
//                    'name' => 'Pages',
//                    'slug' => 'pages',
//                    'home_name' => 'pages',
//                    'template_name' => 'homely',
//                    'custom_link' => NULL,
//                    'breadcrumb_image' => NULL,
//                    'breadcrumb_image_driver' => 'local',
//                    'breadcrumb_status' => 0,
//                    'status' => 1,
//                    'type' => 1,
//                    'is_breadcrumb_img' => 1,
//                    'created_at' => '2025-02-26 17:25:22',
//                    'updated_at' => '2025-02-26 17:25:22'
//                ],
                [
                    'id' => 62,
                    'name' => 'Homepages',
                    'slug' => 'homepages',
                    'home_name' => 'homepages',
                    'template_name' => 'homely',
                    'custom_link' => NULL,
                    'breadcrumb_image' => NULL,
                    'breadcrumb_image_driver' => 'local',
                    'breadcrumb_status' => 0,
                    'status' => 1,
                    'type' => 1,
                    'is_breadcrumb_img' => 1,
                    'created_at' => '2025-02-26 17:25:22',
                    'updated_at' => '2025-02-26 17:25:22'
                ]
            ]);
        }

    }
}
