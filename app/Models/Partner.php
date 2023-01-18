<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Partner extends Model
{
      /**
      * @var array
      */

    protected $guarded= [] ;


    protected $casts = [

        'title' => 'json',
        'description' =>'json'
    ];


    public function setOrderByAttribute($attribute)
    {
        if($attribute == null)
        {
            $last = $this->orderBy('order_by','desc')->first();
            if($last == null)
            {
                $this->attributes['order_by'] = 1;
            }
            else{
                $this->attributes['order_by'] = $last->order_by + 1;
            }
        }
        else{
            $this->attributes['order_by'] = $attribute;
        }
    }
}
