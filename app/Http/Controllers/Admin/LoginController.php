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

    public function updateAccount(Request $request)
    {
        try {
            DB::transaction(function () use ($request) {
                $user = AdminUser::find($request->user_id);

                if (!$user) {
                    return response()->json(['status' => 'error', 'message' => 'User not found!']);
                }

                // Update user's email (you can extend this to other fields)
                $user->email = $request->email;
                $user->save();
            });

            return response()->json(['status' => 'success', 'message' => 'Account updated successfully!']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Something went wrong!']);
        }
    }
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
        $user = Auth::guard('admin')->user();

        // Check if the password is not changed
        if ($user->password_changed == 0) {
            session()->flash('force_password_change', true);
        }

        return redirect()->route('admin.home');
    }

    return back()->withErrors([
        'email' => 'Invalid credentials',
    ]);
}

    public function logout()
    {
        Auth::guard('admin')->logout();
        return redirect('/admin/login');
    }
}
