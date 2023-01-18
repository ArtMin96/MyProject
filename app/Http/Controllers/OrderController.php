<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Orders;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index ($filter =null)
    {
        if($filter){
            if($filter == 'Completed'){
                $all = Orders::where('status',$filter)->orWhere('status','Pending')->orderBy('id','desc')->get();
            }
            else{
                $all = Orders::where('status',$filter)->orderBy('id','desc')->get();
            }

        }
        else{
            $all = Orders::orderBy('id','desc')->get();
        }

        return view('admin.pages.orders.index',compact('all','filter'));
    }

    public function show ($id)
    {
        $order = Orders::findOrFail($id);

        return view('admin.pages.orders.show',compact('order'));
    }

    public function  statusChange()
    {
        $row = Orders::where('id',request('id'))->update([request('type')=>request('val')]);

        return true;
    }
}
