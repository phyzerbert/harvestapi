<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\Setting;

class Project extends Model
{
    protected $guarded = [];

    public function owner() {
        return $this->belongsTo(Owner::class);
    }

    public function getTrackedHours() {
        $setting = Setting::find(1);
        $curl = curl_init();
        curl_setopt_array($curl, array(
        CURLOPT_URL => "https://api.harvestapp.com/v2/time_entries?project_id=".$this->id,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_HTTPHEADER => array(
          "Harvest-Account-Id: ".$setting->account_id,
          "Authorization: Bearer ".$setting->access_token,
          "User-Agent: MyApp (motiwildweb@gmail.com)",
        ),
        ));

        $response = json_decode(curl_exec($curl), true);
        curl_close($curl);
        $collection = collect($response['time_entries']);
        return $collection->sum('hours');
    }
}
