<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\NavigationMenu;
use Illuminate\Support\Facades\Storage;

class CategoryController extends CrudController
{

    private $view_path;

    private $variable = 'category';


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
         $parameters = $this->allColumns(str_replace('Controller','','CategoryController')) ;

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
          return view($this->view_path,['all'=>Category::get()]);
    }

   /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */

    public function create()
    {
         return view($this->view_path,[$this->variable => new Category()]);
    }

    /**
     * @return \Illuminate\Http\RedirectResponse
     */

    public function store()
    {

        $row = Category::create($this->validateRequest($this->parameters()));

        $this->storeFile($row,'main_image','/category');

                return  redirect()
                            ->route($this->variable.'.index')
                                ->with('success','Parameters added successfully')->withInput();
    }


     /**
     * @param Category $category
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */

    public function show(Category $category)
    {
       return view($this->view_path,[$this->variable=> $category]);
    }

    /**
     * @param Category $category
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */

    public function edit(Category $category)
    {
       return view($this->view_path,[$this->variable => $category]);
    }

    /**
     * @param Category $category
     * @return \Illuminate\Http\RedirectResponse
     */

    public function update(Category $category)
    {
        if(request()->has('remove_image'))
        {
            $path = str_replace('/storage/','',$category->main_image);
            Storage::disk('public')->delete($path);
            $category->update(['main_image' =>null]);
        }

        $this->deleteFile($category->main_image,'main_image');

        $category->update($this->validateRequest($this->parameters()));

        $this->storeFile($category,'main_image','/category');



        return redirect()
                        ->route($this->variable.'.index')
                            ->with('success','Parameters Successful Changes');
    }

    /**
     * @param Category $category
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */

    public function destroy(Category $category)
    {

        $this->deleteFile($category->main_image,'main_image');

        Category::where('parent_id',$category->id)->update(['parent_id'=>null]);

        NavigationMenu::where('category_id',$category->id)->delete();

       $category->delete();

               return  redirect()
                       ->route($this->variable.'.index')
                           ->with('massage','Successfully Removed');
    }



}
