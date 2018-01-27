<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Products extends Authenticatable
{

    protected $table = 'tblproducts';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'brand', 'category', 'description','code','unit','quantity','quantity_1','unit_price','status'
    ];


}
