<?php

namespace App\Http\Controllers\users;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use app\Models\User;

class User_Controller extends Controller
{
    public function index()
    {
        $users = User::orderBy("created_at", "desc")->paginate(10);
        return view('content.apps.users.list', compact('users'));
    }
    public function view($id)
    {
        $user = User::findOrFail($id);
        return view('content.apps.users.view', compact('user'));
    }
}
