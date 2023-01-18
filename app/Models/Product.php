<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Product extends Model
{
      /**
      * @var array
      */

    protected $guarded= [] ;



    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function images()
    {
        return $this->morphMany(Image::class, 'imageable');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function sale()
    {
        return $this->hasOne(Sale::class,'product_id');
    }


    /**
     * @var string[]
     */
    protected $casts = [

        'title' => 'json',
        'description' =>'json',
        'how_to_use' =>'json',
        'additional' => 'json',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function components()
    {
        return $this->belongsToMany(Components::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function productByTag()
    {
        return $this->belongsToMany(Tag::class,'product_tags','product_id','tag_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function attributes()
    {
        return $this->belongsToMany(AttributeGroup::class,'product_attributes','product_id','attribute_group_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function single_attributes()
    {
        return $this->belongsToMany(Attribute::class,'product_attributes','product_id','attribute_id');
    }

    public function  attribute_values()
    {
        return $this->hasMany(ProductAttribute::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function crossSale()
    {
        return $this->belongsToMany(Product::class,'cross_sales','product_id','related_id');
    }

    public function crossSaleBack()
    {
        return $this->belongsToMany(Product::class,'cross_sales','related_id','product_id');
    }
   /**
     * This method returns a collection with all the tags related with this tag.
     * It is not a real relation, but emulates it.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function crossSaleBiDrectionalItems($product)
    {
        return $product->crossSale()->where('out_stock',0)->get()->merge($product->crossSaleBack()->where('out_stock',0)->get())->unique('id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function reviews() {
        return $this->hasMany(ProductReview::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */

    public function metatags()
    {
        return $this->morphMany(MetaTags::class, 'metatagable');
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class,'product_tags','product_id','tag_id');
    }
    public function orders()
    {
       return $this->hasMany(OrderHistory::class, 'product_id','id');
    }
    public function orders_count()
    {
       # return $this->morphMany(Tags::class, 'tagable');
    }
}
