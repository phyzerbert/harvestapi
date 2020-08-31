<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Owner;

class OwnerController extends Controller
{
    public function __construct() {
        $this->middleware('auth');
    }
    public function index(Request $request) {
        config(['site.page' => 'owner']);
        $data = Owner::all();
        return view('owner', compact('data'));
    }

    public function create(Request $request) {
        Owner::create([
            'name' => $request->get('name'),
        ]);
        return back()->with('success', 'Created Successfully');
    }

    public function destroy($id) {
        Owner::destroy($id);
        return back()->with('success', 'Deleted Successfully');
    }
}
