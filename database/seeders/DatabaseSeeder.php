<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $user = [
            [
                'username' => 'desknet_dev',
                'name' => 'desknet_dev',
                'email' => 'desknet@lifemedia.id',
                'password' => bcrypt('desknet_dev'),
                'level' => 0,
            ],
        ];

        foreach ($user as $key => $value) {
            User::create($value);
        }
    }
}
