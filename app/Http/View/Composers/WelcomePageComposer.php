<?php


namespace App\Http\View\Composers;


use App\Models\Category;
use App\Models\Product;
use App\Models\Slider;
use App\Models\Blog;
use Illuminate\View\View;

class WelcomePageComposer
{
    public function compose(View $view)
    {
            $view->with([
                'sliders' => $this->sliders(),
                'products_best' => $this->products('best'),
                'categories' => $this->categories(),
                'products' => $this->products(),
                'blogs' => $this->blogs(),
                'featuredBlog' => $this->featuredBlog(),
            ]);
    }

    public  function sliders()
    {
        return Slider::orderBy('order_by','desc')->where('status',1)->get();
    }


    public  function  products($type = null)
    {
        if($type){
            return Product::orderBy('orders','desc')->where('status',1)->take(10)->get();
        }
        return Product::get();
    }

    public  function  categories ()
    {

        $category = Category::where('status',1)->orderBy('order_by','asc')->with(['subCategories' => function ($query) {
            $query->orderBy('order_by', 'asc')->where('status',1);
        },'products'=>function ($query)
        {
            $query->where('status',1);
        }
        ])->get();

        return  $category;

    }

    public function featuredBlog() {
        return Blog::where('is_featured',1)->first();
    }

    public function blogs() {
        return Blog::get();
    }
}
