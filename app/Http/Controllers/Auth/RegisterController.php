<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Http\Requests\RegisterRequest;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    // use RegistersUsers;

    // public function __construct()
    // {
    //     $this->middleware('guest');
    // }

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    // protected $redirectTo = RouteServiceProvider::HOME;
    // protected $redirectTo = '/login';
    
    public function registerAdmin(RegisterRequest $request){

        $user = auth()->user();
        if($user->role == 'super-admin'){

            $admin_register = User::create([
                'username' => $request->username,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'phone' => $request->phone,
                'role'  => 'super-admin'
            ]);
            if($admin_register){
                return json_response(200,__('auth.register'),$admin_register);
            }
            return json_response(400,__('auth.register_error'));
        }
        else{
            return json_response(400,__('auth.auth_error'));
        }
    }
}
