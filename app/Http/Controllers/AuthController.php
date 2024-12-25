<?php

namespace App\Http\Controllers;

use App\Models\Roles;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Redis;

class AuthController extends Controller
{
    //

    public function __construct(
        protected UserService $userService
    ) {}
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required',
            'password' => 'required',
        ]);
        $email = $request->email;
        $password = $request->password;
        $role_id = $request->role_id;
        $remember = $request->remember;
        $user = Auth::attempt(['email' => $email, 'password' => $password, 'role_id' => $role_id], $remember);

        if ($user) {
            if (Auth::check() && !Auth::user()->email_verified_at) {
                Auth::logout();
                return redirect()->back()->withInput()->with(['message' => 'Tài khoản chưa xác thực', 'alert-type' => 'error']);
            }
            return redirect()->route(Auth::user()->role_id == Roles::ROLE_USER ? 'book.search' : (Auth::user()->role_id == Roles::ROLE_LIBRARIAN ? 'book.list' :
                'dashboard'))->with(['message' => 'Đăng nhập thành công', 'alert-type' => 'success']);
        } else {
            return redirect()->back()->withInput()->with(['message' => 'Sai tài khoản hoặc mật khẩu', 'alert-type' => 'error']);
        }
    }

    public function register(Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:users,email',
            'password' => 'required',
            'retype-password' => 'required|same:password',
            'name' => 'required',
        ]);
        $email = $request->email;
        $password = $request->password;
        $name = $request->name;
        $user = User::create(['email' => $email, 'password' => $password, 'name' => $name, 'role_id' => Roles::ROLE_USER]);
        $user->sendEmailVerificationNotification();

        if ($user) {
            return redirect()->route('login.view')->with(['message' => 'Đăng ký tài khoản thành công', 'alert-type' => 'success']);
        } else {
            return redirect()->back()->withErrors(['message' => 'Có lỗi xảy ra khi xử lý',  'alert-type' => 'error']);
        }
    }

    public function verify(Request $request)
    {
        $id = $request->id ?? '';
        $hash = $request->hash ?? '';

        $user = $this->userService->getUser(['id' => $id]);
        Auth::logout();
        if (!$user) {
            return redirect()->route('login.view')->with(['message' => 'Tài khoản không có trong hệ thống', 'alert-type' => 'error']);
        }


        if (!hash_equals((string) $hash, sha1($user->email))) {
            return redirect()->route('login.view')->with(['message' => 'Yêu cầu xác thực có lỗi xảy ra', 'alert-type' => 'error']);
        }

        if ($user->hasVerifiedEmail()) {
            return redirect()->route('login.view')->with(['message' => 'Tài khoản đã được xác thực', 'alert-type' => 'error']);
        }

        if ($user->markEmailAsVerified()) {
            event(new Verified($user));
        }
        return redirect()->route('login.view')->with(['message' => 'Tài khoản xác thực thành công', 'alert-type' => 'success']);
    }
    public function resendVerifyEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ]);

        $email = $request->email;

        $user = $this->userService->getUser(['email' => $email]);

        if (!$user) {
            return redirect()->route('verification.notice')->with(['message' => 'Email không tồn tại trong hệ thống', 'alert-type' => 'error']);
        }

        if ($user->hasVerifiedEmail()) {
            return redirect()->route('verification.notice')->with(['message' => 'Tài khoản đã được xác thực', 'alert-type' => 'error']);
        }

        $user->sendEmailVerificationNotification();
        return redirect()->route('login.view')->with(['message' => 'Email xác thực đã được gửi', 'alert-type' => 'success']);
    }
    public  function resendEmailView()
    {
        return view('auth.resend-email');
    }
    public function logout()
    {
        Auth::logout();
        return redirect()->route('login.view')->with(['message' => 'Đăng xuất thành công', 'alert-type' => 'success']);
    }

    public function forgotPassword()
    {
        return view('auth.forgot-password');
    }

    public function sendEmailForgotPassword(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        return $status === Password::RESET_LINK_SENT
            ? redirect()->route('login.view')->with(['status' => __($status), 'message' => 'Gửi email đặt lại mật khẩu thành công', 'alert-type' => 'success'])
            : back()->with(['email' => __($status), 'message' => 'Có lỗi khi gửi email', 'alert-type' => 'error']);
    }

    public function resetPassword(string $token)
    {
        return view('auth.reset-password', ['token' => $token]);
    }

    public function updateResetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|confirmed',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user, string $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ]);

                $user->save();

                event(new PasswordReset($user));
            }
        );

        return $status === Password::PASSWORD_RESET
            ? redirect()->route('login.view')->with(['status', __($status), 'message' => 'Đặt lại mật khẩu thành công', 'alert-type' => 'success'])
            : back()->with(['email' => [__($status)], 'message' => 'Có lỗ i trong quá trình xử lý', 'alert-type' => 'error']);
    }
}
