<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use App\Models\AdminUser;
use Auth;
use DB;

class LoginController extends Controller
{
    protected $guard = 'admin';  // Use 'admin' guard for login

    public function showLoginForm()
    {
        return view('auth.adminlogin');
    }

    public function login(Request $request)
    {

        $key = hex2bin("0123456789abcdef0123456789abcdef");
        $iv = hex2bin("abcdef9876543210abcdef9876543210");

        $decryptedId = openssl_decrypt($request->identity, 'AES-128-CBC', $key, OPENSSL_ZERO_PADDING, $iv);
        $decryptedId = trim($decryptedId);
        $decryptedPwd = openssl_decrypt($request->password, 'AES-128-CBC', $key, OPENSSL_ZERO_PADDING, $iv);
        $decryptedPwd = trim($decryptedPwd);

        $request->merge([
            'identity' => $decryptedId,
            'email' => $decryptedId,
            'password' => $decryptedPwd,
        ]);

        $credentials = $request->only('email', 'password');
        if (Auth::guard('admin')->attempt($credentials)) {
             return redirect()->route('admin.home');
           // return redirect()->intended('/admin/dashboard');
        }

        return back()->withErrors([
            'email' => 'Invalid credentials',
        ]);
    }

    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/admin/login');
    }
}
