<?php

namespace Database\Seeders;

use App\Models\Missionary;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class MissionariesSeeder extends Seeder
{
    public function run(): void
    {
        Storage::put('/missionary/images/the-kinds.jpg', Storage::disk('local')->get('imgs/missionaries/the-kings.jpg'));

        Missionary::create(
            [
                'title' => 'The Kinds to Mexico',
                'image' => '/missionary/images/the-kinds.jpg',
                'disable_at' => null,
                'order' => 1,
            ]
        );
        Storage::put('/missionary/images/stoltzfus-family.jpg', Storage::disk('local')->get('/imgs/missionaries/stoltzfus-family.jpg'));

        Missionary::create(
            [
                'title' => 'The Stoltzfus Family',
                'image' => '/missionary/images/stoltzfus-family.jpg',
                'disable_at' => null,
                'order' => 2,
            ]
        );

        Storage::put('/missionary/images/MIAI.jpeg', Storage::disk('local')->get('/imgs/missionaries/MIAI.jpeg'));

        Missionary::create(
            [
                'title' => 'Missions In Action International (MIAI)',
                'image' => '/missionary/images/MIAI.jpeg',
                'disable_at' => null,
                'order' => 3,
            ]
        );

        Storage::put('/missionary/images/crossing-the-streets.jpeg', Storage::disk('local')->get('/imgs/missionaries/crossing-the-streets.jpeg'));

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
