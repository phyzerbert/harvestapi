<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\User;

use Auth;

class UserController extends Controller {
    public function __construct() {
        $this->middleware('auth');
    }
    
    public function index(Request $request) {
        config(['site.page' => 'user']);
        $data = User::where('role', 'user')->get();
        return view('user', compact('data'));
    }

    public function create(Request $request) {
        User::create([
            'name' => $request->get('name'),
            'email' => $request->get('email'),
            'password' => bcrypt($request->get('password')),
        ]);
        return back()->with('success', 'Created Successfully');
    }

    public function destroy($id) {
        User::destroy($id);
        return back()->with('success', 'Deleted Successfully');
    }

    public function change_password(Request $request) {
        $user = Auth::user();
        $user->update([
            'password' => bcrypt('password'),
        ]);
        return back()->with('success', 'Updated Successfully');
    }
}
