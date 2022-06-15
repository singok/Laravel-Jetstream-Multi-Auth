<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Admin;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Contracts\Auth\StatefulGuard;
use Laravel\Jetstream\Jetstream;
use Auth;

class RegisterController extends Controller
{

    protected $guard;

    public function __construct(StatefulGuard $guard) {
        $this->guard = $guard;
    }

    public function create()
    {
        if (Auth::guard('admin')->user()) {
            return redirect()->route('dashboard-admin');
        } else {
            return view('admin.register');
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required',
            'password' => 'required | confirmed',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                            ->withErrors($validator)
                            ->withInput();
        }

        $info = Admin::create([
                    'name' => $request['name'],
                    'email' => $request['email'],
                    'password' => Hash::make($request['password']),
        ]);

        Auth::guard('admin')->login($info);

        return redirect()->route('dashboard-admin');
    }
}
