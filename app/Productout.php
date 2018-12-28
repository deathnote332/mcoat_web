<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class Productout extends Model
{
    protected $table = 'product_out';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'receipt_no','total', 'branch', 'printed_by','type'
    ];
}
