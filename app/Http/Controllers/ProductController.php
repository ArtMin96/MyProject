<?php

namespace App\Http\Controllers;

use App\Models\Attribute;
use App\Models\AttributeGroup;
use App\Models\Image;
use App\Models\Product;
use App\Models\ProductAttribute;
use App\Models\ProductFilter;
use App\Models\Sale;
use App\Models\MetaTags;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductController extends CrudController
{


    private $view_path;

    private $variable = 'product';


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
         $parameters = $this->allColumns(str_replace('Controller','','ProductController')) ;

         /* For validation rules write here
          For Example:
                  $parameters['title'] = 'required';
         */


         return $parameters ;

     }


     private  function  saleParameters()
     {
         $parameters = [];
         if(request()->has('sale') and request()->has('sale_type')){
            $parameters['value'] = request()->sale;
            $parameters['type'] = request()->sale_type;
            $parameters['start_data'] = request()->sale_from;
            $parameters['end_data'] = request()->sale_to;

         }

            return $parameters;
     }

//     private function options($options,$id) {
//
//        foreach ($options as $option){
//            Options::create([
//                'product_id' => $id,
//                'stock' => $option['stock'],
//                'color' => $option['color'],
//                ]);
//        }
//
//     }

    /**
     * @param $id
     * @param $name
     *
     */

    private function  attribute($id,$name)
    {
        if (request()->has($name)) {
            foreach (request($name) as $attribute) {

                foreach ($attribute as $new_row) {

                    $new_row['product_id'] = $id;
                    if (array_key_exists("value", $new_row)) {
                        $filter = ProductFilter::where('type',$new_row['type'])
                        ->where('attribute_group_id',$new_row['attribute_group_id'])
                        ->where('attribute_id',$new_row['attribute_id'])->first();


                        if ($new_row['type'] != 'range') {
                            $new_row['value'] = explode(",", $new_row['value']);
                            ProductAttribute::create($new_row);

                        }else {
                            if(isset($filter)){
                                ProductAttribute::create($new_row);
                                $mergedArr = array_merge(array_values($filter->value),array_values($new_row['value']));
                                $new_row['value']['from'] = min($mergedArr);
                                $new_row['value']['to'] = max($mergedArr);

                            }
                        }




                        if(isset($filter)){
                            $new_value = array_unique(array_merge($filter->value,$new_row['value']), SORT_REGULAR);
                            $filter->update(['value' => $new_value ]);
                        }else{
                          $group =  AttributeGroup::where('id',$new_row['attribute_group_id'])->first();
                          $slug= Str::slug(str_arm($group->title[config('app.fallback_locale')]));
                           ProductFilter::create([
                               'slug'=>$slug,
                               'attribute_group_id'=>$new_row['attribute_group_id'],
                               'type'=>$new_row['type'],
                               'attribute_id'=>$new_row['attribute_id'],
                               'value'=>$new_row['value'],
                           ]);
                        }
                    }
                }
            }
        }
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */

    public function index()
    {
          return view($this->view_path,['all'=>Product::get()]);
    }

   /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */

    public function create()
    {
        $required_attr = AttributeGroup::with('attributes')->whereHas('attributes',function ($query){
            $query->where('is_required',1);
        })->get();
         return view($this->view_path,[$this->variable => new Product(),'required_attr'=>$required_attr]);
    }

    /**
     * @return \Illuminate\Http\RedirectResponse
     */

    public function store()
    {

        DB::beginTransaction();
        try{

            $slug = generateSlug(request()->slug,request()->title);
            $row = Product::create($this->validateRequest($this->parameters()));
           /* if(Product::where('slug',$slug)->count() > 0) {
                $slug = $slug.'-'.$row->id;
            }*/

            $row->slug = $slug;
            $row->update();
            $this->storeFile($row,'main_image','/product/main/');


            if(request()->has('meta')) {
                foreach(request()->meta as $data_type => $meta_data) {
                    if(isset($meta_data)) {
                        foreach($meta_data as $lang => $meta_body) {
                            if(isset($meta_body)) {
                                $row->metatags()->updateOrCreate(['lang'=>$lang,'type'=>$data_type],['body'=>$meta_body]);
                            }
                        }
                    }
                }
            }



            $row->components()->sync(request()->components);

            $row->categories()->sync(request()->categories);

            $row->crossSale()->sync(request()->products);

            $row->productByTag()->sync(request()->product_tags);

//            $this->options(request('options'),$row->id);

            if($this->saleParameters())
            {
                $crete_box = $this->saleParameters();

                $crete_box['product_id'] = $row->id;

                 Sale::create($crete_box);

            }

            $this->attribute($row->id,'attribute');

            DB::commit();

            return  redirect()
                ->route($this->variable.'.index')
                ->with('success','Parameters added successfully')->withInput();
        }
        catch(\Exception $exception) {

            DB::rollBack();
                return redirect()->back()->with('massage','Please check all parameters')->withInput();
        }
    }



     /**
     * @param Product $product
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */

    public function show(Product $product)
    {
       return view($this->view_path,[$this->variable=> $product]);
    }

    /**
     * @param Product $product
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */

    public function edit(Product $product)
    {
        $required_attr = AttributeGroup::with('attributes')->whereHas('attributes',function ($query){
            $query->where('is_required',1);
        })->get();
       return view($this->view_path,[$this->variable => $product,'required_attr' => $required_attr,'is_edit' => true]);
    }

    /**
     * @param Product $product
     * @return \Illuminate\Http\RedirectResponse
     */

    public function update(Product $product)
    {

        DB::beginTransaction();

        try{
        $this->deleteFile($product->main_image,'main_image');
        $slug = generateSlug(request()->slug,request()->title);
        $product->update($this->validateRequest($this->parameters()));
       /* if(Product::where('slug',$slug)->count() > 0 && Product::where('slug',$slug)->first()->id != $product->id) {
            $slug = $slug.'-'.$product->id;
        }*/
        if(!request()->has('is_new')) {
            $product->is_new = null;
        }
        if(!request()->has('out_stock')) {
            $product->out_stock = 0;
        }
        $product->slug = $slug;
        $product->update();

        $this->storeFile($product,'main_image','/product');

        $product->components()->sync(request()->components);

        $product->categories()->sync(request()->categories);

        $product->crossSale()->sync(request()->products);

        $product->productByTag()->sync(request()->product_tags);
        if(request()->has('meta')) {
            foreach(request()->meta as $data_type => $meta_data) {
                if(isset($meta_data)) {
                    foreach($meta_data as $lang => $meta_body) {
                        if(isset($meta_body)){
                            $product->metatags()->updateOrCreate(['lang'=>$lang,'type'=>$data_type],['body'=>$meta_body]);
                        }
                    }
                }
            }
        }

//        Options::where('product_id',$product->id)->delete();

//        $this->options(request('options'),$product->id);

        $sale_row = Sale::where('product_id',$product->id);

        if($this->saleParameters()){
            Sale::updateOrCreate(['product_id'=>$product->id],$this->saleParameters());
        }
        else{
            $sale_row->delete();
        }

        ProductAttribute::where('product_id',$product->id)->delete();

        $this->attribute($product->id,'attribute');

            DB::commit();

        return redirect()
                        ->route($this->variable.'.index')
                            ->with('success','Parameters Successful Changes');
            }
        catch(\Exception $exception) {
            #dd($exception);
            DB::rollBack();
            return redirect()->back()->with('massage','Please check all parameters');
        }
    }

    /**
     * @param Product $product
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */

    public function destroy(Product $product)
    {

        DB::beginTransaction();


        try
        {
            if(isset($product->images))
            {
                foreach ($product->images as $image)
                {
                    $path = str_replace('/storage/','',$image->path);

                    Storage::disk('public')->delete($path);
                    $image->delete();
                }
            }

            $product->components()->detach($product->components->pluck('id')->toArray());
            $product->metatags()->delete();
            $product->categories()->detach($product->categories->pluck('id')->toArray());

            #$product->crossSale()->detach($product->crossSale->pluck('id')->toArray());

            Sale::where('product_id',$product->id)->delete();

            ProductAttribute::where('product_id',$product->id)->delete();

            $this->deleteFile($product->main_image,'main_image');

            $product->delete();

            DB::commit();

            return  redirect()
                ->route($this->variable.'.index')
                ->with('massage','Successfully Removed');
        }
        catch (\Exception $exception){
            DB::rollBack();
            return redirect()->back()->with('massage','Please check all parameters');

        }


    }



//    --------------------------------Additional image methods-----------------------


    /**
     *
     */
    public  function  additionalImages()
    {
        $row = Product::findOrFail(request('id'));
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


    public function updateOrderCount() {

    }

    public function duplicate($id) {
        $product = Product::findOrFail($id);
        $required_attr = AttributeGroup::with('attributes')->whereHas('attributes',function ($query){
            $query->where('is_required',1);
        })->get();
        return view('admin.pages.product.create',[$this->variable => $product,'required_attr' => $required_attr,'is_edit' => true]);
    }

}
