<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\Auth\StatefulGuard;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Routing\Pipeline;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Actions\AttemptToAuthenticate;
use Laravel\Fortify\Actions\EnsureLoginIsNotThrottled;
use Laravel\Fortify\Actions\PrepareAuthenticatedSession;
use Laravel\Fortify\Actions\RedirectIfTwoFactorAuthenticatable;
use Laravel\Fortify\Contracts\LoginResponse;
use Laravel\Fortify\Contracts\LoginViewResponse;
use Laravel\Fortify\Contracts\LogoutResponse;
use Laravel\Fortify\Features;
use Laravel\Fortify\Fortify;
use Laravel\Fortify\Http\Requests\LoginRequest;
use Auth;

class LoginController extends Controller
{
    protected $guard;

    public function __construct(StatefulGuard $guard) {
        $this->guard = $guard;
    }

    public function index()
    {
        if (Auth::guard('admin')->user()) {
            return redirect()->route('dashboard-admin');
        } else {
            return view('admin.login');
        }
    }

    public function login(LoginRequest $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                            ->withErrors($validator)
                            ->withInput();
        }
        $email = $request->email;
        $password = $request->password;

        if (Auth::guard('admin')->attempt(['email' => $email, 'password' => $password])) {
            return redirect()->route('dashboard-admin');
        }else{
            return back()->withErrors(['crediential doesnot match']);
        }
    }

    public function destroy(Request $request): LogoutResponse {
        $this->guard->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return app(LogoutResponse::class);
    }
}
