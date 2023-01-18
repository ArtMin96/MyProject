<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Blog extends Model
{
      /**
      * @var array
      */

    protected $guarded= [] ;
    protected $casts = [
      'title' => 'json',
      'content' => 'json',
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

  public function category()
  {
      return $this->hasOne(BlogCategory::class,'id','blog_category_id');
  }

  public function products()
  {
      return $this->belongsToMany(Product::class,'blogs_product','product_id','blog_id');
  }

  public function metatags()
  {
      return $this->morphMany(MetaTags::class, 'metatagable');
  }

  public function tags()
  {
      return $this->morphMany(Tags::class, 'tagable');
  }
}
