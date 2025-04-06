<?php

namespace App\Http\Controllers\Web\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Room;
use App\Models\SessionRequest;

class AdminAuthController extends Controller
{
    public function showLoginForm()
    {
        return view('admin.login');
    }

    public function login(Request $request)
    {
        $credentials['email'] = $request->email;
        $credentials['password'] = $request->password;

        if (Auth::guard('admin')->attempt($credentials)) {
            return redirect()->route('admin.dashboard');
        }

        return back()->withErrors(['email' => 'Invalid credentials']);
    }

    public function logout()
    {
        Auth::guard('admin')->logout();
        return redirect()->route('admin.login');
    }

    public function dashboard()
    {
        $userCount = User::count();
        $roomCount = Room::count();
        $requestCount = SessionRequest::count();
        $users = User::all();
        $rooms = Room::all();
        $requests = SessionRequest::all();
        return view('admin.dashboard', [
            'userCount' => $userCount,
            'roomCount' => $roomCount,
            'requestCount' => $requestCount,
            'users' => $users,
            'rooms' => $rooms,
            'requests' => $requests
        ]);
    }
    
}

