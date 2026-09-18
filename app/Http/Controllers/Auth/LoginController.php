<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        if (request()->is('admin/*') || request()->is('*/admin') || request()->route()->named('login.admin')) {
            return view('auth.login');
        }
        if (request()->is('gate/*') || request()->is('*/gate') || request()->route()->named('login.gate')) {
            return view('auth.login');
        }
        return view('auth.login-selection');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');

        if (! Auth::guard('web')->attempt($credentials, $request->filled('remember'))) {
            throw ValidationException::withMessages([
                'email' => [trans('auth.failed')],
            ]);
        }

        $user = Auth::user();

        if ($request->is('admin/*') && ! $user->isAdmin()) {
            Auth::logout();
            return back()->withErrors(['email' => __('You do not have admin access.')]);
        }

        if ($request->is('gate/*') && ! $user->isGateMan()) {
            Auth::logout();
            return back()->withErrors(['email' => __('You do not have gate access.')]);
        }

        if ($request->is('admin/*') || $request->route()->named('login.admin.post')) {
            return redirect()->intended(route('admin.dashboard'));
        }

        return redirect()->intended(route('gate.dashboard'));
    }

    public function logout()
    {
        Auth::logout();
        return redirect('/');
    }
}
