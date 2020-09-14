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
            'name' => 'Moti',
            'email' => 'moti@wildweb.co.il',
            'role' => 'admin',
            'password' => bcrypt('WildBoard@2020'),
        ]);
    }
}
