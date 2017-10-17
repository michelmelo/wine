<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
  public function wines()
  {
    return $this->belongsToMany(Wine::class);
  }

  public function customer()
  {
    return $this->belongsTo(Customer::class)->withDefault();
  }

  protected $fillable = ['customer_id', 'order_id'];
}
