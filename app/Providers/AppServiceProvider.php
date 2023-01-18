<?php

namespace App\Providers;

use App\Billing\AmeriaGateway;
use App\Billing\ArcaGateway;
use App\Billing\CashGateway;
use App\Billing\GatewayContract;
use App\Billing\iDramGateway;
use App\Http\Middleware\CheckUserRole;
use App\Http\View\Composers\PartialsComposer;
use App\Http\View\Composers\WelcomePageComposer;
use App\Models\Cart;
use App\Models\Language;
use App\Models\NavigationMenu;
use App\Models\RegisterSettings;
use App\Models\Settings;
use App\Role\RoleChecker;
use App\Role\UserRole;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Config;
use App\Models\AttributeGroup;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(CheckUserRole::class, function(Application $app) {
            return new CheckUserRole(
                $app->make(RoleChecker::class)
            );
        });


        $this->app->singleton(GatewayContract::class,function ($app){

            if(request()->has('pay_type')) {
                $type = request('pay_type');
            }else {
                $type = explode('.', \request()->route()->getName())[0];
            }
                switch ($type) {
                    case 'arca':
                        return new ArcaGateway('AMD');
                    case 'idram':
                        return new iDramGateway('AMD');
                    case 'ameria':
                        return new AmeriaGateway('AMD');
                    case 'cash':
                        return new CashGateway('AMD');
                    case 'saved_card':
                        return new ArcaGateway('AMD');
                    default:
                       abort(404);
                }
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $useHTTPS = true;
        if ($useHTTPS) {
            $this->app['request']->server->set('HTTPS', true);
        }
        $this->app['config']['mail'] = [
            'driver' => 'smtp',
            'host' => settings('mail_host'),
            'port' => settings('mail_port'),
            'from' => [
                'address' => settings('mail_from'),
                'name' => config('app.name'),
            ],
            'encryption' => settings('mail_encryption'),
            'username' => settings('mail_username'),
            'password' => settings('mail_password'),
			'sendmail' => '/usr/sbin/sendmail -t -i',
			'pretend' => false,
			'stream' => [
			'ssl' => [
				'allow_self_signed' => true,
				'verify_peer' => false,
				'verify_peer_name' => false,
			],
		]
        ];

        if(!Cookie::has('cart_id')){
        Cookie::queue('cart_id',session()->getId(),45000);
        }
        View::composer(['admin.*'],function ($view){
            $view->with('languages',Language::get())->with('item',request()->segment(2));
        });

        View::composer(['auth.register'],function ($view){
            $view->with('additional_settings',RegisterSettings::orderBy('order_by','desc')->where('status',1)->get());
        });

        View::composer(['welcome','front.*'],function ($view){
            $view->with('languages',Language::get())
                ->with('menu_tabs',NavigationMenu::where('is_active',1)->orderBy('order_by', 'asc')->get())
                ->with('socials',Settings::where('group','social_media')->whereNotNull('link')->where('status',1)->get())
               ->with('shop_cart',Cart::where('session_id',Cookie::get('cart_id'))->count())
               ->with('sidebarFilters',AttributeGroup::get());
        });


        Blade::if('Role', function ($role){

            $roles =(new UserRole())->getAllRoles();

            if(!auth()->check() or !isset($roles->$role)) return false;

            return (new RoleChecker())->check(auth()->user(),$roles->$role);

        });


        Blade::if('Roles', function ( array $roles){

            $allRoles = (new UserRole())->getAllRoles();

            foreach ($roles as $role){
                if((new RoleChecker())->check(auth()->user(),$allRoles->$role)) return true;
            }
            return false;

        });





        /* View Composers  */

        View::composer('admin.partials.*', PartialsComposer::class);
        View::composer('welcome', WelcomePageComposer::class);



    }
}
