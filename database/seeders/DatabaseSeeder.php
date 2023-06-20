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
        $password = substr(md5(rand()), 0, 7);

        $user = [
            [
                'username' => 'JP002167',
                'password' => bcrypt($password),
                'password_txt' => ($password),
                'level' => 0,
            ],
        ];

        foreach ($user as $key => $value) {
            User::create($value);
        }
    }
}
