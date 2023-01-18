<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PromoItemProducts extends Model
{
      /**
      * @var array
      */

    protected $guarded= [] ;
    public function product() {
        return $this->hasOne(Product::class,'id','product_id');
    }
}
