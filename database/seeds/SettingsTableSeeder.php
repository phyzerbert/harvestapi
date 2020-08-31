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
            'account_id' => '1341550',
            'access_token' => '2407330.pt.E8ee9UROoS4cBSEBhBx8BmoghJCUp0nOvX5uKm0eW8ujvJ1i6ZRM9j4ThcPGF3Kt6UpwNLmYXZIOW5uDNqFHBg',
        ]);

        $owner_array = ['Moti', 'Ben', 'Avital', 'Shira', 'Mor'];
        foreach ($owner_array as $item) {
            Owner::create([
                'name' => $item,
            ]);
        }
    }
}
