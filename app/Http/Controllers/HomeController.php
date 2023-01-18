<?php

namespace App\Http\Controllers;

use App\Models\Orders;
use App\Models\Product;
use App\User;


class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $orders = Orders::where('status','Completed')->get();

        $users = User::whereJsonContains('roles','ROLE_USER')->get();

        $products = Product::get();

        return view('home',compact('orders','users','products'));
    }


    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function allUsers()
    {

        if(auth()->user()->hasRole('ROLE_SUPER_ADMIN')){
            $users = User::get();
        }

        elseif(auth()->user()->hasRoles(['ROLE_ADMIN','ROLE_SHOP_MANAGER'])){
            $users = User::whereJsonDoesntContain('roles','ROLE_SUPER_ADMIN')->whereJsonDoesntContain('roles','ROLE_ADMIN')->get();
        }
        else{
            abort(404);
        }



        return view('admin.pages.users.index',compact('users'));
    }

    /**
     * @param $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function userEdit($id)
    {
        $user = User::findOrFail($id);

        return view('admin.pages.users.show',compact('user'));
    }


    public function userOrders($id)
    {
        $orders = Orders::where('user_id',$id)->get();

        return view('admin.pages.users.orders',compact('orders'));
    }



    public function orderModal()
    {
        $order = Orders::findOrFail(request('id'));

        return view('admin.components.details_modal')->with('orderDetail',$order)->render();
    }

}
