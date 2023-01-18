<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BlogCategory extends Model
{
      /**
      * @var array
      */

    protected $guarded= [] ;
    protected $casts = [
      'title' => 'json',
  ];


  
  public function setSlugAttribute($attribute)
  {

      $column_slug =  \Illuminate\Support\Str::slug($attribute);

     if(($this->where('slug',$column_slug)->count() > 0 and $this->getKey() == null)  or ($this->getKey() != null and $this->where('slug',$column_slug)->count() > 1)){

         $last_row_id = $this->orderBy('id','desc')->first()->id;

         $this->attributes['slug'] = $column_slug.$last_row_id;
     }
     else{
         $this->attributes['slug'] = $column_slug;
     }
  }

  public function posts()
  {
          return $this->belongsTo(Blog::class);
  }

}
