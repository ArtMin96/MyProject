<?php


namespace App\Http\View\Composers;


use App\Models\AttributeGroup;
use App\Models\Blog;
use App\Models\Brand;
use App\Models\Category;
use App\Models\BlogCategory;
use App\Models\Components;
use App\Models\NavigationMenu;
use App\Models\Product;
use App\Models\Tag;
use Illuminate\View\View;
class PartialsComposer
{

    /**
     * @param View $view
     */
    public function compose(View $view)
    {
        $view->with([
            'parents' => $this->getParents(),
            'categories' =>$this->getCategories(),
            'components' => $this->getComponent(),
            'products' => $this->getProducts(),
            'blog_categories' => $this->getBlogCategories(),
            'blog_items' => $this->getBlogs(),
            'brands' => $this->getBrand(),
            'attribute_groups' => $this->getAttributeGroup(),
            'tags' => $this->getAttributeTags(),
        ]);
    }

    /**
     * @return mixed
     */

    public function getParents()
    {
        return NavigationMenu::where('parent_id',0)->get();
    }

    /**
     * @return mixed
     */
    public function getComponent()
    {

        return Components::get();
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function getCategories()
    {
        return Category::get();
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function getProducts()
    {
        return Product::get();
    }

    /**
     * @return mixed
     */
    public  function getBrand()
    {
        return Brand::get();
    }

        /**
     * @return \Illuminate\Support\Collection
     */
    public function getBlogCategories()
    {
        return BlogCategory::get();
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function getBlogs()
    {
        return Blog::get();
    }

    /**
     * @return mixed
     */
    public  function getAttributeGroup()
    {
        return AttributeGroup::get();
    }


    public  function getAttributeTags()
    {
        return Tag::get();
    }

}
