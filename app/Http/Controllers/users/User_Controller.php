<?php

namespace App\Http\Controllers\users;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use app\Models\User;

class User_Controller extends Controller
{
    public function index()
    {
        $users = User::orderBy("created_at", "desc")->paginate(500);
        return view('content.apps.users.list', compact('users'));
    }
    public function view($id, Request $request)
    {
        $user = User::findOrFail($id);
        
        if ($request->ajax()) {
            return view('content.apps.users.partials.show-modal-content', compact('user'));
        }

        return view('content.apps.users.view', compact('user'));
    }
}
