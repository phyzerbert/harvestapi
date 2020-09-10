<?php

use Illuminate\Database\Seeder;

use App\Models\Setting;
use App\Models\Owner;

class SettingsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Setting::create([
            'account_id' => '923363',
            'access_token' => '1583543.pt.A9d-BczVfNFKI9WLKmBkxXvTsTy9pzm3p36ebG1TUjdvnUHcukCRBbx-2_FcBsTMByRNcADdJByVZ_vvrgMMjA',
        ]);

        $owner_array = ['Moti', 'Ben', 'Avital', 'Shira', 'Mor'];
        foreach ($owner_array as $item) {
            Owner::create([
                'name' => $item,
            ]);
        }
    }
}
