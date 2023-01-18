<?php

namespace App\Http\Controllers;

use App\Models\Image;
use App\Models\Info;

class InfoController extends CrudController
{

    private $view_path;

    private $variable = 'info';


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
         $parameters = $this->allColumns(str_replace('Controller','','InfoController')) ;

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
          return view($this->view_path,['all'=>Info::get()]);
    }

   /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */

    public function create()
    {
         return view($this->view_path,[$this->variable => new Info()]);
    }

    /**
     * @return \Illuminate\Http\RedirectResponse
     */

    public function store()
    {
        $row = Info::create($this->validateRequest($this->parameters()));

        $this->storeFile($row,'main_image','/info');

        $this->storeFile($row,'image','/info');

        $this->storeFile($row,'image_last','/info');

                return  redirect()
                            ->route($this->variable.'.index')
                                ->with('success','Parameters added successfully')->withInput();
    }


     /**
     * @param Info $info
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */

    public function show(Info $info)
    {
       return view($this->view_path,[$this->variable=> $info]);
    }

    /**
     * @param Info $info
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */

    public function edit(Info $info)
    {
       return view($this->view_path,[$this->variable => $info]);
    }

    /**
     * @param Info $info
     * @return \Illuminate\Http\RedirectResponse
     */

    public function update(Info $info)
    {

        $this->deleteFile($info->image,'image');

        $this->deleteFile($info->image,'main_image');

        $this->deleteFile($info->image,'image_last');

        $info->update($this->validateRequest($this->parameters()));

        $this->storeFile($info,'main_image','/info');

        $this->storeFile($info,'image','/info');

        $this->storeFile($info,'image_last','/info');



        return redirect()
                        ->route($this->variable.'.index')
                            ->with('success','Parameters Successful Changes');
    }

    /**
     * @param Info $info
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */

    public function destroy(Info $info)
    {
        $this->deleteFile($info->image,'image');

        $this->deleteFile($info->image,'main_image');

        $this->deleteFile($info->image,'image_last');

       $info->delete();

               return  redirect()
                       ->route($this->variable.'.index')
                           ->with('massage','Successfully Removed');
    }

    /**
     *
     */
    public  function  additionalImages()
    {
        $row = Info::findOrFail(request('id'));
        $this->galleryAdd('file',$row,'product_additional');
    }


    /**
     *
     */
    public  function  additionalImagesRemove()
    {
        $row = Image::where('id',request('delete_file'));
        $this->galleryRemove('delete_file',$row);
    }
}
