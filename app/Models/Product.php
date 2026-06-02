<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name',
        'price',
        'desc',
        'category',
        'img',
        'is_new_arrival',
    ];
}