<?php

namespace App\Http\Controllers;

use App\Models\Blog;

class BlogController extends CrudController
{

    private $view_path;

    private $variable = 'blog';


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
         $parameters = $this->allColumns(str_replace('Controller','','BlogController')) ;

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
          return view($this->view_path,['all'=>Blog::get()]);
    }

   /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */

    public function create()
    {
         return view($this->view_path,[$this->variable => new Blog()]);
    }

    /**
     * @return \Illuminate\Http\RedirectResponse
     */

    public function store()
    {

         $row = Blog::create($this->validateRequest($this->parameters()));
         $row->products()->sync(request()->products);
         if(request()->has('meta')) {
            foreach(request()->meta as $data_type => $meta_data) {
                foreach($meta_data as $lang => $meta_body) {
                    $row->metatags()->updateOrCreate(['lang'=>$lang,'type'=>$data_type],['body'=>$meta_body]);
                }
            }
        }
         $this->storeFile($row,'main_image','/blog');
                return  redirect()
                            ->route($this->variable.'.index')
                                ->with('success','Parameters added successfully')->withInput();
    }


     /**
     * @param Blog $blog
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */

    public function show(Blog $blog)
    {
       return view($this->view_path,[$this->variable=> $blog]);
    }

    /**
     * @param Blog $blog
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */

    public function edit(Blog $blog)
    {
       return view($this->view_path,[$this->variable => $blog]);
    }

    /**
     * @param Blog $blog
     * @return \Illuminate\Http\RedirectResponse
     */

    public function update(Blog $blog)
    {
        if(request()->has('remove_image'))
        {
            $path = str_replace('/storage/','',$blog->main_image);
            Storage::disk('public')->delete($path);
            $blog->update(['main_image' =>null]);
        }
        $blog->products()->sync(request()->products);
        $this->deleteFile($blog->main_image,'main_image');
        if(!request()->has('is_featured')) {
            $blog->update(['is_featured'=>0]);
        }
        if(request()->has('meta')) {
            foreach(request()->meta as $data_type => $meta_data) {
                foreach($meta_data as $lang => $meta_body) {
                    $blog->metatags()->updateOrCreate(['lang'=>$lang,'type'=>$data_type],['body'=>$meta_body]);
                }
            }
        }
        $blog->update($this->validateRequest($this->parameters()));

        $this->storeFile($blog,'main_image','/blog');
        return redirect()
                        ->route($this->variable.'.index')
                            ->with('success','Parameters Successful Changes');
    }

    /**
     * @param Blog $blog
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */

    public function destroy(Blog $blog)
    {
        $this->deleteFile($blog->main_image,'main_image');
        $blog->products()->detach($blog->products->pluck('id')->toArray());
        $blog->metatags()->delete();
       $blog->delete();

               return  redirect()
                       ->route($this->variable.'.index')
                           ->with('massage','Successfully Removed');
    }
}
