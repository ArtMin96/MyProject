<?php

namespace App\Http\Controllers;

use App\Models\BlogCategory;

class BlogCategoryController extends CrudController
{

    private $view_path;

    private $variable = 'blog_category';


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
         $parameters = $this->allColumns(str_replace('Controller','','BlogCategoryController')) ;

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
          return view($this->view_path,['all'=>BlogCategory::get()]);
    }

   /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */

    public function create()
    {
         return view($this->view_path,[$this->variable => new BlogCategory()]);
    }

    /**
     * @return \Illuminate\Http\RedirectResponse
     */

    public function store()
    {
        $row = BlogCategory::create($this->validateRequest($this->parameters()));
         $this->storeFile($row,'main_image','/blog_category');

                return  redirect()
                            ->route($this->variable.'.index')
                                ->with('success','Parameters added successfully')->withInput();
    }


     /**
     * @param BlogCategory $blogCategory
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */

    public function show(BlogCategory $blogCategory)
    {
       return view($this->view_path,[$this->variable=> $blogCategory]);
    }

    /**
     * @param BlogCategory $blogCategory
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */

    public function edit(BlogCategory $blogCategory)
    {
       return view($this->view_path,[$this->variable => $blogCategory]);
    }

    /**
     * @param BlogCategory $blogCategory
     * @return \Illuminate\Http\RedirectResponse
     */

    public function update(BlogCategory $blogCategory)
    {
        if(request()->has('remove_image'))
        {
            $path = str_replace('/storage/','',$blogCategory->main_image);
            Storage::disk('public')->delete($path);
            $blogCategory->update(['main_image' =>null]);
        }

        $this->deleteFile($blog->main_image,'main_image');

        $blogCategory->update($this->validateRequest($this->parameters()));

        $this->storeFile($blogCategory,'main_image','/blog_category');
        return redirect()
                        ->route($this->variable.'.index')
                            ->with('success','Parameters Successful Changes');
    }

    /**
     * @param BlogCategory $blogCategory
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */

    public function destroy(BlogCategory $blogCategory)
    {
        $this->deleteFile($blogCategory->main_image,'main_image');
        $blogCategory->delete();

               return  redirect()
                       ->route($this->variable.'.index')
                           ->with('massage','Successfully Removed');
    }
}
