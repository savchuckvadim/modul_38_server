<?php

namespace Database\Seeders;

use App\Models\Followers;
use Illuminate\Database\Seeder;

class FollowersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Followers::factory()
        ->count(20)
        ->create();
    }
}
