<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use App\Models\User;
use Auth;
use DB;

class LoginController extends Controller
{
    protected $guard = 'web';  // Use 'web' guard for login

     public function create()
    {
        return view('auth.userlogin');
    }

    public function store(Request $request)
    {

        $key = hex2bin("0123456789abcdef0123456789abcdef");
        $iv = hex2bin("abcdef9876543210abcdef9876543210");

        $decryptedId = openssl_decrypt($request->identity, 'AES-128-CBC', $key, OPENSSL_ZERO_PADDING, $iv);
        $decryptedId = trim($decryptedId);
        $decryptedPwd = openssl_decrypt($request->password, 'AES-128-CBC', $key, OPENSSL_ZERO_PADDING, $iv);
        $decryptedPwd = trim($decryptedPwd);

        $request->merge([
            'identity' => $decryptedId,
            'unique_login_id' => $decryptedId,
            'password' => $decryptedPwd,
        ]);

        $credentials = $request->only('unique_login_id', 'password');
        if (Auth::guard('web')->attempt($credentials)) {
             return redirect()->route('home');
        }

        return back()->withErrors([
            'email' => 'Invalid credentials',
        ]);
    }

    public function destroy(Request $request)
    {
        Auth::guard('web')->logout();
       $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }
}
