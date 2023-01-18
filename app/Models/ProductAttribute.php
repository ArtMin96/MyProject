<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductAttribute extends Model
{
      /**
      * @var array
      */

    protected $guarded= [] ;

    protected $casts = [

        'value' => 'json',
    ];

    public function group()
    {
        return $this->hasOne(AttributeGroup::class,'id','attribute_group_id');
    }
    public function attribute_parent() {
        return $this->hasOne(Attribute::class,'id','attribute_id');
    }
    public function attribute_product_filter() {
        return $this->hasOne(ProductFilter::class,'id','attribute_id');
    }

}
