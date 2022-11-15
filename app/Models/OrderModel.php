<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderModel extends Model
{
    use HasFactory;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    public $timestamps = false;
    protected $table = 'order_details';
    protected $fillable = [
        'id',
        'note',
        'order_no',
        'order_status',
        'product_id',
        'risk',
        'ship_time',
        'country',
        'create_at',
        'update_at',
    ];

}
