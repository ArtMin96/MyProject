<?php

namespace App\Http\Controllers;

use App\Models\Cities;
use App\Models\Countries;
use App\Models\Partner;
use App\Models\States;

class PartnerController extends CrudController
{

    private $view_path;

    private $variable = 'partner';


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
         $parameters = $this->allColumns(str_replace('Controller','','PartnerController')) ;

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
          return view($this->view_path,['all'=>Partner::get()]);
    }

   /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */

    public function create()
    {
         $countries = Countries::get();

         return view($this->view_path,[$this->variable => new Partner(),'countries' => $countries]);
    }

    /**
     * @return \Illuminate\Http\RedirectResponse
     */

    public function store()
    {

       $row = Partner::create($this->validateRequest($this->parameters()));

        $this->storeFile($row,'logo','/partner');

                return  redirect()
                            ->route($this->variable.'.index')
                                ->with('success','Parameters added successfully')->withInput();
    }


     /**
     * @param Partner $partner
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */

    public function show(Partner $partner)
    {
        $location = (object)[
            'country' => Countries::where('id',$partner->country_id)->first()->country['en'],
            'state' => States::where('id',$partner->state_id)->first()->state['en'],
            'city' => Cities::where('id',$partner->city_id)->first()->city['en'],
        ];

       return view($this->view_path,[$this->variable=> $partner,'location'=> $location]);
    }

    /**
     * @param Partner $partner
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */

    public function edit(Partner $partner)
    {
        $countries = Countries::get();

       return view($this->view_path,[$this->variable => $partner,'countries'=>$countries]);
    }

    /**
     * @param Partner $partner
     * @return \Illuminate\Http\RedirectResponse
     */

    public function update(Partner $partner)
    {

        $this->deleteFile($partner->logo,'logo');

        $partner->update($this->validateRequest($this->parameters()));

        $this->storeFile($partner,'logo','/partner');

        return redirect()
                        ->route($this->variable.'.index')
                            ->with('success','Parameters Successful Changes');
    }

    /**
     * @param Partner $partner
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */

    public function destroy(Partner $partner)
    {

        $this->deleteFile($partner->logo,'logo');

       $partner->delete();

               return  redirect()
                       ->route($this->variable.'.index')
                           ->with('massage','Successfully Removed');
    }



    public  function  filter(){
        $output = '';
        switch (request('type')) {
            case 'country':
                $collection = Countries::find(request('id'))->states;
                $column = 'state';
                break;
            case 'state':
                $collection = States::find(request('id'))->cities;
                $column = 'city';
                break;
            default:
                // echo "i не равно 0, 1 или 2";
        }

        foreach ($collection as $item) {
            $output .= '<option value="'.$item->id.'" >'.jToS($item->$column).'</option>';
        }
          return $output;

    }
}
