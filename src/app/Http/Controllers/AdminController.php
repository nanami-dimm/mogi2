<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class AdminController extends Controller
{

    public function login()
    {
    return view('manager_login');
    }

public function store(Request $request)
    {
        
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            
            $user = Auth::user();
            
            
            if ($user->role === 'admin') {
                return redirect('admin/attendance/list');  
            }

            
            return redirect('/attendance/list');  
        }
    }

    public function index()
    {
        return view('manager_attendance');
    }
}
