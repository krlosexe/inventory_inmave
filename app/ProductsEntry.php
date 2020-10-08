<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductsEntry extends Model
{
    protected $fillable = [
      "warehouse", "number_invoice","date_invoice", "taxes", "transport", "subtotal", "total_invoice","created_at"
    ];

    protected $table         = 'products_entry';
    public    $timestamps    = true;


    public function products()
    {
      return $this->hasMany('App\ProductsEntryItems', 'id_entry')
                    ->join('products', 'products.id', '=', 'product_entry_items.id_product')  
                    ->select(array('product_entry_items.*','products.id as id_product', 'products.description','products.code', 'products.presentation'));
    }


}
