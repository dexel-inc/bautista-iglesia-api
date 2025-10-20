<?php

namespace Database\Seeders;

use App\Models\Missionary;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class MissionariesSeeder extends Seeder
{
    public function run(): void
    {
        Missionary::create(
            [
                'title' => 'The Kinds to Mexico',
                'image' => '/missionary/images/the-kinds.jpg',
                'disable_at' => null,
                'order' => 1,
            ]
        );

        Missionary::create(
            [
                'title' => 'The Stoltzfus Family',
                'image' => '/missionary/images/stoltzfus-family.jpg',
                'disable_at' => null,
                'order' => 2,
            ]
        );

        Missionary::create(
            [
                'title' => 'Missions In Action International (MIAI)',
                'image' => '/missionary/images/MIAI.jpeg',
                'disable_at' => null,
                'order' => 3,
            ]
        );

        Missionary::create(
            [
                'title' => 'Key Family',
                'image' => '/missionary/images/crossing-the-streets.jpeg',
                'disable_at' => null,
                'order' => 4,
            ]
        );
    }
}
