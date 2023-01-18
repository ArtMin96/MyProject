<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductFilter extends Model
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
    public function attribute()
    {
        return $this->hasOne(Attribute::class,'id','attribute_id');
    }
    public function attributes()
    {
        return $this->belongsTo(Attribute::class,'attribute_id','id');
    }
}
