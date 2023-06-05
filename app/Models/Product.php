<?php

namespace App\Models;

use App\Models\Traits\Image;


use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use Image;
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'mst_products';
    protected $primaryKey = 'product_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */

    protected $fillable = [
        'product_name',
        'product_image',
        'product_price',
        'is_sales',
        'description',
    ];

    protected $casts = [
        'product_id' => 'string'
    ];

    public function getProductImageUrlAttribute()
    {
        return $this->generateUrl($this->product_image, true);
    }
}
