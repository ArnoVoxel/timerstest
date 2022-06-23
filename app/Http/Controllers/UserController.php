<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function liste()
    {
        $users = User::all();

        //dd($users);

        return view('users', [
            'users' => $users,
        ]);
    }
}
