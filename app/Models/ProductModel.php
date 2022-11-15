<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductModel extends Model
{
    use HasFactory;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    public $timestamps = false;
    protected $table = 'products';
    protected $fillable = [
        'id',
        'title',
        'product_id',
        'image',
        'sku',
        'barcode',
        'create_at',
        'update_at',
    ];

}