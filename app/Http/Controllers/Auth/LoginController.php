<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Providers\RouteServiceProvider;
use App\User;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Socialite;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    protected function authenticated(Request $request, $user)
    {

        if (auth()->user()->hasRole('ROLE_USER')) {

            if(Cart::where('session_id',Cookie::get('cart_id'))->count() > 0)
            {
                return redirect()->route('cart.page');
            }
            if(request()->has('next_url') && isset(request()->next_url)) {
                return redirect(request()->next_url);
            }
            return redirect('/');
        }

        return redirect('/admin/dashboard');
    }

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function __call($method, $parameters)
    {
        return self::$method(...$parameters);
    }


//    protected function credentials(Request $request)
//    {
//        $val = str_replace(['-',' ','+(374)'],'',$request->get('email'));
//        return ['phone'=>$val,'password'=>$request->get('password')];
//
//    }

    protected function sendFailedLoginResponse(Request $request)
    {
        return back()->with('val_mess', 'Մուտքագրված տվյալները սխալ են');
    }

    /**
     * Redirect the user to the OAuth Provider.
     *
     * @return Response
     */
    public function redirectToProvider($provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    /**
     * Obtain the user information from provider.  Check if the user already exists in our
     * database by looking up their provider_id in the database.
     * If the user exists, log them in. Otherwise, create a new user then log them in. After that 
     * redirect them to the authenticated users homepage.
     *
     * @return Response
     */
    public function handleProviderCallback($provider)
    {
        $user = Socialite::driver($provider)->user();
        $authUser = $this->findOrCreateUser($user, $provider);
        Auth::login($authUser, true);
        return redirect($this->redirectTo);
    }
    
    /**
     * If a user has registered before using social auth, return the user
     * else, create a new user object.
     * @param  $user Socialite user object
     * @param $provider Social auth provider
     * @return  User
     */
    public function findOrCreateUser($user, $provider)
    {
        
        if ($user->getEmail() != '') {
            $authUser = User::where('email', 'like', $user->getEmail())->first();
            if ($authUser) {
                $authUser->provider = $provider;
                  $authUser->provider_id = $user->getId();
                  $authUser->update(); 
                return $authUser;
            }
        }
        $str = $user->getName() . $user->getId() . $user->getEmail();
        return User::create([
                    'name' => $user->getName(),
                    'last_name' => $user->getName(),
                    'email' => $user->getEmail(),
                    'provider' => $provider,
                    'provider_id' => $user->getId(),
                    'roles' => ["ROLE_USER"],
                    'password' => bcrypt($str),
        ]);
    }

}
