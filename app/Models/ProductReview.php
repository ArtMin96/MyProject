<?php

namespace App\Models;
use App\User;
use Illuminate\Database\Eloquent\Model;

class ProductReview extends Model
{
      /**
      * @var array
      */
    protected $table = "product_reviews";
    protected $guarded= [] ;

    public function product() {
      return $this->belongsTo(Product::class);
  }

  public function user() {
    return $this->belongsTo(User::class);
}
}
