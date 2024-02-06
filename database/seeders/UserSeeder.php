<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'name' => 'Administrator',
                'username' => 'admin',
                'email' => 'admin@localhost',
                'password' => 'admin@localhost',
                'level_id' => 2
            ],
            [
                'name' => 'Bendahara',
                'username' => 'bendahara',
                'email' => 'bendahara@localhost',
                'password' => 'bendahara@localhost',
                'level_id' => 3
            ],
        ];

        foreach($users as $user) {
            User::create($user);
        }

        // User::factory(62)->create();
    }
}
