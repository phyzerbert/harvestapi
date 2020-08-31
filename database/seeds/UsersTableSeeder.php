<?php

use Illuminate\Database\Seeder;
use App\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name' => 'Admin',
            'email' => 'motiwildweb@gmail.com',
            'role' => 'admin',
            'password' => bcrypt('123456'),
        ]);
    }
}
