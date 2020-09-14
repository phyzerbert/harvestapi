<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Setting;
use App\Models\Project;

use Auth;
use Carbon\Carbon;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        config(['site.page' => 'home']);
        ini_set('max_execution_time', '0');
        $this->loadProjects();
        $project_count = Project::where('is_active', 1)->where('is_hidden', '!=', 1)->count();
        $client_count = $this->getClientCount();
        $hours_tracked = $this->getHoursTracked();
        // $billable = Project::where('tracked', '>', 0)->avg('billable');
        $billable = $this->getBillable();
        $data = Project::where('is_hidden', 0)->where('is_active', 1)->get();
        return view('home', compact('data', 'project_count', 'client_count', 'hours_tracked', 'billable'));
    }

    public function loadProjects() {
        $setting = Setting::find(1);
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.harvestapp.com/v2/projects",
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

        // dd($response);

        $projects = $response['projects']; 
        foreach ($projects as $item) {
            $project = Project::where('project_id', $item['id'])->first();
            $created_at = str_replace('T', ' ', str_replace('Z', '', $item['created_at']));
            $updated_at = str_replace('T', ' ', str_replace('Z', '', $item['updated_at']));
            if($project) {
                if($project->updated_at != $updated_at) {
                    $project->update([
                        'name' => $item['name'],
                        'client_name' => $item['client']['name'],
                        'start_date' => $created_at,
                        'budget' => $item['budget'],
                        'project_created_at' => $created_at,
                        'project_updated_at' => $updated_at,
                    ]);
                }
            } else {
                $project = Project::create([
                        'project_id' => $item['id'],
                        'name' => $item['name'],
                        'client_name' => $item['client']['name'],
                        'start_date' => $item['starts_on'],
                        'budget' => $item['budget'],
                        'is_active' => $item['is_active'] ? 1 : 0,
                        'project_created_at' => $created_at,
                        'project_updated_at' => $updated_at,
                    ]);
            }
            $tracked_data = $this->getTrackedHours($project->project_id);
            
            $project->update([
                'is_active' => $item['is_active'] ? 1 : 0,
                'tracked' => $tracked_data['tracked_hours'],
            ]);
        }
    }

    public function getTrackedHours($project_id) {
        $setting = Setting::find(1);
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.harvestapp.com/v2/time_entries?project_id=".$project_id,
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
        $tracked_hours = $collection->sum('hours');
        $billable_hours = $collection->where('billable', true)->sum('hours');
        $data = [
            'tracked_hours' => $tracked_hours,
            'billable_hours' => $billable_hours,
        ];
        return $data;
    }

    public function getBillable() {
        $setting = Setting::find(1);
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.harvestapp.com/v2/reports/time/projects?from=20200101&to=20201231",
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
        $collection = collect($response['results']);
        $total_hours = $collection->sum('total_hours');
        $billable_hours = $collection->sum('billable_hours');
        $billable = intval($billable_hours / $total_hours * 100);
        return $billable;
    }

    public function getHoursTracked() {
        $setting = Setting::find(1);
        $start_month = Carbon::now()->startOfMonth()->format('Ymd');
        $end_month = Carbon::now()->endOfMonth()->format('Ymd');
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.harvestapp.com/v2/reports/time/projects?from=$start_month&to=$end_month",
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
        $collection = collect($response['results']);
        $total_hours = $collection->sum('total_hours');
        return $total_hours;
    }

    public function getClientCount() {
        $setting = Setting::find(1);
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.harvestapp.com/v2/clients",
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

        return count($response['clients']);
    }

    public function project_update(Request $request) {
        $project = Project::find($request->get('id'));
        $field = $request->get('field');
        $value = $request->get('value');
        $project->update([
            $field => $value,
        ]);
        return response()->json(['status' => 200, 'data' => $project]);
    }

    public function hidden_projects(Request $request) {
        $data = Project::where('is_hidden', 1)->get();
        return view('hidden_projects', compact('data'));
    }

    public function setting() {
        config(['site.page' => 'setting']);
        $setting = Setting::find(1);
        return view('setting', compact('setting'));
    }

    public function setting_update(Request $request) {
        $setting = Setting::find(1);
        $setting->update([
            'account_id' => $request->get('account_id'),
            'access_token' => $request->get('access_token'),
        ]);
        return back()->with('success', 'Updated Successfully');
    }
}
