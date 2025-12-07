<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\AdminAuthRequest;
use App\Http\Requests\AdminPasswordResetLinkRequest;
use App\Http\Requests\AdminPasswordResetRequest;
use App\Mail\AdminForgotPasswordMail;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    /* Show login form */
    public function showLoginForm()
    {
        return view("admin.auth.login");
    }

    /* Handle login */
    public function login(AdminAuthRequest $request)
    {
        try {
            $admin = Admin::where('email', $request->validated('email'))->first();

            if (!$admin) {
                return back()->withErrors([
                    'email' => 'The provided email does not match our records.',
                ])->withInput();
            }

            if (!Hash::check($request->validated('password'), $admin->password)) {
                return back()->withErrors([
                    'password' => 'The provided password is incorrect.',
                ])->withInput();
            }

            Auth::guard('admin')->login($admin);


            return redirect()->route('admin.dashboard.index');
        } catch (\Throwable $th) {
            return back()->with('error', 'An error occurred during login. Please try again.');
        }
    }

    /* Show forgot password form */
    public function showForgotPasswordForm()
    {
        return view("admin.auth.forgot-password");
    }

    /* Handle forgot password */
    public function sendResetLink(AdminPasswordResetLinkRequest $request)
    {
        try {
            $admin = Admin::where('email', $request->validated('email'))->firstOrFail();

            $token = Str::random(70);
            $admin->remember_token = $token;
            $admin->save();

            Mail::to($admin->email)->send(new AdminForgotPasswordMail(
                $token,
                $admin->name,
                $admin->email
            ));

            return back()->with('status', 'Password reset link has been sent to your email address.');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to send reset link. Please try again.');
        }
    }

    /* Show reset password form */
    public function showResetPasswordForm($token)
    {
        return view("admin.auth.reset-password", compact('token'));
    }

    /* Handle reset password */
    public function resetPassword(AdminPasswordResetRequest $request)
    {
        try {
            $admin = Admin::where([
                'email' => $request->validated('email'),
                'remember_token' => $request->validated('token')
            ])->first();

            if (!$admin) {
                return back()->with('error', 'Invalid token or email address.')
                    ->withInput();
            }

            $admin->password = Hash::make($request->validated('password'));
            $admin->remember_token = null;
            $admin->save();

            return redirect()->route('admin.login')
                ->with('success', 'Password has been reset successfully. You can now log in.');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to reset password. Please try again.')
                ->withInput();
        }
    }

    /* Logout */
    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('admin.login')->with('success', 'You have been logged out successfully.');
    }
}
