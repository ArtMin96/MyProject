<?php

namespace App\Http\Controllers;

use App\Models\Coupons;

class CouponsController extends CrudController
{

    private $view_path;

    private $variable = 'coupons';


     public function __construct()
     {
         parent::__construct();
         if(request()->route())
         {
             $this->view_path = 'admin.pages.'.request()->route()->getName();
         }
     }


    /**
     * @var array
     */

    private function parameters ()
     {
         $parameters = $this->allColumns(str_replace('Controller','','CouponsController')) ;

         /* For validation rules write here
          For Example:
                  $parameters['title'] = 'required';
         */


         return $parameters ;

     }


    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */

    public function index()
    {
          return view($this->view_path,['all'=>Coupons::get()]);
    }

   /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */

    public function create()
    {
         return view($this->view_path,[$this->variable => new Coupons()]);
    }

    /**
     * @return \Illuminate\Http\RedirectResponse
     */

    public function store()
    {
         Coupons::create($this->validateRequest($this->parameters()));

                return  redirect()
                            ->route($this->variable.'.index')
                                ->with('success','Parameters added successfully')->withInput();
    }


     /**
     * @param Coupons $coupons
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */

    public function show(Coupons $coupons)
    {
       return view($this->view_path,[$this->variable=> $coupons]);
    }

    /**
     * @param Coupons $coupons
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */

    public function edit(Coupons $coupons)
    {
       return view($this->view_path,[$this->variable => $coupons]);
    }

    /**
     * @param Coupons $coupons
     * @return \Illuminate\Http\RedirectResponse
     */

    public function update(Coupons $coupons)
    {
        $coupons->update($this->validateRequest($this->parameters()));

        return redirect()
                        ->route($this->variable.'.index')
                            ->with('success','Parameters Successful Changes');
    }

    /**
     * @param Coupons $coupons
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */

    public function destroy(Coupons $coupons)
    {
       $coupons->delete();

               return  redirect()
                       ->route($this->variable.'.index')
                           ->with('massage','Successfully Removed');
    }
}
