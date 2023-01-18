<?php

namespace App\Http\Controllers;

use App\Models\Promo;
use App\Models\PromoItems;
use App\Models\PromoItemProducts;
use App\Models\Product;
use Symfony\Component\HttpFoundation\Request;
use Carbon\Carbon;
class PromoController extends CrudController
{

    private $view_path;

    private $variable = 'promo';


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
         $parameters = $this->allColumns(str_replace('Controller','','PromoController')) ;

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
          return view($this->view_path,['all'=>Promo::get()]);
    }

   /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */

    public function create()
    {
        $products = Product::get();
         return view($this->view_path,[$this->variable => new Promo(),'products'=>$products]);
    }

    /**
     * @return \Illuminate\Http\RedirectResponse
     */

    public function store(Request $request)
    {
        $promo = new Promo;
        $promo->title = $request->title;
        $promo->description = $request->description;
        $promo->date_from = Carbon::createFromFormat('d-m-Y', $request->date_from)->format('Y-m-d');
        $promo->date_to =  Carbon::createFromFormat('d-m-Y', $request->date_to)->format('Y-m-d');;
        $promo->save();

        foreach($request->promo as $promo_item) {
            $promo_items = new PromoItems;
            $promo_items->promo_id = $promo->id;
            $promo_items->type = $promo_item['type'];
            if(isset($promo_item['target_id'])) {
                $promo_items->target_id = $promo_item['target_id'];
            }
            if(isset($promo_item['min_price'])) {
                $promo_items->min_price = $promo_item['min_price'];
            }
            $promo_items->save();
            foreach($promo_item['product_ids'] as $product_id){
                $product = new PromoItemProducts;
                $product->promo_item_id = $promo_items->id;
                $product->product_id = $product_id;
                $product->save();
            }
        }

         #Promo::create($this->validateRequest($this->parameters()));

                return  redirect()
                            ->route($this->variable.'.index')
                                ->with('success','Parameters added successfully')->withInput();
    }


     /**
     * @param Promo $promo
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */

    public function show(Promo $promo)
    {
       return view($this->view_path,[$this->variable=> $promo]);
    }

    /**
     * @param Promo $promo
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */

    public function edit(Promo $promo)
    {
        $products = Product::get();
       return view($this->view_path,[$this->variable => $promo,'products'=>$products]);
    }

    /**
     * @param Promo $promo
     * @return \Illuminate\Http\RedirectResponse
     */

    public function update($id,Request $request)
    {
        $promo = Promo::findOrFail($id);
        $promo->title = $request->title;
        $promo->description = $request->description;
        $promo->date_from = Carbon::createFromFormat('d-m-Y', $request->date_from)->format('Y-m-d');
        $promo->date_to =  Carbon::createFromFormat('d-m-Y', $request->date_to)->format('Y-m-d');;
        $promo->update();

        foreach($request->promo as $promo_item) {
            if(isset($promo_item['id'])) {
                $promo_items = PromoItems::findOrFail($promo_item['id']);
            }else {
                $promo_items = new PromoItems;
            }
            $promo_items->promo_id = $promo->id;
            $promo_items->type = $promo_item['type'];
            if(isset($promo_item['target_id'])) {
                $promo_items->target_id = $promo_item['target_id'];
            }
            if(isset($promo_item['min_price'])) {
                $promo_items->min_price = $promo_item['min_price'];
            }
            if(isset($promo_item['id'])) {
                $promo_items->update();
            }else {
                $promo_items->save();
            }
            PromoItemProducts::where('promo_item_id',$promo_items->id)->delete();
            foreach($promo_item['product_ids'] as $product_id){
                $product = new PromoItemProducts;
                $product->promo_item_id = $promo_items->id;
                $product->product_id = $product_id;
                $product->save();
            }
        }

        return redirect()
                        ->route($this->variable.'.index')
                            ->with('success','Parameters Successful Changes');
    }

    /**
     * @param Promo $promo
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */

    public function destroy(Promo $promo)
    {
        $promo->items()->with('promoItems')->delete();
        $promo->items()->delete();
       $promo->delete();

               return  redirect()
                       ->route($this->variable.'.index')
                           ->with('massage','Successfully Removed');
    }
    public function getPromoItemForm(Request $request){
        if(isset($request->type)) {
            return view('admin.pages.promo.widgets.form')->with('type',$request->type)->with('count',$request->count)->with('promo',new Promo)->render();
        }
    }
}
