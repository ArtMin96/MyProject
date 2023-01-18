<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\UserAddress;
use App\Providers\RouteServiceProvider;
use App\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

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

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }



    protected function registered(Request $request, $user)
    {
      return (new LoginController())->authenticated($request, $user);
    }
    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        $messages = [
            'confirmed' => 'Գաղտնաբառերը չեն համընկնում',
            'min' =>'Գաղտնաբառը պետք է կազմված լինի առնվազն 8 նիշերից',
            'required' => 'Տվյալ դաշտը լրացնելը պարտադիր է',
            'unique' =>'Էլ հասցեն արդեն գրանցված է',
        ];
//        $data['phone'] = str_replace(['-',' ','+(374)'],'',$data['phone']);
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
           'phone' => ['required'],
//            'policy' => 'required',

        ],$messages);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        $user_row = User::create([
            'name' => $data['name'],
            'last_name' => $data['last_name'],
            'email' => $data['email'],
            'phone' =>isset($data['phone'])? $data['phone'] :'',
            'accept_notification' => key_exists('accept_notification',$data) ? $data['accept_notification'] : null,
            'additional' => key_exists('additional',$data) ? $data['additional'] : null,
            'password' => Hash::make($data['password']),
            'roles' => ["ROLE_USER"],
        ]);


        return  $user_row;

    }


}
