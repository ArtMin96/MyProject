<?php

namespace App\Http\Controllers;

use App\Models\Slider;

class SliderController extends CrudController
{

    private $view_path;

    private $variable = 'slider';


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
         $parameters = $this->allColumns(str_replace('Controller','','SliderController')) ;

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
          return view($this->view_path,['all'=>Slider::get()]);
    }

   /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */

    public function create()
    {
         return view($this->view_path,[$this->variable => new Slider()]);
    }

    /**
     * @return \Illuminate\Http\RedirectResponse
     */

    public function store()
    {


         $row  = Slider::create($this->validateRequest($this->parameters()));

         $this->storeFile($row,'image','/slider');


                return  redirect()
                            ->route($this->variable.'.index')
                                ->with('success','Parameters added successfully')->withInput();
    }


     /**
     * @param Slider $slider
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */

    public function show(Slider $slider)
    {
       return view($this->view_path,[$this->variable=> $slider]);
    }

    /**
     * @param Slider $slider
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */

    public function edit(Slider $slider)
    {
       return view($this->view_path,[$this->variable => $slider]);
    }

    /**
     * @param Slider $slider
     * @return \Illuminate\Http\RedirectResponse
     */

    public function update(Slider $slider)
    {

        $this->deleteFile($slider->image,'image');

        $slider->update($this->validateRequest($this->parameters()));

        $this->storeFile($slider,'image','/slider');



        return redirect()
                        ->route($this->variable.'.index')
                            ->with('success','Parameters Successful Changes');
    }

    /**
     * @param Slider $slider
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy(Slider $slider)
    {

        $this->deleteFile($slider->image,'image');

        $slider->delete();

               return  redirect()
                       ->route($this->variable.'.index')
                           ->with('massage','Successfully Removed');
    }
}
